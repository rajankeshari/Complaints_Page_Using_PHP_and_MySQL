<?php

class View_offered_elective_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_elective_offered($selsyear,$selsess,$seldept,$courselist,$branchlist,$sem)
    {
          
        $sql = "select a.*,b.dept_id,c.course_id,c.branch_id,d.subject_id,d.name,e.semester,e.sequence from optional_offered a
inner join dept_course b on b.aggr_id=a.aggr_id
inner join course_branch c on c.course_branch_id=b.course_branch_id
inner join subjects d on d.id=a.id
inner join course_structure e on e.id=d.id
where a.session_year=? and a.`session`=?
and b.dept_id=? and c.course_id=? and c.branch_id=?
and e.semester=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$seldept,$courselist,$branchlist,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

 
    

}

?>