<?php

namespace GN;

use GN\GlbObjFunc\Glb;
use GN\Translate;

/**
 * @param $filepath - dir to the image src
 * @param $size - approx size of the rendered image
 */
class TranslateBase {
	static $langPath = ROOT . '/lang/';
	static $langDefaut = 'fr';
	static $lang;
	static $filePath;

	public static function setupFolderForTranslation(array $folderPathList, $saveOriginArrayName) {
		// Initialisation
		$origin = Translate::read($saveOriginArrayName, true);
		$originNew = [];
		$fr = [];

		// Get filePathList
		$filePathList = [];
		$extToInclude = ['php', 'txt'];
		$folderToIgnore = ['vendor', 'assets', 'lang', 'test'];
		foreach ($folderPathList as $folderPath) $filePathList += Glb::directoryIterator($folderPath, $extToInclude, $folderToIgnore);

		// Parse files
		$array = [];
		foreach ($filePathList as $filePath) $array[$filePath] = self::parseHtml(file_get_contents($filePath), $filePath);
		var_dump($array);

		// Generate translation files
		foreach ($array as $filePath => $textList) {
			if (file_exists($filePath)) {
				// Clean filePath
				$filePathClean = str_replace(ROOT, '', $filePath);
				$filePathClean = str_replace('\\', '/', $filePathClean);
				foreach ($textList as $text) {
					// Change file content
					if (strlen(trim($text->str)) == 0) continue;

					$textNew = Translate::jsonify($text->str);

					$origin[$textNew] = $origin[$textNew] ?? (string)rand();
					$originNew[$filePathClean][$textNew] = $origin[$textNew];
					$fr[$origin[$textNew]] = $textNew;
				}
			}
		}
		Translate::save($originNew, $saveOriginArrayName);
		Translate::save($fr, 'fr');
	}

	public static function parseHtml($html, $filePath = "") {
		// Initialisation
		$array = [];

		// Parse Data
		$getInBetweenStringsList = [];
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<?php", "?" . ">");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<?=", "?" . ">");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<title", "</title>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h1", "</h1>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h2", "</h2>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h3", "</h3>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h4", "</h4>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h5", "</h5>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<h6", "</h6>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<li>", "</li>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<label", "</label>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<button", "</button>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<p", "</p>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<a", "</a>");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<img src=\"", "\"");
		$getInBetweenStringsList += Glb::getInbetweenStrings($html, "<span", "</span>");
		ksort($getInBetweenStringsList);

		// Handle imbrication
		foreach ($getInBetweenStringsList as $element) {
			// First element
			if (count($array) == 0) {
				$array[$element->strStart] = $element;
				continue;
			}

			//
			$isIncluded = false;
			foreach ($array as $key => $elementIncluded) {
				if ($elementIncluded->strStart < $element->strStart and $element->strEnd < $elementIncluded->strEnd) {
					if ($elementIncluded->schemaStart == "<?php" or $elementIncluded->schemaStart == "<?=") continue;
					if ($element->schemaStart != "<a" and $element->schemaStart != "<span") {
						// 1st part
						if ($elementIncluded->strStart < $element->schemaStartValue) {
							$elementTmp = new \GN\GlbObjFunc\GetInBetweenString();
							$elementTmp->str = substr($elementIncluded->str, 0, $element->schemaStartValue - $elementIncluded->strStart);
							$elementTmp->strStart = $elementIncluded->strStart;
							$elementTmp->strEnd = $element->schemaStartValue;
							$elementTmp->strPos = $elementIncluded->strPos;
							$elementTmp->schemaStartValue = $elementIncluded->schemaStartValue;
							$elementTmp->schemaEndValue = $elementTmp->strEnd;
							$array[$key] = $elementTmp;
						} else {
							unset($array[$key]);
						}

						// 2nd part
						if ($element->schemaStart == "<img src=\"") {
							$restOfString = substr($elementIncluded->str, $element->schemaEndValue - $elementIncluded->strEnd);
							$element->schemaEndValue += strlen(explode(">", $restOfString)[0]) + strlen(">");
						}
						$array[$element->strStart] = $element;

						// 3rd part
						if ($element->schemaEndValue < $elementIncluded->strEnd) {
							$elementTmp = new \GN\GlbObjFunc\GetInBetweenString();
							$elementTmp->str = substr($elementIncluded->str, $element->schemaEndValue - $elementIncluded->strEnd);
							$elementTmp->strStart = $element->schemaEndValue;
							$elementTmp->strEnd = $elementIncluded->strEnd;
							$elementTmp->strPos = $elementTmp->strStart;
							$elementTmp->schemaStartValue = $elementTmp->strStart;
							$elementTmp->schemaEndValue = $elementIncluded->schemaEndValue;
							$array[$elementTmp->strStart] = $elementTmp;
						}
					}
					// var_dump($element);
					$isIncluded = true;
				}
			}
			if ($isIncluded == false) {
				$array[$element->strStart] = $element;
				if ($element->schemaStart != "<span") {
				}
				// var_dump($filePath);
				// if ($filePath == 'C:/wamp64/www/GEOS/\admin\multilang\gestion.php') {
				// 	if ($element->schemaStart == "<span") {
				// 		var_dump($element);
				// 	}
				// }
			}
		}

		foreach ($array as $key => $elementIncluded) {
			if ($elementIncluded->schemaStart == "<?php" or $elementIncluded->schemaStart == "<?=") unset($array[$key]);
		}
		return $array;
	}

	public static function imageFormulaireGestion($originalText, &$translatedText) {
		$color = 'primary';
		if (strpos($originalText, '/') === 0) {
			$color = 'warning';
			if (strpos($originalText, '/assets/img') !== false and strpos($originalText, '.jpg') !== false and !isset($translatedText)) {
				$texttmp = explode('/', $originalText);
				$texttmp[count($texttmp) - 1] = 'ID_TRANSLATED_IMAGE.jpg';
				$translatedText = implode('/', $texttmp);
			}
		}
		return $color;
	}
}


		  // var_dump(directoryIterator(ROOT));
		  // var_dump(directoryIterator(ROOT . '/lowtech/'));