<?php

namespace GN;

// Déclaration des méthodes manuellement car gérées automatiquement par \GN\GlbObjFunc\__Get
/**
 * @method getFilepath()
 * @method getType()
 * @method getImg()
 * @method getWidth()
 * @method getHeight()
 */
class Image {
	use \GN\GlbObjFunc\__Get;

	/**
	 * @var string contain path to the image
	 */
	private $filepath;

	/**
	 * @var int from self::ALLOWED_TYPES
	 */
	private $type;

	/**
	 * @var resource contain img var
	 */
	private $img;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;


	public function __construct($filepath) {
		$this->filepath = $filepath;

		// [0] => width, [1] => height, [2] => imgType, 
		$array = getimagesize($this->filepath);
		$this->width = $array[0];
		$this->height = $array[1];
		$this->type = $array[2];

		$this->imageCreateFromAny();

		//imagejpeg($im, $nom_image, self::JPG_COMPRESSION);
	}

	private function imageCreateFromAny() {
		// Allowed types
		// https://www.php.net/manual/fr/function.exif-imagetype.php
		switch ($this->type) {
			case IMAGETYPE_GIF:
				$this->img = imageCreateFromGif($this->filepath);
				break;
			case IMAGETYPE_JPEG:
				$this->img = imageCreateFromJpeg($this->filepath);
				break;
			case IMAGETYPE_PNG:
				$this->img = imageCreateFromPng($this->filepath);
				break;
			case IMAGETYPE_BMP:
				$this->img = imageCreateFromBmp($this->filepath);
				break;
			default:
				throw new \Exception("Img $this->filepath is not allowed, it's type is $this->type", 1);
		}
	}
}