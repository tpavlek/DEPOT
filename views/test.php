<?php
require_once('obj/db.php');
require_once('obj/tournaments/Match.php');
require_once('obj/tournaments/Bracket.php');
require_once('fragments/Match.php');
$db =  DB::getInstance(); ?>
<?php
/*$url = "http://us.battle.net/sc2/en/profile/2275201/1/FGTlllllllll/ladder/leagues";
$bnet = file_get_contents($url);
$start = strpos($bnet, "<title>") + 7;
$len = strpos($bnet, "</title>") - $start;

  $val = substr($bnet, $start, $len);
switch(true) {
case (strstr($val, "Bronze") !== FALSE): print "BRONZE"; break;
case (strpos($val, "Platinum")): print "PLAT"; break;
default: print htmlspecialchars($val); ; break;*/

print_r($db->getMatchSet(407));

?>
<?php
?>
