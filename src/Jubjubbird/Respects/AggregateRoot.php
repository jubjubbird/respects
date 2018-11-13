<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\RecordsEvents;

interface AggregateRoot extends RecordsEvents, IsEventSourced
{
}
