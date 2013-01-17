<?php
require_once('/home/ebon/DEPOT/obj/db.php');
require_once('obj/page.php');
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

  static function editPost() {
    $db = DB::getInstance();
    if (!isset($_SESSION['uid'])) {
      return array('status' => 1, 'message' => 'Must be logged in');
    } else if (!($_SESSION['rank'] == 'admin' || $db->isPostAuthor($_GET['pid'], $_SESSION['uid']))) {
      return array('status' => 1, 'message' => "You don't have permissions for that");
    }
    return $db->editPost($_GET['pid'], $_POST['subject'], $_POST['message']);
  }
  static function deletePost() {
    $page = new Page();
    $db = $page->getDB();
    if (!($page->permissions(array("loggedIn")) && ($page->permissions(array("admin")) || $db->isPostAuthor($_POST['pid'], $_SESSION['uid'])))) {
      return (array('status' => 1, 'message' => 'Insufficient permissions'));
    }
    return $db->deletePost($_POST['pid']);
  }

  static function deleteTopic() {
    $page = new Page();
    $db = $page->getDB();
    if(!($page->permissions(array('loggedIn')) && ($page->permissions(array('admin'))) || $db->isTopicAuthor($_POST['tid'], $_SESSION['uid']))) {
      return array('status' => 1, 'message' => 'Insufficient permissions');
    }
    return $db->deleteTopic($_POST['tid']);
  }
  static function newTopic() {
    if (!(isset($_SESSION['uid']))) return array("status" => 1, "message" => "You must be logged in to do that");
    $db = DB::getInstance();
    $page = new Page(); // this seem slike a dirty hack TODO
    $argArray = array('table' => 'topics', 'fields' => array(':subject' =>
			$_POST['subject'], ':message' => nl2br($_POST['message']), ':author' => $_SESSION['username'], ':author_uid' =>
			$_SESSION['uid'], ':last_poster' => $_SESSION['username'], ':date' => $page->getDate(), ':last_reply' =>
      $page->getDate(), ':in_forum' => $_GET['fid'], ':last_reply_uid' => $_SESSION['uid'], ':last_reply_pid' => 0));
    if (!($_FILES['replayUpload']['error'])) {
       print_r($_FILES);
       $replayPath =  "assets/replays/".$_SESSION['uid'] . "_" .date('YmdHis') .".SC2Replay";
       move_uploaded_file($_FILES['replayUpload']['tmp_name'], $replayPath);
       $argArray['fields'][':replay'] = $replayPath;
    }
    
    $result = $db->addTopic($argArray);
			return $result;

  }
}

?>
