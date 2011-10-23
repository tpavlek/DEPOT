<ul>
<?php
	require_once('funcs/topics.php');
	echo "<form action=\"?page=postaction&newreply=1&tid=". $_GET['tid'] . "\" method=\"POST\">";
?>
<p><li><label for="subject"> Subject: 
<input class="reply" type="text" name="subject" value="RE: <?php echo getTopicSubject($_GET['tid']); ?>"></li></p>
<p><li><label for="message"> Message:
<textarea class="reply" name="message"></textarea></li></p>
<input type="submit" value="GO GO!">
</form>
</body>
</html>
