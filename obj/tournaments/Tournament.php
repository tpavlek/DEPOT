<?php
require_once('obj/page.php');

class Tournament extends Page {

  private $tourn_id;
  private $name;

  public function __construct($tourn_id) {
    parent::__construct();
    $data = $this->db->getTournament($tourn_id);
    $this->name = $data['name'];
  }

  function getName() {
    return $this->name;
  }
}


?>
