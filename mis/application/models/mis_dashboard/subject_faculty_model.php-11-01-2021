<?php

class Subject_faculty_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_subject_faculty_codewise($selsyear,$selsess,$sub_code)
    {
          
        $sql = "SELECT a.*,c.subject_id,c.name,d.semester,d.sequence,d.aggr_id, CONCAT_WS(' ',e.first_name,e.middle_name,e.last_name) AS faculty, b.session_year,b.`session`,e.dept_id,
b.course_id,b.branch_id,b.section
FROM subject_mapping_des a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
INNER JOIN user_details e ON e.id=a.emp_no
WHERE b.session_year=? AND b.`session`=? AND c.subject_id=? ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$sub_code));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_subject_faculty_idwise($selsyear,$selsess,$sub_id)
    {
          
        $sql = "
SELECT a.*,c.subject_id,c.name,d.semester,d.sequence,d.aggr_id, CONCAT_WS(' ',e.first_name,e.middle_name,e.last_name) AS faculty, b.session_year,b.`session`,e.dept_id,
b.course_id,b.branch_id,b.section
FROM subject_mapping_des a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
INNER JOIN user_details e ON e.id=a.emp_no
WHERE b.session_year=? AND b.`session`=?  and a.sub_id=? ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$sub_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    

 
    

}

?>