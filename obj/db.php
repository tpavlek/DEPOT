<?php
require_once('funcs/verify.php');
require_once('config.php');
class DB {

	private $pdo;

	public function __construct($username, $password, $dbName, $host) {
		$this->pdo = new PDO("mysql:host=localhost;dbname=depot","ebon","green");
	}

	static function getInstance() {
		return new DB("1","2","3","4");
	}
	
	function getPDO() {
		return $this->pdo;
	}

	/**
	* Adds to the database. Takes an associative array, with 'table' => table_name
	* and 'fields' => array(':field_name' => 'value', etc.)
	* Returns associative array, with status and message.
	*/
	function add($args) {
		$query = "INSERT into " . $args['table'] . " (";
		$queryPart2 = "VALUES (";
		$i = 1;
    foreach ($args['fields'] as $a=>$v) {
      $query .=  str_replace(":","",$a);
			$queryPart2 .= $a; 
      if ($i < count($args['fields'])) {
        $query .= ", ";
				$queryPart2 .= ",";
      } else {
        $query .= ") ";
				$queryPart2 .= ")";
      }
      $i++;
    }
		$query .= $queryPart2;
		$queryPrepared = $this->pdo->prepare($query);
		if ($queryPrepared->execute($args['fields'])) 
			return (array('status' => 0, 'message' => 'Successfully added to database'));
		else
			return (array('status' => 1, 'message' => 'Adding to database failed.'));
	}
	
	function addTopic($args) {
		if (!$this->isInDatabase(array('type' => 'forum', 'value' => $args['fields'][':in_forum'])))
			return array('status' => 1, 'message' => "That forum doesn't exist, you hobo");
		else {
			$ver1 = verifyString($args['fields'][':subject'], $GLOBALS['SUBJECT_MIN_LENGTH'], $GLOBALS['SUBJECT_MAX_LENGTH']);
			$ver2 = verifyString($args['fields'][':message'], $GLOBALS['POST_MIN_LENGTH'], $GLOBALS['POST_MAX_LENGTH']);
			if ($ver1['status'] == 1) {
				$ver1['message'] = "Subject " . $ver1['message'];
				return $ver1;
			} else $args['fields'][':subject'] = validateString($args['fields'][':subject']);
			if ($ver2['status'] == 1) {
				$ver2['message'] = "Message " . $ver2['message'];
				return $ver2;
			} else $args['fields'][':message'] = validateString($args['fields'][':message']);
			$result = $this->add($args);
			if ($result['status'] == 0) {
				require_once('obj/forum/Topic.php');
				$this->updateForumList(new Topic($this->getLastInsertId()));
				$this->updatePostCount($args['fields'][':author_uid']);
			}
			return $result;
		}
	}
	
	function addPost($args) { //TODO UPDATE POST COUNT
		if (!$this->isInDatabase(array('type' => 'topic', 'value' => $args['fields'][':in_reply_to'])))
			return array('status' => 1, 'message' => "That topic doesn't exist, you hobo");
		else {
			$ver1 = verifyString($args['fields'][':subject'], $GLOBALS['SUBJECT_MIN_LENGTH'], $GLOBALS['SUBJECT_MAX_LENGTH']);
			$ver2 = verifyString($args['fields'][':message'], $GLOBALS['POST_MIN_LENGTH'], $GLOBALS['POST_MAX_LENGTH']);
			if ($ver1['status'] == 1) {
				$ver1['message'] = "Subject " . $ver1['message'];
				return $ver1;
			} else $args['fields'][':subject'] = validateString($args['fields'][':subject']);
			if ($ver2['status'] == 1) {
				$ver2['message'] = "Message " . $ver2['message'];
				return $ver2;
			} else $args['fields'][':message'] = validateString($args['fields'][':message']);
			$result = $this->add($args);
			if ($result['status'] == 0) {
				require_once('obj/forum/Post.php');
				require_once('obj/forum/Topic.php');
				$post = new Post($this->getLastInsertId());
				$this->updateTopicList($post);
				$this->updateForumList(new Topic($post->getTID()));
				$this->updatePostCount($args['fields'][':author_uid']);
			}
			return $result;
		}
	}

	function updateTopicList($post) {
		$query = "UPDATE topics set last_poster = :last_poster, replies = replies + 1, last_reply = :last_reply, last_reply_uid = :last_reply_uid, last_reply_pid = :last_reply_pid where tid = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':last_poster', $post->getAuthor());
		$queryPrepared->bindParam(':last_reply', $post->getDate());
		$queryPrepared->bindParam(':last_reply_uid', $post->getAuthorUID());
		$queryPrepared->bindParam(':last_reply_pid', $post->getPID());
		$queryPrepared->bindParam(':tid', $post->getTID());
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully updated topic list');
		else
			return array('status' => 1, 'message' => 'Failed to update topic list'); 
		
	}

