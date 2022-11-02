<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'user_model',
			'anggota_model',
			'simpanan_model',
			'simpanan_pokok_model',
			'simpanan_wajib_model',
			'simpanan_sukarela_model',
		]);

        $this->load->library('form_validation');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Simpanan";
		$d['highlight_menu'] = "simpanan";
		$d['content_view'] = 'simpanan/index';

		if (!check_permission('simpanan', $d['role'])){
            redirect('home');
        }else{
			$d['data']['summary'] = $this->simpanan_model->summary($d['person_id']);
			$d['data']['rows'] = $this->simpanan_model->get($d['person_id']);

			$this->load->view('layout/template', $d);
        }
	}

	
	public function page($module)
	{	
        $d = $this->user_model->login_check();
		
		if (!check_permission('simpanan_anggota', $d['role'])){
			redirect('home');
        }else{
			switch($module){
				case 'pokok':
					$d['title'] = "Simpanan Pokok";
					$d['content_view'] = 'simpanan/pokok';
					$d['highlight_menu'] = "simpanan_pokok";
					$d['data'] = $this->simpanan_pokok_model->get();
					break;
				case 'wajib':
					$d['title'] = "Simpanan Wajib";
					$d['content_view'] = 'simpanan/wajib';
					$d['highlight_menu'] = "simpanan_wajib";
					$d['data'] = $this->simpanan_wajib_model->get();
					break;
				case 'sukarela':
					$d['title'] = "Simpanan Sukarela";
					$d['content_view'] = 'simpanan/sukarela';
					$d['highlight_menu'] = "simpanan_sukarela";
					$d['data'] = $this->simpanan_sukarela_model->get();
					break;
			}

			$d['module'] = $module;
			
			// List Data
			$d['person_list'] = $this->anggota_model->list();

            $this->load->view('layout/template', $d);
        }
	}

	public function create($module)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('person','Anggota','required');
        $this->form_validation->set_rules('balance','Jumlah Simpanan','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('simpanan_anggota', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();
				
				switch ($module) {
					case 'pokok':
						$nd["code"] = $this->simpanan_pokok_model->get_code();
						$simpanan_id = $this->simpanan_pokok_model->create($nd);
						break;
					case 'wajib':
						$nd["code"] = $this->simpanan_wajib_model->get_code();
						$simpanan_id = $this->simpanan_wajib_model->create($nd);
						break;
					case 'sukarela':
						$nd["code"] = $this->simpanan_sukarela_model->get_code();
						$simpanan_id = $this->simpanan_sukarela_model->create($nd);
						break;
					default:
						$data['success'] = 0;
						$data['error'] = "Invalid module";
						
						$this->session->set_flashdata('msg', $data);
						redirect('home');   
						return;

						break;
				}

				if ($simpanan_id) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
					$data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }
        }else{
			$data['success'] = 0;
			$data['error'] = "Invalid Input";

        }

		$this->session->set_flashdata('msg', $data);  
		redirect('simpanan/page/'.$module);
	}

	private function get_input()
    {
        $data["person"] = $this->input->post('person');
        $data["date"] = $this->input->post('date');
        $data["balance"] = $this->input->post('balance');
        
        return $data;
    }

}
