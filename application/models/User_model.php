<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class User_model extends CI_Model {
 
    public function check_user($data)
    {   
        $check = $this->db->get_where('user', "username = '{$data['username']}' AND password= '{$data['password']}' AND active = 1");

        if($check->num_rows() > 0){
            return $check->row_array()['id'];
        }else{
            return false;
        }
    }

    public function login_check() {
        if ($this->session->userdata('sess_data')) {
            $data = $this->session->userdata('sess_data');
            $user_detail = $this->detail($data["id"]);
            
            if(!empty($user_detail)){
                $data = array_merge($data, $user_detail);
            }

            return $data;
        }else{
            redirect("user/login");
        }
    }

    public function get()
    {
        return $this->db->select()->from('user')->order_by('id', 'asc')->get()->result_array();
    }

    public function detail($id)
    {
        return $this->db->select([
            'user.id',
            'user.username',
            'user.role',
            'user.active',
            'person.id person_id',
            'person.name',
            'person.nik',
            'person.tmk',
            'person.address',
            'person.phone',
            'person.email',
            'person.ktp',
            'person.profile_photo',
        ])
        ->from('user')
        ->join('person', 'user.id = person.user_id', 'left')
        ->where('user.id',$id)
        ->get()
        ->row_array();
    }

    public function create($data)
    {
        $this->db->insert('user', $data);

        return ($this->db->affected_rows()>0) ? true : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('user', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

}