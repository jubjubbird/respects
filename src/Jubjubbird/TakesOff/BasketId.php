<?php declare(strict_types=1);

namespace Jubjubbird\TakesOff;

use Buttercup\Protects\IdentifiesAggregate;

final class BasketId implements IdentifiesAggregate
{
    private $basketId;
    /**
     * @param string $basketId
     */
    public function __construct($basketId)
    {
        $this->basketId = (string)$basketId;
    }

    public static function fromString($string)
    {
        return new BasketId($string);
    }

    public static function generate()
    {
        $badSampleUuid = md5(uniqid());
        return new BasketId($badSampleUuid);
    }

    public function __toString()
    {
        return $this->basketId;
    }

    public function equals(IdentifiesAggregate $other)
    {
        return
            $other instanceof BasketId
            && $this->basketId == $other->basketId;
    }
}
