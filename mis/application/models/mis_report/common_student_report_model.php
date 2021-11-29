<?php

class common_student_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student($syear)
    {
       
          
        $sql = "select x.* from 
(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,b.dept_id, d.course_id,d.branch_id,b.category
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN stu_academic c ON c.admn_no=a.admn_no
INNER JOIN stu_academic d ON d.admn_no=a.admn_no
WHERE a.session_yr=? AND a.course='comm'
GROUP BY a.admn_no)x
order by x.dept_id,x.course_id,x.branch_id,x.admn_no";

        $query = $this->db->query($sql,array($syear));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    
    function get_gpa($admn_no,$syear,$sem){
        
         $sql = "select a.* from final_semwise_marks_foil a where a.admn_no=? and a.session_yr=? and a.semester=?";

        $query = $this->db->query($sql,array($admn_no,$syear,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    

}

?>