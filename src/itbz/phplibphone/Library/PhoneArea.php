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
 * Lookup national destination codes
 * @package phplibphone
 */
class PhoneArea implements \itbz\phplibphone\LookupInterface
{

    /**
     * Collection of area codes
     * @var array $codes
     */
    protected $codes = array();


    /**
     * Lookup area name of country and national destination code
     * @param string $cc Country code
     * @param string $ndc National destination code
     * @return string Description, empty string if nothing was found
     */
    public function fetchArea($cc, $ndc)
    {
        assert('is_string($cc)');
        assert('is_string($ndc)');
        if ( !isset($this->codes[$cc]) ) return '';
        if ( !isset($this->codes[$cc][$ndc]) ) return '';
        return $this->codes[$cc][$ndc];
    }

}
