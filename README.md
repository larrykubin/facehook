Facehook - CodeIgniter Hooks For Facebook
=========================================

Facehook is a set of hooks for CodeIgniter that makes it easy to require facebook login to access a controller function. It was developed against CodeIgniter 2 and the Facebook PHP SDK 3.0. It was developed for my first Facebook application and this was my first use of CodeIgniter, so I welcome suggestions on how to improve it.


Get Dependencies
----------------

CodeIgniter 2
http://www.codeigniter.com

You will obviously be using CodeIgniter since that is what these hooks are for. 

Facebook PHP SDK 3.0+
https://github.com/facebook/php-sdk

Once you have a CodeIgniter directory structure set up, clone the Facebook PHP SDK and put it in application/libraries, or use the one in this project if you don't need the latest and greatest.

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