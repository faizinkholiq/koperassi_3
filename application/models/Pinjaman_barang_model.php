<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Pinjaman_barang_model extends CI_Model {

    public function get_dt($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = [
                "pinjaman_barang.date", 
                "pinjaman_barang.name", 
                "pinjaman_barang.buy", 
                "pinjaman_barang.sell", 
                "pinjaman_barang.angsuran",
                "pinjaman_barang.status",
            ];
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
            $this->db->where('pinjaman_barang.person', $p['person']);
        }

        if(!empty($p["status"])){
            $this->db->having("status_angsuran", $p["status"]);
        }

        $this->db->select([
            'pinjaman_barang.id',
            'pinjaman_barang.person',
            'pinjaman_barang.date',
            'pinjaman_barang.name',
            'pinjaman_barang.buy',
            'pinjaman_barang.sell',
            'pinjaman_barang.angsuran',
            'pinjaman_barang.status',
            'COUNT(DISTINCT angsuran_barang.id) angsuran_paid',
            "CASE WHEN COUNT(DISTINCT angsuran_barang.id) = pinjaman_barang.angsuran 
            THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran",
        ])
        ->from('pinjaman_barang')
        ->join('(
            SELECT 
                person.nik,
                person.name,
                person.salary,
                depo.name depo
            FROM person
            LEFT JOIN depo ON depo.id = person.depo
            GROUP BY person.nik
        ) person', 'person.nik = pinjaman_barang.person')
        ->join('angsuran_barang', "angsuran_barang.pinjaman = pinjaman_barang.id AND angsuran_barang.status = 'Lunas'", 'left')
        ->order_by('person.nik ASC, pinjaman_barang.date DESC')
        ->group_by('pinjaman_barang.id');
        
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

    public function get_dt_all($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = [
                "person.nik", 
                "person.name", 
                "pinjaman_barang.date", 
                "pinjaman_barang.name", 
                "pinjaman_barang.buy", 
                "pinjaman_barang.sell", 
                "pinjaman_barang.angsuran",
                "pinjaman_barang.status",
            ];
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

        if(!empty($p["status_anggaran"])){
            $this->db->having("status_angsuran", $p["status_anggaran"]);
        }

        if(!empty($p["status"])){
            $this->db->where("pinjaman_barang.status", $p["status"]);
        }

        $limit = $p["length"];
		$offset = $p["start"];

        $this->db->select([
            'person.nik',
            'person.name person_name',
            'pinjaman_barang.id',
            'pinjaman_barang.person',
            'pinjaman_barang.date',
            'pinjaman_barang.name',
            'pinjaman_barang.buy',
            'pinjaman_barang.sell',
            'pinjaman_barang.angsuran',
            'pinjaman_barang.status',
            'COUNT(DISTINCT angsuran_barang.id) angsuran_paid',
            "CASE WHEN COUNT(DISTINCT angsuran_barang.id) = pinjaman_barang.angsuran 
            THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran",
        ])
        ->from('pinjaman_barang')
        ->join('(
            SELECT 
                person.nik,
                person.name,
                person.salary,
                depo.name depo
            FROM person
            LEFT JOIN depo ON depo.id = person.depo
            GROUP BY person.nik
        ) person', 'person.nik = pinjaman_barang.person')
        ->join('angsuran_barang', "angsuran_barang.pinjaman = pinjaman_barang.id AND angsuran_barang.status = 'Lunas'", 'left')
        ->order_by('person.nik ASC, pinjaman_barang.date DESC')
        ->group_by('pinjaman_barang.id');
        
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
        $this->db->insert('pinjaman_barang', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('pinjaman_barang', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pinjaman_barang');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function detail($id)
    {
        return $this->db->get_where('pinjaman_barang', ["id" => $id])->row_array();
    }
    
    public function full_detail($id)
    {
        $data["summary"] = $this->db->select([
            'pinjaman_barang.id',
            'person.nik',
            'person.name person_name',
            'depo.name depo',
            'pinjaman_barang.name',
            'pinjaman_barang.date',
            'pinjaman_barang.buy',
            'pinjaman_barang.angsuran total_angsuran',
            "SUM(CASE WHEN angsuran_barang.status = 'Lunas' THEN 1 ELSE 0 END) angsuran_lunas",
            "SUM(
                CASE WHEN angsuran_barang.status = 'Lunas'
                THEN angsuran_barang.angsuran
                ELSE 0 END
            ) total",
            "SUM(
                CASE WHEN angsuran_barang.status = 'Belum Lunas'
                THEN angsuran_barang.angsuran
                ELSE 0 END
            ) sisa",
        ])->from('pinjaman_barang')
        ->join('person', 'person.nik = pinjaman_barang.person')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->join('angsuran_barang', 'angsuran_barang.pinjaman = pinjaman_barang.id', 'left')
        ->where('pinjaman_barang.id', $id)
        ->get()->row_array();

        $data["angsuran"] = $this->db->select([
            'angsuran_barang.id',
            'angsuran_barang.year',
            'angsuran_barang.month',
            'angsuran_barang.month_no',
            '0 sisa',
            '(COALESCE(angsuran_barang.angsuran, 0)) angsuran',
            'angsuran_barang.status',
        ])
        ->from('angsuran_barang')
        ->join('pinjaman_barang', 'pinjaman_barang.id = angsuran_barang.pinjaman')
        ->join('person', 'person.nik = pinjaman_barang.person')
        ->where('pinjaman_barang.id', $id)
        ->group_by('angsuran_barang.id')
        ->get()->result_array();

        return $data;
    }

    public function get_angsuran($person)
    {
        return $this->db->select([
            'angsuran_barang.id',
            'pinjaman_barang.id pinjaman_id',
            'person.id person_id',
            'angsuran_barang.date',
            'angsuran_barang.year',
            'angsuran_barang.month',
            'angsuran_barang.month_no',
            'angsuran_barang.angsuran',
            'angsuran_barang.status',
            'COALESCE(angsuran_barang.angsuran, 0) angsuran_barang',
        ])
        ->from('angsuran_barang')
        ->join("(
            SELECT 
                pinjaman_barang.*,
                CASE WHEN COUNT(DISTINCT angsuran_barang.id) = pinjaman_barang.angsuran 
                THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran
            FROM pinjaman_barang
            LEFT JOIN angsuran_barang ON angsuran_barang.pinjaman = pinjaman_barang.id AND angsuran_barang.status = 'Lunas'
            GROUP BY pinjaman_barang.id
        ) pinjaman_barang", "pinjaman_barang.id = angsuran_barang.pinjaman AND pinjaman_barang.status = 'Approved' AND pinjaman_barang.status_angsuran = 'Belum Lunas'")
        ->join('person', 'person.nik = pinjaman_barang.person')
        ->where('pinjaman_barang.person', $person)
        ->group_by('angsuran_barang.id')
        ->get()->result_array();
    }

    public function create_angsuran($data)
    {
        $this->db->insert('angsuran_barang', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_angsuran($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('angsuran_barang', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_angsuran($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('angsuran_barang');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail_angsuran($id)
    {
        return $this->db->get_where('angsuran_barang', ["id" => $id])->row_array();
    }

    public function get_by_person($person)
    {   
        $this->db->where('person', $person);
        $this->db->where("status != 'Approved'");
        return $this->db->get('pinjaman_barang')->result_array();
    }

    public function get_summary_angsuran($id)
    {
        return $this->db->select([
            'pinjaman_barang.id',
            'person.nik',
            'person.name person_name',
            'depo.name depo',
            'pinjaman_barang.name',
            'pinjaman_barang.date',
            'pinjaman_barang.buy',
            'pinjaman_barang.angsuran total_angsuran',
            "SUM(CASE WHEN angsuran_barang.status = 'Lunas' THEN 1 ELSE 0 END) angsuran_lunas",
            "SUM(angsuran_barang.angsuran) total_pinjaman",
            "SUM(
                CASE WHEN angsuran_barang.status = 'Lunas'
                THEN angsuran_barang.angsuran
                ELSE 0 END
            ) total_bayar",
            "SUM(
                CASE WHEN angsuran_barang.status = 'Belum Lunas'
                THEN angsuran_barang.angsuran
                ELSE 0 END
            ) sisa_pinjaman",
        ])->from('pinjaman_barang')
        ->join('person', 'person.nik = pinjaman_barang.person')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->join('angsuran_barang', 'angsuran_barang.pinjaman = pinjaman_barang.id', 'left')
        ->where('pinjaman_barang.id', $id)
        ->get()->row_array();
    }
}