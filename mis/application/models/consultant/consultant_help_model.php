<?php

/**
 * Author: Vivek Kumar
* Email: vivek0739@users.noreply.github.com
* Date: 20 june 2015
*/
class Consultant_help_model extends CI_Model
{
   var $table = '';
    function __construct()
    {
        parent::__construct();
    }
    public function getDesignation($id)
    {
    	$query=$this->db->query("SELECT designations.name as designation from emp_basic_details INNER JOIN designations ON emp_basic_details.designation = designations.id where emp_basic_details.emp_no='$id'");
    	return $query->row()->designation;
    }
    public function get_hod($emp_no)
    {
         
        $query=$this->db->query("SELECT user_auth_types.id as hod 
                                from user_details  as first 
                                INNER join user_details as second 
                                ON  first.dept_id=second.dept_id 
                                INNER JOIN user_auth_types 
                                ON second.id=user_auth_types.id 
                                where first.id='$emp_no' 
                                and user_auth_types.auth_id='hod';");
        if($query->num_rows()==0)
            return NULL;
        return $query->row()->hod;
       
        

    }
    public function get_hos($emp_no)
    {
         
         $query=$this->db->query("SELECT user_auth_types.id as hos
                                from user_details  as first 
                                INNER join user_details as second 
                                ON  first.dept_id=second.dept_id 
                                INNER JOIN user_auth_types 
                                ON second.id=user_auth_types.id 
                                where first.id='$emp_no' 
                               and user_auth_types.auth_id='hos';");
        return $query->row()->hos;
       

    }
    function get_detail_user($emp_no)
    {
        $query=$this->db->query("SELECT user_address.contact_no ,user_details.*
                                from user_address  
                                INNER JOIN user_details 
                                ON user_details.id=user_address.id 
                                where user_details.id='$emp_no';");
        return $query->row();
    }
     public function get_pce()
    {
        $query=$this->db->query("SELECT user_auth_types.id as pce from user_auth_types   where auth_id='pce';");
        return $query->row()->pce;
    }
    public function get_dt()
    {
        $query=$this->db->query("SELECT user_auth_types.id as dt from user_auth_types   where auth_id='dt';");
        return $query->row()->dt;
    }
    public function get_departments($dept_id)
    {

         $query=$this->db->query("SELECT departments.name as dept from departments  where id='$dept_id';");
        return $query->row()->dept;
    }
    public function get_ar_proj()
    {
        $query=$this->db->query("SELECT user_auth_types.id as pce from user_auth_types   where auth_id='acc_ar_prj';");
        return $query->row()->pce;
    }
    public function get_service_tax()
    {
        $query=$this->db->query("SELECT service_tax from consultancy_service_tax where timestamp = 
                                 (Select max(timestamp) from consultancy_service_tax)");
        return $query->row()->service_tax;
    }
    public function put_service_tax($timestamp , $service_tax)
    {
        $query = $this->db->query("INSERT into consultancy_service_tax values ( '$timestamp' , '$service_tax')");
    }
}