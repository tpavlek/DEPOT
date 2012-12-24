<?php
/*require_once('/home/ebon/DEPOT/obj/User.php');
require_once('/home/ebon/DEPOT/obj/forum/Post.php');
require_once('/home/ebon/DEPOT/obj/forum/Topic.php');
require_once('/home/ebon/DEPOT/obj/control/PostDeleteButton.php');
require_once('/home/ebon/DEPOT/obj/control/TopicDeleteButton.php');
require_once('/home/ebon/DEPOT/obj/control/PostEditButton.php');
class UserBox {
	private $user;
	private $html;
	
	public function __construct($uid, $id) {
	
		$this->user = new User($uid);
		$this->html = "<div class='userbox'>";
		$this->html .= $this->buildUsername() . "<br />";
		$this->html .= $this->buildUserPicture() . "<br />";
		$this->html .= $this->buildPostCount() . "<br />";
		$this->html .= "<div class='buttonrow'>";
		if (key($id) == 'pid') {
			$post = new Post($id['pid']);
			$this->html .= new PostDeleteButton($post);
			$this->html .= new PostEditButton($post);
		} 
		else if (key($id) == 'tid') {
			$topic = new Topic($id['tid']);
			$this->html .= new TopicDeleteButton($topic);
		}
		$this->html .= "</div>";
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
	
}*/

?>
