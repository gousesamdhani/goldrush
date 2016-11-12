<?php
class login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','html'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('users');
	}
    public function index()
    {
		
		// form validation
		$this->form_validation->set_rules("phone", "Phone Number", "trim|required|xss_clean|exact_length[10]|numeric");
		$this->form_validation->set_rules("password", "Password", "trim|required|md5");
		
		if ($this->form_validation->run() == FALSE)
        {
			// validation fail
			$this->load->view('login_view');
		}
		else
		{
			// get form input
			$phone = $this->input->post("phone");
        	$password = $this->input->post("password");
        	
			// check for user credentials
			$uresult = $this->users->authentication(array('phone' => $phone, 'password' => $password));
			//print_r($uresult);

			if (count($uresult) > 0)
			{
				// set session
				$sess_data = array('login' => TRUE, 'username' => $uresult->username, 'id' => $uresult->id);
				$this->session->set_userdata($sess_data);
				redirect("profile");
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Wrong Phone or Password!</div>');
				redirect('user_login');
			}
		}
    }
}