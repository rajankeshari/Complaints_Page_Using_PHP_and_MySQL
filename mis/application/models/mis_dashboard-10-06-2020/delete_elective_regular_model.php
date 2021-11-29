<?php

class Delete_elective_regular_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_details($selsyear,$selsess,$admn_no)
    {
          
        $sql = "select b.*,c.subject_id,c.name,concat_ws(' ',d.first_name,d.middle_name,d.last_name)as      sname,e.course_id,e.branch_id,a.admn_no,a.semester from reg_regular_form a 
                inner join reg_regular_elective_opted b on b.form_id=a.form_id
                inner join subjects c on c.id=b.sub_id
                inner join user_details d on d.id=a.admn_no
                inner join stu_academic e on e.admn_no=a.admn_no
                where a.session_year=? and a.`session`=? and a.admn_no=?
                and a.hod_status='1' and a.acad_status='1'";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function delete_elective_paper($formid,$subid){
        $sql = "delete from reg_regular_elective_opted where form_id=? and sub_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($formid,$subid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }
    

}

?>