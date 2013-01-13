<?php
class UserControl {

	static function changePic() {
		// require_once('funcs/paramFetch.php');
		if (isset($_FILES['profile_img_upload'])) {
			if ($_FILES['profile_img_upload']['tmp_name'] == 'error')
				return array('status' => 1, 'message' => 'There was some sort of error. I dunno');
			$size = getimagesize($_FILES['profile_img_upload']['tmp_name']);
			if ($size[0] > 100 || $size[1] > 100) {
				return array('status' => 1, 'message' => 'Image dimensions too large');
			}
			if (move_uploaded_file($_FILES['profile_img_upload']['tmp_name'], "assets/profile/uid_" . $_SESSION['uid'])) {
				//$this->db->updateProfilePic($_SESSION['uid'],"assets/profile/uid_" . $_SESSION['uid']);
				return array('status' => 0);
			} else
				return array('status' => 1, 'message' => 'Some hardcore failure just went on');
		}
	}
}
