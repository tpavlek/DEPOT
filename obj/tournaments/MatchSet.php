<?php
require_once('obj/tournaments/Match.php');

class MatchSet {

  private $matchset;
  private $player1;
  private $player2;
  private $player_1_score;
  private $player_2_score;
  private $bo;
  private $game_num;
  private $winner;
  
  public function __construct($midarray) {
    foreach ($midarray as $match) {
      $this->matchset[] = new Match($match);
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
        if ($match->getWinner() == $match->getPlayer1()) {
          $this->player_1_score++;
          if ($this->player_1_score > ($this->bo / 2)) {
            $this->winner = $match->getPlayer1();
          }
        } else {
          $this->player_2_score++;
          if ($this->player_2_score > ($this->bo / 2)) {
            $this->winner = $match->getPlayer2();
          }
        }
      }
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

  function getCurrentMatch() {
    if ($this->game_num == ($this->bo +1)) return $this->matchset[$this->bo -1]->getMID();
    return($this->matchset[$this->game_num -1]->getMID());
  }
}

?>
