<?php
require_once('obj/db.php');

class Map {

  private $name;
  private $mid;
  private $path;

  public function __construct($name) {
    $db = DB::getInstance();
    $data = $db->getMapByName($name);
    $this->name = $name;
    $this->mid = $data['id'];
    $this->path = $data['path'];
  }

  public function getName() {
    return $this->name;
  }

  public function getMID() {
    return $this->mid;
  }

  public function getPath() {
    return $this->path;
  }

}

?>
