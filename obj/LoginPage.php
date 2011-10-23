<?php
class LoginPage extends Page {

	public function __construct() {
	}

	function showForm() {
		$str = "
			<div class='formbox'>
			<form action='?page=login&method=auth' method='post'>
			<input type='submit' value='Login with Google Account' />
			</form>
			</div>
		";
		return $str;
	}

  function auth($args) {
    require_once 'includes/openid.php';
    try {
      $openid = new LightOpenID;
      if(!$openid->mode) {
          $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('contact/email');
						if ($args['method'] == 'registerUser') {
							$openid->returnUrl = $openid->realm . "?page=register&method=finishRegistration";
						}
            header('Location: ' . $openid->authUrl());
      } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
      } else {
				require_once('obj/sess.php');
				$arr = $openid->getAttributes('contact/email');
				$sess = new SessObj();
				if ($args['method'] == "loginUser") {
					$result = $sess->loginUser($arr['contact/email']);
				} elseif ($args['method'] == "registerUser") {
					$result = $sess->registerUser($arr['contact/email'], $args);
				}
				echo "<span class='big'>" . $result['message'] . "</span>";
				if(isset($_GET['dest'])) {
					header('Refresh: 3, url=' . $_GET['dest']);
				} else {
					header('Refresh: 3, url=index.php');
				}
				echo "<p><i>Sending you back to the page from whence you came...</i></p>";
	    }
    } catch(ErrorException $e) {
      echo $e->getMessage();
    }

  }
}
?>
