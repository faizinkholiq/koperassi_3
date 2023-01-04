<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Person_model extends CI_Model {

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
        ])
        ->from('person')
        ->where('person.id', $id)
        ->get()
        ->row_array();
    }

}