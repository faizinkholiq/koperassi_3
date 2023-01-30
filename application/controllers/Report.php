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

        $rowNo = 1;
        $sheet->setCellValue("A$rowNo", 'Koperasi PT. Putri Daya Usahatama');
        $rowNo++;
        $sheet->setCellValue("A$rowNo", 'Rincian Simpanan Anggota Koperasi');
        $rowNo+=2;

        $firstRow = $rowNo;
        $sheet->setCellValue("A$rowNo", 'NIK');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->mergeCells("A$rowNo:A".$rowNo+1);
        $sheet->setCellValue("B$rowNo", 'Nama');
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->mergeCells("B$rowNo:B".$rowNo+1);
        $sheet->setCellValue("C$rowNo", 'Depo');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->mergeCells("C$rowNo:C".$rowNo+1);
        $sheet->setCellValue("D$rowNo", 'Jabatan');
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->mergeCells("D$rowNo:D".$rowNo+1);
        $sheet->setCellValue("E$rowNo", 'Data Simpanan');
        $sheet->mergeCells("E$rowNo:I$rowNo");
        $rowNo++;
        $sheet->setCellValue("E$rowNo", 'Wajib');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue("F$rowNo", 'Pokok');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue("G$rowNo", 'Sukarela');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->setCellValue("H$rowNo", 'Investasi');
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->setCellValue("I$rowNo", 'Total');
        $sheet->getColumnDimension('I')->setWidth(20);
        $rowNo++;
        
        $data = $this->report_model->get_data();
        foreach($data as $row)
        {
            $sheet->setCellValue("A$rowNo", $row['nik']);
            $sheet->setCellValue("B$rowNo", $row['name']);
            $sheet->setCellValue("C$rowNo", $row['depo']);
            $sheet->setCellValue("D$rowNo", $row['position']);
            $sheet->setCellValue("E$rowNo", $row['wajib']);
            $sheet->setCellValue("F$rowNo", $row['pokok']);
            $sheet->setCellValue("G$rowNo", $row['sukarela']);
            $sheet->setCellValue("H$rowNo", $row['investasi']);
            $sheet->setCellValue("I$rowNo", $row['total']);
            $rowNo++;
        }
        $rowNo--;

        $styleArray = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ],
        ];
        
        $sheet->getStyle("A$firstRow:I$rowNo")->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report_Simpanan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

}