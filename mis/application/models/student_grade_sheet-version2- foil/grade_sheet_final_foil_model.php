<?php

class Grade_sheet_final_foil_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function check_student_auth_id($admn_no)
    {
        $sql="select a.* from stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    function alumni_check_student_auth_id($admn_no)
    {
        $sql="select a.* from alumni_stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    //to get dept from user_details table
    
    function get_student_dept($admn_no)
    {
        $sql="select * from user_details where id=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    function get_student_foil_data($data){
        $this->db->where($data);
        $query = $this->db->get('final_semwise_marks_foil');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_student_foil_description($id){
        $this->db->select('a.*,b.name');
        $this->db->from('final_semwise_marks_foil_desc a');
        $this->db->join('subjects b', 'a.mis_sub_id = b.id','inner');
        $this->db->where('a.foil_id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_student_details($id){
        $sql = "SELECT UPPER(a.id) AS admn_no, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name, 
CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END as course_id,b.branch_id,c.name, UPPER(CONCAT(CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,a.photopath
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN cs_branches c ON c.id=b.branch_id
WHERE a.id=?";

        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }
    
    function get_student_gpa($id){
        $sql = "(select b.sem as semester,round(a.gpa,2)as core_gpa,'NA' as gpa,round(a.ogpa,2)as core_cgpa,'NA' as cgpa,
a.passfail as `core_status`,'NA' as `status`,a.examtype as `type`,'N' as hstatus from tabulation1 a 
inner join dip_m_semcode b on a.sem_code=b.semcode
where a.adm_no=?
group by a.ysession,a.wsms,a.examtype,a.sem_code)union
(select a.semester,round(a.core_gpa,2) as core_gpa,round(a.gpa,2)as gpa,round(a.core_cgpa,2)as core_cgpa,
round(a.cgpa,2)as cgpa,a.core_status,a.`status`,a.`type`,a.hstatus from final_semwise_marks_foil a
where a.admn_no=? and a.course<>'MINOR') ";

        $query = $this->db->query($sql,array($id,$id));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function grade_sheet_details_jrf_foil($admn_no, $sy, $sess,$et){
        $sql = "select c.name,a.grade from final_semwise_marks_foil_desc a 
inner join final_semwise_marks_foil b on b.id=a.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.id=a.mis_sub_id
where b.admn_no=? and b.session_yr=? and b.`session`=?
and b.`type`=?";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
        function grade_sheet_details_jrf_foil_subject_code($admn_no, $sy, $sess,$et){
        $sql = "select c.name,a.grade from final_semwise_marks_foil_desc a 
inner join final_semwise_marks_foil b on b.id=a.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.subject_id=a.sub_code
where b.admn_no=? and b.session_yr=? and b.`session`=?
and b.`type`=?";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function alumni_get_course_duration_sem($adm_no)
    {
        $sql="select a.course_id,b.duration,(b.duration*2)as tsem,a.auth_id  from alumni_stu_academic a
inner join cs_courses b on a.course_id=b.id and a.admn_no='".$adm_no."'
group by a.course_id";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
  
}

?>