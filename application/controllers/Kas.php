<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'kas_model',
        ]);

        $this->load->library('form_validation');
    }

    public function index()
	{
        $d = $this->user_model->login_check();
        $d['content_view'] = 'kas/index';
        $role = isset($_GET['role'])? $_GET['role'] : null;
        $d['highlight_menu'] = "kas";
        $d['title'] = "Kas Koperasi";

        if (!check_permission('kas', $d['role'])){
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

    public function create()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('kas', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            
            $kas_id = $this->kas_model->create($nd);
            if ($kas_id) {
                $data['success'] = 1;
                $data['message'] = "Data berhasil tersimpan !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menyimpan data !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('kas');
	}


    public function edit()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('kas', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd = $this->get_input();
            
            $detail = $this->kas_model->detail($id);
            if ($detail) {
                $nd['id'] = $id;
                if ($this->kas_model->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid simpanan ID !";
            }
            
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('kas');
	}

    public function delete_ubah_simpanan() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('kas', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->kas_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('kas');
    }

}