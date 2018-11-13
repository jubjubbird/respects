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
     * The Aggregate this event belongs to.
     * @return IdentifiesAggregate
     */
    public function getAggregateId();

    public function getPayload(): Serializable;

    /**
     * @return DateTimeInterface
     */
    public function getRecordedOn(): DateTimeInterface;
}
