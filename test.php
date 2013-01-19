<?php
require_once('sc2replay/mpqfile.php');

$mpqfile = new MPQFile("assets/replays/meowth.SC2Replay");
$replay = $mpqfile->parseReplay();
print_r($replay);

?>
