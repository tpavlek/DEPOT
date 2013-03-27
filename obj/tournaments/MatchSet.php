<?php
require_once('obj/tournaments/Match.php');
require_once('obj/page.php');

class MatchSet extends Page{

  private $matchset;
  private $player1;
  private $player2;
  private $player_1_score;
  private $player_2_score;
  private $bo;
  private $game_num;
  private $winner;
  
  public function __construct($mid, $array = TRUE) {
    if($array) {
      foreach ($mid as $match) {
        $this->matchset[] = new Match($match);
      }
    } else {
      parent::__construct();
      $this->matchset = $this->getDB()->getMatchSet($mid);
    }

    $this->player1 = $this->matchset[0]->getPlayer1();
    $this->player2 = $this->matchset[0]->getPlayer2();
    $this->player_1_score = 0;
    $this->player_2_score = 0;
    $this->winner = 0;
    $this->game_num = 1;
    $this->bo = sizeof($this->matchset);
    foreach ($this->matchset as $match) {
      if ($match->hasWinner()) {
        $this->game_num++;
        if ($match->getWinner() == $this->player1) {
          $this->player_1_score++;
        } else {
          $this->player_2_score++;  
        }
      }
    }
    if ($this->player_1_score >= ceil($this->bo / 2)) {
      $this->winner = $match->getPlayer1();
    }
    if ($this->player_2_score >= ceil($this->bo / 2)) {
      $this->winner = $match->getPlayer2();
    }
  }

  function getPlayer1Score() {
    return $this->player_1_score; 
  }

  function getPlayer1() {
    return $this->player1;
  }

  function getPlayer2() {
    return $this->player2;
  }

  function getPlayer2Score() {
    return $this->player_2_score;
  }

  function hasWinner() {
    return $this->winner;
  }

  function getGameNum() {
    return $this->game_num;
  }

  function getReplay($num = FALSE) {
    if ($num) {
    }
    $replayArray = array();
    foreach ($this->matchset as $match) {
      $replayArray[] = $match->getReplay();
    }
    return $replayArray;
  }
  
  /* if OBJ is true, it will return the current match object,
   * else, default, it will return the current match ID
   */
  function getCurrentMatch($obj = FALSE) {
    if ($this->game_num == ($this->bo +1)) {
      $return = $this->matchset[$this->bo -1];
    } else if ($this->hasWinner()) { 
      $return = $this->matchset[$this->game_num -2];
    } else {
      $return = $this->matchset[$this->game_num -1];
    }

    if ($obj) return $return;
    else return $return->getMID();
  }
}

?>
