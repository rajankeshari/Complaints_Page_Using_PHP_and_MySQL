<?php
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Parent_credential_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user($admn_no)
    {
        $query = $this->db->select('*')->from('emaildata')->join('user_details', 'emaildata.admission_no=user_details.id')
        ->where('user_details.id',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function get_stu($admn_no)
    {
        $query = $this->db->select('*')->from('user_details')->join('stu_academic', 'user_details.id=stu_academic.admn_no')
        ->join('user_other_details', 'user_details.id=user_other_details.id')
        ->where('user_details.id',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            $branch = $this->get_branch($row['branch_id']);
            $course = $this->get_course($row['course_id']);
            $department = $this->get_department($row['dept_id']);
            $row['branch'] = $branch['name'];
            $row['course'] = $course['name'];
            $row['department'] = $department['name'];
            return $row;
        }
        else
        {
            return false;
        }
    }

    function parent_details($admn_no)
    {
        $query = $this->db->select('*')->from('stu_details')->join('stu_other_details', 'stu_details.admn_no=stu_other_details.admn_no')
        ->where('stu_details.admn_no',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function user_email_domain($admn_no)
    {
       $this->db->where('admission_no',$admn_no);
	   $query=$this->db->get('emaildata');
       $row = $query->result_array();
       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function user_email($admn_no)
    {
        $this->db->where('id',$admn_no);
        $query=$this->db->get('user_details');
       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function insert_parent_credential_logs($parent_credential_logs)
	{
        $this->db->insert('parent_credential_logs',$parent_credential_logs);
        return TRUE;
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
    
    // function update_users_status($admn_no)
	// {
    //     $this->db->update('users',array('status' => 'A'),array('id' => $admn_no));
    //     return TRUE;
    // }
    

}