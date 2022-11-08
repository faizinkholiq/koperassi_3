<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Data User";
        $d['highlight_menu'] = "user";
        $d['content_view'] = 'user/index';

        if (!check_permission('user', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
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

    public function get_data()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('user', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $data = $this->user_model->get();
        }
        
        echo json_encode($data);
	}

    public function create()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('user', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $user_id = $this->user_model->create($nd);
                if ($user_id) {

                    $data['success'] = 1;
                    $data['message'] = "Success !";
                    redirect('user');
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Failed !";
                }
            }

            // return $data;
        }else{
            $d['title'] = "Tambah User Baru";
            $d['highlight_menu'] = "user";
            $d['content_view'] = 'user/input';
    
            if (!check_permission('user', $d['role'])){
                redirect('home');
            }else{
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('user', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $detail = $this->user_model->detail($id);
                if ($detail) {
                    $nd["id"] = $id;
                    if ($this->user_model->edit($nd)) {
    
                        $data['success'] = 1;
                        $data['message'] = "Success !";
                        redirect('user');
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Failed !";
                        print_r($data);
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID !";
                    print_r($data);
                }
            }

            return $data;
        }else{
            $d['title'] = "Ubah User";
            $d['highlight_menu'] = "user";
            $d['content_view'] = 'user/input';

            if (!check_permission('user', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->user_model->detail($id);
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function delete($id) 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('user', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            if ($this->user_model->delete($id)) {

                $data['success'] = 1;
                $data['message'] = "Success !";
                redirect('user');
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed !";
            }
        }

        return $data;
    }

    private function get_input()
    {
        $data["username"] = $this->input->post('username');
        $data["password"] = $this->input->post('password');
        $data["name"] = $this->input->post('name');

        return $data;
    }

    public function settings()
    {
        $d = $this->user_model->login_check();
        $d['title'] = "Ubah Data Akun";
        $d['highlight_menu'] = "user_settings";
        $d['content_view'] = 'user/settings';

        if (!check_permission('settings', $d['role'])){
            redirect('home');
        }else{
            $id = $this->session->userdata('sess_data')['id'];
            $d["data"] = $this->user_model->detail($id);

            $this->load->view('layout/template', $d);
        }
    }


}
