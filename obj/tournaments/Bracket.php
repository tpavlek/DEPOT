<?php

class Bracket extends Page{

  private $tourn_id;
  private $bracket;

  public function __construct($tourn_id) {
    parent::__construct();
    $this->tourn_id = $tourn_id;
  }

  function getBracket($round) {
    $this->bracket = $this->db->getBracket($this->tourn_id, $round);
    return $this->bracket;
  }


}

?>
