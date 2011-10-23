<?php
require_once('obj/page.php');

class UserList extends Page {
	private $userList;

	public function __construct($order) {
		parent::__construct();
		$this->userList = $this->db->getUserList($order);
	}

	function showUsers() {
		$str = "<div id='listbox'>";
		$str .= "<table cellspacing='0'><th><a href='?page=userlist&sort=orderBy'>Username</a></th>";
		$str .= "<th><a href='?page=userList&orderBy=join_date'>Joined on</a></th>";
		$str .= "<th><a href='?page=userList&orderBy=postcount'>Post Count</a></th>";
		$str .= "<th><a href='?page=userList&orderBy=points'>Points</a></th>";
		foreach ($this->userList['data'] as $user) {
			$str .= "<tr><td><a href='?page=userProfile&uid=".$user->getUID()."'>" 
  			. $user->getUsername(). "</td><td>" . $user->getJoinDate() . "</td><td>" 
  			. $user->getPostcount() . "</td><td>" . $user->getPoints() . "</td></tr>";
		}
		$str .= "</table>";
		$str .= "</div>";
		return $str;
	}

}
