<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Models\Order;
use App\Domain\Payment\PaymentCalculator;
use App\Domain\Repository\ShoppingCartItemRepositoryInterface;
use App\Infrastructure\Supports\Enums\OrderStatusEnum;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use App\Infrastructure\Supports\Enums\ShoppingCartStatusEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShoppingCartService
{
    public function __construct(
        private ShoppingCartItemRepositoryInterface $repository,
        private PaymentCalculator $paymentCalculator,
    ) {
    }

    public function getShoppingCartDetails(UuidInterface $userId): array
    {
        $items = $this->findItemsByUser($userId);

        $totalPrice = $this->calculateTotalPrice($items);

        return [
            'items' => $items,
            'totalPrice' => $totalPrice,
        ];
    }

    public function finish(UuidInterface $userId, Request $request): Order
    {
        $items = $this->findItemsByUser($userId);

        if (true === $items->isEmpty()) {
            throw new LogicException('Shopping cart is empty');
        }

        $discount = $request->get('discount') ?? 0;
        $originalPrice = $this->calculateTotalPrice($items);
        $paymentMethod = PaymentMethodEnum::tryFromValue($request['payment_method']);
        $strategy = $this->paymentCalculator->getStrategy($paymentMethod);

        if ($discount > ($originalPrice * 0.1)) {
            throw new BadRequestException('The discount must not be greater than 10% of the total price');
        }

        $order = new Order();
        $order->setId(Uuid::uuid4());
        $order->setUserId($userId);
        $order->setStatus(OrderStatusEnum::PENDING);
        $order->setDiscount($discount);
        $order->setOriginalPrice($originalPrice);
        $order->setPaymentMethod($paymentMethod);
        $order->setTotalPrice($strategy->calculateFinalPrice($originalPrice, $discount));

        $items = $items->map(fn ($item) => $item['id']);

        DB::transaction(function () use ($order, $items): void {
            $order->saveOrFail();
            $order->items()->attach($items);

            $this->repository->updateManyStatus(
                array_values($items->toArray()),
                ShoppingCartStatusEnum::FINISHED
            );
        });

        // TODO:
        // dispatch job to payment
        return $order;
    }

    private function calculateTotalPrice(Collection $items): float
    {
        return array_sum(
            array_map(
                fn (array $item) => $item['totalPrice'],
                $items->toArray()
            )
        );
    }

    private function findItemsByUser(UuidInterface $userId): Collection
    {
        return $this->repository->findBy([
            'user_id' => $userId,
            'status' => ShoppingCartStatusEnum::AWAITING,
        ]);
    }

    public function remove(UuidInterface $userId): void
    {
        $items = $this->repository->findBy([
            'shopping_carts.user_id' => $userId->toString(),
            'shopping_carts.status' => ShoppingCartStatusEnum::AWAITING->value,
        ]);

        if (null === empty($items)) {
            throw new NotFoundHttpException();
        }

        $this->repository->removeMany($items);
    }
}
