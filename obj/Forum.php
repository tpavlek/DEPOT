<?php
require_once('obj/page.php');
class Forum extends Page {

	private $fid;
	private $topicList;
	private $pageNum;

	public function __construct($fid,$args) {
		parent::__construct();
		$this->topicList = $this->db->getTopicsInForumByPage($fid, $args);
		$this->fid = $fid;
		$this->pageNum = $args['pageNum'];
	}

	function displayTopics() {
		$str = "<ul>";
		if ($this->topicList['status'] == 1) {
			$str .= $this->topicList['message'];
		} else {
		foreach ($this->topicList['data'] as $topic) {
			$str .= "<a href='?page=viewTopic&tid=" . $topic->getTid() . "'><li class='subject'>" . $topic->getSubject() . "</li></a>";
			$str .= "<div class='author' style='text-align:right'><span><a href='?page=userProfile&uid=" . $topic->getLastReplyUID() . "'>" . $topic->getLastPoster() . "</a></span><br><span><a href='?page=viewTopic&tid=" . $topic->getTid() . "#pid_" . $topic->getLastReplyPID() . "'><img src='assets/icons/arrow_sm.png' height=10><a></span></div>";
			$str .= "<hr>";
		}
		}
		$str .= "</ul>";
		return $str;
	}

	function displayNewPostButton() {
		$str = "<ul>
			<a href='?page=newTopic&fid=" . $this->fid . "'><li class='big'>NEW TOPIC</li></a>
			</ul>";
		return $str;
	}


}
?>
