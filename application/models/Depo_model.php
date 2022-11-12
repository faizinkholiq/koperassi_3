<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Depo_model extends CI_Model {

    public function get()
    {
        return $this->db->get('depo')->result_array();
    }

    public function detail($id)
    {
        return $this->db->get_where('depo', ["id" => $id])->row_array();
    }

    public function create($data)
    {
        $this->db->insert('depo', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('depo', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('depo');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
}