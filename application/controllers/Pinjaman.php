<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'pinjaman_model',
            'person_model',
            'kas_model',
        ]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman";
        $d['highlight_menu'] = "pinjaman";
        
        if (!check_permission('pinjaman', $d['role'])){
            redirect('home');
        }else{
            if($d['role'] == 1){
                $d['content_view'] = 'pinjaman/index_admin';
            }else{
                $d['content_view'] = 'pinjaman/index';
                $d['summary'] = $this->pinjaman_model->summary($d['person_id']);
            }

            $this->load->view('layout/template', $d);
        }
	}

    public function angsuran()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Angsuran";
        $d['highlight_menu'] = "angsuran";
        $d['content_view'] = 'pinjaman/angsuran';

        if (!check_permission('pinjaman', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->pinjaman_model->get_angsuran($d["nik"]);
            $d['summary'] = count($d['data']) > 0 ? $this->pinjaman_model->get_summary_angsuran($d["data"][0]["pinjaman_id"]) : [];

            $this->load->view('layout/template', $d);
        }
	}

    public function detail($id)
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman";
        $d['highlight_menu'] = "pinjaman";
        
        if (!check_permission('pinjaman', $d['role'])){
            redirect('home');
        }else{
            $d['detail'] = $this->pinjaman_model->full_detail($id);
            $d['content_view'] = 'pinjaman/detail';
            $this->load->view('layout/template', $d);
        }
	}

    public function get_dt(){
        $d = $this->user_model->login_check();
        
        if($d['role'] != 1){
            $params['person'] = $d['nik'];
        }
        
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        $params["status"] = $this->input->post("status");

        $data = $this->pinjaman_model->get_dt($params);

        ob_end_clean();
        echo json_encode($data);
    }

    public function get_dt_all(){
        $d = $this->user_model->login_check();
        
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        $params["status_anggaran"] = $this->input->post("status_anggaran");
        $params["status"] = $this->input->post("status");

        $data = $this->pinjaman_model->get_dt_all($params);

        ob_end_clean();
        echo json_encode($data);
    }

    private function get_input()
    {
        $person_id = $this->input->post('person');
        $detail_person = $this->person_model->detail($person_id);
        $data = [];
        
        if ($detail_person) {
            $data["person"] = $detail_person['nik'];
            $data["date"] = $this->input->post('date');
            $data["year"] = $this->input->post('year');
            $data["month"] = $this->input->post('month');
            $data["balance"] = $this->input->post('balance');
            $data["angsuran"] = $this->input->post('angsuran');
        }

        return $data;
    }

    public function create()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            if(!$nd){
                $data['success'] = 0;
                $data['error'] = "Invalid Person !";
            }else{
                $pinjaman_now = $this->pinjaman_model->get_by_person($nd['person']);
                if (count($pinjaman_now) > 0) {
                    $data['success'] = 0;
                    $data['error'] = "Pengajuan pinjaman ditolak karena masih ada pinjaman yang belum terselesaikan !";
                } else {
                    $nd['status'] = 'Pending';
                    $pinjaman_id = $this->pinjaman_model->create($nd);
    
                    if ($pinjaman_id) {
                        $data['success'] = 1;
                        $data['message'] = "Data berhasil tersimpan !";
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Gagal menyimpan data !";
                    }
                }
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function edit()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd = $this->get_input();
            $nd['status'] = 'Pending';

            $detail = $this->pinjaman->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function delete() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->pinjaman_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('pinjaman');
    }

    public function approve()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Approved';
            $nd['real'] = $this->input->post('real');
            $detail = $this->pinjaman_model->detail($id);

            if ($detail) {
                $nd['id'] = $detail['id'];
                $kas_by_year = $this->kas_model->detail_by_year($detail['year']);
                if ($kas_by_year){
                    $nd_kas['id'] = $kas_by_year['id'];
                    $nd_kas['kredit'] = floatval($kas_by_year['kredit']) + floatval($nd['real']);

                    if ($this->kas_model->edit($nd_kas)) {
                        if ($this->pinjaman_model->edit($nd)) {
                            $year = $detail["year"];
                            $month = $detail["month"];
                            $mn = 12;
                            $rate = 15.23/100;
                            $nper = floatval($detail["angsuran"]);
                            $sisa = floatval($nd['real']);
                            $angsuran = round(PMT($rate / $mn, $nper, $sisa));

                            for ($i=1; $i <= $detail["angsuran"]; $i++) {
                                if($month%12 == 1) $month = 1;

                                $bunga = $sisa * $rate / $mn; 

                                $nd_angsuran["pinjaman"] = $detail["id"];
                                $nd_angsuran["year"] = $year;
                                $nd_angsuran["month"] = $month;
                                $nd_angsuran["month_no"] = $i;
                                $nd_angsuran["pokok"] = $angsuran - $bunga;
                                $nd_angsuran["bunga"] = $bunga;
                                $nd_angsuran["status"] = "Belum Lunas";

                                $sisa = $sisa - $angsuran;
                                $this->pinjaman_model->create_angsuran($nd_angsuran);
                                if($month >= 12) $year++;
                                $month++;
                            }

                            $data['success'] = 1;
                            $data['message'] = "Data berhasil tersimpan !";
                        } else {
                            $data['success'] = 0;
                            $data['error'] = "Gagal menyimpan data !";
                        }
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Gagal mengupdate data kas !";
                }
                
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function reject()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Decline';
            $nd['reason'] = $this->input->post('reason');
            $detail = $this->pinjaman_model->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_model->edit($nd)) {
                    $data['success'] = 1;
                    $data['message'] = "Data berhasil tersimpan !";
                } else {
                    $data['success'] = 0;
                    $data['error'] = "Gagal menyimpan data !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }
        }

		$this->session->set_flashdata('msg', $data);  
		redirect('pinjaman');
	}

    public function paid()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
            $this->session->set_flashdata('msg', $data);  
            redirect('home');
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Lunas';
            $nd['date'] = $this->input->post('date');
            $detail = $this->pinjaman_model->detail_angsuran($id);
            if ($detail) {
                $detail_pinjaman = $this->pinjaman_model->detail($detail['pinjaman']);
                if($detail_pinjaman) {
                    $nd['id'] = $detail['id'];
                    if ($this->pinjaman_model->edit_angsuran($nd)) {
                        $kas_by_year = $this->kas_model->detail_by_year($detail_pinjaman['year']);
                        if ($kas_by_year){
                            $nd_kas['id'] = $kas_by_year['id'];
                            $angsuran = floatval($detail['pokok']) + floatval($detail['bunga']);
                            $nd_kas['debet'] = floatval($kas_by_year['debet']) + $angsuran;
                            if ($this->kas_model->edit($nd_kas)) {
                                $data['success'] = 1;
                                $data['message'] = "Data berhasil tersimpan !";
                            }else{
                                $data['success'] = 0;
                                $data['error'] = "Gagal menyimpan data !";
                            }
                        }else{
                            $data['success'] = 0;
                            $data['error'] = "Gagal Update Kas !";
                        }
                    } else {
                        $data['success'] = 0;
                        $data['error'] = "Gagal menyimpan data !";
                    }
                }else{
                    $data['success'] = 0;
                    $data['error'] = "Invalid Pinjaman ID !";
                }
            }else{
                $data['success'] = 0;
                $data['error'] = "Invalid ID !";
            }

            $this->session->set_flashdata('msg', $data);  
            redirect('pinjaman/detail/'.$detail['pinjaman']);
        }

	}

    public function export_template()
    {
        // Data
        $p = $_GET;
        $data = $this->pinjaman_model->get_report_template($p);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;
        
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
            'fill' => [
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '00FF7F']
            ]
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'No');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(5);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Debited Acc.');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Beneficiary ID');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Credited Acc.');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Amount');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Eff. Date');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Transaction Purpose');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(25);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Remark 1');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Receiver Name');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Beneficiary Email');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(35);
    
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        $no = 1;
        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $no++);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "2833485555");
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "001");
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['acc_no']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['real']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "");
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "");
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", "Realisasi Pinjaman");
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['email']);
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
        $filename = 'Template_BCA_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function export_template_angsuran($id)
    {
        // Data
        $p = $_GET;
        $data = $this->pinjaman_model->full_detail($id)['angsuran'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;
        
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
            'fill' => [
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '00FF7F']
            ]
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'ID');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bulan');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Tahun');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bulan Ke-');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(15);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pokok');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Bunga');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Angsuran');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Status');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);

        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        $no = 1;
        $month_name_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['id']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $month_name_list[$row['month']-1]);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['year']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['month_no']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", number_format((float)$row['pokok'], 2, '.', ''));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", number_format((float)$row['bunga'], 2, '.', ''));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", number_format((float)$row['angsuran'], 2, '.', ''));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['status']);
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

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $filename = 'template_angsuran_'.$id.'_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.csv"'); 
        header('Cache-Control: max-age=0');


        $writer->save('php://output');

    }

    public function import_angsuran($id)
	{	
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $file = $_FILES['file'];
            $tmp_file = $file['tmp_name'];
            $ekstensi  = explode('.', $file['name']);

            if (!empty($tmp_file)){
                if (strtolower(end($ekstensi)) === 'csv' && $file["size"] > 0) {
                    $i = 0;
					$handle = fopen($tmp_file, "r");

                    $import = false;
					while (($row = fgetcsv($handle, 2048))) {
						$i++;
						if ($i == 1) continue;
                        
                        $item = explode(';', $row[0]);
                        if (!empty($item[0])) {
                            $newdata = [
                                "id" => intval(str_replace('"', '', $item[0])),
                                "status" => str_replace('"', '', $item[7]),
                            ];

                            if ($this->pinjaman_model->edit_angsuran($newdata)) {
                                $import = true;
                            }else{
                                $import = false;
                            }
                        }
					}
					fclose($handle);

                    if($import){
                        $data['success'] = 1;
                        $data['message'] = "Import Data berhasil!";
                    }else{
                        $data['success'] = 0;
                        $data['error'] = "Import Data gagal!";
                    }

                } else {
                    $data['success'] = 0;
                    $data['error'] = "Format file tidak valid!";
				}
            }else{
                $data['success'] = 0;
                $data['error'] = "No file uploaded !";
            }
        }

		echo json_encode($data);
    }

    public function export_template_pelunasan()
    {
        // Data
        $p = $_GET;
        $data = $this->pinjaman_model->get_all_pinjaman();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNo = 1;
        $letters = get_alphabet_list();
        $letterCounter = 0;
        $firstLtrCounter = $letterCounter;
        
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
            'fill' => [
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '00FF7F']
            ]
        ];

        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'ID');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Name');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Pengajuan');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Realisasi');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Month');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Year');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        $letterCounter++;
        $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", 'Status');
        $sheet->getColumnDimension("{$letters[$letterCounter]}")->setWidth(20);
        
        $sheet->getStyle("{$letters[$firstLtrCounter]}{$firstRow}:{$letters[$letterCounter]}{$rowNo}")->applyFromArray($headerStyle);
        $rowNo++;
        
        $firstRow = $rowNo;
        foreach($data as $row)
        {
            $letterCounter = $firstLtrCounter;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['id']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['name']);
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", number_format((float)$row['pengajuan'], 2, '.', ''));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", number_format((float)$row['realisasi'], 2, '.', ''));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", date('m'));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", date('Y'));
            $letterCounter++;
            $sheet->setCellValue("{$letters[$letterCounter]}{$rowNo}", $row['status_angsuran']);
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

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $filename = 'template_pelunasan_'.date('YmdHis');
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.csv"'); 
        header('Cache-Control: max-age=0');


        $writer->save('php://output');

    }

    public function import_pelunasan()
	{	
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $file = $_FILES['file'];
            $tmp_file = $file['tmp_name'];
            $ekstensi  = explode('.', $file['name']);

            if (!empty($tmp_file)){
                if (strtolower(end($ekstensi)) === 'csv' && $file["size"] > 0) {
                    $i = 0;
					$handle = fopen($tmp_file, "r");

                    $import = false;
					while (($row = fgetcsv($handle, 2048))) {
						$i++;
						if ($i == 1) continue;
                        
                        $item = explode(';', $row[0]);
                        if (!empty($item[0])) {
                            $newdata = [
                                "pinjaman" => intval(str_replace('"', '', $item[0])),
                                "month" => intval(str_replace('"', '', $item[4])),
                                "year" => intval(str_replace('"', '', $item[5])),
                                "status" => str_replace('"', '', $item[6]),
                            ];

                            if ($this->pinjaman_model->do_lunas_by_month($newdata)) {
                                $import = true;
                            }else{
                                $import = false;
                            }
                        }
					}
					fclose($handle);

                    if($import){
                        $data['success'] = 1;
                        $data['message'] = "Import Data berhasil!";
                    }else{
                        $data['success'] = 0;
                        $data['error'] = "Import Data gagal!";
                    }

                } else {
                    $data['success'] = 0;
                    $data['error'] = "Format file tidak valid!";
				}
            }else{
                $data['success'] = 0;
                $data['error'] = "No file uploaded !";
            }
        }

		echo json_encode($data);
    }


}
