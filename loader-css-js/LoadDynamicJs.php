<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

use GN\LoaderCssJs\Loader;

// L'autoloader est inutilisable car le fichier est appelé en HTML
require_once('src/Loader.php');

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
	"flickity.js",
	"pricing.js",
	"smooth-scroll.js",
	"lazy-load.js",
	"theme.js",
	"tooltips.js",
	"typed.js",
	// "highlight.js",
	// "isotope.js",
	// "countup.js",
]);