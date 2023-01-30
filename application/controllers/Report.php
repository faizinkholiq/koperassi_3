<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    public function export_simpanan()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Alamat');
        
        // $siswa = $this->siswa_model->getAll();
        // $no = 1;
        // $x = 2;
        // foreach($siswa as $row)
        // {
        //     $sheet->setCellValue('A'.$x, $no++);
        //     $sheet->setCellValue('B'.$x, $row->nama);
        //     $sheet->setCellValue('C'.$x, $row->kelas);
        //     $sheet->setCellValue('D'.$x, $row->jenis_kelamin);
        //     $sheet->setCellValue('E'.$x, $row->alamat);
        //     $x++;
        // }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-siswa';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

}