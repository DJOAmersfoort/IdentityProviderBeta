<?php
declare(strict_types=1);

namespace IdpBundle\Enum;

use MyCLabs\Enum\Enum;

/**
 * Adds a method to get the human-readable name
 *
 * @author Roelof Roos <github@roelof.io>
 */
abstract class HumanReadableEnum extends Enum
{
    protected static $humanReadableValues;

    /**
     * Returns a human-readable value of this enum.
     * @return string
     */
    public function getHumanReadable() : string
    {
        $key = $this->getKey();

        if (empty($key)) {
            return '';
        }

        if (!empty(static::humanReadableValues) &&
            is_array(static::humanReadableValues) &&
            array_key_exists(static::humanReadableValues, $key)) {
            return self::humanReadableValues[$key];
        }

        return ucfirst(strtolower($key));
    }
}
