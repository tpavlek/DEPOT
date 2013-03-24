<div class='row-fluid'>
  <form class="form-horizontal" action="api.php?type=tournament&method=createTournament" target="submit-iframe"
    method="POST">
    <div class="control-group">
      <label class="control-label" for='name'>Name:</label>
      <div class="controls">
        <input type='text' name='name' />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for='channel'>Channel:</label>
      <div class="controls">
        <input type='text' name='channel' />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for='info'>Additional Info:</label>
      <div class="controls">
        <textarea rows=5 name='info'></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="max_rounds">Maximum Number of rounds</label>
      <div class="controls">
        <input type="number" name="max_rounds" step="1" min="1" max="10"
          value='1'/>
        <input type="text" name="max_participants" disabled width="10" value="2"/>
      </div>
    </div>
    <div class="accordion" id="roundList">
    </div>
    <input type="submit" class="btn btn-success" value="Save" />
  </form>
</div>
<div class="error"></div>

  <script>
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
                      <label class='control-label' for='rd" + round + "_bo'>Bo</label>\
                      <div class='controls'>\
                        <input type='number' min='1' max='987' name='rd" + round + "_bo' value='1' step='2'/>\
                      </div>\
                    </div>\
                    <div class='control-group'>\
                      <label class='control-label' for='map'>Map:</label>\
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

    $('input[name=max_rounds]').change(function() {
      $('input[name=max_participants]').val(Math.pow(2, $('input[name=max_rounds]').val()) + " participants");
      var selector = $('#rd' + $('input[name=max_rounds]').val());
      if (selector.is(':visible')) {
        var num = parseInt($('input[name=max_rounds]').val().replace('#rd', '')) + 1;
        $('#rd' + num).parents('.accordion-group').hide();
      } else {
        $('#roundList').append(generateAccordionElement($('input[name=max_rounds]').val()));
        bindDropdownEvents();
      }
    });

    $(document).ready(function() {
      $('#roundList').html(generateAccordionElement(1));
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
    }

    $('#submit-iframe-dood').load( function() {
      var result = JSON.parse($('#submit-iframe-dood').contents().find('body').html());
      if (result.status) {
        $('.error').html(result.message).show('fast');
      } else {
        location.reload();
      }
    });



  </script>

<?php




?>
