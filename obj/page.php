<?php
require_once('obj/db.php');
require_once('config.php');
session_start();
class Page {
	public $date;
	protected $db;

	public function __construct() {
		global $DATABASE;
		$this->db = new DB($DATABASE['username'],$DATABASE['password'],$DATABASE['name'],$DATABASE['host']);
    date_default_timezone_set('America/Edmonton');
		$this->date = date("d-m-Y H:i:s");
	}
  
  function permissions($reqd) {
    foreach ($reqd as $perm) {
      switch ($perm) {
        case 'loggedIn': return (isset($_SESSION['uid'])); break;
        case 'admin': return (isset($_SESSION['uid']) && $_SESSION['rank'] == 'admin');
      }
    }
  }


  /* maybe I should fix this? 
	function permissions($reqd) {
		switch ($reqd) {
			case 'loggedIn': 
				if (!isset($_SESSION['username'])) $this->redirect("login"); 
				else return true; break;
			case 'unbanned':
				if (isset($_SESSION['rank'])) {
					if ($_SESSION['rank'] == "banned" || $this->db->isBanned($_SESSION['uid'])) header("Location: http://gtfocatlol.ytmnd.com/");
					else return true;
				} else return true; break;
		}
  }*/

  function getDate() {
    return $this->date;
  }

  function getDB() {
    return $this->db;
  }

}

?>
