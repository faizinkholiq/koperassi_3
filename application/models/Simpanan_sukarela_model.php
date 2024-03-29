<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_sukarela_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
            'simpanan_sukarela.id',
            'person.id person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.nik = simpanan_sukarela.person')
        ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function get_dt($p)
    {
        $search = $p["search"];
        $limit = $p["length"];
		$offset = $p["start"];

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'person.id person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            "position.name position_name",
            'simpanan_sukarela.year',
            'simpanan_sukarela.month',
            '"D" dk',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
            'simpanan_sukarela.posting',
            'ROW_NUMBER() OVER(ORDER BY year, CAST(month AS DECIMAL)) AS row_no'
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.nik = simpanan_sukarela.person')
        ->join('position', 'position.id = person.position', 'left')
        ->get_compiled_select();

        $q_w_sukarela = $this->db->select([
            'penarikan_simpanan.id',
            'person.id person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            "position.name position_name",
            'penarikan_simpanan.year',
            'penarikan_simpanan.month',
            '"K" dk',
            'penarikan_simpanan.date',
            'penarikan_simpanan.balance',
            '1 posting',
            'ROW_NUMBER() OVER(ORDER BY year, CAST(month AS DECIMAL)) AS row_no'
        ])
        ->from('penarikan_simpanan')
        ->join('person', 'person.nik = penarikan_simpanan.person')
        ->join('position', 'position.id = person.position', 'left')
        ->where('penarikan_simpanan.status', 'Approved')
        ->get_compiled_select();
        
        // Get All Data
        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["no_ktp", "nik", "name", "phone", "join_date", "balance", "year", "month", "dk"];
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

        if (!empty($p["month"]) && $p['month'] != 'all') {
            $this->db->where('month', $p['month']);
        }

        if (!empty($p["year"])) {
            $this->db->where('year', $p['year']);
        }

        $this->db->select([
            "simpanan.*",
            "ROW_NUMBER() OVER(ORDER BY 1) AS row_no"
        ])
        ->from("(
            $q_sukarela UNION ALL 
            $q_w_sukarela ORDER BY 
                year, 
                CAST(month AS DECIMAL)
        ) simpanan");

        $q_all = $this->db->get();
        $data["recordsTotal"] = $q_all->num_rows();
        $data["recordsFiltered"] = $q_all->num_rows();
        
        $this->db->stop_cache();

        $this->db->limit($limit, $offset);
        
        $data["data"] = $this->db->get()->result_array();
        $data["draw"] = intval($p["draw"]);
        $this->db->flush_cache();
        
        $data["total"] = $this->get_total($p);

        return $data;
    }

    public function detail($id)
    {
        return $this->db->select([
            "simpanan_sukarela.*",
            "person.nik",
            "person.name",
            "position.name position",
            "person.depo",
            "person.acc_no",
            "person.address",
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.nik = simpanan_sukarela.person')
        ->join('position', 'position.id = person.position', 'left')
        ->where('simpanan_sukarela.id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('simpanan_sukarela', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_sukarela', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_sukarela');
        
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
        ->from('simpanan_sukarela')
        ->order_by('code', 'DESC')
        ->limit(1)
        ->get()->row_array();

        if ($row) {
            return $row["code"];
        }else{
            return "1000000001";
        }
    }

    private function get_total($p = []) {
        if (isset($p['person'])) {
            $this->db->where('simpanan_sukarela.person', $p['person']);
        }

        if (!empty($p["month"]) && $p['month'] != 'all') {
            $this->db->where('simpanan_sukarela.month', $p['month']);
        }

        if (!empty($p["year"])) {
            $this->db->where('simpanan_sukarela.year', $p['year']);
        }

        $data = $this->db->select('SUM(balance) total')->from('simpanan_sukarela')->get()->row_array();
        if (!empty($data)) {
            return $data['total'];
        }else{
            return 0;
        }
    }

    public function import($data)
    {
        $this->db->trans_begin();

        foreach ($data as $key => $item) {
            $this->db->insert('simpanan_sukarela', $item);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}