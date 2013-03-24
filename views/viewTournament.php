<?php
require_once('obj/tournaments/Tournament.php');
require_once('obj/tournaments/Bracket.php');
require_once('fragments/Match.php');
require_once('fragments/Bracket.php');
$tourn_id = isset($_GET['tourn_id']) ? $_GET['tourn_id'] : 1;
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
    <h3>Registered Users (<?php echo count($users);?>):</h3>
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
  <a class="btn btn-primary" href="?page=adminTournament.php&tourn_id=<?php echo $tourn_id;?>">Modify Tournament</a>
<?php
  }
} ?> 
</div> <!-- End of the unstarted tournament -->   


<?php if ($tournament->hasStarted()) { ?>
<div style="display:none;" id="tournamentNumRounds"><?php echo $tournament->getNumRounds(); ?></div>
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
  $match_id = $user->isInMatch($tourn_id);
  if ($match_id) { ?>
    <div class="row-fluid">
      <div class="span12">
          <a class="btn-large btn-warning btn-block btn" role="button" data-toggle="modal" href="#reportMatchModal">
            Report Match Result
          </a>
      </div>
    </div>
<?php
  }
}?>
<?php if ($tournament->getNumRounds() > 4) { ?>
<div class="row-fluid">
  <div class="span2"><p><button onclick="javascript:reverseBracket();" class="btn btn-info">Previous</button></p></div>
  <div class="span8"></div>
  <div class="span2"><p><button onclick="javascript:advanceBracket()" class="btn btn-info 
    pull-right">Next</button></p></div>
</div>
<?php } ?>

<div class="row-fluid">
<?php
  $end = ($tournament->getNumRounds() > 4) ? $tournament->getNumRounds() - 4 : 0;
  for ($i = $tournament->getNumRounds(); $i > $end; $i--) {
    $matchCol = new BracketRender($tourn_id, $i);
    print $matchCol->getBox();
  }
  ?>

</div>
<div class="progress progress-striped">
<div class="bar" style="width:<?php echo $tournament->getProgressAsPercent(); ?>%;"></div>
</div>

<!-- Match Report Modal -->
<?php if ($match_id) { 
  $match = new Match($match_id);
  $player1 = new User($match->getPlayer1());
  $player2 = new User($match->getPlayer2());
  ?>
<div class="modal hide fade" id="reportMatchModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3>Report Match</h3>
  </div>
  <div class="modal-body">
  <div class="error"></div>
  <form class="form-horizontal" action="api.php?type=tournament&method=reportResults" enctype="multipart/form-data" target="submit-iframe" method="POST">
    <div class="control-group">
      <label class="control-label" for="report_win">Winner: </label> 
        <div class="controls">
        <label class="radio inline">
          <input type="radio" name="report_win" value="<?php echo $player1->getUID();?>"><?php echo $player1->getBnetName(); ?>
        </label>
        <label class="radio inline">
          <input type="radio" name="report_win" value="<?php echo $player2->getUID(); ?>" checked><?php echo $player2->getBnetName(); ?>
        </label>
        </div>
    </div>
    <input type="hidden" name="tourn_id" value='<?php echo $tourn_id; ?>' />
    <div class="control-group">
      <label class="control-label" for="tournament_replay_upload">Replay:</label>
      <div class="controls">
        <input name="tournament_replay_upload" type="file" onchange="javascript:processReplayUpload()">
      </div>
    </div>

    <div class="control-group">
      <div class="controls">
        <input class="btn btn-success" type="submit" value="Confirm" />
      </div>
    </div>
  </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
  </div>
</div>

<?php } ?>


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
    <form action="api.php?type=tournament&method=reportWin" method="POST" target="submit-iframe"
      class="form-inline">
      <input type="hidden" name="match_id" />
      <label class="radio inline">
        <input type="radio" name="report_winner" checked value="1" />Player 1</input>
      </label>
      <label class="radio inline">
        <input type="radio" name="report_winner" value="2" />Player 2</input>
      </label>
      <input type="submit" value="Report" class="btn btn-success" />
     </form> 
    <div class="error"></div>
  </div> 
  <div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Close</button>
  </div> 
