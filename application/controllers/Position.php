<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'position_model',
        ]);

        $this->load->library('form_validation');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Master Jabatan";
        $d['highlight_menu'] = "position";
        $d['content_view'] = 'position/index';
        
        if (!check_permission('master', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->position_model->get();
            $this->load->view('layout/template', $d);
        }
	}

    public function create()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('name','Nama','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $position_id = $this->position_model->create($nd);
                if ($position_id) {
                    $data['success'] = 1;
                    $data['message'] = "Jabatan berhasil disimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Jabatan gagal disimpan !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('position');
        }else{
            $d['title'] = "Tambah Jabatan Baru";
            $d['highlight_menu'] = "position";
            $d['content_view'] = 'position/input';
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

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $detail = $this->position_model->detail($id);
                if ($detail) {
                    $nd["id"] = $id;

                    if ($this->position_model->edit($nd)) {
                        $data['success'] = 1;
                        $data['message'] = "Jabatan berhasil diubah !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Jabatan gagal diubah !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID jabatan !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('position');
        }else{
            $d['title'] = "Ubah Jabatan";
            $d['highlight_menu'] = "position";
            $d['content_view'] = 'position/input';

            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->position_model->detail($id);
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
            if ($this->position_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus jabatan !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus jabatan !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('position');
    }

    private function get_input()
    {
        $data["name"] = $this->input->post('name');
        
        return $data;
    }

}
