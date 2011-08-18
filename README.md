Facehook is a set of hooks for CodeIgniter that makes it easy to require facebook login to access a controller function. It was developed against CodeIgniter 2 and the Facebook PHP SDK 3.0.

Set up your config in application/config/config.php

edit settings for your app

Get a copy of the facebook php sdk and put it in libraries, or use the one in this package
Create a route that maps to your logout controller. A sample logout controller is included here.

Most of the settings are self explanatory. The app ID and secret are provided by facebook when you create a new facebook application, and fb_scope is a list of permissions that you want to ask the user for when they log in with facebook. A complete list of Facebook Graph API permissions can be found at the URL below:

http://developers.facebook.com/docs/reference/api/permissions/

* Sample Configuration *

$config['fb_app_id'] = 'abcdefgh';
$config['fb_secret'] = '12345678';
$config['fb_scope']  = 'email,user_birthday,user_photo_video_tags';
$config['fb_login_url_tag']  = '{fb_login_url}';
$config['fb_logout_url_tag'] = '{fb_logout_url}';
$config['fb_logout_action']  = 'users/logout';
