<?php

require_once('obj/tournaments/Bracket.php');
require_once('fragments/Match.php');
class BracketRender extends Page {
  /**
   * takes a tournament ID and a round number and renders out the matchColumn.
   */

  private $match_array;
  private $bo;
  private $bracket;
  private $ro;

  public function __construct($tourn_id, $ro) {
    parent::__construct();
    $this->bracket = new Bracket($tourn_id);
    $this->match_array = $this->bracket->getBracket($ro);
    $this->bo = $this->bracket->getBo($ro);
    $this->ro = $ro;
  }

  public function getBox() {
    $str = "
      <div class='span3 matchColumn' id='ro" . $this->ro ."'>";
    $content = "";
    for ($j = 1; $j <= $this->bo; $j++) {
      $map = $this->bracket->getMap($this->ro, $j);
      $content .= "<p>Game " . $j . ": " . $map['name'] ."</p>";
    }
    $str .= "<div class='mapListPopover' data-trigger='hover' data-html='true' data-content = '" . $content . "' rel='popover'
      data-placement='bottom'>
        <a>
            <h3>Round " . $this->ro . " (bo" . $this->bo ."):</h3>
        </a>
    </div>";
    if ($this->bo > 1) {
      foreach($this->match_array as $match) {
        $str .= "
        <div class='match' id='mid" . $match->getCurrentMatch() . "'>";
        $box = new BoMatchBox($match);
        $str .= $box->getBox();
        $str .= "</div>";
      }
    } else {
      foreach ($this->match_array as $match) {
        $str .= "
        <div class='match' id='mid" . $match->getMID() . "'>";
        $box = new MatchBox($match);
        $str .= $box->getBox();
        $str .= "</div>";
      }
    }
    $str .= "</div>";
    return $str;
  }
}

?>