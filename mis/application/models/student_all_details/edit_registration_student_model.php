<?php

Class edit_registration_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //=====================================Summer=========================================

    function get_summer_registraion_form_details($syear, $sess, $admn_no) {
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,c.name dname,d.name as cname,e.name as bname from reg_summer_form a inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status<>'2' and a.acad_status<>'2'";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_summer_fee_details($fid) {
        $sql = "select a.* from reg_summer_fee a where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_summer_sub_details($fid) {
        $sql = "select a.*,b.subject_id as subject_code,b.name as sub_name,c.semester,c.aggr_id from reg_summer_subject a 
inner join subjects b on a.sub_id=b.id
inner join course_structure c on c.id=a.sub_id
where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //=====================================Regular=========================================


    function get_regular_registraion_form_details($syear, $sess, $admn_no) {
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,c.name dname,d.name as cname,e.name as bname from reg_regular_form a inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status<>'2' and a.acad_status<>'2'";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_regular_fee_details($fid) {
        $sql = "select a.* from reg_regular_fee a where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_regular_sub_details($fid) {
        $sql = "select a.*,b.subject_id as subject_code,b.name as sub_name,c.semester,c.aggr_id from reg_summer_subject a 
inner join subjects b on a.sub_id=b.id
inner join course_structure c on c.id=a.sub_id
where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //===========================================OHTER====================================
    function get_other_registraion_form_details($syear, $sess, $admn_no, $et) {
        if ($et == 'Other') {
            $et = 'R';
        }
        if ($et == 'Special') {
            $et = 'S';
        }
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,c.name dname,d.name as cname,e.name as bname from reg_other_form a inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status<>'2' and a.acad_status<>'2' and a.type=?";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no, $et));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_other_fee_details($fid) {
        $sql = "select a.* from reg_other_fee a where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_other_sub_details($fid) {
        $sql = "select a.*,b.subject_id as subject_code,b.name as sub_name,c.semester,c.aggr_id from reg_other_subject a 
inner join subjects b on a.sub_id=b.id
inner join course_structure c on c.id=a.sub_id
where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //==========================================JRF==================================================

    function get_exam_registraion_form_details($syear, $sess, $admn_no, $et) {
        if ($et == 'JRF') {
            $et = 'R';
        }
        if ($et == 'JRF_SPL') {
            $et = 'S';
        }
        $sql = "select a.*,b.dept_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,c.name dname,d.name as cname,e.name as bname from reg_exam_rc_form a inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status<>'2' and a.acad_status<>'2' and a.type=?";

        $query = $this->db->query($sql, array($syear, $sess, $admn_no, $et));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_exam_fee_details($fid) {
        $sql = "select a.* from reg_exam_rc_fee a where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_exam_sub_details($fid) {
        $sql = "select a.*,b.subject_id as subject_code,b.name as sub_name,c.semester,c.aggr_id from reg_exam_rc_subject a 
inner join subjects b on a.sub_id=b.id
left join course_structure c on c.id=a.sub_id
where a.form_id=?";

        $query = $this->db->query($sql, array($fid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //=============================================To Edit==================================

    public function show_regular_table_record($data) {
        
  $sql = "select a.* from reg_regular_form a where a.session_year=? and a.`session`=?
and a.admn_no=? and a.form_id=? and a.semester=? and a.hod_status<>'2' and a.acad_status<>'2'";

        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['admn_no'],$data['form_id'],$data['semester']));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function show_exam_table_record($data) {
        if($data['exam_type']=='JRF'){$t='R';}
        if($data['exam_type']=='JRF_SPL'){$t='S';}
  $sql = "select a.* from reg_exam_rc_form a where a.session_year=? and a.`session`=?
and a.admn_no=? and a.form_id=? and a.hod_status<>'2' and a.acad_status<>'2' and a.`type`=?";

        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['admn_no'],$data['form_id'],$t));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function show_regular_table_fee($form_id,$admn_no){
        
        $sql = "select a.* from reg_regular_fee a where a.form_id=? and a.admn_no=?";

        $query = $this->db->query($sql, array($form_id,$admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function show_exam_table_fee($form_id,$admn_no){
        
        $sql = "select a.* from reg_exam_rc_fee a where a.form_id=? and a.admn_no=?";

        $query = $this->db->query($sql, array($form_id,$admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function update_exam_table_registration($field_id,$field_value,$admn_no,$form_id){
        
        $sql = "update reg_exam_rc_form set ".$field_id."=? where admn_no=? and form_id=?";

        $query = $this->db->query($sql, array($field_value,$admn_no,$form_id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
        
    }

}

?>