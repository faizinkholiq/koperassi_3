<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_pokok_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
                'simpanan_pokok.id',
                'simpanan_pokok.person person_id',
                'person.name',
                'person.nik',
                'person.tmk',
                'person.phone',
                'person.join_date',
                'simpanan_pokok.balance',
            ])
            ->from('simpanan_pokok')
            ->join('person', 'person.id = simpanan_pokok.person')
            ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select()
        ->from('simpanan_pokok')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('simpanan_pokok', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_pokok', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_pokok');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

}