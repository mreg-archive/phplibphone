<?php
/**
 * Copyright (c) 2012 Hannes Forsgård
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/)
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package phplibphone
 */
namespace itbz\phplibphone;


/**
 * Models international phone numbers from a Swedish context
 * @package phplibphone
 */
class PhoneNumber
{

	/**
	 * Area code prefix
	 * @const string TRUNK_PREFIX
	 */
	const TRUNK_PREFIX = '0';


	/**
	 * Country code prefix
	 * @const string INTERNATIONAL_PREFIX
	 */
	const INTERNATIONAL_PREFIX = '+';


	/**
	 * Parsing state country code
	 * @const int PARSING_CC
	 */
	const PARSING_CC = 1;


	/**
	 * Parsing state national destination code
	 * @const int PARSING_NDC
	 */
	const PARSING_NDC = 2;


	/**
	 * Parsing state subscriber number
	 * @const int PARSING_SN
	 */
	const PARSING_SN = 0;


    /**
     * Object for fetching countries
     * @var Country $countries
     */
    private $countries;


    /**
     * Object for fetching areas
     * @var PhoneArea $areas
     */
    private $areas;


    /**
     * Object for fetching carriers
     * @var PhoneCarrier $carriers
     */
    private $carriers;


	/**
	 * Raw number input
	 * @var string $raw
	 */
	private $raw = '';


    /**
     * Country code
     * @var string $cc
     */
    private $cc = '';

    
    /**
     * National destination code
     * @var string $ndc
     */
    private $ndc = '';
    

    /**
     * Subscriber number
     * @var string $sn
     */
    private $sn = '';


    /**
     * Set dependencies
     * @param Country $countries
     * @param PhoneArea $areas
     * @param PhoneCarrier $carriers
     */
    public function __construct(
        Country $countries,
        PhoneArea $areas,
        PhoneCarrier $carriers
    )
    {
        $this->countries = $countries;
        $this->areas = $areas;
        $this->carriers = $carriers;
    }


    /**
     * Reset container
     * @return void
     */
    public function reset()
    {
        $this->raw = '';
        $this->cc = '';
        $this->ndc = '';
        $this->sn = '';
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
		$this->cc = $cc;
		$this->raw = $nr;

		$len = strlen($nr);
		if ( $len == 0 ) {
			$nr = '0';
			$len = 1;
		}	

		// Set parsing state
		switch ( $nr[0] ) {
			case self::INTERNATIONAL_PREFIX:
				$state = self::PARSING_CC;
				$i = 1;
				break;
			case self::TRUNK_PREFIX:
				$state = self::PARSING_NDC;
				$i = 1;
				break;
			default:
				$i = 0;
				$state = self::PARSING_SN;
		}
		
		// Active parsing part
		$part = '';

		// Step through number
		for (; $i<$len; $i++ ) {
			if ( ctype_digit($nr[$i]) ) $part .= $nr[$i];
			
			if ( $state == self::PARSING_CC ) {
    			// Check if $part is a valid country code
				if ( is_numeric($part) && $this->countries->fetchByCC($part) != '' ) {
					$this->cc = $part;
					$part = '';
					$state = self::PARSING_NDC;
				} elseif ( strlen($part) >= 5 ) {
    				// Max 5 chars in country codes
					$state = self::PARSING_NDC;
				}
			}

			if ( $state == self::PARSING_NDC ) {
    			// Check if $part is a valid national destination code
				if ( is_numeric($part) && $this->areas->fetchArea($this->cc, $part)!='' ) {
					$this->ndc = $part;
					$part = '';
					$state = self::PARSING_SN;
				} elseif ( strlen($part) >= 3 ) {
    				// Max 3 chars in national destination codes
					$state = self::PARSING_SN;
				}
			}
		} // </for>
		
		// The rest is subscriber number
		$this->sn = $part;
    }


	/**
	 * Get unformatted number. Only avaliable if number is set using setRaw()
	 * @return string
	 */
	public function getRaw()
	{
		return $this->raw;
	}


    /**
     * Set country code
     * @param numeric $cc
     * @return void
     */
    public function setCc($cc)
    {
        assert('is_numeric($cc) || $cc==""');
        $this->cc = $cc;
    }


    /**
     * Set national destination code
     * @param numeric $ndc
     * @return void
     */
    public function setNdc($ndc)
    {
        assert('is_numeric($ndc) || $ndc==""');
        $this->ndc = $ndc;
    }


    /**
     * Set subscriber number
     * @param numeric $sn
     * @return void
     */
    public function setSn($sn)
    {
        assert('is_numeric($sn) || $sn==""');
        $this->sn = $sn;
    }


    /**
     * Get country code
     * @return string
     */
    public function getCc()
    {
        return $this->cc;
    }


    /**
     * Get national destination code
     * @return string
     */
    public function getNdc()
    {
        return $this->ndc;
    }


    /**
     * Get subscriber number
     * @return string
     */
    public function getSn()
    {
        return $this->sn;
    }


	/**
	 * Get area code (trunk prefix + national destination code).
	 * @return string
	 */
	public function getAreaCode()
	{
		return empty($this->ndc) ? '' : self::TRUNK_PREFIX.$this->ndc;
	}


	/**
	 * Get number formatted according to E164
	 * @return string
	 */
	public function getE164()
	{
		$num = $this->cc . $this->ndc . $this->sn;
		if ( !empty($num) ) $num = "+$num";
		return $num;
	}


	/**
	 * Get number formatted for internation calls
	 * @return string
	 */
	public function getInternationalFormat()
	{
        if ( empty($this->cc) ) return '';
		$ndc = empty($this->ndc) ? '' : $this->ndc . ' ';
		return self::INTERNATIONAL_PREFIX . $this->cc . ' ' . $ndc . self::group($this->sn);
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
		return $areaCode . self::group($this->sn);
	}


    /**
     * Get phone number in national or internation format depending on country
     * @param string $cc Calling from country code
     * @return string
     */
    public function format($cc = '46')
    {
		return ( $this->cc == $cc ) ? $this->getNationalFormat() : $this->getInternationalFormat();
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
        return $this->countries->fetchByCC($this->cc, $lang);
    }


    /**
     * Get carrier info. Depends on carrier bank.
     * @return string name of carrier, empty string if none is avaliable
     */
    public function getCarrier()
    {
        return $this->carriers->fetchCarrier($this->cc, $this->ndc, $this->sn);
    }


	/**
	 * Get string describing area code area. Depends on area code bank.
	 * @return string
	 */
	public function getArea()
	{
		return $this->areas->fetchArea($this->cc, $this->ndc);
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
