<?php
require_once('obj/page.php');

class User extends Page {
	protected $uid;
	private $username;
	private $join_date;
	private $rank;
	private $postcount;
	private $profilePic;

	public function __construct($uid) {
		parent::__construct();
		$arr = $this->db->getUser($uid);
		$this->uid = $arr['data']['id'];
		$this->username = $arr['data']['username'];
		$this->join_date = $arr['data']['join_date'];
		$this->rank = $arr['data']['rank'];
		$this->postcount = $arr['data']['postcount'];
		$this->profilePic = $arr['data']['profile_pic'];
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
	
	function getProfilePic() {
		return $this->profilePic;
	}
}


?>
