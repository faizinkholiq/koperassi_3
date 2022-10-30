<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'pinjaman_model',
        ]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman";
        $d['highlight_menu'] = "pinjaman";
        $d['content_view'] = 'pinjaman/index';

        if (!check_permission('laporan', $d['role'])){
            redirect('home');
        }else{
            $d['data']['summary'] = $this->pinjaman_model->summary($d['person_id']);
			$d['data']['rows'] = $this->pinjaman_model->get($d['person_id']);

            $this->load->view('layout/template', $d);
        }
	}

}
