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
            'parameter_model',
		]);

        $this->load->library('form_validation');
    }

	public function simpanan()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Rekapitulasi Simpanan";
		$d['highlight_menu'] = "report_simpanan";
		$d['content_view'] = 'report/simpanan';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function simpanan_detail()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Laporan Simpanan";
		$d['highlight_menu'] = "report_simpanan_detail";
		$d['content_view'] = 'report/simpanan_detail';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
            $d['parameter'] = $this->parameter_model->detail($d['person_id']);

			$this->load->view('layout/template', $d);
        }
	}
    
    public function pinjaman_uang()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Rekapitulasi Pinjaman Uang";
		$d['highlight_menu'] = "report_pinjaman_uang";
		$d['content_view'] = 'report/pinjaman_uang';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function pinjaman_barang()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Rekapitulasi Pinjaman Barang";
		$d['highlight_menu'] = "report_pinjaman_barang";
		$d['content_view'] = 'report/pinjaman_barang';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function laba_rugi()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Rekapitulasi Laba Rugi";
		$d['highlight_menu'] = "report_laba_rugi";
		$d['content_view'] = 'report/laba_rugi';

		if (!check_permission('report', $d['role'])){
            redirect('home');
        }else{
			$this->load->view('layout/template', $d);
        }
	}

    public function neraca()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Neraca";
		$d['highlight_menu'] = "neraca";
		$d['content_view'] = 'report/neraca';

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

    public function export_simpanan()
    {
        // Params
        $p['month'] = $this->input->get('month');
        $p['year'] = $this->input->get('year');

        // Data
        $data = $this->report_model->get_data_simpanan($p);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rincian Simpanan Anggota Koperasi');
        $rowNo+=2;
        
        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'NIK');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Nama');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Depo');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Tahun');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Data Simpanan');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+4]}{$rowNo}");
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Wajib');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pokok');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Sukarela');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Investasi');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['nik']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['depo']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['position']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['wajib']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['pokok']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['sukarela']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['investasi']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['total']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $rowNo++;
        }
        $rowNo--;

        $allStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($allStyle);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report_Detail_Simpanan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_simpanan_detail()
    {   
        // Params
        $p['from'] = $this->input->get('from');
        $p['to'] = $this->input->get('to');
        $p['year'] = $this->input->get('year');

        // Data
        $data = $this->report_model->get_data_simpanan_detail($p);

        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rincian Simpanan Anggota Koperasi');
        $rowNo+=2;
        
        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        // $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'BAG');
        // $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        // $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        // $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'NIK');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Nama');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Tahun');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bulan');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Desc');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Ket');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Data Simpanan');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+4]}{$rowNo}");
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pokok');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Wajib');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Sukarela');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Investasi');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;

        $rowBefore = [];
        $mergePersonRow = 0;
        $mergeBfPersonRow = 0;
        $mergeYearRow = 0;
        foreach($data as $row)
        {
            if (!isset($rowBefore['nik'])){
                $rowBefore['nik'] = null;
            }

            if($rowBefore['nik'] == $row['nik']){
                $mergePersonRow++;
                if($rowBefore['year'] == $row['year']){
                    $mergeYearRow++;
                }
            }else{
                $mergePersonRow = 0;
                $mergeYearRow = 0;
            }
            
            $letterCounter = $firstLtrCounter;
            
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['nik']);
            if($rowBefore['nik'] != $row['nik']){
                if(($rowNo-$mergeBfPersonRow-1) - ($rowNo - 1)){
                    $sheet->mergeCells("{$letters[$letterCounter]}".($rowNo-$mergeBfPersonRow-1).":{$letters[$letterCounter]}".($rowNo - 1));
                }
            }
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            if($rowBefore['nik'] != $row['nik']){
                if(($rowNo-$mergeBfPersonRow-1) - ($rowNo - 1)){
                    $sheet->mergeCells("{$letters[$letterCounter]}".($rowNo-$mergeBfPersonRow-1).":{$letters[$letterCounter]}".($rowNo - 1));
                }
            }
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['year']);
            if($rowBefore['nik'] != $row['nik']){
                if(($rowNo-$mergeBfPersonRow-1) - ($rowNo - 1)){
                    $sheet->mergeCells("{$letters[$letterCounter]}".($rowNo-$mergeBfPersonRow-1).":{$letters[$letterCounter]}".($rowNo - 1));
                }
            }
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['month']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", !empty($row['month'])? $months[$row['month'] - 1] : '');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['ket']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['pokok']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['wajib']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['sukarela']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['investasi']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['pokok'] + $row['wajib'] + $row['sukarela'] + $row['investasi']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $rowNo++;

            $mergeBfPersonRow = $mergePersonRow;
            $rowBefore = $row;
        }
        $rowNo--;
        // exit;

        $allStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($allStyle);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report_Simpanan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_pinjaman_uang()
    {
        // Params
        $p['month'] = $this->input->get('month');
        $p['year'] = $this->input->get('year');

        // Data
        $data = $this->report_model->get_data_pinjaman_uang($p);
        $month_name = ["JANUARI", "PEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rincian Pinjaman Anggota Koperasi');
        $rowNo+=2;
        
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'PERIODE: '. $month_name[$p['month'] - 1] .' '.$p['year']);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold( true );

        $rowNo++;
        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'NIK');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Nama');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Debit');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Kredit');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Data');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+4]}{$rowNo}");
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Cicilan');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bayar');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bunga');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Sisa');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Gaji');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['nik']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['debit']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['kredit']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['cicilan']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['bayar']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['bunga']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['sisa']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['gaji']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $rowNo++;
        }
        $rowNo--;

        $allStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($allStyle);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report_Detail_Simpanan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_pinjaman_barang()
    {
        // Params
        $p['month'] = $this->input->get('month');
        $p['year'] = $this->input->get('year');

        // Data
        $data = $this->report_model->get_data_pinjaman_barang($p);
        $month_name = ["JANUARI", "PEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rincian Pinjaman Barang Anggota Koperasi');
        $rowNo+=2;
        
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'PERIODE: '. $month_name[$p['month'] - 1] .' '.$p['year']);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold( true );

        $rowNo++;
        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'NIK');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Nama Anggota');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Nama Barang');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(30);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Harga Beli');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Profit');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Harga Jual');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;

        $year = $p['year'];
        $month = $p['month'];
        $month_no = $month;
        for ($i=1; $i <= 18; $i++) { 

            if($month_no == 13) {
                $month_no = 1;
                $year++;
            }

            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", substr($month_name[$month_no-1], 0, 3));
            $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
            $letterCounter++;

            $month_no++;
        }
        $letterCounter--;

        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['nik']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['person_name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['buy']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['profit']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['sell']);
            $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
            $letterCounter++;
            
            $year = $p['year'];
            $month = $p['month'];
            $month_no = $month;
            for ($i=1; $i <= 18; $i++) { 

                if($month_no == 13) {
                    $month_no = 1;
                    $year++;
                }

                $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row[$year.str_pad($month_no, 2, '0', STR_PAD_LEFT)]);
                $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
                $letterCounter++;

                $month_no++;
            }
            $letterCounter--;
            $rowNo++;
        }
        $rowNo--;

        $allStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($allStyle);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Report_Detail_Simpanan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_laba_rugi()
    {
        // Params
        $p['year'] = $this->input->get('year');
        $year = substr( $p['year'], -2);

        // Data
        $data = $this->report_model->get_data_laba_rugi($p);

        $month_name = ["JANUARI", "PEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;

        // $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        // $rowNo++;
        // $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rekapitulasi Laba Rugi');
        // $rowNo+=2;
        
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'PERIODE: '.$p['year']);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold( true );

        $rowNo++;
        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'DESCRIPTION');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(40);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+4);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'ACTUAL');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+13]}{$rowNo}");
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL I\nJanuari - Maret '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++; 
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL II\nApril - Juni '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL III\nJuli - September '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL IV\nOktober - Desember '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "YEAR TO DATE\n".$p["year"]);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "YEAR TO DATE\n".($p["year"] - 1));
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo-=2;
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'VARIANCE');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '%');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(10);
        $rowNo++;
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        
        $rowNo+=2;
        $firstRow = $rowNo-1;

        $baseStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $baseStyle2 = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pokok Pinjaman & Bunga');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;
        $lastLtrCounter = $letterCounter;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koreksi Bunga');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_koreksi_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Pokok Pinjaman & Bunga');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pokok Pinjaman');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan Bunga');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '% Bunga Pinjaman');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $letterCounter+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["bunga_pinjaman_percent"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0.0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pinjaman Beli Barang Elektronik');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Harga Pokok Pinj. Beli B. Elektronik');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Gross Profit');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_gross_profit"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Pokok Pinjaman & Bunga');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_pokok_pinjaman_bunga"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_total_pokok_pinjaman_bunga_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Pinjaman Beli Elektronik');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_jual_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_jual_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Grand Total Pendapatan Koperasi');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_grand_total_pendapatan_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Pokok Pinjaman');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_pokok_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_total_pokok_pinjaman_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Harga Pokok Pinj. Beli B. Elektronik');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["total_beli_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_beli_pinjaman_barang_2"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Grand Total Pokok Koperasi');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_grand_total_pokok_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Laba Bunga Pinjaman');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_laba_bunga_pinjaman"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Laba Bunga Pinjaman Beli Elektronik');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_laba_bunga_pinjaman_barang"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Grand Total Laba Koperasi');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q1"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q2"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q3"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["q4"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["ytd"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["lytd"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $data["variance"]["percent_grand_total_laba_koperasi"]);
        $sheet->getStyle("{$letters[$letterCounter]}{$rowNo}")->getNumberFormat()->setFormatCode('#,##0');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Beban Operasional:');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Bunga Simpanan Sukarela');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Bunga Tabungan');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Bensin, Parkir & Transport');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Peralatan Toko');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Biaya Pengurus Koperasi');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Meeting Pengurus');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Dana Kesejahteraan Anggota');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Beban Operasional');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan Operasional');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan / Biaya Lain-lain');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Tahapan BCA');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Pajak Tahapan BCA');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Bank BCA');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", '    Lain-lain');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Pendapatan / Biaya lain-lain');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan Sebelum Pajak');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan Pajak');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pendapatan Bersih Koperasi');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Retained Earning - Prior Year');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Less: Sisa Hasil Usaha - 2019');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Less: Pembagian RE - 2017-2019');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Retained Earning - Ending');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($baseStyle);
        $rowNo++;

        $letterCounter = $firstLtrCounter;

        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->getBorders()->getBottom()->setBorderStyle(\cPhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$firstLtrCounter]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+1)]}{$firstRow}:{$letters[($firstLtrCounter+1)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+2)]}{$firstRow}:{$letters[($firstLtrCounter+2)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+3)]}{$firstRow}:{$letters[($firstLtrCounter+3)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+4)]}{$firstRow}:{$letters[($firstLtrCounter+4)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+5)]}{$firstRow}:{$letters[($firstLtrCounter+5)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+6)]}{$firstRow}:{$letters[($firstLtrCounter+6)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+7)]}{$firstRow}:{$letters[($firstLtrCounter+7)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+8)]}{$firstRow}:{$letters[($firstLtrCounter+8)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+9)]}{$firstRow}:{$letters[($firstLtrCounter+9)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+10)]}{$firstRow}:{$letters[($firstLtrCounter+10)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+11)]}{$firstRow}:{$letters[($firstLtrCounter+11)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+12)]}{$firstRow}:{$letters[($firstLtrCounter+12)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+13)]}{$firstRow}:{$letters[($firstLtrCounter+13)]}{$rowNo}")->applyFromArray($baseStyle2);
        $sheet->getStyle("{$letters[($firstLtrCounter+14)]}{$firstRow}:{$letters[($firstLtrCounter+14)]}{$rowNo}")->applyFromArray($baseStyle2);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laba_Rugi_'.$p['year'].'_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_neraca()
    {
        // Params
        $p['year'] = $this->input->get('year');
        $year = substr( $p['year'], -2);

        // Data
        $data = $this->report_model->get_data_neraca($p);

        $month_name = ["JANUARI", "PEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 2;
        $letters = get_alphabet_list();
        $letterCounter = 1;
        $firstLtrCounter = $letterCounter;

        // $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Koperasi PT. Putri Daya Usahatama');
        // $rowNo++;
        // $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Rekapitulasi Laba Rugi');
        // $rowNo+=2;
        
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'KOPERASI KARYAWAN');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+10]}{$rowNo}");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold( true );
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setSize(12);
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'PT PUTRI DAYA USAHATAMA');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+10]}{$rowNo}");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold( true );
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getFont()->setSize(12);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Per 31 Desember '.$p['year']);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+10]}{$rowNo}");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal('center');
        $rowNo+=2;

        $firstRow = $rowNo;
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'DESCRIPTION');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(40);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}".$rowNo+4);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'ACTUAL');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+9]}{$rowNo}");
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL I\nJanuari - Maret '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->getColumnDimension("{$letters[$letterCounter+1]}")->setWidth(15);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo-=2;
        $letterCounter+=2; 
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL II\nApril - Juni '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->getColumnDimension("{$letters[$letterCounter+1]}")->setWidth(15);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo-=2;
        $letterCounter+=2; 
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL III\nJuli - September '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->getColumnDimension("{$letters[$letterCounter+1]}")->setWidth(15);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo-=2;
        $letterCounter+=2; 
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUARTAL IV\nOktober - Desember '$year");
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->getColumnDimension("{$letters[$letterCounter+1]}")->setWidth(15);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo-=2;
        $letterCounter+=2; 
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "LAST YEAR ".($p["year"] - 1));
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $sheet->getColumnDimension("{$letters[$letterCounter+1]}")->setWidth(15);
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter+1]}".$rowNo+1);
        $rowNo++;
        $letterCounter++;
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);

        $lastLtrCounter = $letterCounter;
        
        $rowNo+=2;
        $firstRow = $rowNo-1;

        $baseStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'CURRENT ASSET');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Cash');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bank Simpanan BCA');
        $rowNo++;
        
        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bank Simpanan BCA');
        $rowNo++;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bank Simpanan BCA');
        $rowNo+=2;

        $letterCounter = $firstLtrCounter;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Cash & Bank');
        $rowNo+=2;

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Account Receivable Barang');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Piutang Bunga Anggota Koperasi');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Piutang Pokok Anggota Koperasi');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Piutang Lain');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Persediaan Toko');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Current Asset');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'FIXED ASSET');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'FA Monitor & Perb. Toko');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Accum Penyusutan');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Net');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Assets');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'CURRENT LIABILITIES');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Lain-lain');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Dagang');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Simpanan Pokok Anggota');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Simpanan Wajib Anggota');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Simpanan Investasi Anggota');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Simpanan Sukarela');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Setoran Bank HS Anggota');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Free BPJS');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Anggota');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Bunga Sukarela Tahun '.$p["year"]);
        $rowNo+=4;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Pajak Ps 25');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Hutang Pajak Ps 23');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'MODAL');
        $rowNo++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Laba yang ditahan');
        $rowNo+=2;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Total Liabilitas & Equity');
        $rowNo++;
        
        $allStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$lastLtrCounter]}{$rowNo}")->applyFromArray($allStyle);
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$rowNo}:{$letters[$lastLtrCounter]}{$rowNo}")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Neraca_'.$p['year'].'_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}