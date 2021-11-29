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
    

}

?>