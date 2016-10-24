<?php

namespace CoreBundle\Utils;

interface StringGeneratorInterface
{
    /**
     * Generate a "random" alpha-numeric string.
     *
     * Should not be considered sufficient for cryptography, etc.
     *
     * @param int $length
     *
     * @return string
     */
    public static function quickGenerate($length = 16);

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length How many characters do we want?
     *
     * @return string
     */
    public static function secureGenerate($length = 16);

    /**
     * Generate a unique random string.
     *
     * @param int $length How many characters do we want?
     * @param string $characters
     *
     * @return string
     */
    public static function uniqueGenerate($length = 16, $characters = null);
}
