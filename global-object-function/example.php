<?php

namespace namespaceName;

// Déclaration des méthodes manuellement car gérées automatiquement par \GN\GlbObjFunc\__Get
/**
 * @method getPropertyOne()
 * @method getPropertyTwo()
 */
class className
{
	use \GN\GlbObjFunc\__Get;
	use \GN\GlbObjFunc\Hydrate;

	private $propertyOne;
	private $propertyTwo;

	function __construct(string $propertyOne)
	{
		$this->propertyOne = $propertyOne;

		$infos = $this->getInfos();
		$this->hydrate($infos);
	}

	//-----------------------------------------------------------------------------------
	// Basic function
	//-----------------------------------------------------------------------------------

	/**
	 * Récupère les infos de la base
	 *
	 * @return array all columns from 'table' table
	 */
	private function getInfos()
	{
		global $bdd;

		$req = $bdd->prepare('SELECT * FROM table WHERE propertyOne=:propertyOne');
		$req->execute(array('propertyOne' => $this->getPropertyOne()));

		return $req->fetch(\PDO::FETCH_ASSOC);
	}

	// Automated Get function from \GN\GlbObjFunc\__Get can be overwritten
	// They automaticly return $this->propertyTwo so don't overwrite them to do only that
	private function getPropertyTwo()
	{
		// Do some stuff..
		return $this->propertyTwo;
	}
}