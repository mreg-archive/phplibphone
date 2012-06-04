<?php
namespace itbz\phplibphone;
use itbz\phplibphone\Swe\PhoneArea as SwePhoneArea;


class PhoneAreaTest extends \PHPUnit_Framework_TestCase
{

    function testVoid()
    {
    }


/*    public function testSwePhoneArea()
    {
        $areas = new SwePhoneArea();

        // Invalid cc
        $desc = $areas->fetchArea('45', '8');
        $this->assertEquals('', $desc);

        // Invalid ndc
        $desc = $areas->fetchArea('46', '88');
        $this->assertEquals('', $desc);

        // Valid
        $desc = $areas->fetchArea('46', '8');
        $this->assertEquals('Stockholm', $desc);
    }*/

}
