<?php
include("common.php");
  if (!isset($_COOKIE['user']))
        $welcome = "Don't try to pull that. Log in.";
    elseif (!isAuthor($_COOKIE['user'], $_GET['pid'],false) && !isMod($_COOKIE['user']))
        $welcome = "Don't BS me. You didn't write that post.";
    else
       echo "Full of win";
?>
<ul>
<?php
    echo "<form action=\"postaction.php?newreply=1&tid=". $_GET['tid'] . "\" method=\"POST\">";
?>
<p><li><label for="subject"> Subject: 
<input class="reply" type="text" name="subject"></li></p>
<p><li><label for="message"> Message:
<textarea class="reply" name="message"></textarea></li></p>
<input type="submit" value="GO GO!">
</form>
