<?php

class UserProfile extends Page {

	private $user;

	public function __construct($user) {
		parent::__construct();
		$this->user = $user;
	}
	
	public function showUser() {
		$str = "<ul>";
		$str .= "<span class='big'>" . $this->user->getUsername() ."</span><br><i>".$this->user->getRank() ."</i><br><br>";
    $str .= "<span class='big'>Posts:" . $this->user->getPostcount() . "</span><br><br>";
    $str .= "<span class='big'>Points:" . $this->user->getPoints() . "</span><br>";
		return $str;
	}
}

?>
