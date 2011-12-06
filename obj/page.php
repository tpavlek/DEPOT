<?php
require_once('obj/db.php');
require_once('config.php');
session_start();
class Page {
	public $date;
	private $html;
	protected $db;

	public function __construct() {
		global $DATABASE;
		$this->db = new DB($DATABASE['username'],$DATABASE['password'],$DATABASE['name'],$DATABASE['host']);
		date_default_timezone_set('America/Edmonton');
		$this->date = date("d-m-Y H:i:s");
	}

	public function __toString() {
		return (string)$this->html;	
	}

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
	}

	function redirect($dest) {
		$returnUrl = $_GET['page'];
		$url = "?" .http_build_query(array('page' => $dest, 'dest' => $returnUrl) + $_GET);
		switch ($dest) {
			case 'login': $message = "Must be logged in"; break;
			default: $message = "Ride 'em cowboy!"; break;
		}
		header("Refresh: 3, url=".$url);
		$this->html .= "<span class='big'>" . $message . "</span><br><br>";
		$this->html .="<i>Sending you to where you need to go...</i>";
	}
	
	function blog() {
		$this->html = "<script src='http://feeds.feedburner.com/DepotWarehouseBlag?format=sigpro' type='text/javascript' ></script>";
	}

	function forum() {
		require_once('obj/ForumList.php');
		$forumList = new ForumList();
		if ($this->permissions("unbanned")) {
			$this->html .= $forumList->showForums();
		}

	}

	function viewForum($fid) { //TODO get rid of magic numbers
		if ($this->permissions("unbanned")) {
		require_once('obj/Forum.php');
		$forumObj = new Forum($fid, array('pageNum' => 0, 'pageLimit' => 35));
		$this->html .= $forumObj->displayTopics();
		$this->html .= $forumObj->displayNewPostButton();
		}
	}

	function login($method) {
		require_once('obj/LoginPage.php');
		$loginPage = new LoginPage();
		$this->html .= "<br /> <br />";
		switch ($method) {
			case 'showForm': $this->html .= $loginPage->showForm(); break;
			case 'auth': $this->html .= $loginPage->auth(array('method'=>'loginUser')); break;
			default: $this->html .= $loginPage->showForm(); break;
		}
	}

	function userControl($method) {
		require_once('obj/UserControl.php');
		$userControlObj = new UserControl();
		switch ($method) {
			default:
			case 'showForm': $this->html .= $userControlObj->showForm(); break;
			case 'changePic': $this->html .= $userControlObj->changePic(); break;
			case 'logOut': $this->html .= $userControlObj->logOut(); break;
		}
	}
	
	function userProfile($uid) {
		require_once('obj/UserProfile.php');
		require_once('obj/User.php');
		$profile = new UserProfile(new User($uid));
		$this->html .= $profile->showUser();
	}

	function register($method) {
		require_once('obj/RegisterPage.php');
		$registerPage = new RegisterPage();
		switch ($method) {
			case 'showForm': $this->html .= $registerPage->showForm(); break;
			case 'register': $this->html .= $registerPage->startRegistration(); break;
			case 'finishRegistration': $this->html .=  $registerPage->finishRegistration(); break;
		}
	}

	function post($method) {
		if ($this->permissions("loggedIn")) {
			require_once('obj/forum/PostAction.php');
			$post = new PostAction();
			switch($method) {
				case 'showForm': $this->html .= $post->showForm(); break;
				case 'showEditForm': $this->html .= $post->showEditForm(); break;
				case 'newReply': $result = $post->newReply(); 
					$this->html .= "<span class='big'>" . $result['message'] ."</span>"; break;
				case 'editPost': $result = $post->editPost();
					$this->html .= "<span class='big'>" . $result['message'] . "</span>"; break;
			}
		}
	}

	function viewTopic($tid) {
		if ($this->permissions("unbanned")){
			require_once('obj/forum/Topic.php');
			if ($this->db->isInDatabase(array('type' => 'topic', 'value' => $tid))) {
				$topic = new Topic($tid);
				$pageNum = (isset($_GET['pageNum'])) ? $_GET['pageNum'] : 0;
				$postsPerPage = (isset($_GET['postsPerPage'])) ? $_GET['postsPerPage'] : 15;
				$args = array('pageNum' => $pageNum, 'postsPerPage' => $postsPerPage); 
				$this->html .= $topic->showTopic($args);
			} else $this->html .= "<span class='big'>Topic doesn't exist, hobo</span>";
		}
	}

	function newTopic($method) {
		if ($this->permissions("unbanned") && $this->permissions("loggedIn")) {
			require_once('obj/forum/TopicAction.php');
			$topic = new TopicAction();
			switch($method) {
				case 'newTopic': $result = $topic->createTopic();
					$this->html .= "<span class='big'>" . $result['message'] . "</span>";
					if ($result['status'] == 0)
						//header('Refresh: 3, url=?page=viewTopic&tid=' . $result['tid']);
				break;
				default:
				case 'showForm':  $this->html .= $topic->showForm($_GET['fid']); 
				break;
			}
		}
	}

	function userList($order) {
		require_once('obj/UserList.php');
		switch ($order) {
			case 'join_date': $list = new UserList(array('orderBy' => 'join_date')); break;
			case 'postcount': $list = new UserList(array('orderBy' => 'postcount')); break;
			default:
			case 'points': $list = new UserList(array('orderBy' => 'points')); break; 
			case 'username': $list = new UserList(array('orderBy' => 'username')); break;
		}
		$this->html .= $list->showUsers();
		
	}

	function fourohfour() {
		$this->html .= "<span class='big'>Not found, try going back home</span>";
	}

	public function getHTML() {
		return $this->html;
	}
}

?>
