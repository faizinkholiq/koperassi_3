<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'pinjaman_model',
            'person_model',
        ]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman";
        $d['highlight_menu'] = "pinjaman";
        $d['content_view'] = 'pinjaman/index';
        
        if (!check_permission('pinjaman', $d['role'])){
            redirect('home');
        }else{
            $d['summary'] = $this->pinjaman_model->summary($d['person_id']);
            $this->load->view('layout/template', $d);
        }
	}

    public function angsuran()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Angsuran";
        $d['highlight_menu'] = "angsuran";
        $d['content_view'] = 'pinjaman/angsuran';

        if (!check_permission('pinjaman', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    public function get_dt(){
        $d = $this->user_model->login_check();
        
        if($d['role'] != 1){
            $params['person'] = $d['nik'];
        }
        
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $data = $this->pinjaman_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    private function get_input()
    {
        $person_id = $this->input->post('person');
        $detail_person = $this->person_model->detail($person_id);
        $data = [];
        
        if ($detail_person) {
            $data["person"] = $detail_person['nik'];
            $data["date"] = $this->input->post('date');
            $data["year"] = $this->input->post('year');
            $data["month"] = $this->input->post('month');
            $data["balance"] = $this->input->post('balance');
            $data["angsuran"] = $this->input->post('angsuran');
        }

        return $data;
    }

    public function create()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            if(!$nd){
                $data['success'] = 0;
                $data['error'] = "Invalid Person !";
            }else{
                $nd['status'] = 'Pending';
                $simpanan_id = $this->pinjaman_model->create($nd);

                if ($simpanan_id) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function edit()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd = $this->get_input();
            $nd['status'] = 'Pending';

            $detail = $this->pinjaman->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function delete() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->pinjaman_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('pinjaman');
    }


    public function approve()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Approved';
            $detail = $this->pinjaman_model->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_model->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function reject()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Decline';
            $detail = $this->pinjaman_model->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_model->edit($nd)) {
                    
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

}
