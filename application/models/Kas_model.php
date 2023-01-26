<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Kas_model extends CI_Model {

    public function detail($id)
    {
        return $this->db->get_where('kas', ['id' => $id ])->row_array();
    }

    public function create($data)
    {
        $this->db->insert('kas', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('kas', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kas');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
 }