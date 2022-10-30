<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_sukarela_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'person.name',
            'person.nik',
            'person.tmk',
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
        return $this->db->select()
        ->from('simpanan_sukarela')
        ->where('id',$id)
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

}