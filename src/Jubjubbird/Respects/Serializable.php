<?php declare(strict_types=1);

namespace Jubjubbird\Respects;

interface Serializable
{
    /**
     * @param array $data
     * @return static The object instance
     */
    static function deserialize(array $data);

    /**
     * @return array
     */
    function serialize(): array;
}
