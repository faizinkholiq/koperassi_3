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
            
            $data = array_merge($data, $user_detail);

            return $data;
        }else{
            redirect("user/login");
        }
    }

    public function list()
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
            'user_detail.name',
            'user_detail.nik',
            'user_detail.tmk',
            'user_detail.address',
            'user_detail.phone',
            'user_detail.email',
            'user_detail.ktp',
            'user_detail.avatar',
        ])
        ->from('user')
        ->join('user_detail', 'user.id = user_detail.user_id', 'left')
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

        return ($this->db->affected_rows()>0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

}