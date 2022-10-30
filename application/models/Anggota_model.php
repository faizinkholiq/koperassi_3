<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Anggota_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select()->from('person')->order_by('id', 'asc');
        return $q->get()->result_array();
    }

    public function list()
    {
        $q = $this->db->select()
            ->from('person')
            ->where('status', 'Aktif')
            ->order_by('id', 'asc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select([
            'person.*',
            'person_family.id id_family',
            'person_family.name name_family',
            'person_family.address address_family',
            'person_family.phone phone_family',
            'person_family.status status_family',
        ])
        ->from('person')
        ->join('person_family', 'person.id = person_family.person_id', 'left')
        ->where('person.id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('person', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('person', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('person');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_keluarga()
    {
        return $this->db->select()->from('person_family')->order_by('id', 'asc')->get()->result_array();
    }

    public function detail_keluarga($id)
    {
        return $this->db->select()
        ->from('person_family')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create_keluarga($data)
    {
        $this->db->insert('person_family', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_keluarga($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('person_family', $data);

        return ($this->db->affected_rows()>0) ? true : false;
    }

    public function delete_keluarga($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('person_family');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

}