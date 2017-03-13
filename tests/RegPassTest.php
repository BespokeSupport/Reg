<?php

use BespokeSupport\Reg\Reg;

/**
 * Class RegTest.
 */
class RegTest extends \PHPUnit_Framework_TestCase
{
    public $regs = [

        // consists of two letters, followed by two numbers and three letters at the end
        Reg::STYLE_CURRENT => [
            'AA51AAA',
        ],

        //one letter followed by one, two or three numbers, and three letters at the end
        Reg::STYLE_PREFIX => [
            'A1AAA',
            'A11AAA',
            'A111AAA',
        ],

        //three letters, followed by one, two or three numbers, and a single letter at the end
        Reg::STYLE_SUFFIX => [
            'AAA1A',
            'AAA11A',
            'AAA111A',
        ],

        // It can consist of up to four numbers followed by up to three letters, or vice versa .
        // The maximum number of characters is six
        Reg::STYLE_DATELESS_NUMBER => [
            '1A',
            '1AA',
            '1AAA',
            '11A',
            '11AA',
            '11AAA',
            '111A',
            '111AA',
            '111AAA',
            '1111A',
            '1111AA',
        ],

        // It can consist of up to four numbers followed by up to three letters, or vice versa .
        // The maximum number of characters is six
        Reg::STYLE_DATELESS_LETTER => [
            'A1',
            'A11',
            'A111',
            'AA1',
            'AA11',
            'AA111',
            'AA1111',
            'AAA1',
            'AAA11',
            'AAA111',
            'AAZ1111',
        ],
    ];

    public function testBasic()
    {
        foreach ($this->regs as $style => $registrations) {
            foreach ($registrations as $registration) {
                $reg = Reg::create($registration);
                $this->assertEquals($registration, $reg->reg);

                switch ($style) {
                    case Reg::STYLE_CURRENT:
                        $this->assertTrue($reg->isStyleCurrent());
                        break;
                    case Reg::STYLE_PREFIX:
                        $this->assertTrue($reg->isStylePrefix());
                        break;
                    case Reg::STYLE_SUFFIX:
                        $this->assertTrue($reg->isStyleSuffix());
                        break;
                    case Reg::STYLE_DATELESS_LETTER:
                        $this->assertTrue($reg->isStyleDateless());
                        break;
                    case Reg::STYLE_DATELESS_NUMBER:
                        $this->assertTrue($reg->isStyleDateless());
                        break;
                }
            }
        }
    }

    public function testComplete()
    {
        $plates = require dirname(__FILE__).'/_testPlates.php';

        foreach ($plates as $registration => $data) {
            $reg = Reg::create($registration);
            if (!$data['registration']) {
                $this->assertNull($reg);
                continue;
            }

            $this->assertNotNull($reg);
            $this->assertEquals($data['registration'], $reg->getFormatted());
            $this->assertEquals($reg.$data['style'], $reg.$reg->style);
        }
    }
}
