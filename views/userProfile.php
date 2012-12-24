<?php
require_once('obj/User.php');
require_once('fragments/userBox.php');
//TODO handle no uid
$user = new User($_GET['uid']);
?>
<div class="row-fluid">
  <div class="span4">
    <div class="userBox pull-left">
      <?php $userBox = new UserBox($user->getUID());
        print $userBox->getBox();
       ?>
    </div>
  </div>
  <div class="span8">
    <div class="well">
      Latest posts and other info will go here
    </div>
  </div>
</div>
