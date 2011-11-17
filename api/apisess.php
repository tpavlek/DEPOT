<?php
require_once('obj/page.php');
require_once('obj/db.php');
class APISess {

	static function addToSession() { //TODO SEPARATE FROM COLOUR & security
		$db = DB::getInstance();
		foreach ($_POST as $k => $v) {
			$_SESSION[$k] = $v;
		}
		return $db->update(array('table' => 'user', 'fields' => array(':colour_time' => $_POST['colour_time']), 'where' => array(':id' => $_SESSION['uid'])));
	}
}

?>
