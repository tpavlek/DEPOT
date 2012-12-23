<?php
require_once('/home/ebon/DEPOT/obj/page.php');
require_once('/home/ebon/DEPOT/obj/sess.php');

class APIRegister {

  static function registerUser() {
    $sess = new SessObj();
    return($sess->registerUser($_POST['email'], $_POST['username']));
  }

  static function usernameTaken() {
    
  }

}
?>
