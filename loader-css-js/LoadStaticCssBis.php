<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

// L'autoloader est inutilisable car le fichier est appelé en HTML

// header('HTTP/1.1 304 Not Modified');
// header('Cache-Control: max-age=84600, must-revalidate');

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/libs/";

// Prioritaire
readfiles($root, [
	"masonry/masonry.css",
	"fontawesome-5.13.0/css/all.css",
]);

// Autres
readfiles($root, [
	"flickity/flickity.css",
	"flickity-fade/flickity-fade.css",
	"aos/dist/aos.css",
	"jarallax/dist/jarallax.css",
	"highlightjs/styles/vs2015.css",
	"@fancyapps/fancybox/dist/jquery.fancybox.min.css",
]);

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/fonts/";
readfiles($root, [
	"Feather/feather.css",
]);

function readfiles(string $root, array $filenames)
{
	foreach ($filenames as $filename) {
		readfile($root . $filename);
		echo "\n";
	}
}