<?php
if (!isset($_GET['rid'])) {
  echo "<script>window.location = index.php</script>";
}
require_once('obj/Replay.php');
require_once('obj/tournaments/Match.php');
require_once('fragments/replayBox.php');
require_once('obj/tournaments/MatchSet.php');

$rid = $_GET['rid'];
$replay = new Replay($rid);
$match_id = $replay->hasMatch();
if ($match_id) {
  $match = new Match($match_id);
?>
<div class='alert alert-info'>
  <button class="close" data-dismiss="alert">&times;</button>
  <h4>Hey guy, I heard you like Starcraft!</h4>
  <p>This match was played as part of a tournament!</p>
  <a class='btn btn-info' href='?page=viewTournament.php&tourn_id=<?php echo $match->getTournID();?>'>
    View Tournament
  </a>
</div>
<?php
  $match_id = $match->isMatchSet();
  if ($match_id) {
    $matchset = new MatchSet($match_id, FALSE);
?>
  <div class='alert alert-success'>
    <button class="close" data-dismiss="alert">&times;</button>
    <h4>The requested replay is part of a best-of series.</h4>
    <p> Here's the entire series for context. Your requested replay is denoted by "(requested)" below.</p>
  </div>
<?php

    $arr = $matchset->getReplay();
    $i = 1;
    foreach ($arr as $repid) {
      print "<h4>Game " . $i . (($_GET['rid'] == $repid) ? " (requested)" : "") . ":</h4>";
      $replay = new Replay($repid);
      printReplay($replay);
      print "<hr />";
      $i++;
    }
  } else {
    printReplay($replay);
  }
  
} else { 
  printReplay($replay);
}

function printReplay($replay) {
  $replayBox = new replayBox($replay->getPath());
  print $replayBox->getBox();
}
?>


