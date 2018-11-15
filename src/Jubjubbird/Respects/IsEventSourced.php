<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\IdentifiesAggregate;

/**
 * An AggregateRoot, that can be reconstituted from an AggregateHistory.
 */
interface IsEventSourced
{
    /**
     * @param AggregateHistory $aggregateHistory
     * @return RecordsEvents
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory): RecordsEvents;

    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId(): IdentifiesAggregate;
}
