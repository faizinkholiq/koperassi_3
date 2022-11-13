<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'anggota_model',
            'simpanan_model',
            'simpanan_sukarela_model',
        ]);
        $this->load->library('form_validation');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Data User";
        $d['highlight_menu'] = "user";
        $d['content_view'] = 'user/index';

        if (!check_permission('user', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    function login(){
		if ($this->session->userdata('sess_data') == TRUE) {
            redirect('home');
        }else{
            $nd['username'] = $this->input->post('username');
            $nd['password'] = $this->input->post('password');

			if($check = $this->user_model->check_user($nd)){
				$data_session = $this->user_model->detail($check);
				$this->session->set_userdata('sess_data', $data_session);

				redirect('home');
	
			}else{
				$this->session->set_flashdata('msg', 'Username / Password is wrong');
				$this->load->view('login');
			}
        }
    }

    public function logout()
	{
		if ($this->session->userdata('sess_data') == TRUE) {
            $this->session->sess_destroy();
        }

        redirect('user/login');
	}

    public function get_data()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('user', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $data = $this->user_model->get();
        }
        
        echo json_encode($data);
	}

    public function create()
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('user', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();

                $user_id = $this->user_model->create($nd);
                if ($user_id) {

                    $data['success'] = 1;
                    $data['message'] = "Success !";
                    redirect('user');
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Failed !";
                }
            }

            // return $data;
        }else{
            $d['title'] = "Tambah User Baru";
            $d['highlight_menu'] = "user";
            $d['content_view'] = 'user/input';
    
            if (!check_permission('user', $d['role'])){
                redirect('home');
            }else{
                $this->load->view('layout/template', $d);
            }
        }
	}

    public function edit($id)
	{
        $d = $this->user_model->login_check();
        $this->form_validation->set_rules('username','Username','required');

        if ($this->form_validation->run() == TRUE) {
            if (!check_permission('user_settings', $d['role'])){
                $data['success'] = 0;
                $data['error'] = "No Permission !";
            }else{
                $nd = $this->get_input();
                
                $detail = $this->user_model->detail($id);
                if ($detail) {

                    $nd_anggota = [];
                    // Upload File
                    if($_FILES["profile_photo"]['error'] == 0) {
                        $file = $_FILES["profile_photo"];
                        $file["origin"] = "profile_photo";

                        $upload = $this->upload_file($file);
    
                        if(!$upload['success']){
                            $data = [
                                'success' => 0,
                                'message' => $upload['message'],
                            ];
    
                            $this->session->set_flashdata('msg', $data);
                            redirect('user/settings');   
                            return;
                        }else{
                            $nd_anggota["profile_photo"] = $upload['file'];
                            if(!empty($detail["profile_photo"])){
                                $path_to_file = './files/'.$detail["profile_photo"];
                                @unlink($path_to_file);
                            }
                        }
                    } 

                    if (!empty($this->input->post('remove_profile_photo'))){
                        $remove_file = $this->input->post('remove_profile_photo');
                        if ($remove_file) {
                            $nd_anggota["profile_photo"] = null;
                            $path_to_file = './files/'.$detail["profile_photo"];
                            @unlink($path_to_file);
                        } 

                    }

                    if (isset($_POST["password"])){
                        if ($this->input->post("password") != $this->input->post("konf_password")) {
                            $data['success'] = 0;
                            $data['error'] = "Password dan Konfirmasi Password tidak sama";

                            $this->session->set_flashdata('msg', $data);
                            redirect('user/settings');
                            return;
                        }else{
                            $nd["password"] = $this->input->post("password");
                        }
                    }

                    $nd["id"] = $id;
                    if ($this->user_model->edit($nd)) {

                        if (!empty($nd_anggota)) {
                            $nd_anggota["id"] = $detail["person_id"];
                            $this->anggota_model->edit($nd_anggota);
                        }

                        $data['success'] = 1;
                        $data['message'] = "Perubahan berhasil disimpan !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Perubahan gagal disimpan !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid akun ID !";
                }
            }
        }else{
            $data['success'] = 0;
            $data['error'] = "Failed Validation !";
        }

        $this->session->set_flashdata('msg', $data);
        redirect('user/settings');
        return;
	}

    public function delete($id) 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('user', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            if ($this->user_model->delete($id)) {

                $data['success'] = 1;
                $data['message'] = "Success !";
                redirect('user');
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed !";
            }
        }

        return $data;
    }

    private function get_input()
    {
        $data["username"] = $this->input->post('username');

        return $data;
    }

    public function settings()
    {
        $d = $this->user_model->login_check();
        $d['title'] = "Ubah Data Akun";
        $d['highlight_menu'] = "user_settings";
        $d['content_view'] = 'user/settings';

        if (!check_permission('user_settings', $d['role'])){
            redirect('home');
        }else{
            $id = $this->session->userdata('sess_data')['id'];
            $d["data"] = $this->user_model->detail($id);

            $this->load->view('layout/template', $d);
        }
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

    public function notifications()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "List Notifikasi Perubahan";
        $d['content_view'] = 'user/notifications';

        if (!check_permission('notifications', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    public function notifications_detail($id)
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Detail Perubahan";
        
        if (!check_permission('notifications', $d['role'])){
            redirect('home');
        }else{
            $detail = $this->user_model->detail_notif($id);
            if($detail){
                switch ($detail["module"]){
                    case "Simpanan Sukarela":
                        $d["before"] = $this->simpanan_sukarela_model->detail($detail["changes_id"]);
                        $d["after"] = $this->simpanan_model->detail_temp_by_changes($detail["changes_id"], 'Sukarela');
                        $d['content_view'] = 'simpanan/detail_changes';
                        break;
                    case "Anggota":
                        $d["before"] = $this->anggota_model->detail($detail["changes_id"]);
                        $d["after"] = $this->anggota_model->detail_temp_by_changes($detail["changes_id"]);
                        $d['content_view'] = 'anggota/detail_changes';
                        break;
                }
                
                $this->load->view('layout/template', $d);
            }else{
                redirect('user/notifications');
            }
        }
	}
}
