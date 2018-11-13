<?php declare(strict_types=1);

namespace Jubjubbird\TakesOff;

use Jubjubbird\Respects\Serializable;

final class BasketWasPickedUp implements Serializable
{
    private $basketId;

    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * @param array $data
     * @return static The object instance
     */
    static function deserialize(array $data)
    {
        return new self(BasketId::fromString($data[0]));
    }

    public function getAggregateId()
    {
        return $this->basketId;
    }

    /**
     * @return array
     */
    function serialize(): array
    {
        return [strval($this->basketId)];
    }
}
