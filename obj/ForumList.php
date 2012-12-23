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
			$str .= "<a href='?page=viewForum&fid=" . $forum['id'] ."'>";
			$str .= "<li class='subject'>" . $forum['name'] . "</li></a>";
			$str .= "<div class='author' style='text-align:right'><span><b>In Topic:</b> <a href='?page=viewTopic&tid=" . $forum['last_topic_id'] . "'>" . $forum['last_topic'] . "</a>";
			$str .= "<br><span><b>By:</b><a href='?page=userProfile&uid=" . $forum['last_poster_id'] . "'> ". $forum['last_poster'] . "</span>";
			$str .= "<a href='?page=viewTopic&tid=" . $forum['last_topic_id'] . "#" . $forum['last_poster_pid'] . "'> <img src='assets/icons/arrow_sm.png' height=10></a></div><hr>";
		}
		$str .= "</ul>";
		return $str;
	}
}

?>
