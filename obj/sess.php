<?php
require_once('/home/ebon/DEPOT/obj/page.php');
class SessObj extends Page {

	public function logoutUser() {
		// Session must already be started, it's done on Page obj
		session_unset();
		session_destroy();
	}

	public function loginUser($email) { //TODO handle status 1
		if ($this->db->isInDatabase(array('type' => 'email', 'value' => $email))) {
			$arr = $this->db->getUserByEmail($email, array('id', 'username','rank'));
			$_SESSION['username'] = $arr['username'];
			$_SESSION['rank'] = $arr['rank'];
			$_SESSION['uid'] = $arr['id'];
			return(array('status' => 0, 'message' => 'Successfully logged in'));
		} else
			return (array('status' => 1, 'message' => 'Account not found in database'));
	}

	public function registerUser($email,$username) {
		if ($this->db->isInDatabase(array('type' => 'email', 'value' => $email))) {
			return (array('status' => 1, 'message' => "Email is already in database"));		
		} else if ($this->db->isInDatabase(array('type' => 'username', 'value' => $username))) {
			return (array('status' => 1, 'message' => "Username is already in database"));
    } else { 
      $result = $this->db->registerUser($username, $email, $this->date);
      if ($result['status'] == 0) {
        return $this->loginUser($email);
      } else return $result;
		}
	}


}

?>
