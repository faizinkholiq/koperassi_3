<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameter extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'parameter_model',
        ]);

        $this->load->library('form_validation');
    }

    public function index()
	{
        $d = $this->user_model->login_check();
        $d['content_view'] = 'parameter/index';
        $role = isset($_GET['role'])? $_GET['role'] : null;
        $d['highlight_menu'] = "parameter_settings";
        $d['title'] = "Parameter Settings";
        
        if (!check_permission('parameter_settings', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

}