<?php
require_once('obj/db.php');
require_once('obj/page.php');
class APIUser {

  static function changePic() {
    $db = DB::getInstance();
    $page = new Page();
			$size = getimagesize($_FILES['profile_pic_upload']['tmp_name']);
			if ($size[0] > 100 || $size[1] > 100) {
				return array ('status' => 1, 'message' => 'image too largely'); 
			}
			if (move_uploaded_file($_FILES['profile_pic_upload']['tmp_name'], "assets/profile/uid_" . $_SESSION['uid'])) {
				$db->updateProfilePic($_SESSION['uid'],"assets/profile/uid_" . $_SESSION['uid']);
		    return array('status' => 0, 'message' => 'Profile Image changed successfully');
      }
  }
}  
	

?>
