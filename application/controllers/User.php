<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
    }

    function login(){
		if ($this->session->userdata('sess_data') == TRUE) {
            redirect('home');
        }else{
            $nd['username'] = $this->input->post('username');
            $nd['password'] = $this->input->post('password');

			if($check = $this->user_model->check_user($nd)){
				$data_session = $this->user_model->detail($check);
				$this->session->set_userdata('sess_data', $data_session);

				redirect('home');
	
			}else{
				$this->session->set_flashdata('msg', 'Username / Password is wrong');
				$this->load->view('login');
			}
        }
    }

    public function logout()
	{
		if ($this->session->userdata('sess_data') == TRUE) {
            $this->session->sess_destroy();
        }

        redirect('user/login');
	}

}
