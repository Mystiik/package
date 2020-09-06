<?php

namespace GN;

use GN\Image;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class Srcset
{
	const CONFIG_RESIZE_UPDATE = false; // true -> resize images every call
	const RESIZE_NORMAL = "normal"; // just resize
	const RESIZE_CROP = "crop"; // resize by cropping, centered
	const RESIZE_BOTH = "both"; // do 50% cropping centered, 50% resizing
	const SIZE = [60, 200, 500, 750, 1200];

	public static function src($filepath, $size, $resizeParams = self::RESIZE_NORMAL, $resizeUpdate = null)
	{
		// Image construction
		$img = new Image($filepath);

		// Resizing
		$resizeUpdate = $resizeUpdate ?? self::CONFIG_RESIZE_UPDATE;

		if ($resizeUpdate) {
			foreach (self::SIZE as $resizeWidth) {
				if ($img->getWidth() < $resizeWidth) {
					continue;
				} else {
				}
			}
		}

		// Printing HTML code
	}


	static function resizeNormal()
	{
	}
}