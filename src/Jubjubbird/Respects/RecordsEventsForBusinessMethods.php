<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\IdentifiesAggregate;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

trait RecordsEventsForBusinessMethods
{
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    /**
     * Clears the record of new Domain Events. This doesn't clear the history of the object.
     * @return void
     */
    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }

    /**
     * @return IdentifiesAggregate
     */
    abstract public function getAggregateId(): IdentifiesAggregate;

    /**
     * Get all the Domain Events that were recorded since the last time it was cleared, or since it was
     * restored from persistence. This does not include events that were recorded prior.
     * @return DomainEvents
     * @throws Exception when one of the events is not a DomainEvent.
     */
    public function getRecordedEvents(): DomainEvents
    {
        return new DomainEvents($this->recordedEvents);
    }

    /**
     * The trait ApplyCallWhenMethod may be used instead of implementing this method.
     * @param DomainEvent $event
     * @return mixed
     */
    abstract protected function apply(DomainEvent $event);

    /**
     * Records the first occurrence of this event from the method that caused it.
     * @param Serializable $event
     */
    protected function recordThat(Serializable $event): void
    {
        $now = null;
        try {
            $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        } catch (Exception $e) {
            // cannot happen
        }
        $recordedEvent = new RecordedEvent($event, $this->getAggregateId(), $now);
        $this->apply($recordedEvent);
        $this->recordedEvents[] = $recordedEvent;
    }
}
