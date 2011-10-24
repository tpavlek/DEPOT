<?php
/*if(!isset($_COOKIE['block'])) {
	header('Location: index.php?page=login');
}
require 'common.php';
$query = "SELECT tid from topics where block = ";
$query = $query . $_COOKIE['block'] . " AND author = 'QoD Block " . $_COOKIE['block'] . "'";
$query = $query . " ORDER by date DESC";
echo $query;
$topics = $pdo->query($query);
$arr = $topics->fetch();*/
header('Location: index.php?page=viewTopic&tid=2496');
?>
