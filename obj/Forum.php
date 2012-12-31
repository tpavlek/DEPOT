<?php
require_once('obj/page.php');
class Forum extends Page {

  private $fid;
  private $name;
  private $last_topic;
  private $last_topic_tid;
  private $last_poster;
  private $last_poster_uid;
  private $last_poster_pid;
	private $topicList;
  private $pageNum;
  private $pageLimit;

	public function __construct($fid) {
    parent::__construct();
    $data = $this->db->getForum($fid);
    $this->name = $data['name'];
    $this->last_topic = $data['last_topic'];
    $this->last_topic_tid = $data['last_topic_id'];
    $this->last_poster = $data['last_poster'];
    $this->last_poster_uid = $data['last_poster_id'];
    $this->last_poster_pid = $data['last_poster_pid'];
		$this->fid = $fid;
  }

  public function getFid() {
    return $this->fid;
  }

  function getName() {
    return $this->name;
  }

  function getLastTopic() {
    return $this->last_topic;
  }

  function getLastTopicTid() {
    return $this->last_topic_tid;
  }

  function getTopics($pageNum, $pageLimit) {
    $this->pageNum = $pageNum;
    $this->pageLimit = $pageLimit;
    $result = $this->db->getTopicsInForumByPage($this->fid, $this->pageNum, $this->pageLimit);
    $this->topicList = $result['data'];
    return $this->topicList;
  }

  function getForumPages($pageLimit) {
    return $this->db->getNumberOfForumPages($this->fid, $pageLimit);
  }

  //Todo GETTER FUNCTIONS

	/*function displayTopics() {
		$str = "<ul>";
		if ($this->topicList['status'] == 1) {
			$str .= $this->topicList['message'];
		} else {
		foreach ($this->topicList['data'] as $topic) {
			$str .= "<a href='?page=viewTopic&tid=" . $topic->getTid() . "'><li class='subject'>" . $topic->getSubject() . "</li></a>";
			$str .= "<div class='author' style='text-align:right'><span><a href='?page=userProfile&uid=" . $topic->getLastReplyUID() . "'>" . $topic->getLastPoster() . "</a></span><br><span><a href='?page=viewTopic&tid=" . $topic->getTid() . "#pid_" . $topic->getLastReplyPID() . "'><img src='assets/icons/arrow_sm.png' height=10><a></span></div>";
			$str .= "<hr>";
		}
		}
		$str .= "</ul>";
		return $str;
	}

	function displayNewPostButton() {
		$str = "<ul>
			<a href='?page=newTopic&fid=" . $this->fid . "'><li class='big'>NEW TOPIC</li></a>
			</ul>";
		return $str;
  }*/


}
?>
