<?php
require_once('obj/User.php');
require_once('obj/control/PostDeleteButton.php');
require_once('obj/control/TopicDeleteButton.php');
class UserBox {
	private $user;
	private $html;
	
	public function __construct($uid, $id) {
	
		$this->user = new User($uid);
		$this->html = "<div class='userbox'>";
		$this->html .= $this->buildUsername() . "<br />";
		$this->html .= $this->buildUserPicture() . "<br />";
		$this->html .= $this->buildPostCount() . "<br />";
		if (key($id) == 'pid') $this->html .= new PostDeleteButton($id['pid']);
		else if (key($id) == 'tid') $this->html .= new TopicDeleteButton($id['tid']);
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
