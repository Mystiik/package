<?php

namespace GN\GlbObjFunc;
// use get

/**
 * Define a list of usefull functions
 */
class Glb {
    //------------------------------------------------------------------------------------------------------------
    // getInbetweenStrings
    //------------------------------------------------------------------------------------------------------------
    public static function getInbetweenStrings($str, $start, $end) {
        $array = [];
        $str = str_replace("<path", "-path", $str);

        $explodeStart = explode($start, $str);
        $strStart = strlen($explodeStart[0]) - 1;
        unset($explodeStart[0]);

        foreach ($explodeStart as $explodeStartElement) {
            $schemaStartValue = $strStart;
            $strStart += strlen($start);

            $explode = explode($end, $explodeStartElement);
            if ($explode) {
                // Clean the content #1
                if (substr($start, -1) != ">") {
                    $tmp = explode(">", $explode[0]);
                    if (isset($tmp[1])) {
                        $strStart += strlen($tmp[0]) + strlen(">");
                        unset($tmp[0]);
                        $explode[0] = implode(">", $tmp);
                    }
                }

                // Clean the content
                // $explodeElementClean = trim(str_replace([PHP_EOL, "\t", "\n", "\r", "\0", "\x0B"], "", $explode[0]));
                $explodeElementClean = trim(str_replace([], "", $explode[0]));

                // Will show spaces that have been removed from content
                $explodeClean = [0, 0];
                if ($explodeElementClean != '') {
                    $explodeClean = explode($explodeElementClean, $explode[0]);
                    if ($explodeClean) {
                        $explodeClean[0] = strlen($explodeClean[0] ?? '');
                        $explodeClean[1] = strlen($explodeClean[1] ?? '');
                    }
                } else {
                    $explodeClean[0] = strlen($explode[0]);
                }
                $strStart += $explodeClean[0];

                //
                if ($explodeElementClean != '') {
                    $explodeElementClean = str_replace("-path", "<path", $explodeElementClean);

                    $getInBetweenString = new GetInBetweenString();
                    $getInBetweenString->str = $explodeElementClean;
                    $getInBetweenString->strStart = $strStart + 1;
                    $getInBetweenString->strEnd = $strStart + strlen($explodeElementClean) + 1;
                    $getInBetweenString->strPos = strpos($str, $explodeElementClean);
                    $getInBetweenString->schemaStart = $start;
                    $getInBetweenString->schemaEnd = $end;
                    $getInBetweenString->schemaStartValue = $schemaStartValue + 1;
                    $getInBetweenString->schemaEndValue = $getInBetweenString->strEnd + $explodeClean[1] + strlen($end);
                    $array[$getInBetweenString->strStart] = $getInBetweenString;
                }

                //
                $strStart += strlen($explodeElementClean);
                $strStart += $explodeClean[1];

                if (isset($explode[1])) {
                    unset($explode[0]);
                    $strStart +=  strlen(implode($end, $explode));
                }
                $strStart += strlen($end);
            } else {
                $strStart += strlen($explodeStartElement);
            }
        }
        return $array;
    }

    //------------------------------------------------------------------------------------------------------------
    // getSizeFromBytes
    //------------------------------------------------------------------------------------------------------------
    public static function getSizeFromBytes($bytes) {
        $size = ["o", "Ko", "Mo", "Go", "To", "Po", "Eo", "Zo", "Yo"];
        $bytes = filesize($bytes);
        $factor = floor((strlen($bytes) - 1) / 3);

        return round($bytes / pow(1024, $factor), 2) . " " . $size[$factor];
    }

    //------------------------------------------------------------------------------------------------------------
    // startBuffering
    //------------------------------------------------------------------------------------------------------------
    public static function startBuffering() {
        ob_start();
    }

    //------------------------------------------------------------------------------------------------------------
    // endBuffering
    //------------------------------------------------------------------------------------------------------------
    public static function endBuffering() {
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    //------------------------------------------------------------------------------------------------------------
    // directoryIterator
    //------------------------------------------------------------------------------------------------------------
    public static function directoryIterator($path, $extToInclude = [], $folderToIgnore = []) {
        $array = [];

        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDir() and !$fileInfo->isDot() and !in_array($fileInfo->getFilename(), $folderToIgnore)) {
                $arrayTmp = self::directoryIterator($fileInfo->getPathname(), $extToInclude, $folderToIgnore);
                foreach ($arrayTmp as $filePath) $array[] = $filePath;
            }

            if ($fileInfo->isFile()) {
                if (isset(pathinfo($fileInfo->getPathname())['extension']) and in_array(pathinfo($fileInfo->getPathname())['extension'], $extToInclude)) {
                    $array[] = $fileInfo->getPathname();
                }
            }
        }
        return $array;
    }
}