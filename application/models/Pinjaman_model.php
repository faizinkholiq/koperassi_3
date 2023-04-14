<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Pinjaman_model extends CI_Model {

    public function summary($person)
    {   
        $row = $this->db->select([
            'COALESCE(wajib.balance, 0) + COALESCE(investasi.balance, 0) + COALESCE(sukarela.balance, 0) + (COALESCE(person.salary, 0) * 2) plafon',
            'COALESCE(SUM(pinjaman.angsuran), 0) sisa',
        ])
        ->from('person')
        ->join("(
            SELECT
                pinjaman.person,
                MAX(pinjaman.real) balance,
                SUM(angsuran.pokok) angsuran
            FROM pinjaman
            LEFT JOIN angsuran ON angsuran.pinjaman = pinjaman.id AND angsuran.status != 'Lunas'
            GROUP BY pinjaman.person
        ) pinjaman", 'person.nik = pinjaman.person', 'left')
        ->join('(SELECT person, SUM(balance) balance FROM simpanan_wajib GROUP BY person) wajib', 'wajib.person = person.nik', 'left')
        ->join('(SELECT person, SUM(balance) balance FROM simpanan_investasi GROUP BY person) investasi', 'investasi.person = person.nik', 'left')
        ->join('(SELECT person, SUM(balance) balance FROM simpanan_sukarela GROUP BY person) sukarela', 'sukarela.person = person.nik', 'left')
        ->where('person.id', $person)
        ->group_by('person.nik')->get()->row_array();

        $data['plafon'] = $row['plafon']; 
        $data['sisa'] = $row['sisa'];

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

        if(!empty($p["status"])){
            $this->db->having("status_angsuran", $p["status"]);
        }

        $this->db->select([
            'pinjaman.id',
            'pinjaman.person',
            'pinjaman.date',
            'pinjaman.year',
            'pinjaman.month',
            'person.wajib + person.investasi + person.sukarela + (person.salary * 2) plafon',
            'pinjaman.balance', 
            'pinjaman.real', 
            'pinjaman.angsuran',
            'COUNT(DISTINCT angsuran.id) angsuran_paid',
            'pinjaman.status',
            "CASE WHEN COUNT(DISTINCT angsuran.id) = pinjaman.angsuran 
            THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran",
        ])
        ->from('pinjaman')
        ->join('(
            SELECT 
                person.nik,
                person.name,
                person.salary,
                depo.name depo,
                wajib.balance wajib,
                investasi.balance investasi,
                sukarela.balance sukarela
            FROM person
            LEFT JOIN depo ON depo.id = person.depo
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_wajib GROUP BY person) wajib ON wajib.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_investasi GROUP BY person) investasi ON investasi.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_sukarela GROUP BY person) sukarela ON sukarela.person = person.nik
            GROUP BY person.nik
        ) person', 'person.nik = pinjaman.person')
        ->join('angsuran', "angsuran.pinjaman = pinjaman.id AND angsuran.status = 'Lunas'", 'left')
        ->order_by('date', 'desc')
        ->group_by('pinjaman.id');
        
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

        if(!empty($p["status"])){
            $this->db->having("status_angsuran", $p["status"]);
        }

        $limit = $p["length"];
		$offset = $p["start"];

        $this->db->select([
            'pinjaman.id',
            'pinjaman.person',
            'person.nik',
            'person.name',
            'person.depo',
            'pinjaman.balance pengajuan', 
            'person.wajib wajib',
            'person.investasi',
            'person.sukarela',
            'person.salary gaji',
            'person.wajib + person.investasi + person.sukarela + (person.salary * 2) plafon',
            'pinjaman.real realisasi',
            'pinjaman.angsuran',
            'pinjaman.status',
            "CASE WHEN COUNT(DISTINCT angsuran.id) = pinjaman.angsuran 
            THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran",
        ])
        ->from('pinjaman')
        ->join('(
            SELECT 
                person.nik,
                person.name,
                person.salary,
                depo.name depo,
                wajib.balance wajib,
                investasi.balance investasi,
                sukarela.balance sukarela
            FROM person
            LEFT JOIN depo ON depo.id = person.depo
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_wajib GROUP BY person) wajib ON wajib.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_investasi GROUP BY person) investasi ON investasi.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_sukarela GROUP BY person) sukarela ON sukarela.person = person.nik
            GROUP BY person.nik
        ) person', 'person.nik = pinjaman.person')
        ->join('angsuran', "angsuran.pinjaman = pinjaman.id AND angsuran.status = 'Lunas'", 'left')
        ->order_by('pinjaman.date', 'desc')
        ->group_by('pinjaman.id');
        
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
    
    public function full_detail($id)
    {
        $data["summary"] = $this->db->select([
            'pinjaman.id',
            'person.nik',
            'person.name',
            'depo.name depo',
            'pinjaman.angsuran total_angsuran',
            "SUM(CASE WHEN angsuran.status = 'Lunas' THEN 1 ELSE 0 END) angsuran_lunas",
            "SUM(
                CASE WHEN angsuran.status = 'Lunas'
                THEN angsuran.pokok + angsuran.bunga
                ELSE 0 END
            ) total",
            "SUM(
                CASE WHEN angsuran.status = 'Belum Lunas'
                THEN angsuran.pokok + angsuran.bunga
                ELSE 0 END
            ) sisa",
        ])->from('pinjaman')
        ->join('person', 'person.nik = pinjaman.person')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->join('angsuran', 'angsuran.pinjaman = pinjaman.id', 'left')
        ->where('pinjaman.id', $id)
        ->get()->row_array();

        $data["angsuran"] = $this->db->select([
            'angsuran.id',
            'angsuran.year',
            'angsuran.month',
            'angsuran.month_no',
            '0 sisa',
            'angsuran.pokok',
            'angsuran.bunga',
            '(COALESCE(angsuran.pokok, 0) + COALESCE(angsuran.bunga, 0)) angsuran',
            'angsuran.status',
        ])
        ->from('angsuran')
        ->join('pinjaman', 'pinjaman.id = angsuran.pinjaman')
        ->join('person', 'person.nik = pinjaman.person')
        ->where('pinjaman.id', $id)
        ->group_by('angsuran.id')
        ->get()->result_array();

        return $data;
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
            'COALESCE(angsuran.pokok, 0) + COALESCE(angsuran.bunga, 0) angsuran',
        ])
        ->from('angsuran')
        ->join("(
            SELECT 
                pinjaman.*,
                CASE WHEN COUNT(DISTINCT angsuran.id) = pinjaman.angsuran 
                THEN 'Lunas' ELSE 'Belum Lunas' END status_angsuran
            FROM pinjaman
            LEFT JOIN angsuran ON angsuran.pinjaman = pinjaman.id AND angsuran.status = 'Lunas'
            GROUP BY pinjaman.id
        ) pinjaman", "pinjaman.id = angsuran.pinjaman AND pinjaman.status = 'Approved' AND pinjaman.status_angsuran = 'Belum Lunas'")
        ->join('person', 'person.nik = pinjaman.person')
        ->where('pinjaman.person', $person)
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

    public function get_by_person($person)
    {   
        $this->db->where('person', $person);
        $this->db->where("status != 'Approved'");
        return $this->db->get('pinjaman')->result_array();
    }

    public function get_hutang_now($person)
    {
        return $this->db->select([
            'pinjaman.id',
            'pinjaman.balance',
            'pinjaman.real',
            'person.nik',
            'person.name',
            'person.salary',
            'depo.name depo',
            "CASE WHEN COUNT(DISTINCT angsuran.id) = pinjaman.angsuran 
            THEN 'Lunas' 
            ELSE 'Belum Lunas' 
            END status_angsuran",
            "SUM(
                CASE WHEN angsuran.status = 'Lunas'
                THEN angsuran.pokok + angsuran.bunga
                ELSE 0 END
            ) total",
            "SUM(
                CASE WHEN angsuran.status = 'Belum Lunas'
                THEN angsuran.pokok + angsuran.bunga
                ELSE 0 END
            ) sisa",
        ])->from('pinjaman')
        ->join('angsuran', "angsuran.pinjaman = pinjaman.id AND angsuran.status = 'Lunas'", 'left')
        ->join('person', 'person.nik = pinjaman.person')
        ->join('depo', 'depo.id = person.depo', 'left')
        ->where("pinjaman.person", $person)
        ->get()->row_array();
    }

}