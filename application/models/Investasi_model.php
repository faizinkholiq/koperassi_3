<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Investasi_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'investasi.id',
            'investasi.person person_id',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            'investasi.balance',
        ])
        ->from('investasi')
        ->join('person', 'person.id = investasi.person')
        ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select()
        ->from('investasi')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('investasi', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('investasi', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('investasi');
        
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
        ->from('investasi')
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