<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_pokok_model extends CI_Model {

    public function get()
    {
        $q = $this->db->select([
                'simpanan_pokok.id',
                'person.id person_id',
                'person.name',
                'person.no_ktp',
                'person.nik',
                'person.phone',
                'person.join_date',
                'simpanan_pokok.date',
                'simpanan_pokok.balance',
            ])
            ->from('simpanan_pokok')
            ->join('person', 'person.nik = simpanan_pokok.person')
            ->order_by('date', 'desc');
        return $q->get()->result_array();
    }

    public function get_dt($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["person.ktp", "person.nik", "person.name", "person.phone", "person.join_date", "simpanan_pokok.balance", "year", "month", "dk"];
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
            $this->db->where('simpanan_pokok.month', $p['month']);
        }

        if (!empty($p["year"])) {
            $this->db->where('simpanan_pokok.year', $p['year']);
        }

        $limit = $p["length"];
		$offset = $p["start"];

        $this->db->select([
            'simpanan_pokok.id',
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
            'simpanan_pokok.year',
            'simpanan_pokok.month',
            'simpanan_pokok.dk',
            'simpanan_pokok.date',
            'simpanan_pokok.balance',
            'simpanan_pokok.posting',
            'ROW_NUMBER() OVER(ORDER BY year, CAST(month AS DECIMAL)) AS row_no'
        ])
        ->from('simpanan_pokok')
        ->join('person', 'person.nik = simpanan_pokok.person')
        ->join('position', 'position.id = person.position', 'left')
        ->order_by("year, CAST(month AS DECIMAL)");
        
        $q = $this->db->get();
        $data["recordsTotal"] = $q->num_rows();
        $data["recordsFiltered"] = $q->num_rows();
        
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
        return $this->db->select()
        ->from('simpanan_pokok')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('simpanan_pokok', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_pokok', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_pokok');
        
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
        ->from('simpanan_pokok')
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
            $this->db->where('simpanan_pokok.person', $p['person']);
        }

        if (!empty($p["month"]) && $p['month'] != 'all') {
            $this->db->where('simpanan_pokok.month', $p['month']);
        }

        if (!empty($p["year"])) {
            $this->db->where('simpanan_pokok.year', $p['year']);
        }

        $data = $this->db->select('SUM(balance) total')->from('simpanan_pokok')->get()->row_array();
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
            $this->db->insert('simpanan_pokok', $item);
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