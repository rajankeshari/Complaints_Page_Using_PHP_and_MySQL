<?php

class Cash_prize_report extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student($syear,$did,$cid,$bid,$sem,$gender,$category,$pc)
    {
        
        
        $sql = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
a.dept,a.course,a.branch,a.semester,a.core_gpa as 'core_gpa',a.gpa 'with_honours_gpa',
a.core_status as 'core_status',a.`status` as 'honours_status', c.name AS dname,d.name AS cname,e.name AS bname,b.sex,b.category
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN departments c ON c.id= LCASE(a.dept)
INNER JOIN cs_courses d ON d.id= LCASE(a.course)
INNER JOIN cs_branches e ON e.id= LCASE(a.branch)
WHERE a.`session`='Winter' AND a.`type`='R' AND a.core_status='Pass' and a.course<>'MINOR' ";

        
     
        
        if($syear!=''){
            
            $sql.="and a.session_yr='".$syear."'";
           
            
        }
        if($did!='none'){
            $sql.="and a.dept='".$did."'";
           
        }
        if($cid!='none'){
            $sql.="and a.course='".$cid."'";
            
        }
        if($bid!='none'){
            $sql.="and a.branch='".$bid."'";
            
        }
        if($sem!=''){
            $sql.="and a.semester='".$sem."'";
            
        }
        if($gender!='both'){
            $sql.="and b.sex='".$gender."'";
            
        }
        if($category!='all'){
            $sql.="and b.category='".$category."'";
            
        }
        if($pc=='yes'){
            $sql.="and b.physically_challenged='".$pc."'";
            
        }
        
        
        
        
        $query = $this->db->query("$sql order by a.dept,a.course,a.branch,a.semester,a.admn_no");

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_student_monsoon($syear,$admn_no)
    {
          
        $sql = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,a.dept,a.course,a.branch,a.semester
,a.core_gpa as 'core_gpa',a.gpa 'with_honours_gpa', a.core_status as 'core_status',a.`status` as 'honours_status',a.hstatus
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.session_yr=? AND a.`session`='Monsoon' AND a.`type`='R' AND a.admn_no=? and a.course<>'MINOR'";

        $query = $this->db->query($sql,array($syear,$admn_no));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    

}

?>