<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model {
    public function login($username){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$username);
        return $this->db->get()->row_array();
    }
}