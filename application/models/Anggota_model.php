<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Anggota_model extends CI_Model {

    public function get($role = null)
    {
        $q = $this->db->select([
            "person.id",
            "person.no_ktp",
            "person.nik",
            "person.name",
            "person.phone",
            "person.join_date",
            "person.status",
            "person.position",
            "position.name position_name",
            "person_temp.status status_perubahan",
            "person_temp.reason",
        ])
        ->from('person')
        ->join('user', 'user.id = person.user_id')
        ->join('position', 'position.id = person.position', 'left')
        ->join('person_temp', 'person_temp.person_id = person.id', 'left')
        ->order_by('person.id', 'asc');

        if (!empty($role)){
            $q->where('user.role', '1');
        }else{
            $q->where('user.role', '2');
        }

        return $q->get()->result_array();
    }

    public function get_dt($p)
    {
        $search = $p["search"];
        
        $this->db->start_cache();

        if(!empty($search["value"])){
			$col = ["person.no_ktp", "person.nik", "person.name", "person.phone", "person.join_date", "position.name", "person_temp.status"];
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


        if (!empty($p['role'])){
            $this->db->where('user.role', '1');
        }else{
            $this->db->where('user.role', '2');
        }

        $limit = $p["length"];
		$offset = $p["start"];

        $this->db->select([
            "person.id",
            "person.no_ktp",
            "person.nik",
            "person.name",
            "person.phone",
            "person.join_date",
            "person.status",
            "person.position",
            "position.name position_name",
            "person_temp.status status_perubahan",
            "person_temp.reason",
            "CONCAT('Rp', FORMAT(IF(sukarela.total IS NOT NULL AND sukarela.total != 0, sukarela.total + person.sukarela, IF(person.sukarela IS NOT NULL, person.sukarela, 0)), 0, 'id_ID')) sukarela",
            "CONCAT('Rp', FORMAT(IF(investasi.total IS NOT NULL AND investasi.total != 0, investasi.total + person.investasi, IF(person.investasi IS NOT NULL, person.investasi, 0)), 0, 'id_ID')) investasi",
            'ROW_NUMBER() OVER(ORDER BY person.id ASC) AS row_no'
        ])
        ->from('person')
        ->join('user', 'user.id = person.user_id')
        ->join('position', 'position.id = person.position', 'left')
        ->join('person_temp', 'person_temp.person_id = person.id', 'left')
        ->join('(SELECT person, SUM(balance) total FROM simpanan_sukarela WHERE posting = 1 GROUP BY person) sukarela', 'sukarela.person = person.nik', 'left')
        ->join('(SELECT person, SUM(balance) total FROM simpanan_investasi WHERE posting = 1 GROUP BY person) investasi', 'investasi.person = person.nik', 'left')
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

    public function list()
    {
        $q = $this->db->select([
                "person.id",
                "person.user_id",
                "person.no_ktp",
                "person.nik",
                "person.name",
                "person.address",
                "person.phone",
                "person.join_date",
                "person.status",
                "person.position",
                "position.name position_name",
                "person.depo",
                "person.acc_no",
                "person.phone",
                "person.email",
                "person.ktp",
                "person.profile_photo",
                "person.join_date",
                "person.status",
                "person.salary",
            ])
            ->from('person')
            ->join('user', 'user.id = person.user_id')
            ->join('position', 'position.id = person.position', 'left')
            ->where('person.status', 'Aktif')
            ->where('user.role', '2')
            ->order_by('person.id', 'asc');
        return $q->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select([
            "person.id",
            "person.user_id",
            "person.no_ktp",
            "person.nik",
            "person.name",
            "person.address",
            "person.phone",
            "person.join_date",
            "person.status",
            "person.position",
            "person.depo",
            "person.acc_no",
            "person.phone",
            "person.email",
            "person.ktp",
            "person.profile_photo",
            "person.join_date",
            "person.status",
            "person.salary",
            "person.sukarela",
            "person.investasi",
            'person_family.id id_family',
            'person_family.name name_family',
            'person_family.address address_family',
            'person_family.phone phone_family',
            'person_family.status status_family',
            'position.name position_name',
            'depo.name depo_name',
            'user.id user_id'
        ])
        ->from('person')
        ->join('person_family', 'person.id = person_family.person', 'left')
        ->join('position', 'person.position = position.id', 'left')
        ->join('depo', 'person.depo = depo.code', 'left')
        ->join('user', 'person.user_id = user.id', 'left')
        ->where('person.id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('person', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('person', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('person');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_keluarga()
    {
        return $this->db->select()->from('person_family')->order_by('id', 'asc')->get()->result_array();
    }

    public function detail_keluarga($id)
    {
        return $this->db->select()
        ->from('person_family')
        ->where('id',$id)
        ->get()
        ->row_array();
    }

    public function create_keluarga($data)
    {
        $this->db->insert('person_family', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_keluarga($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('person_family', $data);

        return ($this->db->affected_rows()>0) ? true : false;
    }

    public function delete_keluarga($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('person_family');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_list_position()
    {
        $q = $this->db->select()->from('position')->order_by('id', 'desc');
        return $q->get()->result_array();
    }

    public function get_list_depo()
    {
        $q = $this->db->select()->from('depo')->order_by('id', 'desc');
        return $q->get()->result_array();
    }

    public function create_temp($data)
    {
        $this->db->insert('person_temp', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_temp($data)
    {   
        $this->db->where('person_id', $data['id']);
        unset($data['id']);
        $this->db->update('person_temp', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function detail_temp($id)
    {
        return $this->db->get_where('person_temp', ["person_id" => $id])->row_array();
    }

    public function delete_temp($id)
    {
        $this->db->where('person_id', $id);
        $this->db->delete('person_temp');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }
    
}