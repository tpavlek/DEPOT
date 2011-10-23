<?php
require_once('obj/page.php');
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
	}

	function showTopic() {
		$str = "<ul><li class='subject'>" . $this->subject . "</li>";
    $str .= "<li class='author'><a href='?page=userProfile&uid=" . $this->author_uid . "'>" . $this->author . "</a></li><hr>";
    $str .= "<li class='message'>" . $this->message . "</li></ul>";
		$str .= $this->getReplies();
		$str .= "<ul class='big'>";
		$str .= "<a href='?page=post&tid=" . $this->tid ."'>NEW REPLY</a>";
		$str .= "</ul>";
		return $str;
	}

	static function showTopicForm($fid) {
		$str = "<ul><form action='?page=newTopic&method=newTopic&fid=" . $fid
      . "' method='post'> 
      <p><li><label for='subject'> Subject: 
      <input class='reply' type='text' name='subject'></li></p>
      <p><li><label for='message'> Message:
      <textarea class='reply' name='message'></textarea></li></p>
      <input type='submit' value='GO GO!'>
      </form></ul>"; 
		return $str;
	}

	static function createTopic($obj) {
		require_once('funcs/verify.php');
		if (!verifyString($_POST['subject'],3,100))
			return array('status' => 1, 'message' => 'Subject is invalid');
		else {
			$result = $obj->db->createTopic(array('table' => 'topics', 'fields' => array(':subject' =>
			$_POST['subject'], ':message' => $_POST['message'], ':author' => $_SESSION['username'], ':author_uid' =>
			$_SESSION['uid'], ':last_poster' => $_SESSION['username'], ':date' => $obj->date, ':last_reply' =>
			$obj->date, ':in_forum' => $_GET['fid'], ':last_reply_uid' => $_SESSION['uid'])));
			if($result['status'] == 0)
				$obj->db->updateForumList(new Topic($obj->db->getLastInsertId()));
			return $result;
		}
	}

	function getReplies() {
		$pageNum = (isset ($_GET['pageNum'])) ? $_GET['pageNum'] : 0;
		$postsPerPage = (isset($_GET['postsPerPage'])) ? $_GET['postsPerPage'] : 35;
		$this->replies = $this->db->getRepliesInTopicByPage($this->tid, array('pageNum' => $pageNum, 'postsPerPage' => $postsPerPage));
		$str = "";
		foreach ($this->replies['data'] as $post) {
			$str .= "<ul>";
      $str .= "<li class='subject'>" . $post->getSubject() . "</li>";
      $str .= "<li class='author'><a href='?page=userProfile&uid=" . $post->getAuthorUID() . "'>" . $post->getAuthor() . "</a></li><hr>";
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
	function getLastPoster() {
		return $this->last_poster;
	}
}

?>
