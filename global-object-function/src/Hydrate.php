<?php

namespace GN\GlbObjFunc;

trait Hydrate
{
	protected function hydrate(array $data)
	{
		foreach ($data as $key => $value) {
			// Dans le cas où la bdd a des table du type "nom_nom", la variable sera "nomNom"
			$key = explode("_", $key);
			for ($i = 1; $i < count($key); $i++) {
				$key[$i] = ucfirst($key[$i]);
			}
			$key = implode("", $key);

			// Si la variable existe
			if (property_exists($this, $key)) {
				// On l'affecte
				$this->$key = $value;
			}
		}
	}
}