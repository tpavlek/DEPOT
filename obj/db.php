<?php
require_once('/home/ebon/DEPOT/funcs/verify.php');
require_once('/home/ebon/DEPOT/config.php');
class DB {

	private $pdo;

	public function __construct($username, $password, $dbName, $host) {
		global $DATABASE;
		$this->pdo = new PDO("mysql:host=" . $host . ";dbname=" . $dbName,$username,$password);
	}

  static function getInstance() {
    global $DATABASE;
		return new DB($DATABASE['username'],$DATABASE['password'],$DATABASE['name'],$DATABASE['host']);
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
			return (array('status' => 1, 'message' => 'Adding to database 
			failed. <br> ' .implode($queryPrepared->errorInfo()) ));
	}
	
	function update($args) { 
		$query = "UPDATE " . $args['table'] . " set ";
		$i = 1;
		foreach ($args['fields'] as $a => $v) {
			$query .=  str_replace(":","",$a);
			$query .= " = " . $a;
      if ($i < count($args['fields']))
        $query .= ", ";
      else $query .= " ";
      $i++;
		}
		$i = 1;
		$query .= "WHERE ";
		foreach ($args['where'] as $a => $v) {
			$query .=  str_replace(":","",$a);
			$query .= " = " . $a;
      if ($i < count($args['where']))
        $query .= " AND ";
      else $query .= " ";
      $i++;
		}
    $queryPrepared = $this->pdo->prepare($query);
		if ($queryPrepared->execute(array_merge($args['fields'], $args['where']))) 
			return (array('status' => 0, 'message' => 'Successfully updated.'));
    else {
      print_r($queryPrepared->errorInfo());
      return (array('status' => 1, 'message' => 'Adding to database failed.'));
    }
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
	
  function editPost($pid, $subject, $message) {
		if (!$this->isInDatabase(array('type' => 'post', 'value' => $pid)))
			return array('status' => 1, 'message' => "That post doesn't exist, gtfo");
		else {
			$ver1 = verifyString($subject, $GLOBALS['SUBJECT_MIN_LENGTH'], $GLOBALS['SUBJECT_MAX_LENGTH']);
			$ver2 = verifyString($message, $GLOBALS['POST_MIN_LENGTH'], $GLOBALS['POST_MAX_LENGTH']);
			if ($ver1['status'] == 1) {
				$ver1['message'] = "Subject " . $ver1['message'];
				return $ver1;
			} else $args['fields'][':subject'] = validateString($subject);
			if ($ver2['status'] == 1) {
				$ver2['message'] = "Message " . $ver2['message'];
				return $ver2;
      } else $args['fields'][':message'] = validateString($message);
      $args = array('table' => 'posts', 'fields' => array(':subject' => $subject, ':message' => $message), 'where' => array(':id' => $pid)); 
			return $this->update($args);
		}
	}
	
  function addPost($args) {
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
		$query = "UPDATE topics set last_poster = :last_poster, replies = replies + 1, last_reply = :last_reply, last_reply_uid = :last_reply_uid, last_reply_pid = :last_reply_pid where id = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':last_poster', $post->getAuthor());
		$queryPrepared->bindValue(':last_reply', $post->getDate());
		$queryPrepared->bindValue(':last_reply_uid', $post->getAuthorUID());
		$queryPrepared->bindValue(':last_reply_pid', $post->getPID());
		$queryPrepared->bindValue(':tid', $post->getTID());
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully updated topic list');
		else
			return array('status' => 1, 'message' => 'Failed to update topic list'); 
		
	}
	
	function deleteTopic($tid) {
		require_once('obj/forum/Topic.php');
		$result = $this->getRepliesAsPDOStatement($tid);
		if ($result['status'] == 1) return $result;
		$data = $result['data'];
		$topic = new Topic($tid);
		$result = $this->delete(array('table' => 'topics', 'fields' => array(':id' => $tid)));
		if ($result['status'] == 1) return $result;
		else $this->updatePostCount($topic->getAuthorUID(), -1);
		while ($item = $data->fetch()) {
			$this->deletePost($item['id']);
		}
		$newTid = $this->getLastTopic($topic->getFID());
		$this->updateForumList(new Topic($newTid['data']['id']));
		return array('status' => 0, 'message' => 'Everything is gone');
	}
	
	function deletePost($pid) {
		require_once('obj/forum/Post.php');
		require_once('obj/forum/Topic.php');
		$post = new Post($pid);
		$result = $this->delete(array('table' => 'posts', 'fields' => array(':id' => $pid)));
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
		$query = "SELECT id from posts where in_reply_to = :tid ORDER BY STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s') DESC LIMIT 0,1";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not get last post');
		return array('status' => 0, 'data' => $queryPrepared->fetch());
  }

  function hasReplies($tid) {
    $query = "SELECT id from posts where in_reply_to = :tid";
		$queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tid', $tid);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() > 0) return true;
    else return false;
  }
	
	function getLastTopic($fid) {
		$query = "SELECT id from topics where in_forum = :fid ORDER BY STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s') DESC";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':fid', $fid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not get last topic');
		else return array('status' => 0, 'data' => $queryPrepared->fetch());
	}

	function isBanned($uid) {
		$query = "SELECT rank from user where id = :uid";
		$queryPrepared = $this->pdo->prepare($query);
		
		$queryPrepared->bindValue(':uid', $uid);
		$queryPrepared->execute();
		$arr = $queryPrepared->fetch();
		return ($arr['rank'] == "banned");
  }

  function isInTournament($uid, $tourn_id) {
    $query = "SELECT uid from tournament_registered where tourn_id = :tourn_id AND uid = :uid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->bindValue(':uid', $uid);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() != 0) {
      return true;
    } else return false;
  }

  function isInMatch($uid, $tourn_id) {
    require_once('obj/tournaments/Match.php');
    $query = "SELECT matches.player_1, matches.player_2, matches.match_id 
      from bracket, matches where bracket.match_id = matches.match_id
      AND (matches.player_1 = :uid OR matches.player_2 = :uid) 
      AND bracket.in_tournament = matches.in_tournament ORDER by bracket.ro";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->bindValue(':uid', $uid);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() == 0) {
      return false;
    }
    $result = $queryPrepared->fetch();
    if ($result['player_1'] == $uid || $result['player_2'] == $uid) {
      $match = new Match($result['match_id']);
      if (!$match->hasWinner()) {
        return $result['match_id'];
      }
    }
    return false;

  }

	function isInDatabase($args) { //TODO decouple args
		switch ($args['type']) {
			case 'email': $query = "SELECT id from user where email = :data"; break;
			case 'username': $query = "SELECT id from user where username = :data";
			break;
			case 'topic': $query = "SELECT id from topics where id = :data"; break;
			case 'forum': $query = "SELECT id from forums where id = :data"; break;
      case 'post': $query = "SELECT id from posts where id = :data"; break;
		}
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':data',$args['value']);
		$queryPrepared->execute();
		if ($queryPrepared->rowCount() != 0) return true;
		else return false;
	}
	
