<?php
require_once('obj/forum/Topic.php');

class TopicControlBox {

  public function __construct($tid) {
    $topic = new Topic($tid);
  }

  function getBox() {
    $str = "<div class='btn-toolbar'>
              <div class='btn-group'>
                <a class='btn topicControlEdit' href='#topicEditPopup' data-toggle='modal' role='button' disabled>
                  <i class='icon-pencil'></i>
                </a>
                <a class='btn topicControlDelete' role='button' ><i class='icon-trash'></i></a>
              </div>
            </div>
            ";
    return $str;
  }
}

?>

