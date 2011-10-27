<?php
require_once('obj/db.php');
class APITopic {

	static function adminDeleteTopic() {
		$db = DB::getInstance();
		$_SESSION['uid'] = 987;
		$_SESSION['rank'] = 'admin';
		if (!$db->isAdmin($_SESSION['uid'])) return array('status' => 1, 'message' => 'You are not an admin');
		if (isset($_POST['tid'])) $tid = str_replace('tid_', '', $_POST['tid']);
		else return array('status' => 1, 'message' => 'You did not post me the tid, hobo');
		$result = $db->deleteTopic($tid);
		if ($result['status'] == 0) return array('status' => 0, 'tid' => 'tid_' . $tid);
	}
}

?>