	function isTopicAuthor($tid, $uid) {
		$query = "SELECT author_uid from topics where id = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
		$queryPrepared->execute();
		$data = $queryPrepared->fetch();
		return ($data['author_uid'] == $uid);
	}
	
	function isPostAuthor($pid, $uid) {
		$query = "SELECT author_uid from posts where id = :pid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':pid', $pid);
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
		$queryPrepared->bindValue(':email', $email);
		$queryPrepared->execute();
		if ($queryPrepared->rowCount() == 0) {
			return (array('status' => 1, 'message' => "User not found in database"));
		}
		return $queryPrepared->fetch();
	}

  function getForumList() { //TODO status messages
    require_once('obj/Forum.php');
		$query = "SELECT id from forums";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->execute();
    $arr = $queryPrepared->fetchAll();
    $forumList = array();
    foreach ($arr as $id) {
      $forumList[] = new Forum($id['id']);
    }
    return $forumList;
  }

  function getForum($fid) {
    $query = "SELECT * from forums where id = :fid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':fid', $fid);
    $queryPrepared->execute();
    return $queryPrepared->fetch();
  }

	function getUserList($args) { //TODO status messages and pagination
		$query = "SELECT id from user ORDER BY ";
		switch ($args['orderBy']) {
			case 'join_date': $query .= "STR_TO_DATE(join_date, '%d-%m-%Y %H:%i:%s')";  break;
			case 'postcount': $query .= "postcount DESC"; break;
			default: $query .= "username"; break;
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
		$query = "SELECT id, username, rank, email, join_date, postcount, profile_pic, bnet_id, bnet_name, char_code from user WHERE id = :uid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':uid', $uid);
		if (!$queryPrepared->execute()) 
			return array('status' => 1, 'message' => 'Could not read user data');
		else
			return array('status' => 0, 'data' => $queryPrepared->fetch());
  }

  function getUserIdByBnetId($bid) {
    $query = "SELECT id from user where bnet_id = :bid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':bid', $bid);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() == 0) {
      return array('status' => 1, 'message' => 'No user for that id');
    } // TODO CHECK FOR MULTIPLE COPIES OF BNET ID
    else 
      return array('status' => 0, 'data' => $queryPrepared->fetch());
  }

