<?php

class Final_year_passlist_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student_passlist($syear,$did,$cid,$bid)
    {
        
        if($did=='none' && $cid=='none' && $bid='none'){
            $where="";
        }
        else{
            $where="and a.dept=? and a.course=? and a.branch=?";
        }
        $sql = "select a.admn_no,concat_ws(' ' ,b.first_name,b.middle_name,b.last_name) as stu_name,
c.name_in_hindi,a.dept,a.course,a.branch,a.semester,/*a.cgpa,*/a.core_status,e.name as dname,d.name as cname,f.name as bname,g.line1,g.line2,a.hstatus
 from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
inner join stu_details c on c.admn_no=a.admn_no
inner join cs_courses d on d.id=a.course
inner join departments e on e.id=a.dept
inner join cs_branches f on f.id=a.branch
inner join user_address g on g.id=a.admn_no
where a.session_yr=? and a.course<>'MINOR' and a.course<>'JRF' and a.course<>'COMM'
and a.semester=(d.duration*2) ".$where." and g.`type`='permanent' and a.`status`='PASS'
order by a.dept,a.course,a.branch,a.admn_no";

        $query = $this->db->query($sql,array($syear,$did,$cid,$bid));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>