<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_sukarela_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'simpanan_sukarela.balance',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.id = simpanan_sukarela.person')
        ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select([
            "simpanan_sukarela.*",
            "person.nik",
            "person.name",
            "position.name position",
            "person.depo",
            "person.acc_no",
            "person.address",
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.id = simpanan_sukarela.person')
        ->join('position', 'position.id = person.position', 'left')
        ->where('simpanan_sukarela.id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('simpanan_sukarela', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_sukarela', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_sukarela');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_code()
    {
        $row = $this->db->select("
            CASE WHEN MAX(code) IS NOT NULL 
                AND MAX(code) > 0 
                AND MAX(code) != ''
            THEN MAX(code) + 1 
            ELSE '1000000001' END code
        ")
        ->from('simpanan_sukarela')
        ->order_by('code', 'DESC')
        ->limit(1)
        ->get()->row_array();

        if ($row) {
            return $row["code"];
        }else{
            return "1000000001";
        }
    }

}