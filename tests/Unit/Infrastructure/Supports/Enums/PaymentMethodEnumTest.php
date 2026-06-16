<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Supports\Enums;

use App\Infrastructure\Supports\Enums\Exception\InvalidPaymentMethodException;
use App\Infrastructure\Supports\Enums\PaymentMethodEnum;
use PHPUnit\Framework\TestCase;

class PaymentMethodEnumTest extends TestCase
{
    public function testTryFromValueReturnsValidEnum(): void
    {
        $this->assertEquals(PaymentMethodEnum::PIX, PaymentMethodEnum::tryFromValue('pix'));
        $this->assertEquals(PaymentMethodEnum::DEBIT, PaymentMethodEnum::tryFromValue('debit'));
        $this->assertEquals(PaymentMethodEnum::CREDIT_CARD, PaymentMethodEnum::tryFromValue('credit_card'));
        $this->assertEquals(PaymentMethodEnum::CREDIT_CARD_INSTALLMENTS, PaymentMethodEnum::tryFromValue('credit_card_installments'));
    }

    public function testTryFromValueThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(InvalidPaymentMethodException::class);
        PaymentMethodEnum::tryFromValue('boleto');
    }
}
