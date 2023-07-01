<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pinjaman_barang extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
            'user_model',
            'pinjaman_barang_model',
            'person_model',
            'kas_model',
        ]);
    }

	public function index()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman Barang";
        $d['highlight_menu'] = "pinjaman_barang";
        
        if (!check_permission('pinjaman_barang', $d['role'])){
            redirect('home');
        }else{
            if($d['role'] == 1){
                $d['content_view'] = 'pinjaman_barang/index_admin';
            }else{
                $d['content_view'] = 'pinjaman_barang/index';
            }

            $this->load->view('layout/template', $d);
        }
	}

    public function angsuran()
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Angsuran Barang";
        $d['highlight_menu'] = "angsuran_barang";
        $d['content_view'] = 'pinjaman_barang/angsuran';

        if (!check_permission('pinjaman_barang', $d['role'])){
            redirect('home');
        }else{
            $d['data'] = $this->pinjaman_barang_model->get_angsuran($d["nik"]);
            $d['summary'] = count($d['data']) > 0 ? $this->pinjaman_barang_model->get_summary_angsuran($d["data"][0]["pinjaman_id"]) : [];

            $this->load->view('layout/template', $d);
        }
	}

    public function detail($id)
	{
        $d = $this->user_model->login_check();
        $d['title'] = "Pinjaman Barang";
        $d['highlight_menu'] = "pinjaman_barang";
        
        if (!check_permission('pinjaman_barang', $d['role'])){
            redirect('home');
        }else{
            $d['detail'] = $this->pinjaman_barang_model->full_detail($id);
            $d['content_view'] = 'pinjaman_barang/detail';
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

        $data = $this->pinjaman_barang_model->get_dt($params);

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

        $data = $this->pinjaman_barang_model->get_dt_all($params);

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
            $data["name"] = $this->input->post('name');
            $data["month"] = $this->input->post('month');
            $data["year"] = $this->input->post('year');
            $data["buy"] = $this->input->post('buy');
            $data["angsuran"] = $this->input->post('angsuran');
        }

        return $data;
    }

    public function create()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $nd = $this->get_input();
            if(!$nd){
                $data['success'] = 0;
                $data['error'] = "Invalid Person !";
            }else{
                $pinjaman_now = $this->pinjaman_barang_model->get_by_person($nd['person']);
                if (count($pinjaman_now) > 0) {
                    $data['success'] = 0;
                    $data['error'] = "Pengajuan peminjaman barang ditolak karena masih ada pinjaman yang belum terselesaikan !";
                } else {
                    $nd['status'] = 'Pending';
                    $pinjaman_id = $this->pinjaman_barang_model->create($nd);
    
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
		redirect('pinjaman_barang');
	}

    public function edit()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd = $this->get_input();
            $nd['status'] = 'Pending';

            $detail = $this->pinjaman_barang->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_barang->edit($nd)) {
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
		redirect('pinjaman_barang');
	}

    public function delete() 
    {
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->get('id');
            if ($this->pinjaman_barang_model->delete($id)) {
                $data['success'] = 1;
                $data['message'] = "Berhasil menghapus data !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Gagal menghapus data !";
            }
        }

        $this->session->set_flashdata('msg', $data);
        redirect('pinjaman_barang');
    }

    public function approve()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Approved';
            $detail = $this->pinjaman_barang_model->detail($id);
            
            if ($detail) {
                $profit = $detail["buy"] * (0.5/100) * $detail["angsuran"];
                $nd["sell"] = $profit + $detail["buy"];
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_barang_model->edit($nd)) {
                    $year = $detail["year"];
                    $month = $detail["month"];
                    $angsuran = $nd["sell"] / $detail["angsuran"];

                    for ($i=1; $i <= $detail["angsuran"]; $i++) {
                        if($month%12 == 1) $month = 1;

                        $nd_angsuran["pinjaman"] = $detail["id"];
                        $nd_angsuran["year"] = $year;
                        $nd_angsuran["month"] = $month;
                        $nd_angsuran["month_no"] = $i;
                        $nd_angsuran["angsuran"] = $angsuran;
                        $nd_angsuran["status"] = "Belum Lunas";

                        $this->pinjaman_barang_model->create_angsuran($nd_angsuran);
                        if($month >= 12) $year++;
                        $month++;
                    }

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
		redirect('pinjaman_barang');
	}

    public function reject()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Decline';
            $nd['reason'] = $this->input->post('reason');
            $detail = $this->pinjaman_barang_model->detail($id);
            if ($detail) {
                $nd['id'] = $detail['id'];
                if ($this->pinjaman_barang_model->edit($nd)) {
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
		redirect('pinjaman_barang');
	}

    public function paid()
	{
        $d = $this->user_model->login_check();
        if (!check_permission('pinjaman_barang', $d['role'])){
            $data['success'] = 0;
            $data['error'] = "No Permission !";
            $this->session->set_flashdata('msg', $data);  
            redirect('home');
        }else{
            $id = $this->input->post('id');
            $nd['status'] = 'Lunas';
            $nd['date'] = $this->input->post('date');
            $detail = $this->pinjaman_barang_model->detail_angsuran($id);
            if ($detail) {
                $detail_pinjaman = $this->pinjaman_barang_model->detail($detail['pinjaman']);
                if($detail_pinjaman) {
                    $nd['id'] = $detail['id'];
                    if ($this->pinjaman_barang_model->edit_angsuran($nd)) {
                        $data['success'] = 1;
                        $data['message'] = "Data berhasil tersimpan !";
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
            redirect('pinjaman_barang/detail/'.$detail['pinjaman']);
        }

	}
}
