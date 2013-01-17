<?php
require_once('obj/forum/Topic.php');
require_once('fragments/userBox.php');
require_once('fragments/postControl.php');
require_once('fragments/topicControl.php');
require_once('fragments/replayBox.php');
$topic = new Topic($_GET['tid']);
$pageNum = (isset($_GET['pageNum'])) ? $_GET['pageNum'] - 1 : 0;
$postsPerPage = 20; // TODO MAKE THIS LESS MAGIC
$replies = $topic->getReplies($pageNum, $postsPerPage);
?>
<div class="row-fluid">
  <div class="span6"> 
    <h4><?php echo $topic->getSubject(); ?> </h4>
  </div>
  <div class="span4">
    <div class="pagination">
      <ul>
        <li><a href="#">«</a></li>
        <?php $numPages = $topic->getTopicPages($postsPerPage); 
          for ($i=1; $i <= $numPages; $i++) {
            echo "<li><a href='index.php?page=viewTopic.php&tid=" . $topic->getTID() ."&pageNum=". $i ."'>" . $i . "</a></li>"; 
          }
         ?>
        <li><a href="#">»</a></li>
      </ul>
    </div>
  </div>
  <div class="span2">
    <a role="button" data-toggle="modal" href="#topicReplyPopup" class="btn btn-success pull-right">Reply</a>
  </div>
</div>
<div class="well topic" id="tid<?php echo $topic->getTID(); ?>">
  <div class="row-fluid">
    <div class="span8">
      <?php echo $topic->getMessage(); ?>
<?php if($topic->getReplay()) {
        $replayBox = new ReplayBox($topic->getReplay());
        print $replayBox->getBox();
   }
      ?>
    </div>
    <div class="span4">
      <div class="userBox pull-right">
        <?php $userBox = new UserBox($topic->getAuthorUID());
          print $userBox->getBox();
?>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span8"></div>
    <div class="span4">
      <div class="topicBox pull-right">
<?php
            if ($page->permissions(array('admin')) || $topic->getAuthorUID() == $_SESSION['uid']) {
              $topicBox = new TopicControlBox($topic->getTID());
              print $topicBox->getBox();
            }
          ?>
        <!--
        <a role="button" data-toggle="modal" href="#postEditPopup" class="btn btn-caution" onclick="$(this).addClass('editing');">Edit</a>-->

      </div>
    </div>
  </div>
</div>
<!-- combine these two for pagination -->
<?php if ($topic->hasReplies()) { ?>
<div id="posts">
<?php foreach ($replies as $post) {
?><div class="well post" id="pid<?php echo $post->getPID(); ?>">
    <div class="row-fluid">
      <div class="span8">
        <?php echo $post->getMessage(); ?>
      </div>
      <div class="span4">
        <div class="userBox pull-right">
          <?php
            $userBox = new UserBox($post->getAuthorUID());
            print $userBox->getBox();
                      ?>
        </div>
      </div>
    </div>
    <div class='row-fluid'>
      <div class="span8">
      </div>
      <div class="span4">
        <div class='postBox pull-right' >
          <?php
            if ($page->permissions(array('admin')) || $post->getAuthorUID() == $_SESSION['uid']) {
              $postBox = new PostControlBox($post->getPID());
              print $postBox->getBox();
            }
          ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
</div>
<?php } ?>
<iframe style="display:none;" name='submit-iframe' id='submit-iframe-dood' ></iframe>
<!-- modal reply button -->
<div class="modal hide fade" role="dialog" tabindex="-1" id="topicReplyPopup" aria-labelledby="topicReplyPopupLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="topicReplyPopupLabel">Topic Reply</h3>
  </div>
  <div class="modal-body">
  <form action="api.php?type=forum&method=reply&tid=<?php echo $_GET['tid']; ?>" style="text-align:center" target='submit-iframe' method="POST" enctype="multipart/form-data">
    <input name ="subject" id="replySubject" class="input-xxlarge" type="text" placeholder="Reply Subject..." value="RE: <?php echo $topic->getSubject(); ?>"><br>
      <textarea name="message" id="replyMessage" class="input-xxlarge" rows="5" placeholder="Reply..."></textarea>
      <div style="display:none; color:red;" class="error"></div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
    <button id="topicReplySubmitButton" type="submit" class="btn btn-success">Reply</button>
  </form>
  </div>
</div>

<!-- Modal edit buton-->
<?php
?>
<div class="modal hide fade" role="dialog" tabindex="-1" id="postEditPopup" aria-labelledby="postEditPopupLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="postEditPopupLabel">Post Edit</h3>
  </div>
  <div class="modal-body">
    <form action="api.php?type=forum&method=editPost&pid=" target="submit-iframe" style="text-align:center" method="POST">
    <input name="subject" id="replySubject" class="input-xxlarge" type="text" placeholder="Reply Subject..." value="<?php echo $post->getSubject(); ?>"><br>
    <textarea name="message" id="replyMessage" class="input-xxlarge" rows="5" placeholder="Reply..."><?php echo $post->getMessage(); ?></textarea>
      <div style="display:none; color:red;" class="error"></div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
    <button id="postEditSubmitButton" type="submit" class="btn btn-success">Save edit</button>
  </form>
  </div>
</div>
<script>
    $('#submit-iframe-dood').load(function() {
    //location.reload();
      var result = JSON.parse($('#submit-iframe-dood').contents().find('body').html());
      console.log(result.status);
    if (result.status) {
      $('.error').html(result.message).show('fast');
      $('#topicReplySubmitButton').removeClass('btn-success').addClass('btn-danger');
    } else {
      location.reload();
    }

  });
</script>
<script>
  console.log('This only works when the topic has at least one reply');
 /*   $('.postControlEdit').click(function() {
      var act = $('#postEditPopup form').attr('action') + $(this).closest('.post').attr('id').replace("pid","");
      $('#postEditPopup form').attr('action', act);
  });
  $('.postControlDelete').click(function() {
    deletePost($(this).closest('.post').attr('id').replace("pid",""));
});*/
    $('.topicControlDelete').click(function() {
    deleteTopic($(this).closest('.topic').attr('id').replace("tid",""));
    });
</script>             
