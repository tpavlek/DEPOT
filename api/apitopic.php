<?php
require_once('/home/ebon/DEPOT/obj/page.php');
require_once('/home/ebon/DEPOT/obj/db.php');
class APITopic {

	/*static function adminDeleteTopic() {
		$db = DB::getInstance();
		if (!$db->isAdmin($_SESSION['uid'])) return array('status' => 1, 'message' => 'You are not an admin');
		if (isset($_POST['tid'])) $tid = str_replace('tid_', '', $_POST['tid']);
		else return array('status' => 1, 'message' => 'You did not post me the tid, hobo');
		$result = $db->deleteTopic($tid);
		if ($result['status'] == 0) return array('status' => 0, 'tid' => 'tid_' . $tid);
	}
	
	static function userDeleteTopic() {
		$db = DB::getInstance();
		if (isset($_POST['tid'])) $tid = str_replace('tid_', '', $_POST['tid']);
		else return array('status' => 1, 'message' => 'You did not post me the tid, hobo');
		if (!$db->isTopicAuthor($tid, $_SESSION['uid'])) return array('status' => 1, 'message' => 'You are not the topic author');
		$result = $db->userDeleteTopic($tid);
		if ($result['status'] == 0) return array('status' => 0, 'tid' => 'tid_' . $tid);
  }*/

  static function reply() {
    $db = DB::getInstance();
    if (!isset($_SESSION['uid'])) return false; // todo actually return valuable
    $author = $_SESSION['username'];
    $author_uid = $_SESSION['uid'];
    $subject = $_POST['subject'];
    $message = nl2br($_POST['message']);
    $tid = $_GET['tid'];
    $result = $db->addPost(array('table' => 'posts', 'fields' => array(':author' => $author, ':author_uid' => $author_uid, ':subject' => $subject, ':message' => $message, ':in_reply_to' => $tid, ':date' => date("d-m-Y H:i:s"))));
    return($result);
  }
}

?>
