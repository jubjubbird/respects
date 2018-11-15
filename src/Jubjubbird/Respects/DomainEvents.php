<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\ImmutableArray;

class DomainEvents extends ImmutableArray
{
    /**
     * @param $item
     * @throws TypeConstraintViolation when the type of item is not accepted.
     * @return void
     */
    protected function guardType($item): void
    {
        if (!($item instanceof DomainEvent)) {
            throw new TypeConstraintViolation;
        }
    }
}
