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

namespace iio\phplibphone;

/**
 * Empty carrier library null object
 *
 * @package phplibphone
 */
class EmptyCarrierLibrary implements CarrierLookupInterface
{
    /**
     * Empty carrier library null object
     *
     * @param string $ndc National destination number
     * @param string $sn Subscriber number
     *
     * @return string Always returns the empty string
     */
    public function lookup($ndc, $sn)
    {
        return '';
    }

    /**
     * Get country code this library handles
     *
     * @return int
     */
    public function getCountryCode()
    {
        return 0;
    }
}
