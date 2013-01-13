<?php
require_once('sc2replay/mpqfile.php');
require_once('obj/Map.php');
class replayBox {

  private $mpqfile;
  private $replay;
  private $winner;
  private $loser;
  private $map;
  private $replayPath;
 

  public function __construct($path) {
    $this->mpqfile = new MPQfile($path);
    $this->replay = $this->mpqfile->parseReplay();
    $players = $this->replay->getPlayers();
    foreach ($players as $player) {
      if (isset($player['won'])) {
        $this->winner = $player['name'];
      } else {
        $this->loser = $player['name'];
      }
    }
    $map = new Map($this->replay->getMapName());
    $this->map = $map;
    $this->replayPath = $path;
  }

  function getBox() {
    $str = "
    <div class='well'>
      <div class='row-fluid'>
        <div class='span3' >
          <button class='btn btn-success' >" .$this->winner . "</button>
        </div>
        <div class='span6' style='text-align:center;'>
          <ul class='thumbnails'>
            <li class='span12'>
              <div class='thumbnail'>
                <img src=" . $this->map->getPath() . " />
                <div class='btn-group'>
                  <button class='btn btn-primary'>".$this->map->getName()."</button>
                  <button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
                    <span class='caret'></span>
                  </button>
                <ul class='dropdown-menu'>
                  <li><a href=". $this->replayPath . ">Download</a></li>
                </ul>    
              </div>
            </li>
          </ul>
        </div>
        <div class='span3'>
          <button class='btn btn-danger pull-right'>" . $this->loser . "</button>
        </div>
        </div>
      </div>";
    return $str;
  }

}

?>
