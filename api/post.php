<?php
session_start();
class Post {
static function adminDeletePost() {
	$db = DB::getInstance();
	$pid = str_replace("pid_", "", $_POST['pid']);
	if ($db->isAdmin($_SESSION['uid'])) {
		$result = $db->deletePost($pid);
		if ($result['status'] == 1)
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
}

?>
