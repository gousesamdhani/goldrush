<?php
class home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'html'));
		$this->load->library('session');
	}
	
	function index()
	{
		if($this->session->userdata('login'))
			redirect('profile');
		else
			$this->load->view('home_view');
	}
	
	function logout()
	{
		// destroy session
        $data = array('login' => '', 'username' => '', 'id' => '');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
		redirect('user_login');
	}	
}


