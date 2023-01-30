<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'user_model',
			'report_model',
		]);

        $this->load->library('form_validation');
    }

	public function simpanan()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Laporan Simpanan";
		$d['highlight_menu'] = "report_simpanan";
		$d['content_view'] = 'report/simpanan';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function get_dt_simpanan(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $data = $this->report_model->get_dt_simpanan($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function simpanan_detail()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Laporan Simpanan Detail";
		$d['highlight_menu'] = "report_simpanan_detail";
		$d['content_view'] = 'report/simpanan_detail';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function get_dt_simpanan_detail(){
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

        $data = $this->report_model->get_dt_simpanan($params);

        ob_end_clean();
        echo json_encode($data);
    }

}