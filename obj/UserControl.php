<?php
require_once('obj/page.php');
class UserControl extends Page {

	function showForm() {
		$str = "<span class='big'>Log Out</span>
			<form action='?page=userControl&method=logout' method='POST'>
			<input type='submit' value='Log Out'>
			</form>";
		return $str;
	}

	function logOut() {
		require_once('obj/sess.php');
		$sess = new SessObj();
		$sess->logoutUser();
		header("Refresh: 3; url=index.php");
		return "<span class='big'>See you next time, bro!</span>";
	}
}
