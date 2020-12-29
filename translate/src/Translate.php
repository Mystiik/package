<?php

namespace GN;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Translate extends TranslateBase {
    static $langPath = ROOT . '/lang/';
    static $lang;
    static $langOrigin;
    static $filePath;

    static function init($lang) {
        // Folder creation
        if (!file_exists(self::$langPath)) mkdir(self::$langPath . '/', 0777, true);

        // Array loading
        self::$langOrigin = self::read('origin', true);
        self::$lang = self::read($lang);
        // var_dump(self::$langOrigin);
        // var_dump(self::$lang);
    }

    static function text(string $text, array $args = []) {
        $translatedId = self::$langOrigin[$text] ?? -1;
        $translatedText = self::$lang[$translatedId] ?? $text;
        // var_dump($translatedId);

        foreach ($args as $arg => $value) {
            $translatedText = str_replace('-' . $arg . '-', $value, $translatedText);
        }

        return $translatedText;
    }

    static function save($array, $lang) {
        // Folder creation
        if (!file_exists(self::$langPath)) mkdir(self::$langPath . '/', 0777, true);

        // File save
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);
        $json = str_replace(":{", ": {\n\t\t", $json);
        $json = str_replace("{\"", "{\n\t\"", $json);
        $json = str_replace("\",\"", "\",\n\t\t\"", $json);
        $json = str_replace("}}", "\n\t}\n}", $json);
        $json = str_replace("},", "\n\t},\n\t", $json);
        $f = fopen(self::$langPath . $lang . '.json', "w");
        fwrite($f, $json);
        fclose($f);
    }

    static function read($lang, $onlyKeyValue = false) {
        $array = [];
        if (file_exists(self::$langPath . $lang . '.json')) {
            $json = file_get_contents(self::$langPath . $lang . '.json');
            $json = str_replace(": {\n\t\t", ":{", $json);
            $json = str_replace("{\n\t\"", "{\"", $json);
            $json = str_replace("\",\n\t\t\"", "\",\"", $json);
            $json = str_replace("\n\t}\n}", "}}", $json);
            $json = str_replace("\n\t},\n\t", "},", $json);
            $array = json_decode($json, true);
        }

        // On enlève le nom des fichiers
        if ($onlyKeyValue) {
            $arrayTmp = [];
            foreach ($array as $valueList) {
                foreach ($valueList as $key => $value) {
                    $arrayTmp[$key] = (string)$value;
                }
            }
            $array = $arrayTmp;
        }
        return $array;
    }
}