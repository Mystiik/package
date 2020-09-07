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
	const DIR_SAVE_IMG = "/assets"; // from document_root
	const JPG_COMPRESSION = 50; // jpg compression level

	const RESIZE_NORMAL = "normal"; // just resize
	const RESIZE_CROP = "crop"; // resize by cropping, centered
	const RESIZE_BOTH = "both"; // do 50% cropping centered, 50% resizing
	const MAX_SCREEN_SIZE = 1920; // use to set a base calculation for $size = xxvw
	const SIZE = [100, 250, 500, 750, 1200];

	// Must declare static because the main function is static => $this doen't exist
	private static $sizeUsage = 0; // 300
	private static $sizeUsageType = ""; // "vw", "px"
	private static $maxUsefullSize = ""; // "vw", "px"

	public static function src(string $filepath, string $size, $resizeParams = self::RESIZE_NORMAL, $resizeUpdate = null)
	{
		// Image construction
		$img = new Image($_SERVER['DOCUMENT_ROOT'] . $filepath);

		//------------------------------------------------------------------
		// Resizing
		//------------------------------------------------------------------
		// Get max size usage
		self::setSizeUsage($size);

		// Minimum of size usage or img size => max usefull size
		self::$maxUsefullSize = min(self::$sizeUsage, $img->getWidth());

		// Is resize update requested ?
		$resizeUpdate = $resizeUpdate ?? self::RESIZE_UPDATE;

		// Is resize update needed ?
		// todo
		$resizeNeed = false;

		// Resize
		if ($resizeUpdate or $resizeNeed) {
			foreach (self::SIZE as $SIZE) {
				// If the target size is bigger than the max usefull size, then it's not necessary to generate img
				if (self::$maxUsefullSize < $SIZE) {
					continue;
				} else {
					switch ($resizeParams) {
						case self::RESIZE_NORMAL:
							// Define sizes
							$src_img = $img->getImg();
							$dst_x = 0;
							$dst_y = 0;
							$src_x = 0;
							$src_y = 0;
							$dst_w = $SIZE;
							$dst_h = $SIZE * $img->getHeight() / $img->getWidth(); // Produit en croix
							$dst_h = round($dst_h / 10) * 10; // Round to 10px
							$src_w = $img->getWidth();
							$src_h = $img->getHeight();
							$dst_img = imagecreatetruecolor($dst_w, $dst_h);
							imagecopyresized($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
							break;
						case self::RESIZE_CROP:
							# code...
							break;
						case self::RESIZE_BOTH:
							# code...
							break;
						default:
							throw new \Exception("Wrong resizeParams given, arg passed: \"$resizeParams\"", 1);
					}

					//------------------------------------------------------------------
					// Define path
					//------------------------------------------------------------------
					var_dump($filepath);

					// Save the image
					imagejpeg($dst_img, $_SERVER['DOCUMENT_ROOT'] . "/img.jpg", self::JPG_COMPRESSION);
				}
			}
		}

		// Printing HTML code
		return "src='$filepath'";
	}

	private static function setSizeUsage(string $size)
	{
		switch (substr($size, -2)) {
			case 'px':
				// Get the px value
				self::$sizeUsage = substr($size, 0, strlen($size) - 2);
				self::$sizeUsageType = "px";
				break;
			case 'vw':
				// Get the vw value
				$vw = substr($size, 0, strlen($size) - 2);

				// Check vw value
				if ($vw <= 100) {
					self::$sizeUsage = self::MAX_SCREEN_SIZE * $vw / 100;
					self::$sizeUsageType = "vw";
				}
				// if vw > 100, throw error
				else {
					throw new \Exception("Viewport size can't exceed 100, arg passed: \"$size\"", 1);
				}
				break;
			default:
				// If nothing if found, throw error
				throw new \Exception("Wrong size, arg passed: \"$size\"", 1);
				break;
		}
	}

	static function resizeNormal()
	{
	}
}