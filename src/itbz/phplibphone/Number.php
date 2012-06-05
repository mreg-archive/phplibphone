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
     * Default country code
     *
     * @var string
     */
    private $_defaultCc;


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
     *
     * @param string $defaultCc Country code to assume when parsing numbers with
     * no explicit country code
     */
    public function __construct(LookupInterface $countryLib, $defaultCc = '')
    {
        $this->_countryLib = $countryLib;
        $this->_defaultCc = (string)$defaultCc;
    }

    
    /**
     * Register area code lookup library
     *
     * @param LookupInterface $areaLib
     *
     * @return void
     */
    public function setAreaLib(AreaLookupInterface $areaLib)
    {
        $countryCode = $areaLib->getCountryCode();
        $this->_areaLibs[$countryCode] = $areaLib;
    }


    /**
     * Get area code lookup library for country code
     *
     * @param string $countryCode
     *
     * @return LookupInterface
     */
    public function getAreaLib($countryCode)
    {
        if (isset($this->_areaLibs[$countryCode])) {

            return $this->_areaLibs[$countryCode];
        }

        return new EmptyLibrary();
    }


    /**
     * Register carrier code lookup library
     *
     * @param CarrierLookupInterface $carrier
     *
     * @return void
     */
    public function setCarrierLib(CarrierLookupInterface $carrier)
    {
        $countryCode = $carrier->getCountryCode();
        $this->_carrierLibs[$countryCode] = $carrier;
    }


    /**
     * Get carrier lookup library for country code
     *
     * @param string $countryCode
     *
     * @return CarrierLookupInterface
     */
    public function getCarrierLib($countryCode)
    {
        if (isset($this->_carrierLibs[$countryCode])) {

            return $this->_carrierLibs[$countryCode];
        }

        return new EmptyCarrierLibrary();
    }


    /**
     * Reset container
     *
     * @return void
     */
    public function reset()
    {
        $this->_raw = '';
        $this->_cc = $this->_defaultCc;
        $this->_ndc = '';
        $this->_sn = '';
    }


    /**
     * Set raw number
     *
     * Non numerical characters (apart from prefixes) are silently ignored
     *
     * @param string $nr
     *
     * @return void
     */
    public function setRaw($nr)
    {
        assert('is_string($nr)');
        $this->reset();
        $this->_raw = $nr;

        $len = strlen($nr);
        if ($len == 0) {
            $nr = '0';
            $len = 1;
        }    

        // Set parsing state
        switch ( $nr[0] ) {
            case self::CC_PREFIX:
                $state = self::STATE_CC;
                $step = 1;
                break;
            case self::TRUNK_PREFIX:
                $state = self::STATE_NDC;
                $step = 1;
                break;
            default:
                $step = 0;
                $state = self::STATE_SN;
        }
        
        // Active parsing part
        $part = '';

        // Step through number
        for (; $step < $len; $step++ ) {
            if (!ctype_digit($nr[$step])) {
                continue;
            }

            $part .= $nr[$step];
            
            if ($state == self::STATE_CC) {
                // Check if $part is a valid country code
                if ($this->_countryLib->lookup($part) != '') {
                    $this->_cc = $part;
                    $part = '';
                    $state = self::STATE_NDC;
                } elseif (strlen($part) >= 5) {
                    // Max 5 chars in country codes
                    $state = self::STATE_NDC;
                }
            }

            if ($state == self::STATE_NDC) {
                // Check if $part is a valid national destination code
                if ($this->getAreaLib($this->_cc)->lookup($part) != '') {
                    $this->_ndc = $part;
                    $part = '';
                    $state = self::STATE_SN;
                } elseif (strlen($part) >= 3) {
                    // Max 3 chars in national destination codes
                    $state = self::STATE_SN;
                }
            }
        }
        
        // The rest is subscriber number
        $this->_sn = $part;
    }


    /**
     * Get unformatted number
     *
     * Only avaliable if number is set using setRaw()
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->_raw;
    }


    /**
     * Set country code
     *
     * @param string $cc
     *
     * @return void
     */
    public function setCountryCode($cc)
    {
        assert('is_string($cc)');
        $this->_cc = $cc;
    }


    /**
     * Set national destination code
     *
     * @param string $ndc
     *
     * @return void
     */
    public function setNationalDestinationCode($ndc)
    {
        assert('is_string($ndc)');
        $this->_ndc = $ndc;
    }


    /**
     * Set subscriber number
     *
     * @param string $sn
     *
     * @return void
     */
    public function setSubscriberNumber($sn)
    {
        assert('is_string($sn)');
        $this->_sn = $sn;
    }


    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->_cc;
    }


    /**
     * Get national destination code
     *
     * @return string
     */
    public function getNationalDestinationCode()
    {
        return $this->_ndc;
    }


    /**
     * Get subscriber number
     *
     * @return string
     */
    public function getSubscriberNumber()
    {
        return $this->_sn;
    }


    /**
     * Get area code
     *
     * @return string
     */
    public function getAreaCode()
    {
        return empty($this->_ndc) ? '' : self::TRUNK_PREFIX . $this->_ndc;
    }


    /**
     * Get number formatted according to E164
     *
     * @return string
     */
    public function getE164()
    {
        $num = $this->_cc . $this->_ndc . $this->_sn;
        if (!empty($num)) {
            $num = "+$num";
        }

        return $num;
    }


    /**
     * Get number formatted for internation calls
     *
     * @return string
     */
    public function getInternationalFormat()
    {
        if (empty($this->_cc)) {

            return '';
        }
        
        return str_replace(
            '  ',
            ' ',
            sprintf(
                '%s%s %s %s',
                self::CC_PREFIX,
                $this->_cc,
                $this->_ndc,
                self::group($this->_sn)
            )
        );
    }


    /**
     * Get number in national format
     *
     * No country code and a hyphen between ndc and sn
     *
     * @return string
     */
    public function getNationalFormat()
    {
        $areaCode = $this->getAreaCode();
        if (!empty($areaCode)) {
            $areaCode .= '-'; 
        }
        
        return $areaCode . self::group($this->_sn);
    }


    /**
     * Get phone number in national or international format
     *
     * If country code equals default country code national format is used. Else
     * international.
     *
     * @return string
     */
    public function format()
    {
        if ($this->_cc == $this->_defaultCc) {

            return $this->getNationalFormat();
        }

        return $this->getInternationalFormat();
    }


    /**
     * Validate number of E.164 conformity
     *
     * @return bool
     */
    public function isValid()
    {
        $nr = $this->getE164();
        $len = strlen($nr);
        
        return ($len >= 6 && $len <= 16);
    }


    /**
     * Get name of country
     *
     * @return string Name of country, empty string if none is avaliable
     */
    public function getCountry()
    {
        return $this->_countryLib->lookup($this->_cc);
    }


    /**
     * Get name of carrier info
     *
     * @return string Empty string if no info is avaliable
     */
    public function getCarrier()
    {
        return $this->getCarrierLib($this->_cc)->lookup(
            $this->_ndc,
            $this->_sn
        );
    }


    /**
     * Get string describing area code area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->getAreaLib($this->_cc)->lookup($this->_ndc);
    }



    /**
     * Generic group number
     *
     * @param string $nr
     *
     * @return string
     */
    static public function group($nr)
    {
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
        }

        if (strlen($nr)&1) {
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
