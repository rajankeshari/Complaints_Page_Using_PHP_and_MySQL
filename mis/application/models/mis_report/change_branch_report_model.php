<?php

class change_branch_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student($syear)
    {
       
          
        $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.category,c.iit_jee_cat_rank from change_branch_log a 
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=a.admn_no
where a.session_year=? order by b.id";

        $query = $this->db->query($sql,array($syear));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_choice($id){
         $sql = "select a.* from change_branch_option a where a.cb_log_id=?";

        $query = $this->db->query($sql,array($id));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    function get_gpa($admn_no,$syear,$sem){
        $sy=  explode('-', $syear);
        //print_r($sy);
        $sy1=($sy[0]-1).'-'.($sy[1]-1);
         $sql = "select a.* from final_semwise_marks_foil a where a.admn_no=? and a.session_yr=? and a.semester=?";

        $query = $this->db->query($sql,array($admn_no,$sy1,$sem));

       echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	//New for CGPA
	
	function get_cgpa($admn_no,$syear,$sem){
        $sy=  explode('-', $syear);
        //print_r($sy);
        $sy1=($sy[0]-1).'-'.($sy[1]-1);
         $sql = "SELECT x.*
FROM (
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id AS foil_id,a.`status`, a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts,  a.published_on, a.actual_published_on,a.cgpa,a.core_cgpa
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no=? and a.session_yr=? AND UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') AND a.course<>'jrf' and a.semester=?
ORDER BY a.admn_no,a.semester,a.actual_published_on DESC
LIMIT 100000000)x
GROUP BY x.admn_no, x.semester";

        $query = $this->db->query($sql,array($admn_no,$sy1,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	function check_passfail($foilid){
        
         $sql = "select a.* from 
(select a.* from final_semwise_marks_foil_desc_freezed a where a.foil_id=? 
order by  a.sub_code,a.grade limit 1000)a  group by a.sub_code  having  (a.grade='F' || a.grade='I')";

        $query = $this->db->query($sql,array($foilid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    

}

?>