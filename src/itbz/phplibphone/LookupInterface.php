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
 * Standard interface for lookup data collections
 *
 * @package phplibphone
 */
interface LookupInterface
{
    /**
     * Lookup number
     *
     * @param string $nr
     *
     * @return string Data found, empty string if nothing was found
     */
    public function lookup($nr);
}
