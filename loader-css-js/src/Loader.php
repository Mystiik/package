<?php

namespace GN\LoaderCssJs;

class Loader
{
	/**
	 * Empêche un bug au niveau des commentaires
	 * <pre> -> Fin d'un fichier -> // blabla </pre>
	 * <pre> -> Début d'un fichier -> /* blabla </pre>
	 * <pre> ---> Résultat sans retour à la ligne (par défaut) -> // blabla /* blabla </pre>
	 * <pre> ---> le /* est mit en commentaire, le commentaire est donc interprété comme du code -> bug </pre>
	 */
	static function readfiles(string $root, array $filenames)
	{
		foreach ($filenames as $filename) {
			readfile($root . $filename);
			echo "\n";
		}
	}
}