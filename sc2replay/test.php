<?php
require_once('mpqfile.php');
$file = new mpqfile('test.SC2Replay');
$replay = $file->parseReplay();
print_r($replay->getPlayers());

?>
