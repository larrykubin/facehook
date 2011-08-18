Facehook - CodeIgniter Hooks For Facebook
=========================================

Facehook is a set of hooks for CodeIgniter that makes it easy to require facebook login to access a controller function. It was developed against CodeIgniter 2 and the Facebook PHP SDK 3.0.

Set up your config in application/config/config.php

Dependencies
------------

Get a copy of the facebook php sdk and put it in libraries, or use the one in this package
Create a route that maps to your logout controller. A sample logout controller is included here.

Most of the settings are self explanatory. The app ID and secret are provided by facebook when you create a new facebook application. The fb_scope is a list of permissions that you want to ask the user for when they log in with facebook. A complete list of Facebook Graph API permissions can be found at the URL below:

http://developers.facebook.com/docs/reference/api/permissions/

The fb_login_url_tag and fb_logout_url_tag are placeholders that you put in your views. The display_override hook injects the facebook login and logout URLs into the view wherever you put these placeholders. The fb_logout_action is where the user is redirected after they are logged out of facebook. You can put any cleanup or logout code here. For instance, you might want to destroy some session variables. Make sure there is a corresponding route defined in your config/routes.php that maps this route to your logout method. In the sample below, my users/logout was routed to a controller called Users with a method called logout(). Easy.

*Sample Configuration*

```php
$config['fb_app_id'] = 'abcdefgh';
$config['fb_secret'] = '12345678';
$config['fb_scope']  = 'email,user_birthday,user_photo_video_tags';
$config['fb_login_url_tag']  = '{fb_login_url}';
$config['fb_logout_url_tag'] = '{fb_logout_url}';
$config['fb_logout_action']  = 'users/logout';
```