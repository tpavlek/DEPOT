<?php
include("common.php");
if (!isset($_GET['tid']))
    echo "How the hell did you get here? You do not belong.";
else {
    $getTopicQuery = "SELECT subject, message, author from topics where tid = :tid";
    $getTopicExec = $pdo->prepare($getTopicQuery);
    $getTopicExec->execute(array(':tid' => $_GET['tid']));
    $getTopic = $getTopicExec->fetchAll();
    echo "<ul>";
    foreach ($getTopic as $row) {
        echo "<li class=\"subject\">" . $row['subject'] . "</li>";
        echo "<li class=\"author\">" . $row['author'] . "<br>Posts: " . getPostCount($row['author']);
        if (isset($_COOKIE['user']) && ($row['author'] == $_COOKIE['user'] || $_COOKIE['rank'] == 'admin'))
            echo "<hr><a href=\"?page=postaction&delete=1&tid=". $_GET['tid'] . "\">Delete topic </a></li><hr>";
        else
            echo "</li><hr>";
        echo "<li class=\"message\">" . $row['message'] . "</li>";
        }
    echo "</ul>";
    $getReplyQuery = "SELECT subject, message, author from posts where in_reply_to = :tid";
    $getReplyExec = $pdo->prepare($getReplyQuery);
    $getReplyExec->execute(array(':tid' => $_GET['tid']));
    $getReply = $getReplyExec->fetchAll();
    foreach ($getReply as $row) {
        echo "<ul>";
        echo "<li class=\"subject\">" . $row['subject'] . "</li>";
        echo "<li class=\"author\">" . $row['author'] . "</li><hr>";
        echo "<li>" . $row['message'] . "</li>";
        echo "</ul>";
        }
        }

?>
</ul>
<ul class="big">
<?php
echo "<a href=\"?page=newreply&tid=". $_GET['tid'] . "\">NEW REPLY</a>";
?>
</ul>
