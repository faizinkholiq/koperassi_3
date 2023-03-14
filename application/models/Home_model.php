<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function get_summary_admin()
    {
        $data['pokok']['all'] = rupiah($this->get_total_simpanan('simpanan_pokok'));
        $data['wajib']['all'] = rupiah($this->get_total_simpanan('simpanan_wajib'));
        $data['sukarela']['all'] = rupiah(floatval($this->get_total_simpanan('simpanan_sukarela')) - floatval($this->get_total_penarikan()));
        $data['investasi']['all'] = rupiah($this->get_total_simpanan('simpanan_investasi'));
        
        $p['month'] = date('m');
        $p['year'] = date('Y');

        $data['pokok']['now'] = rupiah($this->get_total_simpanan('simpanan_pokok', $p));
        $data['wajib']['now'] = rupiah($this->get_total_simpanan('simpanan_wajib', $p));
        $data['sukarela']['now'] = rupiah(floatval($this->get_total_simpanan('simpanan_sukarela', $p)) - floatval($this->get_total_penarikan($p)));
        $data['investasi']['now'] = rupiah($this->get_total_simpanan('simpanan_investasi', $p));
        
        $data['total'] = rupiah(
            floatval($this->get_total_simpanan('simpanan_pokok')) + 
            floatval($this->get_total_simpanan('simpanan_wajib')) + 
            floatval($this->get_total_simpanan('simpanan_sukarela')) + 
            floatval($this->get_total_simpanan('simpanan_investasi')) -
            floatval($this->get_total_penarikan())
        );

        return $data;
    }

    public function get_summary_member($person)
    {
        $p['person'] = $person;
        $data['pokok'] = rupiah($this->get_total_simpanan('simpanan_pokok', $p));
        $data['wajib'] = rupiah($this->get_total_simpanan('simpanan_wajib', $p));
        $data['sukarela'] = rupiah(floatval($this->get_total_simpanan('simpanan_sukarela', $p)) - floatval($this->get_total_penarikan($p)));
        $data['investasi'] = rupiah($this->get_total_simpanan('simpanan_investasi', $p));
        $data['penarikan'] = rupiah($this->get_total_penarikan(), $p);
        $data['pinjaman'] = 0;
        
        return $data;
    }

    private function get_total_simpanan($table, $p = null) {
        if (isset($p['person']) && !empty($p['person'])) {
            $this->db->where('person', $p['person']);
        }

        if (isset($p['month']) && !empty($p['month'])) {
            $this->db->where('month', (int)$p['month']);
        }

        if (isset($p['year']) && !empty($p['year'])) {
            $this->db->where('year', $p['year']);
        }

        $data = $this->db->select('SUM(balance) total')->from($table)->get()->row_array();
        if (!empty($data)) {
            return $data['total'];
        }else{
            return 0;
        }
    }

    private function get_total_penarikan($p = null) {
        if (isset($p['person']) && !empty($p['person'])) {
            $this->db->where('person', $p['person']);
        }

        if (isset($p['month']) && !empty($p['month'])) {
            $this->db->where('month', (int)$p['month']);
        }

        if (isset($p['year']) && !empty($p['year'])) {
            $this->db->where('year', $p['year']);
        }

        $this->db->where("type", 'Sukarela');
        $this->db->where("status", 'Approved');
        $data = $this->db->select('SUM(balance) total')->from('penarikan_simpanan')->get()->row_array();
        if (!empty($data)) {
            return $data['total'];
        }else{
            return 0;
        }
    }

}