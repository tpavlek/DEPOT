<?php
require_once('/home/ebon/DEPOT/obj/page.php');
class Control extends Page {

	public function __construct() {
		parent::__construct();
	}
	
	function permissions($reqd) {
		if (!isset($_SESSION['uid'])) return false;
		foreach ($reqd['permissions'] as $perm) {
			switch ($perm) {
				case 'admin': return ($this->db->isAdmin($_SESSION['uid'])); break;
				case 'postauthor': return ($this->db->isPostAuthor($reqd['args']['pid'], $reqd['args']['uid'])); break;
				case 'topicauthor': return ($this->db->isTopicAuthor($reqd['args']['tid'], $reqd['args']['uid'])); break;
				default: return false;
			}
		}
	}
}

?>
