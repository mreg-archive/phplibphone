<?php
/**
 * This file is part of the phplibphone package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 *
 * @package phplibphone
 */
namespace itbz\phplibphone;


/**
 * Parse phone numbers
 *
 * @package phplibphone
 */
class Number
{

    /**
     * Area code prefix
     */
    const TRUNK_PREFIX = '0';


    /**
     * Country code prefix
     */
    const CC_PREFIX = '+';


    /**
     * Contry code parsing state
     */
    const STATE_CC = 1;


    /**
     * Nation destination code parsing state
     */
    const STATE_NDC = 2;


    /**
     * Subscriber number parsing state
     */
    const STATE_SN = 0;


    /**
     * Country code lookup library
     *
     * @var LookupInterface
     */
    private $_countryLib;


    /**
     * Array of area code lookup libraries
     *
     * @var array
     */
    private $_areaLibs = array();


    /**
     * Array of carrier lookup libraries
     *
     * @var array
     */
    private $_carrierLibs = array();


    /**
     * Raw number input
     *
     * @var string
     */
    private $_raw = '';


    /**
     * Country code
     *
     * @var string
     */
    private $_cc = '';

    
    /**
     * National destination code
     *
     * @var string
     */
    private $_ndc = '';
    

    /**
     * Subscriber number
     *
     * @var string
     */
    private $_sn = '';


    /**
     * Country code lookup library is required
     *
     * @param LookupInterface $countryLib
     */
    public function __construct(LookupInterface $countryLib)
    {
        $this->_countryLib = $countryLib;
        #PhoneArea $areas,
        #PhoneCarrier $carriers
        #$this->_areaLibs = $areas;
        #$this->_carrierLibs = $carriers;
    }

    // såhär...
    public function registerAreaLib($countryCode, LookupInterface $areaLib)
    {
        assert('is_scalar($countryCode)');
        $this->_areaLibs[$countryCode] = $areaLib;
    }


    /*
        låt Lookup vara ett enkelt interface för att hämta info om nummer
            eller subnummer
        
        skapa ett underpaket som heter Library
        
        skapa de olika klasserna med data där
        
        ladda area code libs efter landskod i en egen funktion
        
        ladda carrier libs efter landskod i en egen funktion

        sedan är det bara att köra på som innan
            men byt namn på metode och liknande...
    */


    /**
     * Reset container
     * @return void
     */
    public function reset()
    {
        $this->_raw = '';
        $this->_cc = '';
        $this->_ndc = '';
        $this->_sn = '';
    }


    /**
     * Set number. Non numerical characters (apart from prefixes) will be
     * silently ignored.
     * @param string $nr
     * @param numeric $cc Default country code
     * @return void
     */
    public function setRaw($nr, $cc = '46')
    {
        assert('is_string($nr)');
        assert('is_numeric($cc)');
        $this->reset();
        $this->_cc = $cc;
        $this->_raw = $nr;

        $len = strlen($nr);
        if ( $len == 0 ) {
            $nr = '0';
            $len = 1;
        }    

        // Set parsing state
        switch ( $nr[0] ) {
            case self::CC_PREFIX:
                $state = self::STATE_CC;
                $i = 1;
                break;
            case self::TRUNK_PREFIX:
                $state = self::STATE_NDC;
                $i = 1;
                break;
            default:
                $i = 0;
                $state = self::STATE_SN;
        }
        
        // Active parsing part
        $part = '';

        // Step through number
        for (; $i<$len; $i++ ) {
            if ( ctype_digit($nr[$i]) ) $part .= $nr[$i];
            
            if ( $state == self::STATE_CC ) {
                // Check if $part is a valid country code
                if ( is_numeric($part) && $this->_countryLib->fetchByCC($part) != '' ) {
                    $this->_cc = $part;
                    $part = '';
                    $state = self::STATE_NDC;
                } elseif ( strlen($part) >= 5 ) {
                    // Max 5 chars in country codes
                    $state = self::STATE_NDC;
                }
            }

            if ( $state == self::STATE_NDC ) {
                // Check if $part is a valid national destination code
                if ( is_numeric($part) && $this->_areaLibs->fetchArea($this->_cc, $part)!='' ) {
                    $this->_ndc = $part;
                    $part = '';
                    $state = self::STATE_SN;
                } elseif ( strlen($part) >= 3 ) {
                    // Max 3 chars in national destination codes
                    $state = self::STATE_SN;
                }
            }
        } // </for>
        
        // The rest is subscriber number
        $this->_sn = $part;
    }


    /**
     * Get unformatted number. Only avaliable if number is set using setRaw()
     * @return string
     */
    public function getRaw()
    {
        return $this->_raw;
    }


    /**
     * Set country code
     * @param numeric $cc
     * @return void
     */
    public function setCc($cc)
    {
        assert('is_numeric($cc) || $cc==""');
        $this->_cc = $cc;
    }


