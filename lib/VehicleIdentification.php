<?php

namespace BespokeSupport\Reg;

/**
 * Class VehicleIdentification
 * @package BespokeSupport\Reg
 */
class VehicleIdentification
{
    /**
     * @param $ident
     *
     * @return Reg|Vin|null
     */
    public static function create($ident)
    {
        return Reg::create($ident) ?? Vin::create($ident);
    }
}
