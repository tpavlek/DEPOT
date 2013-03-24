<?php
if (!$page->permissions(array("admin"))) { ?> 
  <script>
    window.location = "index.php"; 
    return;
  </script> 
<?php }

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
  $method = 'editTournament';
} else {
  $tourn_id = 0;
  $method = 'createTournament';
} 
require_once('obj/tournaments/Tournament.php');
require_once('obj/tournaments/Bracket.php');
$tournament = new Tournament($tourn_id);
$bracket = new Bracket($tourn_id);

?>

<h2>Tournament Administration</h2> 
<?php
//Docs for if the user is creating a tournament
if (!$tournament->getName()) {
?>
<p>Creating a tournament is a two step process. First, you fill out all the information here and
create a base tournament. After submitting, you must then fill out step two of the tournament
creation process which is setting the best-ofs for each round and the maps that each round is to be played on.
</p>
<?php
}

?>
  <form class="form-horizontal" action="api.php?type=tournament&method=<?php echo $method; ?>" method="POST" 
  target="submit-iframe">
  <input type="hidden" value=<?php echo $tourn_id?> name="tourn_id" />
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
  $bo = $bracket->getBo($i, $tournament->getNumRounds());
  $map = $bracket->getMap($i);
?> 
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#roundList" 
        href="#rd<?php echo $i;?>">Round <?php echo $i; ?> (bo<?php echo $bo;?>)</a>
    </div>
    <div class="accordion-body collapse in" id="rd<?php echo $i;?>">
      <div class="accordion-inner">
        <div class="control-group">
          <label class="control-label" for="rd_bo">Bo</label>
          <div class="controls">
            <input type="number" name="rd_bo[]" value="<?php echo $bo;?>"/>

          </div>
        </div>;
        <div class="control-group">
        <label class="control-label" for="map">First Map:</label>
          <div class="controls">
          <input type="hidden" class="maphidden" name="map_<?php echo $i; ?>" 
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


<?php 
// We only want to display if the tournament already exists
if ($tournament->getName()) { ?>
<form action="api.php?type=tournament&method=deleteTournament" method="POST" 
  target="submit-iframe">
  <input type="hidden" value="<?php echo $_GET['tourn_id'];?>" name="tourn_id" />
  <input type="submit" class="btn btn-danger" value="Delete Tournament" />
</form>
<?php } ?>

<?php
  /*I am SO sorry about this. I can't have the bindings happen on the creation start */ 
if ($tourn_id) { ?>
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
      var selector = $('#rd' + $('input[name=max_rounds]').val());
    if (selector.is(':visible')) {
        var num = parseInt($('input[name=max_rounds]').val().replace('#rd', '')) + 1;
        $('#rd' + num).parents('.accordion-group').remove();
      } else {
        $('#roundList').prepend(generateAccordionElement($('input[name=max_rounds]').val()));
        bindDropdownEvents();
      }


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

  bindDropdownEvents();
});

    function generateAccordionElement(round) {
      var str = "";
      str += "<div class='accordion-group'>\
                <div class='accordion-heading'>\
                  <a class='accordion-toggle' data-toggle='collapse' data-parent='#roundList'\
                    href='#rd" + round + "'>Round " + round + "</a>\
                </div>\
                <div class='accordion-body collapse' id='rd" + round + "'>\
                  <div class='accordion-inner'>\
                    <div class='control-group'>\
                      <label class='control-label' for='rd_bo'>Bo</label>\
                      <div class='controls'>\
                        <input type='number' min='1' max='987' name='rd_bo[]' value='1' step='2'/>\
                      </div>\
                    </div>\
                    <div class='control-group'>\
                      <label class='control-label' for='map'>First Map:</label>\
                      <div class='controls'>\
                        <input type='hidden' class='maphidden' name='map_" + round + "'>\
                        <div class='input-append'>\
                          <input type='text' name='map' />\
                          <div class='btn-group'>\
                          <button class='btn dropdown-toggle' data-toggle='dropdown' >\
                            <span class='caret'></span>\
                           </button>\
                          <ul class='dropdown-menu mapMenuDropdown'>\
                          </ul>\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
                </div>\
              </div>";
      return str;
    }
    function bindDropdownEvents() {
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
        mapMenu.show('fast').css("zIndex", 20);

        $('.mapOption').click(function(event) {
          var target = $(event.target);
          $(event.target).parents('.input-append').children('input').val(target.html());
          $(event.target).parents('.input-append').siblings('.maphidden').val(target.attr('id').replace('map', ''));
          $('.mapMenuDropdown').hide('fast');
        });

      });

      $('input[name=rd_bo]').change(function() {
        
      });
    }
</script>

<?php } ?>

<script>
$('#submit-iframe-dood').load(function() {
    var result = JSON.parse($('#submit-iframe-dood').contents().find('body').html());
    if (result.status) {
      $('.error').html(result.message).show('fast');
    } else {
      window.location = '?page=adminTournament.php&tourn_id=' + result.data;
    }

  });

</script>


