<?php
require_once('battleNetParser.php');

$bnet = new BattleNetParser('http://us.battle.net/sc2/en/profile/2275201/1/FGTlllllllll/');
print $bnet->get_user();
print $bnet->get_race();
print_r ($bnet->get_1v1_league());
?>
