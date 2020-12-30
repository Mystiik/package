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
        //
        $translatedId = self::$langOrigin[self::jsonify($text)] ?? -1;
        $translatedText = self::$lang[$translatedId] ?? $text;
        // var_dump($translatedId);

        $translatedText = self::unjsonify($translatedText);

        // foreach ($args as $arg => $value) {
        //     $translatedText = str_replace('-' . $arg . '-', $value, $translatedText);
        // }

        return $translatedText;
    }

    public static function translateHtml($html) {
        // Parse html
        $array = self::parseHtml($html);
        krsort($array);

        foreach ($array as $text) {
            // Modify text
            if (strlen(trim($text->str)) == 0) continue;

            $contentLeft = substr($html, 0, $text->strStart);
            $contentRight = substr($html, $text->strEnd - strlen($html));
            $html = $contentLeft . self::text($text->str) . $contentRight;
            // var_dump('------------------------------------------------------------');
            // var_dump($contentLeft);
            // var_dump($text->str);
            // var_dump($contentRight);
        }
        return $html;
    }

    public static function jsonify($text) {
        // Clean text
        $text = str_replace([PHP_EOL, "\t", "\n", "\r", "\0", "\x0B"], "", $text);
        while (strpos($text, "  ")) $text = str_replace("  ", " ", $text);

        // Prevent html interpretation
        $text = str_replace("<", "(-", $text);
        $text = str_replace(">", "-)", $text);
        $text = str_replace("{", "(~", $text);
        $text = str_replace("}", "~)", $text);
        return $text;
    }

    public static function unjsonify($text) {
        $text = str_replace("(-", "<", $text);
        $text = str_replace("-)", ">", $text);
        $text = str_replace("(~", "{", $text);
        $text = str_replace("~)", "}", $text);

        return $text;
    }

    static function save($array, $lang) {
        // Folder creation
        if (!file_exists(self::$langPath)) mkdir(self::$langPath . '/', 0777, true);

        // File save
        $json = json_encode($array, JSON_UNESCAPED_UNICODE);
        $json = str_replace(":{", ": {\n\t", $json);
        $json = str_replace("{\"", "{\n\t\"", $json);
        $json = str_replace("\",\"", "\",\n\t\"", $json);
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
            $json = str_replace(": {\n\t", ":{", $json);
            $json = str_replace("{\n\t\"", "{\"", $json);
            $json = str_replace("\",\n\t\"", "\",\"", $json);
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