<?php
require 'openid.php';
try {
    $openid = new LightOpenID('http://'. $_SERVER['HTTP_HOST']);
    if(!$openid->mode) {
             $openid->identity = 'https://www.google.com/accounts/o8/id';
             $openid->required = array('contact/email');
             header('Location: ' . $openid->authUrl());
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
      $arr = $openid->getAttributes();
?> 
      <html>
      <head>
      </head>
<body>
<div style="display:none" id='success'><?php echo $openid->validate();?></div>
<div style="display:none" id='email'><?php echo $arr['contact/email']; ?></div>
      <script>
      var obj = {success:document.getElementById('success').innerHTML, email:document.getElementById('email').innerHTML};
      window.opener.handleOpenIDResponse(obj); window.close();
</script>
      </body>
      <?php
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
?>
