<?php

namespace GN\Team;

// Déclaration des méthodes manuellement car gérées automatiquement par \GN\GlbObjFunc\__Get
/**
 * @method getId()
 * @method getName()
 * @method getImageId()
 * @method getFunction()
 */
class Member {
  use \GN\GlbObjFunc\__Get;
  use \GN\GlbObjFunc\Hydrate;

  private $id;
  private $name;
  private $imageId;
  private $function;

  public function __construct(array $data = []) {
    $this->id = "";
    $this->name = "";
    $this->imageId = "";
    $this->function = "";

    $this->hydrate($data);
  }
}
