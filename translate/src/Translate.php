<?php

namespace GN;

use GN\GlbObjFunc\Glb;
use GN\GlbObjFunc\GetInBetweenString;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Translate extends TranslateBase {
    static $langPath = ROOT . '/lang/';
    static $langDefaut = 'fr';
    static $lang;
    static $filePath;

    static function init($lang, $filePath) {
        // Folder creation
        $dir = self::$langPath;
        if (!file_exists($dir)) mkdir($dir . '/', 0777, true);

        // Array loading
        if (file_exists(self::$langPath . $filePath)) {
            self::$lang = include(self::$langPath . $filePath);
        }
    }

    static function text(string $text, array $args = []) {
        $translatedText = self::$lang[$text] ?? $text;

        foreach ($args as $arg => $value) {
            $translatedText = str_replace('-' . $arg . '-', $value, $translatedText);
        }

        return $translatedText;
    }

    static function save($array, $lang) {
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

    static function read($lang) {
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
        return $array;
    }
}