    /**
     * Set national destination code
     * @param numeric $ndc
     * @return void
     */
    public function setNdc($ndc)
    {
        assert('is_numeric($ndc) || $ndc==""');
        $this->_ndc = $ndc;
    }


    /**
     * Set subscriber number
     * @param numeric $sn
     * @return void
     */
    public function setSn($sn)
    {
        assert('is_numeric($sn) || $sn==""');
        $this->_sn = $sn;
    }


    /**
     * Get country code
     * @return string
     */
    public function getCc()
    {
        return $this->_cc;
    }


    /**
     * Get national destination code
     * @return string
     */
    public function getNdc()
    {
        return $this->_ndc;
    }


    /**
     * Get subscriber number
     * @return string
     */
    public function getSn()
    {
        return $this->_sn;
    }


    /**
     * Get area code (trunk prefix + national destination code).
     * @return string
     */
    public function getAreaCode()
    {
        return empty($this->_ndc) ? '' : self::TRUNK_PREFIX.$this->_ndc;
    }


    /**
     * Get number formatted according to E164
     * @return string
     */
    public function getE164()
    {
        $num = $this->_cc . $this->_ndc . $this->_sn;
        if ( !empty($num) ) $num = "+$num";
        return $num;
    }


    /**
     * Get number formatted for internation calls
     * @return string
     */
    public function getInternationalFormat()
    {
        if ( empty($this->_cc) ) return '';
        $ndc = empty($this->_ndc) ? '' : $this->_ndc . ' ';
        return self::CC_PREFIX . $this->_cc . ' ' . $ndc . self::group($this->_sn);
    }


    /**
     * Get number in national format, with no country code
     * and a hyphen between ndc and sn
     * @return string
     */
    public function getNationalFormat()
    {
        $areaCode = $this->getAreaCode();
        if ( !empty($areaCode) ) $areaCode .= '-'; 
        return $areaCode . self::group($this->_sn);
    }


    /**
     * Get phone number in national or internation format depending on country
     * @param string $cc Calling from country code
     * @return string
     */
    public function format($cc = '46')
    {
        return ( $this->_cc == $cc ) ? $this->getNationalFormat() : $this->getInternationalFormat();
    }


    /**
     * A valid number must in its E164 form contain between 5 and 15 digits
     * @return bool
     */
    public function isValid()
    {
        $nr = $this->getE164();
        $len = strlen($nr);
        return ( $len >= 6 && $len <= 16 );
    }


    /**
     * Get name of country. Depends on country bank.
     * @param string $lang Preferred return language, alpha 2 country code
     * @return string name of country, empty string if none is avaliable
     */
    public function getCountry($lang = 'en')
    {
        return $this->_countryLib->fetchByCC($this->_cc, $lang);
    }


    /**
     * Get carrier info. Depends on carrier bank.
     * @return string name of carrier, empty string if none is avaliable
     */
    public function getCarrier()
    {
        return $this->_carrierLibs->fetchCarrier($this->_cc, $this->_ndc, $this->_sn);
    }


    /**
     * Get string describing area code area. Depends on area code bank.
     * @return string
     */
    public function getArea()
    {
        return $this->_areaLibs->fetchArea($this->_cc, $this->_ndc);
    }


    /**
     * Generic group number
     * @param string $nr
     * @return string
     */
    static public function group($nr){
        assert('is_string($nr)');
        $nr = preg_replace("/[\n \t]/", '', $nr);
        switch ( strlen($nr) ) {
            case 1:
            case 2:
            case 3:
                return $nr;
            case 4:
                return preg_replace("/^(.{2})(.{2})/", '$1 $2', $nr);
            case 5:
                return preg_replace("/^(.{3})(.{2})/", '$1 $2', $nr);
            case 6:
                return preg_replace("/^(.{2})(.{2})(.{2})/", '$1 $2 $3', $nr);
            case 7:
                return preg_replace("/^(.{3})(.{2})(.{2})/", '$1 $2 $3', $nr);
            case 8:
                return preg_replace("/^(.{3})(.{3})(.{2})/", '$1 $2 $3', $nr);
            case 9:
                return preg_replace("/^(.{3})(.{3})(.{3})/", '$1 $2 $3', $nr);
            default:
                if ( strlen($nr)&1 ) {
                    //odd length
                    $nr = preg_replace(
                        "/^([0-9]{3})([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?/",
                        "$1 $2 $3 $4 $5 $6 $7",
                        $nr
                    );
                } else {
                    //even length
                    $nr = preg_replace(
                        "/^([0-9]{2})([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?/",
                        "$1 $2 $3 $4 $5 $6 $7",
                        $nr
                    );
                }
                return trim($nr);
        }
    }

}
