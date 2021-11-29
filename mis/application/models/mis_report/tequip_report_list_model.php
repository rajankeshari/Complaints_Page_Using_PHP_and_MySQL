<?php

class tequip_report_list_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	function get_student_statistics($syear,$typelist,$stustatus,$stcategory)
    {
        if($typelist=='ug'||$typelist=='pg'){
            $course="a.course<>'MINOR' and a.course<>'JRF' and a.course<>'COMM'";
        }
        if($typelist=='jrf'){
            $course="a.course='JRF'";
        }
        if($stustatus=='all'){$where="";}
        if($stustatus=='pass'){$where=" and (a.core_status='PASS' || a.core_status='Pass' || a.core_status='pass')";}
        if($stustatus=='fail'){$where=" and (a.core_status='FAIL' || a.core_status='Fail' || a.core_status='fail')";}
       // echo $stcategory;
		//die('abc');
		//if($stcategory == "GENERAL') {$stcategory='GEN';}
		
		if($stcategory=='ST'  || $stcategory=='SC' || $stcategory=='GEN' || $stcategory=='OBC')
		{
        //echo $stcategory;
		//die('123');
		
        $sql = "select 
  e.name as Department ,  d.name as Course, (case when a.course='comm'  then a.branch else f.name end ) as Branch
  ,a.session,a.session_yr,a.semester,count(a.admn_no) as total_student, count( if(b.sex='M' ,a.admn_no,null )  ) as Male,count( if(b.sex='F' ,a.admn_no,null )  ) as Female,
  count( if(lower(b.category)='sc'||lower(b.category)='st' ,a.admn_no,null )  ) as SC_ST_student,
  count( if(lower(b.category)='obc'||lower(b.category) like '%obc%' ,a.admn_no,null )  ) as OBC,
  count( if(lower(b.category)='gen' ||lower(b.category) like '%gen%' ,a.admn_no,null )  ) as GEN
 
from user_details b 
inner join final_semwise_marks_foil a on a.admn_no=b.id and  a.session_yr='".$syear."' and  a.course<>'prep' and a.course<>'minor'  and  a.`session`<>'Summer'
and (a.`type`='R'|| a.`type`='J')
inner join stu_details c on c.admn_no=a.admn_no
left join cs_courses d on d.id=a.course
left join departments e on e.id=a.dept
left join cs_branches f on f.id=a.branch
left join user_address g on g.id=a.admn_no  and g.`type`='permanent'
left join stu_academic h on h.admn_no=b.id

Where upper(b.category) like  '%".$stcategory."%'

group by    a.dept,a.course, a.branch,a.`session`,a.semester 
order by a.dept,a.course, a.branch ,a.`session`,a.semester  ";
        }
		
		
		else
		
		{
		//echo $stcategory;
        //die('else');		
		
        $sql = "select 
  e.name as Department ,  d.name as Course, (case when a.course='comm'  then a.branch else f.name end ) as Branch
  ,a.session,a.session_yr,a.semester,count(a.admn_no) as total_student, count( if(b.sex='M' ,a.admn_no,null )  ) as Male,count( if(b.sex='F' ,a.admn_no,null )  ) as Female,
  count( if(lower(b.category)='sc'||lower(b.category)='st' ,a.admn_no,null )  ) as SC_ST_student,
  count( if(lower(b.category)='obc'||lower(b.category) like '%obc%' ,a.admn_no,null )  ) as OBC,
  count( if(lower(b.category)='gen' ||lower(b.category) like '%gen%' ,a.admn_no,null )  ) as GEN
 
from user_details b 
inner join final_semwise_marks_foil a on a.admn_no=b.id and  a.session_yr='".$syear."' and  a.course<>'prep' and a.course<>'minor'  and  a.`session`<>'Summer'
and (a.`type`='R'|| a.`type`='J')
inner join stu_details c on c.admn_no=a.admn_no
left join cs_courses d on d.id=a.course
left join departments e on e.id=a.dept
left join cs_branches f on f.id=a.branch
left join user_address g on g.id=a.admn_no  and g.`type`='permanent'
left join stu_academic h on h.admn_no=b.id



group by    a.dept,a.course, a.branch,a.`session`,a.semester 
order by a.dept,a.course, a.branch ,a.`session`,a.semester  ";
		}
		
		
		
		//echo $sql;
		//die("pou");
        $query = $this->db->query($sql,array($syear,$typelist,$stcategory));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	
    function get_student_passlist($syear,$typelist,$stustatus)
    {
        if($typelist=='ug'||$typelist=='pg'){
            $course="a.course<>'MINOR' and a.course<>'JRF' and a.course<>'COMM'";
        }
        if($typelist=='jrf'){
            $course="a.course='JRF'";
        }
        if($stustatus=='all'){$where="";}
        if($stustatus=='pass'){$where=" and (a.core_status='PASS' || a.core_status='Pass' || a.core_status='pass')";}
        if($stustatus=='fail'){$where=" and (a.core_status='FAIL' || a.core_status='Fail' || a.core_status='fail')";}
        
    
        $sql = "select a.admn_no,concat_ws(' ' ,b.first_name,b.middle_name,b.last_name) as stu_name,
b.sex,b.category,b.physically_challenged,g.state,
a.dept,a.course,a.branch,a.semester,/*a.cgpa,*/a.core_status
 from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
inner join stu_details c on c.admn_no=a.admn_no
inner join cs_courses d on d.id=a.course
inner join departments e on e.id=a.dept
inner join cs_branches f on f.id=a.branch
inner join user_address g on g.id=a.admn_no
inner join stu_academic h on h.admn_no=b.id
where a.session_yr=? and ".$course."
and a.semester=(d.duration*2) and g.`type`='permanent' and h.auth_id=?".$where."
order by a.dept,a.course,a.branch,a.admn_no

";

        $query = $this->db->query($sql,array($syear,$typelist));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>