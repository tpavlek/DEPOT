<?php
function verifyString($str, $min, $max) {
	if (strlen(strip_tags($str)) > $max) return array('status' => 1, 'message' => 'too long. Maximum length: ' . $max);
	elseif (strlen(strip_tags($str)) < $min) return array('status' => 1, 'message' => 'too short. Minimum length: ' . $min);
	else return array('status' => 0);
}

function validateString($str) {
	return strip_tags($str,"<i><b>");
}
?>
