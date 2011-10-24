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

	function showTopic() {
		$str = "<ul><li class='subject'>" . $this->subject . "<hr></li>";
    $str .= new UserBox($this->getAuthorUID());
    $str .= "<li>" . $this->message . "</li></ul>";
		$str .= $this->getReplies();
		$str .= "<ul class='big'>";
		$str .= "<a href='?page=post&tid=" . $this->tid ."'>NEW REPLY</a>";
		$str .= "</ul>";
		return $str;
	}

	function getReplies() {
		$pageNum = (isset ($_GET['pageNum'])) ? $_GET['pageNum'] : 0;
		$postsPerPage = (isset($_GET['postsPerPage'])) ? $_GET['postsPerPage'] : 35;
		$this->replies = $this->db->getRepliesInTopicByPage($this->tid, array('pageNum' => $pageNum, 'postsPerPage' => $postsPerPage));
		$str = "";
		foreach ($this->replies['data'] as $post) {
			$str .= "<ul id='" . $post->getPID() . "'>";
      $str .= "<li class='subject'>" . $post->getSubject() . "<hr></li>";
      $str .= new UserBox($post->getAuthorUID());
      $str .= "<li>" . $post->getMessage() . "</li>";
      $str .= "</ul>";
		}
		return $str;
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
