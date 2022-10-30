<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'anggota_model',
        ]);

        $this->load->library('form_validation');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Data Anggota";
        $d['highlight_menu'] = "anggota";
        $d['content_view'] = 'anggota/index';
        
        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->anggota_model->get();
            $this->load->view('layout/template', $d);
        }
	}

    public function get_data()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $data = $this->anggota_model->get();
        }
        
        echo json_encode($data);
	}

    public function detail($id)
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Detail Anggota";
        $d['highlight_menu'] = "anggota";
        $d['content_view'] = 'anggota/detail';

        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $d["data"] = $this->anggota_model->detail($id);
            $this->load->view('layout/template', $d);
        }
	}

    public function create()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('nama','Nama','required');
        $this->form_validation->set_rules('nik','NIK','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('anggota', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $anggota_id = $this->anggota_model->create($nd["detail_anggota"]);
                if ($anggota_id) {
                    if (!empty($nd["family"]["name"])) {
                        $nd["family"]["person_id"] = $anggota_id;
                        $this->anggota_model->create_keluarga($nd["family"]);
                    }

                    $data['success'] = 1;
                    $data['message'] = "Success !";
                    redirect('anggota');
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Failed !";
                }
            }

            // return $data;
        }else{
            $d['title'] = "Tambah Anggota Baru";
            $d['highlight_menu'] = "anggota";
            $d['content_view'] = 'anggota/input';
    
            if (!check_permission('anggota', $d['role'])){
                redirect('home');
            }else{
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('nama','Nama','required');
        $this->form_validation->set_rules('nik','NIK','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('anggota', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $detail = $this->anggota_model->detail($id);
                if ($detail) {
                    $nd["detail_anggota"]["id"] = $id;
                    if ($this->anggota_model->edit($nd["detail_anggota"])) {
                        if (!empty($nd["family"]["name"])) {
                            $nd["family"]["person_id"] = $id;
                            if (!empty($detail["id_family"])) {
                                $nd["family"]["id"] = $detail["id_family"];
                                $this->anggota_model->edit_keluarga($nd["family"]);
                            }else{
                                $this->anggota_model->create_keluarga($nd["family"]);
                            }
                        }
    
                        $data['success'] = 1;
                        $data['message'] = "Success !";
                        redirect('anggota');
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
            $d['title'] = "Ubah Anggota";
            $d['highlight_menu'] = "anggota";
            $d['content_view'] = 'anggota/input';

            if (!check_permission('anggota', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->anggota_model->detail($id);
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function delete($id) 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            if ($this->anggota_model->delete($id)) {
                $this->anggota_model->delete_keluarga($id);

                $data['success'] = 1;
                $data['message'] = "Success !";
                redirect('anggota');
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed !";
            }
        }

        return $data;
    }

    private function get_input()
    {
        $data["detail_anggota"]["nik"] = $this->input->post('nik');
        $data["detail_anggota"]["tmk"] = $this->input->post('tmk');
        $data["detail_anggota"]["name"] = $this->input->post('nama');
        $data["detail_anggota"]["address"] = $this->input->post('alamat');
        $data["detail_anggota"]["phone"] = $this->input->post('no_telp');
        $data["detail_anggota"]["email"] = $this->input->post('email');
        $data["detail_anggota"]["join_date"] = $this->input->post('tgl_anggota');
        $data["detail_anggota"]["status"] = $this->input->post('status');

        $data["family"]["name"] = $this->input->post("nama_kel");
        $data["family"]["address"] = $this->input->post("alamat_kel");
        $data["family"]["phone"] = $this->input->post("no_telp_kel");
        $data["family"]["status"] = $this->input->post("status_kel");

        return $data;
    }

}