</div>
<?php } ?>

  <script>
  $(document).ready(function() {
    bindMatchButtonEvent();
  });

  $('.mapListPopover').popover();

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

function bindMatchButtonEvent() {
  $('[name=matchEditButton]').click(function() {
    var match_id = $(this).parents().closest('.match').attr('id').replace('mid', '');
    $('#editMatchModal').find('form:first').children('[name=match_id]').val(match_id);
    $('#editMatchModal').find('form:last').children('[name=match_id]').val(match_id);
    var round = $(this).parents().closest('.matchColumn').attr('id').replace('ro', '');
    $('#editMatchModal').find('form:first').children('[name=round]').val(round);
    var numMatch= $(this).parents().closest('.matchColumn').children('.match').size();
    for (var i = 0; i < numMatch; i++) {
      $('#editMatchModal').find('form:first').children('[name=match_move_num]').append(
        "<option value=" + i + ">Match " + i + "</option>");
    }
  });
}

function advanceBracket() {
  $('.mapListPopover').popover('hide');
  var num = parseInt($('body').find('.matchColumn:visible:first').attr('id').replace('ro', ""));
  if (num > 4) {
    for (var i = num; i > (num -3); i--) {
      $('#ro' + i).html($('#ro' + (i -1)).html());
    }
    arr = $('body').find('.matchColumn:visible').toArray();
    for (var i in arr) {
      var mynum = parseInt($(arr[i]).attr('id').replace('ro', ''));
      $(arr[i]).attr('id', 'ro' + (mynum-1));
  }
    $.ajax({
      type: "GET",
      url: "api.php?type=tournament&method=getBracket&ro="+(num-4)+"&tourn_id="+getURLParameter("tourn_id"),
      dataType: "json",
      data: "json",
      success: function(data) { addColumn(data); },
      error: function(jqxhr) {console.log(jqxhr); }

    });
  }

}

function reverseBracket() {
  $('.mapListPopover').popover('hide');
  var num = parseInt($('body').find('.matchColumn:visible:first').attr('id').replace('ro', ""));
  var totalRounds = $('#tournamentNumRounds').html();
  if (num < totalRounds) {
    for (var i = num -3; i < num; i++) {
      $('#ro' + i).html($('#ro' + (i+1)).html());
    }
    arr = $('body').find('.matchColumn:visible').toArray();
    for (var i in arr) {
      var mynum = parseInt($(arr[i]).attr('id').replace('ro', ''));
      $(arr[i]).attr('id', 'ro' + (mynum + 1));
    }
    $.ajax({
      type: "GET",
      url: "api.php?type=tournament&method=getBracket&ro="+(num+1)+"&tourn_id="+getURLParameter("tourn_id"),
      dataType:"json",
      data: "json",
      success: function(data) { removeColumn(data); },
      error: function(jqxhr) { console.log(jqxhr); }
    });
  }
}

function removeColumn(data) {
  var toAppend = "";
  for (var str in data) {
    toAppend += data[str];
  }
  var selector = $('.matchColumn:first').replaceWith(toAppend);
  $('.mapListPopover').popover();
}

function addColumn(data) {
  $('.mapListPopover').popover('hide');
  var toAppend = "";
  for (var str in data) {
    toAppend += data[str];
  }
  var selector = $('.matchColumn:last').replaceWith(toAppend);
  $('.mapListPopover').popover();
  bindMatchButtonEvent();
}

function processReplayUpload() {
  var search = $('input[type=file]').val().search(".SC2Replay");
  if (search > 0) {
    $('.error').hide('fast');
    $('input[type=submit]').removeClass('btn-danger').addClass('btn-success').val("Upload");
  } else {
    $('input[type=submit]').removeClass('btn-warning btn-success').addClass('btn-danger').val("Try Again");
    $('.error').show('fast').html("That doesn't look like an SC2Replay to me");
  }
}
</script>
