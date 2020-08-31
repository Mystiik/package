<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

use GN\LoaderCssJs\Loader;

// L'autoloader est inutilisable car le fichier est appelé en HTML
require_once('src/Loader.php');

// header('HTTP/1.1 304 Not Modified');
header('Cache-Control: max-age=84600, must-revalidate');
header('content-type: text/css');

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/libs/";

// Prioritaire
Loader::readfiles($root, [
	"masonry/masonry.css",
	"fontawesome-5.13.0/css/all.css",
]);

// Autres
Loader::readfiles($root, [
	"flickity/dist/flickity.min.css",
	"flickity-fade/flickity-fade.css",
	"aos/dist/aos.css",
	"jarallax/dist/jarallax.css",
	"highlightjs/styles/vs2015.css",
	"@fancyapps/fancybox/dist/jquery.fancybox.min.css",
]);

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/fonts/";
Loader::readfiles($root, [
	"Feather/feather.css",
]);