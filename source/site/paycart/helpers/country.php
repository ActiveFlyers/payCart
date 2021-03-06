<?php

/**
 * @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Country Helper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Country Helper
 * @author Rimjhim Jain
 */

class PaycartHelperCountry extends PaycartHelper
{
	/**
	 * Return an array containing all the country name, isocode2, isocode3
	 */
	function getCountryList()
	{
		return array(
				  array('title' => 'Afghanistan','isocode2' => 'AF','isocode3' => 'AFG'),
				  array('title' => 'Åland Islands','isocode2' => 'AX','isocode3' => 'ALA'),
				  array('title' => 'Albania','isocode2' => 'AL','isocode3' => 'ALB'),
				  array('title' => 'Algeria','isocode2' => 'DZ','isocode3' => 'DZA'),
				  array('title' => 'American Samoa','isocode2' => 'AS','isocode3' => 'ASM'),
				  array('title' => 'Andorra','isocode2' => 'AD','isocode3' => 'AND'),
				  array('title' => 'Angola','isocode2' => 'AO','isocode3' => 'AGO'),
				  array('title' => 'Anguilla','isocode2' => 'AI','isocode3' => 'AIA'),
				  array('title' => 'Antarctica','isocode2' => 'AQ','isocode3' => 'ATA'),
				  array('title' => 'Antigua and Barbuda','isocode2' => 'AG','isocode3' => 'ATG'),
				  array('title' => 'Argentina','isocode2' => 'AR','isocode3' => 'ARG'),
				  array('title' => 'Armenia','isocode2' => 'AM','isocode3' => 'ARM'),
				  array('title' => 'Aruba','isocode2' => 'AW','isocode3' => 'ABW'),
				  array('title' => 'Australia','isocode2' => 'AU','isocode3' => 'AUS'),
				  array('title' => 'Austria','isocode2' => 'AT','isocode3' => 'AUT'),
				  array('title' => 'Azerbaijan','isocode2' => 'AZ','isocode3' => 'AZE'),
				  array('title' => 'Bahamas','isocode2' => 'BS','isocode3' => 'BHS'),
				  array('title' => 'Bahrain','isocode2' => 'BH','isocode3' => 'BHR'),
				  array('title' => 'Bangladesh','isocode2' => 'BD','isocode3' => 'BGD'),
				  array('title' => 'Barbados','isocode2' => 'BB','isocode3' => 'BRB'),
				  array('title' => 'Belarus','isocode2' => 'BY','isocode3' => 'BLR'),
				  array('title' => 'Belgium','isocode2' => 'BE','isocode3' => 'BEL'),
				  array('title' => 'Belize','isocode2' => 'BZ','isocode3' => 'BLZ'),
				  array('title' => 'Benin','isocode2' => 'BJ','isocode3' => 'BEN'),
				  array('title' => 'Bermuda','isocode2' => 'BM','isocode3' => 'BMU'),
				  array('title' => 'Bhutan','isocode2' => 'BT','isocode3' => 'BTN'),
				  array('title' => 'Bolivia (Plurinational State of)','isocode2' => 'BO','isocode3' => 'BOL'),
				  array('title' => 'Bonaire ,Sint Eustatius and Saba','isocode2' => 'BQ','isocode3' => 'BES'),
				  array('title' => 'Bosnia and Herzegovina','isocode2' => 'BA','isocode3' => 'BIH'),
				  array('title' => 'Botswana','isocode2' => 'BW','isocode3' => 'BWA'),
				  array('title' => 'Bouvet Island','isocode2' => 'BV','isocode3' => 'BVT'),
				  array('title' => 'Brazil','isocode2' => 'BR','isocode3' => 'BRA'),
				  array('title' => 'British Indian Ocean Territory','isocode2' => 'IO','isocode3' => 'IOT'),
				  array('title' => 'Brunei Darussalam','isocode2' => 'BN','isocode3' => 'BRN'),
				  array('title' => 'Bulgaria','isocode2' => 'BG','isocode3' => 'BGR'),
				  array('title' => 'Burkina Faso','isocode2' => 'BF','isocode3' => 'BFA'),
				  array('title' => 'Burundi','isocode2' => 'BI','isocode3' => 'BDI'),
				  array('title' => 'Cambodia','isocode2' => 'KH','isocode3' => 'KHM'),
				  array('title' => 'Cameroon','isocode2' => 'CM','isocode3' => 'CMR'),
				  array('title' => 'Canada','isocode2' => 'CA','isocode3' => 'CAN'),
				  array('title' => 'Cabo Verde','isocode2' => 'CV','isocode3' => 'CPV'),
				  array('title' => 'Cayman Islands','isocode2' => 'KY','isocode3' => 'CYM'),
				  array('title' => 'Central African Republic','isocode2' => 'CF','isocode3' => 'CAF'),
				  array('title' => 'Chad','isocode2' => 'TD','isocode3' => 'TCD'),
				  array('title' => 'Chile','isocode2' => 'CL','isocode3' => 'CHL'),
				  array('title' => 'China','isocode2' => 'CN','isocode3' => 'CHN'),
				  array('title' => 'Christmas Island','isocode2' => 'CX','isocode3' => 'CXR'),
				  array('title' => 'Cocos (Keeling) Islands','isocode2' => 'CC','isocode3' => 'CCK'),
				  array('title' => 'Colombia','isocode2' => 'CO','isocode3' => 'COL'),
				  array('title' => 'Comoros','isocode2' => 'KM','isocode3' => 'COM'),
				  array('title' => 'Congo','isocode2' => 'CG','isocode3' => 'COG'),
				  array('title' => 'Congo (Democratic Republic of the)','isocode2' => 'CD','isocode3' => 'COD'),
				  array('title' => 'Cook Islands','isocode2' => 'CK','isocode3' => 'COK'),
				  array('title' => 'Costa Rica','isocode2' => 'CR','isocode3' => 'CRI'),
				  array('title' => 'Côte d\'Ivoire','isocode2' => 'CI','isocode3' => 'CIV'),
				  array('title' => 'Croatia','isocode2' => 'HR','isocode3' => 'HRV'),
				  array('title' => 'Cuba','isocode2' => 'CU','isocode3' => 'CUB'),
				  array('title' => 'Curaçao','isocode2' => 'CW','isocode3' => 'CUW'),
				  array('title' => 'Cyprus','isocode2' => 'CY','isocode3' => 'CYP'),
				  array('title' => 'Czech Republic','isocode2' => 'CZ','isocode3' => 'CZE'),
				  array('title' => 'Denmark','isocode2' => 'DK','isocode3' => 'DNK'),
				  array('title' => 'Djibouti','isocode2' => 'DJ','isocode3' => 'DJI'),
				  array('title' => 'Dominica','isocode2' => 'DM','isocode3' => 'DMA'),
				  array('title' => 'Dominican Republic','isocode2' => 'DO','isocode3' => 'DOM'),
				  array('title' => 'Ecuador','isocode2' => 'EC','isocode3' => 'ECU'),
				  array('title' => 'Egypt','isocode2' => 'EG','isocode3' => 'EGY'),
				  array('title' => 'El Salvador','isocode2' => 'SV','isocode3' => 'SLV'),
				  array('title' => 'Equatorial Guinea','isocode2' => 'GQ','isocode3' => 'GNQ'),
				  array('title' => 'Eritrea','isocode2' => 'ER','isocode3' => 'ERI'),
				  array('title' => 'Estonia','isocode2' => 'EE','isocode3' => 'EST'),
				  array('title' => 'Ethiopia','isocode2' => 'ET','isocode3' => 'ETH'),
				  array('title' => 'Falkland Islands (Malvinas)','isocode2' => 'FK','isocode3' => 'FLK'),
				  array('title' => 'Faroe Islands','isocode2' => 'FO','isocode3' => 'FRO'),
				  array('title' => 'Fiji','isocode2' => 'FJ','isocode3' => 'FJI'),
				  array('title' => 'Finland','isocode2' => 'FI','isocode3' => 'FIN'),
				  array('title' => 'France','isocode2' => 'FR','isocode3' => 'FRA'),
				  array('title' => 'French Guiana','isocode2' => 'GF','isocode3' => 'GUF'),
				  array('title' => 'French Polynesia','isocode2' => 'PF','isocode3' => 'PYF'),
				  array('title' => 'French Southern Territories','isocode2' => 'TF','isocode3' => 'ATF'),
				  array('title' => 'Gabon','isocode2' => 'GA','isocode3' => 'GAB'),
				  array('title' => 'Gambia','isocode2' => 'GM','isocode3' => 'GMB'),
				  array('title' => 'Georgia','isocode2' => 'GE','isocode3' => 'GEO'),
				  array('title' => 'Germany','isocode2' => 'DE','isocode3' => 'DEU'),
				  array('title' => 'Ghana','isocode2' => 'GH','isocode3' => 'GHA'),
				  array('title' => 'Gibraltar','isocode2' => 'GI','isocode3' => 'GIB'),
				  array('title' => 'Greece','isocode2' => 'GR','isocode3' => 'GRC'),
				  array('title' => 'Greenland','isocode2' => 'GL','isocode3' => 'GRL'),
				  array('title' => 'Grenada','isocode2' => 'GD','isocode3' => 'GRD'),
				  array('title' => 'Guadeloupe','isocode2' => 'GP','isocode3' => 'GLP'),
				  array('title' => 'Guam','isocode2' => 'GU','isocode3' => 'GUM'),
				  array('title' => 'Guatemala','isocode2' => 'GT','isocode3' => 'GTM'),
				  array('title' => 'Guernsey','isocode2' => 'GG','isocode3' => 'GGY'),
				  array('title' => 'Guinea','isocode2' => 'GN','isocode3' => 'GIN'),
				  array('title' => 'Guinea-Bissau','isocode2' => 'GW','isocode3' => 'GNB'),
				  array('title' => 'Guyana','isocode2' => 'GY','isocode3' => 'GUY'),
				  array('title' => 'Haiti','isocode2' => 'HT','isocode3' => 'HTI'),
				  array('title' => 'Heard Island and McDonald Islands','isocode2' => 'HM','isocode3' => 'HMD'),
				  array('title' => 'Holy See','isocode2' => 'VA','isocode3' => 'VAT'),
				  array('title' => 'Honduras','isocode2' => 'HN','isocode3' => 'HND'),
				  array('title' => 'Hong Kong','isocode2' => 'HK','isocode3' => 'HKG'),
				  array('title' => 'Hungary','isocode2' => 'HU','isocode3' => 'HUN'),
				  array('title' => 'Iceland','isocode2' => 'IS','isocode3' => 'ISL'),
				  array('title' => 'India','isocode2' => 'IN','isocode3' => 'IND'),
				  array('title' => 'Indonesia','isocode2' => 'ID','isocode3' => 'IDN'),
				  array('title' => 'Iran (Islamic Republic of)','isocode2' => 'IR','isocode3' => 'IRN'),
				  array('title' => 'Iraq','isocode2' => 'IQ','isocode3' => 'IRQ'),
				  array('title' => 'Ireland','isocode2' => 'IE','isocode3' => 'IRL'),
				  array('title' => 'Isle of Man','isocode2' => 'IM','isocode3' => 'IMN'),
				  array('title' => 'Israel','isocode2' => 'IL','isocode3' => 'ISR'),
				  array('title' => 'Italy','isocode2' => 'IT','isocode3' => 'ITA'),
				  array('title' => 'Jamaica','isocode2' => 'JM','isocode3' => 'JAM'),
				  array('title' => 'Japan','isocode2' => 'JP','isocode3' => 'JPN'),
				  array('title' => 'Jersey','isocode2' => 'JE','isocode3' => 'JEY'),
				  array('title' => 'Jordan','isocode2' => 'JO','isocode3' => 'JOR'),
				  array('title' => 'Kazakhstan','isocode2' => 'KZ','isocode3' => 'KAZ'),
				  array('title' => 'Kenya','isocode2' => 'KE','isocode3' => 'KEN'),
				  array('title' => 'Kiribati','isocode2' => 'KI','isocode3' => 'KIR'),
				  array('title' => 'Korea (Democratic People\'s Republic of)','isocode2' => 'KP','isocode3' => 'PRK'),
				  array('title' => 'Korea (Republic of)','isocode2' => 'KR','isocode3' => 'KOR'),
				  array('title' => 'Kuwait','isocode2' => 'KW','isocode3' => 'KWT'),
				  array('title' => 'Kyrgyzstan','isocode2' => 'KG','isocode3' => 'KGZ'),
				  array('title' => 'Lao People\'s Democratic Republic','isocode2' => 'LA','isocode3' => 'LAO'),
				  array('title' => 'Latvia','isocode2' => 'LV','isocode3' => 'LVA'),
				  array('title' => 'Lebanon','isocode2' => 'LB','isocode3' => 'LBN'),
				  array('title' => 'Lesotho','isocode2' => 'LS','isocode3' => 'LSO'),
				  array('title' => 'Liberia','isocode2' => 'LR','isocode3' => 'LBR'),
				  array('title' => 'Libya','isocode2' => 'LY','isocode3' => 'LBY'),
				  array('title' => 'Liechtenstein','isocode2' => 'LI','isocode3' => 'LIE'),
				  array('title' => 'Lithuania','isocode2' => 'LT','isocode3' => 'LTU'),
				  array('title' => 'Luxembourg','isocode2' => 'LU','isocode3' => 'LUX'),
				  array('title' => 'Macao','isocode2' => 'MO','isocode3' => 'MAC'),
				  array('title' => 'Macedonia (the former Yugoslav Republic of)','isocode2' => 'MK','isocode3' => 'MKD'),
				  array('title' => 'Madagascar','isocode2' => 'MG','isocode3' => 'MDG'),
				  array('title' => 'Malawi','isocode2' => 'MW','isocode3' => 'MWI'),
				  array('title' => 'Malaysia','isocode2' => 'MY','isocode3' => 'MYS'),
				  array('title' => 'Maldives','isocode2' => 'MV','isocode3' => 'MDV'),
				  array('title' => 'Mali','isocode2' => 'ML','isocode3' => 'MLI'),
				  array('title' => 'Malta','isocode2' => 'MT','isocode3' => 'MLT'),
				  array('title' => 'Marshall Islands','isocode2' => 'MH','isocode3' => 'MHL'),
				  array('title' => 'Martinique','isocode2' => 'MQ','isocode3' => 'MTQ'),
				  array('title' => 'Mauritania','isocode2' => 'MR','isocode3' => 'MRT'),
				  array('title' => 'Mauritius','isocode2' => 'MU','isocode3' => 'MUS'),
				  array('title' => 'Mayotte','isocode2' => 'YT','isocode3' => 'MYT'),
				  array('title' => 'Mexico','isocode2' => 'MX','isocode3' => 'MEX'),
				  array('title' => 'Micronesia (Federated States of)','isocode2' => 'FM','isocode3' => 'FSM'),
				  array('title' => 'Moldova (Republic of)','isocode2' => 'MD','isocode3' => 'MDA'),
				  array('title' => 'Monaco','isocode2' => 'MC','isocode3' => 'MCO'),
				  array('title' => 'Mongolia','isocode2' => 'MN','isocode3' => 'MNG'),
				  array('title' => 'Montenegro','isocode2' => 'ME','isocode3' => 'MNE'),
				  array('title' => 'Montserrat','isocode2' => 'MS','isocode3' => 'MSR'),
				  array('title' => 'Morocco','isocode2' => 'MA','isocode3' => 'MAR'),
				  array('title' => 'Mozambique','isocode2' => 'MZ','isocode3' => 'MOZ'),
				  array('title' => 'Myanmar','isocode2' => 'MM','isocode3' => 'MMR'),
				  array('title' => 'Namibia','isocode2' => 'NA','isocode3' => 'NAM'),
				  array('title' => 'Nauru','isocode2' => 'NR','isocode3' => 'NRU'),
				  array('title' => 'Nepal','isocode2' => 'NP','isocode3' => 'NPL'),
				  array('title' => 'Netherlands','isocode2' => 'NL','isocode3' => 'NLD'),
				  array('title' => 'New Caledonia','isocode2' => 'NC','isocode3' => 'NCL'),
				  array('title' => 'New Zealand','isocode2' => 'NZ','isocode3' => 'NZL'),
				  array('title' => 'Nicaragua','isocode2' => 'NI','isocode3' => 'NIC'),
				  array('title' => 'Niger','isocode2' => 'NE','isocode3' => 'NER'),
				  array('title' => 'Nigeria','isocode2' => 'NG','isocode3' => 'NGA'),
				  array('title' => 'Niue','isocode2' => 'NU','isocode3' => 'NIU'),
				  array('title' => 'Norfolk Island','isocode2' => 'NF','isocode3' => 'NFK'),
				  array('title' => 'Northern Mariana Islands','isocode2' => 'MP','isocode3' => 'MNP'),
				  array('title' => 'Norway','isocode2' => 'NO','isocode3' => 'NOR'),
				  array('title' => 'Oman','isocode2' => 'OM','isocode3' => 'OMN'),
				  array('title' => 'Pakistan','isocode2' => 'PK','isocode3' => 'PAK'),
				  array('title' => 'Palau','isocode2' => 'PW','isocode3' => 'PLW'),
				  array('title' => 'Palestine, State of','isocode2' => 'PS','isocode3' => 'PSE'),
				  array('title' => 'Panama','isocode2' => 'PA','isocode3' => 'PAN'),
				  array('title' => 'Papua New Guinea','isocode2' => 'PG','isocode3' => 'PNG'),
				  array('title' => 'Paraguay','isocode2' => 'PY','isocode3' => 'PRY'),
				  array('title' => 'Peru','isocode2' => 'PE','isocode3' => 'PER'),
				  array('title' => 'Philippines','isocode2' => 'PH','isocode3' => 'PHL'),
				  array('title' => 'Pitcairn','isocode2' => 'PN','isocode3' => 'PCN'),
				  array('title' => 'Poland','isocode2' => 'PL','isocode3' => 'POL'),
				  array('title' => 'Portugal','isocode2' => 'PT','isocode3' => 'PRT'),
				  array('title' => 'Puerto Rico','isocode2' => 'PR','isocode3' => 'PRI'),
				  array('title' => 'Qatar','isocode2' => 'QA','isocode3' => 'QAT'),
				  array('title' => 'Réunion','isocode2' => 'RE','isocode3' => 'REU'),
				  array('title' => 'Romania','isocode2' => 'RO','isocode3' => 'ROU'),
				  array('title' => 'Russian Federation','isocode2' => 'RU','isocode3' => 'RUS'),
				  array('title' => 'Rwanda','isocode2' => 'RW','isocode3' => 'RWA'),
				  array('title' => 'Saint Barthélemy','isocode2' => 'BL','isocode3' => 'BLM'),
				  array('title' => 'Saint Helena, Ascension and Tristan da Cunha','isocode2' => 'SH','isocode3' => 'SHN'),
				  array('title' => 'Saint Kitts and Nevis','isocode2' => 'KN','isocode3' => 'KNA'),
				  array('title' => 'Saint Lucia','isocode2' => 'LC','isocode3' => 'LCA'),
				  array('title' => 'Saint Martin (French part)','isocode2' => 'MF','isocode3' => 'MAF'),
				  array('title' => 'Saint Pierre and Miquelon','isocode2' => 'PM','isocode3' => 'SPM'),
				  array('title' => 'Saint Vincent and the Grenadines','isocode2' => 'VC','isocode3' => 'VCT'),
				  array('title' => 'Samoa','isocode2' => 'WS','isocode3' => 'WSM'),
				  array('title' => 'San Marino','isocode2' => 'SM','isocode3' => 'SMR'),
				  array('title' => 'Sao Tome and Principe','isocode2' => 'ST','isocode3' => 'STP'),
				  array('title' => 'Saudi Arabia','isocode2' => 'SA','isocode3' => 'SAU'),
				  array('title' => 'Senegal','isocode2' => 'SN','isocode3' => 'SEN'),
				  array('title' => 'Serbia','isocode2' => 'RS','isocode3' => 'SRB'),
				  array('title' => 'Seychelles','isocode2' => 'SC','isocode3' => 'SYC'),
				  array('title' => 'Sierra Leone','isocode2' => 'SL','isocode3' => 'SLE'),
				  array('title' => 'Singapore','isocode2' => 'SG','isocode3' => 'SGP'),
				  array('title' => 'Sint Maarten (Dutch part)','isocode2' => 'SX','isocode3' => 'SXM'),
				  array('title' => 'Slovakia','isocode2' => 'SK','isocode3' => 'SVK'),
				  array('title' => 'Slovenia','isocode2' => 'SI','isocode3' => 'SVN'),
				  array('title' => 'Solomon Islands','isocode2' => 'SB','isocode3' => 'SLB'),
				  array('title' => 'Somalia','isocode2' => 'SO','isocode3' => 'SOM'),
				  array('title' => 'South Africa','isocode2' => 'ZA','isocode3' => 'ZAF'),
				  array('title' => 'South Georgia and the South Sandwich Islands','isocode2' => 'GS','isocode3' => 'SGS'),
				  array('title' => 'South Sudan','isocode2' => 'SS','isocode3' => 'SSD'),
				  array('title' => 'Spain','isocode2' => 'ES','isocode3' => 'ESP'),
				  array('title' => 'Sri Lanka','isocode2' => 'LK','isocode3' => 'LKA'),
				  array('title' => 'Sudan','isocode2' => 'SD','isocode3' => 'SDN'),
				  array('title' => 'Suriname','isocode2' => 'SR','isocode3' => 'SUR'),
				  array('title' => 'Svalbard and Jan Mayen','isocode2' => 'SJ','isocode3' => 'SJM'),
				  array('title' => 'Swaziland','isocode2' => 'SZ','isocode3' => 'SWZ'),
				  array('title' => 'Sweden','isocode2' => 'SE','isocode3' => 'SWE'),
				  array('title' => 'Switzerland','isocode2' => 'CH','isocode3' => 'CHE'),
				  array('title' => 'Syrian Arab Republic','isocode2' => 'SY','isocode3' => 'SYR'),
				  array('title' => 'Taiwan, Province of China','isocode2' => 'TW','isocode3' => 'TWN'),
				  array('title' => 'Tajikistan','isocode2' => 'TJ','isocode3' => 'TJK'),
				  array('title' => 'Tanzania, United Republic of','isocode2' => 'TZ','isocode3' => 'TZA'),
				  array('title' => 'Thailand','isocode2' => 'TH','isocode3' => 'THA'),
				  array('title' => 'Timor-Leste','isocode2' => 'TL','isocode3' => 'TLS'),
				  array('title' => 'Togo','isocode2' => 'TG','isocode3' => 'TGO'),
				  array('title' => 'Tokelau','isocode2' => 'TK','isocode3' => 'TKL'),
				  array('title' => 'Tonga','isocode2' => 'TO','isocode3' => 'TON'),
				  array('title' => 'Trinidad and Tobago','isocode2' => 'TT','isocode3' => 'TTO'),
				  array('title' => 'Tunisia','isocode2' => 'TN','isocode3' => 'TUN'),
				  array('title' => 'Turkey','isocode2' => 'TR','isocode3' => 'TUR'),
				  array('title' => 'Turkmenistan','isocode2' => 'TM','isocode3' => 'TKM'),
				  array('title' => 'Turks and Caicos Islands','isocode2' => 'TC','isocode3' => 'TCA'),
				  array('title' => 'Tuvalu','isocode2' => 'TV','isocode3' => 'TUV'),
				  array('title' => 'Uganda','isocode2' => 'UG','isocode3' => 'UGA'),
				  array('title' => 'Ukraine','isocode2' => 'UA','isocode3' => 'UKR'),
				  array('title' => 'United Arab Emirates','isocode2' => 'AE','isocode3' => 'ARE'),
				  array('title' => 'United Kingdom of Great Britain and Northern Ireland','isocode2' => 'GB','isocode3' => 'GBR'),
				  array('title' => 'United States of America','isocode2' => 'US','isocode3' => 'USA'),
				  array('title' => 'United States Minor Outlying Islands','isocode2' => 'UM','isocode3' => 'UMI'),
				  array('title' => 'Uruguay','isocode2' => 'UY','isocode3' => 'URY'),
				  array('title' => 'Uzbekistan','isocode2' => 'UZ','isocode3' => 'UZB'),
				  array('title' => 'Vanuatu','isocode2' => 'VU','isocode3' => 'VUT'),
				  array('title' => 'Venezuela (Bolivarian Republic of)','isocode2' => 'VE','isocode3' => 'VEN'),
				  array('title' => 'Viet Nam','isocode2' => 'VN','isocode3' => 'VNM'),
				  array('title' => 'Virgin Islands (British)','isocode2' => 'VG','isocode3' => 'VGB'),
				  array('title' => 'Virgin Islands (U.S.)','isocode2' => 'VI','isocode3' => 'VIR'),
				  array('title' => 'Wallis and Futuna','isocode2' => 'WF','isocode3' => 'WLF'),
				  array('title' => 'Western Sahara','isocode2' => 'EH','isocode3' => 'ESH'),
				  array('title' => 'Yemen','isocode2' => 'YE','isocode3' => 'YEM'),
				  array('title' => 'Zambia','isocode2' => 'ZM','isocode3' => 'ZMB'),
				  array('title' => 'Zimbabwe','isocode2' => 'ZW','isocode3' => 'ZWE')
			);
	}
}