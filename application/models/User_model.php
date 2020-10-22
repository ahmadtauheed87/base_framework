<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function check_email($email)
    {
        return $user = $this->db->get_where('users',array('email'=>$email))->row_array();
    }

    function register_user($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    function login($email,$password) {
        return $result = $this->db->get_where('users',array('email'=>$email , 'password' =>md5($password)))->row_array();
    }
}