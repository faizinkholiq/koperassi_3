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
            $d['summary'] = $this->kas_model->get_summary();
            $this->load->view('layout/template', $d);
        }
	}

    public function get_data(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        
        $params["role"] = $this->input->post("role");

        $data = $this->kas_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    private function get_input()
    {
        $data["date"] = date('Y-m-d');
        $data["year"] = $this->input->post('year');
        $data["debet"] = $this->input->post('debet');
        $data["kredit"] = $this->input->post('kredit');
        $data["total"] = $this->input->post('debet');

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
            $nd['updated_by'] = $d['person_id'];
            
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

    public function delete() 
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