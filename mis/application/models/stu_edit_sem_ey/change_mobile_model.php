<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
class Change_mobile_model extends CI_Model
{
    function __construct() 
    {
        parent::__construct();
    }
    function update_mobile_number($mobileno,$uid) {
        $myquery = "update user_other_details set mobile_no=? where id=?";
        $query = $this->db->query($myquery, array($mobileno,$uid));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    
}
