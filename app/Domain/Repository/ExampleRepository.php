<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use stdClass;

class ExampleRepository implements RepositoryInterface
{
    public function get(int $id): object
    {
        $object = new stdClass();
        $object->id = $id;
        $object->name = 'Teste da Silva'.$id;

        return $object;
    }
}
