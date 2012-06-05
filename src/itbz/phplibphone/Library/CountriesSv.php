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
 *
 * @subpackage Library
 */
namespace itbz\phplibphone\Library;


/**
 * Maps country codes to swedish country names
 *
 * @package phplibphone
 *
 * @subpackage Library
 */
class CountriesSv implements \itbz\phplibphone\LookupInterface
{

    /**
     * The country code map
     *
     * @var array
     */
    static private $_countries = array(
	    1 => 'Usa, Kanada, Puerto Rico',
	    1242 => 'Bahamas',
	    1246 => 'Barbados',
	    1264 => 'Anguilla',
	    1268 => 'Antigua och barbuda',
	    1284 => 'Brittiska Jungfruöarna',
	    1340 => 'Amerikanska Jungfruöarna',
	    1345 => 'Caymanöarna',
	    1441 => 'Bermuda',
	    1473 => 'Grenada',
	    1649 => 'Turks- och Caicosöarna',
	    1664 => 'Montserrat',
	    1670 => 'Nordmarianerna',
	    1671 => 'Guam',
	    1684 => 'Amerikanska samoa',
	    1758 => 'S:t Lucia',
	    1767 => 'Dominica',
	    1784 => 'S:t Vincent och Grenadinerna',
	    1809 => 'Dominikanska republiken',
	    1868 => 'Trinidad och Tobago',
	    1869 => 'S:t Kitts och Nevis',
	    1876 => 'Jamaica',
	    20 => 'Egypten',
	    212 => 'Västsahara, Marocko',
	    213 => 'Algeriet',
	    216 => 'Tunisien',
	    218 => 'Libyen',
	    220 => 'Gambia',
	    221 => 'Senegal',
	    222 => 'Mauretanien',
	    223 => 'Mali',
	    224 => 'Guinea',
	    225 => 'Elfenbenskusten',
	    226 => 'Burkina faso',
	    227 => 'Niger',
	    228 => 'Togo',
	    229 => 'Benin',
	    230 => 'Mauritius',
	    231 => 'Liberia',
	    232 => 'Sierra leone',
	    233 => 'Ghana',
	    234 => 'Nigeria',
	    235 => 'Tchad',
	    236 => 'Centralafrikanska republiken',
	    237 => 'Kamerun',
	    238 => 'Kap verde',
	    239 => 'Sao Tome och Principe',
	    240 => 'Ekvatorialguinea',
	    241 => 'Gabon',
	    242 => 'Republiken Kongo',
	    243 => 'Demokratiska republiken Kongo',
	    244 => 'Angola',
	    245 => 'Guinea bissau',
	    246 => 'Brittiska terr. i indiska oceanen',
	    248 => 'Seychellerna',
	    249 => 'Sudan',
	    250 => 'Rwanda',
	    251 => 'Etiopien',
	    252 => 'Somalia',
	    253 => 'Djibouti',
	    254 => 'Kenya',
	    255 => 'Tanzania',
	    256 => 'Uganda',
	    257 => 'Burundi',
	    258 => 'Moçambique',
	    260 => 'Zambia',
	    261 => 'Madagaskar',
	    262 => 'Réunion',
	    263 => 'Zimbabwe',
	    264 => 'Namibia',
	    265 => 'Malawi',
	    266 => 'Lesotho',
	    267 => 'Botswana',
	    268 => 'Swaziland',
	    269 => 'Mayotte, Komorerna',
	    27 => 'Sydafrika',
	    290 => 'S:t Helena',
	    291 => 'Eritrea',
	    297 => 'Aruba',
	    298 => 'Färöarna',
	    299 => 'Grönland',
	    30 => 'Grekland',
	    31 => 'Nederländerna',
	    32 => 'Belgien',
	    33 => 'Frankrike, France métropolitaine',
	    34 => 'Spanien',
	    350 => 'Gibraltar',
	    351 => 'Portugal',
	    352 => 'Luxemburg',
	    353 => 'Irland',
	    354 => 'Island',
	    355 => 'Albanien',
	    356 => 'Malta',
	    357 => 'Cypern',
	    358 => 'Finland',
	    359 => 'Bulgarien',
	    36 => 'Ungern',
	    370 => 'Litauen',
	    371 => 'Lettland',
	    372 => 'Estland',
	    373 => 'Moldavien',
	    374 => 'Armenien',
	    375 => 'Vitryssland',
	    376 => 'Andorra',
	    377 => 'Monaco',
	    378 => 'San marino',
	    379 => 'Vatikanstaten',
	    380 => 'Ukraina',
	    381 => 'Serbien',
	    382 => 'Montenegro',
	    385 => 'Kroatien',
	    386 => 'Slovenien',
	    387 => 'Bosnien och Hercegovina',
	    389 => 'Makedonien',
	    39 => 'Italien',
	    40 => 'Rumänien',
	    41 => 'Schweiz',
	    420 => 'Tjeckien',
	    421 => 'Slovakien',
	    423 => 'Liechtenstein',
	    43 => 'Österrike',
	    44 => 'Storbritannien, Guernsey, Isle of Man, Jersey',
	    45 => 'Danmark',
	    46 => 'Sverige',
	    47 => 'Norge, Svalbard',
	    48 => 'Polen',
	    49 => 'Tyskland',
	    500 => 'Falklandsöarna',
	    501 => 'Belize',
	    502 => 'Guatemala',
	    503 => 'El salvador',
	    504 => 'Honduras',
	    505 => 'Nicaragua',
	    506 => 'Costa rica',
	    507 => 'Panama',
	    508 => 'Saint-Pierre och Miquelon',
	    509 => 'Haiti',
	    51 => 'Peru',
	    52 => 'Mexiko',
	    53 => 'Kuba',
	    54 => 'Argentina',
	    55 => 'Brasilien',
	    56 => 'Chile',
	    57 => 'Colombia',
	    58 => 'Venezuela',
	    590 => 'Saint-Barthélemy, Guadeloupe',
	    591 => 'Bolivia',
	    592 => 'Guyana',
	    593 => 'Ecuador',
	    594 => 'Franska Guyana',
	    595 => 'Paraguay',
	    596 => 'Martinique',
	    597 => 'Surinam',
	    598 => 'Uruguay',
	    599 => 'Nederländska antillerna',
	    60 => 'Malaysia',
	    61 => 'Julön, Australien, Kokosöarna',
	    62 => 'Indonesien',
	    63 => 'Filippinerna',
	    64 => 'Nya Zeeland, Pitcairnöarna',
	    65 => 'Singapore',
	    66 => 'Thailand',
	    670 => 'Östtimor',
	    672 => 'Norfolkön, Antarktis',
	    673 => 'Brunei',
	    674 => 'Nauru',
	    675 => 'Papua Nya Guinea',
	    676 => 'Tonga',
	    677 => 'Salomonöarna',
	    678 => 'Vanuatu',
	    679 => 'Fiji',
	    680 => 'Palau',
	    681 => 'Wallis- och futunaöarna',
	    682 => 'Cooköarna',
	    683 => 'Niue',
	    685 => 'Samoa',
	    686 => 'Kiribati',
	    687 => 'Nya Kaledonien',
	    688 => 'Tuvalu',
	    689 => 'Franska Polynesien',
	    690 => 'Tokelauöarna',
	    691 => 'Mikronesiska federationen',
	    692 => 'Marshallöarna',
	    7 => 'Ryssland, Kazakstan',
	    81 => 'Japan',
	    82 => 'Sydkorea',
	    84 => 'Vietnam',
	    850 => 'Nordkorea',
	    852 => 'Hongkong',
	    853 => 'Macau',
	    855 => 'Kambodja',
	    856 => 'Laos',
	    86 => 'Kina',
	    880 => 'Bangladesh',
	    886 => 'Taiwan',
	    90 => 'Turkiet',
	    91 => 'Indien',
	    92 => 'Pakistan',
	    93 => 'Afghanistan',
	    94 => 'Sri Lanka',
	    95 => 'Burma',
	    960 => 'Maldiverna',
	    961 => 'Libanon',
	    962 => 'Jordanien',
	    963 => 'Syrien',
	    964 => 'Irak',
	    965 => 'Kuwait',
	    966 => 'Saudiarabien',
	    967 => 'Jemen',
	    968 => 'Oman',
	    970 => 'Palestina',
	    971 => 'Förenade arabemiraten',
	    972 => 'Israel',
	    973 => 'Bahrain',
	    974 => 'Qatar',
	    975 => 'Bhutan',
	    976 => 'Mongoliet',
	    977 => 'Nepal',
	    98 => 'Iran',
	    992 => 'Tadzjikistan',
	    993 => 'Turkmenistan',
	    994 => 'Azerbajdzjan',
	    995 => 'Georgien',
	    996 => 'Kirgizistan',
	    998 => 'Uzbekistan'
	);


    /**
     * Lookup country code
     *
     * @param string $nr
     *
     * @return string Data found, empty string if nothing was found
     */
    public function lookup($nr)
    {
        if (isset(self::$_countries[$nr])) {
            
            return self::$_countries[$nr];
        }

        return '';
    }

}
