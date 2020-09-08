<?php
// https://css4design.developpez.com/tutoriels/css/feuille-de-style-css-dynamique-avec-php/

use GN\LoaderCssJs\Loader;

// L'autoloader est inutilisable car le fichier est appelé en HTML
require_once('src/Loader.php');

header('content-type: text/css');

$root = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/";

Loader::readfiles($root, [
	"lazy-load.css",
	"theme.min.css",
]);