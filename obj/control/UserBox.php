<?php
require_once('obj/User.php');
require_once('obj/forum/Post.php');
require_once('obj/forum/Topic.php');
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
		if (key($id) == 'pid') {
			$post = new Post($id['pid']);
			$this->html .= new PostDeleteButton($post);
		} 
		else if (key($id) == 'tid') {
			$topic = new Topic($id['tid']);
			$this->html .= new TopicDeleteButton($topic);
		}
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
