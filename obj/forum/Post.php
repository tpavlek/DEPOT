<?php
require_once('obj/page.php');
class Post extends Page {

	private $tid;
	private $pid;
	private $subject;
	private $message;
	private $author;
	private $author_uid;
	private $postDate;

	public function __construct($pid) {
		parent::__construct();
		$arr = $this->db->getPost($pid);
		$this->tid = $arr['data']['in_reply_to'];
		$this->pid = $arr['data']['id'];
		$this->subject = $arr['data']['subject'];
		$this->message = $arr['data']['message'];
		$this->author = $arr['data']['author'];
		$this->author_uid = $arr['data']['author_uid'];
		$this->postDate = $arr['data']['date'];
	}
	
	function isDeleted() {
		return ($this->message == "[deleted]");
	}

	function getTID() {
		return $this->tid;
	}

	function getPID() {
		return $this->pid;
	}
	
	function getAuthor() {
		return $this->author;
	}
	
	function getAuthorUID() {
		return $this->author_uid;
	}
	
	function getSubject() {
		return $this->subject;
	}

	function getMessage() {
		return $this->message;
	}

	function getDate() {
		return $this->postDate;
	}
}

?>
