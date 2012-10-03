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
    private $countryLib;

    /**
     * Array of area code lookup libraries
     *
     * @var array
     */
    private $areaLibs = array();

    /**
     * Array of carrier lookup libraries
     *
     * @var array
     */
    private $carrierLibs = array();

    /**
     * Default country code
     *
     * @var string
     */
    private $defaultCc;

    /**
     * Raw number input
     *
     * @var string
     */
    private $raw = '';

    /**
     * Country code
     *
     * @var string
     */
    private $cc = '';

    /**
     * National destination code
     *
     * @var string
     */
    private $ndc = '';

    /**
     * Subscriber number
     *
     * @var string
     */
    private $sn = '';

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
        $this->countryLib = $countryLib;
        $this->defaultCc = (string)$defaultCc;
    }

    /**
     * Register area code lookup library
     *
     * @param AreaLookupInterface $areaLib
     *
     * @return void
     */
    public function setAreaLib(AreaLookupInterface $areaLib)
    {
        $countryCode = $areaLib->getCountryCode();
        $this->areaLibs[$countryCode] = $areaLib;
    }

    /**
     * Get area code lookup library for country code
     *
     * @param string/int $countryCode
     *
     * @return LookupInterface
     */
    public function getAreaLib($countryCode)
    {
        if (isset($this->areaLibs[$countryCode])) {

            return $this->areaLibs[$countryCode];
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
        $this->carrierLibs[$countryCode] = $carrier;
    }

    /**
     * Get carrier lookup library for country code
     *
     * @param string/int $countryCode
     *
     * @return CarrierLookupInterface
     */
    public function getCarrierLib($countryCode)
    {
        if (isset($this->carrierLibs[$countryCode])) {

            return $this->carrierLibs[$countryCode];
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
        $this->raw = '';
        $this->cc = $this->defaultCc;
        $this->ndc = '';
        $this->sn = '';
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
        $this->raw = $nr;

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
        for (; $step < $len; $step++) {
            if (!ctype_digit($nr[$step])) {
                continue;
            }

            $part .= $nr[$step];

            if ($state == self::STATE_CC) {
                // Check if $part is a valid country code
                if ($this->countryLib->lookup($part) != '') {
                    $this->cc = $part;
                    $part = '';
                    $state = self::STATE_NDC;
                } elseif (strlen($part) >= 5) {
                    // Max 5 chars in country codes
                    $state = self::STATE_NDC;
                }
            }

            if ($state == self::STATE_NDC) {
                // Check if $part is a valid national destination code
                if ($this->getAreaLib($this->cc)->lookup($part) != '') {
                    $this->ndc = $part;
                    $part = '';
                    $state = self::STATE_SN;
                } elseif (strlen($part) >= 3) {
                    // Max 3 chars in national destination codes
                    $state = self::STATE_SN;
                }
            }
        }

        // The rest is subscriber number
        $this->sn = $part;
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
        return $this->raw;
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
        $this->cc = $cc;
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
        $this->ndc = $ndc;
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
        $this->sn = $sn;
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->cc;
    }

    /**
     * Get national destination code
     *
     * @return string
     */
    public function getNationalDestinationCode()
    {
        return $this->ndc;
    }

    /**
     * Get subscriber number
     *
     * @return string
     */
    public function getSubscriberNumber()
    {
        return $this->sn;
    }

    /**
     * Get area code
     *
     * @return string
     */
    public function getAreaCode()
    {
        return empty($this->ndc) ? '' : self::TRUNK_PREFIX . $this->ndc;
    }

    /**
     * Get number formatted according to E164
     *
     * @return string
     */
    public function getE164()
    {
        $num = $this->cc . $this->ndc . $this->sn;
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
        if (empty($this->cc)) {

            return '';
        }

        return str_replace(
            '  ',
            ' ',
            sprintf(
                '%s%s %s %s',
                self::CC_PREFIX,
                $this->cc,
                $this->ndc,
                self::group($this->sn)
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

        return $areaCode . self::group($this->sn);
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
        if ($this->cc == $this->defaultCc) {

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
        return $this->countryLib->lookup($this->cc);
    }

    /**
     * Get name of carrier info
     *
     * @return string Empty string if no info is avaliable
     */
    public function getCarrier()
    {
        return $this->getCarrierLib($this->cc)->lookup(
            $this->ndc,
            $this->sn
        );
    }

    /**
     * Get string describing area code area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->getAreaLib($this->cc)->lookup($this->ndc);
    }

    /**
     * Generic group number
     *
     * @param string $nr
     *
     * @return string
     */
    public static function group($nr)
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
                "/^([0-9]{3})([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?/",
                "$1 $2 $3 $4 $5 $6",
                $nr
            );
        } else {
            //even length
            $nr = preg_replace(
                "/^([0-9]{2})([0-9]{2})?([0-9]{2})?([0-9]{2})?([0-9]{2})?/",
                "$1 $2 $3 $4 $5 $6",
                $nr
            );
        }

        return trim($nr);
    }
}
