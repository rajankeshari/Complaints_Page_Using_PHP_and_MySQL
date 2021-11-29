<?php

class Common_student_subject_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get_common_student($syear, $sess, $group){
        
      
               $sql="select c.id,c.subject_id as sub_code,c.name
from subject_mapping a
inner join subject_mapping_des b on a.map_id=b.map_id
inner join subjects c on c.id=b.sub_id
inner join user_details d on d.id=b.emp_no
inner join departments e on e.id=a.dept_id
INNER JOIN user_other_details uod ON uod.id=d.id
where a.session_year=? and a.`session`=?
and a.dept_id='comm' and a.course_id='comm' and a.branch_id='comm' and section=? and b.coordinator='1'
order by c.subject_id

";
               $query = $this->db->query($sql, array($syear, $sess, $group)); 
          //  echo $this->db->last_query();die();
    if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }

       
    }
    
    function get_sub_count($syear,$sess,$subid,$gd){
        $sql="select b.admn_no,b.mis_sub_id,b.grade,count(mis_sub_id)as nos from final_semwise_marks_foil a
inner join final_semwise_marks_foil_desc b on b.foil_id=a.id
where a.session_yr=? and a.`session`=? and a.dept='comm'
and b.mis_sub_id=? and b.grade=? group by b.grade";
               $query = $this->db->query($sql, array($syear,$sess,$subid,$gd)); 
        //echo $this->db->last_query();die();    
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
        
    }
    
    
    
    
    function get_department($id){
        $sql="select name from departments where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    function get_course($id){
        $sql="select name from cs_courses where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    function get_branch($id){
        $sql="select name from cs_branches where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    
    
}

?>