	function updateForumList($topic) {
		$query = "UPDATE forums set last_topic = :last_topic, last_topic_id =
			:last_topic_id, last_poster = :last_poster, last_poster_id = 
			:last_poster_id, last_poster_pid = :last_poster_pid where id = :fid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':last_topic', $topic->getSubject());
		$queryPrepared->bindValue(':last_topic_id', $topic->getTid());
		$queryPrepared->bindValue(':last_poster', $topic->getAuthor());
		$queryPrepared->bindValue(':last_poster_id', $topic->getAuthorUID());
		$queryPrepared->bindValue(':last_poster_pid', $topic->getLastReplyPID());
		$queryPrepared->bindValue(':fid', $topic->getFID());
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully updated forum list');
		else
			return array('status' => 1, 'message' => 'Failed to update forum list');
	}
	
	function updatePostCount($uid, $num=1) {
		$query = "UPDATE user set postcount = postcount + :num where id = :uid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':num', $num);
		$queryPrepared->bindValue(':uid', $uid);
		if($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Post count updated successfully');
		else
			return array('status' => 0, 'message' => 'Post count could not be updated');
	}
	
	function updateProfilePic($uid, $path) {
		$query = "UPDATE user set profile_pic = :profile_pic where id = :uid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':profile_pic', $path);
		$queryPrepared->bindValue(':uid', $uid);
		if ($queryPrepared->execute())
			return array('status' => 0, 'message' => 'Successfully changed profile picture');
		else
			return array('status' => 1, 'message' => 'Failed to update the profile picture in the database');
	}

	function getTopicsInForumByPage($fid, $pageNum, $pageLimit) {
		$query = "SELECT id from topics where in_forum = :fid ORDER by 
			STR_TO_DATE(last_reply, '%d-%m-%Y %H:%i:%s')
			DESC LIMIT " . ($pageNum * $pageLimit) . ", " 
			. $pageLimit;
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':fid', $fid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'Could not retrieve 
			topics '. implode($queryPrepared->errorInfo()));
		else {
			require_once('/home/ebon/DEPOT/obj/forum/Topic.php');
			$arr = $queryPrepared->fetchAll();
			$data = array();
			foreach ($arr as $a) {
				$data[] = new Topic($a['id']);
			}
			return array('status' => 0, 'data' => $data);
		}
  }

  function getNumberOfTopicPages($tid, $pageLimit) {
    $query = "SELECT id from posts where in_reply_to = :tid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tid', $tid);
    $queryPrepared->execute();
    $num = $queryPrepared->rowCount() + 1; 
    if ($num % $pageLimit == 0) return $num / $pageLimit;
    else return (int)(($num / $pageLimit) + 1);
  }

  function getNumberOfForumPages($fid, $pageLimit) {
    $query = "SELECT id from topics where in_forum = :fid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':fid', $fid);
    $queryPrepared->execute();
    $num = $queryPrepared->rowCount() + 1;
    if ($num % $pageLimit == 0) return $num / $pageLimit;
    else return (int)(($num / $pageLimit) + 1);
  }
	
	function getRepliesAsPDOStatement($tid) {
		$query = "SELECT id from posts where in_reply_to = :tid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
		if (!$queryPrepared->execute()) return array('status' => 1, 'message' => 'Could not retrieve posts');
		else return array('status' => 0, 'data' => $queryPrepared);
	}
	
	function getRepliesInTopicByPage($tid, $pageNum, $postsPerPage) {
		$query = "SELECT id from posts where in_reply_to = :tid ORDER by 
			STR_TO_DATE(date, '%d-%m-%Y %H:%i:%s')
		  LIMIT " . ($pageNum * $postsPerPage) . ", " 
			. $postsPerPage;
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'Could not retrieve posts 
			<br> ' . implode($queryPrepared->errorInfo()));
		else {
			require_once('obj/forum/Post.php');
			$arr = $queryPrepared->fetchAll();
			$data = array();
      foreach ($arr as $a) {
				$data[] = new Post($a['id']);
			}
			return array('status' => 0, 'data' => $data);
		}
	}
	
	function getPost($pid) {
		$query = "SELECT id, author, subject, message, in_reply_to, date, author_uid from posts where id = :pid";
		$queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':pid', $pid);
		if (!$queryPrepared->execute())
			return array('status' => 1, 'message' => 'could not get post');
		else {
			return array('status' => 0, 'data' => $queryPrepared->fetch());
		}
	}

	function getTopic($tid) {
    $query = "SELECT subject, author, last_poster, id, author_uid, 
    message, in_forum, last_reply_uid, last_reply_pid, replay from 
			topics WHERE id = :tid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
		if (!$queryPrepared->execute()) 
			return array('status' => 1, 'message' => 'Could not read topic from database');
		else
			return array('status' => 0, 'data' => $queryPrepared->fetch());
	}
	
	function getTopicSubject($tid) {
		$query = "SELECT subject from topics WHERE id = :tid";
    $queryPrepared = $this->pdo->prepare($query);
		$queryPrepared->bindValue(':tid', $tid);
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
			$queryPrepared->bindValue(':uid', $uid);
			$queryPrepared->execute();
			$data = $queryPrepared->fetch();
			return ($data['rank'] == 'admin');
		}
	}

	function getLastInsertId() {
		return $this->pdo->lastInsertId();
  }
