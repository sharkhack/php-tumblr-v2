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

if(isset($_SESSION['tumblr_oauth_token']) && isset($_SESSION['tumblr_oauth_token_secret']) && isset($_SESSION['tumblr_used_blog_name']) && !empty($_FILES)){

	$params = array("data" => array(file_get_contents($_FILES['img_file']['tmp_name'])), "type" => "photo","caption"=>'image caption');

	$tumblr = new TumblrOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['tumblr_oauth_token'], $_SESSION['tumblr_oauth_token_secret']);

	$yourblogname = $_SESSION['tumblr_used_blog_name'];
	$yourblogname = substr($yourblogname,0,-1);
	$yourblogname = substr($yourblogname,7);
		
	// tumblr photo or content post url
	$uri = "http://api.tumblr.com/v2/blog/$yourblogname/post";

	// tumblr post image data
	$jsonToArray = $tumblr->tPost($uri,$params);
	print_r($jsonToArray);

} else {
	header('Location: login.php'); 
}

exit();
?>
