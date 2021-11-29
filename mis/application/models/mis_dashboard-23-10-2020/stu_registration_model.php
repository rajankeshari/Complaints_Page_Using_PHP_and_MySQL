<?php

class Stu_registration_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_stu_registration($tbl,$selsyear,$selsess,$admn_no)
    {
        if($admn_no!=""){
            $sql = "select * from ".$tbl."  where session_year=? and session=? and admn_no=?";
			$query = $this->db->query($sql,array($selsyear,$selsess,$admn_no));
         } else{
            $sql = "select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as sname,d.enrollment_year as year_of_admission,
b.category,b.physically_challenged,
b.dept_id,a.course_id,a.branch_id,a.semester,a.section
 from ".$tbl." a 	
inner join user_details b on b.id=a.admn_no
inner join users c on c.id=a.admn_no and c.`status`='A'
inner join stu_academic d on d.admn_no=a.admn_no
where a.session_year=? and a.session=?
and a.hod_status='1' and a.acad_status='1' order by b.dept_id,a.course_id,a.branch_id,a.semester,a.section";
        }
        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	function get_stu_registration_with_course($tbl,$selsyear,$selsess)
    {
        
            $sql = "select t.* from (

(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS sname,d.enrollment_year AS year_of_admission, b.category,b.physically_challenged, 
b.dept_id,a.course_id,a.branch_id,a.semester,a.section,e.subject_code,e.subject_name,e.sub_category,f.sub_type
from ".$tbl." a 	
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN users c ON c.id=a.admn_no AND c.`status`='A'
INNER JOIN stu_academic d ON d.admn_no=a.admn_no
inner join cbcs_stu_course e on e.form_id=a.form_id and e.admn_no=a.admn_no
left join cbcs_subject_offered f on f.id=e.sub_offered_id and f.session_year=a.session_year and f.`session`=a.`session`
WHERE a.session_year=? AND a.session=? AND a.hod_status='1' AND a.acad_status='1'
)
union(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS sname,d.enrollment_year AS year_of_admission, b.category,b.physically_challenged, 
b.dept_id,a.course_id,a.branch_id,a.semester,a.section,e.subject_code,e.subject_name,e.sub_category,f.sub_type
from ".$tbl." a 	
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN users c ON c.id=a.admn_no AND c.`status`='A'
INNER JOIN stu_academic d ON d.admn_no=a.admn_no
inner join old_stu_course e on e.form_id=a.form_id and e.admn_no=a.admn_no
left join old_subject_offered f on f.id=e.sub_offered_id and f.session_year=a.session_year and f.`session`=a.`session`
WHERE a.session_year=? AND a.session=? AND a.hod_status='1' AND a.acad_status='1'
)
)t order by t.admn_no,t.subject_code";
        
        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$selsyear,$selsess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	
	
	

    function get_subjects($form_id,$tbl_subj)
    {
        $sql = "select a.*,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from ".$tbl_subj." a
inner join subjects b on b.id=a.sub_id left join course_structure c on c.id=a.sub_id where a.form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    function get_details_by_formID($form_id){
        $sql = "select * from reg_regular_form where form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_honour_minor($admn_no){
        $sql = "select a.* from hm_form a where a.admn_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }


    function get_subjects_cs($cid,$sem,$sec=null){

        $caggrid=explode('_', $cid);
        $tsem=$sem.'_'.$sec;
        if($caggrid[0]=="comm"){
            $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and  c.sequence not like '%.%' order by c.sequence+0";
$query = $this->db->query($sql,array($cid,$tsem));

        }else{

        $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and c.sequence not like '%.%' order by c.sequence+0";

$query = $this->db->query($sql,array($cid,$sem));
}

       // echo $sql;die();
       

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

function get_minor($admn_no){
        $sql = "select b.minor_agg_id from hm_form a 
inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and b.offered='1'";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    

 
    

}

?>