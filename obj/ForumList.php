<?php
require_once('obj/page.php');

class ForumList extends Page {

	private $forumList;	

	public function __construct() {
		parent::__construct();
		$this->forumList = $this->db->getForumList();
  }

  function getForumList() {
    return $this->forumList;
  }

}

?>
