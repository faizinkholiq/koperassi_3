<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Parameter_model extends CI_Model {

    public function detail($person)
    {
        return $this->db->get_where('parameter_sistem', ['person' => $person ])->row_array();
    }

    public function create($data)
    {
        $this->db->insert('parameter_sistem', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function edit($data)
    {   
        $this->db->where('person', $data['person']);
        unset($data['person']);
        $this->db->update('parameter_sistem', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($person)
    {
        $this->db->where('person', $person);
        $this->db->delete('parameter_sistem');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
 }