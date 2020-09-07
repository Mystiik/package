<?php

namespace GN;

use GN\Image;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Srcset
{
	const RESIZE_UPDATE = true; // true -> resize images every call
	const DIR_SAVE_IMG = "/assets/img-@generated"; // from document_root
	const JPG_COMPRESSION = 50; // jpg compression level

	const RESIZE_NORMAL = "normal"; // just resize
	const RESIZE_CROP = "crop"; // resize by cropping, centered
	const RESIZE_BOTH = "both"; // do 50% cropping centered, 50% resizing
	const MAX_SCREEN_SIZE = 1920; // use to set a base calculation for $size = xxvw
	const SIZE = [100, 250, 500, 750, 1200, "src"];

	// Must declare static because the main function is static => $this doen't exist
	private static $savingPath;
	private static $filename; // file name (without extension)
	private static $sizeUsage; // 300
	private static $sizeValue; // 300
	private static $sizeType; // "vw", "px"
	private static $maxUsefullSize; // Minimum of size usage or img size => max usefull size


	public static function src(string $filepath, string $size, $resizeParam = self::RESIZE_NORMAL, $resizeUpdate = null)
	{
		// Image construction
		$img = new Image($_SERVER['DOCUMENT_ROOT'] . $filepath);

		// Get the file name (without extension)
		self::$filename = pathinfo($filepath)['filename'];

		self::setSizeUsage($img, $size);
		self::definePath();
		self::resize($img, $resizeParam);
		return self::generateHTML();
	}


	private static function setSizeUsage(Image $img, string $size)
	{
		switch (substr($size, -2)) {
			case 'px':
				// Get the px value
				self::$sizeValue = substr($size, 0, strlen($size) - 2);
				self::$sizeUsage = self::$sizeValue;
				self::$sizeType = "px";
				break;
			case 'vw':
				self::$sizeValue = substr($size, 0, strlen($size) - 2);
				self::$sizeUsage = self::MAX_SCREEN_SIZE * self::$sizeValue / 100;
				self::$sizeType = "vw";

				if (self::$sizeValue > 100) {
					throw new \Exception("Viewport size can't exceed 100, arg passed: \"$size\"", 1);
				}
				break;
			default:
				// If nothing if found, throw error
				throw new \Exception("Wrong size, arg passed: \"$size\"", 1);
				break;
		}

		// Minimum of size usage or img size => max usefull size
		self::$maxUsefullSize = min(self::$sizeUsage, $img->getWidth());
	}

	private static function definePath()
	{
		// $_SERVER['DOCUMENT_ROOT'] 	-> C:/wamp64/www/Gnicolas/
		// $_SERVER['SCRIPT_FILENAME']	-> C:/wamp64/www/Gnicolas/index.php
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_SERVER['SCRIPT_FILENAME']);
		$path = str_replace(".php", "", $path);
		$path = self::DIR_SAVE_IMG . "/$path";
		$path = explode("/", $path);

		// Folder path generation
		$i = 0;
		$savingPath = "";
		while ($i < count($path)) {
			if ($path[$i] == "") {
				// Depile 1st element
				array_shift($path);
				continue;
			}

			$savingPath .= "/" . $path[$i];
			if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $savingPath)) {
				mkdir($_SERVER['DOCUMENT_ROOT'] . $savingPath);
			}

			$i++;
		}

		self::$savingPath = $savingPath;
	}

	private static function resize(Image $img, $resizeParam)
	{
		for ($i = 0; $i < count(self::SIZE); $i++) {
			$SIZE = self::SIZE[$i];
			$SIZE_PREC = self::SIZE[$i - 1] ?? 0;

			// Is resize update requested ?
			$resizeUpdate = $resizeUpdate ?? self::RESIZE_UPDATE;

			// Is resize update needed ?
			$resizeNeed = !file_exists(self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg");

			if ($resizeUpdate or $resizeNeed) {
				if (!($SIZE == "src" or $SIZE_PREC < self::$maxUsefullSize or $SIZE < self::$maxUsefullSize)) {
					continue;
				} else {
					// Check that the dst folder exists
					if (!file_exists($_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE")) {
						mkdir($_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE");
					}

					// Resize
					if ($SIZE != "src") {
						$src_img = $img->getImg();
						$dst_x = 0;
						$dst_y = 0;
						$dst_w = $SIZE;
						$dst_h = $SIZE * $img->getHeight() / $img->getWidth(); // Produit en croix
						$dst_h = round($dst_h / 10) * 10; // Round to 10px

						switch ($resizeParam) {
							case self::RESIZE_NORMAL:
								// Define sizes
								$src_x = 0;
								$src_y = 0;
								$src_w = $img->getWidth();
								$src_h = $img->getHeight();
								$dst_img = imagecreatetruecolor($dst_w, $dst_h);
								imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
								break;
							case self::RESIZE_CROP:
								// Define sizes
								$src_x = ($img->getWidth() - $dst_w) / 2;
								$src_y = ($img->getHeight() - $dst_h) / 2;
								$src_w = $dst_w;
								$src_h = $dst_h;
								$dst_img = imagecreatetruecolor($dst_w, $dst_h);
								imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
								break;
							case self::RESIZE_BOTH:
								// Define sizes
								$src_x = ($img->getWidth() - $dst_w) / 4;
								$src_y = ($img->getHeight() - $dst_h) / 4;
								$src_w = $dst_w + ($img->getWidth() - $dst_w) / 2;
								$src_h = $dst_h + ($img->getHeight() - $dst_h) / 2;
								$dst_img = imagecreatetruecolor($dst_w, $dst_h);
								imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
								break;
							default:
								throw new \Exception("Wrong resizeParam given, arg passed: \"$resizeParam\"", 1);
						}
					} else {
						// Src image will still be compressed as JPG 50%
						$src_img = $img->getImg();
						$dst_img = $src_img;
					}


					// Save the image
					imagejpeg($dst_img, $_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg", self::JPG_COMPRESSION);
				}
			}
		}
	}

	private static function generateHTML()
	{
		//------------------------------------------------------------------
		// Print HTML code
		//------------------------------------------------------------------
		// sizes='100vw'
		$return = "sizes='" . self::$sizeValue . self::$sizeType . "' ";

		// srcset="wolf-400.jpg 400w,"
		$return .= "srcset='";

		foreach (self::SIZE as $SIZE) {
			$path = self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg";

			if ($SIZE != "src") {
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$path")) {
					// "wolf-400.jpg 400w,"
					$return .= $path . " " . $SIZE . "w, ";
				}
			} else {
				$return .= "' ";

				// src="wolf-400.jpg"
				$return .= "src='$path'";
			}
		}

		return $return;
	}
}