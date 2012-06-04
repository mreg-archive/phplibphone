<?php
namespace itbz\phplibphone;
use itbz\phplibphone\Swe\PhoneArea as SwePhoneArea;
use PDO;
use itbz\Cache\VoidCacher;


class PhoneNumberTest extends \PHPUnit_Framework_TestCase
{

    public function getPdo()
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('CREATE TABLE lookup__Iso3166(country_code, country_code_alpha3, country_code_num, country_name_se, country_name_en, cc, PRIMARY KEY(country_code ASC));');
        $pdo->query("INSERT INTO lookup__Iso3166 VALUES ('CA', 'CAN', '124', 'Kanada', 'Canada', '1')");
        $pdo->query("INSERT INTO lookup__Iso3166 VALUES ('US', 'USA', '840', 'Usa', 'United States', '1')");
        $pdo->query("INSERT INTO lookup__Iso3166 VALUES ('SE', 'SWE', '752', 'Sverige', 'Sweden', '46')");
        return  $pdo;
    }
    
    
    private function getPhone()
    {
        $pdo = $this->getPdo();
        $country = new Country($pdo, new VoidCacher());
        $area = new SwePhoneArea();
        $carrier = new PhoneCarrier();
        $phone = new PhoneNumber($country, $area, $carrier);
        return $phone;
    }


    public function testGetAreaCode()
    {
        $p = $this->getPhone();
        $p->setNdc('8');
        $c = $p->getAreaCode();
        $this->assertEquals('08', $c);
    }

    
    public function testGetE164()
    {
        $p = $this->getPhone();

        $c = $p->getE164();
        $this->assertEquals('', $c);

        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getE164();
        $this->assertEquals('+4687740212', $c);
    }


    public function testReset()
    {
        $p = $this->getPhone();
        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getE164();
        $this->assertEquals('+4687740212', $c);

        $p->reset();

        $c = $p->getE164();
        $this->assertEquals('', $c);
    }


    public function testGetInternationalFormat()
    {
        $p = $this->getPhone();

        $c = $p->getInternationalFormat();
        $this->assertEquals('', $c);

        $p->setCc('46');
        $p->setSn('7740212');
        $c = $p->getInternationalFormat();
        $this->assertEquals('+46 774 02 12', $c);

        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getInternationalFormat();
        $this->assertEquals('+46 8 774 02 12', $c);
    }


    public function testGetNationalFormat()
    {
        $p = $this->getPhone();

        $c = $p->getNationalFormat();
        $this->assertEquals('', $c);

        $p->setSn('7740212');
        $c = $p->getNationalFormat();
        $this->assertEquals('774 02 12', $c);

        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getNationalFormat();
        $this->assertEquals('08-774 02 12', $c);
    }


    public function testFormat()
    {
        $p = $this->getPhone();
        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');

        $c = $p->format('45');
        $this->assertEquals('+46 8 774 02 12', $c);

        $c = $p->format('46');
        $this->assertEquals('08-774 02 12', $c);
    }


    public function testIsValid()
    {
        $p = $this->getPhone();
        $this->assertTrue(!$p->isValid());
        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $this->assertTrue($p->isValid());
    }


    public function testGetCountry()
    {
        $p = $this->getPhone();

        $c = $p->getCountry();
        $this->assertEquals('', $c);

        $p->setCc('46');
        $c = $p->getCountry();
        $this->assertEquals('Sweden', $c);

        $c = $p->getCountry('se');
        $this->assertEquals('Sverige', $c);
    }


    public function testGetCarrier()
    {
        $p = $this->getPhone();

        $c = $p->getCarrier();
        $this->assertEquals('', $c);

        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getCarrier();
        $this->assertEquals('', $c);
    }


    public function testGetArea()
    {
        $p = $this->getPhone();

        $c = $p->getArea();
        $this->assertEquals('', $c);

        $p->setCc('46');
        $p->setNdc('8');
        $p->setSn('7740212');
        $c = $p->getArea();
        $this->assertEquals('Stockholm', $c);
    }


    public function testGroup()
    {
        $p = $this->getPhone();

        $c = $p::group('1');
        $this->assertEquals('1', $c);

        $c = $p::group('11');
        $this->assertEquals('11', $c);

        $c = $p::group('111');
        $this->assertEquals('111', $c);

        $c = $p::group('1111');
        $this->assertEquals('11 11', $c);

        $c = $p::group('11111');
        $this->assertEquals('111 11', $c);

        $c = $p::group('111111');
        $this->assertEquals('11 11 11', $c);

        $c = $p::group('1111111');
        $this->assertEquals('111 11 11', $c);

        $c = $p::group('11111111');
        $this->assertEquals('111 111 11', $c);

        $c = $p::group('111111111');
        $this->assertEquals('111 111 111', $c);

        $c = $p::group('1111111111');
        $this->assertEquals('11 11 11 11 11', $c);

        $c = $p::group('11111111111');
        $this->assertEquals('111 11 11 11 11', $c);
    }


    public function testSetRaw()
    {
        $p = $this->getPhone();

        $p->setRaw('+9987740212');
        $this->assertEquals('99 87 74 02 12', $p->format());

        $p->setRaw('+4687740212');
        $this->assertEquals('08-774 02 12', $p->format());

        $p->setRaw('087740212');
        $this->assertEquals('08-774 02 12', $p->format());

        $p->setRaw('87740212');
        $this->assertEquals('877 402 12', $p->format());

        $p->setRaw('+187740212');
        $this->assertEquals('+1 877 402 12', $p->format());

        $p->setRaw('087740212', '1');
        $this->assertEquals('+1 877 402 12', $p->format());

        $p->setRaw('87740212', '1');
        $this->assertEquals('+1 877 402 12', $p->format());

        $p->setRaw('');
        $this->assertEquals('', $p->format());
    }


    public function testGetRaw()
    {
        $p = $this->getPhone();
        $p->setRaw('+4687740212');
        $this->assertEquals('+4687740212', $p->getRaw());
    }


    public function testGetCc()
    {
        $p = $this->getPhone();
        $p->setRaw('+4687740212');
        $this->assertEquals('46', $p->getCc());
    }


    public function testGetNdc()
    {
        $p = $this->getPhone();
        $p->setRaw('+4687740212');
        $this->assertEquals('8', $p->getNdc());
    }


    public function testGetSn()
    {
        $p = $this->getPhone();
        $p->setRaw('+4687740212');
        $this->assertEquals('7740212', $p->getSn());
    }

}
