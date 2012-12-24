<?php
require_once('/home/ebon/DEPOT/obj/control/Control.php');
class TopicDeleteButton extends Control {
	private $path;
	private $html;
	
	public function __construct($topic) {
		parent::__construct();
		$this->html = "";
		$this->path = "assets/icons/del_sm.png";
		if (parent::permissions(array('permissions' => array('admin')))) {
			$this->html .= "<img onClick='javascript:adminDeleteTopic(event)' height=10 src='" . $this->path . "' />";
		}
		else if (isset ($_SESSION['uid'])) {
			if (!$topic->isDeleted()) {
			if (parent::permissions(array('permissions' => array('topicauthor'), 'args' => array('tid' => $topic->getTID(), 'uid' => $_SESSION['uid'])))) {
				$this->html .= "<img onClick='javascript:userDeleteTopic(event)' height=10 src='" . $this->path . "'>";
			}
		}
		}
	}
	
	public function __toString() {
		return $this->html;
	}
}

?>
