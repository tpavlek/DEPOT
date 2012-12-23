<?php
require_once('/home/ebon/DEPOT/obj/page.php');
require_once('/home/ebon/DEPOT/obj/db.php');
require_once('/home/ebon/DEPOT/obj/sess.php');
class APISess {

	static function addToSession() { //TODO SEPARATE FROM COLOUR & security
		$db = DB::getInstance();
		foreach ($_POST as $k => $v) {
			$_SESSION[$k] = $v;
		}
		return $db->update(array('table' => 'user', 'fields' => array(':colour_time' => $_POST['colour_time']), 'where' => array(':id' => $_SESSION['uid'])));
  }

  static function loginUser() {
    $sess = new SessObj();
    return $sess->loginUser($_POST['email']);
  }
  static function logout() {
    session_unset();
    session_destroy();
  }
}

?>
