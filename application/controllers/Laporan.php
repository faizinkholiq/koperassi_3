<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['highlight_menu'] = "laporan";
        $d['content_view'] = 'coming_soon';

        $this->load->view('layout/template', $d);
	}
	
}
