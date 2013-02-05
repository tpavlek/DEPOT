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
    $url = $_POST['bnet_url'] . "ladder/leagues";
    $str = file_get_contents($url);
    if (!$str) return array('status' => 1, 'message' => "Can't connect to Blizzard");
    $start = strpos($str, "<title>") + 7;
    $league = substr($str, $start, strpos($str, "</title>") - $start);
    switch (true) {
    case (strpos($league, "Bronze") !== FALSE): $rank = "Bronze"; break;
    case (strpos($league, "Silver") !== FALSE): $rank = "Silver"; break;
    case (strpos($league, "Gold") !== FALSE): $rank = "Gold"; break;
    case (strpos($league, "Platinum") !== FALSE): $rank = "Platinum"; break;
    case (strpos($league, "Diamond") !== FALSE): $rank = "Diamond"; break;
    case (strpos($league, "Master") !== FALSE): $rank = "Masters"; break;
    case (strpos($league, "Grandmaster") !== FALSE): $rank = "Grandmaster"; break;
    default: $rank = FALSE; break;
    }
    if (!$rank) {
      return array('status' => 1, 'message' => 'Could not determine league');
    }
    $result = $db->updateBnetLeague($_SESSION['uid'], $rank);
    if ($result['status']) return $result;
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
