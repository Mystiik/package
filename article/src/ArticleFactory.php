<?php

namespace GN\Article;

use \GN\Article\Article;
use \GN\Article\Editor;

class ArticleFactory {
    const DIR_SAVE_ARTICLE = "/article"; // from document_root

    public static function getArticleFromId($id) {
        return self::getArticleFromPath($_SERVER['DOCUMENT_ROOT'] . self::DIR_SAVE_ARTICLE . "/$id.txt");
    }

    public static function getArticleFromPath($path) {
        $article = new Article();

        if (file_exists($path)) {
            $content = unserialize(file_get_contents($path));
            if ($content and is_a($content, '\GN\Article\Article')) {
                $content->title = str_replace(['<p>', '</p>'], '', $content->getTitle());
                $content->description = str_replace(['<p>', '</p>'], '', $content->getDescription());
                $article = $content;
            }
        }

        return $article;
    }

    public static function getArticleList($path = self::DIR_SAVE_ARTICLE) {
        $array = self::directoryIterator($_SERVER['DOCUMENT_ROOT'] . $path);

        // Latest Date first
        foreach ($array as $key => $value) {
            krsort($array[$key]);
        }

        return $array;
    }

    public static function getRandomArticle($path = self::DIR_SAVE_ARTICLE) {
        $array = self::directoryIterator($_SERVER['DOCUMENT_ROOT'] . $path);

        shuffle($array);
        $dateParution = array_pop($array);
        shuffle($dateParution);
        $articleList = array_pop($dateParution);
        shuffle($articleList);
        $article = array_pop($articleList);

        return $article;
    }

    private static function directoryIterator($path) {
        $extToInclude = ['txt'];
        // $folderToIgnore = ['vendor', 'assets', 'lang'];
        $folderToIgnore = [];
        $array = [];

        if (file_exists($path)) {
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDir() and !$fileInfo->isDot() and !in_array($fileInfo->getFilename(), $folderToIgnore)) {
                    $array = array_merge(self::directoryIterator($fileInfo->getPathname()), $array);
                }

                if ($fileInfo->isFile()) {
                    if (isset(pathinfo($fileInfo->getPathname())['extension']) and in_array(pathinfo($fileInfo->getPathname())['extension'], $extToInclude)) {
                        $content = self::getArticleFromPath($fileInfo->getPathname());
                        if ($content and is_a($content, '\GN\Article\Article')) {
                            $array[$content->getCategory()][$content->getDateParution()][] = $content;
                        }
                    }
                }
            }
        }
        return $array;
    }
}