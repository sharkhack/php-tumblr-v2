<?php
/*
 * php-tumblr-v2
 * https://github.com/sharkhack/
 *
 * Copyright (c) 2012 Azer Bulbul
 * Licensed under the MIT license.
 * https://github.com/sharkhack/iphp-tumblr-v2/LICENSE-MIT
 *Reference Tumblr API : http://www.tumblr.com/docs/en/api/v2
*/
session_start();

require_once('config.php');
require_once('php-tumblr.php');

if(isset($_SESSION['tumblr_oauth_token']) && isset($_SESSION['tumblr_oauth_token_secret']) && isset($_REQUEST['oauth_verifier'])){
	$tumblr = new TumblrOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['tumblr_oauth_token'], $_SESSION['tumblr_oauth_token_secret']);
	$access_token = $tumblr->getAccessToken($_REQUEST['oauth_verifier']);

	if($tumblr->isResponseSuccess==true){
		$_SESSION['tumblr_oauth_token'] = $access_token['oauth_token'];
		$_SESSION['tumblr_oauth_token_secret'] = $access_token['oauth_token_secret'];
	} else {
		header('Location: login.php');
	}
} else {
	header('Location: login.php'); 
}

exit();
?>
