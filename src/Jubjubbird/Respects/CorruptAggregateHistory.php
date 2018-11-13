<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

use Buttercup\Protects\ButtercupProtectsException;
use Exception;

class CorruptAggregateHistory extends Exception implements ButtercupProtectsException
{
}
