<?php
// So far, we've kept our `Basket` aggregate in memory at all times. Often, we'll want to persist it, and load it back
// in memory later for further use. As you've learned, our Aggregate is not represented by its state, but by its
// history of Domain Events. So loading our Aggregate back in history, simply involves reconstituting it from all the
// events that it has recorded previously. This concept is called **Event Sourcing**. The events are the single source
// of elements that make up the Aggregate.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\IdentifiesAggregate;
use Buttercup\Protects\Tests\Misc\ProductId;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Jubjubbird\Respects\AggregateHistory;
use Jubjubbird\Respects\DomainEvents;
use Jubjubbird\Respects\IsEventSourced;
use Jubjubbird\Respects\RecordedEvent;
use Jubjubbird\Respects\RecordsEvents;
use Jubjubbird\Respects\Serializable;
use function Verraes\ClassFunctions\short;

$test = function() {
    $basketId = BasketId::generate();
    $basket = BasketV4::pickUp($basketId);
    $productId = new ProductId('TPB1');
    $basket->addProduct($productId, "The Princess Bride");
    $basket->addProduct(new ProductId('TPB2'), "The book");
    $basket->removeProduct($productId);
    $events = $basket->getRecordedEvents();
    $basket->clearRecordedEvents();
    // Here we would store the events, and later retrieve them from that store.
    $reconstitutedBasket = BasketV4::reconstituteFrom(
        new AggregateHistory($basketId, (array) $events)
    );

    it("should be the same after reconstitution",
        $basket == $reconstitutedBasket
    );
};

// We declare that `Basket` `IsEventSourced`, in other words, that we can rebuild it from it's events.
final class BasketV4 implements RecordsEvents, IsEventSourced
{
    // The `IsEventSourced` interface requires us to implement a `reconstituteFrom(AggregateHistory)` static method.
    // This method is like an alternative constructor. Recall that we had `pickUp()` earlier, which was the constructor
    // for calling the Basket into life. `Reconstitute` implies that this Basket already exists conceptually, but that
    // we suspended it temporarily by unloading it from memory. The difference is subtle but important.
    /**
     * @param AggregateHistory $aggregateHistory
     * @return RecordsEvents
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory): RecordsEvents
    {
        // `AggregateHistory` is a list of chronological `DomainEvents` for a single Aggregate instance. Let's start by
        // fetching its identifier.
        $basketId = $aggregateHistory->getAggregateId();
        // We instantiate the Basket object. (As you recall, the constructor is private.)
        $basket = new BasketV4(new BasketId($basketId));


        foreach($aggregateHistory as $event) {
            // As you saw earlier, our Aggregate keeps state, to protect invariants. We need to rebuild this state from
            // the events in the `AggregateHistory`. But there's a problem: we can't call methods like `pickUp()`,
            // `addProduct()`, and `removeProduct()`, because these would call `recordThat()`. That would cause the
            // events to be recorded a second time.

            // The trick is to separate the logic that applies events to the state. We'll call a new private method:
            $basket->apply($event);
        }

        // Finally we return the newly reconstituted Basket.
        return $basket;
    }

    // Inside each `whenEventName()` method, we manipulate the state. The first one is not very interesting.
    private function whenBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->productCount = 0;
        $this->products = [];
    }

    private function whenProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        // Remember that all of this code used to be in `addProduct()`
        $productId = $event->getProductId();
        if(!$this->productIsInBasket($productId)) {
            $this->products[(string) $productId] = 0;
        }

        ++$this->products[(string) $productId];
        ++$this->productCount;
    }

    private function whenProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        --$this->products[(string) $event->getProductId()];
        --$this->productCount;
    }

    public static function pickUp(BasketId $basketId)
    {
        $basket = new BasketV4($basketId);
        $basket->recordThat(new BasketWasPickedUp($basketId));
        // We moved the code that was on this line, to the `whenBasketWasPickedUp()` method.
        return $basket;
    }
    public function addProduct(ProductId $productId, $name)
    {
        $this->guardProductLimit();
        $this->recordThat(
            new ProductWasAddedToBasket($this->basketId, $productId, $name)
        );
        // The code that used to be here, is now in `whenProductWasAddedToBasket().
    }

    public function removeProduct(ProductId $productId)
    {
        if(! $this->productIsInBasket($productId)) {
            return;
        }

        $this->recordThat(
            new ProductWasRemovedFromBasket($this->basketId, $productId)
        );
        // And this code moved to `whenProductWasRemovedFromBasket()`.
    }

    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId(): IdentifiesAggregate
    {
        return $this->basketId;
    }

    /**
     * Delegate the application of the event to the appropriate when... method, e. g. a VisitorHasLeft event will be
     * processed by the (private) method whenVisitorHasLeft(VisitorHasLeft $event): void
     * @param RecordedEvent $event
     */
    protected function apply(RecordedEvent $event): void
    {
        $method = 'when' . short($event->getPayload());
        $this->$method($event->getPayload(), $event);
    }

    private function recordThat(Serializable $domainEvent)
    {
        $now = null;
        try {
            $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        } catch (Exception $e) {
            // cannot happen
        }
        $recordedEvent = new RecordedEvent($domainEvent, $this->basketId, $now);
        $this->latestRecordedEvents[] = $recordedEvent;
        $this->apply($recordedEvent);
    }

    // no changes here
    private $products;
    private $productCount;
    private $basketId;
    private $latestRecordedEvents = [];
    private function productIsInBasket(ProductId $productId) { return array_key_exists((string) $productId, $this->products) && $this->products[(string)$productId] > 0; }
    private function guardProductLimit() { if ($this->productCount >= 3) { throw new BasketLimitReached; } }
    private function __construct(BasketId $basketId) { $this->basketId = $basketId; }
    public function getRecordedEvents(): DomainEvents { return new DomainEvents($this->latestRecordedEvents); }
    public function clearRecordedEvents(): void { $this->latestRecordedEvents = []; }

}

$test();