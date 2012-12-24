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

  static function newTopic() {
    $db = DB::getInstance();
    //TODO verify login
    $page = new Page(); // this seem slike a dirty hack TODO
    $result = $db->addTopic(array('table' => 'topics', 'fields' => array(':subject' =>
			$_POST['subject'], ':message' => nl2br($_POST['message']), ':author' => $_SESSION['username'], ':author_uid' =>
			$_SESSION['uid'], ':last_poster' => $_SESSION['username'], ':date' => $page->getDate(), ':last_reply' =>
			$page->getDate(), ':in_forum' => $_GET['fid'], ':last_reply_uid' => $_SESSION['uid'], ':last_reply_pid' => 0)));
			return $result;

  }
}

?>
