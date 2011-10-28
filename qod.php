<?php
require_once('obj/db.php');
$db = DB::getInstance();
$topic = $db->getLastTopic(3);
$tid = $topic['data']['tid'];
header('Location: index.php?page=viewTopic&tid=' . $tid );
?>
