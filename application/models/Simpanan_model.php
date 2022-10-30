<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_model extends CI_Model {

    public function get($person)
    {
        $q_pokok = $this->db->select([
                'simpanan_pokok.id',
                'simpanan_pokok.person person_id',
                'person.name',
                'person.nik',
                'person.tmk',
                'person.phone',
                'person.join_date',
                '"Simpanan Pokok" type',
                'simpanan_pokok.date',
                'simpanan_pokok.balance',
            ])
            ->from('simpanan_pokok')
            ->join('person', 'person.id = simpanan_pokok.person')
            ->where('person.id', $person)
            ->get_compiled_select();
        
        $q_wajib = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            '"Simpanan Wajib" type',
            'simpanan_wajib.date',
            'simpanan_wajib.balance',
        ])
        ->from('simpanan_wajib')
        ->join('person', 'person.id = simpanan_wajib.person')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            '"Simpanan Sukarela" type',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.id = simpanan_sukarela.person')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_all = $this->db->query($q_pokok.' UNION ALL '.$q_wajib. ' UNION ALL '.$q_sukarela.' ORDER BY date DESC');
        return $q_all->result_array();
    }

    public function summary($person)
    {
        $data['plafon'] = 0; 
        $data['limit'] = 0; 
        $data['gaji'] = 0; 
        $data['simpanan'] = $this->db->select([
                'COALESCE(SUM(simpanan_pokok.balance), 0) + COALESCE(SUM(simpanan_wajib.balance), 0) + COALESCE(SUM(simpanan_sukarela.balance), 0) balance',
            ])
            ->from('person')
            ->join('simpanan_pokok', 'person.id = simpanan_pokok.person', 'left')
            ->join('simpanan_wajib', 'person.id = simpanan_wajib.person', 'left')
            ->join('simpanan_sukarela', 'person.id = simpanan_sukarela.person', 'left')
            ->where('person.id', $person)
            ->group_by('person.id')->get()->row_array()['balance'];

        return $data;
    }

}