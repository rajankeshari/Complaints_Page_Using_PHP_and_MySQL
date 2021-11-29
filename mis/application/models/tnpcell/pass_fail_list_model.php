<?php

class Pass_fail_list_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_fail_list_final_foil($syear,$did,$cid,$bid)
    {
        
        if($did=='none' && $cid=='none' && $bid='none'){
            $where="";
        }
        else{
            $where=" where B.dept=? and B.course=? and B.branch=?";
        }
        $sql = "select B.*,x.name as dname,y.name as cname,z.name as bname from(
select A.dept,A.course,A.branch,A.admn_no,A.stu_name,group_concat(A.mis_sub_id separator ', ') as mis_sub_id,
group_concat(A.sub_code separator ', ') as sub_code,
group_concat(A.session separator ', ') as session,
group_concat(A.semester separator ', ') as semester,
group_concat(A.name separator ', ') as name
from(
select a.admn_no,a.mis_sub_id,a.sub_code,a.grade,b.session_yr,b.`session`,b.semester,c.subject_id,c.name,
b.dept,b.course,b.branch,concat_ws(' ',d.first_name,d.middle_name,d.last_name) as stu_name from final_semwise_marks_foil_desc a
inner join final_semwise_marks_foil b on b.id=a.foil_id
inner join subjects c on c.id=a.mis_sub_id
inner join user_details d on d.id=a.admn_no
where a.grade='F' AND b.session_yr=?
and b.course<>'MINOR'
order by b.`session`,b.dept,b.course,b.branch,b.semester/*,a.admn_no*/)A
group by A.admn_no)B
left join departments x on x.id=B.dept
left join cs_courses y on y.id=B.course
left join cs_branches z on z.id=B.branch
".$where."
order by B.dept,B.course,B.branch,B.semester,B.admn_no";

       // echo $sql;die();
        $query = $this->db->query($sql,array($syear,$did,$cid,$bid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_registration_details_summer($admn_no,$syear){
        $sql = "select a.admn_no,c.dept_id,a.course_id,a.branch_id,a.semester,a.course_aggr_id,a.hod_status,a.acad_status,a.hod_remark,a.acad_remark,
group_concat(b.sub_id separator ', ') as sub_id,
group_concat(d.subject_id separator ', ') as subject_id,
group_concat(d.name separator ', ') as name
from reg_summer_form a 
inner join reg_summer_subject b on a.form_id=b.form_id
inner join user_details c on c.id=a.admn_no
inner join subjects d on d.id=b.sub_id
where a.admn_no=? and a.session_year=? and a.`session`='Summer'
group by a.admn_no,a.timestamp
order by a.admn_no
";

       
        $query = $this->db->query($sql,array($admn_no,$syear));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
function get_stu_prefinal_final($syear,$did,$cid,$bid,$sy,$sem)
    {
        
		
		if($did=='none' ||$did=='all'){
			$where.=" ";
		
		}
		else{
			$where.=" and f.dept_id= '".$did."'";
		}
	   if($cid=='none' ){
			$where=" ";
		}
		else{
			$where.=" and sa.course_id= '".$cid."'";
		}
		if($bid=='none' ){
			$where=" ";
		}
		else{
			$where.=" and sa.branch_id= '".$bid."'";
		}
        
        
        /*$sql = "select a.admn_no,concat_ws(' ' ,b.first_name,b.middle_name,b.last_name) as stu_name,
c.name_in_hindi,a.dept,a.course,a.branch,a.semester,a.core_status,e.name as dname,d.name as cname,f.name as bname,g.line1,g.line2,a.hstatus,b.email,g.contact_no
 from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
inner join stu_details c on c.admn_no=a.admn_no
inner join cs_courses d on d.id=a.course
inner join departments e on e.id=a.dept
inner join cs_branches f on f.id=a.branch
inner join user_address g on g.id=a.admn_no
where a.session_yr=? and a.course<>'MINOR' and a.course<>'JRF' and a.course<>'COMM'
and a.semester=(d.duration*2) ".$where." and g.`type`='permanent' and a.`status`='PASS'
order by a.dept,a.course,a.branch,a.admn_no" ;*/

$sql = " select f.dept_id,CONCAT_WS (' ',f.first_name, f.middle_name,f.last_name) as sname, f.id, DATE_FORMAT(f.dob,'%d/%m/%Y') as dob, f.category, f.sex /*f.grade as '10th Perc.', f.year as '10th year', f.grade as '12th Perc.', f.year as '12th year'*/,stp.passout_year,e.name as dname,d.name as cname,br.name as bname,sa.course_id,f.email,ud.mobile_no
FROM user_details f
join   stu_enroll_passout stp on stp.admn_no=f.id and stp.passout_year= '".$syear."'
inner join stu_academic sa on sa.admn_no=f.id    ".$where."
inner join cs_courses d on d.id=sa.course_id
inner join departments e on e.id=f.dept_id
inner join cs_branches br on br.id=sa.branch_id
inner join user_other_details ud on ud.id=f.id
inner join reg_regular_form rrf on rrf.admn_no=f.id and rrf.session_year='".$sy."' and rrf.semester='".$sem."' and rrf.hod_status='1' and rrf.acad_status='1'
order by f.dept_id,stp.passout_year  ";


        $query = $this->db->query($sql,$prr);

 //      echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_edu_details($admn_no,$c){
        $sql = "select * from stu_prev_education where admn_no=? and specialization=?";
      
        $query = $this->db->query($sql,array($admn_no,$c));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function get_minor($admn_no){
        $sql = "select c.name as dname,d.name as bname from hm_form a 
inner join hm_minor_details b on a.form_id=b.form_id
inner join departments c on c.id=b.dept_id
inner join cs_branches d on d.id=b.branch_id
where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y'
and b.offered='1'";
      
        $query = $this->db->query($sql,array($admn_no));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function get_mtech_details($admn_no,$sno){
        $sql = "select * from stu_prev_education where admn_no=? and sno=?";
      
        $query = $this->db->query($sql,array($admn_no,$sno));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function get_result_declared_semester($syear,$did,$cid,$bid){
        $sql = "select max(a.semester)as semester from result_declaration_log a  where
a.s_year=? and a.dept_id=? and a.course_id=? and  a.branch_id=? and a.`type`='F'";
      
        $query = $this->db->query($sql,array($syear,$did,$cid,$bid));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->semester;
        } else {
            return false;
        }
    }
    
    //==============================================================================================
    
    
    
    //======================================================================================
	
}

?>