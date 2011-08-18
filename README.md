Facehook - CodeIgniter Hooks For Facebook
=========================================

Facehook is a set of hooks for CodeIgniter 2 that makes it easy to do the following:

1. Lock down a controller method/action so that it can not be accessed unless the user is logged into Facebook. Facehook generates the appropriate code and URLs for your application. 
1. Request permission to access information about the authenticated Facebook user, such as their birthday, what music they like, and who their friends are. This is done using the Graph API.
1. Pass the profile information to a callback function. You might want to use this to store the profile data in your local database so that you don't have to make repeated calls to the Graph API. 

Dependencies
------------

*CodeIgniter 2 (http://www.codeigniter.com)*

You will obviously be using CodeIgniter since that is what these hooks are for. 

*Facebook PHP SDK 3.0+ (https://github.com/facebook/php-sdk)*

Once you have a CodeIgniter directory structure set up, clone the Facebook PHP SDK and put it in application/libraries, or use the one in this project if you don't need the latest and greatest.

Configuration
-------------

First, make sure hooks are enabled in your config/config.php file so that CodeIgniter can use hooks. Also, set the base_url of your app because it will be used to generate a redirect URL to pass to Facebook.

``` php
<?php
$config['base_url']	= 'http://facehook.dev/';
$config['enable_hooks'] = true;
?>
```
Also, make sure you are loading the URL helper and session library. You are probably doing this in your config/autoload.php, but just in case:

```
<?php
$autoload['helper'] = array('url');
$autoload['libraries'] = array('session');
?>
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
?>
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
?>
```

The App ID and Secret are provided by Facebook when you create a new Facebook application. The fb_scope is a list of permissions that you want to ask the user for when they log in with Facebook. A complete list of Facebook Graph API permissions can be found at the URL below:

http://developers.facebook.com/docs/reference/api/permissions/

The fb_login_url_tag and fb_logout_url_tag are placeholders that you put in your views. The display_override hook injects the Facebook login and logout URLs into the view wherever you put these placeholders. The fb_logout_action is where the user is redirected after they are logged out of Facebook. You can put any cleanup or logout code here. For instance, you might want to destroy some session variables. Make sure there is a corresponding route defined in your config/routes.php that maps this route to your logout method. In the sample below, my users/logout was routed to a controller called Users with a method called logout(). Easy.

*Handling Profile Data*

Facehook contains a function called handle_profile that is called after a user authenticates. It receives the user's profile data. I should probably change the hook to use call_user_func and allow you to pass in a function, but I didn't think about that until just now. Here is an example of handling profile data. It loads a user model and checks if the user is in the database by looking up their facebook user id. If they aren't in the database, it inserts them and sets the session variable to the last inserted user. If they are already in the user, it just sets the session variable to their existing user id.

``` php
<?php
function handle_profile($profile)
{
	$this->CI->load->model('user_model');
	$user = $this->CI->user_model->get_by('fb_user_id', $profile['id']);
	if( !$user )
	{
		$this->CI->user_model->insert(array(
			'fb_user_id' => $profile['id'],
			'email' => $profile['email']
		));
		
		$this->CI->session->set_userdata('user_id', $this->CI->db->insert_id());
	}
	else
	{
		$this->CI->session->set_userdata('user_id', $user->id);
	}
}
?>
```

*Requiring Facebook Login for a specific URL/function*

What we have shown above is all good for allowing the user to log in and out. But we probably want the ability to prohibit a user from accessing a URL unless they are already authenticated. To do this, define a variable in your class called $require_fb and set its value to an array of methods that you want locked down. If users hit any of these URL's, Facehook will know that it needs to direct them to the Facebook login URL first. Once they are logged in to Facebook, it will automatically redirect them to the URL they were trying to access. 

<?php
class Welcome extends CI_Controller {
	public $require_fb = array('secret');

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function secret()
	{
		$this->load->view('secret_message');
	}
}
?>