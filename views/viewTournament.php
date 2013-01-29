<?php
require_once('obj/tournaments/Tournament.php');
require_once('obj/tournaments/Bracket.php');
require_once('fragments/Match.php');
$tourn_id = isset($_GET['tourn_id']) ? $_GET['tourn_id'] : 0;
$tournament = new Tournament($tourn_id);
$users = $tournament->getUserList(); 
$bracket = new Bracket($tourn_id);
?>
<?php if (!$tournament->hasStarted()) {
?>
<div class="unstarted-tournament">
  <div class="row-fluid">
    <div class="span8">
      <h2><?php echo $tournament->getName(); ?></h2>
    </div>
    <div class="span4">
      <form action="api.php?type=tournament&method=register&tourn_id=<?php echo $tourn_id; ?>" method="POST" onsubmit="return validateTournamentRegister()" target="submit-iframe">
        <div class="error" style="display:none;"></div>
      <input name="submit" type="submit" value="Join Tournament" class="btn-large btn-block btn btn-success pull-right">
      </form>
    </div>
  </div>
</div>
<div class="row-fluid">
  <div class="span5">
    <h3>Registered Users:</h3>
    <div class="well">
      <?php 
        if ($users['status']) {
          echo $users['message'];
        } else {
          foreach ($users['data'] as $user) {
            echo "<p><a class='btn btn-info' href=?page=userProfile.php&uid=". $user->getUID() .">" . $user->getBnetName() ."</a></p>";
          }
        }
      ?>
    </div>
  </div>
  <div class="span2">
  </div>
  <div class="span5">
    <h3>Information:</h3>
    <div class="well">
      <?php echo $tournament->getInfo(); ?>
    </div>
  </div>
</div>
<?php
if ($page->permissions(array('admin'))) {
?>
  <form action="api.php?type=tournament&method=generateBracket&tourn_id=<?php echo $tourn_id; ?>" method="POST" target="submit-iframe">
    <input type="submit" class="btn btn-warning" value="Generate Brackets" />
  </form>
<?php
  }
} ?> 
</div> <!-- End of the unstarted tournament -->   
<?php if ($tournament->hasStarted()) { ?>
<div class="row-fluid">
  <div class="span8">
    <h3><?php echo $tournament->getName();?> Bracket</h3>
  </div>
  <div class="span4">
    <a class="btn btn-success btn-block pull-right btn-large" href="?page=stream.php">Stream</a>
  </div>
</div>

<?php if ($page->permissions(array("loggedIn"))) {
  $user = new User($_SESSION['uid']);
  if ($user->isInMatch($tourn_id)) { ?>
    <div class="row-fluid">
      <div class="span12">
        <form action="api.php?type=tournament&method=uploadReplay" method="POST" enctype="multipart/form-data" target="submit-iframe">
          <input type="hidden" name="tourn_id" value='<?php echo $tourn_id; ?>' />
          <input name="tournament_replay_upload" type="file" style="display:none;" onchange="javascript:processReplayUpload()">
          <a class="btn-large btn-warning btn-block btn" name="tournament_replay_upload_button" onclick="$('[name=tournament_replay_upload]').click();">Upload Replay</a>
        </form>
      </div>
    </div>
    <p class="error"></p>
<?php
  }
}?>
<div class="row-fluid">
<?php for ($i = $tournament->getNumRounds(); $i > 0; $i--) {
  $brackety = $bracket->getBracket($i);
?>
  <div class="span3 matchColumn" id="ro<?php echo $i; ?>">
    <?php
      foreach ($brackety as $match) {
        ?><div class='match' id='mid<?php echo $match->getMID(); ?>'> <?php
        $box = new MatchBox($match);
        print $box->getBox();
        echo "</div>";
      }
    ?>
  </div>
<?php } ?>
</div>
<div class="progress progress-striped">
<div class="bar" style="width:<?php echo $tournament->getProgressAsPercent(); ?>%;"></div>
</div>
<!-- Modal match edit -->
<div class="modal hide fade" id="editMatchModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3>Edit Match</h3>
  </div>
  <div class="modal-body">
    <form class="form-inline" action="api.php?type=tournament&method=editMatch" target="submit-iframe" 
      method="POST">
      <input type="hidden" name="match_id" />
      <input type="hidden" name="tourn_id" value='<?php echo$_GET['tourn_id']; ?>'/>
      <input type="hidden" name="round" />
      <label class="radio inline">
        <input type="radio" name="player_move_num" value="1">Player 1
      </label>
      <label class="radio inline">
        <input type="radio" name="player_move_num" value="2">Player 2
      </label>
      <select name="match_move_num">
      </select>
      <label for="match_move_pos">Pos: </label>
      <label class="radio inline">
        <input type="radio" name="match_move_pos" value ="1" />1
      </label>
      <label class="radio inline">
        <input type="radio" name="match_move_pos" value="2" />2
      </label>
      <input type="submit" class="btn btn-success" value="Move" />
    </form>
      
  </div> 
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
  </div> 
</div>
<?php } ?>

<script>
function validateTournamentRegister() {
  return true; // TODO checks and balances
}

$('#submit-iframe-dood').load( function() {
  var result = JSON.parse($('#submit-iframe-dood').contents().find('body').html());
    if (result.status) {
      $('.error').html(result.message).show('fast');
      $('[name=tournament_replay_upload_button]').removeClass('btn-primary').addClass('btn-danger');
    } else {
      location.reload();
    }
});

$('[name=matchEditButton]').click(function() {
  var match_id = $(this).parents().closest('.match').attr('id').replace('mid', '');
  $('#editMatchModal').find('form:first').children('[name=match_id]').val(match_id);
  var round = $(this).parents().closest('.matchColumn').attr('id').replace('ro', '');
  $('#editMatchModal').find('form:first').children('[name=round]').val(round);
  var numMatch= $(this).parents().closest('.matchColumn').children('.match').size();
  for (var i = 0; i < numMatch; i++) {
    $('#editMatchModal').find('form:first').children('[name=match_move_num]').append(
      "<option value=" + i + ">Match " + i + "</option>");
  }
});

function processReplayUpload() {
  var search = $('input[type="file"]').val().search(".SC2Replay");
  if (search > 0) {
    $('.error').hide('fast');
    $('[name=tournament_replay_upload_button]').removeClass('btn-danger').addClass('btn-success').html("Uploading").parent().submit();
  } else {
    $('[name=tournament_replay_upload_button]').removeClass('btn-warning btn-success').addClass('btn-danger').html("Try Again");
    $('.error').show('fast').html("That doesn't look like an SC2Replay to me");
  }
}
</script>
