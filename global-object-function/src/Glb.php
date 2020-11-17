<?php

namespace GN\GlbObjFunc;

/**
 * Define a list of usefull functions
 */
class Glb {
    /**
     * Get list of elements between two strings
     */
    static function getInbetweenStrings($str, $start, $end) {
        $array = [];
        $explodeStart = explode($start, $str);
        unset($explodeStart[0]);
        foreach ($explodeStart as $explodeStartElement) {
            $explode = explode($end, $explodeStartElement);
            if (isset($explode[1])) $array[] = $explode[0];
            var_dump($explode[0]);
        }
        return $array;
    }

    /**
     * Get size from size bytes
     */
    static function getSizeFromBytes($bytes) {
        $size = ["o", "Ko", "Mo", "Go", "To", "Po", "Eo", "Zo", "Yo"];
        $bytes = filesize($bytes);
        $factor = floor((strlen($bytes) - 1) / 3);

        return round($bytes / pow(1024, $factor), 2) . " " . $size[$factor];
    }
}