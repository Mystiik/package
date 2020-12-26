<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/init.php'); ?>

<?php
if (isset($_POST['function'])) {
  //---------------------------------------------------------------------------------------------
  // remove_article
  //---------------------------------------------------------------------------------------------
  if ($_POST['function'] == 'submit_member' and isset($_POST['data'])) {
    $array = json_decode($_POST['data'], true);
    $memberList = [];
    foreach ($array as $element) {
      $member = new GN\Team\Member($element);
      $memberList[] = $member;
    }

    $f = fopen(ROOT . '/vendor/gnicolas/package/team/data/team.txt', 'w');
    fwrite($f, serialize($memberList));
    fclose($f);

    var_dump($memberList);
  }
}