<?php

namespace GN;

use GN\Image;
use GN\GlbObjFunc\Glb;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Srcset {
	const RESIZE_UPDATE = false; // true -> resize images every call
	const LAZY_LOAD = false;
	const DIR_SAVE_IMG = "/assets/img-@generated"; // from document_root
	const JPG_COMPRESSION = 50; // jpg compression level (50 - opti)

	const RESIZE_NORMAL = "normal"; // just resize
	const RESIZE_CROP = "crop"; // resize by cropping, centered
	const RESIZE_BOTH = "both"; // do 50% cropping centered, 50% resizing
	const MAX_SCREEN_SIZE = 1920; // use to set a base calculation for $size = xxvw
	const BREAK_SIZE = [992, 768, 576]; // break size of desktop, tablet, mobile
	const SIZE = [1, 40, 100, 250, 500, 750, 1200, "src"];

	// Must declare static because the main function is static => $this doen't exist
	private static $savingPath;
	private static $filename; // file name (without extension)
	private static $sizeUsage = []; // 300 -> desktop, tablet, mobile
	private static $sizeValue = []; // 300 -> desktop, tablet, mobile
	private static $sizeType = []; // "vw", "px" -> desktop, tablet, mobile
	private static $maxUsefullSize; // Minimum of size usage or img size => max usefull size


	public static function src(string $filepath, array $size, $resizeParam = self::RESIZE_NORMAL, $destPath = null) {
		try {
			// Image construction
			$filepath = str_replace($_SERVER['DOCUMENT_ROOT'], "", $filepath);
			$img = new Image($_SERVER['DOCUMENT_ROOT'] . $filepath);

			// Get the file name (without extension)
			self::$filename = pathinfo($filepath)['filename'];
			self::setSizeUsage($img, $size);
			self::definePath($destPath);
			self::resize($img, $resizeParam);
			return self::generateHTML($img, $filepath);
		} catch (\Exception $e) {
			throw $e;
		}
	}

	// public static function createOptimizedImage(string $filepath, array $size, $resizeParam = self::RESIZE_NORMAL, $destPath = null) {
	// 	// Image construction
	// 	$img = new Image($_SERVER['DOCUMENT_ROOT'] . $filepath);

	// 	// Get the file name (without extension)
	// 	self::$filename = pathinfo($filepath)['filename'];
	// 	self::setSizeUsage($img, $size);
	// 	self::definePath($destPath);
	// 	self::resize($img, $resizeParam);
	// }


	private static function setSizeUsage(Image $img, array $size) {
		if (count($size) == 1 or count($size) == count(self::BREAK_SIZE)) {
			for ($i = 0; $i < count($size); $i++) {
				switch (substr($size[$i], -2)) {
					case 'px':
						// Get the px value
						self::$sizeValue[$i] = substr($size[$i], 0, strlen($size[$i]) - 2);
						self::$sizeUsage[$i] = self::$sizeValue[$i];
						self::$sizeType[$i] = "px";
						break;
					case 'vw':
						self::$sizeValue[$i] = substr($size[$i], 0, strlen($size[$i]) - 2);
						self::$sizeUsage[$i] = self::MAX_SCREEN_SIZE * self::$sizeValue[$i] / 100;
						self::$sizeType[$i] = "vw";

						if (self::$sizeValue[$i] > 100) {
							throw new \Exception("Viewport size can't exceed 100, arg passed: \"$size[$i]\"", 1);
						}
						break;
					case 'rc': //src
						self::$sizeValue[$i] = 0;
						self::$sizeUsage[$i] = 0;
						self::$sizeType[$i] = "src";
						break;
					default:
						// If nothing if found, throw error
						throw new \Exception("Wrong size, arg passed: \"$size[$i]\"", 1);
						break;
				}
			}

			// If one size passed, affect every sizes to it
			if (count($size) == 1) {
				for ($i = 1; $i < count(self::BREAK_SIZE); $i++) {
					self::$sizeValue[$i] = self::$sizeValue[$i - 1];
					self::$sizeUsage[$i] = self::$sizeUsage[$i - 1];
					self::$sizeType[$i] = self::$sizeType[$i - 1];
				}
			}

			// Minimum of size usage or img size => max usefull size
			self::$maxUsefullSize = min(max(self::$sizeUsage), $img->getWidth());
		} else {
			throw new \Exception("Wrong size param, expecting 1 or " . count(self::BREAK_SIZE) . " sizes, " . count($size) . " given", 1);
		}
	}

	private static function definePath($destPath) {
		// $_SERVER['DOCUMENT_ROOT'] 	-> C:/wamp64/www/Gnicolas/
		// $_SERVER['SCRIPT_FILENAME']	-> C:/wamp64/www/Gnicolas/index.php
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $destPath ?? $_SERVER['SCRIPT_FILENAME']);
		$path = str_replace(".php", "", $path);
		$path = self::DIR_SAVE_IMG . "/$path";
		$path = str_replace("//", "/", $path);
		$path = str_replace("//", "/", $path);
		// $path = explode("/", $path);

		if (self::RESIZE_UPDATE or !file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
			if (!mkdir($_SERVER['DOCUMENT_ROOT'] . $path, 0777, true)) {
				throw new \Exception("definePath: Folder hasn't been created at: " . $_SERVER['DOCUMENT_ROOT'] . $path, 1);
			}
		}
		self::$savingPath = $path;
	}

	private static function resize(Image $img, $resizeParam) {
		for ($i = 0; $i < count(self::SIZE); $i++) {
			$SIZE = self::SIZE[$i];
			// $SIZE_PREC = self::SIZE[$i - 1] ?? 0;

			// Is resize update needed ?
			$resizeNeed = !file_exists($_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg");
			// $resizeNeed = false;
			if (self::RESIZE_UPDATE or $resizeNeed) {
				// if (!($SIZE == "src" or $SIZE < self::$maxUsefullSize)) {
				if (!($SIZE == "src" or $SIZE < self::$maxUsefullSize or true)) {
					continue;
				} else {
					// Check that the dst folder exists
					if (!file_exists($_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE")) {
						mkdir($_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE");
					}

					// Resize
					if ($SIZE != "src") {
						if ($SIZE != self::SIZE[0]) {
							$src_img = $img->getImg();
							$dst_x = 0;
							$dst_y = 0;
							$dst_w = $SIZE;
							$dst_h = $SIZE * $img->getHeight() / $img->getWidth(); // Produit en croix
							$dst_h = max(round($dst_h / 10), 1) * 10; // Round to 10px, min 10

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
							// This will handle the first print of an image
							// First, resize to a 4x4 box
							$src_img = $img->getImg();
							$dst_x = 0;
							$dst_y = 0;
							$dst_w = $SIZE;
							$dst_h = $SIZE;

							// Define sizes
							$src_x = 0;
							$src_y = 0;
							$src_w = $img->getWidth();
							$src_h = $img->getHeight();
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

							// Then resize to 100px to keep ratios when used in js systems that build on img load (masonry, carousel...)
							$src_img = $dst_img;
							$dst_x = 0;
							$dst_y = 0;
							$dst_w = 100;
							$dst_h = 100 * $img->getHeight() / $img->getWidth(); // Produit en croix
							$dst_h = max(round($dst_h / 1), 1) * 1;

							// Define sizes
							$src_x = 0;
							$src_y = 0;
							$src_w = $SIZE;
							$src_h = $SIZE;
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
						}
					} else {
						// Src image will still be compressed as JPG 50%
						$src_img = $img->getImg();
						$dst_img = $src_img;
					}


					// Save the image
					if (!imagejpeg($dst_img, $_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg", self::JPG_COMPRESSION)) {
						throw new \Exception("resize: ImageJpeg hasn't been created at: " . $_SERVER['DOCUMENT_ROOT'] . self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg", 1);
					};
				}
			}
		}
	}

	private static function generateHTML(Image $img, $filepath) {
		//------------------------------------------------------------------
		// Print HTML code
		//------------------------------------------------------------------
		// sizes='100vw'
		// sizes='(min-width:992px) 800px, (min-width:768px) 600px, (min-width:576px) 400px'
		$return = "sizes='";
		for ($i = 0; $i < count(self::BREAK_SIZE); $i++) {
			$return .= "(min-width:" . self::BREAK_SIZE[$i] . "px) " . self::$sizeValue[$i] . self::$sizeType[$i] . ", ";
		}
		$return .= "' ";

		// srcset="wolf-400.jpg 400w,"if (self::LAZY_LOAD) {
		$return .= "data-srcset='";

		foreach (self::SIZE as $SIZE) {
			$path = self::$savingPath . "/$SIZE" . "/" . self::$filename . ".jpg";

			if ($SIZE != "src") {
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$path")) {
					// "wolf-400.jpg 400w,"
					$return .= $path . " " . $SIZE . "w, ";
				}
			} else {
				if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/$path")) {
					$path = $filepath;
				}
				$return .= $path . " " . $img->getWidth() . "w, ";
				$return .= "' ";

				// src="wolf-400.jpg"
				$return .= "data-src='$path'";

				if (self::LAZY_LOAD) {
					// src='data:image/png;base64,---echo base64_encode(file_get_contents("dir/dir/img.png"));---
					$path = self::$savingPath . "/" . self::SIZE[0] . "/" . self::$filename . ".jpg";
					// $return .= "src='$path' ";
					$return .= "src='data:image/jpeg;base64," . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/$path")) . "' ";

					// Lazy-loading - Direct styling with js
					// $return .= "onload=\"this.style.filter = 'blur(20px)'; this.style.opacity = '1'; this.style.transition = 'all 0.8s linear';\" ";
				} else {
					$return = str_replace("data-", "", $return);
				}
			}
		}

		return $return;
	}

	public static function getImgList($path = self::DIR_SAVE_IMG) {
		return (self::directoryIterator($_SERVER['DOCUMENT_ROOT'] . $path));
	}

	private static function directoryIterator($path) {
		$extToInclude = ['jpg'];
		// $folderToIgnore = ['vendor', 'assets', 'lang'];
		$folderToIgnore = [];
		$array = [];

		if (file_exists($path)) {
			foreach (new \DirectoryIterator($path) as $fileInfo) {
				if ($fileInfo->isDir() and !$fileInfo->isDot() and !in_array($fileInfo->getFilename(), $folderToIgnore)) {
					$array = array_merge(self::directoryIterator($fileInfo->getPathname()), $array);
				}

				if ($fileInfo->isFile()) {
					if (isset(pathinfo($fileInfo->getPathname())['extension']) and in_array(pathinfo($fileInfo->getPathname())['extension'], $extToInclude)) {
						$array[str_replace(".jpg", "", $fileInfo->getFileName())] = [
							'path' => str_replace($_SERVER['DOCUMENT_ROOT'], "", $fileInfo->getPathname()),
							'name' => $fileInfo->getFileName(),
							'size' => \GN\GlbObjFunc\Glb::getSizeFromBytes($fileInfo->getPathname()),
						];
					}
				}
			}
		}
		return $array;
	}
}