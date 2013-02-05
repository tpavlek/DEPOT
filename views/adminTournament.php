<?php
if (!$page->permissions(array("admin"))) { ?> 
  <script>
    window.location = "index.php"; 
    return;
  </script> 
<?php }

if (isset($_GET['tourn_id'])) {
  require_once('obj/tournaments/Tournament.php');
  require_once('obj/tournaments/Bracket.php');
  $tournament = new Tournament($_GET['tourn_id']);
  $bracket = new Bracket($_GET['tourn_id']);
}
?>
  
<form class="form-horizontal" action="api.php?type=tournament&method=editTournament" method="POST" 
  target="submit-iframe">
  <div class="control-group">
    <label class="control-label" for="name">Tournament Name:</label>
    <div class="controls">
      <input type="text" name="name" value="<?php echo $tournament->getName(); ?>"/>
    </div>
  </div>
    <div class="control-group">
    <label class="control-label" for="max_rounds">Maximum Number of rounds</label>
    <div class="controls">
    <input type="number" name="max_rounds" step="1" min="1" max="10" 
      value='<?php echo $tournament->getNumRounds(); ?>'/>
      <input type="text" name="max_participants" disabled width="10"/>
    </div>
  </div>
  <div class="accordion" id="roundList">
<?php for ($i = $tournament->getNumRounds(); $i > 0; $i--) {
  $bo = $bracket->getBo($i);
?> 
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#roundList" 
        href="#rd<?php echo $i;?>">Round <?php echo $i; ?> (bo<?php echo $bo;?>)</a>
    </div>
    <div class="accordion-body collapse in" id="rd<?php echo $i;?>">
      <div class="accordion-inner">
        <div class="control-group">
          <label class="control-label" for="rd<?php echo $i;?>_bo">Bo</label>
          <div class="controls">
            <input type="number" name="rd<?php echo $i;?>_bo" value="<?php echo $bo;?>"/>

          </div>
        </div>
<?php for ($j = 1; $j <= $bo; $j++) { 
  $map = $bracket->getMap($i, $j);
?>
        <div class="control-group">
        <label class="control-label" for="map">Game <?php echo $j;?> Map:</label>
          <div class="controls">
            <input type="hidden" class="maphidden" name="mapgame<?php echo $j;?>" 
              value="<?php echo $map['id']; ?>"/>
            <div class="input-append">
            <input type="text" name="map" value="<?php echo $map['name'];?>" />
              <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown" > 
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu mapMenuDropdown">
                </ul>
              </div>
            </div>
          </div>
        </div>
<?php } ?>
      </div>
    </div>
  </div>
<?php
  }
?>
  </div>
  <div class="control-group">
    <label class="control-label" for="info">Tournament Info:</label>
    <div class="controls">
      <textarea name='info'><?php echo $tournament->getInfo(); ?> </textarea>
    </div>
  </div>
  
<input type="submit" class="btn btn-success" value="Save" />
</form>
<form action="api.php?type=tournament&method=deleteTournament" method="POST" 
  target="submit-iframe">
  <input type="hidden" value="<?php echo $_GET['tourn_id'];?>" name="tourn_id" />
  <input type="submit" class="btn btn-danger" value="Delete Tournament" />
</form>


<script>
$('input[name=map]').keydown(function(event) {
  var mapMenu = $(event.target).siblings('.btn-group').children('.mapMenuDropdown');
  mapMenu.html("");
    for (i in window.mapList) {
      if (window.mapList[i].name.toLowerCase().indexOf($(event.target).val().toLowerCase()) >= 0) {
        mapMenu.append("<li><a class='mapOption' id='map" 
          + window.mapList[i]['id'] + "'>" 
          + window.mapList[i].name + "</a></li>");
      }
    }
    mapMenu.show('fast');
    
    $('.mapOption').click(function(event) {
      var target = $(event.target);
      $(event.target).parents().closest('.input-append').children('[name=map]').val(target.html());
      $('.mapMenuDropdown').hide('fast');
    });

  });

  $('input[name=max_rounds]').change(function() {
    $('input[name=max_participants]').val(Math.pow(2, $('input[name=max_rounds]').val()) + " participants");
  });

  $(document).ready($('input[name=max_rounds]').change());

$(document).ready(function() {
  $.ajax({
    type: "GET",
    url: "api.php?type=tournament&method=getMapList",
    data: "json",
    success: function(data) {
      window.mapList = JSON.parse(data);
    },
    error: function(jqxhr) {
      console.log(jqxhr);
    }
  });
});
</script>


