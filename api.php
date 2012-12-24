<?php
require_once('/home/ebon/DEPOT/obj/db.php');
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
    case 'reply': print json_encode(APITopic::reply()); break;
    case 'getForums': print json_encode(APIForum::getForums()); break;
    case 'getTopicsInForumByPage': print json_encode(APIForum::getTopicsInForumByPage()); break;
    case 'getTopic': print json_encode(APIForum::getTopic()); break;
    } break;

  case 'register': require_once('api/register.php');
    switch($_GET['method']) {
    case 'registerUser': print json_encode(APIRegister::registerUser()); break;
    } break;
 }
?>
