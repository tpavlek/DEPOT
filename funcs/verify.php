<?php
$allowed = "<i><b><br><strong><a>"; //TODO move this to config

function verifyString($str, $min, $max) {
	if (strlen(strip_tags($str), $allowed) > $max) return array('status' => 1, 'message' => 'too long. Maximum length: ' . $max);
	elseif (strlen(strip_tags($str)) < $min) return array('status' => 1, 'message' => 'too short. Minimum length: ' . $min);
	else return array('status' => 0);
}

function validateString($str) {
	return nl2br(strip_tags($str,$allowed));
}
?>
