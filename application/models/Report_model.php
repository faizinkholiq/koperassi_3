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

    public function get_data_simpanan($p = null) 
    {   
        $where = "";
        
        if(!empty($p['year'])){
            $where .= " AND year =".$p['year'];
        }

        if(!empty($p['month'])){
            $where .= " AND month =".$p['month'];
        }

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
            "COALESCE(simpanan_sukarela.balance, 0) - COALESCE(penarikan_sukarela.balance, 0) sukarela",
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
        ->join("(
            SELECT 
                id, 
                person, 
                SUM(balance) balance 
            FROM simpanan_wajib 
            WHERE posting = 1 $where 
            GROUP BY person
        ) simpanan_wajib", 'simpanan_wajib.person = person.nik', 'left')
        ->join("(
            SELECT 
                id, 
                person, 
                SUM(balance) balance 
            FROM simpanan_pokok 
            WHERE posting = 1 $where 
            GROUP BY person
        ) simpanan_pokok", 'simpanan_pokok.person = person.nik', 'left')
        ->join("(
            SELECT 
                id, 
                person, 
                SUM(balance) balance 
            FROM simpanan_sukarela 
            WHERE posting = 1 $where 
            GROUP BY person
        ) simpanan_sukarela", 'simpanan_sukarela.person = person.nik', 'left')
        ->join("(
            SELECT 
                id, 
                person, 
                SUM(balance) balance 
            FROM simpanan_investasi 
            WHERE posting = 1 $where 
            GROUP BY person
        ) simpanan_investasi", 'simpanan_investasi.person = person.nik', 'left')
        ->join("(
            SELECT 
                id, 
                person, 
                SUM(balance) balance 
            FROM penarikan_simpanan 
            WHERE type = 'Sukarela'
                AND status = 'Approved' $where
            GROUP BY person
        ) penarikan_sukarela", 'penarikan_sukarela.person = person.nik', 'left')
        ->where('user.role', '2')
        ->order_by('person.id', 'asc');
        
        $q = $this->db->get();

        return $q->result_array();
    }

    public function get_data_simpanan_detail($p = null) 
    {
        
        $where_simpanan = "";
        if(!empty($p['year'])){
            $where_simpanan .= " AND year = '".$p['year']."'";
        }

        if(!empty($p['from']) && !empty($p['to'])){
            $where_simpanan .= " AND month BETWEEN '".$p['from']. "' AND '".$p['to']."'";
        }

        $q_simpanan = $this->db->select([
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
                'Db' ket,
                'Simpanan Pokok' type
            FROM simpanan_pokok
            WHERE posting = 1 $where_simpanan
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Db' ket,
                'Simpanan Wajib' type
            FROM simpanan_wajib
            WHERE posting = 1 $where_simpanan
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Db' ket,
                'Simpanan Sukarela' type
            FROM simpanan_sukarela
            WHERE posting = 1 $where_simpanan
            UNION ALL
            SELECT 
                id,
                person,
                CAST(month AS DECIMAL) month,
                year,
                balance,
                'Db' ket,
                'Investasi' type
            FROM simpanan_investasi
            WHERE posting = 1 $where_simpanan
            ORDER BY year, type, CAST(month AS DECIMAL)
        ) simpanan", 'simpanan.person = person.nik')
        ->where('user.role', '2')
        ->group_by("person.id, simpanan.year, simpanan.month")
        ->get_compiled_select();
        
        if(!empty($p['year'])){
            $this->db->where('year', $p['year']);
        }

        if(!empty($p['from']) && !empty($p['to'])){
            $this->db->where("month BETWEEN '".$p['from']. "' AND '".$p['to']."'");
        }

        $q_penarikan = $this->db->select([
            "person.id",
            "person.nik",
            "person.name",
            "penarikan.year",
            "penarikan.month",
            "'Kr' ket",
            "SUM(CASE WHEN penarikan.type = 'Simpanan Pokok' THEN penarikan.balance ELSE 0 END) pokok", 
            "SUM(CASE WHEN penarikan.type = 'Simpanan Wajib' THEN penarikan.balance ELSE 0 END) wajib", 
            "SUM(CASE WHEN penarikan.type = 'Simpanan Sukarela' THEN penarikan.balance ELSE 0 END) sukarela", 
            "SUM(CASE WHEN penarikan.type = 'Investasi' THEN penarikan.balance ELSE 0 END) investasi"
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
                'Kr' ket,
                CASE 
                    WHEN type = 'Pokok' THEN 'Simpanan Pokok'
                    WHEN type = 'Wajib' THEN 'Simpanan Wajib'
                    WHEN type = 'Sukarela' THEN 'Simpanan Sukarela'
                    WHEN type = 'Investasi' THEN 'Investasi'
                END AS type
            FROM penarikan_simpanan
        ) penarikan", 'penarikan.person = person.nik')
        ->where('user.role', '2')
        ->group_by("person.id, penarikan.year, penarikan.month")
        ->get_compiled_select();

        // Get All Data
        $data = $this->db->query("
        SELECT *
        FROM (
            $q_simpanan UNION ALL 
            $q_penarikan
            ORDER BY id, year, month        
        ) simpanan")->result_array();
        
        return $data;
    }

}

