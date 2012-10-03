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
 * @package phplibphone\Library
 */

namespace itbz\phplibphone\Library;

use itbz\phplibphone\Exception;
use SimpleXMLElement;

/**
 * Fetch carrier information for swedish phone numbers from api.pts.se
 *
 * @package phplibphone\Library
 */
class CarriersSe implements \itbz\phplibphone\CarrierLookupInterface
{
    /**
     * Get country code this library handles
     *
     * @return int
     */
    public function getCountryCode()
    {
        return 46;
    }

    /**
     * Fetch carrier information from api.pts.se
     *
     * @param string $ndc National destination code
     * @param string $sn Subscriber number
     *
     * @return string Carrier description
     *
     * @throws Exception if unable to reach api.pts.se, or XML is broken
     */
    public function lookup($ndc, $sn)
    {
        $url = "http://api.pts.se/ptsnumber/ptsnumber.asmx/SearchByNumber";
        $query = sprintf('?Ndc=%s&Number=%s', urlencode($ndc), urlencode($sn));
        $page = @file_get_contents($url . $query);

        if (!$page) {
            throw new Exception("Unable to fetch carrier from '$url'");
        }

        libxml_use_internal_errors(true);
        $xml = new SimpleXMLElement($page);

        if (!$xml instanceof SimpleXMLElement) {
            throw new Exception("Invalid XML returned from '$url'");
        }

        foreach ($xml->children() as $node) {
            if ($node->getName() == 'Operator') {

                return (string)$node;
            }
        }

        throw new Exception("Operator node missing from '$url'");
    }
}