	function delete($args) {
		$query = "DELETE from " . $args['table'];
		$query .= " WHERE ";
		$i = 1;
    foreach ($args['fields'] as $a=>$v) {
      $query .=  str_replace(":","",$a) . " = " . $a;
      if ($i < count($args['fields'])) {
        $query .= " AND ";
      }
      $i++;
    }
		$queryPrepared = $this->pdo->prepare($query);
		if ($queryPrepared->execute($args['fields']))
			return (array('status' => 0, 'message' => 'Successfully deleted from database'));
		else
			return (array('status' => 1, 'message' => 'Could not delete from database'));
	}
	
	function deleteTopic($tid) {
		require_once('obj/forum/Topic.php');
		$result = $this->getRepliesAsPDOStatement($tid);
		if ($result['status'] == 1) return $result;
		$data = $result['data'];
		$topic = new Topic($tid);
		$result = $this->delete(array('table' => 'topics', 'fields' => array(':tid' => $tid)));
		if ($result['status'] == 1) return $result;
		else $this->updatePostCount($topic->getAuthorUID(), -1);
		while ($item = $data->fetch()) {
			$this->deletePost($item['pid']);
		}
		$newTid = $this->getLastTopic($topic->getFID());
		$this->updateForumList(new Topic($newTid['data']['tid']));
		return array('status' => 0, 'message' => 'Everything is gone');
	}
	
	function deletePost($pid) {
		require_once('obj/forum/Post.php');
		require_once('obj/forum/Topic.php');
		$post = new Post($pid);
		$result = $this->delete(array('table' => 'posts', 'fields' => array(':pid' => $pid)));
		if ($result['status'] == 1) return $result;
		$this->updatePostCount($post->getAuthorUID(), -1);
		$topic = new Topic($post->getTID());
		if ($topic->getLastReplyPID() == $post->getPID()) {
			$newPid = $this->getLastReply($post->getTID());
			$this->updateTopicList(new Post($newPid['data']['pid']));
		}
		return array('status' => 0, 'message' => 'Post deleted successfully');
	}
	
	function getLastReply($tid) {
		$query = "SELECT pid from posts where in_reply_to = :tid ORDER BY STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s') DESC LIMIT 0,1";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not get last post');
		return array('status' => 0, 'data' => $queryPrepared->fetch());
	}
	
	function getLastTopic($fid) {
		$query = "SELECT tid from topics where in_forum = :fid ORDER BY STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s') DESC";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':fid', $fid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not get last topic');
		else return array('status' => 0, 'data' => $queryPrepared->fetch());
	}

	function isBanned($uid) {
		$query = "SELECT rank from user where id = :uid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':uid', $uid);
		$queryPrepared->execute();
		$arr = $queryPrepared->fetch();
		return ($arr['rank'] == "banned");
	}

