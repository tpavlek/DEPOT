<?php
require_once('obj/page.php');
class APIPost {
static function adminDeletePost() {
	$db = DB::getInstance();
	$pid = str_replace("pid_", "", $_POST['pid']);
	if ($db->isAdmin($_SESSION['uid'])) {
		$result = $db->deletePost($pid);
		if ($result['status'] == 0)
			return array('status' => 0, 'pid' => "pid_" . $pid);	
		else return array('status' => 1);
	} else return array('status' => 1);
}
	
	static function userDeletePost() {	//TODO implement in DB
		$db = DB::getInstance();
		$pid = str_replace("pid_", "", $_POST['pid']);
		if ($db->isPostAuthor($pid, $_SESSION['uid'])) {
			$query = "UPDATE posts set message = '[deleted]' where pid = :pid";
			$queryPrepared = $db->getPDO()->prepare($query);
			$queryPrepared->bindParam(':pid', $pid);
			if ($queryPrepared->execute()){
				$db->updatePostCount($_SESSION['uid'], -1);
			 	return array('status' => 0, 'pid' => "pid_" . $pid);
			 }
			else return array('status' => 1);
		}
		else return array('status' => 1, 'message' => 'Not post Author');
	}
	
	static function loadNextPage() {
		require_once('obj/forum/Topic.php');
		$tid = str_replace('tid_', '', $_GET['tid']);
		$num = $_GET['num'];
		$topic = new Topic($tid);
		$data = $topic->getReplies(array('pageNum' => $num, 'postsPerPage' => 15));
		if (!$data) return array ('status' => 1, 'message' => 'No more posts');
		else return array('status' => 0, 'html' => $data, 'pageNum' => $num);
	}
}

?>
