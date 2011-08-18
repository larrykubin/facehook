<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */