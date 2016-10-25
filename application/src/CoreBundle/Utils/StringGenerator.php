<?php

namespace CoreBundle\Utils;

class StringGenerator implements StringGeneratorInterface
{
    /**
     * @var string
     */
    protected static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * {@inheritdoc}
     */
    public static function quickGenerate($length = 16)
    {
        $charactersLength = strlen(static::$characters);
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= static::$characters[rand(0, $charactersLength - 1)];
        }

        return $str;
    }

    /**
     * {@inheritdoc}
     */
    public static function secureGenerate($length = 16)
    {
        $str = '';
        $max = mb_strlen(static::$characters, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= static::$characters[random_int(0, $max)];
        }

        return $str;
    }

    /**
     * {@inheritdoc}
     */
    public static function uniqueGenerate($length = 16, $characters = null)
    {
        $token = "";
        if (is_null($characters)) {
            $characters = static::$characters;
        }
        $max = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[static::crypto_rand_secure(0, $max)];
        }

        return $token;
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return mixed
     */
    private static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;

        if ($range < 1) return $min; // not so random...

        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }
}
