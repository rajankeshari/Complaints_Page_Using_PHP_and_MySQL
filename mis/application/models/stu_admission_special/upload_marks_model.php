<?php

Class Upload_marks_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_registration($syear, $sess, $admn_no, $sem,$et) {
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name from reg_other_form a 
inner join user_details b on a.admn_no=b.id            
where a.session_year=? and a.`session`=? and
a.admn_no=? and a.semester=? and a.hod_status='1' and a.acad_status='1' and a.`type`=?";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no, $sem,$et));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_registration_jrf($syear, $sess, $admn_no) {
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name from reg_exam_rc_form a 
inner join user_details b on a.admn_no=b.id            
where a.session_year=? and a.`session`=? and
a.admn_no=?  and a.hod_status='1' and a.acad_status='1'";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_subjects($form_id,$etype) {
        if($etype=='JRF'){$tbl='reg_exam_rc_subject';}
        if($etype=='Other' || $etype=='Special'){$tbl='reg_other_subject';}
        
        $sql = "select a.*,b.subject_id,b.name from ".$tbl." a 
inner join subjects b on a.sub_id=b.id
where a.form_id=?";

        $query = $this->db->query($sql, array($form_id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_course_structure_student($admn_no) {
        $sql = "select a.* from reg_regular_form a where a.admn_no=? and a.hod_status='1' and a.acad_status='1'
order by semester+0 desc limit 1;";

        $query = $this->db->query($sql, array($admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_subject_mapping($data){
        $sql = "select a.* from subject_mapping a where  a.`session`=? and a.session_year=? and a.dept_id=? and a.aggr_id=? and a.course_id=? and a.branch_id=? and a.semester=?;";

        $query = $this->db->query($sql, array($data['session'],$data['session_year'],$data['dept_id'],$data['aggr_id'],$data['course_id'],$data['branch_id'],$data['semester']));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    //=============================JRF====================================
    function check_subject_mapping_jrf($data){
        $sql = "SELECT a.* FROM subject_mapping a
inner join subject_mapping_des b on a.map_id=b.map_id
WHERE a.`session`=? AND a.session_year=? AND a.dept_id=?  AND a.course_id=? AND a.branch_id=? and b.sub_id=?";

        $query = $this->db->query($sql, array($data['session'],$data['session_year'],$data['dept_id'],$data['course_id'],$data['branch_id'],$data['sub_id']));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    
    
    
    //=====================================================================
    function check_marks_master($mapid,$subid,$sess,$syear,$et){
        $sql = "select a.* from marks_master a where a.sub_map_id=? and a.subject_id=?
and a.`session`=? and a.session_year=? and a.`type`=? and a.`status`='Y';";

        $query = $this->db->query($sql, array($mapid,$subid,$sess,$syear,$et));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function check_marks_master_description_for_stu($mm_id,$admn_no){
        $sql = "select a.* from marks_subject_description a where a.marks_master_id=? and a.admn_no=?";
        $query = $this->db->query($sql, array($mm_id,$admn_no));
        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function update_marks_master_description($dataup,$con)
    {
         if($this->db->update('marks_subject_description',$dataup,$con))
         {
                    return true;
         } 
            return false;
            
    }

}

?>