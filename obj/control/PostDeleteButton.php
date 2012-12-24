<?php
require_once('/home/ebon/DEPOT/obj/control/Control.php');
class PostDeleteButton extends Control {
	private $path;
	private $html;
	
	public function __construct($post) {
		parent::__construct();
		$this->path = "assets/icons/del_sm.png";
		$this->html = "";
		if (parent::permissions(array('permissions' => array('admin')))) {
			$this->html .= "<img alt='Delete Post' onClick='adminDeletePost(event)' height=10 src='" . $this->path . "' />";
		}
		else if (isset ($_SESSION['uid'])) {
			if (!$post->isDeleted()) {
				if (parent::permissions(array('permissions' => array('postauthor'), 'args' => array('pid' => $post->getPID(), 'uid' => $_SESSION['uid'])))) { 
					$this->html .= "<img onClick='userDeletePost(event)' height=10 src='" . $this->path . "'>";
				}
			}
		}
	}
	
	public function __toString() {
		return $this->html;
	}
}

?>
