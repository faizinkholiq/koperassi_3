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
			'investasi_model',
            'person_model',
            'parameter_model',
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
			$d['summary'] = $this->simpanan_model->summary($d['person_id']);
			$d['history'] = $this->simpanan_model->get_history($d['person_id']);
			$d['person_list'] = $this->anggota_model->list();
			$this->load->view('layout/template', $d);
        }
	}

    public function get_dt_simpanan(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $params["person"] = $this->input->post("person");
        $params["type"] = $this->input->post("type");
        $params["month"] = $this->input->post("month");
        $params["year"] = $this->input->post("year");

        $data = $this->simpanan_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
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
                    $d['default_nominal'] = $this->simpanan_model->get_default_nominal("Pokok")["nominal"];
					break;
				case 'wajib':
					$d['title'] = "Simpanan Wajib";
					$d['content_view'] = 'simpanan/wajib';
					$d['highlight_menu'] = "simpanan_wajib";
                    $d['default_nominal'] = $this->simpanan_model->get_default_nominal("Wajib")["nominal"];
					break;
				case 'sukarela':
					$d['title'] = "Simpanan Sukarela";
					$d['content_view'] = 'simpanan/sukarela';
					$d['highlight_menu'] = "simpanan_sukarela";
					break;
				case 'investasi':
					$d['title'] = "Investasi";
					$d['content_view'] = 'simpanan/investasi';
					$d['highlight_menu'] = "investasi";
					break;
			}

			$d['module'] = $module;
			
            $d['parameter'] = $this->parameter_model->detail($d['person_id']);
			// List Data
			$d['person_list'] = $this->anggota_model->list();

            $this->load->view('layout/template', $d);
        }
	}

    public function get_dt_simpanan_pokok(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $params["month"] = $this->input->post("month");
        $params["year"] = $this->input->post("year");

        $data = $this->simpanan_pokok_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function get_dt_simpanan_wajib(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
       
        $params["month"] = $this->input->post("month");
        $params["year"] = $this->input->post("year");

        $data = $this->simpanan_wajib_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function get_dt_simpanan_sukarela(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
     
        $params["month"] = $this->input->post("month");
        $params["year"] = $this->input->post("year");

        $data = $this->simpanan_sukarela_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function get_dt_simpanan_investasi(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
     
        $params["month"] = $this->input->post("month");
        $params["year"] = $this->input->post("year");

        $data = $this->investasi_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
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
                if(!$nd){
                    $data['success'] = 0;
                    $data['error'] = "Invalid Person !";
                }else{
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
                        case 'investasi':
                            $nd["code"] = $this->investasi_model->get_code();
                            $simpanan_id = $this->investasi_model->create($nd);
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
            }
        }else{
			$data['success'] = 0;
			$data['error'] = "Invalid Input";
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('simpanan/page/'.$module);
	}

    public function edit($module)
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
                $nd['id'] = $this->input->post('id');
                if(!$nd){
                    $data['success'] = 0;
                    $data['error'] = "Invalid Person !";
                }else{
                    switch ($module) {
                        case 'pokok':
                            $simpanan_id = $this->simpanan_pokok_model->edit($nd);
                            break;
                        case 'wajib':
                            $simpanan_id = $this->simpanan_wajib_model->edit($nd);
                            break;
                        case 'sukarela':
                            $simpanan_id = $this->simpanan_sukarela_model->edit($nd);
                            break;
                        case 'investasi':
                            $simpanan_id = $this->investasi_model->edit($nd);
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
                        $data['message'] = "Update Berhasil !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Update Gagal !";
                    }
                }
            }
        }else{
			$data['success'] = 0;
			$data['error'] = "Invalid Input";
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('simpanan/page/'.$module);
	}

    public function delete($module) 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('simpanan_anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');

            switch ($module) {
                case 'pokok':
                    $delete = $this->simpanan_pokok_model->delete($id);
                    break;
                case 'wajib':
                    $delete = $this->simpanan_wajib_model->delete($id);
                    break;
                case 'sukarela':
                    $delete = $this->simpanan_sukarela_model->delete($id);
                    break;
                case 'investasi':
                    $delete = $this->investasi_model->delete($id);
                    break;
                default:
                    $data['success'] = 0;
                    $data['error'] = "Invalid module";
                    
                    $this->session->set_flashdata('msg', $data);
                    redirect('home');   
                    return;

                    break;
            }

            if ($delete) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
		redirect('simpanan/page/'.$module);
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
            $data["dk"] = 'D';
        }

        return $data;
    }

	public function edit_temp()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('person_id','Anggota','required');
        $this->form_validation->set_rules('balance','Jumlah Simpanan','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('simpanan', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input_temp();

				$detail = $this->simpanan_model->detail_temp($nd["simpanan_id"], $nd["type"]);
				if($detail){
					$nd["id"] = $detail["id"];
					if($this->simpanan_model->edit_temp($nd)){
						$simpanan_id = $detail["simpanan_id"];
						$action = "edit";
					}
				}else{
					$simpanan_id = $this->simpanan_model->create_temp($nd);
					$action = "create";
				}

				if ($simpanan_id) {
					if($action == "create") {
						$this->user_model->create_notif([
							"user_id" => $d["id"],
							"time" => date("Y-m-d"),
							"message" => "Pengajuan perubahan Simpanan ".$nd["type"]." sedang diproses",
							"status" => "Pending",
							"module" => "Simpanan ".$nd["type"],
							"changes_id" => $nd["simpanan_id"],
						]);
					}

					// Record history
        			$code = $this->input->post('code');
					$detail_history = $this->simpanan_model->detail_history($nd["person_id"], $code, $nd["type"], "Pending");
					if ($detail_history) {
						$this->simpanan_model->edit_history([
							"id" => $detail_history["id"],
							"date" => date("Y-m-d"),
							"balance" => $nd["balance"],
						]);
					}else{
						$this->simpanan_model->create_history([
							"person_id" => $nd["person_id"],
							"date" => date("Y-m-d"),
							"code" => $code,
							"type" => $nd["type"],
							"balance" => $nd["balance"],
							"status" => "Pending",
						]);
					}

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
		redirect('simpanan');
	}

	private function get_input_temp()
    {
        $data["simpanan_id"] = $this->input->post('simpanan_id');
        $data["person_id"] = $this->input->post('person_id');
        $data["type"] = $this->input->post('type');
        $data["balance"] = $this->input->post('balance');
        
        return $data;
    }

	// Pengaturan Simpanan
	public function settings()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Master Simpanan";
        $d['highlight_menu'] = "simpanan_settings";
        $d['content_view'] = 'simpanan/settings';
        
        if (!check_permission('master', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->simpanan_model->get_settings();
            $this->load->view('layout/template', $d);
        }
	}
	
    public function create_settings()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('simpanan','Simpanan','required');
        $this->form_validation->set_rules('nominal','Nominal','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input_settings();

                $position_id = $this->simpanan_model->create_settings($nd);
                if ($position_id) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil disimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Data gagal disimpan !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('simpanan/settings');
        }else{
            $d['title'] = "Tambah Data Baru";
            $d['highlight_menu'] = "simpanan_settings";
            $d['content_view'] = 'simpanan/settings_input';
            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit_settings($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('simpanan','Simpanan','required');
        $this->form_validation->set_rules('nominal','Nominal','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('master', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input_settings();

                $detail = $this->simpanan_model->detail_settings($id);
                if ($detail) {
                    $nd["id"] = $id;

                    if ($this->simpanan_model->edit_settings($nd)) {
                        $data['success'] = 1;
                        $data['message'] = "Data berhasil diubah !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Data gagal diubah !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            redirect('simpanan/settings');
        }else{
            $d['title'] = "Ubah Data Simpanan";
            $d['highlight_menu'] = "simpanan_settings";
            $d['content_view'] = 'simpanan/settings_input';

            if (!check_permission('master', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->simpanan_model->detail_settings($id);
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function delete_settings() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('master', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->simpanan_model->delete_settings($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('simpanan/settings');
    }

    public function generate_settings() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('master', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            $detail = $this->simpanan_model->detail_settings($id);
            
            if ($detail) {
                $nd[strtolower($detail['simpanan'])] = $detail['nominal'];
                if ($this->simpanan_model->generate_settings($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Proses Generate Berhasil !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Proses Generate Gagal ";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Data simpanan tidak ditemukan";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('simpanan/settings');
    }

    private function get_input_settings()
    {
        $data["simpanan"] = $this->input->post('simpanan');
        $data["nominal"] = $this->input->post('nominal');
        
        return $data;
    }

    public function pengajuan_perubahan()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pengajuan Perubahan Simpanan";
		$d['highlight_menu'] = "ubah_simpanan";
		$d['content_view'] = 'simpanan/ajuan_ubah';
        $d['summary'] = $this->simpanan_model->summary($d['person_id']);

		if (!check_permission('ubah_simpanan', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function get_dt_ubah_simpanan(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $params["person"] = $this->input->post("person");

        $data = $this->simpanan_model->get_dt_ubah_simpanan($params);

        ob_end_clean();
        echo json_encode($data);
    }

    private function get_input_ubah_simpanan()
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
            $data["type"] = $this->input->post('type');
        }

        return $data;
    }

    public function submit_ubah_simpanan()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('person','Person','required');
        $this->form_validation->set_rules('balance','Nominal Perubahan','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('ubah_simpanan', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input_ubah_simpanan();
                if(!$nd){
                    $data['success'] = 0;
                    $data['error'] = "Invalid Person !";
                }else{
                    $date_input = $nd['year']."-".$nd['month']."-01";
                    if($date_input >= date('Y-m-01')){
                        $nd['status'] = 'Pending';
                        $detail = $this->simpanan_model->detail_ubah_simpanan_by_month($nd);
                        if ($detail) {
                            $nd['id'] = $detail['id'];
                            $this->simpanan_model->edit_ubah_simpanan($nd);
                            $simpanan_id = $nd['id'];
                        }else{
                            $simpanan_id = $this->simpanan_model->create_ubah_simpanan($nd);
                        }
    
                        if ($simpanan_id) {
                            $data['success'] = 1;
                            $data['message'] = "Data berhasil tersimpan !";
                        } else {
                            $data['success'] = 0;
                            $data['error'] = "Gagal menyimpan data !";
                        }
                    }else{
                        $data['success'] = 0;
                        $data['error'] = "Pengajuan perubahan ditolak karena bulan/tahun yang dipilih sudah terlewat !";
                    }
                }
            }
        }else{
			$data['success'] = 0;
			$data['error'] = "Invalid Input";
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('simpanan/pengajuan_perubahan');
	}

    public function edit_ubah_simpanan()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('ubah_simpanan', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd = $this->get_input_ubah_simpanan();
            $nd['status'] = 'Pending';

            $date_input = $nd['year']."-".$nd['month']."-01";
            if($date_input >= date('Y-m-01')){
                $detail = $this->simpanan_model->detail_ubah_simpanan($id);
                if ($detail) {
                    $nd['id'] = $detail['id'];
                    if ($this->simpanan_model->edit_ubah_simpanan($nd)) {
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
            }else{
                $data['success'] = 0;
                $data['error'] = "Pengajuan perubahan ditolak karena bulan/tahun yang dipilih sudah terlewat !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('simpanan/pengajuan_perubahan');
	}

    public function delete_ubah_simpanan() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('ubah_simpanan', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->simpanan_model->delete_ubah_simpanan($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('simpanan/pengajuan_perubahan');
    }


    public function approve_ubah_simpanan()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('ubah_simpanan', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Approved';
            $detail = $this->simpanan_model->detail_ubah_simpanan($id);
            if ($detail) {
                $type = strtolower($detail['type']);
                $nd['id'] = $detail['id'];
                if ($this->simpanan_model->edit_ubah_simpanan($nd)) {
                    
                    $person = $this->anggota_model->detail_by_nik($detail['person']);
                    if ($person) {
                        $data_person["id"] = $person['id'];
                        $data_person[$type] = $detail['balance'];
                        $data_person['status_simpanan'] = 'custom';
                        $this->anggota_model->edit($data_person);
                    }

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
		redirect('simpanan/pengajuan_perubahan');
	}

    public function reject_ubah_simpanan()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('ubah_simpanan', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Decline';
            $nd['reason'] = $this->input->post('reason');
            $detail = $this->simpanan_model->detail_ubah_simpanan($id);
            if ($detail) {
                $type = strtolower($detail['type']);
                $nd['id'] = $detail['id'];
                if ($this->simpanan_model->edit_ubah_simpanan($nd)) {
                    
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
		redirect('simpanan/pengajuan_perubahan');
	}


    // Posting Simpanan
	public function posting()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Posting Simpanan";
        $d['highlight_menu'] = "posting_simpanan";
        $d['content_view'] = 'simpanan/posting';
        
        if (!check_permission('posting_simpanan', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    public function do_posting()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('posting_simpanan', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd['bulan'] = $this->input->post('bulan');
            $nd['tahun'] = $this->input->post('tahun');
            $nd['simpanan'] = $this->input->post('simpanan');
            if($nd['simpanan']) {
                if ($this->simpanan_model->posting($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Mohon pilih salah satu simpanan!";
            }
        }

        echo json_encode($data);
	}

    public function penarikan()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Penarikan Simpanan";
		$d['highlight_menu'] = "penarikan_simpanan";
		
        if ($d['role'] == 1) {
            $d['content_view'] = 'simpanan/penarikan';
        }else{
            $d['content_view'] = 'simpanan/penarikan_anggota';
        }

		if (!check_permission('penarikan_simpanan', $d['role'])){
            redirect('home');
        }else{
			$d['person_list'] = $this->anggota_model->list();
			$this->load->view('layout/template', $d);
        }
	}

    public function get_dt_penarikan(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $params["type"] = $this->input->post("type");

        $data = $this->simpanan_model->get_dt_penarikan($params);

        ob_end_clean();
        echo json_encode($data);
    }

}
