<?php
require_once('funcs/verify.php');
require_once('config.php');
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
      require_once('obj/tournaments/MatchSet.php');
        $query = "SELECT matches.player_1, matches.player_2, matches.match_id
      from bracket, matches where bracket.match_id = matches.match_id
      AND (matches.player_1 = :uid OR matches.player_2 = :uid) 
      AND bracket.in_tournament = matches.in_tournament 
      AND matches.in_tournament = :tourn_id
      ORDER by bracket.ro";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':tourn_id', $tourn_id);
        $queryPrepared->bindValue(':uid', $uid);
        $queryPrepared->execute();
        if ($queryPrepared->rowCount() == 0) {
            return false;
        }
        $result = $queryPrepared->fetchAll();
        if (count($result) > 1) {
          $midarray = array();
          foreach ($result as $mid) {
            $midarray[] = $mid['match_id'];
          }
          $match = new MatchSet($midarray);
          if (!$match->hasWinner()) {
            return $match->getCurrentMatch();
          }
        } else {
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
        $query = "SELECT id, username, rank, email, join_date, postcount, profile_pic,
		  bnet_id, bnet_name, char_code, bnet_league, bnet_url
		  FROM user WHERE id = :uid";
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
            require_once('obj/forum/Topic.php');
            $arr = $queryPrepared->fetchAll();
            $data = array();
            foreach ($arr as $a) {
                $data[] = new Topic($a['id']);
            }
            return array('status' => 0, 'data' => $data);
        }
    }

    function getTopicsInForumFromUserByPage($fid, $uid, $pageNum, $pageLimit) {
        $query = "SELECT id from topics
            where in_forum = :fid
            AND author_uid = :uid
            ORDER by
			STR_TO_DATE(last_reply, '%d-%m-%Y %H:%i:%s')
			DESC LIMIT " . ($pageNum * $pageLimit) . ", "
            . $pageLimit;
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':fid', $fid);
        $queryPrepared->bindValue(':uid', $uid);
        if (!$queryPrepared->execute())
            return array('status' => 1, 'message' => 'Could not retrieve
			topics '. implode($queryPrepared->errorInfo()));
        else {
            require_once('obj/forum/Topic.php');
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

    function getAcronym() {
      $queryPrepared = $this->pdo->query("SELECT fgt from acronyms where approved = 1 ORDER BY RAND() LIMIT 1");
      $queryPrepared->execute();
      return $queryPrepared->fetch()['fgt'];
    }

    function approveAcronym($id) {
      $update = array('table' => 'acronyms', 'fields' => array(':approved' => 1), 
        'where' => array(':id' => $id));
      return $this->update($update);
    }

    function deleteAcronym($id) {
      $delete = array('table' => 'acronyms', 'fields' => array(':id' => $id));
      return $this->delete($delete);
    }

    function getUnapprovedAcronymsByPage($page, $pageLimit = 10) {
      $query = "SELECT id, fgt from acronyms where approved = 0 ORDER by id
        LIMIT ". ($page *$pageLimit) .", ".$pageLimit;
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->execute();
      return $queryPrepared->fetchAll();
    }

    function submitAcronym($str) {
      $query = "insert into acronyms (fgt) values (:str)";
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->bindValue(':str', $str);
      return $queryPrepared->execute();
    }

    function getMatch($mid) {
        $query = "SELECT in_tournament, player_1, player_2, replay, winner, match_id from matches where match_id = :mid";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':mid', $mid);
        $queryPrepared->execute();
        return $queryPrepared->fetch();
    }

    function getMatchSet($mid) {
      require_once('obj/tournaments/MatchSet.php');
      require_once('obj/tournaments/Match.php');
      $query = "SELECT b1.match_id 
        from bracket b1, bracket b2
        where b2.match_id = :mid
        AND b1.ro = b2.ro
        AND b1.position = b2.position
        AND b1.in_tournament = b2.in_tournament
        ORDER BY b1.match_id";
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->bindValue(':mid', $mid);
      $queryPrepared->execute();
      $data = $queryPrepared->fetchAll();

      $matchset = array();

      foreach ($data as $mid) {
        $matchset[] = new Match($mid['match_id']);
      }
      return $matchset;
    }

    function getBracket($tourn_id, $round) {
        require_once('obj/tournaments/Match.php');
        require_once('obj/tournaments/MatchSet.php');
        $query = "SELECT match_id, position, bo, game_num from bracket
      where in_tournament = :tourn_id and ro = :round 
      ORDER by POSITION";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':tourn_id', $tourn_id);
        $queryPrepared->bindValue(':round', $round);
        $queryPrepared->execute();
        if ($queryPrepared->rowCount() == 0) {
            return array('status' => 1, 'message' => "There's nothing here");
        }
        $matchset = array();
        $bracket = array();
        $result = $queryPrepared->fetchAll();
        if ($result[0]['bo'] > 1) {
            $i = 0;
            foreach ($result as $match) {
              if ($i < $result[0]['bo']) {
                $midarray[] = $match['match_id'];
                    if ($i == $result[0]['bo'] -1) {
                        $bracket[] = new MatchSet($midarray);
                        $midarray = array();
                        $i = 0;
                        continue; // we need to continue to stop $i from incrementing again
                    }
                }
                $i++;
            }
        } else {
            foreach($result as $match) {
                $bracket[] = new Match($match['match_id']);
            }
        }
        /*while ($result = $queryPrepared->fetch()) {
          if ($result['bo'] > 1) {
            print_r("here");
            $matchset[] = $result['match_id'];
          } else {
            if (sizeof($matchset) > 1) {
              $bracket[] = new MatchSet($matchset);
              $matchset = array();
            }
            $bracket[] = new Match($result['match_id']);
          }
        }(*/
        return $bracket;
    }

    function registerInTournament($uid, $tourn_id) {
        return $this->add(array('table' => 'tournament_registered', 'fields' => array(':uid' => $uid, ':tourn_id' => $tourn_id)));
    }

    function getMatchFromReplay($rid) {
      $query = "SELECT match_id from matches where replay = :rid";
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->bindValue(':rid', $rid);
      $queryPrepared->execute();
      return $queryPrepared->fetch();
    }

    function getBoFromMatch($match_id) {
      $query = "SELECT bo from bracket where match_id = :match_id";
      $queryPrepared = $this->pdo->prepare($query);
      $queryPrepared->bindValue(':match_id', $match_id);
      $queryPrepared->execute();
      return $queryPrepared->fetch()['bo'];
    }
    /**
     * Takes a $tourn_id
     * returns an array with 'status' and 'data' containing an array of User objects.
     */
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

    /**
     * takes a tournament id
     * returns a nonnegative integer with the number of registered participants
     */
    function getTournamentRegisteredNum($tourn_id) {
        $query = "SELECT uid from tournament_registered where tourn_id = :tourn_id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':tourn_id', $tourn_id);
        $queryPrepared->execute();
        return $queryPrepared->rowCount();
    }

    function determineNumberOfRounds($participants_num) {
        $ro = 0;
        $i = 1;
        while (!$ro) {
            if ($participants_num <= pow(2,$i)) {
                $ro = $i;
            }
            $i++;
        }
        return $ro;
    }

    function generateBracket($tourn_id) {
        $participants_num = $this->getTournamentRegisteredNum($tourn_id);
        $ro = $this->determineNumberOfRounds($participants_num);
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

    function addBo($tourn_id, $round, $bo, $map) {
        return $this->add(array('table' => 'bo_tournament', 'fields' => array(':tourn_id' => $tourn_id,
            ':ro' => $round, ':bo' => $bo, ':map' => $map)));
    }

    //This is a dirty hack. I'm sorry future self, but I just didn't want to refactor EVERY piece of code that uses this
    // so, $ro will be false unless it's not.
    function getBoFromTournament($tourn_id, $ro = FALSE) {
        $query = "SELECT ro, bo, map from bo_tournament where tourn_id = :tourn_id ORDER by ro";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':tourn_id', $tourn_id);
        $queryPrepared->execute();
        $data = $queryPrepared->fetchAll();
        if (!$ro) {
          $ro = $this->determineNumberofRounds($this->getTournamentRegisteredNum($tourn_id));
        }
        $sentinel = true;

        /* the array by default returns 0 => all the data, 1 => all the data, etc.
         * I want it roundnum => all the data so I'm going through this algorithm
         */
        for ($i = 1; $i <= $ro; $i++ ) {
            foreach($data as $bo) {
                if($bo['ro'] == $i) {
                    $add = array('ro' => $bo['ro'], 'bo' => $bo['bo'], 'map' => $bo['map']);
                    $return[] = $add;
                    $sentinel = false;
                }
            }
            // If we don't see the round we want to assume that it's a b01
            if ($sentinel) {
                $add = array('ro' => $i, 'bo' => 1);
                $return[] = $add;
            }
            $sentinel = true;
        }
        return $return;
    }

    function seedBracket($tourn_id, $rounds, $overflow) { //TODO skip seeding
        $users = $this->getTournamentRegisteredList($tourn_id)['data'];
        $j = 0;

        //first we seed in players into round 1
        $tournament_bo = $this->getBoFromTournament($tourn_id);
        for ($i = $rounds; $i > 0; $i--) {
            for ($n = 0; $n < $tournament_bo[$i - 1]['bo']; $n++) {
              for ($k = 0; ($k < pow(2, $i - 1)); $k++) {
                    
                    $add = array('table' => 'matches', 'fields' => array(':in_tournament' => $tourn_id));
                    $this->add($add);
                    $add = array('table' => 'bracket', 'fields' => array(':match_id' =>
                      $this->getLastInsertId(), ':position' => $k, ':in_tournament' => $tourn_id, ':ro' => $i,
                        ':bo' => $tournament_bo[$i - 1]['bo'], ':game_num' => $n + 1));

                    // We only want to set the first map of the match, the default will handle the rest
                    if (isset($tournament_bo[$i - 1]['map']) && $n == 0) {
                      $add['fields'][':map'] = $tournament_bo[$i-1]['map'];
                    }
                    $this->add($add);
                }
            }
        }

        for ($i = 0; ($i < pow(2, $rounds - 1)); $i++) {
            $mid = $this->getMidFromBracketByPosition($tourn_id, $i, $rounds);
            $add = array('table' => 'matches', 'fields' => array(':player_1' => $users[$i]->getUID()));
            if ($j < $overflow || $overflow == 0) {
                $add['fields'][':player_2'] = $users[$j + pow(2, $rounds -1)]->getUID();
            }
            foreach ($mid as $match) {
                $add['where'] = array(':match_id' => $match);
                $this->update($add);
            }
            $j++;
        }
        //If there's overflow we set byes to -1. We must do this before generating remaining matches
        if ($overflow ) {
            $query = "UPDATE matches
        LEFT JOIN bracket
        ON matches.match_id = bracket.match_id
        SET player_2 = -1
        where matches.in_tournament = :tourn_id and matches.player_2 = 0
        AND bracket.ro = :rounds";
            $queryPrepared = $this->pdo->prepare($query);
            $queryPrepared->bindValue(':tourn_id', $tourn_id);
            $queryPrepared->bindValue(':rounds', $rounds);
            $queryPrepared->execute();
        }
        //next we create all remaining matches/bracket
        if ($overflow) {
            $this->processByes($tourn_id, $rounds);
        }
        $this->update(array('table' => 'tournaments', 'fields' => array(':started' => 1), 'where' => array(':id' => $tourn_id)));
        return array('status' => 0);
    }

    function reportGameWin($mid, $uid) {
        $this->update(array('table' => 'matches', 'fields' => array(':winner' => $uid), 'where' =>
        array(':match_id' => $mid)));
        return $this->advanceBracket($uid, $mid);
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

    function addTournament($name, $channel, $info) {
        $result = $this->add(array('table' => 'tournaments', 'fields' => array(':name' => $name, ':channel' => $channel,
            ':info' => $info)));
        $result['data'] = $this->getLastInsertId();
        return $result;
    }

    function getMidFromBracketByPosition($tourn_id, $position, $round) {
        $query = "SELECT match_id from bracket
      where in_tournament = :in_tournament and position = :position and ro = :round";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':in_tournament', $tourn_id);
        $queryPrepared->bindValue(':position', $position);
        $queryPrepared->bindValue(':round', $round);
        if(!$queryPrepared->execute())
            print_r($queryPrepared->errorInfo());
        if ($queryPrepared->rowCount() == 0) return false;
        $data = $queryPrepared->fetchAll();
        foreach ($data as $match) {
            $midarray[] = $match['match_id'];
        }
        return($midarray);
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
        if ($data['ro'] > 1) {
            $add = array ('table' => 'matches', 'fields' => array());
            if (($data['position'] / 2) > floor($data['position'] / 2)) {
                $add['fields'][':player_2'] = $uid;
            } else {
                $add['fields'][':player_1'] = $uid;
            }
            $midFromBrack = $this->getMidFromBracketByPosition($data['in_tournament'],
                floor($data['position']/2), $data['ro'] - 1);
            foreach ($midFromBrack as $match) {
                $add['where'] = array(':match_id' => $match);
                $this->update($add);
            }
            $sentinel = ($data['game_num'] == $data['bo']);
        } else {
            require_once('obj/tournaments/MatchSet.php');
            $midFromBrack = $this->getMidFromBracketByPosition($data['in_tournament'],
                $data['position'], $data['ro']);
            $match = new MatchSet($midFromBrack);
            $sentinel = $match->hasWinner();
        }
        if ($sentinel) {
            $tournQuery = "UPDATE tournaments SET current_round = :ro
        WHERE id = :tourn_id and current_round > :ro";
            $tournQueryPrepared = $this->pdo->prepare($tournQuery);
            $tournQueryPrepared->bindValue(':ro', $data['ro']-1);
            $tournQueryPrepared->bindValue(':tourn_id', $data['in_tournament']);
            if(!$tournQueryPrepared->execute())
                return array('status' => 1, 'message' => $tournQueryPrepared->errorInfo());
        }
        return (array('status' => 0));
    }

    function deleteTournament($tourn_id) {
        return $this->delete(array('table' => 'tournaments', 'fields' => array(':id' => $tourn_id)));
    }

    function addReplay($uid, $path) {
      $add = array('table' => 'replays', 'fields' => array(':submitter' => $uid, ':path' => $path));
      return $this->add($add);
    }

    function addMatchReplay($match_id, $uid, $replay) {
      $this->addReplay($uid, $replay);
      return ($this->update(array('table' => 'matches', 'fields' => array(':replay' => 
        $this->getLastInsertId()), 'where' => array(':match_id' => $match_id))));
    }

    function getReplay($rid) {
        $query = "SELECT submitter, path from replays where id = :rid";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':rid',  $rid);
        $queryPrepared->execute();
        return $queryPrepared->fetch();
    }


    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

    function getMapByName($name) {
        $query = "SELECT * from maps where name = :name";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':name', $name);
        $queryPrepared->execute();
        return $queryPrepared->fetch();
    }

    function getMap($map_id) {
        $query = "SELECT id, name, path from maps where id = :id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':id', $map_id);
        $queryPrepared->execute();
        return $queryPrepared->fetch();
    }

    function getMapByRoundGame($tourn_id, $round, $game) {
        $query = "SELECT map from bracket
      where in_tournament = :tourn_id AND ro = :round AND game_num = :game";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':tourn_id', $tourn_id);
        $queryPrepared->bindValue(':round', $round);
        $queryPrepared->bindValue(':game', $game);
        if(!$queryPrepared->execute()) print_r($queryPrepared->errorInfo());
        $result = $queryPrepared->fetch()['map'];
        return $this->getMap($result);
    }

    function getMapList() {
        $query = "SELECT id, name from maps";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->execute();
        $data = $queryPrepared->fetchAll();
        $return = array();
        $i = 0;
        foreach ($data as $row) {
            $return[$i]['name'] = $row['name'];
            $return[$i]['id'] = $row['id'];
            $i++;
        }
        return $return;
    }

    function updateBnetId($uid, $bid) {
        return $this->update(array('table' => 'user', 'fields' => array(':bnet_id' => $bid),
            'where' => array(':id' => $uid)));
    }

    function updateBnetLeague($uid, $rank) {
        return $this->update(array('table' => 'user', 'fields' => array(':bnet_league' => $rank), 'where' =>
        array(':id' => $uid)));
    }

    function updateBnetName($uid, $bname) {
        return $this->update(array('table' => 'user', 'fields' => array(':bnet_name' => $bname),
            'where' => array(':id' => $uid)));
    }

    function updateBnetCharCode($uid, $ccode) {
        return $this->update(array('table' => 'user', 'fields' => array(':char_code' => $ccode),
            'where' => array(':id' => $uid)));
    }

    function updateBnetUrl($uid, $url) {
        return $this->update(array('table' => 'user', 'fields' => array(':bnet_url' => $url),
            'where' => array(':id' => $uid)));
    }

    function getStream($uid) {
        $query = "SELECT title, description, twitch_user from streams where streamer = :uid";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':uid', $uid);
        $queryPrepared->execute();
        return $queryPrepared->fetch();
    }

}
?>
