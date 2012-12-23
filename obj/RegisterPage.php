<?php
require_once('obj/LoginPage.php');
class RegisterPage extends LoginPage {

	function showForm() {
		$str = "<div class='formbox'>
			<form action='?page=register&method=register' method='post'>
        <p><label for='username'>Username:</label>
        <input type='text' name='username'></p>
				<input type='submit' value='Register with Google Account'>
			</form>
			</div>";
		return $str;
	}

	function startRegistration() {
		$args = array('method'=>'registerUser','username'=>$_POST['username']);
		$_SESSION['registration'] = $args;
		parent::auth($args);
	}
		
	function finishRegistration() {
		parent::auth($_SESSION['registration']);
	}	
}

?>
