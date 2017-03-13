<?php

use BespokeSupport\Reg\Reg;

/**
 * Class RegFailsTest.
 */
class RegFailsTest extends \PHPUnit_Framework_TestCase
{
    public function testRegs()
    {
        $plates = require dirname(__FILE__).'/_testPlates.php';

        foreach ($plates as $registration => $data) {
            if ($data['registration']) {
                continue;
            }
            $reg = Reg::create($registration);
            $this->assertNull($reg);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBlank()
    {
        (new Reg(''));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTooLong()
    {
        (new Reg('AAA11111AAA'));
    }
}
