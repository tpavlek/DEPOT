<?php
require_once('/home/ebon/DEPOT/obj/db.php');
require_once('/home/ebon/DEPOT/config.php');
class APIForum {
  private $db;

  static function getForums() {
    $db = DB::getInstance();
    return $db->getForumList();
  }

  static function getTopicsInForumByPage() {
    $db = DB::getInstance();
    $pageNum = (isset($_GET['pageNum'])) ? $_GET['pageNum'] : 0;
    $pageLimit = (isset($_GET['pageLimit'])) ? $_GET['pageLimit'] : $GLOBALS['TOPICS_PER_PAGE'];
    $fid = (isset($_GET['fid'])) ? $_GET['fid'] : 0;
    return $db->getTopicsInForumByPage($fid, $pageNum, $pageLimit);
  }

  static function getTopic() {
    $db = DB::getInstance();
    return $db->getTopic($_GET['tid']);
  }
}

?>
