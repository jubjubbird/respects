<?php declare(strict_types=1);

namespace Buttercup\Protects\Tests;

use Buttercup\Protects\AggregateHistory;
use Buttercup\Protects\AggregateRoot;
use Buttercup\Protects\IdentifiesAggregate;
use Jubjubbird\Respects\ApplyCallsWhenMethod;
use Jubjubbird\Respects\RecordsEventsForBusinessMethods;

class BasketV5 implements AggregateRoot
{
    use ApplyCallsWhenMethod, RecordsEventsForBusinessMethods;
    private $id;
    private function __construct(BasketId $id) {$this->id = $id;}
    public static function pickUp(BasketId $id) {
        $basket = new self($id);
        $basket->recordThat(new BasketWasPickedUp($id));
        return $basket;
    }
    public static function reconstituteFrom(AggregateHistory $aggregateHistory)
    {
        $customer = new self(BasketId::fromString(strval($aggregateHistory->getAggregateId())));
        foreach ($aggregateHistory as $event) {$customer->apply($event);}
        return $customer;
    }
    public function getAggregateId(): IdentifiesAggregate{return $this->id;}
    private function whenBasketWasPickedUp(BasketWasPickedUp $event) {}
}

$basketId = BasketId::generate();
$basket = BasketV5::pickUp($basketId);
$recordedEvents = $basket->getRecordedEvents();
$basket->clearRecordedEvents();
$reconstituted = BasketV5::reconstituteFrom(new AggregateHistory($basketId, $recordedEvents->toArray()));
it('should have recorded an event', $recordedEvents->count() === 1);
it('should reconstitute when using traits', $basket == $reconstituted);
