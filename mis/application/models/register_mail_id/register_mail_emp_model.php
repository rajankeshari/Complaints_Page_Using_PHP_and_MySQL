<?php

class Register_mail_emp_model extends CI_Model{     // Model for employee email registration

    function __construct() {    // Call the Model constructor
        parent::__construct();
    }

    function get_emp_details($id){ // Getting employee various details from different tables 

        $sql = "select a.id,a.first_name,a.middle_name,a.last_name,c.name as designation,a.email,e.mobile_no,c.type as ft_nft ,d.name as department from user_details a 
        inner join emp_basic_details b on a.id=b.emp_no
        inner join designations c on c.id=b.designation
        inner join departments d on d.id=a.dept_id
        inner join user_other_details e on e.id=a.id
        where a.id=? "; 
        // sql query to get various details of employee

        $query = $this->db->query($sql,array($id));
     
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }

    function getUser($userName){  // checking employee email is already present or not in database for duplicates

        $sql =   "select domain_name from emaildata_emp where domain_name like '%".$userName."%' ";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        	return true;
        }
        else{
            return false;
        }			
    }
    
    function check_register_emp($id){  // checking employee is registered for email or not

        $sql =   "select domain_name,password from emaildata_emp where emp_id='".$id."'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }
    function check_validated_emp($id){  // checking employee email is activated or not

        $sql = "SELECT Active FROM emaildata_emp WHERE emp_id='".$id."'";
        $query = $this->db->query($sql)->result_array();
        if($query['0']['Active']==1){
            return true;
        }
        else{
            return false;
        }
    }
    function insert_emaildata($data){   // storing employee email data to permanent table emaildata_emp

		if($this->db->insert('emaildata_emp',$data)){
            return TRUE;
        }    
		else{
            return FALSE;
        }		
	}
          
    function insert_email_form_emp($data){  // storing employee basic email data to temporary table email_form_emp

        if($this->db->insert('email_form_emp',$data)){
            return true;
        }
        else{
            return false;
        }
    } 
}
?>
