<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Anggota_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'person.*',
            'position.name position_name',
        ])
        ->from('person')
        ->join('position', 'person.position = position.id', 'left')
        ->order_by('id', 'asc');
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
            'position.name position_name',
        ])
        ->from('person')
        ->join('person_family', 'person.id = person_family.person_id', 'left')
        ->join('position', 'person.position = position.id', 'left')
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

    public function get_list_position()
    {
        $q = $this->db->select()->from('position')->order_by('id', 'desc');
        return $q->get()->result_array();
    }

    public function create_temp($data)
    {
        $this->db->insert('person_temp', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_temp($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('person_temp', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function detail_temp($id)
    {
        return $this->db->get_where('person_temp', ["person_id" => $id])->row_array();
    }

    public function delete_temp($id)
    {
        $this->db->where('person_id', $id);
        $this->db->delete('person_temp');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
}