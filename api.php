<?php
require_once('obj/db.php');
switch($_GET['type']) {
 	case 'post': require_once('api/post.php');
 		switch ($_GET['method']) {
 			case 'adminDeletePost': print json_encode(APIPost::adminDeletePost()); break;
 			case 'userDeletePost': print json_encode(APIPost::userDeletePost()); break;
 		} break;
 }
?>
