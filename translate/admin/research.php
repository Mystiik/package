<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php');

use GN\GlbObjFunc\Glb;

function directoryIterator($path) {
    $extToInclude = ['php', 'txt'];
    $folderToIgnore = ['vendor', 'assets', 'lang', 'test'];
    $array = [];

    foreach (new DirectoryIterator($path) as $fileInfo) {
        // var_dump($fileInfo->getFileInfo());

        if ($fileInfo->isDir() and !$fileInfo->isDot() and !in_array($fileInfo->getFilename(), $folderToIgnore)) {
            $array = array_merge(directoryIterator($fileInfo->getPathname()), $array);
        }

        if ($fileInfo->isFile()) {
            if (isset(pathinfo($fileInfo->getPathname())['extension']) and in_array(pathinfo($fileInfo->getPathname())['extension'], $extToInclude)) {
                // var_dump($fileInfo->getPathname());
                $content = file_get_contents($fileInfo->getPathname());
                // Glb::getInbetweenStrings($content, "<?= GN\Translate::text('", "') ?");
                // $getInBetweenStringsList = Glb::getInbetweenStrings($content, ">", "<");
                $getInBetweenStringsList = [];
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<title", "</title>");
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h1", "</h1>");
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h2", "</h2>");
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h3", "</h3>");
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<h4", "</h4>");
                $getInBetweenStringsList += Glb::getInbetweenStrings($content, "<p", "</p>");
                ksort($getInBetweenStringsList);

                $aList = Glb::getInbetweenStrings($content, "<a", "</a>");
                foreach ($aList as $a) {
                    $strStartPrev = 0;
                    foreach ($getInBetweenStringsList as $strStart => $getInBetweenStrings) {
                        // var_dump($strStartPrev . ' <= ' . $a['strStart'] . ' and ' . $a['strStart'] . ' <= ' . $strStart);
                        // var_dump($strStartPrev <= $a['strStart'] and $a['strStart'] <= $strStart);
                        if ($strStartPrev <= $a['strStart'] and $a['strStart'] <= $strStart) {
                            $getInBetweenStrings = $getInBetweenStringsList[$strStartPrev];
                            // var_dump($getInBetweenStrings);
                            // var_dump($getInBetweenStrings['strStart'] <= $a['strStart'] and $a['strStart'] <= $getInBetweenStrings['strEnd']);
                            if ($getInBetweenStrings['strStart'] <= $a['strStart'] and $a['strStart'] <= $getInBetweenStrings['strEnd']) {
                                // do nothing
                            } else {
                                $getInBetweenStringsList[$a['strStart']] = $a;
                            }
                        }
                        $strStartPrev = $strStart;
                    }
                }
                ksort($getInBetweenStringsList);

                foreach ($getInBetweenStringsList as $getInBetweenStrings) {
                    if (!empty(trim($getInBetweenStrings['str']))) {
                        $array[$fileInfo->getPathname()][$getInBetweenStrings['strStart']] = $getInBetweenStrings['strStart'] . " " . $getInBetweenStrings['strEnd'] . " " . $getInBetweenStrings['str'];
                        // $array[$fileInfo->getPathname()][$getInBetweenStrings['strStart']] = $getInBetweenStrings['strPos'] . ": " . $getInBetweenStrings['strStart'] . " " . $getInBetweenStrings['strEnd'] . " " . $getInBetweenStrings['str'];
                        // $array[$fileInfo->getPathname()][] = trim($getInBetweenStrings);
                    }
                }
                // }
            }
        }
    }

    return $array;
}

// var_dump(directoryIterator(ROOT));
// var_dump(directoryIterator(ROOT . '/lowtech/'));