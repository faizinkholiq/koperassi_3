<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_model extends CI_Model {

    public function get($person)
    {
        $q_pokok = $this->db->select([
                'simpanan_pokok.id',
                'simpanan_pokok.person person_id',
                'simpanan_pokok.code',
                'person.name',
                'person.no_ktp',
                'person.nik',
                'person.phone',
                'person.join_date',
                'person.depo',
                'person.address',
                'person.acc_no',
                'person.position',
                'position.name position_name',
                '"Simpanan Pokok" type',
                'simpanan_pokok.date',
                'simpanan_pokok.balance',
                '0 simpanan_temp_id',
            ])
            ->from('simpanan_pokok')
            ->join('person', 'person.nik = simpanan_pokok.person')
            ->join('position', 'person.position = position.id', 'left')
            ->where('person.id', $person)
            ->get_compiled_select();
        
        $q_wajib = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'simpanan_wajib.code',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Wajib" type',
            'simpanan_wajib.date',
            'simpanan_wajib.balance',
            '0 simpanan_temp_id',
        ])
        ->from('simpanan_wajib')
        ->join('person', 'person.nik = simpanan_wajib.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'simpanan_sukarela.code',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Sukarela" type',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
            'simpanan_temp.id simpanan_temp_id',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.nik = simpanan_sukarela.person')
        ->join('position', 'person.position = position.id', 'left')
        ->join('simpanan_temp', 'simpanan_temp.simpanan_id = simpanan_sukarela.id AND simpanan_temp.type = "Sukarela"', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_investasi = $this->db->select([
            'simpanan_investasi.id',
            'simpanan_investasi.person person_id',
            'simpanan_investasi.code',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Wajib" type',
            'simpanan_investasi.date',
            'simpanan_investasi.balance',
            '0 simpanan_temp_id',
        ])
        ->from('simpanan_investasi')
        ->join('person', 'person.nik = simpanan_investasi.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_all = $this->db->query($q_pokok.' UNION ALL '.$q_wajib. ' UNION ALL '.$q_sukarela.' UNION ALL '.$q_investasi.' ORDER BY date DESC');
        return $q_all->result_array();
    }

    public function get_dt($p)
    {
        $search = $p["search"];
        
        $person = $p["person"];
        $limit = $p["length"];
		$offset = $p["start"];

        $q_pokok = $this->db->select([
            'simpanan_pokok.id',
            'simpanan_pokok.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Pokok" type',
            'simpanan_pokok.date',
            'simpanan_pokok.balance',
            'simpanan_pokok.year',
            'simpanan_pokok.month',
            'simpanan_pokok.dk',
        ])
        ->from('simpanan_pokok')
        ->join('person', 'person.nik = simpanan_pokok.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();
    
        $q_wajib = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Wajib" type',
            'simpanan_wajib.date',
            'simpanan_wajib.balance',
            'simpanan_wajib.year',
            'simpanan_wajib.month',
            'simpanan_wajib.dk',
        ])
        ->from('simpanan_wajib')
        ->join('person', 'person.nik = simpanan_wajib.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Sukarela" type',
            'simpanan_sukarela.date',
            'simpanan_sukarela.balance',
            'simpanan_sukarela.year',
            'simpanan_sukarela.month',
            'simpanan_sukarela.dk',
        ])
        ->from('simpanan_sukarela')
        ->join('person', 'person.nik = simpanan_sukarela.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_investasi = $this->db->select([
            'simpanan_investasi.id',
            'simpanan_investasi.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Investasi" type',
            'simpanan_investasi.date',
            'simpanan_investasi.balance',
            'simpanan_investasi.year',
            'simpanan_investasi.month',
            'simpanan_investasi.dk',
        ])
        ->from('simpanan_investasi')
        ->join('person', 'person.nik = simpanan_investasi.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_w_sukarela = $this->db->select([
            'penarikan_simpanan.id',
            'penarikan_simpanan.person person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            "CASE 
                WHEN type = 'Pokok' THEN 'Simpanan Pokok'
                WHEN type = 'Wajib' THEN 'Simpanan Wajib'
                WHEN type = 'Sukarela' THEN 'Simpanan Sukarela'
                WHEN type = 'Investasi' THEN 'Investasi'
            END AS type",
            'penarikan_simpanan.date',
            'penarikan_simpanan.balance',
            'penarikan_simpanan.year',
            'penarikan_simpanan.month',
            "'K'dk",
        ])
        ->from('penarikan_simpanan')
        ->join('person', 'person.nik = penarikan_simpanan.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->where('penarikan_simpanan.status', 'Approved')
        ->get_compiled_select();
        
        // Get All Data
        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["year", "month", "type", "balance"];
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

        if (!empty($p["type"]) && $p['type'] != 'all') {
            $this->db->where('type', $p['type']);
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
            $q_pokok UNION ALL 
            $q_wajib UNION ALL 
            $q_sukarela UNION ALL 
            $q_investasi UNION ALL
            $q_w_sukarela ORDER BY 
                year, 
                CAST(month AS DECIMAL),
                FIELD(type, 'Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela', 'Simpanan Investasi')
        ) simpanan");

        $q_all = $this->db->get();
        $data["recordsTotal"] = $q_all->num_rows();
        $data["recordsFiltered"] = $q_all ->num_rows();

        $this->db->stop_cache();
        
        // $this->db->limit($limit, $offset);

        $data["data"] = $this->db->get()->result_array();
        $data["draw"] = intval($p["draw"]);

        $this->db->flush_cache();

        return $data;
    }


    public function summary($person)
    {   
        $data['plafon'] = 0; 
        $data['limit'] = 0; 
        $data['gaji'] = 0; 
        $data['simpanan'] = $this->db->select([
                'COALESCE(simpanan_pokok.total, 0) 
                + COALESCE(simpanan_wajib.total, 0) 
                + COALESCE(simpanan_sukarela.total, 0)
                + COALESCE(simpanan_investasi.total, 0) balance',
            ])
            ->from('person')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_pokok 
                GROUP BY person
            ) simpanan_pokok', 'person.nik = simpanan_pokok.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_wajib 
                GROUP BY person
            ) simpanan_wajib', 'person.nik = simpanan_wajib.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_sukarela 
                GROUP BY person
            ) simpanan_sukarela', 'person.nik = simpanan_sukarela.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_investasi 
                GROUP BY person
            ) simpanan_investasi', 'person.nik = simpanan_investasi.person', 'left')
            ->where('person.id', $person)
            ->group_by('person.id')->get()->row_array()['balance'];

        return $data;
    }

    public function create_temp($data)
    {
        $this->db->insert('simpanan_temp', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_temp($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_temp', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_temp($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_temp');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail_temp($id, $module)
    {
        return $this->db->get_where('simpanan_temp', ["simpanan_id" => $id, 'type' => $module])->row_array();
    }

    public function get_history($id){
        $data = $this->db->from('history_simpanan')
        ->where('person', $id)
        ->order_by('id', 'DESC')
        ->get()->result_array();
        return $data;
    }

    public function create_history($data)
    {
        $this->db->insert('history_simpanan', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_history($data)
    {   
        $this->db->where('code', $data['code']);
        $this->db->where('status', 'Pending');
        $this->db->update('history_simpanan', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_history($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('history_simpanan');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function detail_history($person, $code, $type, $status)
    {
        return $this->db->get_where('history_simpanan', [
            "person" => $person, 
            "code" => $code,
            "type" => $type,
            "status" => $status,
        ])->row_array();
    }

    public function get_settings()
    {
        return $this->db->get('settings_simpanan')->result_array();
    }

    public function detail_settings($id)
    {
        return $this->db->get_where('settings_simpanan', ["id" => $id])->row_array();
    }

    public function create_settings($data)
    {
        $this->db->insert('settings_simpanan', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_settings($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('settings_simpanan', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_settings($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('settings_simpanan');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function generate_settings($data)
    {   
        $data['status_simpanan'] = 'generated';
        $this->db->update('person', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function get_default_nominal($type)
    {
        return $this->db->get_where('settings_simpanan', ['simpanan' => $type])->row_array();
    }

    public function get_dt_ubah_simpanan($p)
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
            'person.id person_id',
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

    public function create_ubah_simpanan($data)
    {
        $this->db->insert('pengajuan_simpanan', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_ubah_simpanan($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('pengajuan_simpanan', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_ubah_simpanan($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pengajuan_simpanan');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail_ubah_simpanan($id)
    {
        return $this->db->get_where('pengajuan_simpanan', ["id" => $id])->row_array();
    }

    public function detail_ubah_simpanan_by_month($p)
    {
        return $this->db->get_where('pengajuan_simpanan', 
        [
            "person" => $p['person'], 
            "year" => $p['year'], 
            "month" => $p['month'], 
            "type" => $p['type']
        ])->row_array();
    }

    public function posting($data)
    {   
        foreach($data["simpanan"] as $key => $val){
            $this->db->where('month', $data['bulan']);
            $this->db->where('year', $data['tahun']);
            $this->db->update('simpanan_'.$val, ["posting" => 1, "posting_date" => date('Y-m-d')]);
        }
        
        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function get_dt_penarikan($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["month", "year", "balance"];
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
            'penarikan_simpanan.id',
            'person.id person_id',
            'person.name',
            'person.no_ktp',
            'person.nik',
            'person.phone',
            'person.join_date',
            'penarikan_simpanan.type',
            'penarikan_simpanan.month',
            'penarikan_simpanan.year',
            'penarikan_simpanan.status',
            'penarikan_simpanan.balance',
            'penarikan_simpanan.date',
            'penarikan_simpanan.reason',
        ])
        ->from('penarikan_simpanan')
        ->join('person', 'person.nik = penarikan_simpanan.person')
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

    public function create_penarikan($data)
    {
        $this->db->insert('penarikan_simpanan', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_penarikan($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('penarikan_simpanan', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_penarikan($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('penarikan_simpanan');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
    public function detail_penarikan($id)
    {
        return $this->db->get_where('penarikan_simpanan', ["id" => $id])->row_array();
    }

    private function get_total_simpanan($table, $person = null) {
        if ($person) {
            $this->db->where('person', $person);
        }

        $data = $this->db->select('SUM(balance) total')->from($table)->get()->row_array();
        if (!empty($data)) {
            return $data['total'];
        }else{
            return 0;
        }
    }

    public function get_summary_simpanan($person = null) 
    {
        $data = [];

        $data['wajib'] = (float)$this->get_total_simpanan('simpanan_wajib', $person);
        $data['pokok'] = (float)$this->get_total_simpanan('simpanan_pokok', $person);
        $data['sukarela'] = (float)$this->get_total_simpanan('simpanan_sukarela', $person);
        $data['investasi'] = (float)$this->get_total_simpanan('simpanan_investasi', $person);
        $data['all'] = $data['wajib'] + $data['pokok'] + $data['sukarela'] + $data['investasi'];
        
        return $data;
    }

    private function get_total_penarikan($type, $person = null) {
        if ($type != 'All') {
            $this->db->where('type', $type);
        }

        if ($person) {
            $this->db->where('person', $person);
        }

        $data = $this->db->select('SUM(balance) total')->from('penarikan_simpanan')->where('status', 'Approved')->get()->row_array();
        if (!empty($data)) {
            return $data['total'];
        }else{
            return 0;
        }
    }

    public function get_summary_penarikan($person = null) 
    {
        $data = [];

        $data['wajib'] = (float)$this->get_total_penarikan('Wajib', $person);
        $data['pokok'] = (float)$this->get_total_penarikan('Pokok', $person);
        $data['sukarela'] = (float)$this->get_total_penarikan('Sukarela', $person);
        $data['investasi'] = (float)$this->get_total_penarikan('Investasi', $person);
        $data['all'] = (float)$this->get_total_penarikan('All', $person);
        
        return $data;
    }

}