<?php
require_once('obj/User.php');
class UserBox extends User {
  private $user;
  private $str;

  public function __construct($uid) {
    $this->user = new User($uid); 
    $this->str = "";
  }

  public function getBox() {
    $this->str = '<ul class="thumbnails">
      <li>
        <div class="thumbnail">
          <img src=' . $this->user->getProfilePic() .'>
          <div class="caption">
            <div class="btn-group">
              <a class="btn btn-info" href="index.php?page=userProfile.php&uid=' . $this->user->getUID() .'">' . $this->user->getUsername() . '</a>
              <button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
              <li><a href="index.php?page=userProfile.php&uid=' . $this->user->getUID() .'">Profile</a></li>
              </ul>
            </div>
            <p>Posts: '. $this->user->getPostCount() . '</p>
          </div>
        </div>
      </li>
    </ul>';
    return $this->str;
  }
}

?>
