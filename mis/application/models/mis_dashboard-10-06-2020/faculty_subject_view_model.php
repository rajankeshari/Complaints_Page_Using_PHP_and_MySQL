<?php

class Faculty_subject_view_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_faculty_subjects_emp($selsyear,$selsess,$emp_no)
    {
          
        $sql = "select b.map_id,b.emp_no,b.coordinator,b.sub_id,
a.`session`,a.session_year,a.dept_id,a.aggr_id,a.course_id,a.branch_id,a.semester,a.section,
c.subject_id,c.name,concat_ws(' ',d.salutation,d.first_name,d.middle_name,d.last_name)as fname from subject_mapping a
inner join subject_mapping_des b on b.map_id=a.map_id
inner join subjects c on c.id=b.sub_id
inner join user_details d on d.id=b.emp_no
where a.session_year=? and a.`session`=? and b.emp_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$emp_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_faculty_subjects_name($selsyear,$selsess,$fname)
    {
          
        $sql = "select x.* from(select b.map_id,b.emp_no,b.coordinator,b.sub_id,
a.`session`,a.session_year,a.dept_id,a.aggr_id,a.course_id,a.branch_id,a.semester,a.section,
c.subject_id,c.name,concat_ws(' ',d.salutation,d.first_name,d.middle_name,d.last_name)as fname from subject_mapping a
inner join subject_mapping_des b on b.map_id=a.map_id
inner join subjects c on c.id=b.sub_id
inner join user_details d on d.id=b.emp_no
where a.session_year=? and a.`session`=?
)x
where x.fname like ? ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,'%'.$fname.'%'));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

 
    

}

?>