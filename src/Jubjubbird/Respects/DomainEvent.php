<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\IdentifiesAggregate;
use DateTimeInterface;

/**
 * Something that happened in the past, that is of importance to the business.
 */
interface DomainEvent
{
    /**
     * @return IdentifiesAggregate The Aggregate this event belongs to.
     */
    public function getAggregateId(): IdentifiesAggregate;

    /**
     * @return Serializable The wrapped event data/
     */
    public function getPayload(): Serializable;

    /**
     * @return DateTimeInterface When the event was recorded.
     */
    public function getRecordedOn(): DateTimeInterface;
}
