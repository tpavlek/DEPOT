<?php

class Button {

	private $action;
	private $html;
	
	public function __construct() {
		$this->html = "<button>"
	}
	
	public function __toString() {
		return $this->html;
	}	
	
}

?>
