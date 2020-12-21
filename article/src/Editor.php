<?php

namespace GN\Article;

// Déclaration des méthodes manuellement car gérées automatiquement par \GN\GlbObjFunc\__Get
/**
 * @method getId()
 * @method getSize()
 * @method getData()
 */
class Editor {
	use \GN\GlbObjFunc\__Get;
	use \GN\GlbObjFunc\Hydrate;

	private $id;
	private $size;
	private $data;

	public function __construct(array $data = []) {
		$this->id = 0;
		$this->size = 0;
		$this->data = "";

		$this->hydrate($data);
	}
}