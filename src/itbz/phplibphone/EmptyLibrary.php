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
 * Empty library null object
 *
 * @package phplibphone
 */
class EmptyLibrary implements LookupInterface
{
    /**
     * Null object lookup
     *
     * @param string $nr
     *
     * @return string Always returns the empty string
     */
    public function lookup($nr)
    {
        return '';
    }
}
