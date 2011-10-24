<?php

class TopicAction extends Page {
	function showForm() {
		$str = "<ul><form action='?page=newTopic&method=newTopic&fid=" . $_GET['fid']
      . "' method='post'> 
      <p><li><label for='subject'> Subject: 
      <input class='reply' type='text' name='subject'></li></p>
      <p><li><label for='message'> Message:
      <textarea class='reply' name='message'></textarea></li></p>
      <input type='submit' value='GO GO!'>
      </form></ul>"; 
		return $str;
	}
	
	function createTopic() {
		require_once('funcs/verify.php');
		if (!verifyString($_POST['subject'],3,100))
			return array('status' => 1, 'message' => 'Subject is invalid');
		else {
			$result = $this->db->addTopic(array('table' => 'topics', 'fields' => array(':subject' =>
			$_POST['subject'], ':message' => $_POST['message'], ':author' => $_SESSION['username'], ':author_uid' =>
			$_SESSION['uid'], ':last_poster' => $_SESSION['username'], ':date' => $this->date, ':last_reply' =>
			$this->date, ':in_forum' => $_GET['fid'], ':last_reply_uid' => $_SESSION['uid'], ':last_reply_pid' => 0)));
			return $result;
		}
	}
}

?>
