Facehook - CodeIgniter Hooks For Facebook
=========================================

Facehook is a set of hooks for CodeIgniter that makes it easy to require facebook login to access a controller function. It was developed against CodeIgniter 2 and the Facebook PHP SDK 3.0. It was developed for my first Facebook application and this was my first use of CodeIgniter, so I welcome suggestions on how to improve it.


Dependencies
------------

*CodeIgniter 2 (http://www.codeigniter.com)*

You will obviously be using CodeIgniter since that is what these hooks are for. 

*Facebook PHP SDK 3.0+ (https://github.com/facebook/php-sdk)*

Once you have a CodeIgniter directory structure set up, clone the Facebook PHP SDK and put it in application/libraries, or use the one in this project if you don't need the latest and greatest.

Configuration
-------------

First, make sure hooks are enabled in your config/config.php file so that CodeIgniter can use hooks.

``` php
<?php
$config['enable_hooks'] = TRUE;
```
Also, make sure you are loading the URL helper. You probably already have it in your config/autoload.php, but just in case:

```
<?php
$autoload['helper'] = array('url');
```

Now that hooks are enabled, the Facebook hook needs to be loaded. Copy/paste the following into your config/hooks.php or just use the hooks.php in this repository if you aren't using any other hooks already.

``` php
<?php
$hook['post_controller_constructor'] = array(
	'class'    => 'Facehook',
	'function' => 'check_login',
	'filename' => 'Facehook.php',
	'filepath' => 'hooks',
	'params'   => ''
);

$hook['display_override'] = array(
	'class'    => 'Facehook',
	'function' => 'inject_urls',
	'filename' => 'Facehook.php',
	'filepath' => 'hooks',
	'params'   => ''
);
``` 

Then, you will need to add some configuration settings for your Facebook application. You can put these at the bottom of config.php.

*Sample Configuration*

``` php
<?php
$config['fb_app_id'] = 'abcdefgh';
$config['fb_secret'] = '12345678';
$config['fb_scope']  = 'email,user_birthday,user_photo_video_tags';
$config['fb_login_url_tag']  = '{fb_login_url}';
$config['fb_logout_url_tag'] = '{fb_logout_url}';
$config['fb_logout_action']  = 'users/logout';
```

The App ID and Secret are provided by Facebook when you create a new Facebook application. The fb_scope is a list of permissions that you want to ask the user for when they log in with Facebook. A complete list of Facebook Graph API permissions can be found at the URL below:

http://developers.facebook.com/docs/reference/api/permissions/

The fb_login_url_tag and fb_logout_url_tag are placeholders that you put in your views. The display_override hook injects the Facebook login and logout URLs into the view wherever you put these placeholders. The fb_logout_action is where the user is redirected after they are logged out of Facebook. You can put any cleanup or logout code here. For instance, you might want to destroy some session variables. Make sure there is a corresponding route defined in your config/routes.php that maps this route to your logout method. In the sample below, my users/logout was routed to a controller called Users with a method called logout(). Easy.

