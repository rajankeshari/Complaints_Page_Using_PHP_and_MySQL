<?php

class student_feedback extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    
    function get_feedback_not_submitted_list($cid,$bid,$sem)
   {

        $sql = " select a.admn_no,a.semester_".$sem.",b.semester from fb_student_feedback a 
inner join reg_regular_form b on a.admn_no=b.admn_no
where b.course_id='".$cid."' and b.branch_id='".$bid."' and b.semester='".$sem."' 
and b.hod_status='1' and b.acad_status='1' and a.semester_".$sem." is null";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
            return FALSE;
        }
    }
    
    //--------------------------Fetching data for others student---------------
    
    function get_feedback_not_submitted_list_from_others($cid,$bid,$sem)
   {

        $sql = " select a.admn_no,a.semester_".$sem.",b.semester from fb_student_feedback a 
inner join reg_exam_rc_form b on a.admn_no=b.admn_no
where b.course_id='".$cid."' and b.branch_id='".$bid."' and b.semester='".$sem."' 
and b.hod_status='1' and b.acad_status='1' and a.semester_".$sem." is null";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    //--------------------------------------------------------------------------------
    
    function update_feedback_fine($admn_no,$sem,$msg)
    {
        $sql = " update fb_student_feedback set semester_".$sem."='".$msg."' where admn_no='".$admn_no."' ";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return TRUE;
        } else {
            return FALSE;
        }
    }
    function get_report_semwise($sem)
    {
        
        $sql = " select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester_".$sem." as semester from fb_student_feedback a
inner join user_details b on a.admn_no=b.id
where a.semester_".$sem." LIKE '%1000%'";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
            return FALSE;
        }
        
    }
    function get_session_year()
    {
       $sql = "select * from mis_session_year";

        $query = $this->db->query($sql);
     
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    
    }
    function get_session()
    {
        $sql = "select * from mis_session";

        $query = $this->db->query($sql);
     
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

 

}

?>