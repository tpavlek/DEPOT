<?php
require_once('obj/page.php');

class APIMisc {

  static function submitAcronym() {
    $page = new Page();
    $db = $page->getDB();
    $str = $_POST['acronym'];
    if (substr($str, -1) != "!") {
      $str .= "!";
    }
    return $db->submitAcronym($str);
  }

  static function modAcronym() {
    $page = new Page();
    $db = $page->getDB();
    if ($_POST['approval']) {
      return $db->approveAcronym($_POST['id']);
    } else {
      return $db->deleteAcronym($_POST['id']);
    }
  }

}

?>
