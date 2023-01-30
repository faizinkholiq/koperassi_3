<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function get_dt_simpanan($p) 
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
            "person.id",
            "person.nik",
            "person.name",
            "person.depo depo_id",
            "depo.code depo_code",
            "depo.name depo",
            "person.position position_id",
            "position.name position",
            "COALESCE(FORMAT(simpanan_wajib.balance, 0, 'id_ID'), 0) wajib",
            "COALESCE(FORMAT(simpanan_pokok.balance, 0, 'id_ID'), 0) pokok",
            "COALESCE(FORMAT(simpanan_sukarela.balance, 0, 'id_ID'), 0) sukarela",
            "COALESCE(FORMAT(simpanan_investasi.balance, 0, 'id_ID'), 0) investasi",
            "FORMAT(
                COALESCE(simpanan_wajib.balance, 0) + 
                COALESCE(simpanan_pokok.balance, 0) + 
                COALESCE(simpanan_sukarela.balance, 0) + 
                COALESCE(simpanan_investasi.balance, 0), 
            0, 'id_ID') total",
        ])
        ->from('person')
        ->join('user', 'user.id = person.user_id', 'left')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->join('position', 'position.id = person.position', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_wajib GROUP BY person) simpanan_wajib', 'simpanan_wajib.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_pokok GROUP BY person) simpanan_pokok', 'simpanan_pokok.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_sukarela GROUP BY person) simpanan_sukarela', 'simpanan_sukarela.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_investasi GROUP BY person) simpanan_investasi', 'simpanan_investasi.person = person.nik', 'left')
        ->where('user.role', '2')
        ->order_by('person.id', 'asc');
        
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

    public function get_data() 
    {
        $this->db->select([
            "person.id",
            "person.nik",
            "person.name",
            "person.depo depo_id",
            "depo.code depo_code",
            "depo.name depo",
            "person.position position_id",
            "position.name position",
            "COALESCE(FORMAT(simpanan_wajib.balance, 0, 'id_ID'), 0) wajib",
            "COALESCE(FORMAT(simpanan_pokok.balance, 0, 'id_ID'), 0) pokok",
            "COALESCE(FORMAT(simpanan_sukarela.balance, 0, 'id_ID'), 0) sukarela",
            "COALESCE(FORMAT(simpanan_investasi.balance, 0, 'id_ID'), 0) investasi",
            "FORMAT(
                COALESCE(simpanan_wajib.balance, 0) + 
                COALESCE(simpanan_pokok.balance, 0) + 
                COALESCE(simpanan_sukarela.balance, 0) + 
                COALESCE(simpanan_investasi.balance, 0), 
            0, 'id_ID') total",
        ])
        ->from('person')
        ->join('user', 'user.id = person.user_id', 'left')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->join('position', 'position.id = person.position', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_wajib GROUP BY person) simpanan_wajib', 'simpanan_wajib.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_pokok GROUP BY person) simpanan_pokok', 'simpanan_pokok.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_sukarela GROUP BY person) simpanan_sukarela', 'simpanan_sukarela.person = person.nik', 'left')
        ->join('(SELECT id, person, SUM(balance) balance FROM simpanan_investasi GROUP BY person) simpanan_investasi', 'simpanan_investasi.person = person.nik', 'left')
        ->where('user.role', '2')
        ->order_by('person.id', 'asc');
        
        $q = $this->db->get();

        return $q->result_array();
    }


}

