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
namespace itbz\phplibphone\Library;


/**
 * Phone carrier base class. Does nothing.
 * @package phplibphone
 */
class PhoneCarrier implements \itbz\phplibphone\LookupInterface
{

    /**
     * Fetch carrier information (returns nothing)
     * @param string $cc Country code
     * @param string $ndc National destination code (area code without prefix)
     * @param string $sn Subscriber number
     * @return string
     */
    public function fetchCarrier($cc, $ndc, $sn)
    {
        assert('is_numeric($cc) || empty($cc)');
        assert('is_numeric($ndc) || empty($ndc)');
        assert('is_numeric($sn) || empty($sn)');
        return '';
    }

}
