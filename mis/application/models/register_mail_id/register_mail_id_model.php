<?php

class Register_mail_id_model extends CI_Model{ // Model for student email registration

    function __construct() {    // Call the Model constructor
        parent::__construct();
    }
     
    function get_student_details_by_admn_no($id){   // Getting student various details from different tables 

     /*  $sql = "select a.id,a.first_name,a.middle_name,a.last_name,a.dept_id, e.Email as email, b.enrollment_year, e.Dept as department,d.duration,d.name as course, e.`Mobile No.` as phn_no, f.name as branch from user_details a 
        inner join stu_academic b on a.id=b.admn_no
        inner join cs_branches f on f.id=b.branch_id
        inner join cs_courses d on d.id=b.course_id
        inner join stuadsuser e on e.Adm_no=a.id
        where a.id='".$id."' "; 
	*/  
		$sql = "select a.id,a.first_name,a.middle_name,a.last_name,a.dept_id, a.email, b.enrollment_year,d.duration,d.name as course, e.name as department, f.name as branch, g.mobile_no as phn_no from user_details a 
        inner join stu_academic b on a.id=b.admn_no
        inner join cs_branches f on f.id=b.branch_id
        inner join cs_courses d on d.id=b.course_id
        inner join departments e on e.id=a.dept_id
        inner join user_other_details g on g.id=a.id
        where a.id='".$id."' ";

	   // sql query to get various details of student

        $query = $this->db->query($sql);
    // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }
	
	

    function get_student_details_by_admn_no_jrf($id){   // Getting jrf-student various details from different tables 

      /*  $sql = "select a.id,b.auth_id as auth_id,a.first_name,a.middle_name,a.last_name,a.dept_id,d.Email as email, b.enrollment_year, d.Dept as department, d.`Mobile No.` as phn_no from user_details a 
        inner join stu_academic b on a.id=b.admn_no
        inner join stuadsuser d on d.Adm_no=a.id
        where a.id='".$id."'"; 
		*/
         $sql = "select a.id,b.auth_id as auth_id,a.first_name,a.middle_name,a.last_name,a.dept_id,a.email, b.enrollment_year, c.name as department, d.mobile_no as phn_no from user_details a 
        inner join stu_academic b on a.id=b.admn_no
        inner join departments c on c.id=a.dept_id
        inner join user_other_details d on d.id=a.id
        where a.id='".$id."'";
		// sql query to get various details of jrf-student

        $query = $this->db->query($sql);
		//echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }

    function getUser($userName){	 // checking student email is already present or not in database for duplicates
        
        $whereCondition = $array = array('domain_name' => $userName);
        
		$this->db->where($whereCondition); 
        $query = $this->db->get('emaildata');	

        if($query->num_rows()>0){
            return true;
        }
        else{
            return false;
        }		
    }
    
    function check_register_student($id){   // checking student and project employee is registered for email or not

        $sql = "select domain_name,password from emaildata where admission_no=?";

        $query = $this->db->query($sql,$id);
		//echo $this->db->last_query(); die();
        if ($query->num_rows()>0) {

            return $query->result();
        }
        else{
            return false;
        }
    }
    function check_validated_student($id){  // checking student email is activated or not

        $sql = "select Active from emaildata where admission_no=?";

        $query = $this->db->query($sql,$id)->result_array();

        if($query['0']['Active']==1){

            return true;
        }
        else{
            return false;
        }
    }
    function insert_emaildata($data){       // storing student email data to permanent table emaildata

		
		if($this->db->insert('emaildata',$data)){
			//var_dump($data);echo "here ok";die();
            return TRUE;
        }    
		else{
			//var_dump($data);echo "here not ok";die();
            return FALSE;
        }
    }
    
    function insert_email_form($data){      // storing student basic email data to temporary table email_form

		if($this->db->insert('email_form',$data)){
            return TRUE;
        }
		else{
            return FALSE;
        }
	}
	
	function get_project_emp_details_by_no($id){   // Getting project employee various details from different tables 

    
		$sql = "select a.emp_no,a.first_name,a.middle_name,a.last_name,a.dept_id, a.joining_date, e.name as department from project_emp_details a 
                
        inner join departments e on e.id=a.dept_id
       
        where a.emp_no='".$id."' ";

	   // sql query to get various details of student

        $query = $this->db->query($sql);
		
     //echo $this->db->last_query(); die();
	 
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }
}
?>
