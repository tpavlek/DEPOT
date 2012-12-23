<?php

class UserProfile extends Page {

	private $user;

	public function __construct($user) {
		parent::__construct();
		$this->user = $user;
	}
	
	public function showUser() {
		$str = "<ul>";
		$str .= "<span class='big'>" . $this->user->getUsername() ."</span><br><i>".$this->user->getRank() ."</i><br>";
		$str .= "<img src='" . $this->user->getProfilePic() . "' /><br><br>";
    $str .= "<span class='big'>Posts:" . $this->user->getPostcount() . "</span><br><br>";
		return $str;
	}
}

?>
