<?php
require_once('obj/control/Control.php');
class PostDeleteButton extends Control {
	private $path;
	private $html;
	
	public function __construct($pid) {
		parent::__construct();
		$this->path = "assets/icons/del_sm.png";
		$this->html = "<div class='buttonrow'>";
		if (parent::permissions(array('permissions' => array('admin')))) {
			$this->html .= "<img onClick='javascript:adminDeletePost(event)' height=10 src='" . $this->path . "' />";
		}
		else if (isset ($_SESSION['uid'])) {
			if (parent::permissions(array('permissions' => array('postauthor'), 'args' => array('pid' => $pid, 'uid' => $_SESSION['uid'])))) {
				$this->html .= "<img onClick='javascript:userDeletePost(event)' height=10 src='" . $this->path . "'>";
			}
		}
		$this->html .= "</div>";
	}
	
	public function __toString() {
		return $this->html;
	}
}

?>
