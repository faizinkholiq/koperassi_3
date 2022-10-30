<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_wajib_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            'simpanan_wajib.balance',
        ])
        ->from('simpanan_wajib')
        ->join('person', 'person.id = simpanan_wajib.person')
        ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select()
        ->from('simpanan_wajib')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('simpanan_wajib', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_wajib', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_wajib');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

}