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
        $d['title'] = "Master Anggota";
        $d['highlight_menu'] = "anggota";
        $d['content_view'] = 'anggota';

        $this->load->view('layout/template', $d);
	}

}
