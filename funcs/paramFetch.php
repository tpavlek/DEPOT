<?php
function getFromGet($arg) {
  return (isset($_GET[$arg])) ? $_GET[$arg] : false;
}
function getFromPost($arg) {
  return (isset($_POST[$arg])) ? $_POST[$arg] : false;
}
/**
 * returns the given argument from from get or post
 * if no such argument exist, returns false
 */
function getFromGetPost($arg) {
  if (isset($_GET[$arg])) {
    return $_GET[$arg];
  } elseif (isset($_POST[$arg])) {
    return $_POST[$arg];
  } else {
    return false;
  }
}

/**
 * returns the given parameter from $_SESSION
 * if it does not exist, returns false
 */
function getFromSession($arg) {
  return isset($_SESSION[$arg]) ? $_SESSION[$arg] : false;
}
?>
