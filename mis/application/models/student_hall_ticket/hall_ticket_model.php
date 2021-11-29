<?php

class Hall_ticket_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function check_reg_status($admm_no,$syear,$sess){
        
        $sql = "select a.* from reg_regular_form a where a.admn_no=?  and a.session_year=? and a.`session`=?
                ORDER BY timestamp DESC    LIMIT 1;";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }

    function check_other_status($admm_no,$syear,$sess,$et){
        
        $sql = "select A.* from (
(select a.* from reg_other_form a
where a.admn_no=? and  a.session_year=? and a.`session`=? and a.`type`=? /*and a.hod_status<>'2' and a.acad_status<>'2'*/
ORDER BY timestamp DESC    LIMIT 1)
union
(select a.* from reg_exam_rc_form a
where a.admn_no=? and a.session_year=? and a.`session`=? and a.`type`=? /*and a.hod_status<>'2' and a.acad_status<>'2'*/
ORDER BY timestamp DESC    LIMIT 1)
)A group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session order by TIMESTAMP DESC limit 1";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess,$et,$admm_no,$syear,$sess,$et));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }
    
    function student_personal_details($admm_no){
        
        $sql = "select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name) as stu_name,c.name as dname,
d.name as cname,e.name as bname,a.photopath from user_details a
inner join stu_academic b on b.admn_no=a.id
inner join departments c on a.dept_id=c.id
inner join cs_courses  d on d.id=b.course_id
left join cs_branches e on e.id=b.branch_id
where a.id=?";

        $query = $this->db->query($sql,array($admm_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }
    function student_personal_details_jrf($admm_no){
        
        $sql = "select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name) as stu_name,c.name as dname,
'N/A' as cname,'N/A' as bname,a.photopath from user_details a
inner join stu_academic b on b.admn_no=a.id
inner join departments c on a.dept_id=c.id
where a.id=?";

        $query = $this->db->query($sql,array($admm_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }
	
	function get_stu_auth($admm_no){
		
		$sql = "select auth_id from stu_academic where admn_no=?";

        $query = $this->db->query($sql,array($admm_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    function get_student($admm_no,$syear,$sess)
    {
          
        $sql = "select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,a.semester,a.session_year,a.`session`,c.name as dname,
d.name as cname,e.name as bname,b.photopath
 from reg_regular_form a  
inner join user_details b on a.admn_no=b.id
inner join departments c on b.dept_id=c.id
inner join cs_courses  d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.admn_no=?
and a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1'";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess));

       echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function get_student_other($admm_no,$syear,$sess){
        
        $sql = "(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,a.semester,a.session_year,a.`session`,c.name AS dname, d.name AS cname,e.name AS bname,b.photopath
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN departments c ON b.dept_id=c.id
INNER JOIN cs_courses d ON d.id=a.course_id
INNER JOIN cs_branches e ON e.id=a.branch_id
WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND a.hod_status='1' AND a.acad_status='1'
)union
(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,a.semester,a.session_year,a.`session`,c.name AS dname, d.name AS cname,e.name AS bname,b.photopath
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN departments c ON b.dept_id=c.id
INNER JOIN cs_courses d ON d.id=a.course_id
INNER JOIN cs_branches e ON e.id=a.branch_id
WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND a.hod_status='1' AND a.acad_status='1'
)";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function get_student_core_subject($admm_no,$sem,$syear,$sess){
        
        $sql = "select a.subject_id,a.name,b.sequence from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.course_aggr_id from reg_regular_form a  where a.admn_no=? and 
a.semester=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1') and b.semester=?
and b.sequence not like '%.%'";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_student_elec_subject($admm_no,$sem,$syear,$sess){
        
        $sql = "select s.subject_id,s.name,b.sub_seq as sequence from reg_regular_elective_opted b
inner join subjects s on s.id=b.sub_id
where form_id= (select a.form_id from reg_regular_form a  where a.admn_no=? and  a.semester=?
and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1' )";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_student_hons_subject($admm_no,$sem){
        
        $sql = "select a.subject_id,a.name,b.sequence from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.honours_agg_id from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y')
and b.semester=?";

        $query = $this->db->query($sql,array($admm_no,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_student_minor_subject($admm_no,$sem){
        
        $sql = "select a.subject_id,a.name,b.sequence from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(
select m.minor_agg_id from hm_minor_details m where 
m.form_id=(select a.form_id from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y')
and m.offered='1')
and b.semester=?";

        $query = $this->db->query($sql,array($admm_no,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_student_all_subject($admm_no,$sem,$syear,$sess)
    {
        
        $sql = "select x.* from((select a.subject_id,a.name,'Core' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.course_aggr_id from reg_regular_form a  where a.admn_no=? and 
a.semester=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1') and b.semester=?
and b.sequence not like '%.%')
union
(
select d.subject_id,d.name,
CASE WHEN c.aggr_id like '%honour%' THEN 'Elective(Honour)' when c.aggr_id  like '%minor%' THEN 'Elective(Minor)'  WHEN c.aggr_id NOT LIKE '%honour%' THEN 'Elective'  else 'Elective' END AS paper_type,d.id as sub_id,c.aggr_id
from reg_regular_elective_opted a
inner join reg_regular_form b on a.form_id=b.form_id
inner join course_structure c on c.id=a.sub_id
inner join subjects d on d.id=a.sub_id
where b.admn_no=? and  b.semester=?
and b.session_year=? and b.`session`=?
and b.hod_status='1' and b.acad_status='1') 
union
(
select a.subject_id,a.name,'Honours' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.honours_agg_id from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y' limit 1)
and b.semester=? and b.sequence not like '%.%'
)
union
(
select a.subject_id,a.name,'Minor' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id in (
select m.minor_agg_id from hm_minor_details m where 
m.form_id=(select a.form_id from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y')
and m.offered='1')
and b.semester=?))x
group by x.sub_id
";
$query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess,$sem,$admm_no,$sem,$syear,$sess,$admm_no,$sem,$admm_no,$sem));  
        
        

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_student_semester($admm_no,$syear,$sess){
        
        $sql = "select a.semester from reg_regular_form a where a.admn_no=?
and a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1'";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->semester;
        } else {
            return false;
        }
        
    }
    //------------------------Regular----------------------------------------------------------------
    function get_approval_status_hod($admm_no,$sem,$syear,$sess){
        
        $sql = "select a.hod_status from reg_regular_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1;";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->hod_status;
        } else {
            return false;
        }
        
    }
    
    function get_approval_status_acad($admm_no,$sem,$syear,$sess){
        
        $sql = "select a.acad_status from reg_regular_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1;";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->acad_status;
        } else {
            return false;
        }
        
    }
   //------------------------------------------------------------------------------------------------------------
    
   //------------------------Others----------------------------------------------------------------
    function get_approval_status_hod_other($admm_no,$sem,$syear,$sess){
        
        $sql = "select A.hod_status from (
(select a.* from reg_other_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1)
union
(select a.* from reg_exam_rc_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1)
)A group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session;";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess,$admm_no,$sem,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->hod_status;
        } else {
            return false;
        }
        
    }
    
    function get_approval_status_acad_other($admm_no,$sem,$syear,$sess){
        
        $sql = "select A.acad_status from(
(select a.* from reg_other_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1)
union
(select a.* from reg_exam_rc_form a
where a.admn_no=? and a.semester=? and a.session_year=? and a.`session`=?
ORDER BY timestamp DESC    LIMIT 1)
)A group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session";

        $query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess,$admm_no,$sem,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->acad_status;
        } else {
            return false;
        }
        
    }
   //------------------------------------------------------------------------------------------------------------
    
    function get_student_all_subject_other($admm_no,$sem,$syear,$sess,$et){
        
         $sql = "select s.subject_id,s.name,'Other' as 'paper_type' from reg_other_subject b
inner join subjects s on s.id=b.sub_id
inner join course_structure c on c.id=b.sub_id
where form_id= (
select A.form_id from
(select a.* from reg_other_form a where a.admn_no=? and a.semester like '%?%' and a.session_year=? and a.`session`=? and a.type=? and a.hod_status='1' and a.acad_status='1'
union
select a.* from reg_exam_rc_form a where a.admn_no=? and a.semester like '%?%' and a.session_year=? and a.`session`=? and a.type=? and a.hod_status='1' and a.acad_status='1'
)A
group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session
) ";

        $query = $this->db->query($sql,array($admm_no,(int)$sem,$syear,$sess,$et,$admm_no,(int)$sem,$syear,$sess,$et));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function get_student_all_subject_other_again($admm_no,$sem,$syear,$sess,$et){
        
         $sql = "select s.subject_id,s.name,'Other' as 'paper_type' from reg_exam_rc_subject b
inner join subjects s on s.id=b.sub_id
inner join course_structure c on c.id=b.sub_id
where form_id= (
select A.form_id from
(select a.* from reg_other_form a where a.admn_no=? and a.semester like '%?%' and a.session_year=? and a.`session`=? and a.type=? and a.hod_status='1' and a.acad_status='1'
union
select a.* from reg_exam_rc_form a where a.admn_no=? and a.semester like '%?%' and a.session_year=? and a.`session`=? and a.type=? and a.hod_status='1' and a.acad_status='1'
)A
group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session
) and c.semester like ?";

        $query = $this->db->query($sql,array($admm_no,(int)$sem,$syear,$sess,$et,$admm_no,(int)$sem,$syear,$sess,$et,(int)$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function get_student_semester_other($admm_no,$syear,$sess){
        
        $sql = "select A.semester,A.course_id from
(select a.* from reg_other_form a where a.admn_no=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1'
union
select a.* from reg_exam_rc_form a where a.admn_no=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1'
)A
group by A.admn_no,A.course_id,A.branch_id,A.semester,A.session_year,A.session";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess,$admm_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }
    
    function get_student_all_subject_jrf($admm_no,$syear,$sess,$et){
        
         $sql = "select s.subject_id,s.name,'JRF' as 'paper_type' from reg_exam_rc_subject b
inner join subjects s on s.id=b.sub_id
where form_id= (
select a.form_id from reg_exam_rc_form a where a.admn_no=? and a.session_year=? and a.`session`=? and a.type=? and a.hod_status='1' and a.acad_status='1'
)";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess,$et));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_student_all_subject_common($admm_no, $sem,$sec, $syear, $sess){
         
        $sem_sec=$sem.'_'.$sec;
        $sql = "select a.subject_id,a.name,'Core' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.course_aggr_id from reg_regular_form a  where a.admn_no=? and 
a.semester=? and a.section=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1') and b.semester='".$sem_sec."'
and b.sequence not like '%.%'
union
(
select d.subject_id,d.name,
CASE WHEN c.aggr_id like '%honour%' THEN 'Elective(Honour)' when c.aggr_id  like '%minor%' THEN 'Elective(Minor)'  WHEN c.aggr_id NOT LIKE '%honour%' THEN 'Elective'  else 'Elective' END AS paper_type,d.id as sub_id,c.aggr_id
from reg_regular_elective_opted a
inner join reg_regular_form b on a.form_id=b.form_id
inner join course_structure c on c.id=a.sub_id
inner join subjects d on d.id=a.sub_id
where b.admn_no=? and  b.semester=? and b.section=?
and b.session_year=? and b.`session`=?
and b.hod_status='1' and b.acad_status='1')
";

        $query = $this->db->query($sql,array($admm_no, $sem,$sec, $syear, $sess,$admm_no, $sem,$sec, $syear, $sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    //-----------------------------------------------------Minor pass fail-------------------
    
  /*  function check_minor_pass_fail($admn_no,$sem) {
	$lst='';
	for($i=$sem;$i>=5;$i--){
		$lst.=$i.($i==5?"":",") ;
	}
	 //echo  $lst ; die();
	 if (substr_count($lst, ',') > 0) { 
	      $s_replace =" and a.semester in (".$lst.")";
     }else
		 $s_replace ="  and a.semester ='".$lst."' ";
       //echo  $s_replace .'<br/>' ;
       
	   $sql = "SELECT SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC(Minor)'), NULL) SEPARATOR ', ') AS incstr
FROM (

select z.* from(
			(
			SELECT B.*
			FROM (
			SELECT a.status AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.`session`,a.course
			FROM final_semwise_marks_foil a
			WHERE a.admn_no=? and  a.course='MINOR' ".$s_replace ."
			GROUP BY a.session_yr,a.`session`,a.semester,a.exam_type
			ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC)B
			GROUP BY B.sem) 
			
			)z group by z.sem )x
         
         ";



        $query = $this->db->query($sql, array($admn_no));
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->row();
        else {
            return 0;
        }
    }
    
    */
    //------------------------------------------------------------------------------------

    
        //============================Summer==============================
    function check_reg_status_summer($admm_no,$syear,$sess){
        
        $sql = "select a.* from reg_summer_form a where a.admn_no=?  and a.session_year=? and a.`session`=?
                ORDER BY timestamp DESC    LIMIT 1;";

        $query = $this->db->query($sql,array($admm_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }
    
    function get_student_all_subject_summer($syear, $sess,$admm_no){
        
        $sql = "select b.sub_id,c.subject_id,c.name,d.semester,d.aggr_id,
CASE 
      WHEN d.aggr_id like '%honour%' THEN 'Honour'
      WHEN d.aggr_id like '%minor%' THEN 'Minor'
      WHEN c.elective=0  THEN 'Core'
      ELSE 'Elective'
END AS paper_type
from reg_summer_form a
inner join reg_summer_subject b on a.form_id=b.form_id
inner join subjects c on c.id=b.sub_id
inner join course_structure d on d.id=b.sub_id
where a.session_year=? and a.`session`=?
and a.admn_no=? and a.hod_status='1' and a.acad_status='1'

";

        $query = $this->db->query($sql,array($syear, $sess,$admm_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();;
        } else {
            return false;
        }
        
        
    }
    
    
    //===================================================================
    
    
    function get_regular_status($syear, $sess,$admm_no){
        
        $sql = "select a.*,b.dept_id from reg_regular_form a
inner join user_details b on a.admn_no=b.id
where a.session_year=? and a.`session`=?
and a.admn_no=? and a.hod_status='1' and a.acad_status='1'; ";

        $query = $this->db->query($sql,array($syear, $sess,$admm_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();;
        } else {
            return false;
        }
        
    }
    function get_mapping_status($syear,$sess,$aggrid,$did,$cid,$bid,$sem,$group,$section){
        $sql = "select a.* from subject_mapping a where a.session_year=? and a.`session`=?
and a.aggr_id=?  and a.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=? and a.`group`=? and a.section=?";

        $query = $this->db->query($sql,array($syear,$sess,$aggrid,$did,$cid,$bid,$sem,$group,$section));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();;
        } else {
            return false;
        }
    }
    
    function get_defaulter_status($mapid,$subid,$admn_no){
        $sql="select a.* from absent_table a where  status='2' and a.map_id=? and a.sub_id=? and a.admn_no=? ";
        $query = $this->db->query($sql,array($mapid,$subid,$admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();;
        } else {
            return false;
        }
    }
    
    function get_section($admn_no,$syear){
        $sql=" select a.section from stu_section_data a where a.admn_no=? and a.session_year=? ";
        $query = $this->db->query($sql,array($admn_no,$syear));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->section;;
        } else {
            return false;
        }
        
    }
    function get_minor_dept_course_branch($aid){
       
        $sql="select a.dept_id,b.course_id,b.branch_id from dept_course a 
inner join course_branch b on a.course_branch_id=b.course_branch_id
where a.aggr_id=? ";
        $query = $this->db->query($sql,array($aid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	function get_student_all_subject_cbcs($admm_no, $form_id){
		
		/*$sql="(select a.subject_code,a.subject_name,b.sub_type,CONCAT('c',a.sub_offered_id) AS sub_offered_id from cbcs_stu_course a
inner join cbcs_subject_offered b on b.id=a.sub_offered_id
 where admn_no=? and form_id=?)
 union
 (select a.subject_code,a.subject_name,b.sub_type,CONCAT('o',a.sub_offered_id) AS sub_offered_id from old_stu_course a
inner join old_subject_offered b on b.id=a.sub_offered_id
 where admn_no=? and form_id=?) ";
        $query = $this->db->query($sql,array($admm_no, $form_id,$admm_no, $form_id));*/
		
		$sql=" SELECT p.*,
CASE
    WHEN q.def_status='y' THEN 'Defaulter'
    ELSE ''
END AS status

 FROM (
(
SELECT a.subject_code,a.subject_name,b.sub_type, CONCAT('c',a.sub_offered_id) AS sub_offered_id
FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
WHERE admn_no=? AND form_id=?) UNION (
SELECT a.subject_code,a.subject_name,b.sub_type, CONCAT('o',a.sub_offered_id) AS sub_offered_id
FROM old_stu_course a
INNER JOIN old_subject_offered b ON b.id=a.sub_offered_id
WHERE admn_no=? AND form_id=?)
)p
left JOIN cbcs_absent_table_defaulter q ON p.sub_offered_id=q.sub_offered_id AND q.admn_no=? ";

$query = $this->db->query($sql,array($admm_no, $form_id,$admm_no, $form_id,$admm_no));
       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
	}
    
    
}

?>