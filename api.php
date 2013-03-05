<?php
require_once('obj/db.php');
switch($_GET['type']) {
 	case 'post': require_once('api/post.php');
 		switch ($_GET['method']) {
 			case 'adminDeletePost': print json_encode(APIPost::adminDeletePost()); break;
 			case 'userDeletePost': print json_encode(APIPost::userDeletePost()); break;
 			case 'loadNextPage': print json_encode(APIPost::loadNextPage()); break;
 		} break;
 	case 'topic': require_once('api/apitopic.php');
 		switch($_GET['method']) {
 			case 'adminDeleteTopic': print json_encode(APITopic::adminDeleteTopic()); break;
 		} break;
 	case 'sess': require_once('api/apisess.php');
 		switch($_GET['method']) {
    case 'addToSession': print json_encode(APISess::addToSession()); break;
    case 'logout': APISess::logout(); break;
    case 'loginUser': print json_encode(APISess::loginUser()); break;
    } break;

  case 'forum': require_once('api/forum.php'); require_once('api/apitopic.php');
    switch($_GET['method']) {
      case 'newTopic': print json_encode(APIForum::newTopic()); break;
      case 'reply': print json_encode(APITopic::reply()); break;
      case 'getForums': print json_encode(APIForum::getForums()); break;
      case 'getTopicsInForumByPage': print json_encode(APIForum::getTopicsInForumByPage()); break;
      case 'getTopic': print json_encode(APIForum::getTopic()); break;
      case 'editPost': print json_encode(APIForum::editPost()); break;
      case 'deletePost': print json_encode(APIForum::deletePost()); break;
      case 'deleteTopic': print json_encode(APIForum::deleteTopic()); break;
    } break;

    case 'stream': require_once('api/stream.php');
        switch($_GET['method']) {
            case 'editStream': print json_encode(APIStream::editStream()); break;
        } break;

  case 'tournament': require_once('api/tournament.php');
    switch($_GET['method']) {
      case 'register': print json_encode(APITournament::register()); break;
      case 'uploadReplay': print json_encode(APITournament::uploadReplay()); break;
      case 'generateBracket': print json_encode(APITournament::generateBracket()); break;
      case 'editMatch': print json_encode(APITournament::editMatch()); break;
      case 'getBracket': print json_encode(APITournament::getBracket()); break;
      case 'reportWin': print json_encode(APITournament::reportWin()); break;
      case 'getMapList': print json_encode(APITournament::getMapList()); break;
      case 'editTournament': print json_encode(APITournament::editTournament()); break;
      case 'deleteTournament': print json_encode(APITournament::deleteTournament()); break;
      case 'createTournament': print json_encode(APITournament::createTournament()); break;
    } break;

  case 'user': require_once('api/user.php'); 
    switch($_GET['method']) {
    case 'changePic': print json_encode(APIUser::changePic()); break;
    case 'setBnet': print json_encode(APIUser::setBnet()); break;
    } break;
  case 'register': require_once('api/register.php');
    switch($_GET['method']) {
    case 'registerUser': print json_encode(APIRegister::registerUser()); break;
    } break;
 }
?>
