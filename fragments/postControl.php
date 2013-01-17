<?php
require_once('obj/forum/Post.php');

class PostControlBox {

  public function __construct($pid) {
    $post = new Post($pid);
  }

  function getBox() {
    $str = "<div class='btn-toolbar'>
              <div class='btn-group'>
                <a class='btn postControlEdit' href='#postEditPopup' data-toggle='modal' role='button'>
                  <i class='icon-pencil'></i>
                </a>
                <a class='btn postControlDelete' id='murphy' role='button' ><i class='icon-trash'></i></a>
              </div>
            </div>
            ";
    return $str;
  }
}

?>

