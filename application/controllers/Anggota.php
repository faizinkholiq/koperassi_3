<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Data Anggota";
        $d['highlight_menu'] = "anggota";
        $d['content_view'] = 'anggota';

        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

    public function detail()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Detail Anggota";
        $d['highlight_menu'] = "anggota";
        $d['content_view'] = 'detail_anggota';

        if (!check_permission('anggota', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

}
