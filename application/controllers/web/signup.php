<?php
class signup extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('users');
	}
	
	function index()
	{
		// set form validation rules
		$this->form_validation->set_rules('username', 'Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
		//$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules("phone", "Phone Number", "trim|required|xss_clean|exact_length[10]|numeric");
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
		
		// submit
		if ($this->form_validation->run() == FALSE)
        {
			// fails
			$this->load->view('signup_view');
        }
		else
		{
			//insert user details into db
			$data = array(
				'username' => $this->input->post('username'),
				'phone' => $this->input->post('phone'),
				//'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
				'user_type' => 2,
				'created_at' => date('Y-m-d H:m:i')
			);
			
			if ($this->users->AddUser($data))
			{
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! Please login to access your Profile!</div>');
				redirect('user_signup');
			}
			else
			{
				// error
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Phone Number is already registered!!!</div>');
				redirect('user_signup');
			}
		}
	}
}