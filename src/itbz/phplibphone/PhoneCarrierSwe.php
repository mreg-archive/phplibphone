<?php
/**
 * Copyright (c) 2012 Hannes Forsgård
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/)
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package phplibphone
 */
namespace itbz\phplibphone\Swe;
use itbz\phplibphone\PhoneCarrier as PhoneCarrierBase;
use itbz\STB\Exception;
use SimpleXMLElement;


/**
 * Fetch carrier information for swedish phone number from api.pts.se
 * @package phplibphone
 */
class PhoneCarrier extends PhoneCarrierBase
{

    /**
     * Fetch carrier information from api.pts.se
     * @param string $cc Country code
     * @param string $ndc National destination code (area code without prefix)
     * @param string $sn Subscriber number
     * @return string Name of carrier, empty string if nothing could be fetched.
     * @throws Exception if unable to reach api.pts.se, or if returned XML is broken
     */
    public function fetchCarrier($cc, $ndc, $sn)
    {
        assert('is_numeric($cc) || empty($cc)');
        assert('is_numeric($ndc) || empty($ndc)');
        assert('is_numeric($sn) || empty($sn)');
        
        if ( $cc != '46' ) return '';
        if ( $ndc == '' ) return '';
        if ( $sn == '' ) return '';
        
        $ndc = urlencode($ndc);
        $sn = urlencode($sn);
        $url = "http://api.pts.se/ptsnumber/ptsnumber.asmx/SearchByNumber?Ndc=$ndc&Number=$sn";

        $page = @file_get_contents($url);
        
        if ( !$page ) {
            // @codeCoverageIgnoreStart
            throw new Exception("Unable to fetch carrier from $url");
            // @codeCoverageIgnoreEnd
        }

        libxml_use_internal_errors(true);
        $xml = new SimpleXMLElement($page);

        if ( !is_a($xml, 'SimpleXMLElement') || !isset($xml->Operator) ) {
            // @codeCoverageIgnoreStart
            throw new Exception("Invalid XML returned from $url");
            // @codeCoverageIgnoreEnd
        }

        if ( $xml->Operator=='Ogiltigt värde' || $xml->Operator=='Finns ingen operatör med detta nummer' ) {
            return '';
        }
        
        return (string)$xml->Operator;
    }

}
