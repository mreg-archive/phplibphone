<?php
namespace itbz\phplibphone;

use itbz\phplibphone\Library\Countries;
use itbz\phpcountry\Country as PhpCountry;

class CountriesTest extends \PHPUnit_Framework_TestCase
{
    public function testLookup()
    {
        $phpCountry = new PhpCountry;
        $phpCountry->setLang('sv');

        $lib = new Countries($phpCountry);

        $this->assertEquals('USA', $lib->lookup('1'));
        $this->assertEquals('', $lib->lookup('0'));
    }

    public function testTranslateException()
    {
        $phpCountry = $this->getMock(
            '\itbz\phpcountry\Country',
            array('translate')
        );

        $phpCountry->expects($this->once())
            ->method('translate')
            ->will(
                $this->throwException(
                    new \itbz\phpcountry\TranslationException
                )
            );

        $lib = new Countries($phpCountry);
        $this->assertEquals('', $lib->lookup('1'));
    }
}
