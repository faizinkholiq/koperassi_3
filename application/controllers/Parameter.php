<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameter extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'parameter_model',
        ]);

        $this->load->library('form_validation');
    }

    public function index()
	{
        $d = $this->user_model->login_check();
        $d['content_view'] = 'parameter/index';
        $role = isset($_GET['role'])? $_GET['role'] : null;
        $d['highlight_menu'] = "parameter_sistem";
        $d['title'] = "Parameter Sistem";
        $d['data'] = $this->parameter_model->detail($d['person_id']);

        if (!check_permission('parameter_sistem', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    private function get_input()
    {
        $data["year"] = $this->input->post('year');
        $data["month"] = $this->input->post('month');

        return $data;
    }

    public function update($person)
	{
        $d = $this->user_model->login_check();
        if (!check_permission('parameter_sistem', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            $nd['person'] = $person;

            $detail = $this->parameter_model->detail($person);
            if ($detail) {
                if ($this->parameter_model->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                if ($this->parameter_model->create($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }
            
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('parameter');
	}

}