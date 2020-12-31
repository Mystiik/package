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

    static public $CATEGORY_LIST;

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

    static public function translateCategoryList($lang) {
        switch ($lang) {
            case 'en':
                self::$CATEGORY_LIST =  [
                    0 => 'News',
                    1 => 'Engineering Projects',
                    2 => 'Assistance',
                    3 => 'Safety/Security',
                    4 => 'Consulting',
                ];
                break;
            case 'es':
                self::$CATEGORY_LIST =  [
                    0 => 'Noticias',
                    1 => 'Proyectos de ingeniería',
                    2 => 'Apoyo',
                    3 => 'Seguridad / protección',
                    4 => 'Concilio',
                ];
                break;
            default:
                self::$CATEGORY_LIST =  [
                    0 => 'Actualités',
                    1 => 'Ingenierie Projets',
                    2 => 'Assistance',
                    3 => 'Sûreté/Sécurité',
                    4 => 'Conseil',
                ];
                break;
        }
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