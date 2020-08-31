<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

use GN\LoaderCssJs\Loader;

// L'autoloader est inutilisable car le fichier est appelé en HTML
require_once('src/Loader.php');

// header('HTTP/1.0 304 Not Modified');
// header('Cache-Control: max-age=3600, must-revalidate');
header('content-type: application/x-javascript');

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/js/";

// Prioritaire

// Autres
Loader::readfiles($root, [
	"aos.js",
	"dropdown.js",
	"fancybox.js",
	"masonry.js",
	"map.js",
	"navbar.js",
	"polyfills.js",
	"pricing.js",
	"smooth-scroll.js",
	"theme.js",
	"tooltips.js",
	"typed.js",
	// LazySize / Used to differ image loading / Doc: https://afarkas.github.io/lazysizes/index.html
	// "lazysize.js",
	// "theme.min.js",
	// "highlight.js",
	// "isotope.js",
	// "countup.js",
]);