<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {
	public function index()
	{
        $d['highlight_menu'] = "simpanan";
        $d['content_view'] = 'coming_soon';
        $this->load->view('layout/template', $d);
	}
}
