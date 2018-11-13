<?php declare(strict_types=1);

namespace Jubjubbird\TakesOff;

use Buttercup\Protects\IdentifiesAggregate;

final class BasketId implements IdentifiesAggregate
{
    private $basketId;
    // You are free to extend from an abstract class, and to implement the constructor as you wish. For example, you
    // could add some validation in there.
    /**
     * @param string $basketId
     */
    public function __construct($basketId)
    {
        $this->basketId = (string) $basketId;
    }

    public static function fromString($string)
    {
        return new BasketId($string);
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

    // A nice convention is to have a static generate() method to create a random BasketId. The example here is a bad
    // way to generate a UUID, so don't do this in production. Use something like https://github.com/ramsey/uuid
    public static function generate()
    {
        $badSampleUuid = md5(uniqid());
        return new BasketId($badSampleUuid);
    }
}
