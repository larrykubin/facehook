<?
class Facehook
{
    public function __construct()
    {
        $this->CI =& get_instance();

        $options = array(
            'appId'  => $this->CI->config->item('fb_app_id'), 
            'secret' => $this->CI->config->item('fb_secret')
        );

        $this->CI->load->library('facebook', $options, 'fb');
        $this->__build_urls();
    }

    public function check_login()
    {
        // try to get current facebook user, if they are already logged in, no action necessary
        $fb_user_id = $this->CI->fb->getUser();
        if( $fb_user_id ) 
        { 
            $this->CI->session->set_userdata('fb_user_id', $fb_user_id);

            try
            {
                $profile_fields = $this->CI->config->item('fb_profile_fields');
                $profile = $this->CI->fb->api('/me?fields=' . $profile_fields);
                $this->__handle_profile($profile);
            }
            catch (FacebookApiException $e) 
            {
                $profile = null;
            }

            return;
        }
        elseif( $this->CI->input->get('state') )
        {
                header('Location: '. $this->login_url);
        }

        // no actions of the current controller require facebook authentication, safe to return
        if( !isset($this->CI->require_fb) )
            return;

        // if developer is trying to use hook, make sure they are using it properly
        if( !is_array($this->CI->require_fb) )
        {
            show_error($this->CI->router->class . '->require_fb must be an array of methods');
        }

        // login isn't required for this method
        if( !in_array($this->CI->router->method, $this->CI->require_fb) ) 
            return;

        // facebook login is required and they aren't logged in, redirect to login url
        redirect($this->login_url);
    }

    public function inject_urls()
    {
        $output = $this->CI->output->get_output();

        $placeholders = array(
            $this->CI->config->item('fb_login_url_tag'),
            $this->CI->config->item('fb_logout_url_tag')
        );

        $replacements = array(
            $this->login_url, 
            $this->logout_url
        );

        $output = str_replace($placeholders, $replacements, $output);
        
        $this->CI->output->_display($output);
    }

    private function __build_urls()
    {
        // pass the necessary permissions needed for the app along with
        // the url that they are currently trying to access, so we can redirect back after they login
        $this->login_url = $this->CI->fb->getLoginUrl(array(
            'scope' => $this->CI->config->item('fb_scope'),
            'redirect_uri' => current_url()
        ));
        
        $this->logout_url = $this->CI->fb->getLogoutUrl(array(
            'next'=> site_url($this->CI->config->item('fb_logout_action'))
        ));
    }

    private function __handle_profile($profile)
    {
        // uncomment to dump profile data on the screen
        // print "<pre>";
        // print_r($profile);
        // print "</pre>";
        /*
        insert code for handling profile data here
        For instance, if you had a user model and you had a function for getting a user 
        from your users table usin their facebook user id, then you could do the following to 
        insert a new user and their profile data into your users table if they aren't already there.

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
        */
    }
}