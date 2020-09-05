<?php

namespace GN\GlbObjFunc;

trait __Get
{
	// La fonction __call enlève la génération de doc automatique
	// Y palier: https://docs.phpdoc.org/latest/references/phpdoc/tags/method.html
	// Exemple à mettre au dessus de la classe en question:
	/**
	 * @method string getString()
	 * @method void setInteger(integer $integer)
	 * @method setString(integer $integer)
	 * @method static string staticGetter()
	 */
	// Fin de l'exemple
	public function __call($name, $arguments)
	{
		//---------------------------------------------------------------------------
		// Automatic getter
		//---------------------------------------------------------------------------
		// Appel du type "getNameName" pour une variable "nameName"
		if (substr($name, 0, 3) == "get") {
			// On enlève le "get" et on met la premiere lettre en minuscule
			$name = lcfirst(substr($name, 3));

			// Si la variable existe
			if (property_exists($this, $name)) {
				// On la retourne
				return $this->$name;
			} else {
				// Sinon, on tente avec une première lettre majuscule
				$name = ucfirst($name);
				if (property_exists($this, $name)) {
					// On la retourne
					return $this->$name;
				}
			}
			// Si la variable n'existe pas dans tous les cas, on throw une erreur 
			throw new \Exception("Property $name does not exists in " . get_class($this) . " object", 1);
		} else {
			throw new \Exception("Function $name does not exists in " . get_class($this) . " object. Args: [" . implode(",", $arguments) . "]", 1);
		}
	}
}