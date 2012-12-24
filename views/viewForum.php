<?php
require_once('obj/Forum.php');
require_once('fragments/userBox.php');
//TODO do pagenum right
$pageNum = 0;
$topicsPerPage = 25;
$forum = new Forum($_GET['fid']);
$topicList = $forum->getTopics($pageNum, $topicsPerPage);
?>

<div class="row-fluid">
  <div class="span10">
    <h3><?php echo $forum->getName(); ?></h3>
  </div>
  <div class="span2">
    <a href="#topicCreatePopup" role="button" data-toggle="modal" class="btn btn-danger pull-right">New Topic</a>
  </div>
</div>
</div>

<div class="accordion" id="topicList">
<?php foreach($topicList as $topic) {
?>
  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="row-fluid">
        <div class="span10">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="topicList" href="#tid<?php echo $topic->getTID(); ?>">
            <?php echo $topic->getSubject(); ?>
          </a>
        </div>
        <div class="span2">
          <p><?php echo $topic->getAuthor(); ?></p>
        </div>
      </div>
    </div>
    <div id="tid<?php echo $topic->getTID(); ?>" class="accordion-body collapse">
      <div class="accordion-inner">
        <div class="row-fluid">
          <div class="span8">
<?php echo "<h4>".$topic->getSubject() ."</h4>";
echo $topic->getMessage();?>
          </div>
          <div class="span4">
            <div class="userBox pull-right">
              <?php $userBox = new UserBox($topic->getAuthorUID());
                print $userBox->getBox();
              ?>
            </div>
          </div>
        </div>
        <a class="btn btn-info" href="index.php?page=viewTopic.php&tid=<?php echo $topic->getTID();?>">View Thread</a>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<!-- modal new topic button -->
<div class="modal hide fade" role="dialog" tabindex="-1" id="topicCreatePopup" aria-labelledby="topicCreatePopupLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="topicCreatePopupLabel">Topic Reply</h3>
  </div>
  <div class="modal-body">
    <form action="javascript:newTopic();" style="text-align:center">
    <input id="topicSubject" class="input-xxlarge" type="text" placeholder="Topic Subject..."><br>
      <textarea id="topicMessage" class="input-xxlarge" rows="5" placeholder="Message..."></textarea>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">Create Topic</button>
  </form>
  </div>
</div>
