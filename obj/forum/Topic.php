<?php
require_once('obj/page.php');
require_once('obj/control/UserBox.php');
class Topic extends Page {

	protected $fid;
	protected $tid;
	private $subject;
	private $message;
	private $last_poster;
	private $author;
	private $author_uid;
	private $replies;
	private $last_reply_uid;
	private $last_reply_pid;

	public function __construct($tid) {
		parent::__construct();
		$this->tid = $tid;
		$arr = $this->db->getTopic($tid);
		$this->subject = $arr['data']['subject'];
		$this->message = $arr['data']['message'];
		$this->author = $arr['data']['author'];
		$this->author_uid = $arr['data']['author_uid'];
		$this->last_poster = $arr['data']['last_poster'];
		$this->fid = $arr['data']['in_forum'];
		$this->last_reply_uid = $arr['data']['last_reply_uid'];
		$this->last_reply_pid = $arr['data']['last_reply_pid'];
	}

	function showTopic($args) {
		$str = "<script src='funcs/lazyload.js'></script>";
		$str .= "<div class='topic'>";
		$str .= "<ul id='tid_" . $this->tid . "'><li class='subject'>" . $this->subject . "<hr></li>";
    $str .= new UserBox($this->getAuthorUID(), array('tid' => $this->tid));
    $str .= "<li class='message'>" . $this->message . "</li></ul>";
		$str .= $this->getReplies($args);
		$str .= "</div>";
		$str .= "<ul class='big'>";
		$str .= "<a href='?page=post&tid=" . $this->tid ."'>NEW REPLY</a>";
		$str .= "</ul>";
		return $str;
	}
	
	function getReplies($args) {
		$this->replies = $this->db->getRepliesInTopicByPage($this->tid, $args);
		if ($this->replies['status'] == 1) return $this->replies['message'];
		$str = "";
		foreach ($this->replies['data'] as $post) {
			$str .= "<ul id='pid_" . $post->getPID() . "'>";
      $str .= "<li class='subject'>" . $post->getSubject() . "<hr></li>";
      $str .= new UserBox($post->getAuthorUID(), array('pid' => $post->getPID()));
      $str .= "<li class='message'>" . $post->getMessage() . "</li>";
      $str .= "</ul>";
		}
		return $str;
	}
	
	function isDeleted() {
		return ($this->message == "[deleted]");
	}

	function getTid() {
		return $this->tid;
	}

	function getFID() {
		return $this->fid;
	}

	function getSubject() {
		return $this->subject;
	}

	function getMessage() {
		return $this->message;
	}

	function getAuthor() {
		return $this->author;
	}

	function getAuthorUID() {
		return $this->author_uid;
	}
	
	function getLastReplyUID() {
		return $this->last_reply_uid;
	}
	
	function getLastReplyPID() {
		return $this->last_reply_pid;
	}
	function getLastPoster() {
		return $this->last_poster;
	}
}

?>
