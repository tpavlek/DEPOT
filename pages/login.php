<?php
require 'includes/openid.php';
try {
    $openid = new LightOpenID;
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('contact/email');
            header('Location: ' . $openid->authUrl());
        }
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
	$arr = $openid->getAttributes('contact/email');
        header('Location: ?page=useraction&login=1&email='.$arr['contact/email']);
	}
} catch(ErrorException $e) {
    echo $e->getMessage();
}

?>

<div class="formbox">
<form action="?page=login&login" method="post">
<input type="submit" value="Login with Google Account" />
</form>
</div>

