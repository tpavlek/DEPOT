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
<div class='userinfo'>
<div class="nav">
	<a href="?page=userList"><div><span class="mid">Users</span></div></a>
	<a href="?page=forum"><div><span>Forum</span></div></a>
	<a href="?page=tekaef"><div><span>TEKAEF</span></div></a>
</div>
<?php
if (!isset($_SESSION['username'])) {
    echo "<span class='user'><a href='?page=login'>Login</a> / <a href='?page=register'>Register</a></span>";
    } else { ?>
    <span class="user">Welcome, <span class='username'><a href='?page=userControl'><?php echo $_SESSION['username']; ?> !</a></span> <img src="assets/icons/down_arrow_sm.png" onclick="showDropDown(event)" ></div>
    <div id="userControlDropDown" style="display:none">
    	<span class="bold">Enable Colour Change:</span>
    	<input type="checkbox" id="disableColourChange" 
    	<?php 
    			if ($_SESSION['colour_time'] != 0) echo "checked='yes'";
    	?>
    	 onchange="hideColourVariation()">
    	<div id='colourChangePrefs' <?php if ($_SESSION['colour_time'] == 0) echo "style='display:none'" ?>>
    		<input type="range" min="0" max="30000" value=
    		<?php
    			echo $_SESSION['colour_time'];
    		?>
    		class="obnoxiousColours" onchange="showNewValue()"/><button id="obnoxiousColoursGoButton" onclick="updateObnoxiousColours()"> </button> </div>
    	<hr>
    	<span class="bold"><a href="?page=userControl">User Control</a></span>
    	<hr /> 
    	<span class="bold"><a href="?page=userControl&method=logOut">Log out</a></span>
    	</div>
    	</span>
<?php } ?>

</div>
<div id="main-content">
<br /> <br />

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
				$page->post($_GET['method']);
			} else {
				$page->post("showForm");
			} 
		break;
	
		case 'viewTopic': $page->viewTopic($_GET['tid']); break;

		case 'viewForum': $page->viewForum($_GET['fid']); break;
		
		case 'forum': $page->forum(); break;
		
		case 'tekaef': $page->tekaef(); break;

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
<br>
</div>
<script type="text/javascript">

	var intervalBob;
	
	$(window).load(function(){

		if($('#disableColourChange').is(':checked')) {
			intervalBob = setInterval(randpage, $('.obnoxiousColours').val());
			$('#obnoxiousColoursGoButton').text(($('.obnoxiousColours').val() / 1000) + "s");
		}
	});
	
	function rand_color() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++) {
			color += letters[Math.round(Math.random() * 15)];
		}
		return color;
	}
	
	function randpage() {
		$('body').css("background", rand_color());
	}
	
	function showNewValue() {
		$('#obnoxiousColoursGoButton').text(($('.obnoxiousColours').val() / 1000) + "s");
	}	
	
	function updateObnoxiousColours() {
		clearInterval(intervalBob);
		intervalBob = setInterval(randpage,$('.obnoxiousColours').val());
		$.ajax({
			url: "api.php?type=sess&method=addToSession",
			type: "POST",
			data: {'colour_time': $('.obnoxiousColours').val()},
			success: function(data) {
			}
		});
	}
	
	function hideColourVariation() {
		if($('#disableColourChange').is(':checked')) {
			var ms = $('.obnoxiousColours').val();
			if (ms == 0) ms = 3000;
			$('.obnoxiousColours').val(ms);
			$('#obnoxiousColoursGoButton').text(($('.obnoxiousColours').val() / 1000) + "s");
			$('#colourChangePrefs').show('fast');
			intervalBob = setInterval(randpage,ms);
			$.post("api.php?type=sess&method=addToSession", {'colour_time' : 3000});
		} else {
			$('#colourChangePrefs').hide('fast');
			$.post("api.php?type=sess&method=addToSession", {'colour_time':0});
			clearInterval(intervalBob);
		}
	}

</script>
</body>
</html>
