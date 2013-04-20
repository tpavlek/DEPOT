<?php
require_once('sc2replay/mpqfile.php');
require_once('obj/Map.php');
require_once('obj/db.php');
class replayBox {

  private $mpqfile;
  private $replay;
  private $winner;
  private $loser;
  private $map;
  private $replayPath;
  private $version;
 

  public function __construct($path) {
    $db = DB::getInstance();
    $this->mpqfile = new MPQfile($path);
    $this->version = $this->mpqfile->getVersion();
    if ($this->version < 2) {
      $this->replay = $this->mpqfile->parseReplay();
      $players = $this->replay->getPlayers();
      foreach ($players as $player) {
        if (isset($player['won'])) {
          $this->winner = array('name' => $player['name'], 'bnet_id' => $player['uid']);
          $inDB = $db->getUserIdByBnetId($this->winner['bnet_id']);
          if (!$inDB['status']) $this->winner['uid'] = $inDB['data']['id'];
        } else {
          $this->loser = array('name' => $player['name'], 'bnet_id' => $player['uid']);
          $inDB = $db->getUserIdByBnetId($this->loser['bnet_id']);
          if (!$inDB['status']) $this->loser['uid'] = $inDB['data']['id'];
        }
      }
      $map = new Map($this->replay->getMapName());
      $this->map = $map;
    }
    $this->replayPath = $path;
  }

  function getBox() {
    if ($this->version >= 2) {
      return $this->getErrorBox();
    }
    $str = "
    <div class='well'>
      <div class='row-fluid'>
        <div class='span3' >";
    if (isset($this->winner['uid'])) {
      $str .= "<a href='?page=userProfile.php&uid=". $this->winner['uid'] . "' class='btn btn-success'>". $this->winner['name'] ."</a>";
    } else {
      $str .= "<button class='btn btn-success'>" .$this->winner['name'] . "</button>";
    }
    $str .= "
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
        <div class='span3'>";
    if (isset($this->loser['uid'])) {
      $str .= "<a href='?page=userProfile.php&uid=" . $this->loser['uid'] . "' class='btn btn-danger'>".$this->loser['name']."</a>";
    } else {
      $str .= "<button class='btn btn-danger pull-right'>" . $this->loser['name'] . "</button>";
    }
    $str .= "
        </div>
        </div>
      </div>";
    return $str;
  }

  function getErrorBox() {
    $str = "
      <div class='well'>
        <p>Replay processing does not currently work for patch 2.0.4 and higher</p>
        <div class='btn-group'>
          <button class='btn btn-primary'>Click arrow to download</button>
          <button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
            <span class='caret'></span>
          </button>
          <ul class='dropdown-menu'>
            <li><a href=". $this->replayPath . ">Download</a></li>
          </ul>    
        </div>

        </div>
        ";
    $str = "
      <p>
      <a href='". $this->replayPath . "' class='btn'><i class='icon-play'></i> Download Replay</a>
      </p>";
    return $str;
  }

  function getWinnerUID() {
    if (isset($this->winner['uid']))
      return $this->winner['uid'];
    else return 0;
  }

  function getLoserUID() {
    if (isset($this->winner['uid'])) {
      return $this->winner['uid'];
    } else return 0;
  }

  function getWinnerName() {
    return $this->winner['name'];
  }

  function getWinnerBnetID() {
    return $this->winner['bnet_id'];
  }

  function getLoserName() {
    return $this->loser['name'];
  }

  function getLoserBnetID() {
    return $this->loser['bnet_id'];
  }

}

?>
