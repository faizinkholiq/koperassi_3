<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function get_summary_admin($person)
    {
        $this->db->select([
            "CONCAT('RP', FORMAT(SUM(simpanan_pokok.balance), 0, 'id_ID')) pokok",
            "CONCAT('RP', FORMAT(SUM(simpanan_wajib.balance), 0, 'id_ID')) wajib",
            "CONCAT('RP', FORMAT(SUM(simpanan_sukarela.balance), 0, 'id_ID')) sukarela",
            "CONCAT('RP', FORMAT(SUM(simpanan_investasi.balance), 0, 'id_ID')) investasi",
            "0 pinjaman",
        ])
        ->from('person')
        ->join('simpanan_pokok', 'simpanan_pokok.person = person.nik', 'left')
        ->join('simpanan_wajib', 'simpanan_wajib.person = person.nik', 'left')
        ->join('simpanan_sukarela', 'simpanan_sukarela.person = person.nik', 'left')
        ->join('simpanan_investasi', 'simpanan_investasi.person = person.nik', 'left')
        ->where('person.nik', $person);
        $data = $this->db->get()->row_array();
        
        return $data;
    }

    public function get_summary_member($person)
    {
        $this->db->select([
            "CONCAT('RP', FORMAT(SUM(simpanan_pokok.balance), 0, 'id_ID')) pokok",
            "CONCAT('RP', FORMAT(SUM(simpanan_wajib.balance), 0, 'id_ID')) wajib",
            "CONCAT('RP', FORMAT(SUM(simpanan_sukarela.balance), 0, 'id_ID')) sukarela",
            "CONCAT('RP', FORMAT(SUM(simpanan_investasi.balance), 0, 'id_ID')) investasi",
            "0 pinjaman",
        ])
        ->from('person')
        ->join('simpanan_pokok', 'simpanan_pokok.person = person.nik', 'left')
        ->join('simpanan_wajib', 'simpanan_wajib.person = person.nik', 'left')
        ->join('simpanan_sukarela', 'simpanan_sukarela.person = person.nik', 'left')
        ->join('simpanan_investasi', 'simpanan_investasi.person = person.nik', 'left')
        ->where('person.nik', $person);
        $data = $this->db->get()->row_array();
        
        return $data;
    }

}