<?php

class Dsc_model extends CI_Model { 

    function __construct() {
        parent::__construct();
    }

        
    function get_guide_co_guide($id)
    {
       
        $sql="SELECT * from project_guide WHERE admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function guide_co_guide_details($id)
    {
       
        $sql="SELECT concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name)AS fname,
				a.id,c.name AS dname
				FROM  user_details a 
				INNER JOIN departments c ON c.id=a.dept_id
				WHERE a.id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	function get_internal_list_student_wise($id){
		$sql="SELECT b.id,b.project_id,c.name AS dname,b.dept_id,b.emp_no,CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS fname,
b.role
FROM project_guide a
INNER JOIN project_guide_internal b ON b.project_id=a.id
INNER JOIN departments c ON c.id=b.dept_id
INNER JOIN user_details d ON d.id=b.emp_no
WHERE a.admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	function get_external_list_student_wise($id){
		$sql="SELECT a.* FROM project_guide_external a
INNER JOIN project_guide b ON b.id=a.project_id
WHERE b.admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	function add_external_guide($data){
        if($this->db->insert('project_guide_external',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	function add_internal_guide($data){
        if($this->db->insert('project_guide_internal',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	
	function student_details($admn_no){
		
		$sql="SELECT a.dept_id,b.course_id,b.branch_id,b.semester FROM user_details a 
INNER JOIN stu_academic b ON b.admn_no=a.id
WHERE a.id=?";

        
        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function add_project_guide($data){
        if($this->db->insert('project_guide',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	function add_fellowmaster_guide($data){
        if($this->db->insert('fellow_master',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	
	function update_project_guide($co_guide,$sole_shared,$rowid){
		 $sql = "UPDATE project_guide SET co_guide=? , sole_shared=? WHERE id=?";
        $query = $this->db->query($sql,array($co_guide,$sole_shared,$rowid));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE; 
        }   
		
	}
	
	
	function update_fellowmaster_guide($co_guide,$admn_no){
		
		$sql = "UPDATE fellow_master SET co_guide=? WHERE stud_reg_no=?";
        $query = $this->db->query($sql,array($co_guide,$admn_no));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }   
		
	}
	
	function get_project_details($admn_no){
		
		$sql="SELECT a.*, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS guide_name,
 CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS co_guide_name
FROM project_guide a
LEFT JOIN user_details b ON b.id=CAST(a.guide as CHAR(50))
LEFT JOIN user_details c ON c.id=CAST(a.co_guide as CHAR(50))
WHERE a.admn_no=?
GROUP BY a.admn_no";

        
        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function update_project_guide1($proj_id,$admn_no,$role,$emp_no){

		if($role=='guide'){
		$sql = "UPDATE project_guide SET guide=? WHERE id=? AND admn_no=?";
		}
		if($role=='coguide'){
		$sql = "UPDATE project_guide SET co_guide=? WHERE id=? AND admn_no=?";
		}
        $query = $this->db->query($sql,array($emp_no,$proj_id,$admn_no));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        } 
	
	}
	function update_fellow_master1($admn_no,$role,$emp_no){

		if($role=='guide'){
		$sql = "UPDATE fellow_master SET guide=? WHERE stud_reg_no=?";
		}
		if($role=='coguide'){
		$sql = "UPDATE fellow_master SET co_guide=? WHERE stud_reg_no=?";
		}
        $query = $this->db->query($sql,array($emp_no,$admn_no));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        } 
	
	}
	function update_project_guide_internal($proj_id,$role,$emp_no){
		$sql = "UPDATE project_guide_internal SET emp_no=? WHERE project_id=? AND role=?";
		$query = $this->db->query($sql,array($emp_no,$proj_id,$role));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        } 
		
	}
	
	function getdata_project_guide($proj_id,$admn_no){
		$sql="SELECT a.* FROM project_guide a WHERE a.id=? AND a.admn_no=?";

        
        $query = $this->db->query($sql,array($proj_id,$admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function getdata_project_guide_internal($proj_id,$role){
		$sql="SELECT a.* FROM project_guide_internal a WHERE a.project_id=? AND role=?";

        
        $query = $this->db->query($sql,array($proj_id,$role));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	
	function add_backup($data){
		if($this->db->insert('project_guide_backup',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	
	
	}

	function remove_data($id,$nm,$tbl)
	{

		$sql = "UPDATE ".$tbl." SET ".$nm."=null where id=".$id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return '1';
        } else {
            return '0'; 
        }   

	}
	
	function add_project_guide_backup($id,$nm,$tbl,$admn_no){

		date_default_timezone_set('Asia/Calcutta');

		if($nm=="guide" ||$nm=="co_guide"){
			$tmp=$this->getdata_project_guide($id,$admn_no);
				if($nm=="guide"){
					$data['emp_no']=$tmp->guide;
				}
				if($nm=="co_guide"){
					$data['emp_no']=$tmp->co_guide;
				}

		}
		if($nm=="chairman" ||$nm=="mem_dept" ||$nm=="mem_sis_dept"){
				
			$tmp=$this->getdata_project_guide_internal($id,$nm);

			$data['emp_no']=$tmp->emp_no;	
		}

		
		
		$data['admn_no']=$admn_no;
		$data['role']=$nm;
		$data['created_by']=$this->session->userdata('id');
		$data['created_date']=date("Y-m-d H:i:s");
		$data['remark1']='removed';
		$data['remark2']='';



        if($this->db->insert('project_guide_backup',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }

    function remove_data_internal($id,$nm,$tbl)
	{
		$tmp=$this->getdata_project_guide_internal($id,$nm);
		// $sql = "UPDATE project_guide_internal SET emp_no=null where id=?";
  //       $query = $this->db->query($sql,array($tmp->id));
  //       
        $sql="DELETE FROM project_guide_internal WHERE id=?";

        
        $query = $this->db->query($sql,array($tmp->id));

        if ($this->db->affected_rows() > 0) {
            return '1';
        } else {
            return '0'; 
        }   

	}

		
	
		function get_external_list_student_wise_rolewise($id,$role)
		{
		
		$sql="SELECT a.* FROM project_guide_external a
INNER JOIN project_guide b ON b.id=a.project_id
WHERE b.admn_no=? and a.ext_role=?";

        
        $query = $this->db->query($sql,array($id,$role));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}

	function add_project_guide_external_backup($id){

		$sql="INSERT INTO project_guide_external_backup 
SELECT a.* FROM project_guide_external a WHERE a.id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return '1';
        } else {
            return '0'; 
        }   

	}
	function remove_project_guide_external($id){

$sql="DELETE FROM project_guide_external WHERE id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return '1';
        } else {
            return '0'; 
        }   

	}
	function update_project_guide_external($id){

$sql = "UPDATE project_guide_external_backup SET remark1=?, remark2=? WHERE id=?";
		$query = $this->db->query($sql,array($this->session->userdata('id'),date("Y-m-d H:i:s"),$id));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        } 


	}





			
	
	
	
	
	
    

}

?>