<?php
require_once(__DIR__.'/obj/page.php');
$page = new Page();
if (isset($_GET['page'])) {
  switch ($_GET['page']) {
  case 'adminTournament.php': $currentPage= 'adminTournament.php'; break;
  case 'editProfile.php': $currentPage= 'editProfile.php'; break;
  case 'forum.php': $currentPage = 'forum.php'; break;
  case 'home.php': $currentPage = 'home.php'; break;
  case 'members.php': $currentPage = 'members.php'; break;
  case 'replayAnalyzer.php': $currentPage = 'replayAnalyzer.php'; break;
  case 'stream.php': $currentPage = 'stream.php'; break;
  case 'editStream.php': $currentPage = 'editStream.php'; break;
  case 'userProfile.php': $currentPage = 'userProfile.php'; break;
  case 'viewForum.php': $currentPage = 'viewForum.php'; break;
  case 'viewTopic.php': $currentPage = 'viewTopic.php'; break;
  case 'viewTournament.php': $currentPage = 'viewTournament.php'; break;
  default: $currentPage = 'home.php'; break;
  }
} else {
  $currentPage = 'home.php';
}

?>

<!DOCTYPE html>
<html lang= "en">
  <head>
    <meta   charset=  "utf-8"  >
    <title>  [FGT] Starcraft 2 Team  </title>  
    <meta   name=  "viewport"   content=  "width=device-width, initial-scale=1.0"  >
    <meta   name=  "description"   content=  ""  >
    <meta   name=  "author"   content=  ""  >
    <!-- Le styles -->  
    <link   href=  "bootstrap/css/bootstrap.css"   rel=  "stylesheet">
    <style>  
      body   {
        padding-top  : 60px  ; /* 60px to make the container go all the way to the bottom of the topbar */  
      }
    </style>  
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/feed.css" rel="stylesheet">
    <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
    <script type="text/javascript" src = "bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="funcs.js"></script>
    <script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo/yahoo-min.js&2.6.0/build/event/event-min.js&2.6.0/build/connection/connection-min.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->  
    <!--[if lt IE 9]>  
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>  
    <![endif]-->  

    <!-- Le fav and touch icons -->  
    <link   rel=  "shortcut icon"   href=  "images/favicon.ico"  >
    <link   rel=  "apple-touch-icon"   href=  "images/apple-touch-icon.png"  >
    <link   rel=  "apple-touch-icon"   sizes=  "72x72"   href=  "images/apple-touch-icon-72x72.png"  >
    <link   rel=  "apple-touch-icon"   sizes=  "114x114"   href=  "images/apple-touch-icon-114x114.png"  >
  </head>  
  <body>
<div class="container">
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <a class="brand" href="index.php">Team [FGT]</a>
      <ul class="nav">
        <li><a href="index.php">Home</a></li> <!-- TODO make actives work -->
        <li><a href="index.php?page=members.php">Members</a></li>
        <li><a href="index.php?page=forum.php">Forum</a></li>
        <li><a href="index.php?page=viewTournament.php&tourn_id=7">[FGT] Open</a></li>
      </ul>
      <ul class="pull-right" style="padding-right:1em"> 
        <?php if(!isset($_SESSION['username'])) { ?>
        <div class="btn-group">
          <a href="javascript:openGoogleWindow();" class="btn btn-success">Login</a>
          <a href="#registerPopup" role="button" data-toggle="modal" class="btn btn-primary">Register</a>
        </div> 
        <?php } else { ?>
        <div class="btn-group">
          <a class="btn btn-primary" href="index.php?page=userProfile.php&uid=<?php echo $_SESSION['uid']?>">
            <?php echo $_SESSION['username'];  ?>
          </a>
          <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="?page=editProfile.php">Edit Profile</a></li>
            <li><a href="javascript:logout();">Logout</a></li>
          </ul>
        </div> <?php } ?>
      </ul>
    </div>
  </div>

  <!-- Register popup -->
  <div id="registerPopup" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="registerPopupLabel">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3 id="registerPopupLabel">Register With Google Account</h3>
    </div>
    <div class="modal-body">
      <form class="form-horizontal" action="javascript:openGoogleWindow();">
        <div class="control-group" id="inputUsernameControlGroup">
          <label class="control-label" for="inputUsername">Username</label>
          <div class="controls">
            <input type="text" id="inputUsername" placeholder="Username">
            <span class="help-inline" id="usernameError" style="display:none">Error signing up</span>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <button type="submit" id="registerButton" class="btn btn-danger" href="javascript:openGoogleWindow();">Register with Google Account</button>
          </div>
        </div>
      </form>

    </div>
    <div class="modal-footer">
    </div>
  </div>
  <!-- Done with popup -->


  <div   class=  "container"  >
    <iframe name="submit-iframe" id="submit-iframe-dood"></iframe>
    <?php include "views/" . $currentPage; ?>
  </div>
</div>   <!-- /container -->  
</body>
</html>
