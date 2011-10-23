<?php
include("common.php");
include("verify.php");
if (isset($_GET['newtopic'])) {
	if (!isset($_COOKIE['user'])) {
  	$welcome = "Please to log in.";
		$returnurl = "?page=login";
	} else {
  	if (!verifyString($_POST['subject'], 3, 70)) $welcome = "Invalid subject. Doin' it rong, you are.";
        else {
	$uid = $pdo->query("SELECT uid from user where username = " . $_COOKIE['user']);
        $query = "INSERT INTO topics (subject, message, author, author_uid, last_poster, date, last_reply, block) VALUES (:subject, :message, :author, :author_uid, :last_poster, :date, :last_reply, :block)";
        $queryReady = $pdo->prepare($query);
        $queryReady->execute(array(':subject' => $_POST['subject'], ':message' => 
nl2br($_POST['message']), ':author' => $_COOKIE['user'], ':author_uid' => $uid['uid'], ':last_poster' => $_COOKIE['user'], ':date' => $date, ':last_reply' => $date, ':block' => $_COOKIE['block']));
        changePostCount($_COOKIE['user']);
        $welcome = "Kay cool.";
        $returnurl = "?page=forum";
            }
        }
    }
if (isset($_GET['newreply'])) {
    if (!isset($_COOKIE['user'])) { 
        $welcome = "Hey fag, log in plox.";
	$returnurl = "?page=login";
	}
    else {
        $query = "INSERT INTO posts (subject, message, author, in_reply_to, date) VALUES (:subject, :message, :author, :in_reply_to, :date)";
        $queryReady = $pdo->prepare($query);
        $queryReady->execute(array(':subject' => $_POST['subject'], ':message' => nl2br($_POST['message']), ':author' => $_COOKIE['user'], ':in_reply_to' => $_GET['tid'], ':date' => $date));
        $changeDate = "UPDATE topics set last_reply = :date where tid = :tid";
        $changeDateReady = $pdo->prepare($changeDate);
        $changeDateReady->execute(array(':date' => $date, ':tid' => $_GET['tid']));
        changePostCount($_COOKIE['user']);
        $welcome = "Haha, awesome";
        $returnurl = "?page=viewtopic&tid=" .$_GET['tid'];
        $additional = "Sending you back to the topic from whence you came.";
        }
    }
if (isset($_GET['delete'])) {
	if (!isset($_COOKIE['user']))
  	$welcome = "Don't try to pull that. Log in.";
  elseif (!isAuthor($_COOKIE['user'], $_GET['tid']))
  	$welcome = "Don't BS me. You didn't write that topic.";
  elseif (!isset($_GET['confirm'])) {
  	$welcome = "Are you sure you wish to delete this topic?";
    $additional = 
"<form action='?page=postaction&delete=1&tid=".$_GET['tid']."&confirm=1' method='GET'>
<input type='submit' value='Confirm'>
</form>";  
    }
    else {
        if (deleteTopic($_GET['tid'])) {
            $welcome = "Hooray! Topic went bye bye!";
            $returnurl = "http://depotwarehouse.net/forum.php";
            }
        else
            $welcome = "Failure to delete topic";
        }
    }
    
if (isset($_GET['edit'])) {
  if (!isset($_COOKIE['user'])) {
        $welcome = "Don't try to pull that. Log in.";
   } elseif (!isAuthor($_COOKIE['user'], $_GET['pid']) && !isMod($_COOKIE['user'])) {
        $welcome = "Don't BS me. You didn't write that post.";
   } else {
        header("Location:http://depotwarehouse.net/");
	}
}

echo "<span class=\"big\">" . $welcome . "</span><br><br>";

if(isset($additional))
    echo "<i>" . $additional ."</i>";
?>

