<?php

namespace GN;

use GN\GlbObjFunc\Glb;
use GN\Translate;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class TranslateBase {
    static $langPath = ROOT . '/lang/';
    static $langDefaut = 'fr';
    static $lang;
    static $filePath;

    // static function init($lang, $filePath) {
    //     // Folder creation
    //     $dir = self::$langPath;
    //     if (!file_exists($dir)) mkdir($dir . '/', 0777, true);

    //     // Array loading
    //     if (file_exists(self::$langPath . $filePath)) {
    //         self::$lang = include(self::$langPath . $filePath);
    //     }
    // }

    // static function text(string $text, array $args = []) {
    //     $translatedText = self::$lang[$text] ?? $text;

    //     foreach ($args as $arg => $value) {
    //         $translatedText = str_replace('-' . $arg . '-', $value, $translatedText);
    //     }

    //     return $translatedText;
    // }


    public static function setupRawFolderForTranslation(array $folderPathList, $saveOriginArrayName) {
        // Initialisation
        $origin = Translate::read($saveOriginArrayName);
        $originNew = [];
        $fr = [];

        // Search all texts in folder's files
        $array = [];
        foreach ($folderPathList as $folderPath) $array += self::directoryIterator($folderPath);
        // var_dump($array);
        // Modify them: 'bla' => <?= GN\Translate::text('bla'); ?<
        foreach ($array as $filePath => $textList) {
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                krsort($textList);
                $f = fopen($filePath, 'w');
                $filePathClean = str_replace(ROOT, '', $filePath);
                $filePathClean = str_replace('\\', '/', $filePathClean);
                $isArticle = !(strpos($filePathClean, 'article/') === false);
                foreach ($textList as $text) {
                    // Change file content
                    if (!$isArticle) {
                        if (strpos($text->str, '<?= GN\Translate::text(') === false) {
                            $textNew = str_replace("'", "\'", $text->str);
                            $textNew = "<" . "?= GN\Translate::text('" . $textNew . "'); ?" . ">";
                            $contentLeft = substr($content, 0, $text->strStart);
                            $contentRight = substr($content, $text->strEnd - strlen($content));
                            $content = $contentLeft . $textNew . $contentRight;
                        }
                    }

                    // Generate origin.json
                    $textNew = str_replace([PHP_EOL, "\t", "\n", "\r", "\0", "\x0B"], "", $text->str);
                    $textNew = str_replace("<?= GN\Translate::text('", "", $textNew);
                    $textNew = str_replace("'); ?" . ">", "", $textNew);
                    $textNew = str_replace("\'", "'", $textNew);
                    $textNew = str_replace("<", "(-", $textNew);
                    $textNew = str_replace(">", "-)", $textNew);
                    $textNew = str_replace("{", "(~", $textNew);
                    $textNew = str_replace("}", "~)", $textNew);
                    $originNew[$filePathClean][$textNew] = $origin[$filePathClean][$textNew] ?? (string)rand();
                }
                // var_dump($content);
                fwrite($f, $content);
                fclose($f);
                //
                $originNew[$filePathClean] = array_reverse($originNew[$filePathClean], true);
                $fr[$filePathClean] = array_flip($originNew[$filePathClean]);
            }
        }
        //
        Translate::save($originNew, $saveOriginArrayName);
        Translate::save($fr, $saveOriginArrayName . '-fr');
    }

    public static function directoryIterator($path) {
        $extToInclude = ['php', 'txt'];
        $folderToIgnore = ['vendor', 'assets', 'lang', 'test'];
        $array = [];

        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDir() and !$fileInfo->isDot() and !in_array($fileInfo->getFilename(), $folderToIgnore)) {
                $array = array_merge(self::directoryIterator($fileInfo->getPathname()), $array);
            }

            if ($fileInfo->isFile()) {
                if (isset(pathinfo($fileInfo->getPathname())['extension']) and in_array(pathinfo($fileInfo->getPathname())['extension'], $extToInclude)) {
                    // var_dump($fileInfo->getPathname());
                    $content = file_get_contents($fileInfo->getPathname());
                    // $getInBetweenStringsList = Glb::getInbetweenStrings($content, ">", "<");
                    $getInBetweenStringsList = [];
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<title", "</title>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h1", "</h1>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h2", "</h2>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h3", "</h3>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h4", "</h4>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h5", "</h5>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h6", "</h6>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<li>", "</li>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<p", "</p>");
                    $getInBetweenStringsList += Glb::getInbetweenStrings($content, "src=\"", "\"");
                    ksort($getInBetweenStringsList);

                    $aList = Glb::getInbetweenStrings($content, "<a", "</a>");
                    $getInBetweenStringsList = addWithoutOverlap($getInBetweenStringsList, $aList);

                    foreach ($getInBetweenStringsList as $getInBetweenStrings) {
                        if (!empty(trim($getInBetweenStrings->str))) {
                            // $array[$fileInfo->getPathname()][$getInBetweenStrings->strStart] = $getInBetweenStrings->strStart . " " . $getInBetweenStrings->strEnd . " " . $getInBetweenStrings->str;
                            $array[$fileInfo->getPathname()][$getInBetweenStrings->strStart] = $getInBetweenStrings;
                        }
                    }
                }
            }
        }
        return $array;
    }

    public static function imageFormulaireGestion($originalText, &$translatedText) {
        $color = 'primary';
        if (strpos($originalText, '/') === 0) {
            $color = 'warning';
            if (strpos($originalText, '/assets/img') !== false and strpos($originalText, '.jpg') !== false and !isset($translatedText)) {
                $texttmp = explode('/', $originalText);
                $texttmp[count($texttmp) - 1] = 'ID_TRANSLATED_IMAGE.jpg';
                $translatedText = implode('/', $texttmp);
            }
        }
        return $color;
    }
}

function addWithoutOverlap($getInBetweenStringsList, $listOverlap) {
    foreach ($listOverlap as $a) {
        $strStartPrev = 0;
        foreach ($getInBetweenStringsList as $strStart => $getInBetweenStrings) {
            if ($strStartPrev != 0 and $strStartPrev <= $a->strStart and $a->strStart <= $strStart) {
                $getInBetweenStrings = $getInBetweenStringsList[$strStartPrev];
                if ($getInBetweenStrings->strStart <= $a->strStart and $a->strStart <= $getInBetweenStrings->strEnd) {
                    // do nothing
                } else {
                    $getInBetweenStringsList[$a->strStart] = $a;
                }
            }
            $strStartPrev = $strStart;
        }
    }
    ksort($getInBetweenStringsList);
    return $getInBetweenStringsList;
}


// var_dump(directoryIterator(ROOT));
// var_dump(directoryIterator(ROOT . '/lowtech/'));