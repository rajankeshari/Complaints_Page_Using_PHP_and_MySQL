<?php

class Marks_upload_status_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list($syear,$sess,$did,$mstatus)
    {
        
        if($mstatus=='yes'){
            $status="and c.`status`='Y'";
        }
        else if($mstatus=='no'){
             $status="and (c.`status`='N' || c.`status` is null)";
        }else{
            $status="";
        }
        if($did=='none'){
            $dept="";
        }else if($did=='all'){
            $dept="";
        }
        else{
            $dept="and a.dept_id='".$did."'";
        }
        
        $sql = "select b.*,a.dept_id,a.course_id,a.branch_id,a.semester,c.highest_marks,c.sub_map_id,
CASE c.`type` WHEN 'R' THEN 'Regular' WHEN 'J' THEN 'JRF' WHEN 'O' THEN 'Other' END AS exam_type
,c.`status`,d.subject_id,d.name as sub_name,
concat_ws(' ',e.first_name,e.middle_name,e.last_name) as faculty,
f.name as dname,g.name as cname,h.name as bname,i.mobile_no 
from subject_mapping_des b
inner join subject_mapping a on a.map_id=b.map_id
left join marks_master c on (a.map_id=c.sub_map_id and a.session_year=c.session_year and a.`session`=c.`session` and b.sub_id=c.subject_id)
inner join subjects d on d.id=b.sub_id
inner join user_details e on e.id=b.emp_no
inner join departments f on f.id=a.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
inner join user_other_details i on i.id=b.emp_no
where a.session_year=? and a.`session`=? ".$dept." ".$status." 
and b.coordinator='1' order by a.dept_id,a.course_id,a.branch_id,a.semester,b.emp_no";

        
        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>