<?php

class Student_elective_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_registration($admn_no) {
		
		//hod status and acad status added by anuj on 12-11-2018

        $sql = "select * from reg_regular_form where admn_no=? and hod_status<>'2' and acad_status<>'2'";

        $query = $this->db->query($sql, array($admn_no));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_elective_papers($form_id) {
        $sql = "select a.*,b.subject_id,b.name from reg_regular_elective_opted a 
inner join subjects b on b.id=a.sub_id
where a.form_id=?";

        $query = $this->db->query($sql, array($form_id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function passout_year($id) {
        $sql = "select a.passout_year from stu_enroll_passout a where a.admn_no=?";
        $query = $this->db->query($sql, array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_offered_elective($syear, $sess, $aggrid, $batch) {
        $sql = "select a.*,b.subject_id,b.name,c.sequence from optional_offered a
            inner join subjects b on b.id=a.id
			inner join course_structure c on c.id=a.id
where a.session_year=? and a.`session`=?
and a.aggr_id=? and batch=?;";

        $query = $this->db->query($sql, array($syear, $sess, $aggrid, $batch));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_sequence_no($id) {
        $sql = "SELECT sequence FROM course_structure WHERE id=?";

        $query = $this->db->query($sql, array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function insert_data($data) {
        if ($this->db->insert('reg_regular_elective_opted', $data))
            return TRUE;
        else
            return FALSE;
    }

    function check_subject_availability($form_id, $sub_seq, $sub_id) {
        $sql = "select * from reg_regular_elective_opted where form_id=? and sub_seq=? and sub_id=?";
        $query = $this->db->query($sql, array($form_id, $sub_seq, $sub_id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function get_core_syllabus($aggrid,$sem,$sec,$formid){
        
        if($sec!=0){$semester=$sem.'_'.$sec;}else{$semester=$sem;}
        
        
        $sql = "(select b.subject_id,b.name,c.aggr_id from course_structure a 
inner join subjects b on b.id=a.id
inner join course_structure c on c.id=b.id
where a.aggr_id=? and a.semester=?
and a.sequence not like '%.%' order by a.sequence+0)
union
(select b.subject_id,b.name,c.aggr_id from reg_regular_elective_opted a 
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=b.id
where a.form_id=?)";

        $query = $this->db->query($sql, array($aggrid,$semester,$formid));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
//================Honours====================================================================    
    function get_honours_syllabus($admn_no,$sem){
        $hstatus=$this->get_honours_status($admn_no);
        if(!empty($hstatus)){
            $sql = "(select b.subject_id,b.name,c.aggr_id from course_structure a 
inner join subjects b on b.id=a.id
inner join course_structure c on c.id=b.id
where a.aggr_id=? and a.semester=?
and a.sequence not like '%.%' order by a.sequence+0)";

        $query = $this->db->query($sql, array($hstatus->honours_agg_id,$sem));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
            
        }else{
            return false;
        }
        
        
    }
    function get_honours_status($admn_no){
        $sql = "select a.* from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y' group by a.admn_no";
        $query = $this->db->query($sql, array($admn_no));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    //================Honours Ends=================================================================
    //================Minor Starts====================================================================    
    function get_minor_syllabus($admn_no,$sem){
        $mstatus=$this->get_minor_status($admn_no);
        if(!empty($mstatus)){
            $mcs=$this->get_minor_cs($mstatus->form_id);
            $sql = "(select b.subject_id,b.name,c.aggr_id from course_structure a 
inner join subjects b on b.id=a.id
inner join course_structure c on c.id=b.id
where a.aggr_id=? and a.semester=?
and a.sequence not like '%.%' order by a.sequence+0)";

        $query = $this->db->query($sql, array($mcs->minor_agg_id,$sem));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
            
        }else{
            return false;
        }
        
        
    }
    function get_minor_status($admn_no){
        $sql = "select a.* from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y' group by a.admn_no";
        $query = $this->db->query($sql, array($admn_no));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_minor_cs($formid){
        $sql = "select * from hm_minor_details where form_id=? and offered='1'";
        $query = $this->db->query($sql, array($formid));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    //================Minor Ends=================================================================
}

?>