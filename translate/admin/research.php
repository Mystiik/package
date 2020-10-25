<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php');

function directoryIterator($path)
{
    $extToInclude = ['php'];
    $folderToIgnore = ['vendor', 'assets', 'lang'];
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
                if (strpos($content, "<?= GN\Translate::text('") !== false) {
                    $array[$fileInfo->getPathname()] = getInbetweenStrings($content, "<?= GN\Translate::text('", "') ?");
                }
            }
        }
    }

    return $array;
}

var_dump(directoryIterator(ROOT));

// $dir = new DirectoryIterator(ROOT);
// var_dump($dir);
// foreach ($dir as $file) {
// $content = file_get_contents($file->getPathname());
// if (strpos($content, $string) !== false) {
// // Bingo
// }
// }