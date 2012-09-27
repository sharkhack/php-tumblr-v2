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

if(isset($_SESSION['tumblr_oauth_token']) && isset($_SESSION['tumblr_oauth_token_secret'])){
	$tumblr = new Tumblr(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['tumblr_oauth_token'], $_SESSION['tumblr_oauth_token_secret']);

	// get user info
	$jsonToArray = $tumblr->tGet('http://api.tumblr.com/v2/user/info');
	print_r($jsonToArray);

	// setter your firstly blog name
	$cnt = count($jd['response']['user']['blogs']);
	if($cnt>0 && $jd['response']['user']['blogs'][0]['url']!=''){
		$_SESSION['tumblr_used_blog_name'] = $jd['response']['user']['blogs'][0]['url'];
	}

} else {
	header('Location: login.php'); 
}

exit();
?>
