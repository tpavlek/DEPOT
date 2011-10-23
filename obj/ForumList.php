<?php
require_once('obj/page.php');

class ForumList extends Page {

	private $forumList;	

	public function __construct() {
		parent::__construct();
		$this->forumList = $this->db->getForumList();
	}

	function showForums() {
		$str = "<ul>";
		foreach ($this->forumList as $forum) {
			$str .= "<a href='?page=viewForum&fid=" . $forum['fid'] ."'>";
			$str .= "<li class='subject'>" . $forum['name'] . "</li></a>";
			$str .= "<li class='author'><a href='?page=viewTopic&tid=" . $forum['last_topic_id'] . "'>" . $forum['last_topic'] . "</a></li><hr>";
		}
		$str .= "</ul>";
		return $str;
	}
}

?>
