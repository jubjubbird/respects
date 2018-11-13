<?php

namespace Buttercup\Protects;

use Exception;

final class AggregateHistory extends DomainEvents
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * AggregateHistory constructor.
     * @param IdentifiesAggregate $aggregateId
     * @param array $events
     * @throws CorruptAggregateHistory
     * @throws Exception when one of the events is not a DomainEvent.
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $events)
    {
        /** @var $event DomainEvent */
        foreach($events as $event) {
            if(!$event->getAggregateId()->equals($aggregateId)) {
                throw new CorruptAggregateHistory;
            }
        }
        parent::__construct($events);
        $this->aggregateId = $aggregateId;
    }

    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return AggregateHistory
     */
    public function append(DomainEvent $domainEvent)
    {
        throw new \Exception("@todo  Implement append() method.");
    }
}