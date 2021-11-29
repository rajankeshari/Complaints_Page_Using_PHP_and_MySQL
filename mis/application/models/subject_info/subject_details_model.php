<?php

class Subject_details_model extends CI_Model
{
	function get_subject_details($id)
	{
            $myquery = "select sub.id,RIGHT(a.aggr_id,9) as `session`,a.dept_id,b.course_id,b.branch_id,bs.semester,bs.sequence,sub.subject_id,sub.name,sub.lecture,sub.tutorial,sub.practical,sub.credit_hours,sub.contact_hours,sub.`type`,a.aggr_id,sub.lecture,sub.tutorial,sub.practical from dept_course as a 
join course_branch as b on a.course_branch_id=b.course_branch_id 
join course_structure as bs on a.aggr_id=bs.aggr_id 
join subjects as sub on bs.id=sub.id
where sub.subject_id='".$id."'
order by a.dept_id,b.branch_id,a.aggr_id,bs.semester,bs.sequence";
            
            

                $query = $this->db->query($myquery);


                if ($query->num_rows() > 0) {
                   
                    return $query->result();
                } else {
                    return false;
                }
            
        }    
           
}
