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

  function getBo($round, $ro = FALSE) {
    $this->bo = $this->db->getBoFromTournament($this->tourn_id, $ro);
    return $this->bo[($round -1)]['bo'];
  }

  /* why am I calling getMap here? Fuck, it's all for the adminTournament. This is bad, I know.
   * Why does the map class instantiate by name?
   */
  function getMap($round, $game = 1) {
    if (!$this->bo) {
      $this->getBo($round);
    }
    // We choose a magic map
    if (!isset($this->bo[($round -1)]['map'])) $this->bo[($round -1)]['map'] = 1;
    return $this->getDB()->getMap($this->bo[($round -1)]['map']); 
  }

}

?>
