<?php
require_once('obj/page.php');
require_once('obj/forum/Topic.php');
require_once('fragments/userBox.php');
$forumList = $page->forumList()->getForumList();
?>

<div class="accordion" id="forumList">
<?php foreach($forumList as $forum) {
?>
  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="row-fluid">
        <div class="span10">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="forumList" href="#fid<?php echo $forum->getFid(); ?>">
            <?php echo $forum->getName(); ?>
          </a>
        </div>
        <div class="span2">
        <a class="pull-right btn-mini btn-info" style="margin-top:5px;" href="index.php?page=viewForum.php&fid=<?php echo $forum->getFid(); ?>">View Forum</a>
        </div>
      </div>
    </div>
    <div id="fid<?php echo $forum->getFid(); ?>" class="accordion-body collapse">
      <div class="accordion-inner">
        <div class="row-fluid">
          <div class="span8">
<?php $topic = new Topic($forum->getLastTopicTid()); 
        echo "<h4>".$topic->getSubject() ."</h4>";
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



