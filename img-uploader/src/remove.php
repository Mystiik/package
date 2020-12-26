<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php'); ?>

<?php
if (isset($_POST['id'])) {
  $path = ROOT . GN\Srcset::DIR_SAVE_IMG . '/upload';
  $arrayFolder = GN\Srcset::SIZE;

  foreach ($arrayFolder as $folder) {
    $pathImg = $path . '/' . $folder . '/' . $_POST['id'] . '.jpg';
    var_dump($pathImg);
    if (file_exists($pathImg)) var_dump(unlink($pathImg));
  }
}