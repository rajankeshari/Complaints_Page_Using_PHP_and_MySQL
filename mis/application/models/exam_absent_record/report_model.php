<?php

class Report_model extends CI_Model
{
	
	

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

 	
	function get_effective($branch,$dept,$course)
	{
		
                
                $sql="select aggr_id, RIGHT(aggr_id,9) as aggyear from dept_course as a join course_branch as b on a.course_branch_id=b.course_branch_id and a.dept_id='".$dept."' and b.course_id='".$course."' and b.branch_id='".$branch."'";
            
                $query = $this->db->query($sql);
                if($this->db->affected_rows() >=0)
                { 
                 return $query->result();
                }
                else
                {
                    return false;
                }
	}
        
        function get_subject($aid,$sem)
	{
		                
             /*   $sql="select a.id,a.semester,a.aggr_id,a.sequence,b.subject_id,b.name from course_structure as a
inner join subjects as b on a.id=b.id where aggr_id='".$aid."' and semester='".$sem."'";*/
             
            $sql="select a.id,a.semester,a.aggr_id,a.sequence,b.subject_id,b.name from course_structure as a
inner join subjects as b on a.id=b.id where aggr_id='".$aid."' and semester='".$sem."' and elective='0'";
                
             $sql1="select distinct a.id,a.semester,a.aggr_id,a.sequence,b.subject_id,b.name from course_structure as a
  inner join subjects as b on a.id=b.id inner join reg_regular_elective_opted as c on a.id=c.sub_id where a.semester='".$sem."' 
  and a.aggr_id='".$aid."' and b.elective!='0'";  
             
             
            
                $query1 = $this->db->query($sql);
                $query2 = $this->db->query($sql1);
               // array_merge($query1,$query2);
                
                if($query1->num_rows() >=0)
                { 
                    $q1=$query1->result();
                    
                    //return $query->result();
                 
                }
                if($query2->num_rows() >=0)
                { 
                    $q2=$query2->result();
                    
                    //return $query->result();
                 
                }
                
                return array_merge($q1,$q2);
                
	}
        
        
	}
	?>