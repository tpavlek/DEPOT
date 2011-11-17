<?php
require_once('obj/page.php');
class SessObj extends Page {

	public function logoutUser() {
		// Session must already be started, it's done on Page obj
		session_unset();
		session_destroy();
	}

	public function loginUser($email) { //TODO handle status 1
		if ($this->db->isInDatabase(array('type' => 'email', 'value' => $email))) {
			$arr = $this->db->getUserByEmail($email, array('id', 'username','rank', 'colour_time'));
			$_SESSION['username'] = $arr['username'];
			$_SESSION['rank'] = $arr['rank'];
			$_SESSION['uid'] = $arr['id'];
			$_SESSION['colour_time'] = $arr['colour_time'];
			return(array('status' => 0, 'message' => 'Successfully logged in'));
		} else
			return (array('status' => 1, 'message' => 'Account not found in database'));
	}

	public function registerUser($email,$args) {
		$username = $args['username'];
		$block = $args['block'];
		if ($this->db->isInDatabase(array('type' => 'email', 'value' => $email))) {
			return (array('status' => 1, 'message' => "Email is already in database"));		
		} else if ($this->db->isInDatabase(array('type' => 'username', 'value' => $username))) {
			return (array('status' => 1, 'message' => "Username is already in database"));
		} else { 
			return $this->db->add(array('table' => 'user', 'fields' => array(':username' => $username, 
				':email' => $email, ':block' => $block, ':join_date' => $this->date)));
		}
	}


}

?>
