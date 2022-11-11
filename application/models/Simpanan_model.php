<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_model extends CI_Model {

    public function get($person)
    {
        $q_pokok = $this->db->select([
                'simpanan_pokok.id',
                'simpanan_pokok.person person_id',
                'simpanan_pokok.code',
                'person.name',
                'person.nik',
                'person.tmk',
                'person.phone',
                'person.join_date',
                'person.depo',
                'person.address',
                'person.acc_no',
                'person.position',
                'position.name position_name',
                '"Simpanan Pokok" type',
                'simpanan_pokok.date',
                'simpanan_pokok.balance',
            ])
            ->from('simpanan_pokok')
            ->join('person', 'person.id = simpanan_pokok.person')
            ->join('position', 'person.position = position.id', 'left')
            ->where('person.id', $person)
            ->get_compiled_select();
        
        $q_wajib = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'simpanan_wajib.code',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Wajib" type',
            'simpanan_wajib.date',
            'simpanan_wajib.balance',
        ])
        ->from('simpanan_wajib')
        ->join('person', 'person.id = simpanan_wajib.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'simpanan_sukarela.code',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Sukarela" type',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.id = simpanan_sukarela.person')
        ->join('position', 'person.position = position.id', 'left')
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
                'COALESCE(simpanan_pokok.total, 0) 
                + COALESCE(simpanan_wajib.total, 0) 
                + COALESCE(simpanan_sukarela.total, 0) balance',
            ])
            ->from('person')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_pokok 
                GROUP BY person
            ) simpanan_pokok', 'person.id = simpanan_pokok.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_wajib 
                GROUP BY person
            ) simpanan_wajib', 'person.id = simpanan_wajib.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_sukarela 
                GROUP BY person
            ) simpanan_sukarela', 'person.id = simpanan_sukarela.person', 'left')
            ->where('person.id', $person)
            ->group_by('person.id')->get()->row_array()['balance'];

        return $data;
    }

}