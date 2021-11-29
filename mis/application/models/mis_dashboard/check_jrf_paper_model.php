<?php

class Check_jrf_paper_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_paper_wise_student($selsyear,$selsess,$paper_code)
    {
          
        $sql = "select a.*,b.id,b.subject_id,b.name,d.semester,d.sequence,d.aggr_id,c.admn_no,c.session_year,c.`session`,e.dept_id from reg_exam_rc_subject a
inner join subjects b on b.id=a.sub_id
inner join reg_exam_rc_form c on c.form_id=a.form_id
inner join course_structure d on d.id=a.sub_id
inner join user_details e on e.id=c.admn_no
where c.session_year=? and c.`session`=? and  b.subject_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$paper_code));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_mapping_details($selsyear,$selsess,$paper_code){
            $sql = "select a.*,c.subject_id,c.name,d.semester,d.sequence,d.aggr_id,concat_ws(' ',e.first_name,e.middle_name,e.last_name)as faculty,
b.session_year,b.`session`,e.dept_id
 from subject_mapping_des a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
inner join user_details e on e.id=a.emp_no
where b.session_year=? and b.`session`=? and c.subject_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$paper_code));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    
 
    

}

?>