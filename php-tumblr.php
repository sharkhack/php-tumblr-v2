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

require_once('OAuth.php');

class Tumblr{

	  public $isResponseSuccess = false;

	  public $apiRootUrl = "http://www.tumblr.com/api/";
	 
	  public $responseFormat = 'json';
	  public $isJsonDecode = true;
	 
	  private $accessTokenURL='http://www.tumblr.com/oauth/access_token';
	  private $authenticateURL= 'http://www.tumblr.com/oauth/authorize'; 
	  private $authorizeURL= 'http://www.tumblr.com/oauth/authorize';
	  private $requestTokenURL='http://www.tumblr.com/oauth/request_token';
	  
	  public function __construct(
	  			$consumer_key, 
	  			$consumer_secret, 
	  			$oauth_token = NULL, 
	  			$oauth_token_secret = NULL
	  			) {
	    $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
	    $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
	    if (!empty($oauth_token) && !empty($oauth_token_secret)) {
	      $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
	    } else {
	      $this->token = NULL;
	    }
	  }

	
	  public function getRequestToken($oauth_callback = NULL) {
	    $parameters = array();
	    if (!empty($oauth_callback)) {
	      $parameters['oauth_callback'] = $oauth_callback;
	    } 
	    $request = $this->oAuthRequest($this->requestTokenURL, 'GET', $parameters);
	    $token = OAuthUtil::parse_parameters($request);
	    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
	    return $token;
	  }
  
	  public function getAuthorizeURL($token, $sign_in_with_tumblr = TRUE) {
	    if (is_array($token)) {
	      $token = $token['oauth_token'];
	    }
	    if (empty($sign_in_with_tumblr)) {
	      return $this->authorizeURL . "?oauth_token={$token}";
	    } else {
	       return $this->authenticateURL . "?oauth_token={$token}";
	    }
	  }


	  public function getAccessToken($oauth_verifier = FALSE) {
	    $parameters = array();
	    if (!empty($oauth_verifier)) {
	      $parameters['oauth_verifier'] = $oauth_verifier;
	    }

	    $request = $this->oAuthRequest($this->accessTokenURL, 'GET', $parameters);

	    $token = OAuthUtil::parse_parameters($request);

	    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
	    return $token;
	  }
	  
	  public function tGet($url, $parameters = array()) {
	    $response = $this->oAuthRequest($url, 'GET', $parameters);
	    if ($this->responseFormat === 'json' && $this->isJsonDecode==true) {
	      return json_decode($response,true);
	    }
	    return $response;
	  }
	  
	  public function tPost($url, $parameters = array()) {
	    $response = $this->oAuthRequest($url, 'POST', $parameters);
	    if ($this->responseFormat === 'json' && $this->isJsonDecode==true) {
	      return json_decode($response,true);
	    }
	    return $response;
	  }


	  public function oAuthRequest($url, $method, $parameters) {
	    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
			if($this->responseFormat == 'xml'){
		      $url = "{$this->apiRootUrl}{$url}";
			}
			else{
				$url = "{$this->apiRootUrl}{$url}/{$this->responseFormat}";
			}
	    }
	    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
	    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
	    switch ($method) {
	    case 'GET':
	      return $this->doCall($request->to_url(), 'GET');
	    default:
	      return $this->doCall($request->get_normalized_http_url(), $method, $request->to_postdata());
	    }
	  }

	  
	  private function doCall($url, $method, $postfields = NULL) {
	    $this->http_info = array();
	    $c = curl_init();
	    curl_setopt($c, CURLOPT_USERAGENT, 'Tumblr v2');
	    curl_setopt($c, CURLOPT_CONNECTTIMEOUT,60);
	    curl_setopt($c, CURLOPT_TIMEOUT, 60);
	    curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($c, CURLOPT_HTTPHEADER, array('Expect:'));
	    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($c, CURLOPT_HEADERFUNCTION, array($this, 'getHeaderForCurl'));
	    curl_setopt($c, CURLOPT_HEADER, FALSE);

	    if ($method == 'POST') {
	        curl_setopt($c, CURLOPT_POST, TRUE);
		if (!empty($postfields)) {
		  curl_setopt($c, CURLOPT_POSTFIELDS, $postfields);
		}
	    }

	    curl_setopt($c, CURLOPT_URL, $url);
	    $response = curl_exec($c);
	    
	    //http response code == 200
	    $this->isResponseSuccess = (curl_getinfo($c, CURLINFO_HTTP_CODE)=='200')?true:false;
	    
	    curl_close ($c);
	    return $response;
	  }

	  private function getHeaderForCurl($ch, $header) {
	    $i = strpos($header, ':');
	    if (!empty($i)) {
	      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
	      $value = trim(substr($header, $i + 2));
	      $this->http_header[$key] = $value;
	    }
	    return strlen($header);
	  }
}