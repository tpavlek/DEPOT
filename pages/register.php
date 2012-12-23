<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'includes/openid.php';
try {
    $openid = new LightOpenID;
    if(!$openid->mode) {
        if(isset($_GET['register'])) {
            
		$openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('contact/email');
		header('Location: ' . $openid->authUrl());
        }
?>
<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
	$arr=$openid->getAttributes('contact/email');
?>
<div class="formbox">
<form action="?page=useraction&register=1" method="POST">
<input type="hidden" name="email" value=<?php echo $arr['contact/email']; ?> />
<p><label for="username">Please choose a username: </label>
<input class="textinput" type="text" name="username" size="30"> </p>
<input type="submit" value="Finish Registration">
</form>
</div>
<?php } 
} catch(ErrorException $e) {
    echo $e->getMessage();
}
?>
<?php if (!isset($_GET['register'])) { ?>
<div class="formbox">
<form action="?page=register&register" method="post">
    <input type="submit" value="Register with Google Account">
</form>
</div>
<?php } ?>
