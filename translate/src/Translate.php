<?php

namespace GN;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Translate
{
    static $langPath = ROOT . '/lang/';
    static $langDefaut = 'fr';
    static $lang;
    static $filePath;

    static function init($lang, $filePath)
    {
        // Folder creation
        $dir = self::$langPath;
        foreach (explode('/', pathinfo($filePath)['dirname']) as $folder) {
            $dir .=  $folder . '/';
            if (!file_exists($dir)) mkdir($dir . '/');
        }

        // Array loading
        if (file_exists(self::$langPath . $filePath)) {
            self::$lang = include(self::$langPath . $filePath);
        }
    }

    static function text(string $text, array $args = [])
    {
        $translatedText = self::$lang[$text] ?? $text;

        foreach ($args as $arg => $value) {
            $translatedText = str_replace('-' . $arg . '-', $value, $translatedText);
        }

        return $translatedText;
    }
}