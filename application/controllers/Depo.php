<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depo extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'depo_model',
        ]);

        $this->load->library('form_validation');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Master Depo / Stock Point";
        $d['highlight_menu'] = "depo";
        $d['content_view'] = 'master/depo';
        
        if (!check_permission('master', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->depo_model->get();
            $this->load->view('layout/template', $d);
        }
	}

    public function create()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('code','Kode','required');
        $this->form_validation->set_rules('name','Nama','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $anggota_id = $this->depo_model->create($nd);
                if ($anggota_id) {
                    $data['success'] = 1;
                    $data['message'] = "Success Create New Data !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Failed Create New Data !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('anggota');
        }else{
            $d['title'] = "Tambah Depo Baru";
            $d['highlight_menu'] = "depo";
            $d['content_view'] = 'master/depo';
            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('name','Nama','required');
        $this->form_validation->set_rules('code','Kode','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $detail = $this->depo_model->detail($id);
                if ($detail) {
                    $nd["id"] = $id;

                    if ($this->depo_model->edit($nd)) {
                        $data['success'] = 1;
                        $data['message'] = "Success !";
                        redirect('anggota');
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Failed !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID !";
                }
            }

            return $data;
        }else{
            $d['title'] = "Ubah Depo";
            $d['highlight_menu'] = "depo";
            $d['content_view'] = 'master/depo';

            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->depo_model->detail($id);
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function delete($id) 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('master', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            if ($this->depo_model->delete($id)) {

                $data['success'] = 1;
                $data['message'] = "Success !";
                redirect('master/depo');
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed !";
            }
        }

        return $data;
    }

    private function get_input()
    {
        $data["code"] = $this->input->post('code');
        $data["name"] = $this->input->post('name');
        
        return $data;
    }

}
