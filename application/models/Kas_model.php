<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Kas_model extends CI_Model {

    public function get_dt($p)
    {
        $search = $p["search"];
        
        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = [];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

            if ($src){
                $this->db->group_start();
                foreach($col as $key => $val){
                    $this->db->or_group_start();
                    foreach($src_arr as $k => $v){
                        $this->db->like($val, $v, 'both'); 
                    }
                    $this->db->group_end();
                }
                $this->db->group_end();
            }
		}

        $limit = $p["length"];
		$offset = $p["start"];

        $this->db->select([
            "kas.id",
            "kas.date",
            "kas.year",
            "kas.debet",
            "kas.kredit",
            "kas.total",
            "kas.updated_by",
        ])
        ->from('kas_koperasi kas')
        ->order_by('kas.id', 'asc');
        
        $q = $this->db->get();
        $data["recordsTotal"] = $q->num_rows();
        $data["recordsFiltered"] = $q->num_rows();
        
        $this->db->stop_cache();

        $this->db->limit($limit, $offset);
        
        $data["data"] = $this->db->get()->result_array();
        $data["draw"] = intval($p["draw"]);

        $this->db->flush_cache();

        return $data;
    }

    public function detail($id)
    {
        return $this->db->get_where('kas_koperasi', ['id' => $id ])->row_array();
    }

    public function create($data)
    {
        $this->db->insert('kas_koperasi', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('kas_koperasi', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kas_koperasi');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_summary()
    {        
        $kas = $this->db->query("
            SELECT
            SUM(COALESCE(debet, 0)) - SUM(COALESCE(kredit, 0)) total
            FROM kas_koperasi;
        ")->row_array();
        $data["kas"] = isset($kas["total"]) && !empty($kas["total"])? $kas["total"] : 0;

        return $data;
    }

    public function detail_by_year($year)
    {
        return $this->db->get_where('kas_koperasi', ['year' => $year ])->row_array();
    }
    
 }