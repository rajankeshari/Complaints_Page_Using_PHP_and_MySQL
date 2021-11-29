<?php

class Add_elective_semwise_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_student_details($selsyear,$selsess,$admn_no,$semester)
    {
          
        $sql = "select a.* from reg_regular_form a where a.session_year=? and a.`session`=?
and a.admn_no=? and a.semester=? /*and a.hod_status='1' and a.acad_status='1'*/";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$admn_no,$semester));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_elective_subjects($aggr_id,$syear,$sess,$sem){

        $sql = "select a.*,b.subject_id,b.name,c.semester,c.sequence from optional_offered a
inner join subjects b on b.id=a.id
inner join course_structure c on c.id=b.id
where a.aggr_id=?  and a.session_year=? and a.`session`=? and c.semester=? and a.batch<>'0'";

        //echo $sql;die();
        $query = $this->db->query($sql,array($aggr_id,$syear,$sess,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    function add_elective_paper($data){

        if($this->db->insert('reg_regular_elective_opted',$data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function get_stu_elective($form_id){
        $sql = "select a.*,b.subject_id,b.name from reg_regular_elective_opted a 
inner join subjects b on b.id=a.sub_id
where a.form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    

 
    

}

?>