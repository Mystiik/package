<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php'); ?>

<?php
if (isset($_POST['function'])) {
  //---------------------------------------------------------------------------------------------
  // remove_article
  //---------------------------------------------------------------------------------------------
  if ($_POST['function'] == 'remove_article' and isset($_POST['id'])) {
    $path = ROOT . GN\Article\ArticleFactory::DIR_SAVE_ARTICLE . '/' . $_POST['id'] . '.txt';
    var_dump($path);
    if (file_exists($path)) var_dump(unlink($path));
  }
}