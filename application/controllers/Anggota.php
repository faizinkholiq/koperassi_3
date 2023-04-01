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
        $d['content_view'] = 'anggota/index';
        $role = isset($_GET['role'])? $_GET['role'] : null;
        $d['highlight_menu'] = !empty($role)? "administrator" : "anggota";
        $d['title'] = !empty($role)? "Data Administrator" : "Data Anggota";
        
        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    public function get_data(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        
        $params["role"] = $this->input->post("role");

        $data = $this->anggota_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function detail($id)
	{
        $d = $this->user_model->login_check();
        $d['content_view'] = 'anggota/detail';
        $role = isset($_GET['role'])? $_GET['role'] : null;
        $d['highlight_menu'] = !empty($role)? "administrator" : "anggota";
        $d['title'] = !empty($role)? "Detail Administrator" : "Detail Anggota";

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
        $this->form_validation->set_rules('no_ktp','No KTP','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('anggota', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();
                $role = $this->input->post('role');
                // Upload File
                foreach ($_FILES as $key => $item) {
                    if($item['error'] == 0) {
                        $file = $item;
                        $file["origin"] = $key;

                        $upload = $this->upload_file($file);
    
                        if(!$upload['success']){
                            $data = [
                                'success' => 0,
                                'message' => $upload['message'],
                            ];
    
                            $this->session->set_flashdata('msg', $data);
                            redirect('anggota');   
                            return;
                        }else{
                            $nd["detail_anggota"][$key] = $upload['file'];
                        }
                    } 
                }

                // Create user account
                $user_id = $this->user_model->create([
                    "username" => $nd["detail_anggota"]["no_ktp"],
                    "name" => $nd["detail_anggota"]["name"],
                    "role" => $role,
                    "password" => "member@koperasi123",
                    "active" => ($nd["detail_anggota"]["status"] == "Aktif")? 1 : 0,
                ]);

                if (!$user_id) {
                    $data['success'] = 0;
                    $data['error'] = "Failed Create Account User !";
                    $this->session->set_flashdata('msg', $data);
                    redirect('anggota');
                    return;
                }

                $nd["detail_anggota"]["user_id"] = $user_id;
                $anggota_id = $this->anggota_model->create($nd["detail_anggota"]);
                if ($anggota_id) {
                    if (!empty($nd["family"]["name"])) {
                        $nd["family"]["person"] = $anggota_id;
                        $this->anggota_model->create_keluarga($nd["family"]);
                    }

                    $data['success'] = 1;
                    $data['message'] = "Success Create New Data !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Failed Create New Data !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            if (!empty($role) && $role == 1) {
                redirect('anggota?role=1');
            }else{
                redirect('anggota');
            }
        }else{
            $d['content_view'] = 'anggota/input';
            $role = isset($_GET['role'])? $_GET['role'] : null;
            $d['highlight_menu'] = !empty($role)? "administrator" : "anggota";
            $d['title'] = !empty($role)? "Tambah Administrator Baru" : "Tambah Anggota Baru";

            if (!check_permission('anggota', $d['role'])){
                redirect('home');
            }else{
                $d['list_depo'] = $this->anggota_model->get_list_depo();
                $d['list_position'] = $this->anggota_model->get_list_position();
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('nama','Nama','required');
        $this->form_validation->set_rules('no_ktp','No KTP','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('anggota', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();
                $role = $this->input->post('role');

                $detail = $this->anggota_model->detail($id);

                if ($detail) {
                    $nd["detail_anggota"]["id"] = $id;
                    
                    // Upload File
                    foreach ($_FILES as $key => $item) {
                        if($item['error'] == 0) {
                            $file = $item;
                            $file["origin"] = $key;

                            $upload = $this->upload_file($file);
        
                            if(!$upload['success']){
                                $data = [
                                    'success' => 0,
                                    'message' => $upload['message'],
                                ];
        
                                $this->session->set_flashdata('msg', $data);
                                redirect('anggota');   
                                return;
                            }else{
                                $nd["detail_anggota"][$key] = $upload['file'];
                                if(!empty($detail[$key])){
                                    $path_to_file = './files/'.$detail[$key];
                                    @unlink($path_to_file);
                                }
                            }
                        } 
                    }

                    // Remove file
                    $remove_file = [];

                    if (!empty($this->input->post('remove_profile_photo'))){
                        $remove_file["profile_photo"] = $this->input->post('remove_profile_photo'); 
                    }

                    if (!empty($this->input->post('remove_ktp'))){
                        $remove_file["ktp"] = $this->input->post('remove_ktp'); 
                    }

                    foreach ($remove_file as $key => $val) {
                        if ($val){
                            $nd["detail_anggota"][$key] = null;
                            $path_to_file = './files/'.$detail[$key];
                            @unlink($path_to_file);
                        }
                    }

                    if ($this->anggota_model->edit($nd["detail_anggota"])) {
                        // Update user account 
                        $this->user_model->edit([
                            "id" => $detail["user_id"],
                            "name" => $nd["detail_anggota"]["name"],
                            "role" => $this->input->post('role'),
                            "active" => ($nd["detail_anggota"]["status"] == "Aktif")? 1 : 0,
                        ]);

                        if (!empty($nd["family"]["name"])) {
                            $nd["family"]["person"] = $id;
                            if (!empty($detail["id_family"])) {
                                $nd["family"]["id"] = $detail["id_family"];
                                $this->anggota_model->edit_keluarga($nd["family"]);
                            }else{
                                $this->anggota_model->create_keluarga($nd["family"]);
                            }
                        }
    
                        $data['success'] = 1;
                        $data['message'] = "Success Update Data!";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Failed Update Data !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID !";
                }
            }

            $this->session->set_flashdata('msg', $data);
            if (!empty($role) && $role == 1) {
                redirect('anggota?role=1');
            }else{
                redirect('anggota');
            }
        }else{
            $d['content_view'] = 'anggota/input';
            $role = isset($_GET['role'])? $_GET['role'] : null;
            $d['highlight_menu'] = !empty($role)? "administrator" : "anggota";
            $d['title'] = !empty($role)? "Ubah Administrator" : "Ubah Anggota";

            if (!check_permission('anggota', $d['role'])){
                redirect('home');
            }else{
                $d["data"] = $this->anggota_model->detail($id);
                $d['list_position'] = $this->anggota_model->get_list_position();
                $d['list_depo'] = $this->anggota_model->get_list_depo();

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

    public function reset_password($id)
	{
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            $role = $this->input->post('role');

            $detail = $this->anggota_model->detail($id);
            if ($detail) {
                $nd["detail_anggota"]["id"] = $id;
    
                $edit = $this->user_model->edit([
                    "id" => $detail["user_id"],
                    "password" => "member@koperasi123",
                ]);

                if ($edit) {
                    $data['success'] = 1;
                    $data['message'] = "Password berhasil direset !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Password gagal direset !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        if (!empty($role) && $role == 1) {
            redirect('anggota?role=1');
        }else{
            redirect('anggota');
        }
	}

    private function get_input()
    {
        $data["detail_anggota"]["no_ktp"] = $this->input->post('no_ktp');
        $data["detail_anggota"]["nik"] = $this->input->post('nik');
        $data["detail_anggota"]["name"] = $this->input->post('nama');
        $data["detail_anggota"]["address"] = $this->input->post('alamat');
        $data["detail_anggota"]["phone"] = $this->input->post('no_telp');
        $data["detail_anggota"]["email"] = $this->input->post('email');
        $data["detail_anggota"]["join_date"] = $this->input->post('tgl_anggota');
        $data["detail_anggota"]["status"] = $this->input->post('status');
        $data["detail_anggota"]["salary"] = $this->input->post('salary');
        $data["detail_anggota"]["position"] = $this->input->post('position');
        $data["detail_anggota"]["depo"] = $this->input->post('depo');
        $data["detail_anggota"]["acc_no"] = $this->input->post('acc_no');
        $data["detail_anggota"]["sukarela"] = $this->input->post('sukarela');
        $data["detail_anggota"]["investasi"] = $this->input->post('investasi');

        $data["family"]["name"] = $this->input->post("nama_kel");
        $data["family"]["address"] = $this->input->post("alamat_kel");
        $data["family"]["phone"] = $this->input->post("no_telp_kel");
        $data["family"]["status"] = $this->input->post("status_kel");

        return $data;
    }

    private function upload_file($file)
    {
        $arr_filename = explode('.', $file['name']);
        $filename = ucfirst($file["origin"]).'_'.date('YmdHis').'.'.$arr_filename[count($arr_filename) - 1];

        $config['upload_path'] = './files';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|svg';
        $config['file_name'] = $filename;
        $config['overwrite'] = true;
        $config['max_size'] = 2000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file["origin"])) {
            $data = [
                'success' => 0,
                'message' => $this->upload->display_errors(),
            ];
        }else{
            $data = [
                'success' => 1,
                'message' => 'Upload success',
                'file' => $filename,
            ];
        }

        return $data;
    }

    public function settings()
    {
        $d = $this->user_model->login_check();
        $d['title'] = "Ubah Data Diri";
        $d['highlight_menu'] = "anggota_settings";
        $d['content_view'] = 'anggota/settings';

        if (!check_permission('anggota_settings', $d['role'])){
            redirect('home');
        }else{
            $id = $this->session->userdata('sess_data')['person_id'];
            $d["data"] = $this->anggota_model->detail($id);
            $d["data"]["temporary"] = false;
            if($this->anggota_model->detail_temp($id)) {
                $d["data"] = $this->anggota_model->detail_temp($id);
                $d["data"]["temporary"] = true;
            }
            $d['list_depo'] = $this->anggota_model->get_list_depo();
            $d['list_position'] = $this->anggota_model->get_list_position();

            $this->load->view('layout/template', $d);
        }
    }

    private function get_input_temp()
    {   
        $data["name"] = $this->input->post('nama');
        $data["no_ktp"] = $this->input->post('no_ktp');
        $data["nik"] = $this->input->post('nik');
        $data["address"] = $this->input->post('alamat');
        $data["depo"] = $this->input->post('depo');
        $data["acc_no"] = $this->input->post('acc_no');
        $data["phone"] = $this->input->post('no_telp');
        $data["email"] = $this->input->post('email');

        return $data;
    }

    public function edit_temp($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('nama','Nama','required');
        $this->form_validation->set_rules('no_ktp','No KTP','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('anggota_settings', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input_temp();

                $detail = $this->anggota_model->detail($id);

                if ($detail) {
                    $nd["person_id"] = $id;

                    if (!empty($this->input->post('remove_ktp'))){
                        $nd["ktp"] = null;
                    }else{
                        $nd["ktp"] = $detail["ktp"];
                    }

                    // Upload File
                    if(isset($_FILES["ktp"])){
                        if($_FILES["ktp"]['error'] == 0) {
                            $file = $_FILES["ktp"];
                            $file["origin"] = "ktp";
    
                            $upload = $this->upload_file($file);
        
                            if(!$upload['success']){
                                $data = [
                                    'success' => 0,
                                    'error' => $upload['message'],
                                ];
        
                                $this->session->set_flashdata('msg', $data);
                                redirect('anggota/settings');   
                                return;
                            }else{
                                $nd["ktp"] = $upload['file'];
                            }
                        }
                    }

                    $detail_temp = $this->anggota_model->detail_temp($id);
                    if($detail_temp){
                        $this->anggota_model->delete_temp($id);
                    }

                    if ($this->anggota_model->create_temp($nd)) {
                        $this->user_model->create_notif([
                            "user_id" => $detail["user_id"],
                            "time" => date("Y-m-d"),
                            "message" => "Pengajuan perubahan data diri sedang diproses",
                            "status" => "Pending",
                            "module" => "Anggota",
                            "changes_id" => $id,
                        ]);
                        
                        $data['success'] = 1;
                        $data['message'] = "Pengajuan perubahan data berhasil, Harap tunggu hingga adminsitrator menyetujui perubahan data tersebut !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Gagal melakukan pengajuan perubahan !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid ID !";
                }
            }
        }else{
            $data['success'] = 0;
            $data['error'] = "Failed Validation !";
        }

        $this->session->set_flashdata('msg', $data);
        redirect('anggota/settings');
        return;

	}

    public function approver($id)
	{
        $d = $this->user_model->login_check();
        $d['content_view'] = 'anggota/approver';
        $d['highlight_menu'] = "anggota";
        $d['title'] = "Setujui Anggota";

        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $d["data"] = $this->anggota_model->detail($id);
            $this->load->view('layout/template', $d);
        }
	}
    
    public function get_person_temp($id)
	{
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $data = $this->anggota_model->detail_temp($id);
        }
        
        echo json_encode($data);
	}

    public function action_changes($do)
    {
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post("id");
            $reason = $this->input->post("reason");

            $detail = $this->anggota_model->detail($id);
            if ($detail) {
                $detail_temp = $this->anggota_model->detail_temp($id);
                if($detail_temp) {
                    
                    switch($do){
                        case "approved":
                            $nd_temp["id"] = $id; 
                            $nd_temp["status"] = "Approved";
                            if($this->anggota_model->edit_temp($nd_temp)){
                                $nd["id"] = $id;
                                $nd["name"] = $detail_temp["name"];
                                $nd["no_ktp"] = $detail_temp["no_ktp"];
                                $nd["nik"] = $detail_temp["nik"];
                                $nd["address"] = $detail_temp["address"];
                                $nd["depo"] = $detail_temp["depo"];
                                $nd["acc_no"] = $detail_temp["acc_no"];
                                $nd["phone"] = $detail_temp["phone"];
                                $nd["email"] = $detail_temp["email"];
                                $nd["ktp"] = $detail_temp["ktp"];
                                
                                if ($this->anggota_model->edit($nd)) {
                                    $this->user_model->create_notif([
                                        "user_id" => $detail["user_id"],
                                        "time" => date("Y-m-d"),
                                        "message" => "Pengajuan perubahan data diri telah disetujui oleh Administrator",
                                        "status" => "Success",
                                        "module" => "Anggota",
                                        "changes_id" => $id,
                                    ]);

                                    $data['success'] = 1;
                                    $data['message'] = "Success Update Data!";
                                } else {
                                    $data['success'] = 0;
                                    $data['error'] = "Failed Update Data !";
                                }
                            }else{
                                $data['success'] = 0;
                                $data['error'] = "Persetujuan Data gagal !";
                            }

                            break;
                        case "rejected":
                            $nd_temp["id"] = $id; 
                            $nd_temp["reason"] = $reason;
                            $nd_temp["status"] = "Rejected";
                            if($this->anggota_model->edit_temp($nd_temp)){
                                
                                $this->user_model->create_notif([
                                    "user_id" => $detail["user_id"],
                                    "time" => date("Y-m-d"),
                                    "message" => "Pengajuan perubahan data diri ditolak",
                                    "status" => "Failed",
                                    "module" => "Anggota",
                                    "changes_id" => $id,
                                ]);

                                $data['success'] = 1;
                                $data['message'] = "Penolakan Data berhasil !";
                            }else{
                                $data['success'] = 0;
                                $data['error'] = "Penolakan Data gagal !";
                            }

                            break;
                        default:
                                $data['success'] = 0;
                                $data['error'] = "Invalid Action !";
                            break;
                    }

                }else{
                    $data['success'] = 0;
                    $data['error'] = "Temporary data not found !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid anggota ID !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('anggota');
    }

    public function import_gaji()
    {
        $d = $this->user_model->login_check();
        if (!check_permission('anggota', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $file = $_FILES['file'];
            $tmp_file = $file['tmp_name'];
            $ekstensi  = explode('.', $file['name']);

            if (!empty($tmp_file)){
                if (strtolower(end($ekstensi)) === 'csv' && $file["size"] > 0) {
                    $i = 0;
					$handle = fopen($tmp_file, "r");
                    $newdata = [];

                    while (($row = fgetcsv($handle, 2048))) {
						$i++;
						if ($i == 1) continue;
                        
                        $item = explode(';', $row[0]);
                        if (!empty($item[0])) {
                            $newdata[] = [
                                "nik" => $item[0],
                                'salary' => $item[1],
                            ];
                        }
					}
					fclose($handle);

                    if($this->anggota_model->import_gaji($newdata)){
                        $data['success'] = 1;
                        $data['message'] = "Import Data berhasil!";
                    }else{
                        $data['success'] = 0;
                        $data['error'] = "Import Data gagal!";
                    }
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Format file tidak valid!";
				}
            } else {
                $data['success'] = 0;
                $data['error'] = "No file uploaded !";
            }
        }

		echo json_encode($data);
    }

}
