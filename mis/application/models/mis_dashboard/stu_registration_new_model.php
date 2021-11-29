<?php

class Stu_registration_new_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function insertFeeDetails($fee){
         if($this->db->insert('reg_idle_fee', $fee)){
           return $this->db->insert_id();
        }
    }
	function insertFeeDetailsRegular($fee){
         if($this->db->insert('reg_regular_fee', $fee)){
           return $this->db->insert_id();
        }
    }
	
	
	
	function insertForm($data){
        if($this->db->insert('reg_idle_form', $data)){
           return $this->db->insert_id();
        }
    }
	function insertFormRegular($data){
        if($this->db->insert('reg_regular_form', $data)){
           return $this->db->insert_id();
        }
    }
	function already_exists($admn_no,$syear,$sess,$sem)
	{
		$sql="SELECT a.* FROM reg_idle_form a WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND a.semester=?";
        $query = $this->db->query($sql,array($admn_no,$syear,$sess,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	function already_exists_regular($admn_no,$syear,$sess,$sem)
	{
		$sql="SELECT a.* FROM reg_regular_form a WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND a.semester=?";
        $query = $this->db->query($sql,array($admn_no,$syear,$sess,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function get_stu_academic($id){
		$sql="SELECT * FROM stu_academic WHERE admn_no=?";
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
             return $query->row();
        } else {
            return false;
        }
	}
	
    

 
    

}

?>