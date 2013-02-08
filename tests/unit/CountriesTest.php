<?php
namespace iio\phplibphone;

use iio\phplibphone\Library\Countries;
use iio\localefacade\LocaleFacade;

class CountriesTest extends \PHPUnit_Framework_TestCase
{
    public function testLookup()
    {
        $lib = new Countries(new LocaleFacade('sv'));

        $this->assertEquals('USA', $lib->lookup('1'));
        $this->assertEquals('', $lib->lookup('0'));
    }

    /*public function testTranslateException()
    {
        $phpCountry = $this->getMock(
            '\iio\phpcountry\Country',
            array('translate')
        );

        $phpCountry->expects($this->once())
            ->method('translate')
            ->will(
                $this->throwException(
                    new \iio\phpcountry\TranslationException
                )
            );

        $lib = new Countries($phpCountry);
        $this->assertEquals('', $lib->lookup('1'));
    }*/
}
