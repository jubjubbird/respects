<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

interface AggregateRoot extends RecordsEvents, IsEventSourced
{
}
