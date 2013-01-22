
<?php
if (!$page->permissions(array("loggedIn"))) { echo "<script>window.location = 'index.php';</script>";} //TODO ACTUALLY DO SHIT;
require_once('obj/User.php');
$user = new User($_SESSION['uid']);

?>
<h3>Change Profile Pic</h3>
<p>Mustbe 100px by 100px or less</p>
<iframe name='submit-iframe' id='user-submit-iframe' style="display:none;"><body></body></iframe>
<form enctype='multipart/form-data' action="api.php?type=user&method=changePic" target="submit-iframe" method="POST">
<input type="file" style="display:none;" name="profile_pic_upload" />
<div class="form-horizontal">
  <div class="control-group">
    <label class="control-label">Profile Pic:</label>
    <div class="controls">
      <a class="btn btn-primary" onclick="$('input[name=profile_pic_upload]').click();">Select File</a>
      <input type="text" disabled placeholder="Filename..." />
      <input type="submit" class="btn btn-success" value="Save" />
    </div>   
  </div>
</div>
</form>
<!-- TODO check for bad inputs before submit -->
<hr>
<form action="api.php?type=user&method=setBnet" method="POST" target="submit-iframe" class="form-horizontal" onsubmit="return validateMyForm();">
  <div class="control-group">
    <label class="control-label" for="bnet_id">Battle.net Profile URL:</label>
    <div class="controls">
      <input type="text" name="bnet_id" value="<?php echo $user->getBnetID(); ?>"/>
    </div>
    <br />
    <label class="control-label" for="char_code">Character Code</label>
    <div class="controls">
      <input type="text" name="char_code" value="<?php echo $user->getCharCode(); ?>"/>
      <input type="hidden" name="bnet_name" value="<?php echo $user->getBnetName();?>" />
      <input type="submit" class="btn btn-success" value="Save" />
    </div>
  </div>
</form>
<script>
function validateMyForm() {
  var idarr = $('input[name=bnet_id]').val().split("/");
  $('input[name=bnet_id]').val(idarr[6]);
  $('input[name=bnet_name]').val(idarr[8]);
  return true;
}
$('#user-submit-iframe').load(function() {
  location.reload(); //TODO errors
  });
</script>

