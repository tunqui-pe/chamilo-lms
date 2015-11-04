<?php
/* For licensing terms, see /license.txt */

$sysPath = __DIR__.'/../../';
require_once $sysPath.'main/inc/global.inc.php';

//Source language do not change
$dir = $sysPath.'main/lang/';

//Destination
$save_path = $sysPath.'src/Chamilo/CoreBundle/Resources/translations/';

//The new po files will be saved in  $dir.'/LC_MESSAGES/';

if (!is_dir($save_path)) {
    mkdir($save_path);
}
/*
if (!is_dir($save_dir_path).'/LC_MESSAGES') {
    mkdir($save_dir_path.'/LC_MESSAGES');
}*/

$englishDir = $sysPath.'main/lang/english';

$languages = [
    'arabic' => 'ar',
    'asturian' => 'ast',
    'basque' => 'eu',
    'bengali' => 'bn',
    'bosnian' => 'bs',
    'brazilian' => 'pt-BR',
    'bulgarian' => 'bg',
    'catalan' => 'ca',
    'croatian' => 'hr',
    'czech' => 'cs',
    'danish' => 'da',
    'dari' => 'prs',
    'dutch' => 'nl',
    'english' => 'en',
    'esperanto' => 'eo',
    'faroese',
    'fo',
    'finnish' => 'fi',
    'french' => 'fr',
    'friulian' => 'fur',
    'galician' => 'gl',
    'georgian' => 'ka',
    'german' => 'de',
    'greek' => 'el',
    'hebrew' => 'he',
    'hindi' => 'hi',
    'hungarian' => 'hu',
    'indonesian' => 'id',
    'italian' => 'it',
    'japanese' => 'ja',
    'korean' => 'ko',
    'latvian' => 'lv',
    'lithuanian' => 'lt',
    'macedonian' => 'mk',
    'malay' => 'ms',
    'norwegian' => 'no',
    'occitan' => 'oc',
    'pashto' => 'ps',
    'persian' => 'fa',
    'polish' => 'pl',
    'portuguese' => 'pt',
    'quechua_cusco' => 'qu',
    'romanian' => 'ro',
    'russian' => 'ru',
    'serbian' => 'sr',
    'simpl_chinese' => 'zh',
    'slovak' => 'sk',
    'slovenian' => 'sl',
    'somali' => 'so',
    'spanish' => 'es',
    'swahili' => 'sw',
    'swedish' => 'sv',
    'tagalog',
    'tl',
    'thai' => 'th',
    'tibetan',
    'bo',
    'trad_chinese' => 'zh-TW',
    'turkish' => 'tr',
    'ukrainian' => 'uk',
    'vietnamese' => 'vi',
    'xhosa' => 'xh',
    'yoruba' => 'yo',
];
$simple = ['spanish', 'french', 'english'];
$iterator = new FilesystemIterator($dir);
foreach ($iterator as $folder) {
    if ($folder->isDir()) {
        $langPath = $folder->getPathname();

        if (in_array($folder->getBasename(), $simple)) {
            continue;
        }

        $langIterator = new FilesystemIterator($langPath);
        $filter = new RegexIterator($langIterator, '/\.(php)$/');
        foreach ($filter as $phpFile) {
            $phpFilePath = $phpFile->getPathname();
            if ($phpFile->getBasename() != 'trad4all.inc.php') {
                continue;
            }
            $po = file($phpFilePath);
            $translations = array();

            $englishFile = $englishDir.'/'.$phpFile->getBasename();
            echo $englishFile.PHP_EOL;
            foreach ($po as $line) {
                $pos = strpos($line, '=');
                if ($pos) {
                    $variable = (substr($line, 1, $pos - 1));
                    $variable = trim($variable);

                    if ($variable == 'NameOfLang' || $variable == '0') {
                        continue;
                    }

                    require $englishFile;
                    $my_variable_in_english = $variable;

                    require $phpFilePath;
                    $my_variable = $$variable;
                    $translations[] = array(
                        'msgid' => $my_variable_in_english,
                        'msgstr' => $my_variable
                    );
                }
            }
            //$code = api_get_language_isocode($folder->getBasename());
            $code = isset($languages[$folder->getBasename(
                )]) ? $languages[$folder->getBasename()] : 'what';
            //LC_MESSAGES
            $fileName = $phpFile->getBasename('.php');
            $fileName = 'all';
            $new_po_file = $save_path.'/'.$fileName.'.'.$code.'.po';

            /*if (!is_dir($save_path.$folder->getBasename())) {
                mkdir($save_path.$folder->getBasename());
            }*/

            $fp = fopen($new_po_file, 'w');
            foreach ($translations as $item) {
                $line = 'msgid "'.$item['msgid'].'"'."\n";
                $line .= 'msgstr "'.nl2br(str_replace(array("\r\n", "\n"), "<br />", addcslashes($item['msgstr'], '"'))).'"'."\n\n";
                fwrite($fp, $line);
            }
            fclose($fp);
            echo $new_po_file.PHP_EOL;
            echo 'finish' .PHP_EOL;
        }
    }
}
