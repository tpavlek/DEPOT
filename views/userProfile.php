<?php
require_once('obj/User.php');
//TODO handle no uid
$user = new User($_GET['uid']);
?>
<div class="row-fluid">
  <div class="span4">
    <ul class="thumbnails">
      <li>
        <img class="thumbnail" src="<?php echo $user->getProfilePic();?>" />
        <p>Postcount: <?php echo $user->getPostCount(); ?></p>
      </li>
    </ul>
  </div>
  <div class="span8">
    <div class="well">
      Latest posts and other info will go here
    </div>
  </div>
</div>
