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
 * Maps country codes to english country names
 *
 * @package phplibphone
 *
 * @subpackage Library
 */
class CountriesEn implements \itbz\phplibphone\LookupInterface
{

    /**
     * The country code map
     *
     * @var array
     */
    static private $_countries = array(
        1 => 'United States, Canada, Puerto Rico',
        1242 => 'Bahamas',
        1246 => 'Barbados',
        1264 => 'Anguilla',
        1268 => 'Antigua And Barbuda',
        1284 => 'British Virgin Islands',
        1340 => 'U.S. Virgin Islands',
        1345 => 'Cayman Islands',
        1441 => 'Bermuda',
        1473 => 'Grenada',
        1649 => 'Turks and Caicos Islands',
        1664 => 'Montserrat',
        1670 => 'Northern Mariana Islands',
        1671 => 'Guam',
        1684 => 'American Samoa',
        1758 => 'Saint Lucia',
        1767 => 'Dominica',
        1784 => 'Saint Vincent and the Grenadines',
        1809 => 'Dominican Republic',
        1868 => 'Trinidad and Tobago',
        1869 => 'Saint Kitts and Nevis',
        1876 => 'Jamaica',
        20 => 'Egypt',
        212 => 'Western Sahara, Morocco',
        213 => 'Algeria',
        216 => 'Tunisia',
        218 => 'Libyan Arab Jamahiriya',
        220 => 'Gambia',
        221 => 'Senegal',
        222 => 'Mauritania',
        223 => 'Mali',
        224 => 'Guinea',
        225 => 'Côte D\'ivoire',
        226 => 'Burkina Faso',
        227 => 'Niger',
        228 => 'Togo',
        229 => 'Benin',
        230 => 'Mauritius',
        231 => 'Liberia',
        232 => 'Sierra Leone',
        233 => 'Ghana',
        234 => 'Nigeria',
        235 => 'Chad',
        236 => 'Central African Republic',
        237 => 'Cameroon',
        238 => 'Cape Verde',
        239 => 'Sao Tome and Principe',
        240 => 'Equatorial Guinea',
        241 => 'Gabon',
        242 => 'Congo',
        243 => 'The Democratic Republic Of The Congo',
        244 => 'Angola',
        245 => 'Guinea-bissau',
        246 => 'British Indian Ocean Territory',
        248 => 'Seychelles',
        249 => 'Sudan',
        250 => 'Rwanda',
        251 => 'Ethiopia',
        252 => 'Somalia',
        253 => 'Djibouti',
        254 => 'Kenya',
        255 => 'United Republic of Tanzania',
        256 => 'Uganda',
        257 => 'Burundi',
        258 => 'Mozambique',
        260 => 'Zambia',
        261 => 'Madagascar',
        262 => 'Réunion',
        263 => 'Zimbabwe',
        264 => 'Namibia',
        265 => 'Malawi',
        266 => 'Lesotho',
        267 => 'Botswana',
        268 => 'Swaziland',
        269 => 'Mayotte, Comoros',
        27 => 'South Africa',
        290 => 'Saint Helena',
        291 => 'Eritrea',
        297 => 'Aruba',
        298 => 'Faroe Islands',
        299 => 'Greenland',
        30 => 'Greece',
        31 => 'Netherlands',
        32 => 'Belgium',
        33 => 'France, France Métropolitaine',
        34 => 'Spain',
        350 => 'Gibraltar',
        351 => 'Portugal',
        352 => 'Luxembourg',
        353 => 'Ireland',
        354 => 'Iceland',
        355 => 'Albania',
        356 => 'Malta',
        357 => 'Cyprus',
        358 => 'Finland',
        359 => 'Bulgaria',
        36 => 'Hungary',
        370 => 'Lithuania',
        371 => 'Latvia',
        372 => 'Estonia',
        373 => 'Moldova',
        374 => 'Armenia',
        375 => 'Belarus',
        376 => 'Andorra',
        377 => 'Monaco',
        378 => 'San Marino',
        379 => 'Vatican City State',
        380 => 'Ukraine',
        381 => 'Serbien',
        382 => 'Montenegro',
        385 => 'Croatia',
        386 => 'Slovenia',
        387 => 'Bosnia and Herzegovina',
        389 => 'Macedonia',
        39 => 'Italy',
        40 => 'Romania',
        41 => 'Switzerland',
        420 => 'Czech Republic',
        421 => 'Slovakia',
        423 => 'Liechtenstein',
        43 => 'Austria',
        44 => 'United Kingdom, Guernsey, Isle of Man, Jersey',
        45 => 'Denmark',
        46 => 'Sweden',
        47 => 'Norway, Svalbard and Jan Mayen',
        48 => 'Poland',
        49 => 'Germany',
        500 => 'Falkland Islands',
        501 => 'Belize',
        502 => 'Guatemala',
        503 => 'El Salvador',
        504 => 'Honduras',
        505 => 'Nicaragua',
        506 => 'Costa Rica',
        507 => 'Panama',
        508 => 'Saint Pierre and Miquelon',
        509 => 'Haiti',
        51 => 'Peru',
        52 => 'Mexico',
        53 => 'Cuba',
        54 => 'Argentina',
        55 => 'Brazil',
        56 => 'Chile',
        57 => 'Colombia',
        58 => 'Venezuela',
        590 => 'Saint-Barthélemy, Guadeloupe',
        591 => 'Bolivia',
        592 => 'Guyana',
        593 => 'Ecuador',
        594 => 'French Guiana',
        595 => 'Paraguay',
        596 => 'Martinique',
        597 => 'Suriname',
        598 => 'Uruguay',
        599 => 'Netherlands Antilles',
        60 => 'Malaysia',
        61 => 'Christmas Island, Australia, Cocos Islands',
        62 => 'Indonesia',
        63 => 'Philippines',
        64 => 'New Zealand, Pitcairn',
        65 => 'Singapore',
        66 => 'Thailand',
        670 => 'East Timor',
        672 => 'Norfolk Island, Antarctica',
        673 => 'Brunei Darussalam',
        674 => 'Nauru',
        675 => 'Papua New Guinea',
        676 => 'Tonga',
        677 => 'Solomon Islands',
        678 => 'Vanuatu',
        679 => 'Fiji',
        680 => 'Palau',
        681 => 'Wallis And Futuna',
        682 => 'Cook Islands',
        683 => 'Niue',
        685 => 'Samoa',
        686 => 'Kiribati',
        687 => 'New Caledonia',
        688 => 'Tuvalu',
        689 => 'French Polynesia',
        690 => 'Tokelau',
        691 => 'Federated States Of Micronesia',
        692 => 'Marshall Islands',
        7 => 'Russian Federation, Kazakhstan',
        81 => 'Japan',
        82 => 'Republic Of Korea',
        84 => 'Viet Nam',
        850 => 'North Korea',
        852 => 'Hong Kong',
        853 => 'Macao',
        855 => 'Cambodia',
        856 => 'Lao',
        86 => 'China',
        880 => 'Bangladesh',
        886 => 'Taiwan',
        90 => 'Turkey',
        91 => 'India',
        92 => 'Pakistan',
        93 => 'Afghanistan',
        94 => 'Sri Lanka',
        95 => 'Myanmar',
        960 => 'Maldives',
        961 => 'Lebanon',
        962 => 'Jordan',
        963 => 'Syrian Arab Republic',
        964 => 'Iraq',
        965 => 'Kuwait',
        966 => 'Saudi Arabia',
        967 => 'Yemen',
        968 => 'Oman',
        970 => 'State of Palestine',
        971 => 'United Arab Emirates',
        972 => 'Israel',
        973 => 'Bahrain',
        974 => 'Qatar',
        975 => 'Bhutan',
        976 => 'Mongolia',
        977 => 'Nepal',
        98 => 'Iran',
        992 => 'Tajikistan',
        993 => 'Turkmenistan',
        994 => 'Azerbaijan',
        995 => 'Georgia',
        996 => 'Kyrgyzstan',
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
