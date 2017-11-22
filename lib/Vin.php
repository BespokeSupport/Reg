<?php

namespace BespokeSupport\Reg;

/**
 * Class Vin
 * @package BespokeSupport\Reg
 */
class Vin
{
    const REGEX = '/(?i)(?<VIN>[A-Z0-9^IOQ]{11}\d{6})/';
    public $vin = '';

    public function __construct($ident)
    {
        $this->vin = $ident;
    }

    public static function clean($ident)
    {
        $ident = strtoupper($ident);

        $ident = preg_replace('/[\s]/', '', $ident);

        return $ident;
    }

    public static function create($ident)
    {
        if (is_a($ident, self::class)) {
            return $ident;
        }

        $ident = self::clean($ident);

        if (self::match($ident)) {
            return new self($ident);
        } else {
            return null;
        }
    }

    public static function match($ident)
    {
        return preg_match(self::REGEX, $ident);
    }

    public function __toString()
    {
        return $this->vin;
    }
}
