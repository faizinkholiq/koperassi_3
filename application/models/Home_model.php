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
            "CASE WHEN simpanan_pokok.total IS NOT NULL THEN CONCAT('RP', FORMAT(simpanan_pokok.total, 0, 'id_ID')) ELSE 0 END pokok",
            "CASE WHEN simpanan_wajib.total IS NOT NULL THEN CONCAT('RP', FORMAT(simpanan_wajib.total, 0, 'id_ID')) ELSE 0 END wajib",
            "CASE WHEN simpanan_sukarela.total IS NOT NULL THEN CONCAT('RP', FORMAT(simpanan_sukarela.total - penarikan_sukarela.total, 0, 'id_ID')) ELSE 0 END sukarela",
            "CASE WHEN simpanan_investasi.total IS NOT NULL THEN CONCAT('RP', FORMAT(simpanan_investasi.total, 0, 'id_ID')) ELSE 0 END investasi",
            "0 pinjaman",
        ])
        ->from('person')
        ->join('(SELECT id, person, COALESCE(SUM(balance), 0) total FROM simpanan_pokok GROUP BY person) simpanan_pokok', 'simpanan_pokok.person = person.nik', 'left')
        ->join('(SELECT id, person, COALESCE(SUM(balance), 0) total FROM simpanan_wajib GROUP BY person) simpanan_wajib', 'simpanan_wajib.person = person.nik', 'left')
        ->join('(SELECT id, person, COALESCE(SUM(balance), 0) total FROM simpanan_sukarela GROUP BY person) simpanan_sukarela', 'simpanan_sukarela.person = person.nik', 'left')
        ->join('(SELECT id, person, COALESCE(SUM(balance), 0) total FROM simpanan_investasi GROUP BY person) simpanan_investasi', 'simpanan_investasi.person = person.nik', 'left')
        ->join('(SELECT id, person, COALESCE(SUM(balance), 0) total FROM penarikan_simpanan WHERE type = "Sukarela" AND status = "Approved" GROUP BY person) penarikan_sukarela', 'penarikan_sukarela.person = person.nik', 'left')
        ->where('person.nik', $person);
        $data = $this->db->get()->row_array();
        
        return $data;
    }

}