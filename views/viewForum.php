<?php
require_once('obj/Forum.php');
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
    <a href="#" class="btn btn-danger pull-right">New Topic</a>
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
            <img class ="pull-right" src="assets/profile/uid_0.gif" /> <!-- todo profile sec -->
          </div>
        </div>
        <a class="btn btn-info" href="index.php?page=viewTopic.php&tid=<?php echo $topic->getTID();?>">View Thread</a>
      </div>
    </div>
  </div>
<?php } ?>
</div>


