<?php
require_once('obj/page.php');
class APIStream {

    static function editStream() {
        $page = new Page();
        $db = $page->getDB();
        return $db->update(array('table' => 'streams', 'fields' => array(':title' => $_POST['title'], ':description' =>
            $_POST['description'], ':twitch_user' => $_POST['twitch_user']), 'where' => array(':streamer' =>
            $_POST['uid'])));
    }
}
?>