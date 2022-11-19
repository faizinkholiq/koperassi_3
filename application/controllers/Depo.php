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
        $d['content_view'] = 'depo/index';
        
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

                $depo_id = $this->depo_model->create($nd);
                if ($depo_id) {
                    $data['success'] = 1;
                    $data['message'] = "Depo berhasil disimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Depo gagal disimpan !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('depo');
        }else{
            $d['title'] = "Tambah Depo Baru";
            $d['highlight_menu'] = "depo";
            $d['content_view'] = 'depo/input';
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
                        $data['message'] = "Depo berhasil diubah !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Depo gagal diubah !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID depo !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('depo');
        }else{
            $d['title'] = "Ubah Depo";
            $d['highlight_menu'] = "depo";
            $d['content_view'] = 'depo/input';

            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->depo_model->detail($id);
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function delete() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('master', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->depo_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus depo !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus depo !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('depo');
    }

    private function get_input()
    {
        $data["code"] = $this->input->post('code');
        $data["name"] = $this->input->post('name');
        
        return $data;
    }

}
