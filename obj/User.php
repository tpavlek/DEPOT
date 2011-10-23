<?php
require_once('obj/page.php');

class User extends Page {
	protected $uid;
	private $username;
	private $join_date;
	private $rank;
	private $postcount;
	private $block;
	private $points;

	public function __construct($uid) {
		parent::__construct();
		$arr = $this->db->getUser($uid);
		$this->uid = $arr['data']['id'];
		$this->username = $arr['data']['username'];
		$this->join_date = $arr['data']['join_date'];
		$this->rank = $arr['data']['rank'];
		$this->postcount = $arr['data']['postcount'];
		$this->block = $arr['data']['block'];
		$this->points = $arr['data']['points'];
	}

	function getUsername() {
		return $this->username;
	}

	function getUID() {
		return $this->uid;
	}

	function getJoinDate() {
		return $this->join_date;
	}
	
	function getRank() {
		return $this->rank;
	}
	
	function getPostcount() {
		return $this->postcount;
	}
	
	function getPoints() {
		return $this->points;
	}
}


?>
