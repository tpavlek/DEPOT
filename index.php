<?php
require_once("obj/page.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, 
height=device-height, user-scalable=no" />
<script type="text/javascript" src="includes/jquery.js"></script>
<script type="text/javascript" src="funcs/jsfuncs.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<title>DEPOT WAREHOUSE!</title>
</head>
<body>
<?php
if (!isset($_SESSION['username'])) {
    echo "<div class='userinfo'><a href=\"?page=login\">Login</a> / <a href='?page=register'>Register</a></div>";
    } else {
    echo "<div class='userinfo'>Welcome, <span class='username'><a href='?page=userControl'>" . $_SESSION['username'] . "!</a></span></div>";
}
?>

<a href="http://depotwarehouse.net"><span class="banner">DEPOT WAREHOUSE!</span></a>
<br /> <br />

<div id="navbar">
  <div class="nav" id="qod"><a href="qod.php"><span>QoD</span></a>
    <div class="sub_nav" id="leaderboard"><a href="?page=userList"><span>Leaderboard</span></a></div>
  </div>
  <a href="?page=forum"><div class="nav" id="forum"><span>Forum</span></a></div>
  <a href="http://blag.depotwarehouse.net"><div class="nav" id="blag"><span>Blag</span></a></div>
  <a href="?page=about"><div class="nav" id="about"><span>About</span></a></div>
</div>
<div class="cleaner"></div>
<script type="text/javascript">
$(function() {
    $("div.nav").hover(function() {
             $(this).find("div.sub_nav").slideDown(400);
                 }, function() {
                          $(this).find("div.sub_nav").slideUp(400);
                              });
                            });
</script>

<?php
if (isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') {
	require_once('obj/AdminPage.php');
	$page = new AdminPage();
} else {
	$page = new Page();
}
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'login':
			if (isset($_GET['method'])) {
				$page->login($_GET['method']);
			} else {
			$page->login("showForm");
			} break;
		case 'register': 
			if (isset($_GET['method'])) {
				switch ($_GET['method']) {
					case 'register': $page->register("register"); break;
					case 'finishRegistration': $page->register("finishRegistration"); break;
				}
			} else $page->register("showForm"); break;
		case 'userControl': 
			if (isset($_GET['method'])) {
				$page->userControl($_GET['method']);
			} else {
				$page->userControl("showForm"); 
			} 
		break;
		
		case 'post': 
			if (isset($_GET['method'])) {
				switch ($_GET['method']) {
					case 'newReply': $page->post("newReply"); break;
				}
			}
			$page->post("showForm"); break;
	
		case 'viewTopic': $page->viewTopic($_GET['tid']); break;

		case 'viewForum': $page->viewForum($_GET['fid']); break;
		
		case 'forum': $page->forum(); break;
		
		case 'userList': 
			if (isset($_GET['orderBy'])) $page->userList($_GET['orderBy']);
			else $page->userList("points"); 
		break;

		case 'newTopic': 
			if (isset($_GET['method'])) {
				$page->newTopic($_GET['method']);
			} else $page->newTopic("showForm"); 
		break;
			
		case 'userProfile': if (!isset($_GET['uid'])) $page->redirect("userList");
			else
				$page->userProfile($_GET['uid']);
			break;
			
		default: $page->fourohfour();
	}
} else {
$page->blog();
}
echo "<br>";
echo (string)$page;
?>
</body>
</html>
