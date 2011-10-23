<?php
include("verify.php");
include("common.php");
$setcookieUser = false;
$setcookieRank = false;
$setcookieBlock = false;
if (isset($_GET['login']) && $_GET['login'] == 1) {
    $query = $pdo->prepare("SELECT username, rank, block from user where email = :email");
    $query->execute(array(':email' => $_GET['email']));

if ($query->rowCount() == 0) {
        $welcome = "Account not found in database.";
    }
	else {
	$data=$query->fetch();
    	$rank = $data['rank'];
        $block = $data['block'];
	$welcome = "Welcome back to the party.";
        $setcookieUser=true;
        $setcookieRank = true;
	$setcookieBlock = true;
        $expire=time()+60*60*24*30;
        $username = $data['username'];
	}
    }

elseif (isset($_GET['logout']) && $_GET['logout'] == 1 && isset($_COOKIE['user'])) {
    $welcome="You are now logged out";
    $expire = time() - 3600;
    $username = $_COOKIE['user'];
    $rank = "";
    $block = "";
	$username = "";
$setcookieUser=true;
    $setcookieRank=true;
    $setcookieBlock=true;
}

elseif (isset($_GET['register']) && $_GET['register'] == 1) {
	$username = $_POST['username'];
	$email= $_POST['email'];
    	$block = $_POST['block'];
	if (!verifyString($username)) $welcome = "Invalid username.";

    else {
        $expire = time() +60*60*24*30;
        $query = $pdo->prepare("INSERT into user (username, email, block, join_date) VALUES (:username, :email, :block, :join)");
        $query->execute(array(':username' => $username, ':email' => $email, ':block' => $block, ':join' => $date));
        $welcome = "You are now a member!";
        $setcookieUser=true;
        $setcookieRank=true;
	$setcookieBlock = true;
        $rank="user";
         }
}
if ($setcookieUser == true)
    setcookie("user", $username, $expire);
if($setcookieRank == true)
    setcookie("rank",$rank,$expire);
if($setcookieBlock == true)
	setcookie("block",$block,$expire);
?>

<?php echo "<br><span class=\"big\">" . $welcome . "</span>"; ?>

