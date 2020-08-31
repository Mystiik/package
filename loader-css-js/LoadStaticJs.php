<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

use GN\LoaderCssJs\Loader;

// L'autoloader est inutilisable car le fichier est appelé en HTML
require_once('src/Loader.php');

// header('HTTP/1.1 304 Not Modified');
header('Cache-Control: max-age=84600, must-revalidate');
header('content-type: application/x-javascript');

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/libs/";

Loader::readfiles($root, [
	// Prioritaire
	"jquery/dist/jquery.min.js",
	"isotope-layout/dist/isotope.pkgd.min.js",
	"jarallax/dist/jarallax.min.js",
	"jarallax/dist/jarallax-video.min.js",
	"jarallax/dist/jarallax-element.min.js",
	// Autres
	"bootstrap/dist/js/bootstrap.bundle.min.js",
	"flickity/dist/flickity.pkgd.min.js",
	"flickity-fade/flickity-fade.js",
	"aos/dist/aos.js",
	"smooth-scroll/dist/smooth-scroll.min.js",
	"typed.js/lib/typed.min.js",
	"countup.js/dist/countUp.min.js",
	"@fancyapps/fancybox/dist/jquery.fancybox.min.js",
	"imagesloaded/imagesloaded.pkgd.min.js",
	"google-analytics/google-analytics.min.js",
]);