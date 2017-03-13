<?php

use BespokeSupport\Reg\Reg;

/**
 * Class RegClassTest.
 */
class RegClassTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $reg = Reg::create('AB11 ABC');
        $reg = Reg::create($reg);
        $this->assertNotNull($reg);
    }
}
