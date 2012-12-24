<?php
require_once('obj/forum/Topic.php');
require_once('fragments/userBox.php');
$topic = new Topic($_GET['tid']);
$pageNum = (isset($_GET['pageNum'])) ? $_GET['pageNum'] : 0;
$postsPerPage = 20; // TODO MAKE THIS LESS MAGIC
$replies = $topic->getReplies($pageNum, $postsPerPage);
?>
<div class="row-fluid">
  <div class="span10" id="tid<?php echo $topic->getTID(); ?>">
    <h4><?php echo $topic->getSubject(); ?> </h4>
  </div>
  <div class="span2">
    <a role="button" data-toggle="modal" href="#topicReplyPopup" class="btn btn-success pull-right">Reply</a>
  </div>
</div>
<div class="well">
  <div class="row-fluid">
    <div class="span8">
      <?php echo $topic->getMessage(); ?>
    </div>
    <div class="span4">
      <div class="userBox pull-right">
        <?php $userBox = new UserBox($topic->getAuthorUID());
          print $userBox->getBox();
        ?>
      </div>
    </div>
  </div>
</div>
<!-- combine these two for pagination -->
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
  </div>
<?php } ?>
</div>

<!-- modal reply button -->
<div class="modal hide fade" role="dialog" tabindex="-1" id="topicReplyPopup" aria-labelledby="topicReplyPopupLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="topicReplyPopupLabel">Topic Reply</h3>
  </div>
  <div class="modal-body">
    <form action="javascript:newReply();" style="text-align:center">
    <input id="replySubject" class="input-xxlarge" type="text" placeholder="Reply Subject..." value="RE: <?php echo $topic->getSubject(); ?>"><br>
      <textarea id="replyMessage" class="input-xxlarge" rows="5" placeholder="Reply..."></textarea>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">Reply</button>
  </form>
  </div>
</div>
