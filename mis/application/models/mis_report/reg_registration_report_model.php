<?php

class Reg_registration_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_regular_student($syear, $sess, $did, $cid, $bid, $sem)
    {
              
        $sql="select b.id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
CASE (b.sex) WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' END as 'sex',b.category,b.email,f.mobile_no,c.name as dname,d.name as cname,e.name as bname,
a.semester,a.session_year,a.`session`
,b.dept_id,a.course_id,a.branch_id,a.*
from reg_regular_form a
inner join user_details b on a.admn_no=b.id
left join departments c on c.id=b.dept_id
left join cs_courses d on d.id=a.course_id
left join cs_branches e on e.id=a.branch_id
inner join user_other_details f on f.id=b.id
where 1=1";

        if($syear){
            $sql.=" and a.session_year='".$syear."'";
        }
        if($sess){
            $sql.=" and a.session='".$sess."'";
        }
        
        if($did!='all'){
            $sql.=" and b.dept_id='".$did."'";
        }
        if($cid!='all'){
            $sql.=" and a.course_id='".$cid."'";
        }
        if($bid!='all'){
            $sql.=" and a.branch_id='".$bid."'";
        }
        if($sem!=''){
            $sql.=" and a.semester='".$sem."'";
        }
        
        $sql.=" order by b.dept_id,a.course_id,a.branch_id,a.semester,a.admn_no ";
        
       $query = $this->db->query($sql);

            echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    
    
    


}

?>