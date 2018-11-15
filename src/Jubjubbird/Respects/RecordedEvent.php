<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\IdentifiesAggregate;
use DateTimeInterface;

/**
 * A wrapper around a plain domain event that captures the timestamp of its occurrence and the ID of the emitting
 * aggregate. The wrapped event becomes the “payload” of this instance.
 */
class RecordedEvent implements DomainEvent
{
    /** @var IdentifiesAggregate */
    private $aggregateId;
    /** @var Serializable */
    private $payload;
    /** @var DateTimeInterface */
    private $recordedOn;

    /**
     * @param Serializable $payload
     * @param IdentifiesAggregate $aggregateId
     * @param DateTimeInterface $recordedOn
     */
    public function __construct(Serializable $payload, IdentifiesAggregate $aggregateId, DateTimeInterface $recordedOn)
    {
        $this->payload = $payload;
        $this->aggregateId = $aggregateId;
        $this->recordedOn = $recordedOn;
    }

    /**
     * The Aggregate this event belongs to.
     * @return IdentifiesAggregate
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return Serializable
     */
    public function getPayload(): Serializable
    {
        return $this->payload;
    }

    /**
     * @return DateTimeInterface
     */
    public function getRecordedOn(): DateTimeInterface
    {
        return $this->recordedOn;
    }
}
