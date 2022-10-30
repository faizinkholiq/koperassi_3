<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'user_model',
			'anggota_model',
		]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Simpanan";
		$d['highlight_menu'] = "simpanan";
		$d['content_view'] = 'simpanan/index';

		if (!check_permission('simpanan', $d['role'])){
            redirect('home');
        }else{
            $this->load->view('layout/template', $d);
        }
	}

	
	public function page($module)
	{	
        $d = $this->user_model->login_check();
		
		if (!check_permission('simpanan_anggota', $d['role'])){
			redirect('home');
        }else{
			switch($module){
				case 'pokok':
					$d['title'] = "Simpanan Pokok";
					$d['content_view'] = 'simpanan/pokok';
					$d['highlight_menu'] = "simpanan_pokok";
					break;
				case 'wajib':
					$d['title'] = "Simpanan Wajib";
					$d['content_view'] = 'simpanan/wajib';
					$d['highlight_menu'] = "simpanan_wajib";
					break;
				case 'sukarela':
					$d['title'] = "Simpanan Sukarela";
					$d['content_view'] = 'simpanan/sukarela';
					$d['highlight_menu'] = "simpanan_sukarela";
					break;
			}

            $this->load->view('layout/template', $d);
        }
	}

	public function input($module)
	{	
        $d = $this->user_model->login_check();
		
		if (!check_permission('simpanan_anggota', $d['role'])){
			redirect('home');
        }else{
			switch($module){
				case 'pokok':
					$d['title'] = "Simpanan Pokok";
					$d['highlight_menu'] = "simpanan_pokok";
					break;
				case 'wajib':
					$d['title'] = "Simpanan Wajib";
					$d['highlight_menu'] = "simpanan_wajib";
					break;
				case 'sukarela':
					$d['title'] = "Simpanan Sukarela";
					$d['highlight_menu'] = "simpanan_sukarela";
					break;
			}

			$d['content_view'] = 'simpanan/input';
			$d['module'] = $module;
			
			// List Data
			$d['person_list'] = $this->anggota_model->list();
            $this->load->view('layout/template', $d);
        }
	}

}
