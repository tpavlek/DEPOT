<?php
require_once('obj/User.php');
require_once('obj/page.php');
class MatchBox extends Page {

  private $match;

  public function __construct($match) {
    parent::__construct();
    $this->match = $match;
  }

  function getBox() {
    $players[] = $this->match->getPlayer1();
    $players[] = $this->match->getPlayer2();
    $href = "";
    $str = "
     <div class='well'>
      <div class='btn-group btn-group-vertical btn-block'>";
        $i = 1;
        foreach ($players as $player) {
          switch ($player) {
            case -1: $class = 'disabled'; $name = 'Bye'; break;
            case 0: $class='disabled'; $name = 'TBD'; break;
            default: if ($this->match->hasWinner()) {
              if ($this->match->getWinner() == $player) {
                $class='btn-success';
              } else {
                $class='btn-danger';
              } 
            } else {
              $class='btn-primary';
            }
            $user = new User($player);
            $name = $user->getBnetName(); $href= '?page=userProfile.php&uid=' . $player; break;
          } 
          $str .= "<a class='player" . $i ." btn btn-block " . $class . "' href='" . $href . "'>".$name . "</a>";
          $i++;
        } 
    $str .= "
      </div>
      ";
    if ($this->permissions(array('admin'))) {
      $str .= "<p><a name='matchEditButton' role='button' href='#editMatchModal' data-toggle='modal' class='btn pull-right'>
          <i class='icon-wrench'></i>
        </a></p>";
    }
    $str .= "
   </div>
      
   ";
    return $str;
  }
}

?>