	function isInDatabase($args) {
		switch ($args['type']) {
			case 'email': $query= "SELECT id from user where email = :data"; break;
			case 'username': $query="SELECT id from user where username = :data";
			break;
			case 'topic': $query="SELECT tid from topics where tid = :data"; break;
			case 'forum': $query="SELECT fid from forums where fid = :data"; break;
		}
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':data',$args['value']);
		$queryPrepared->execute();
		if ($queryPrepared->rowCount() != 0) return true;
		else return false;
	}
	
	function isTopicAuthor($tid, $uid) {
		$query = "SELECT author_uid from topics where tid = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		$queryPrepared->execute();
		$data = $queryPrepared->fetch();
		return ($data['author_uid'] == $uid);
	}
	
	function isPostAuthor($pid, $uid) {
		$query = "SELECT author_uid from posts where pid = :pid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':pid', $pid);
		$queryPrepared->execute();
		$data = $queryPrepared->fetch();
		return ($data['author_uid'] == $uid);
	}

	function getUserByEmail($email, $args) {
		$query = "SELECT ";
		$i = 1;
		foreach ($args as $a) {
			$query .=  $a;
			if ($i < count($args)) {
				$query .= ", ";
			} else {
				$query .= " ";
			}
			$i++;
		}
		$query .= "from user where email = :email";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':email', $email);
		$queryPrepared->execute();
		if ($queryPrepared->rowCount() == 0) {
			return (array('status' => 1, 'message' => "User not found in database"));
		}
		return $queryPrepared->fetch();
	}

	function getForumList() { //TODO status messages
		$query = "SELECT * from forums ORDER by fid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->execute();
		return $queryPrepared->fetchAll();
	}

	function getUserList($args) { //TODO status messages and pagination
		$query = "SELECT id from user ORDER BY ";
		switch ($args['orderBy']) {
			case 'points': $query .= "points DESC"; break;
			case 'join_date': $query .= "STR_TO_DATE(join_date, '%d-%m-%Y %H:%i:%s')";  break;
			default: $query .= $args['orderBy']; break;
		}
		$queryPrepared = $this->pdo->prepare($query);
		if (!$queryPrepared->execute()) {
			return array('status' => 1, 'message' => 'Could not get user list');
		} else {
			require_once('obj/User.php');
			$arr = $queryPrepared->fetchAll();
			$users = array();
			foreach ($arr as $a) {
				$users[] = new User($a['id']);
			}
			return array('status' => 0, 'data' => $users);
		}
	}
	
	function getUser($uid) {
		$query = "SELECT id, username, email, join_date, rank, postcount, block, points, profile_pic from user WHERE id = :uid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':uid', $uid);
		if (!$queryPrepared->execute()) 
			return array('status' => 1, 'message' => 'Could not read user data');
		else
			return array('status' => 0, 'data' => $queryPrepared->fetch());
	}

	function updateForumList($topic) {
		$query = "UPDATE forums set last_topic = :last_topic, last_topic_id =
			:last_topic_id, last_poster = :last_poster, last_poster_id = 
			:last_poster_id, last_poster_pid = :last_poster_pid where fid = :fid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':last_topic', $topic->getSubject());
		$queryPrepared->bindParam(':last_topic_id', $topic->getTid());
		$queryPrepared->bindParam(':last_poster', $topic->getAuthor());
		$queryPrepared->bindParam(':last_poster_id', $topic->getAuthorUID());
		$queryPrepared->bindParam(':last_poster_pid', $topic->getLastReplyPID());
		$queryPrepared->bindParam(':fid', $topic->getFID());
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully updated forum list');
		else
			return array('status' => 1, 'message' => 'Failed to update forum list');
	}
	
	function updatePostCount($uid, $num=1) {
		$query = "UPDATE user set postcount = postcount + :num";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':num', $num);
		if($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Post count updated successfully');
		else
			return array('status' => 0, 'message' => 'Post count could not be updated');
	}
	
	function updateProfilePic($uid, $path) {
		$query = "UPDATE user set profile_pic = :profile_pic where id = :uid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':profile_pic', $path);
		$queryPrepared->bindParam(':uid', $uid);
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully changed profile picture');
		else
			return array('status' => 1, 'message' => 'Failed to update the profile picture in the database');
	}

	function getTopicsInForumByPage($fid, $args) {
		$query = "SELECT tid from topics where in_forum = :fid ORDER by 
			STR_TO_DATE(last_reply, '%d-%m-%Y %H:%i:%s')
			DESC LIMIT " . ($args['pageNum'] * $args['pageLimit']) . ", " 
			. $args['pageLimit'];
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':fid', $fid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'Could not retrieve topics');
		else {
			require_once('obj/forum/Topic.php');
			$arr = $queryPrepared->fetchAll();
			$data = array();
			foreach ($arr as $a) {
				$data[] = new Topic($a['tid']);
			}
			return array('status' => 1, 'data' => $data);
		}
	}
	
	function getRepliesAsPDOStatement($tid) {
		$query = "SELECT pid from posts where in_reply_to = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not retrieve posts');
		else return array('status' => 0, 'data' => $queryPrepared);
	}
	
	function getRepliesInTopicByPage($tid, $args) {
		$query = "SELECT pid from posts where in_reply_to = :tid ORDER by 
			STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s')
		  LIMIT " . ($args['pageNum'] * $args['postsPerPage']) . ", " 
			. $args['postsPerPage'];
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'Could not retrieve posts');
		else {
			require_once('obj/forum/Post.php');
			$arr = $queryPrepared->fetchAll();
			$data = array();
			foreach ($arr as $a) {
				$data[] = new Post($a['pid']);
			}
			return array('status' => 1, 'data' => $data);
		}
	}
	
	function getPost($pid) {
		$query = "SELECT pid, author, subject, message, in_reply_to, date, author_uid from posts where pid = :pid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':pid', $pid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'could not get post');
		else {
			return array('status' => 0, 'data' => $queryPrepared->fetch());
		}
	}

	function getTopic($tid) {
    $query = "SELECT subject, author, last_poster, tid, author_uid, message, in_forum, last_reply_uid, last_reply_pid from 
			topics WHERE tid = :tid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		if (!$queryPrepared->execute()) 
			return array('status' => 1, 'message' => 'Could not read topic from database');
		else
			return array('status' => 0, 'data' => $queryPrepared->fetch());
	}
	
	function getTopicSubject($tid) {
		$query = "SELECT subject from topics WHERE tid = :tid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindParam(':tid', $tid);
		if (!$queryPrepared->execute()) 
			return "";
		else {
			$data = $queryPrepared->fetch();
			return $data['subject'];
		}
	}
	
	function isAdmin($uid) {
		if (isset($_SESSION['rank']))
			return ($_SESSION['rank'] == 'admin');
		else {
			$query = "SELECT rank from user where id = :uid";
			$queryPrepared = $this->pdo->prepare($query);
			$queryPrepared->bindParam(':uid', $uid);
			$queryPrepared->execute();
			$data = $queryPrepared->fetch();
			print_r($data);
			return ($data['rank'] == 'admin');
		}
	}
	
	function isAuthor($data) { // IMPORTANT GET THIS SHIT DONE
		return false;
	}

	function getLastInsertId() {
		return $this->pdo->lastInsertId();
	}

}
?>
