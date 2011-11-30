<?php
require_once('obj/control/Control.php');
class PostDeleteButton extends Control {
	private $path;
	private $html;
	
	public function __construct() {
		if ($parent::permissions(array('author', 'admin'))) {
			$this->path = "assets/icons/del_sm.png";
			$this->html = "<div class='buttonrow'>";
			$this->html .= "<img src='" . $path . "' />"
		}
	}
}

?>
