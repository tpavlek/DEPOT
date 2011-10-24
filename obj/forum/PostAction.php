<?php 
require_once('obj/page.php');
class PostAction extends Page {

	function showForm() {
		$str = "<ul><form action='?page=post&method=newReply&tid=" . $_GET['tid'] . 			"' method='post'>	
			<p><li><label for='subject'> Subject: 
			<input class='reply' type='text' name='subject' value='RE: " . $this->db->getTopicSubject($_GET['tid']) . "'></li></p>
			<p><li><label for='message'> Message:
			<textarea class='reply' name='message'></textarea></li></p>
			<input type='submit' value='GO GO!'>
			</form></ul>";
		return $str;
	}
	
	function newReply() {
		return $this->db->addPost(array('table' => 'posts', 'fields' => array(':author' => $_SESSION['username'], ':author_uid' => $_SESSION['uid'], ':subject' => $_POST['subject'], ':message' => $_POST['message'], ':in_reply_to' => $_GET['tid'], ':date' => $this->date)));
	}
}

?>
