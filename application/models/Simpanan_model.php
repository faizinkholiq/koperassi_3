<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Simpanan_model extends CI_Model {

    public function get($person)
    {
        $q_pokok = $this->db->select([
                'simpanan_pokok.id',
                'simpanan_pokok.person person_id',
                'simpanan_pokok.code',
                'person.name',
                'person.nik',
                'person.tmk',
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
            ->join('person', 'person.id = simpanan_pokok.person')
            ->join('position', 'person.position = position.id', 'left')
            ->where('person.id', $person)
            ->get_compiled_select();
        
        $q_wajib = $this->db->select([
            'simpanan_wajib.id',
            'simpanan_wajib.person person_id',
            'simpanan_wajib.code',
            'person.name',
            'person.nik',
            'person.tmk',
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
        ->join('person', 'person.id = simpanan_wajib.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_sukarela = $this->db->select([
            'simpanan_sukarela.id',
            'simpanan_sukarela.person person_id',
            'simpanan_sukarela.code',
            'person.name',
            'person.nik',
            'person.tmk',
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
        ->join('person', 'person.id = simpanan_sukarela.person')
        ->join('position', 'person.position = position.id', 'left')
        ->join('simpanan_temp', 'simpanan_temp.simpanan_id = simpanan_sukarela.id AND simpanan_temp.type = "Sukarela"', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_investasi = $this->db->select([
            'investasi.id',
            'investasi.person person_id',
            'investasi.code',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.phone',
            'person.join_date',
            'person.depo',
            'person.address',
            'person.acc_no',
            'person.position',
            'position.name position_name',
            '"Simpanan Wajib" type',
            'investasi.date',
            'investasi.balance',
            '0 simpanan_temp_id',
        ])
        ->from('investasi')
        ->join('person', 'person.id = investasi.person')
        ->join('position', 'person.position = position.id', 'left')
        ->where('person.id', $person)
        ->get_compiled_select();

        $q_all = $this->db->query($q_pokok.' UNION ALL '.$q_wajib. ' UNION ALL '.$q_sukarela.' UNION ALL '.$q_investasi.' ORDER BY date DESC');
        return $q_all->result_array();
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
                + COALESCE(investasi.total, 0) balance',
            ])
            ->from('person')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_pokok 
                GROUP BY person
            ) simpanan_pokok', 'person.id = simpanan_pokok.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_wajib 
                GROUP BY person
            ) simpanan_wajib', 'person.id = simpanan_wajib.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM simpanan_sukarela 
                GROUP BY person
            ) simpanan_sukarela', 'person.id = simpanan_sukarela.person', 'left')
            ->join('(
                SELECT 
                    person, 
                    SUM(balance) total 
                FROM investasi 
                GROUP BY person
            ) investasi', 'person.id = investasi.person', 'left')
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
        ->where('person_id', $id)
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
            "person_id" => $person, 
            "code" => $code,
            "type" => $type,
            "status" => $status,
        ])->row_array();
    }

    public function get_settings()
    {
        return $this->db->get('simpanan_settings')->result_array();
    }

    public function detail_settings($id)
    {
        return $this->db->get_where('simpanan_settings', ["id" => $id])->row_array();
    }

    public function create_settings($data)
    {
        $this->db->insert('simpanan_settings', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit_settings($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('simpanan_settings', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete_settings($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('simpanan_settings');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function get_default_nominal($type)
    {
        return $this->db->get_where('simpanan_settings', ['simpanan' => $type])->row_array();
    }

}