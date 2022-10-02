<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
        $d['highlight_menu'] = "dashboard";
        $d['content_view'] = 'dashboard';
        $this->load->view('layout/template', $d);
	}
}
