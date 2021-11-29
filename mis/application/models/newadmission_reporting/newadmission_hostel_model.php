<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Newadmission_hostel_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user($admn_no)
    {
        $query=$this->db->select('*')->from('users')->join('user_details', 'users.id=user_details.id')
        ->join('user_address', 'users.id=user_address.id and user_address.type="present"')
        ->join('stu_academic', 'users.id=stu_academic.admn_no')->where('users.id',$admn_no)->get();
        $row=$query->result_array();
        return $row;
    }

    function get_branch($branch_id)
	{
		$this->db->where('id',$branch_id);
		$query=$this->db->get('branches');
        $row=$query->row_array();
		return $row;
    }
    
    function get_course($course_id)
	{
		$this->db->where('id',$course_id);
		$query=$this->db->get('courses');
        $row=$query->row_array();
		return $row;
    }
    
    function get_department($dept_id)
	{
		$this->db->where('id',$dept_id);
		$query=$this->db->get('departments');
        $row=$query->row_array();
		return $row;
    }
    
    function insert_new_adm_repoting($new_adm_reporting)
	{
        $this->db->insert('new_adm_reporting',$new_adm_reporting);
        return TRUE;
    }
    
    function update_users_status($admn_no)
	{
        $this->db->update('users',array('status' => 'A'),array('id' => $admn_no));
        return TRUE;
    }
    
    function check_counter_repoting($admn_no)
	{
        $this->db->where('admn_no',$admn_no);
		$query=$this->db->get('new_adm_reporting');
        if($query->num_rows() > 0)
        {
            $row=$query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
        
	}

}