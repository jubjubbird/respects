<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use function Verraes\ClassFunctions\short;

trait ApplyCallsWhenMethod
{
    /**
     * Delegate the application of the event to the appropriate when... method, e. g. a VisitorHasLeft event will be
     * processed by the (private) method whenVisitorHasLeft(VisitorHasLeft $event): void
     * @param DomainEvent $event
     */
    protected function apply(DomainEvent $event): void
    {
        $method = 'when' . short($event->getPayload());
        $this->$method($event->getPayload(), $event);
    }
}
