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

$tumblr = new Tumblr(CONSUMER_KEY, CONSUMER_SECRET);

$request_token = $tumblr->getRequestToken(OAUTH_CALLBACK);

$_SESSION['tumblr_oauth_token'] = $request_token['oauth_token'];
$_SESSION['tumblr_oauth_token_secret'] = $request_token['oauth_token_secret'];



if($tumblr->isResponseSuccess==true){
	echo('1');
	$tumblrAuthorizeUrl = $tumblr->getAuthorizeURL($request_token);

	header('Location: ' . $tumblrAuthorizeUrl); 
	echo '<script language="javascript">location.href = "'.$tumblrAuthorizeUrl.'";</script>';
} else {
	echo 'Error';
}

exit();
