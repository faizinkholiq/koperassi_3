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
            $where_simpanan .= " AND year = ".$p['year'];
        }

        if(!empty($p['from']) && !empty($p['to'])){
            $where_simpanan .= " AND month BETWEEN ".$p['from']. " AND ".$p['to'];
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
            $this->db->where("month BETWEEN ".$p['from']. " AND ".$p['to']);
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

    public function get_data_pinjaman_uang($p = null) 
    {   
        $year = $p['year'];
        $month = $p['month'];

        $this->db->select([
            'pinjaman.id',
            'pinjaman.person',
            'person.nik',
            'person.name',
            'person.depo',
            'COALESCE(person.wajib, 0) + COALESCE(person.investasi, 0) + COALESCE(person.sukarela, 0) debit',
            'pinjaman.real kredit',
            'COALESCE(person.wajib, 0) + COALESCE(person.investasi, 0) + COALESCE(person.sukarela, 0) cicilan',
            'SUM(angsuran.pokok) bayar',
            'SUM(angsuran.bunga) bunga',
            '0 sisa',
            'person.salary gaji',
        ])
        ->from('pinjaman')
        ->join("(
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
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_wajib WHERE month = $month AND year = $year GROUP BY person) wajib ON wajib.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_investasi WHERE month = $month AND year = $year GROUP BY person) investasi ON investasi.person = person.nik
            LEFT JOIN (SELECT person, SUM(balance) balance FROM simpanan_sukarela WHERE month = $month AND year = $year  GROUP BY person) sukarela ON sukarela.person = person.nik
            GROUP BY person.nik
        ) person", 'person.nik = pinjaman.person')
        ->join('angsuran', "angsuran.pinjaman = pinjaman.id AND angsuran.status = 'Lunas'", 'left')
        ->where('pinjaman.status', 'Approved')
        ->where('pinjaman.month', $month)
        ->where('pinjaman.year', $year)
        ->order_by('person.nik ASC, pinjaman.date DESC')
        ->group_by('pinjaman.id');
        
        $q = $this->db->get();
        
        return $q->result_array();
    }

    public function get_data_pinjaman_barang($p = null) 
    {   
        $year = $p['year'];
        $month = $p['month'];

        $select_dates = [];

        $month_no = $month;
        for ($i=1; $i <= 18; $i++) { 

            if($month_no == 13) {
                $month_no = 1;
                $year++;
            }

            $select_dates[] = " 
                SUM(CASE WHEN angsuran_barang.year = $year AND angsuran_barang.month = $month_no AND angsuran_barang.status = 'Lunas'
                THEN angsuran_barang.angsuran
                ELSE 0 END) '".$year.str_pad($month_no, 2, '0', STR_PAD_LEFT)."'
            ";

            $month_no++;
        }
        
        $this->db->select($select_dates);
        $this->db->select([
            "pinjaman_barang.id",
            "person.nik",
            "person.name person_name",
            "pinjaman_barang.name",
            "pinjaman_barang.buy",
            "pinjaman_barang.sell",
            "(pinjaman_barang.sell - pinjaman_barang.buy) profit",
        ])
        ->from('pinjaman_barang')
        ->join('person', 'person.nik = pinjaman_barang.person')
        ->join('angsuran_barang', 'angsuran_barang.pinjaman = pinjaman_barang.id', 'left')
        ->where('pinjaman_barang.status', 'Approved')
        ->group_by('pinjaman_barang.id')
        ->order_by('person.nik', 'asc');
        
        $q = $this->db->get();
        
        return $q->result_array();
    }

    function get_data_laba_rugi($p = null) {
        $year = $p['year'];

        $data = [];

        $p_q1["month_start"] = "1";
        $p_q1["month_end"] = "3";
        $p_q1["year"] = $year;
        $data['q1']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_q1)['total_pokok_pinjaman'];
        $data['q1']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_q1)['total_bunga_pinjaman'];
        $data['q1']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q1)['total_beli_pinjaman_barang'];
        $data['q1']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q1)['total_jual_pinjaman_barang'];
        $data['q1']['percent_pokok_pinjaman'] = 0;
        $data['q1']['percent_bunga_pinjaman'] = 0;
        $data['q1']['percent_beli_pinjaman_barang'] = 0;
        $data['q1']['percent_jual_pinjaman_barang'] = 0;
        $data['q1']['pokok_pinjaman_bunga'] = $data['q1']['total_pokok_pinjaman'] + $data['q1']['total_bunga_pinjaman'];
        $data['q1']['percent_pokok_pinjaman_bunga'] = 0;
        $data['q1']['koreksi_bunga'] = 0;
        $data['q1']['percent_koreksi_bunga'] = 0;
        $data['q1']['total_pokok_pinjaman_bunga'] = $data['q1']['pokok_pinjaman_bunga'] + $data['q1']['koreksi_bunga'];
        $data['q1']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['q1']['bunga_pinjaman_percent'] = (!empty($data['q1']['total_pokok_pinjaman']) && !empty($data['q1']['total_bunga_pinjaman']))? $data['q1']['total_bunga_pinjaman'] / $data['q1']['total_pokok_pinjaman'] * 100 : 0;
        $data['q1']['gross_profit'] = $data['q1']['total_jual_pinjaman_barang'] - $data['q1']['total_beli_pinjaman_barang'];
        $data['q1']['percent_gross_profit'] = !empty($data['q1']['total_jual_pinjaman_barang'])? $data['q1']['gross_profit'] / $data['q1']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['q1']['grand_total_pendapatan_koperasi'] = $data['q1']['total_pokok_pinjaman_bunga'] + $data['q1']['total_jual_pinjaman_barang'];
        $data['q1']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['q1']['grand_total_pendapatan_koperasi'])? $data['q1']['total_pokok_pinjaman_bunga'] / $data['q1']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q1']['percent_jual_pinjaman_barang_2'] = !empty($data['q1']['grand_total_pendapatan_koperasi'])? $data['q1']['total_jual_pinjaman_barang'] / $data['q1']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q1']['percent_grand_total_pendapatan_koperasi'] = $data['q1']['percent_total_pokok_pinjaman_bunga_2'] + $data['q1']['percent_jual_pinjaman_barang_2'];
        $data['q1']['grand_total_pokok_koperasi'] = $data['q1']['total_pokok_pinjaman'] + $data['q1']['total_beli_pinjaman_barang'];
        $data['q1']['percent_total_pokok_pinjaman_2'] = !empty($data['q1']['grand_total_pokok_koperasi'])? $data['q1']['total_pokok_pinjaman'] / $data['q1']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q1']['percent_beli_pinjaman_barang_2'] = !empty($data['q1']['grand_total_pokok_koperasi'])? $data['q1']['total_beli_pinjaman_barang'] / $data['q1']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q1']['percent_grand_total_pokok_koperasi'] = $data['q1']['percent_total_pokok_pinjaman_2'] + $data['q1']['percent_beli_pinjaman_barang_2'];
        $data['q1']['laba_bunga_pinjaman'] = $data['q1']['total_bunga_pinjaman'];
        $data['q1']['percent_laba_bunga_pinjaman'] = !empty($data['q1']['grand_total_pendapatan_koperasi'])? $data['q1']['laba_bunga_pinjaman'] / $data['q1']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q1']['laba_bunga_pinjaman_barang'] = $data['q1']['gross_profit'];
        $data['q1']['percent_laba_bunga_pinjaman_barang'] = !empty($data['q1']['grand_total_pendapatan_koperasi'])? $data['q1']['laba_bunga_pinjaman_barang'] / $data['q1']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q1']['grand_total_laba_koperasi'] = $data['q1']['laba_bunga_pinjaman'] + $data['q1']['laba_bunga_pinjaman_barang'];
        $data['q1']['percent_grand_total_laba_koperasi'] = $data['q1']['percent_laba_bunga_pinjaman'] + $data['q1']['percent_laba_bunga_pinjaman_barang'];


        $p_q2["month_start"] = "4";
        $p_q2["month_end"] = "6";
        $p_q2["year"] = $year;
        $data['q2']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_q2)['total_pokok_pinjaman'];
        $data['q2']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_q2)['total_bunga_pinjaman'];
        $data['q2']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q2)['total_beli_pinjaman_barang'];
        $data['q2']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q2)['total_jual_pinjaman_barang'];
        $data['q2']['percent_pokok_pinjaman'] = 0;
        $data['q2']['percent_bunga_pinjaman'] = 0;
        $data['q2']['percent_beli_pinjaman_barang'] = 0;
        $data['q2']['percent_jual_pinjaman_barang'] = 0;
        $data['q2']['pokok_pinjaman_bunga'] = $data['q2']['total_pokok_pinjaman'] + $data['q2']['total_bunga_pinjaman'];
        $data['q2']['percent_pokok_pinjaman_bunga'] = 0;
        $data['q2']['koreksi_bunga'] = 0;
        $data['q2']['percent_koreksi_bunga'] = 0;
        $data['q2']['total_pokok_pinjaman_bunga'] = $data['q2']['pokok_pinjaman_bunga'] + $data['q2']['koreksi_bunga'];
        $data['q2']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['q2']['bunga_pinjaman_percent'] = (!empty($data['q2']['total_pokok_pinjaman']) && !empty($data['q2']['total_bunga_pinjaman']))? $data['q2']['total_bunga_pinjaman'] / $data['q2']['total_pokok_pinjaman'] * 100 : 0;
        $data['q2']['gross_profit'] = $data['q2']['total_jual_pinjaman_barang'] - $data['q2']['total_beli_pinjaman_barang'];
        $data['q2']['percent_gross_profit'] = !empty($data['q2']['total_jual_pinjaman_barang'])? $data['q2']['gross_profit'] / $data['q2']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['q2']['grand_total_pendapatan_koperasi'] = $data['q2']['total_pokok_pinjaman_bunga'] + $data['q2']['total_jual_pinjaman_barang'];
        $data['q2']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['q2']['grand_total_pendapatan_koperasi'])? $data['q2']['total_pokok_pinjaman_bunga'] / $data['q2']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q2']['percent_jual_pinjaman_barang_2'] = !empty($data['q2']['grand_total_pendapatan_koperasi'])? $data['q2']['total_jual_pinjaman_barang'] / $data['q2']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q2']['percent_grand_total_pendapatan_koperasi'] = $data['q2']['percent_total_pokok_pinjaman_bunga_2'] + $data['q2']['percent_jual_pinjaman_barang_2'];
        $data['q2']['grand_total_pokok_koperasi'] = $data['q2']['total_pokok_pinjaman'] + $data['q2']['total_beli_pinjaman_barang'];
        $data['q2']['percent_total_pokok_pinjaman_2'] = !empty($data['q2']['grand_total_pokok_koperasi'])? $data['q2']['total_pokok_pinjaman'] / $data['q2']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q2']['percent_beli_pinjaman_barang_2'] = !empty($data['q2']['grand_total_pokok_koperasi'])? $data['q2']['total_beli_pinjaman_barang'] / $data['q2']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q2']['percent_grand_total_pokok_koperasi'] = $data['q2']['percent_total_pokok_pinjaman_2'] + $data['q2']['percent_beli_pinjaman_barang_2'];
        $data['q2']['laba_bunga_pinjaman'] = $data['q2']['total_bunga_pinjaman'];
        $data['q2']['percent_laba_bunga_pinjaman'] = !empty($data['q2']['grand_total_pendapatan_koperasi'])? $data['q2']['laba_bunga_pinjaman'] / $data['q2']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q2']['laba_bunga_pinjaman_barang'] = $data['q2']['gross_profit'];
        $data['q2']['percent_laba_bunga_pinjaman_barang'] = !empty($data['q2']['grand_total_pendapatan_koperasi'])? $data['q2']['laba_bunga_pinjaman_barang'] / $data['q2']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q2']['grand_total_laba_koperasi'] = $data['q2']['laba_bunga_pinjaman'] + $data['q2']['laba_bunga_pinjaman_barang'];
        $data['q2']['percent_grand_total_laba_koperasi'] = $data['q2']['percent_laba_bunga_pinjaman'] + $data['q2']['percent_laba_bunga_pinjaman_barang'];


        $p_q3["month_start"] = "7";
        $p_q3["month_end"] = "9";
        $p_q3["year"] = $year;
        $data['q3']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_q3)['total_pokok_pinjaman'];
        $data['q3']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_q3)['total_bunga_pinjaman'];
        $data['q3']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q3)['total_beli_pinjaman_barang'];
        $data['q3']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q3)['total_jual_pinjaman_barang'];
        $data['q3']['percent_pokok_pinjaman'] = 0;
        $data['q3']['percent_bunga_pinjaman'] = 0;
        $data['q3']['percent_beli_pinjaman_barang'] = 0;
        $data['q3']['percent_jual_pinjaman_barang'] = 0;
        $data['q3']['pokok_pinjaman_bunga'] = $data['q3']['total_pokok_pinjaman'] + $data['q3']['total_bunga_pinjaman'];
        $data['q3']['percent_pokok_pinjaman_bunga'] = 0;
        $data['q3']['koreksi_bunga'] = 0;
        $data['q3']['percent_koreksi_bunga'] = 0;
        $data['q3']['total_pokok_pinjaman_bunga'] = $data['q3']['pokok_pinjaman_bunga'] + $data['q3']['koreksi_bunga'];
        $data['q3']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['q3']['bunga_pinjaman_percent'] = (!empty($data['q3']['total_pokok_pinjaman']) && !empty($data['q3']['total_bunga_pinjaman']))? $data['q3']['total_bunga_pinjaman'] / $data['q3']['total_pokok_pinjaman'] * 100 : 0;
        $data['q3']['gross_profit'] = $data['q3']['total_jual_pinjaman_barang'] - $data['q3']['total_beli_pinjaman_barang'];
        $data['q3']['percent_gross_profit'] = !empty($data['q3']['total_jual_pinjaman_barang'])? $data['q3']['gross_profit'] / $data['q3']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['q3']['grand_total_pendapatan_koperasi'] = $data['q3']['total_pokok_pinjaman_bunga'] + $data['q3']['total_jual_pinjaman_barang'];
        $data['q3']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['q3']['grand_total_pendapatan_koperasi'])? $data['q3']['total_pokok_pinjaman_bunga'] / $data['q3']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q3']['percent_jual_pinjaman_barang_2'] = !empty($data['q3']['grand_total_pendapatan_koperasi'])? $data['q3']['total_jual_pinjaman_barang'] / $data['q3']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q3']['percent_grand_total_pendapatan_koperasi'] = $data['q3']['percent_total_pokok_pinjaman_bunga_2'] + $data['q3']['percent_jual_pinjaman_barang_2'];
        $data['q3']['grand_total_pokok_koperasi'] = $data['q3']['total_pokok_pinjaman'] + $data['q3']['total_beli_pinjaman_barang'];
        $data['q3']['percent_total_pokok_pinjaman_2'] = !empty($data['q3']['grand_total_pokok_koperasi'])? $data['q3']['total_pokok_pinjaman'] / $data['q3']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q3']['percent_beli_pinjaman_barang_2'] = !empty($data['q3']['grand_total_pokok_koperasi'])? $data['q3']['total_beli_pinjaman_barang'] / $data['q3']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q3']['percent_grand_total_pokok_koperasi'] = $data['q3']['percent_total_pokok_pinjaman_2'] + $data['q3']['percent_beli_pinjaman_barang_2'];
        $data['q3']['laba_bunga_pinjaman'] = $data['q3']['total_bunga_pinjaman'];
        $data['q3']['percent_laba_bunga_pinjaman'] = !empty($data['q3']['grand_total_pendapatan_koperasi'])? $data['q3']['laba_bunga_pinjaman'] / $data['q3']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q3']['laba_bunga_pinjaman_barang'] = $data['q3']['gross_profit'];
        $data['q3']['percent_laba_bunga_pinjaman_barang'] = !empty($data['q3']['grand_total_pendapatan_koperasi'])? $data['q3']['laba_bunga_pinjaman_barang'] / $data['q3']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q3']['grand_total_laba_koperasi'] = $data['q3']['laba_bunga_pinjaman'] + $data['q3']['laba_bunga_pinjaman_barang'];
        $data['q3']['percent_grand_total_laba_koperasi'] = $data['q3']['percent_laba_bunga_pinjaman'] + $data['q3']['percent_laba_bunga_pinjaman_barang'];


        $p_q4["month_start"] = "10";
        $p_q4["month_end"] = "12";
        $p_q4["year"] = $year;
        $data['q4']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_q4)['total_pokok_pinjaman'];
        $data['q4']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_q4)['total_bunga_pinjaman'];
        $data['q4']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q4)['total_beli_pinjaman_barang'];
        $data['q4']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_q4)['total_jual_pinjaman_barang'];
        $data['q4']['percent_pokok_pinjaman'] = 0;
        $data['q4']['percent_bunga_pinjaman'] = 0;
        $data['q4']['percent_beli_pinjaman_barang'] = 0;
        $data['q4']['percent_jual_pinjaman_barang'] = 0;
        $data['q4']['pokok_pinjaman_bunga'] = $data['q4']['total_pokok_pinjaman'] + $data['q4']['total_bunga_pinjaman'];
        $data['q4']['percent_pokok_pinjaman_bunga'] = 0;
        $data['q4']['koreksi_bunga'] = 0;
        $data['q4']['percent_koreksi_bunga'] = 0;
        $data['q4']['total_pokok_pinjaman_bunga'] = $data['q4']['pokok_pinjaman_bunga'] + $data['q4']['koreksi_bunga'];
        $data['q4']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['q4']['bunga_pinjaman_percent'] = (!empty($data['q4']['total_pokok_pinjaman']) && !empty($data['q4']['total_bunga_pinjaman']))? $data['q4']['total_bunga_pinjaman'] / $data['q4']['total_pokok_pinjaman'] * 100 : 0;
        $data['q4']['gross_profit'] = $data['q4']['total_jual_pinjaman_barang'] - $data['q4']['total_beli_pinjaman_barang'];
        $data['q4']['percent_gross_profit'] = !empty($data['q4']['total_jual_pinjaman_barang'])? $data['q4']['gross_profit'] / $data['q4']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['q4']['grand_total_pendapatan_koperasi'] = $data['q4']['total_pokok_pinjaman_bunga'] + $data['q4']['total_jual_pinjaman_barang'];
        $data['q4']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['q4']['grand_total_pendapatan_koperasi'])? $data['q4']['total_pokok_pinjaman_bunga'] / $data['q4']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q4']['percent_jual_pinjaman_barang_2'] = !empty($data['q4']['grand_total_pendapatan_koperasi'])? $data['q4']['total_jual_pinjaman_barang'] / $data['q4']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q4']['percent_grand_total_pendapatan_koperasi'] = $data['q4']['percent_total_pokok_pinjaman_bunga_2'] + $data['q4']['percent_jual_pinjaman_barang_2'];
        $data['q4']['grand_total_pokok_koperasi'] = $data['q4']['total_pokok_pinjaman'] + $data['q4']['total_beli_pinjaman_barang'];
        $data['q4']['percent_total_pokok_pinjaman_2'] = !empty($data['q4']['grand_total_pokok_koperasi'])? $data['q4']['total_pokok_pinjaman'] / $data['q4']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q4']['percent_beli_pinjaman_barang_2'] = !empty($data['q4']['grand_total_pokok_koperasi'])? $data['q4']['total_beli_pinjaman_barang'] / $data['q4']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['q4']['percent_grand_total_pokok_koperasi'] = $data['q4']['percent_total_pokok_pinjaman_2'] + $data['q4']['percent_beli_pinjaman_barang_2'];
        $data['q4']['laba_bunga_pinjaman'] = $data['q4']['total_bunga_pinjaman'];
        $data['q4']['percent_laba_bunga_pinjaman'] = !empty($data['q4']['grand_total_pendapatan_koperasi'])? $data['q4']['laba_bunga_pinjaman'] / $data['q4']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q4']['laba_bunga_pinjaman_barang'] = $data['q4']['gross_profit'];
        $data['q4']['percent_laba_bunga_pinjaman_barang'] = !empty($data['q4']['grand_total_pendapatan_koperasi'])? $data['q4']['laba_bunga_pinjaman_barang'] / $data['q4']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['q4']['grand_total_laba_koperasi'] = $data['q4']['laba_bunga_pinjaman'] + $data['q4']['laba_bunga_pinjaman_barang'];
        $data['q4']['percent_grand_total_laba_koperasi'] = $data['q4']['percent_laba_bunga_pinjaman'] + $data['q4']['percent_laba_bunga_pinjaman_barang'];


        $p_ytd["year"] = $year;
        $data['ytd']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_ytd)['total_pokok_pinjaman'];
        $data['ytd']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_ytd)['total_bunga_pinjaman'];
        $data['ytd']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_ytd)['total_beli_pinjaman_barang'];
        $data['ytd']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_ytd)['total_jual_pinjaman_barang'];
        $data['ytd']['percent_pokok_pinjaman'] = 0;
        $data['ytd']['percent_bunga_pinjaman'] = 0;
        $data['ytd']['percent_beli_pinjaman_barang'] = 0;
        $data['ytd']['percent_jual_pinjaman_barang'] = 0;
        $data['ytd']['pokok_pinjaman_bunga'] = $data['ytd']['total_pokok_pinjaman'] + $data['ytd']['total_bunga_pinjaman'];
        $data['ytd']['percent_pokok_pinjaman_bunga'] = 0;
        $data['ytd']['koreksi_bunga'] = 0;
        $data['ytd']['percent_koreksi_bunga'] = 0;
        $data['ytd']['total_pokok_pinjaman_bunga'] = $data['ytd']['pokok_pinjaman_bunga'] + $data['ytd']['koreksi_bunga'];
        $data['ytd']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['ytd']['bunga_pinjaman_percent'] = (!empty($data['ytd']['total_pokok_pinjaman']) && !empty($data['ytd']['total_bunga_pinjaman']))? $data['ytd']['total_bunga_pinjaman'] / $data['ytd']['total_pokok_pinjaman'] * 100 : 0;
        $data['ytd']['gross_profit'] = $data['ytd']['total_jual_pinjaman_barang'] - $data['ytd']['total_beli_pinjaman_barang'];
        $data['ytd']['percent_gross_profit'] = !empty($data['ytd']['total_jual_pinjaman_barang'])? $data['ytd']['gross_profit'] / $data['ytd']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['ytd']['grand_total_pendapatan_koperasi'] = $data['ytd']['total_pokok_pinjaman_bunga'] + $data['ytd']['total_jual_pinjaman_barang'];
        $data['ytd']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['ytd']['grand_total_pendapatan_koperasi'])? $data['ytd']['total_pokok_pinjaman_bunga'] / $data['ytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['ytd']['percent_jual_pinjaman_barang_2'] = !empty($data['ytd']['grand_total_pendapatan_koperasi'])? $data['ytd']['total_jual_pinjaman_barang'] / $data['ytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['ytd']['percent_grand_total_pendapatan_koperasi'] = $data['ytd']['percent_total_pokok_pinjaman_bunga_2'] + $data['ytd']['percent_jual_pinjaman_barang_2'];
        $data['ytd']['grand_total_pokok_koperasi'] = $data['ytd']['total_pokok_pinjaman'] + $data['ytd']['total_beli_pinjaman_barang'];
        $data['ytd']['percent_total_pokok_pinjaman_2'] = !empty($data['ytd']['grand_total_pokok_koperasi'])? $data['ytd']['total_pokok_pinjaman'] / $data['ytd']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['ytd']['percent_beli_pinjaman_barang_2'] = !empty($data['ytd']['grand_total_pokok_koperasi'])? $data['ytd']['total_beli_pinjaman_barang'] / $data['ytd']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['ytd']['percent_grand_total_pokok_koperasi'] = $data['ytd']['percent_total_pokok_pinjaman_2'] + $data['ytd']['percent_beli_pinjaman_barang_2'];
        $data['ytd']['laba_bunga_pinjaman'] = $data['ytd']['total_bunga_pinjaman'];
        $data['ytd']['percent_laba_bunga_pinjaman'] = !empty($data['ytd']['grand_total_pendapatan_koperasi'])? $data['ytd']['laba_bunga_pinjaman'] / $data['ytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['ytd']['laba_bunga_pinjaman_barang'] = $data['ytd']['gross_profit'];
        $data['ytd']['percent_laba_bunga_pinjaman_barang'] = !empty($data['ytd']['grand_total_pendapatan_koperasi'])? $data['ytd']['laba_bunga_pinjaman_barang'] / $data['ytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['ytd']['grand_total_laba_koperasi'] = $data['ytd']['laba_bunga_pinjaman'] + $data['ytd']['laba_bunga_pinjaman_barang'];
        $data['ytd']['percent_grand_total_laba_koperasi'] = $data['ytd']['percent_laba_bunga_pinjaman'] + $data['ytd']['percent_laba_bunga_pinjaman_barang'];


        $p_lytd["year"] = $year - 1;
        $data['lytd']['total_pokok_pinjaman'] = $this->_get_data_pinjaman($p_lytd)['total_pokok_pinjaman'];
        $data['lytd']['total_bunga_pinjaman'] = $this->_get_data_pinjaman($p_lytd)['total_bunga_pinjaman'];
        $data['lytd']['total_beli_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_lytd)['total_beli_pinjaman_barang'];
        $data['lytd']['total_jual_pinjaman_barang'] = $this->_get_data_pinjaman_barang($p_lytd)['total_jual_pinjaman_barang'];
        $data['lytd']['percent_pokok_pinjaman'] = 0;
        $data['lytd']['percent_bunga_pinjaman'] = 0;
        $data['lytd']['percent_beli_pinjaman_barang'] = 0;
        $data['lytd']['percent_jual_pinjaman_barang'] = 0;
        $data['lytd']['pokok_pinjaman_bunga'] = $data['lytd']['total_pokok_pinjaman'] + $data['lytd']['total_bunga_pinjaman'];
        $data['lytd']['percent_pokok_pinjaman_bunga'] = 0;
        $data['lytd']['koreksi_bunga'] = 0;
        $data['lytd']['percent_koreksi_bunga'] = 0;
        $data['lytd']['total_pokok_pinjaman_bunga'] = $data['lytd']['pokok_pinjaman_bunga'] + $data['lytd']['koreksi_bunga'];
        $data['lytd']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['lytd']['bunga_pinjaman_percent'] = (!empty($data['lytd']['total_pokok_pinjaman']) && !empty($data['lytd']['total_bunga_pinjaman']))? $data['lytd']['total_bunga_pinjaman'] / $data['lytd']['total_pokok_pinjaman'] * 100 : 0;
        $data['lytd']['gross_profit'] = $data['lytd']['total_jual_pinjaman_barang'] - $data['lytd']['total_beli_pinjaman_barang'];
        $data['lytd']['percent_gross_profit'] = !empty($data['lytd']['total_jual_pinjaman_barang'])? $data['lytd']['gross_profit'] / $data['lytd']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['lytd']['grand_total_pendapatan_koperasi'] = $data['lytd']['total_pokok_pinjaman_bunga'] + $data['lytd']['total_jual_pinjaman_barang'];
        $data['lytd']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['lytd']['grand_total_pendapatan_koperasi'])? $data['lytd']['total_pokok_pinjaman_bunga'] / $data['lytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['lytd']['percent_jual_pinjaman_barang_2'] = !empty($data['lytd']['grand_total_pendapatan_koperasi'])? $data['lytd']['total_jual_pinjaman_barang'] / $data['lytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['lytd']['percent_grand_total_pendapatan_koperasi'] = $data['lytd']['percent_total_pokok_pinjaman_bunga_2'] + $data['lytd']['percent_jual_pinjaman_barang_2'];
        $data['lytd']['grand_total_pokok_koperasi'] = $data['lytd']['total_pokok_pinjaman'] + $data['lytd']['total_beli_pinjaman_barang'];
        $data['lytd']['percent_total_pokok_pinjaman_2'] = !empty($data['lytd']['grand_total_pokok_koperasi'])? $data['lytd']['total_pokok_pinjaman'] / $data['lytd']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['lytd']['percent_beli_pinjaman_barang_2'] = !empty($data['lytd']['grand_total_pokok_koperasi'])? $data['lytd']['total_beli_pinjaman_barang'] / $data['lytd']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['lytd']['percent_grand_total_pokok_koperasi'] = $data['lytd']['percent_total_pokok_pinjaman_2'] + $data['lytd']['percent_beli_pinjaman_barang_2'];
        $data['lytd']['laba_bunga_pinjaman'] = $data['lytd']['total_bunga_pinjaman'];
        $data['lytd']['percent_laba_bunga_pinjaman'] = !empty($data['lytd']['grand_total_pendapatan_koperasi'])? $data['lytd']['laba_bunga_pinjaman'] / $data['lytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['lytd']['laba_bunga_pinjaman_barang'] = $data['lytd']['gross_profit'];
        $data['lytd']['percent_laba_bunga_pinjaman_barang'] = !empty($data['lytd']['grand_total_pendapatan_koperasi'])? $data['lytd']['laba_bunga_pinjaman_barang'] / $data['lytd']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['lytd']['grand_total_laba_koperasi'] = $data['lytd']['laba_bunga_pinjaman'] + $data['lytd']['laba_bunga_pinjaman_barang'];
        $data['lytd']['percent_grand_total_laba_koperasi'] = $data['lytd']['percent_laba_bunga_pinjaman'] + $data['lytd']['percent_laba_bunga_pinjaman_barang'];


        $data['variance']['total_pokok_pinjaman'] = $data['ytd']['total_pokok_pinjaman'] - $data['lytd']['total_pokok_pinjaman'];
        $data['variance']['total_bunga_pinjaman'] = $data['ytd']['total_bunga_pinjaman'] - $data['lytd']['total_bunga_pinjaman'];
        $data['variance']['total_beli_pinjaman_barang'] = $data['ytd']['total_beli_pinjaman_barang'] - $data['lytd']['total_beli_pinjaman_barang'];
        $data['variance']['total_jual_pinjaman_barang'] = $data['ytd']['total_jual_pinjaman_barang'] - $data['lytd']['total_jual_pinjaman_barang'];
        $data['variance']['percent_pokok_pinjaman'] = 0;
        $data['variance']['percent_bunga_pinjaman'] = 0;
        $data['variance']['percent_beli_pinjaman_barang'] = 0;
        $data['variance']['percent_jual_pinjaman_barang'] = 0;
        $data['variance']['pokok_pinjaman_bunga'] = $data['variance']['total_pokok_pinjaman'] + $data['variance']['total_bunga_pinjaman'];
        $data['variance']['percent_pokok_pinjaman_bunga'] = 0;
        $data['variance']['koreksi_bunga'] = 0;
        $data['variance']['percent_koreksi_bunga'] = 0;
        $data['variance']['total_pokok_pinjaman_bunga'] = $data['variance']['pokok_pinjaman_bunga'] + $data['variance']['koreksi_bunga'];
        $data['variance']['percent_total_pokok_pinjaman_bunga'] = 0;
        $data['variance']['bunga_pinjaman_percent'] = (!empty($data['variance']['total_pokok_pinjaman']) && !empty($data['variance']['total_bunga_pinjaman']))? $data['variance']['total_bunga_pinjaman'] / $data['variance']['total_pokok_pinjaman'] * 100 : 0;
        $data['variance']['gross_profit'] = $data['variance']['total_jual_pinjaman_barang'] - $data['variance']['total_beli_pinjaman_barang'];
        $data['variance']['percent_gross_profit'] = !empty($data['variance']['total_jual_pinjaman_barang'])? $data['variance']['gross_profit'] / $data['variance']['total_jual_pinjaman_barang'] * 100 : 0;
        $data['variance']['grand_total_pendapatan_koperasi'] = $data['variance']['total_pokok_pinjaman_bunga'] + $data['variance']['total_jual_pinjaman_barang'];
        $data['variance']['percent_total_pokok_pinjaman_bunga_2'] = !empty($data['variance']['grand_total_pendapatan_koperasi'])? $data['variance']['total_pokok_pinjaman_bunga'] / $data['variance']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['variance']['percent_jual_pinjaman_barang_2'] = !empty($data['variance']['grand_total_pendapatan_koperasi'])? $data['variance']['total_jual_pinjaman_barang'] / $data['variance']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['variance']['percent_grand_total_pendapatan_koperasi'] = $data['variance']['percent_total_pokok_pinjaman_bunga_2'] + $data['variance']['percent_jual_pinjaman_barang_2'];
        $data['variance']['grand_total_pokok_koperasi'] = $data['variance']['total_pokok_pinjaman'] + $data['variance']['total_beli_pinjaman_barang'];
        $data['variance']['percent_total_pokok_pinjaman_2'] = !empty($data['variance']['grand_total_pokok_koperasi'])? $data['variance']['total_pokok_pinjaman'] / $data['variance']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['variance']['percent_beli_pinjaman_barang_2'] = !empty($data['variance']['grand_total_pokok_koperasi'])? $data['variance']['total_beli_pinjaman_barang'] / $data['variance']['grand_total_pokok_koperasi'] * 100 : 0;
        $data['variance']['percent_grand_total_pokok_koperasi'] = $data['variance']['percent_total_pokok_pinjaman_2'] + $data['variance']['percent_beli_pinjaman_barang_2'];
        $data['variance']['laba_bunga_pinjaman'] = $data['variance']['total_bunga_pinjaman'];
        $data['variance']['percent_laba_bunga_pinjaman'] = !empty($data['variance']['grand_total_pendapatan_koperasi'])? $data['variance']['laba_bunga_pinjaman'] / $data['variance']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['variance']['laba_bunga_pinjaman_barang'] = $data['variance']['gross_profit'];
        $data['variance']['percent_laba_bunga_pinjaman_barang'] = !empty($data['variance']['grand_total_pendapatan_koperasi'])? $data['variance']['laba_bunga_pinjaman_barang'] / $data['variance']['grand_total_pendapatan_koperasi'] * 100 : 0;
        $data['variance']['grand_total_laba_koperasi'] = $data['variance']['laba_bunga_pinjaman'] + $data['variance']['laba_bunga_pinjaman_barang'];
        $data['variance']['percent_grand_total_laba_koperasi'] = $data['variance']['percent_laba_bunga_pinjaman'] + $data['variance']['percent_laba_bunga_pinjaman_barang'];

        return $data;
    }
    

    private function _get_data_pinjaman($p = null) {
        $year = isset($p['year']) && !empty($p['year'])? $p['year'] : '' ;
        $month = isset($p['month']) && !empty($p['month'])? $p['month'] : '' ;
        $month_start = isset($p['month_start']) && !empty($p['month_start'])? $p['month_start'] : '' ;
        $month_end = isset($p['month_end']) && !empty($p['month_end'])? $p['month_end'] : '' ;
        
        if(isset($year) && !empty($year)){
            $this->db->where('pinjaman.year', $year);
        }
        
        if(isset($month) && !empty($month)){
            $this->db->where('pinjaman.month', $month);
        }

        if(isset($month_start) && !empty($month_start) && isset($month_end) && !empty($month_end)){
            $this->db->where("pinjaman.month BETWEEN $month_start AND $month_end");
        }

        $this->db->select([
            'COALESCE(ROUND(SUM(angsuran.pokok)), 0) total_pokok_pinjaman',
            'COALESCE(ROUND(SUM(angsuran.bunga)), 0) total_bunga_pinjaman'
        ])
        ->from('pinjaman')
        ->join('angsuran', "angsuran.pinjaman = pinjaman.id", 'left')
        ->where('pinjaman.status', 'Approved');
        
        $q = $this->db->get();
        
        return $q->row_array();
    }

    private function _get_data_pinjaman_barang($p = null) {
        $year = isset($p['year']) && !empty($p['year'])? $p['year'] : '' ;
        $month = isset($p['month']) && !empty($p['month'])? $p['month'] : '' ;
        $month_start = isset($p['month_start']) && !empty($p['month_start'])? $p['month_start'] : '' ;
        $month_end = isset($p['month_end']) && !empty($p['month_end'])? $p['month_end'] : '' ;
        
        if(isset($year) && !empty($year)){
            $this->db->where('pinjaman_barang.year', $year);
        }
        
        if(isset($month) && !empty($month)){
            $this->db->where('pinjaman_barang.month', $month);
        }

        if(isset($month_start) && !empty($month_start) && isset($month_end) && !empty($month_end)){
            $this->db->where("pinjaman_barang.month BETWEEN $month_start AND $month_end");
        }

        $this->db->select([
            'COALESCE(ROUND(SUM(pinjaman_barang.buy)), 0) total_beli_pinjaman_barang',
            'COALESCE(ROUND(SUM(pinjaman_barang.sell)), 0) total_jual_pinjaman_barang'
        ])
        ->from('pinjaman_barang')
        ->where('pinjaman_barang.status', 'Approved');
        
        $q = $this->db->get();
        
        return $q->row_array();
    }
}

