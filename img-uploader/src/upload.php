<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php');

use GN\Srcset;

$mime_type = array('image/gif', 'image/jpg', 'image/jpe', 'image/jpeg', 'image/png', 'image/bmp');
$targetFolder = "/assets/img/upload/";


if (!empty($_FILES)) {
  if (isset($_FILES['file']) and is_array($_FILES['file'])) {
    // Create folder path
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder)) {
      mkdir($_SERVER['DOCUMENT_ROOT'] . $targetFolder, 0777, true);
    }

    // foreach ($_FILES['file']['tmp_name'] as $path) {
    $path = $_FILES['file']['tmp_name'];
    // Check if uploaded file is an image
    if (getimagesize($path)) {
      // Check if image ext is authorized
      $mime = getimagesize($path)['mime'];
      if (in_array($mime, $mime_type)) {
        $name = rand() . "." . explode("/", $mime)[1];
        $targetFile =  $targetFolder . $name;
        // Move uploaded file
        move_uploaded_file($path, $_SERVER['DOCUMENT_ROOT'] . $targetFile);
        // Optimize file
        // Srcset::createOptimizedImage($targetFile, ["1500px", "500px", "1px"], Srcset::RESIZE_NORMAL, "/upload/");
        Srcset::src($targetFile, ["1500px", "500px", "1px"], Srcset::RESIZE_NORMAL, "/upload/");
      }
    }
    // }
    // header('Location: /admin/article/image/gestion');
  }
}

// var_export($_FILES);
// var_export($_POST);