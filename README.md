php-tumblr-v2
=============

Tumblr API v2 for PHP

1. Register your app at http://www.tumblr.com/oauth/apps

2. Copy your OAuth consumer key, secret key and callback URL into config.php.

3. Put these files in a web-accessible location, e.g. http://localhost/php-tumblr-v2/
   (Your callback URL then might be: http://localhost/php-tumblr-v2/callback.php)

4. Visit /login.php to authenticate and authorize your app. The OAuth token and
   OAuth token secret will be returned and stored in $_SESSION.

   These are the two variables to store against this user in the database to allow
   repeatable connections to Tumblr in the future.

5. Finally, head to /test-user-info.php and it should post an example photo to your
   first-listed Tumblr blog.

Tumblr: http://www.tumblr.com/docs/en/api/v2
Tumblr OAuth: http://www.tumblr.com/oauth/apps