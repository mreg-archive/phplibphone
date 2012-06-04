<?php
namespace itbz\phplibphone;
use PDO;
use itbz\Cache\VoidCacher;


class CountryTest extends \PHPUnit_Framework_TestCase
{

    function testVoid()
    {
    }

/*
    public function testFetchByCC()
    {
        $countries = new Country($this->getPdo(), new VoidCacher(), 'lookup');

        $c = $countries->fetchByCC('1');
        $this->assertEquals('Canada, United States', $c);

        $c = $countries->fetchByCC('1', 'se');
        $this->assertEquals('Kanada, Usa', $c);

        $c = $countries->fetchByCC('1', 'en');
        $this->assertEquals('Canada, United States', $c);

        $c = $countries->fetchByCC('1', 'fr');
        $this->assertEquals('Canada, United States', $c);

        $c = $countries->fetchByCC('46');
        $this->assertEquals('Sweden', $c);

        $c = $countries->fetchByCC('');
        $this->assertEquals('', $c);

        $c = $countries->fetchByCC('999');
        $this->assertEquals('', $c);
    }


    public function testFetchByAlpha2()
    {
        $countries = new Country($this->getPdo(), new VoidCacher(), 'lookup');

        $c = $countries->fetchByAlpha2('US');
        $this->assertEquals('United States', $c);

        $c = $countries->fetchByAlpha2('');
        $this->assertEquals('', $c);

        $c = $countries->fetchByAlpha2('US', 'se');
        $this->assertEquals('Usa', $c);

        $c = $countries->fetchByAlpha2('US', 'en');
        $this->assertEquals('United States', $c);

        $c = $countries->fetchByAlpha2('US', 'fr');
        $this->assertEquals('United States', $c);

        $c = $countries->fetchByAlpha2('se');
        $this->assertEquals('Sweden', $c);
    }
*/
}
