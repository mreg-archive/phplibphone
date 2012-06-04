<?php
namespace itbz\phplibphone;
use itbz\phplibphone\Swe\PhoneCarrier as SwePhoneCarrier;


class PhoneCarrierTest extends \PHPUnit_Framework_TestCase
{

    public function testSwePhoneCarrier()
    {
        $carriers = new SwePhoneCarrier();
        
        // Invalid cc
        $c = $carriers->fetchCarrier('45', '8', '7740212');
        $this->assertEquals('', $c);

        // Invalid sn
        #$c = $carriers->fetchCarrier('46', '8', '1111');
        #$this->assertEquals('', $c);

        // Valid
        #$c = $carriers->fetchCarrier('46', '8', '7740212');
        #$this->assertEquals('TeliaSonera Sverige AB', $c);
    }

}
