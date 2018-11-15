<?php declare(strict_types=1);

namespace spec\Jubjubbird\TakesOff;

use DateTimeImmutable;
use Jubjubbird\Respects\AggregateHistory;
use Jubjubbird\Respects\RecordedEvent;
use Jubjubbird\TakesOff\Basket;
use Jubjubbird\TakesOff\BasketId;
use Jubjubbird\TakesOff\BasketWasPickedUp;
use PhpSpec\ObjectBehavior;

class BasketSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Basket::class);
    }

    function it_reconstitutes_from_an_aggregate_history()
    {
        $basketId = BasketId::generate();
        $aggregateHistory = new AggregateHistory(
            $basketId,
            [
                new RecordedEvent(new BasketWasPickedUp($basketId), $basketId, new DateTimeImmutable())
            ]
        );
        $this->beConstructedThroughReconstituteFrom($aggregateHistory);
        $this->getRecordedEvents()->shouldHaveCount(0);
        $this->pickedUp->shouldBe(true);
    }

    function it_reconstitutes_to_an_invalid_state_with_an_empty_aggregate_history()
    {
        $basketId = BasketId::generate();
        $aggregateHistory = new AggregateHistory($basketId, []);
        $this->beConstructedThroughReconstituteFrom($aggregateHistory);
        $this->getRecordedEvents()->shouldHaveCount(0);
        $this->pickedUp->shouldBe(false);
    }
}
