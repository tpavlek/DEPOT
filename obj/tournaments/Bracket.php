<?php

class Bracket extends Page{

  private $tourn_id;
  private $bracket;
  private $bo;

  public function __construct($tourn_id) {
    parent::__construct();
    $this->tourn_id = $tourn_id;
  }

  function getBracket($round) {
    $this->bracket = $this->db->getBracket($this->tourn_id, $round);
    return $this->bracket;
  }

  function getBo($round) {
    return $this->db->getBoFromTournament($this->tourn_id)[($round -1)]['bo'];
  }

  function getMap($round, $game = 1) {
    return $this->db->getMapByRoundGame($this->tourn_id, $round, $game);
  }

}

?>
