
<?

?>
<h3>Change Profile Pic</h3>
<iframe name='submit-iframe' id='user-submit-iframe' style="display:none" ><body></body></iframe>
<form enctype='multipart/form-data' action="api.php?type=user&method=changePic" target="submit-iframe" method="POST">
<input type="file" name="profile_pic_upload" onchange="this.form.submit()"/>
</form>
<script>
$('#user-submit-iframe').load(function() {
  location.reload(); //TODO errors
  });
</script>