// TODO remove magic rank/profile_pic
  function registerUser($username, $email, $join_date) {
    return $this->add(array('table' => 'user', 'fields' => array(':username' => $username, ':email' => $email, ':join_date' => $join_date, ':rank' => "user", ':profile_pic' => "assets/profile/uid_0.gif")));
  }

  function getTournament($tourn_id) {
    $query = "SELECT * from tournaments where id = :tourn_id";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->execute();
    return $queryPrepared->fetch();
  }

  function getMatch($mid) {
    $query = "SELECT in_tournament, player_1, player_2, replay, winner, match_id from matches where match_id = :mid";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':mid', $mid);
    $queryPrepared->execute();
    return $queryPrepared->fetch();
  }

  function getBracket($tourn_id, $round) {
    require_once('obj/tournaments/Match.php');
    $query = "SELECT match_id, position, bo, game_num from bracket where in_tournament = :tourn_id and ro = :round ORDER by POSITION";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->bindValue(':round', $round);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() == 0) {
      return array('status' => 1, 'message' => "There's nothing here");
    }
    $brackets = $queryPrepared->fetchAll();
    foreach ($brackets as $b) {
      $bracket[] = new Match($b['match_id']);
    }
    return $bracket; //TODO THIS MIGHT NOT WORK
  }

  function registerInTournament($uid, $tourn_id) {
    return $this->add(array('table' => 'tournament_registered', 'fields' => array(':uid' => $uid, ':tourn_id' => $tourn_id)));
  }

  function getTournamentRegisteredList($tourn_id) {
    require_once('obj/User.php');
    $query = "SELECT uid from tournament_registered where tourn_id = :tourn_id";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() == 0) {
      return array('status' => 1, 'message' => "There is no one here");
    }
    $result = $queryPrepared->fetchAll();
    foreach ($result as $id) {
      $users[] = new User($id['uid']);
    }
    return array('status' => 0, 'data' => $users);
  }

  function getTournamentRegisteredNum($tourn_id) {
    $query = "SELECT uid from tournament_registered where tourn_id = :tourn_id";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->execute();
    return $queryPrepared->rowCount();
  }

  function generateBracket($tourn_id) {
    $participants_num = $this->getTournamentRegisteredNum($tourn_id);
    $ro = 0;
    $i = 1;
    while (!$ro) {
      if ($participants_num <= pow(2,$i)) {
        $ro = $i;
      }
      $i++;
    }
    $overflow = $participants_num - pow(2, $ro - 1);
    $tourn_update = array('table' =>'tournaments', 'fields' => array(':current_round' => $ro, ':num_rounds' => $ro), 'where' => array(':id' => $tourn_id)); 
    if ($overflow) {
      $tourn_update['fields'][':current_round'] = $ro - 1;
    }
    $this->update($tourn_update);
    return $this->seedBracket($tourn_id, $ro, $overflow);
  }

  function processByes($tourn_id, $round) {
    $bracket = $this->getBracket($tourn_id, $round);
    foreach ($bracket as $match) {
      if ($match->getPlayer1() < 0) {
        $this->reportGameWin($match->getMID(), $match->getPlayer2());
      } else if ($match->getPlayer2() < 0) {
        $this->reportGameWin($match->getMID(), $match->getPlayer1());
      }
    }
  }

  function seedBracket($tourn_id, $rounds, $overflow) { //TODO skip seeding
    $users = $this->getTournamentRegisteredList($tourn_id)['data'];
    $j = 0; 

    //first we seed in players into round 1
    for ($i = 0; ($i < pow(2, $rounds - 1)); $i++) {
      $add = array('table' => 'matches', 'fields' => array(':in_tournament' => $tourn_id, ':player_1' => $users[$i]->getUID()));
      if ($j < $overflow || $overflow == 0) {
        $add['fields'][':player_2'] = $users[$j + pow(2, $rounds -1)]->getUID();
      }
      $this->add($add);
      $this->add(array('table' => 'bracket', 'fields' => array(':match_id' => $this->getLastInsertId(), ':position' => $i, ':in_tournament' => $tourn_id, ':ro' => $rounds)));
      $j++;
    }
    //If there's overflow we set byes to -1. We must do this before generating remaining matches
    if ($overflow) {
      $query = "UPDATE matches set player_2 = -1 where in_tournament = :tourn_id and player_2 = 0";
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->bindValue(':tourn_id', $tourn_id);
      $queryPrepared->execute();
    }
    //next we create all remaining matches/bracket
    for ($i = $rounds -1; $i > 0; $i--) {
      for ($k = 0; ($k < pow(2, $i - 1)); $k++) {
        $add = array('table' => 'matches', 'fields' => array(':in_tournament' => $tourn_id));
        $this->add($add);
        $this->add(array('table' => 'bracket', 'fields' => array(':match_id' => $this->getLastInsertId(), ':position' => $k, ':in_tournament' => $tourn_id, ':ro' => $i)));
      }
    }
    if ($overflow) {
      $this->processByes($tourn_id, $rounds);
    }
    $this->update(array('table' => 'tournaments', 'fields' => array(':started' => 1), 'where' => array(':id' => $tourn_id)));
    return array('status' => 0);
  }

  function reportGameWin($mid, $uid) {
    $this->advanceBracket($uid, $mid);
    return $this->update(array('table' => 'matches', 'fields' => array(':winner' => $uid), 'where' => array(':match_id' => $mid)));
  }

  function bracketRoExists($tourn_id, $position) {
    $query = "SELECT match_id from bracket where tourn_id = :tourn_id and position = :position";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':tourn_id', $tourn_id);
    $queryPrepared->bindValue('position', $position);
    $queryPrepared->execute();
    if ($queryPrepared->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  function getMidFromBracketByPosition($tourn_id, $position, $round) {
    $query = "SELECT match_id from bracket where in_tournament = :in_tournament and position = :position and ro = :round";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':in_tournament', $tourn_id);
    $queryPrepared->bindValue(':position', $position);
    $queryPrepared->bindValue(':round', $round);
    if(!$queryPrepared->execute())
      print_r($queryPrepared->errorInfo());
    if ($queryPrepared->rowCount() == 0) return false;
    return($queryPrepared->fetch());
  }

  function switchMatchPlayers($mid1, $uid1, $mid2, $uid2, $source, $destination) {
    $result = $this->update(array('table' => 'matches', 'fields' => array(':player_'.$source => $uid2),
      'where' => array(':match_id' => $mid1)));
    if ($result['status']) return $result;
    $result = $this->update(array('table' => 'matches', 'fields' => array(':player_'.$destination => $uid1),
      'where' => array(':match_id' => $mid2)));
    if ($result['status']) return $result;
    return array('status' => 0);
  }

  function advanceBracket($uid, $mid) {
    $query = "SELECT * from bracket where match_id = :mid"; //TODO SELECT *
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':mid', $mid);
    $queryPrepared->execute();
    $data = $queryPrepared->fetch();
    $add = array ('table' => 'matches', 'fields' => array());
    if (($data['position'] / 2) > floor($data['position'] / 2)) {
      $add['fields'][':player_2'] = $uid;
    } else {
      $add['fields'][':player_1'] = $uid;
    }
    $midFromBrack = $this->getMidFromBracketByPosition($data['in_tournament'], floor($data['position']/2), $data['ro'] - 1);
    $add['where'] = array(':match_id' => $midFromBrack['match_id']);
    $this->update($add);
    return (array('status' => 0));
  }

  function addMatchReplay($match_id, $replay) {
    return ($this->update(array('table' => 'matches', 'fields' => array(':replay' => $replay), 
      'where' => array(':match_id' => $match_id))));
  }
    

  function getMapByName($name) {
    $query = "SELECT * from maps where name = :name";
    $queryPrepared = $this->pdo->prepare($query);
    $queryPrepared->bindValue(':name', $name);
    $queryPrepared->execute();
    return $queryPrepared->fetch();
  }

  function updateBnetId($uid, $bid) {
    return $this->update(array('table' => 'user', 'fields' => array(':bnet_id' => $bid), 'where' => array(':id' => $uid)));
  }

  function updateBnetName($uid, $bname) {
    return $this->update(array('table' => 'user', 'fields' => array(':bnet_name' => $bname), 'where' => array(':id' => $uid)));
  }

  function updateBnetCharCode($uid, $ccode) {
    return $this->update(array('table' => 'user', 'fields' => array(':char_code' => $ccode), 'where' => array(':id' => $uid)));
  }

}
?>
