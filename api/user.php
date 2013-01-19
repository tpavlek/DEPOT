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

  static function setBnet() {
    $page = new Page();
    $db = $page->getDB();
    if (!($page->permissions(array("loggedIn")) || $page->permissions(array("admin")))) {
      return array('status' => 1, 'message' => "Insufficient Permissions");
    }

    $result = $db->updateBnetId($_SESSION['uid'], $_POST['bnet_id']);
    if ($result['status']) return $result;
    $result = $db->updateBnetName($_SESSION['uid'], $_POST['bnet_name']);
    if ($result['status']) return $result;
    $result = $db->updateBnetCharCode($_SESSION['uid'], $_POST['char_code']);
    if ($result['status']) return $result;
    return array('status' => 0);
  }
}  
	

?>
