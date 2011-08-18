<?
class Users extends CI_Controller
{
	public function logout()
	{
		$fb_app_id = $this->config->item('fb_app_id');
		unset($_SESSION['fb_' . $fb_app_id . '_user_id']);
		unset($_SESSION['fb_' . $fb_app_id . '_code']);
		unset($_SESSION['fb_' . $fb_app_id . '_state']);
        $this->session->sess_destroy();
        redirect(base_url());
	}
}