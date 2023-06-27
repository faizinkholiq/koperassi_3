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

}