<?php
/**
 * Copyright (c) 2012 Hannes Forsgård
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/)
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package phplibphone
 */
namespace itbz\phplibphone;
use PDO;
use itbz\Cache\CacheInterface;


/**
 * Lookup country codes from db
 * @package phplibphone
 */
class Country
{

    /**
     * PDO database connection
     * @var PDO $pdo
     */
    private $pdo;


    /**
     * Cache
     * @var CacheInterface $cache
     */
    private $cache;


    /**
     * Name of table to select from
     * @var string $table
     */
    private $table;


    /**
     * Set database connection
     * @param PDO $pdo
     * @param CacheInterface $cache
     * @param string $table
     */
    public function __construct(PDO $pdo, CacheInterface $cache, $table = 'lookup__Iso3166')
    {
        assert('is_string($table)');
        $this->pdo = $pdo;
        $this->cache = $cache;
        $this->table = $table;
    }


    /**
     * Lookup country names from country calling code
     * @param string $cc Country code
     * @param string $lang Preferred return language
     * @return string empty string if nothing was found
     */
    public function fetchByCC($cc, $lang = 'EN')
    {
        assert('is_numeric($cc) || empty($cc)');
        return $this->fetchBy('cc', $cc, $lang);
    }


    /**
     * Lookup country names from alpha2 code
     * @param string $code Country code
     * @param string $lang Preferred return language
     * @return string empty string if nothing was found
     */
    public function fetchByAlpha2($code, $lang = 'EN')
    {
        assert('is_string($code)');
		if ( !ctype_alpha($code) ) return '';
		$code = strtoupper($code);
        return $this->fetchBy('country_code', $code, $lang);
    }


    /**
     * Generic lookup country names
     * @param string $col Column to select from
     * @param string $code Country code
     * @param string $lang Preferred return language
     * @return string empty string if nothing was found
     */
    private function fetchBy($col, $code, $lang = 'EN')
    {
        assert('is_string($col) && !empty($col)');
        assert('is_string($code)');
        assert('is_string($lang)');

        $lang = strtolower($lang);
        if ( $lang != 'se' ) $lang = 'en';

        $key = "STB.$col.$code.$lang";
        if ( $this->cache->has($key) ) {
            return $this->cache->get($key);
        }

        $code = $this->pdo->quote($code);
        $select = "country_name_$lang";

        $query = "SELECT `$select` FROM {$this->table} WHERE $col = $code";
        $stmt = $this->pdo->query($query);
        $country = array();
        while ( $c = $stmt->fetchColumn() ) {
            $country[] = $c;
        }
        $country = implode(', ', $country);
        
        $this->cache->set($key, $country);
        return $country;
    }

}
