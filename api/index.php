<?php

switch($_GET['type']) {
 	case 'post': require_once('post.php');
 		switch ($_GET['method']) {
 			case 'adminDeletePost': return json_encode(array("test" => "hello")); break;
 		} break;
}

?>
