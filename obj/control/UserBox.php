<?php
require_once('obj/User.php');
class UserBox {
	private $user;
	private $html;
	
	public function __construct($uid) {
	
		$this->user = new User($uid);
		$this->html = "<div class='userbox'>";
		$this->html .= $this->buildUsername() . "<br />";
		$this->html .= $this->buildUserPicture() . "<br />";
		$this->html .= $this->buildPostCount() . "<br />";
		$this->html .= "</div>";
		
	}
	
	public function __toString() {
		return $this->html;
	}
	
	function buildUsername() {
		$str = "<a href='?page=userProfile&uid=" . $this->user->getUID() . "'<span class='bold'>" . $this->user->getUsername() . "</span></a>";
		return $str;
	}
	
	function buildUserPicture() {
		$str = "<img src='" . $this->user->getProfilePic() . "' />";
		return $str;
	}
	
	function buildPostCount() {
		$str = "<span>Posts: " . $this->user->getPostCount() . "</span>";
		return $str;
	}
	
}

?>
