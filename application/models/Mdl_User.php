<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_User extends CI_Model
{
    function __construct()
    {
      
        parent::__construct();
     
    }
  
    public function get_data($select, $id){
        $this->db->select($select);
        $this->db->where('id',$id);
        $query = $this->db->get('userDetails');
        $result = $query->result();
        return $result;
    }
}