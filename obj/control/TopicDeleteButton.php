<?php
require_once('obj/control/Control.php');
class TopicDeleteButton extends Control {
	private $path;
	private $html;
	
	public function __construct($tid) {
		parent::__construct();
		$this->path = "assets/icons/del_sm.png";
		$this->html = "<div class='buttonrow'>";
		if (parent::permissions(array('permissions' => array('admin')))) {
			$this->html .= "<img onClick='javascript:adminDeleteTopic(event)' height=10 src='" . $this->path . "' />";
		}
		else if (isset ($_SESSION['uid'])) {
			if (parent::permissions(array('permissions' => array('topicauthor'), 'args' => array('tid' => $tid, 'uid' => $_SESSION['uid'])))) {
				$this->html .= "<img onClick='javascript:userDeleteTopic(event)' height=10 src='" . $this->path . "'>";
			}
		}
		$this->html .= "</div>";
	}
	
	public function __toString() {
		return $this->html;
	}
}

?>
