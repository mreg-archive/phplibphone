<?php
namespace iio\phplibphone;

use iio\phplibphone\Library\Countries;
use iio\phplibphone\Library\AreasSeSv;
use iio\localefacade\LocaleFacade;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAreaCode()
    {
        $number = new Number(new EmptyLibrary());
        $number->setNationalDestinationCode('8');
        $this->assertEquals('08', $number->getAreaCode());
    }

    public function testGetE164()
    {
        $number = new Number(new EmptyLibrary());
        $this->assertEquals('', $number->getE164());
        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals('+4687740212', $number->getE164());
    }

    public function testReset()
    {
        $number = new Number(new EmptyLibrary(), '46');
        $number->setNationalDestinationCode('8');
        $number->reset();
        $this->assertEquals(
            '+46',
            $number->getE164(),
            'Reset to default country code'
        );
    }

    public function testGetInternationalFormat()
    {
        $number = new Number(new EmptyLibrary());

        $this->assertEquals('', $number->getInternationalFormat());

        $number->setCountryCode('46');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals(
            '+46 774 02 12',
            $number->getInternationalFormat(),
            'No extra space when ndc is missing'
        );

        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals(
            '+46 8 774 02 12',
            $number->getInternationalFormat()
        );
    }

    public function testGetNationalFormat()
    {
        $number = new Number(new EmptyLibrary());

        $this->assertEquals('', $number->getNationalFormat());

        $number->setSubscriberNumber('7740212');
        $this->assertEquals('774 02 12', $number->getNationalFormat());

        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals('08-774 02 12', $number->getNationalFormat());
    }

    public function testFormat()
    {
        $number = new Number(new EmptyLibrary(), '46');

        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals('08-774 02 12', $number->format());

        $number->setCountryCode('45');
        $this->assertEquals('+45 8 774 02 12', $number->format());

    }

    public function testIsValid()
    {
        $number = new Number(new EmptyLibrary());
        $this->assertTrue(!$number->isValid());
        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertTrue($number->isValid());

        $number->setSubscriberNumber('7740212123456');
        $this->assertFalse($number->isValid());
    }

    public function testGetCountry()
    {
        $countryLib = $this->getMock(
            '\iio\phplibphone\EmptyLibrary',
            array('lookup')
        );

        $countryLib->expects($this->once())
                   ->method('lookup')
                   ->with('46')
                   ->will($this->returnValue('Sweden'));

        $number = new Number($countryLib);

        $number->setCountryCode('46');
        $this->assertEquals('Sweden', $number->getCountry());
    }

    public function testGetCarrier()
    {
        $number = new Number(new EmptyLibrary());

        $carrierLib = $this->getMock(
            '\iio\phplibphone\EmptyCarrierLibrary',
            array('getCountryCode', 'lookup')
        );

        $carrierLib->expects($this->once())
                   ->method('getCountryCode')
                   ->will($this->returnValue(46));

        $carrierLib->expects($this->once())
                   ->method('lookup')
                   ->with('8', '7740212')
                   ->will($this->returnValue('Telia'));

        $number->setCarrierLib($carrierLib);

        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals('Telia', $number->getCarrier());

        $number->setCountryCode('45');
        $this->assertEquals('', $number->getCarrier());
    }

    public function testGetArea()
    {
        $number = new Number(new EmptyLibrary());

        $areaLib = $this->getMock(
            '\iio\phplibphone\AreaLookupInterface',
            array('getCountryCode', 'lookup')
        );

        $areaLib->expects($this->once())
                ->method('getCountryCode')
                ->will($this->returnValue(46));

        $areaLib->expects($this->once())
                ->method('lookup')
                ->with('8')
                ->will($this->returnValue('Stockholm'));

        $number->setAreaLib($areaLib);

        $number->setCountryCode('46');
        $number->setNationalDestinationCode('8');
        $number->setSubscriberNumber('7740212');
        $this->assertEquals('Stockholm', $number->getArea());

        $number->setCountryCode('45');
        $this->assertEquals('', $number->getArea());
    }

    public function testSetRaw()
    {
        $number = new Number(new Countries(new LocaleFacade('en')), 46);
        $number->setAreaLib(new AreasSeSv());

        $number->setRaw('+9987740212');
        $this->assertEquals('+998 774 02 12', $number->format());

        $number->setRaw('+4687740212');
        $this->assertEquals('08-774 02 12', $number->format());

        $number->setRaw('087740212');
        $this->assertEquals('08-774 02 12', $number->format());

        $number->setRaw('87740212');
        $this->assertEquals('877 402 12', $number->format());

        $number->setRaw('+187740212');
        $this->assertEquals('+1 877 402 12', $number->format());

        $number->setRaw('invalid');
        $this->assertEquals('', $number->format());

        $number->setRaw('');
        $this->assertEquals('', $number->format());
    }

    public function testGetRaw()
    {
        $number = new Number(new EmptyLibrary());
        $number->setRaw('+4687740212');
        $this->assertEquals('+4687740212', $number->getRaw());
    }

    public function testGetCountryCode()
    {
        $number = new Number(new Countries(new LocaleFacade('en')));

        $number->setRaw('+4687740212');
        $this->assertEquals('46', $number->getCountryCode());
    }

    public function testGetNationalDestinationCode()
    {
        $number = new Number(new Countries(new LocaleFacade('en')));

        $number->setAreaLib(new AreasSeSv());
        $number->setRaw('+4687740212');
        $this->assertEquals('8', $number->getNationalDestinationCode());
    }

    public function testGetSubscriberNumber()
    {
        $number = new Number(new Countries(new LocaleFacade('en')));
        $number->setAreaLib(new AreasSeSv());
        $number->setRaw('+4687740212');
        $this->assertEquals('7740212', $number->getSubscriberNumber());
    }

    public function testGroup()
    {
        $number = new Number(new EmptyLibrary());

        $c = $number::group('1');
        $this->assertEquals('1', $c);

        $c = $number::group('11');
        $this->assertEquals('11', $c);

        $c = $number::group('111');
        $this->assertEquals('111', $c);

        $c = $number::group('1111');
        $this->assertEquals('11 11', $c);

        $c = $number::group('11111');
        $this->assertEquals('111 11', $c);

        $c = $number::group('111111');
        $this->assertEquals('11 11 11', $c);

        $c = $number::group('1111111');
        $this->assertEquals('111 11 11', $c);

        $c = $number::group('11111111');
        $this->assertEquals('111 111 11', $c);

        $c = $number::group('111111111');
        $this->assertEquals('111 111 111', $c);

        $c = $number::group('1111111111');
        $this->assertEquals('11 11 11 11 11', $c);

        $c = $number::group('11111111111');
        $this->assertEquals('111 11 11 11 11', $c);
    }

    public function testEmptyCarrierCountryCode()
    {
        $emptyCarrier = new EmptyCarrierLibrary();
        $this->assertSame(0, $emptyCarrier->getCountryCode());
    }
}
