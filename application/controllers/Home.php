<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'home_model',
            'anggota_model',
        ]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Dashboard";
        $d['highlight_menu'] = "dashboard";

        if($d['role'] == 1){
            $d['summary'] = $this->home_model->get_summary_admin($d['nik']);
        }else if($d['role'] == 2){
            $d['summary'] = $this->home_model->get_summary_member($d['nik']);
            $d['salary'] = $this->anggota_model->detail($d['person_id'])['salary'];
        }

        $d['content_view'] = ($d['role'] == 1)? 'dashboard/admin' : 'dashboard/member';
        $this->load->view('layout/template', $d);
	}

}
