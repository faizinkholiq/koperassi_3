<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Pinjaman_model extends CI_Model {

    public function summary($person)
    {
        $data['plafon'] = 0; 
        $data['limit'] = 0; 
        $data['sisa'] = $this->db->select([
                'COALESCE(SUM(pinjaman.balance), 0) balance',
            ])
            ->from('person')
            ->join('pinjaman', 'person.nik = pinjaman.person', 'left')
            ->where('person.id', $person)
            ->group_by('person.id')->get()->row_array()['balance'];

        return $data;
    }

    public function get_dt($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["date", "year", "month", "limit", "balance"];
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

        if (isset($p['person']) && !empty($p['person'])){
            $this->db->where('pinjaman.person', $p['person']);
        }

        $this->db->select([
            'pinjaman.id',
            'pinjaman.person',
            'pinjaman.date',
            'pinjaman.year',
            'pinjaman.month',
            'pinjaman.balance', 
            'pinjaman.angsuran',
            '0 angsuran_paid',
            'pinjaman.status',
        ])
        ->from('pinjaman')
        ->order_by('date', 'desc');
        
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

    public function create($data)
    {
        $this->db->insert('pinjaman', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('pinjaman', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pinjaman');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail($id)
    {
        return $this->db->get_where('pinjaman', ["id" => $id])->row_array();
    }

    public function get_angsuran($person)
    {
        return $this->db->select([
            'angsuran.id',
            'pinjaman.id pinjaman_id',
            'person.id person_id',
            'angsuran.date',
            'angsuran.year',
            'angsuran.month',
            'angsuran.month_no',
            'angsuran.pokok',
            'angsuran.bunga',
            'angsuran.status',
        ])
        ->from('angsuran')
        ->join('pinjaman', 'pinjaman.id = angsuran.pinjaman')
        ->join('person', 'person.nik = pinjaman.person')
        ->where('person.id', $person)
        ->group_by('angsuran.id')
        ->get()->result_array();
    }

    public function get_dt_angsuran($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["year", "month", "month_no", "pokok", "status"];
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

        if (isset($p['person']) && !empty($p['person'])){
            $this->db->where('pinjaman.person', $p['person']);
        }

        $this->db->select([
            'pinjaman.id',
            'pinjaman.person',
            'pinjaman.date',
            'pinjaman.year',
            'pinjaman.month',
            'pinjaman.balance', 
        ])
        ->from('pinjaman')
        ->order_by('date', 'desc');
        
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

    public function create_angsuran($data)
    {
        $this->db->insert('angsuran', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_angsuran($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('angsuran', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_angsuran($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('angsuran');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail_angsuran($id)
    {
        return $this->db->get_where('angsuran', ["id" => $id])->row_array();
    }

}