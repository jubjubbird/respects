<?php declare(strict_types=1);

namespace Jubjubbird\TakesOff;

use Buttercup\Protects\IdentifiesAggregate;
use Jubjubbird\Respects\AggregateHistory;
use Jubjubbird\Respects\AggregateRoot;
use Jubjubbird\Respects\ApplyCallsWhenMethod;
use Jubjubbird\Respects\RecordsEvents;
use Jubjubbird\Respects\RecordsEventsForBusinessMethods;

class Basket implements AggregateRoot
{
    use ApplyCallsWhenMethod, RecordsEventsForBusinessMethods;
    /** @var bool Used only for the test spec. */
    public $pickedUp = false;
    private $id;

    private function __construct(BasketId $id)
    {
        $this->id = $id;
    }

    public static function pickUp(BasketId $id)
    {
        $basket = new self($id);
        $basket->recordThat(new BasketWasPickedUp($id));
        return $basket;
    }

    public static function reconstituteFrom(AggregateHistory $aggregateHistory): RecordsEvents
    {
        $customer = new self(BasketId::fromString(strval($aggregateHistory->getAggregateId())));
        foreach ($aggregateHistory as $event) {
            $customer->apply($event);
        }
        return $customer;
    }

    public function getAggregateId(): IdentifiesAggregate
    {
        return $this->id;
    }

    private function whenBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->pickedUp = true;
    }
}
