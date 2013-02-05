<?php
require_once('obj/page.php');

class Tournament extends Page {

  private $tourn_id;
  private $name;
  private $registered;
  private $started;
  private $num_rounds;
  private $current_round;
  private $info;

  public function __construct($tourn_id) {
    parent::__construct();
    $data = $this->db->getTournament($tourn_id);
    $this->tourn_id = $tourn_id;
    $this->name = $data['name'];
    $this->started = $data['started'];
    $this->num_rounds = $data['num_rounds'];
    $this->current_round = $data['current_round'];
    $this->info = $data['info'];

  }

  function getName() {
    return $this->name;
  }
  
  function hasStarted() {
    return ($this->started);
  }
  function getUserList() {
    $result = $this->db->getTournamentRegisteredList($this->tourn_id);
    if ($result['status']) {
      return $result;
    }
    else {
      $this->registered = $result['data'];
    }
    return array('status' => 0, 'data' => $this->registered);
  }

  function isRegistered($uid) {
    return $this->db->isInTournament($uid, $this->tourn_id);
  }

  function getRegisteredNum() {
    return sizeof($this->registered);
  }

  function getInfo() {
    return $this->info;
  }

  function getNumRounds() {
    return $this->num_rounds;
  }

  function getCurrentRound() {
    return $this->current_round;
  }

  function getProgressAsPercent() {
    if ($this->current_round == 0) return 100;
    return ((($this->num_rounds - $this->current_round) / $this->num_rounds) * 100);
  }
}


?>
