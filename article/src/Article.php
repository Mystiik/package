<?php

namespace GN\Article;

// Déclaration des méthodes manuellement car gérées automatiquement par \GN\GlbObjFunc\__Get
/**
 * @method getId()
 * @method getCategory()
 * @method getTitle()
 * @method getDescription()
 * @method getImageId()
 * @method getDateParution()
 * @method getDateModification()
 * @method getEditorList()
 */
class Article {
    use \GN\GlbObjFunc\__Get;
    use \GN\GlbObjFunc\Hydrate;

    const CATEGORY_LIST = [
        0 => 'Ingenierie Projets',
        1 => 'Gestion Des Risques',
        2 => 'Conseils',
        3 => 'Sûreté France',
        4 => 'Sûreté Monde',
    ];

    private $id;
    private $category;
    public $title;
    public $description;
    private $imageId;
    private $dateParution;
    private $dateModification;
    public $editorList;

    public function __construct(array $data = []) {
        $this->id = "";
        $this->dateParution = "";
        $this->dateModification = "";
        $this->title = "";
        $this->description = "";
        $this->category = 0;
        $this->imageId = "";
        $this->editorList = [];

        $this->hydrate($data);
    }

    public function setId($id = null) {
        $this->id = $id ?? rand();
    }

    public function setDateParution($date = null) {
        $this->dateParution = $date ?? time();
    }

    public function setDateModification($date = null) {
        $this->dateModification = $date ?? time();
    }
}