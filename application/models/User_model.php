<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class User_model extends CI_Model {
 
    public function check_user($data)
    {   
        $check = $this->db->get_where('user', [
            "username"  => $data['username'], 
            "password" => $data['password'], 
            "active" => 1
        ]);

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

            $data["notification"] = $this->get_notif($data["id"], $data["role"], 3);
            $data["count_notification"] = count($this->get_notif($data["id"], $data["role"]));
            
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
            'user.password',
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

    public function get_notif($id, $role = 2, $limit = null){
        $data = $this->db->select([
            "notification.*",
            "DATE_FORMAT(notification.time, '%M %d, %Y') time",
            "person.id person_id",
            "person.name person_name",
        ])->from('notification')
        ->join('person', 'person.user_id = notification.user_id')
        ->order_by('id', 'DESC')
        ->order_by("FIELD(notification.status, 'Pending', 'Success', 'Failed')");
        
        if ($role == 2) {
            $data->where('notification.user_id', $id);
        }

        if($limit){
            $data->limit($limit);
        }
        
        return $data->get()->result_array();
    }

    public function detail_notif($id){
        $data = $this->db->select([
            "notification.*",
            "DATE_FORMAT(notification.time, '%M %d, %Y') time",
            "person.id person_id",
            "person.name person_name",
        ])->from('notification')
        ->join('person', 'person.user_id = notification.user_id')
        ->where('notification.id', $id)
        ->get()->row_array();
        
        return $data;
    }

    public function create_notif($data)
    {
        $this->db->insert('notification', $data);

        return ($this->db->affected_rows()>0) ? true : false;
    }

    public function edit_notif($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('notification', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

}