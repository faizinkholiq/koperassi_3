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

    public function get_data_simpanan() 
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
            "COALESCE(simpanan_wajib.balance, 0) wajib",
            "COALESCE(simpanan_pokok.balance, 0) pokok",
            "COALESCE(simpanan_sukarela.balance, 0) sukarela",
            "COALESCE(simpanan_investasi.balance, 0) investasi",
            "COALESCE(simpanan_wajib.balance, 0) 
                + COALESCE(simpanan_pokok.balance, 0) 
                + COALESCE(simpanan_sukarela.balance, 0) 
                + COALESCE(simpanan_investasi.balance, 0) total",
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

    public function get_data_simpanan_detail() 
    {
        $this->db->select([
            "person.id",
            "person.nik",
            "person.name",
            "simpanan.year",
            "simpanan.month",
            "'Db' ket",
            "SUM(CASE WHEN simpanan.type = 'Simpanan Pokok' THEN simpanan.balance ELSE 0 END) pokok", 
            "SUM(CASE WHEN simpanan.type = 'Simpanan Wajib' THEN simpanan.balance ELSE 0 END) wajib", 
            "SUM(CASE WHEN simpanan.type = 'Simpanan Sukarela' THEN simpanan.balance ELSE 0 END) sukarela", 
            "SUM(CASE WHEN simpanan.type = 'Investasi' THEN simpanan.balance ELSE 0 END) investasi"
        ])
        ->from('person')
        ->join('user', 'user.id = person.user_id')
        ->join("(
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Simpanan Pokok' type
            FROM simpanan_pokok
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Simpanan Wajib' type
            FROM simpanan_wajib
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Simpanan Sukarela' type
            FROM simpanan_sukarela
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Investasi' type
            FROM simpanan_investasi
            ORDER BY year, type, CAST(month AS DECIMAL)
        ) simpanan", 'simpanan.person = person.nik')
        ->where('user.role', '2')
        ->group_by("person.id, simpanan.year, simpanan.month")
        ->order_by('person.id, simpanan.year, simpanan.month');
        
        $q = $this->db->get();

        return $q->result_array();
    }

}

