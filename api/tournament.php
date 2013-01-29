<?php
require_once('obj/page.php');
require_once('obj/User.php');
class APITournament {

  static function register() { //TODO check for $_GETS
    $page = new Page();
    $db = $page->getDB();
    if (!$page->permissions(array("loggedIn"))) {
      return array('status' => 1, 'message' => 'You must be logged in');
    }
    $user = new User($_SESSION['uid']);
    if (!$user->hasBnet()) {
      return array('status' => 1, 'message' => 'You must fill in your BNET info in your profile');
    }
    if ($db->isInTournament($_SESSION['uid'], $_GET['tourn_id'])) {
      return array('status' => 1, 'message' => "You're already registred");
    }
    return $db->registerInTournament($_SESSION['uid'], $_GET['tourn_id']);
  }

  static function generateBracket() {
    $page = new Page();
    $db = $page->getDB();

    if (!$page->permissions(array('admin'))) {
      return array('status' => 1, 'message' => 'Insufficient Permissions');
    }
    if (!isset($_GET['tourn_id'])) {
      return array('status' => 1, 'message' => 'You did not supply a tourn_id');
    }
    return $db->generateBracket($_GET['tourn_id']);
  }

  static function uploadReplay() {
    $page = new Page();
    $db = $page->getDB();
    if (!($_FILES['tournament_replay_upload']['error'])) {
      $replayPath =  "assets/replays/".$_SESSION['uid'] . "_" .date('YmdHis') .".SC2Replay";
      move_uploaded_file($_FILES['tournament_replay_upload']['tmp_name'], $replayPath);
    }
    $match_id = $db->isInMatch($_SESSION['uid'], $_POST['tourn_id']);
    if (!$match_id) {
      return array('status' => 1, 'message' => "You don't appear to be in a match");
    }
    //$db->addMatchReplay($match_id, $replayPath);
    return(APITournament::processReplay($match_id, $replayPath));
  }

  static function processReplay($match_id, $replay) {
    require_once('fragments/replayBox.php');
    require_once('obj/tournaments/Match.php');
    require_once('obj/User.php');
    $page = new Page();
    $db = $page->getDB();
    $match = new Match($match_id);
    $box = new replayBox($replay);
    $player1 = new User($match->getPlayer1());
    $player2 = new User($match->getPlayer2());
    //TODO check that both players are in replay
    if ($box->getWinnerBnetID() == $player1->getBnetID()) {
      if ($box->getLoserBnetID() != $player2->getBnetID()) {
        return array('status' => 1, 'message' => "This replay doesn't contain both players");
      }
      $winner = $player1->getUID();
    } elseif ($box->getWinnerBnetID() == $player2->getBnetID()) {
      if ($box->getLoserBnetID() != $player1->getBnetID()) {
        return array('status' => 1, 'message' => "This replay doesn't contain both players");
      }
      $winner = $player2->getUID();
    } else {
      return array('status' => 1, 'message' => 'Could not determine winner from replay');
    }
    return $db->reportGameWin($match_id, $winner);
    
  }

  static function editMatch() {
    require_once('obj/tournaments/Match.php');
    $page = new Page();
    $db = $page->getDB();
    $source = $_POST['player_move_num'];
    $destination = $_POST['match_move_pos'];
    $tourn_id = $_POST['tourn_id'];
    $round = $_POST['round'];
    $position = $_POST['match_move_num'];
    $mid1 = $_POST['match_id'];
    $match = new Match($mid1);
    $mid2 = $db->getMidFromBracketByPosition($tourn_id, $position, $round)['match_id'];
    $match2 = new Match($mid2);
    $uid1 = ($source == 1) ? $match->getPlayer1() : $match->getPlayer2();
    $uid2 = ($destination == 1) ? $match2->getPlayer1() : $match2->getPlayer2();
    return $db->switchMatchPlayers($mid1, $uid1, $mid2, $uid2, $source, $destination);

  }

}

?>
