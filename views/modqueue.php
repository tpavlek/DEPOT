<?php
$listofAcronyms = $page->getDB()->getUnapprovedAcronymsByPage(0, 10);

foreach ($listofAcronyms as $ac) {
  $str = "
    <form class='form-horizontal modItem' action='api.php' method='POST'>
      <div class='control-group'>
        <label class='control-label'>" . $ac['fgt'] . "</label>
        <div class='controls'>
          <input type='hidden' name='id' value='" . $ac['id'] . "' />
          <input type='hidden' name='appval' />
          <input type='submit' value='Approve' name='approve' class='btn btn-success' />
          <input type='submit' value='Deny' name='deny' class='btn btn-danger' />
        </div>
      </div>
    </form>
    ";
  print $str;
}

?>

<script>

$('.modItem').submit(modItem);

$('input[name=approve]').click(function(event) {
  var target = event.target;
  $(target).siblings('input[name=appval]').val(1);
});

$('input[name=deny]').click(function(event) {
  var target = event.target;
  $(target).siblings('input[name=appval]').val(0);
});
  
function modItem(event) {
  var target = event.target;
  var id = $(target).find('input[name=id]').val();
  var appval = $(target).find('input[name=appval]').val();
  $.ajax({
    type:"POST",
    url: "api.php?type=misc&method=modAcronym",
    dataType: "json",
    data: {id: id, approval: appval},
    success: function(data) { $(target).hide('fast');  }
    });
  return false;
}
</script>

