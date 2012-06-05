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
 * Interface for looking up carrier information
 *
 * @package phplibphone
 */
interface CarrierLookupInterface
{

    /**
     * Get country code this library handles
     *
     * @return int
     */
    public function getCountryCode();


    /**
     * Lookup number
     *
     * @param string $ndc National destination number
     *
     * @param string $sn Subscriber number
     *
     * @return string Data found, empty string if nothing was found
     */
    public function lookup($ndc, $sn);

}
