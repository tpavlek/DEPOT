<?php
$resultsPerPage = 35;
if(!isset($_COOKIE['user']))
	header('Location: ?page=login');
if (isset($_GET['topicpage']))
	$topicpage = ($_GET['topicpage']-1) * 35;
else $topicpage= 0;

require_once("common.php");
$queryTopics = "SELECT tid, subject, last_poster, author_uid from topics ";
if (isset($_COOKIE['block']) && ($_COOKIE['rank'] == 'user')) { 
$queryTopics = $queryTopics . "where block = " . $_COOKIE['block'];
}
$queryTopics = $queryTopics . " ORDER BY last_reply DESC";
$topics = $pdo->query($queryTopics);
$numresults = $topics->rowCount();
 $numpages = $numresults / $resultsPerPage;
 if ($numresults % $resultsPerPage != 0) {
    $numpages += 1;
    $numpages = (int) $numpages;
    }
$displayTopics = $queryTopics ." LIMIT ". $topicpage.", 35";
$displayTopicsReady= $pdo->prepare($displayTopics);
$displayTopicsReady->execute();
$pageOfStuff = $displayTopicsReady->fetchAll();
 
echo "<ul>";
foreach ($pageOfStuff as $row) {
echo "<a href=\"?page=viewtopic&tid=" . $row['tid'] . "\"><li class=\"subject\">" . $row['subject'] . "</li></a>";
echo "<li class=\"author\"><a href='?page=userprofile&uid=" . $row['author_uid'] ."'>" . $row['last_poster'] . "</a></li>";
echo "<hr>";
}
echo "</ul>";
for ($i =1; $i <= $numpages; $i++) {
	echo "<a href=\"http://depotwarehouse.net/?page=forum&topicpage=".$i."\">".$i."</a>";
	if ($numpages > 1 ) echo " | "; 
}
?>
<ul>
<a href="?page=newTopic"><li class="big">NEW TOPIC</li></a>
</ul>
