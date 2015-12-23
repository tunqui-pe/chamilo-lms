<?php
/* For licensing terms, see /license.txt */
/**
 * @package chamilo.include.internationalization
 */
/**
 * The following table contains two types of conventions concerning person names:
 *
 * "format" - determines how a full person name to be formatted, i.e. in what order the title, the first_name and the last_name to be placed.
 * You might need to correct the value for your language. The possible values are:
 * title first_name last_name  - Western order;
 * title last_name first_name  - Eastern order;
 * title last_name, first_name - Western libraries order.
 * Placing the title (Dr, Mr, Miss, etc) depends on the tradition in you country.
 * @link http://en.wikipedia.org/wiki/Personal_name#Naming_convention
 *
 * To see the complete list:
 *
 *  $language = Intl::getLocaleBundle()->getLocaleNames('en');
 *    echo '<pre>';print_r($language);exit;
 *
 * "sort_by" - determines you preferable way of sorting person names. The possible values are:
 * first_name                  - sorting names with priority for the first name;
 * last_name                   - sorting names with priority for the last name.
 */
return [
	'af' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//afrikaans
	'sq' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//albanian
	//'alemannic' =>        ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'am' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//amharic
	'hy' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//armenian
	'ar' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//arabic
	//'asturian' =>         ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'bs' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//bosnian
	'pt_BR' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//brazilian Portuguese (Brazil)
	'br' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//breton
	'bg' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//bulgarian
	'ca' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//catalan
	'hr' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//croatian
	'cs' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//czech
	'da' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//danish
	//'dari' =>             ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'nl' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//dutch
	'en' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//english
	'eo' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//esperanto
	'et' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//estonian
	'eu' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//basque
	'fi' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//finnish
	'fr' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//french
	'fy' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//frisian
	//'friulian' =>         ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'gl' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	//galician
	'ka' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//georgian
	'de' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//german
	'el' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//greek
	//'hawaiian' =>         ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'he' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//hebrew
	'hi' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//hindi
	'hu' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// hungarian Eastern order
	'is' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//icelandic
	'id' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//indonesian
	'ga' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//irish
	'it' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//italian
	'ja' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// japanese Eastern order
	'ko' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// korean Eastern order
	//'latin' =>            ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'lv' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//latvian
	'lt' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//lithuanian
	'mk' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//macedonian
	'ms' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// malay Eastern order
	'gv' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//manx
	'mr' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//marathi
	//'middle_frisian' =>   ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	//'mingo' =>            ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'ne' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//nepali
	'no' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//norwegian
	//'occitan' =>          ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'ps' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//pashto
	'fa' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//persian
	'pl' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//polish
	'pt' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	// Portuguese
	'qu' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	// Quechua
	'ro' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//romanian
	//'rumantsch' =>        ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'ru' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//russian
	//'sanskrit' =>         ['format' => 'title first_name last_name',  'sort_by' => 'first_name'],
	'sr' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//serbian
	'sr_Cyrl' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//serbian_cyrillic
	'zh_Hans' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// Eastern order //simpl_chinese
	'sk' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//slovak
	'sl' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//slovenian
	'es' => [
		'format' => 'title last_name, first_name',
		'sort_by' => 'last_name',
	],
	// spanish
	'es_PE' => [
		'format' => 'title last_name, first_name',
		'sort_by' => 'last_name',
	],
	// spanish PERU
	'sw' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//swahili
	'sv' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//swedish
	'tl' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//tagalog
	'ta' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	// tamil
	'th' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//thai
	'zh_Hant' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// Eastern order //trad_chinese
	'tr' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//turkish
	'uk' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//ukrainian
	'vi' => [
		'format' => 'title last_name first_name',
		'sort_by' => 'last_name',
	],
	// Eastern order vietnamese
	'cy' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//welsh
	'yi' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	],
	//yiddish
	'yo' => [
		'format' => 'title first_name last_name',
		'sort_by' => 'first_name',
	]
	//yoruba
];
