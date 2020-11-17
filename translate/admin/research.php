<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php');

use GN\GlbObjFunc\Glb;

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
                    $array[$fileInfo->getPathname()] = Glb::getInbetweenStrings($content, "<?= GN\Translate::text('", "') ?");
                }
            }
        }
    }

    return $array;
}

var_dump(directoryIterator(ROOT));