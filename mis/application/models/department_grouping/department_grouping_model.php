<?php

class Department_grouping_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
      
    function get_session_year(){
     	$sql="SELECT a.* FROM mis_session_year a ORDER BY a.session_year DESC LIMIT 3";

     	$query=$this->db->query($sql);
     	return $query->result();
    }

    function get_dept_grouping_details(){
    	$query=$this->db->get('dept_grouping');
    	return $query->result();
    }

    function check_duplicate_entry($dept,$course,$sy,$group){
    	$sql="SELECT * FROM dept_grouping a WHERE a.dept_id='$dept' AND a.course_id='$course' /*AND a.group_id='$group' */AND  a.wef_year='$sy'";
    	$query=$this->db->query($sql);
    	return $query->result();
    }

    function insert_dept_grouping($data){
    	if($this->db->insert('dept_grouping',$data))
			return TRUE;
		else
			return FALSE;
    }

    function delete_dept_grouping_by_id($id){
    	if($this->db->delete('dept_grouping', array('id' => $id))){
    		return true;
    	}else{
    		return false;
    	}
    }

    function check_duplicate_entry_before_update($dept,$course,$sy,$group,$id){
    	$sql="SELECT * from dept_grouping WHERE dept_id='$dept' AND course_id='$course' AND wef_year='$sy' AND group_id='$group' AND id !='$id'";
        $query=$this->db->query($sql);
    	return $query->result();
    }

    function update_dept_grouping_by_id($data,$id){
        $this->db->where('id',$id);
        if($this->db->update('dept_grouping',$data)){
            return true;
        }else{
            return false;
        }

    }

    function get_department_list(){
        $sql="SELECT a.dept_id,b.*
        FROM dept_grouping a
        INNER JOIN cbcs_departments b ON a.dept_id=b.id";
        $query=$this->db->query($sql);
        return $query->result();
    }

    function get_course_list_dept($dept_id){
        $sql="SELECT a.dept_id,a.course_id,b.*
        FROM dept_grouping a
        INNER JOIN cbcs_courses b ON a.course_id=b.id
        WHERE a.dept_id='$dept_id'";
        $query=$this->db->query($sql);
        return $query->result();
    }

    function get_detais_from_dept_grouping($course_id,$dept_id){
        $sql="SELECT *
        FROM dept_grouping a
        WHERE a.dept_id='$dept_id' AND a.course_id='$course_id'";
        $query=$this->db->query($sql);
        return $query->result();
    }

    function get_course_component_list($sem,$course_id){
        $sql="SELECT *
        FROM cbcs_coursestructure_policy a
        WHERE a.course_id='$course_id' AND a.sem='$sem' and a.course_component in ('HSS+MS','HSS','MS')
        group BY a.course_component";
        $query=$this->db->query($sql);
        return $query->result();
    }

    function check_duplicate_group_course_type_entry($data){
        $query=$this->db->get_where('group_course_type',$data);
        return $query->result();
        // if($query)
        //     return true;
        // else
        //     return false;
    }

    function insert_group_dept_type($data){
        if($this->db->insert('group_course_type',$data))
            return TRUE;
        else
            return FALSE;
    }

    function get_group_course_type_details(){
        $query=$this->db->get('group_course_type');
        return $query->result();
    }

    function check_duplicate_entry_before_group_course_type_update($dept,$course,$branch,$sy,$sess,$sem,$group,$cc,$sc,$id){
        $sql="SELECT * FROM group_course_type a WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.session_year='$sy' AND a.`session`='$sess' AND a.semester='$sem' AND a.group_id='$group' AND a.sub_category='$sc' and a.id!='$id'";
        $query=$this->db->query($sql);
        return $query->result();
    }

    function update_group_course_type_by_id($data,$id){
        $this->db->where('id',$id);
        if($this->db->update('group_course_type',$data)){
            return true;
        }else{
            return false;
        }
    }

    function delete_group_course_type_by_id($id){
        if($this->db->delete('group_course_type', array('id' => $id))){
            return true;
        }else{
            return false;
        }
    }

    function check_opted_before_delete($id){
         $sql="SELECT *
FROM dept_grouping a
INNER JOIN group_course_type b ON a.dept_id=b.dept_id AND a.course_id=b.course_id AND a.group_id=b.group_id
WHERE a.id='$id'";
        $query=$this->db->query($sql);
        return $query->result();
    }
 }