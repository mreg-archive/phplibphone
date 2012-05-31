<?php
namespace itbz\phplibphone;
use PDO;
use itbz\Cache\VoidCacher;


class CountryTest extends \PHPUnit_Framework_TestCase
{

    public function getPdo()
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('CREATE TABLE lookup(country_code, country_code_alpha3, country_code_num, country_name_se, country_name_en, cc, PRIMARY KEY(country_code ASC));');
        $pdo->query("INSERT INTO lookup VALUES ('CA', 'CAN', '124', 'Kanada', 'Canada', '1')");
        $pdo->query("INSERT INTO lookup VALUES ('US', 'USA', '840', 'Usa', 'United States', '1')");
        $pdo->query("INSERT INTO lookup VALUES ('SE', 'SWE', '752', 'Sverige', 'Sweden', '46')");
        return  $pdo;
    }
    
    
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

}
