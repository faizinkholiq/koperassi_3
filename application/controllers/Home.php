<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Dashboard";
        $d['highlight_menu'] = "dashboard";
        $d['content_view'] = ($d['role'] == 1)? 'dashboard/admin' : 'dashboard/member';
        print_r($d);
        exit;
        $this->load->view('layout/template', $d);
	}

}
