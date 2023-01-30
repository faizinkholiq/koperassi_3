<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan_model extends CI_Model {

    public function get_dt_simpanan($p) 
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["month", "yea", "balance"];
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
            'pengajuan_simpanan.id',
            'pengajuan_simpanan.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'pengajuan_simpanan.type',
            'pengajuan_simpanan.month',
            'pengajuan_simpanan.year',
            'pengajuan_simpanan.status',
            'pengajuan_simpanan.balance',
            'pengajuan_simpanan.date',
            'pengajuan_simpanan.reason',
        ])
        ->from('pengajuan_simpanan')
        ->join('person', 'person.nik = pengajuan_simpanan.person')
        ->order_by('date', 'desc')
        ->order_by('type', 'asc');
        
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

    public function get_dt_simpanan_detail($p) 
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["month", "yea", "balance"];
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
            'pengajuan_simpanan.id',
            'pengajuan_simpanan.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'pengajuan_simpanan.type',
            'pengajuan_simpanan.month',
            'pengajuan_simpanan.year',
            'pengajuan_simpanan.status',
            'pengajuan_simpanan.balance',
            'pengajuan_simpanan.date',
            'pengajuan_simpanan.reason',
        ])
        ->from('pengajuan_simpanan')
        ->join('person', 'person.nik = pengajuan_simpanan.person')
        ->order_by('date', 'desc')
        ->order_by('type', 'asc');
        
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

}

