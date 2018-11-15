<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\IdentifiesAggregate;
use Exception;

class AggregateHistory extends DomainEvents
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * AggregateHistory constructor.
     * @param IdentifiesAggregate $aggregateId
     * @param DomainEvent[] $domainEvents
     * @throws CorruptAggregateHistory
     * @throws Exception when one of the events is not a DomainEvent.
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $domainEvents)
    {
        /** @var $event DomainEvent */
        foreach ($domainEvents as $event) {
            if (!$event->getAggregateId()->equals($aggregateId)) {
                throw new CorruptAggregateHistory;
            }
        }
        parent::__construct($domainEvents);
        $this->aggregateId = $aggregateId;
    }

    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }
}
