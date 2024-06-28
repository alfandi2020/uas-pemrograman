<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {
    public function update_user($id){
                $role  = $this->input->post('role') == null ? '1' : $this->input->post('role');
                $data = [
                    "nama" => $this->input->post('nama'),
                    "username" => $this->input->post('username'),
                    "password" => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
                    "role" => $role,
                ];
                $this->db->where('id',$id);
                return $this->db->update('users',$data);
    }
}