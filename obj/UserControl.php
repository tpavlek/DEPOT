<?php
require_once('obj/page.php');
class UserControl extends Page {

	function showForm() {
		$str = "<span class='big'>User Actions</span>
			<form action='?page=userControl&method=logout' method='POST'>
			<input type='submit' value='Log Out'>
			</form>
			<hr>
			<span class='big'>Profile</span>
			<form action='?page=userControl&method=changePic' method='POST'>
			<input type='submit' value='Change Profile Picture'>
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
	
	function changePic() {
		require_once('funcs/paramFetch.php');
		if (isset($_FILES['profile_img_upload'])) {
			if ($_FILES['profile_img_upload']['tmp_name'] == 'error')
				return "<span class='big'>There was an error with the upload (wrong size?)</span>";
			$size = getimagesize($_FILES['profile_img_upload']['tmp_name']);

			if ($size[0] > 100 || $size[1] > 100) {
				return "<span class='big'>Image dimensions too large</span>";
			}
			if (move_uploaded_file($_FILES['profile_img_upload']['tmp_name'], "assets/profile/uid_" . $_SESSION['uid'])) {
				$this->db->updateProfilePic($_SESSION['uid'],"assets/profile/uid_" . $_SESSION['uid']);
				header('Refresh: 3, url=?page=userControl');
				return "<span class='big'>There was great success!</span>";
			} else
				return "<span class='big'>Some hardcore failure just went on</span>";
			
		} else {
			$str = "<span class='big'>Change Profile Pic</span><br> <ul>";
			$str .= "<p>Here you can change your profile picture. Some rules are: <ol><li>must be 100x100 or less</li><li>Must be less than 100KB</li></ol></p>";
			$str .= "<form enctype='multipart/form-data' action='?page=userControl&method=changePic' method='POST'>";
			$str .= "<input type='hidden' name='MAX_FILE_SIZE' value='102400'>";
			$str .= "<input name='profile_img_upload' type='file' /><br><br>";
			$str .= "<input type='submit' value='Upload and go!'>";
			return $str;
		}

	}
}
