<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Pinjaman_model extends CI_Model {

    public function get($person)
    {
        return $this->db->select([
                'cicilan.id',
                'cicilan.pinjaman pinjaman_id',
                'person.id person_id',
                'cicilan.date',
                'cicilan.cicilan',
                'pinjaman.bunga',
                'cicilan.bayar',
                '0 sisa',
                '0 gaji',
            ])
            ->from('cicilan')
            ->join('pinjaman', 'pinjaman.id = cicilan.pinjaman')
            ->join('person', 'person.nik = pinjaman.person')
            ->where('person.id', $person)
            ->group_by('cicilan.id')
            ->get()->result_array();
    }

    public function summary($person)
    {
        $data['plafon'] = 0; 
        $data['limit'] = 0; 
        $data['sisa'] = $this->db->select([
                'COALESCE(SUM(pinjaman.balance), 0) balance',
            ])
            ->from('person')
            ->join('pinjaman', 'person.nik = pinjaman.person', 'left')
            ->where('person.id', $person)
            ->group_by('person.id')->get()->row_array()['balance'];

        return $data;
    }

}