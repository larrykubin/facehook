<?
if (!function_exists('facebook_logged_in'))
{   
    function get_facebook_user_id()
    {
        $CI =& get_instance();
    	if( !$CI->session->userdata('fb_user_id') )
    		return false;
    	return $CI->session->userdata('fb_user_id');
    }
}