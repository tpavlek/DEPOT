<?php
require_once('obj/control/Control.php');
class PostEditButton extends Control {
	private $path;
	private $html;
	
	public function __construct($post) {
		parent::__construct();
		$this->path = "assets/icons/edit_sm.png";
		$this->html = "";
		if (parent::permissions(array('permissions' => array('admin')))) {
			$this->html .= "<a href='?page=post&method=showEditForm&pid=" . $post->getPID() . "'><img alt='Edit Post' height=10 src='" . $this->path . "' /></a>";
		}
		else if (isset ($_SESSION['uid'])) {
			if (!$post->isDeleted()) {
				if (parent::permissions(array('permissions' => array('postauthor'), 'args' => array('pid' => $post->getPID(), 'uid' => $_SESSION['uid'])))) {
					$this->html .= "<a href='?page=post&method=showEditForm&pid=" . $post->getPID() ."' <img height=10 src='" . $this->path . "'></a>";
				}
			}
		}
	}
	
	public function __toString() {
		return $this->html;
	}
}

?>
