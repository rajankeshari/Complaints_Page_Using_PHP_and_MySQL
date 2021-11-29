<?php

class Class_engaged_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_faculty_class_hours($id,$syear,$sess) {
      $myquery="(SELECT g.group_no,null as session_id,a.id AS map_id,a.session_year,a.`session`,a.dept_id,a.course_id,
a.branch_id,a.semester, b.emp_no,b.coordinator,b.sub_id,c.name,
 CONCAT(d.first_name,' ',d.middle_name,' ',d.last_name) AS f_name, 
a.sub_name,b.section,'c' AS rstatus,a.lecture,a.tutorial,a.practical
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details d ON d.id=b.emp_no
INNER JOIN cbcs_departments c ON a.dept_id=c.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id=CONCAT('c',a.id) AND g.sub_id=a.sub_code
WHERE a.dept_id=? AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') 
AND a.lecture<>'0'
ORDER BY f_name,a.course_id,a.branch_id,a.semester,g.group_no
)union
(SELECT g.group_no,null as session_id,a.id AS map_id,a.session_year,a.`session`,a.dept_id,a.course_id,
a.branch_id,a.semester, b.emp_no,b.coordinator,b.sub_id,c.name,
 CONCAT(d.first_name,' ',d.middle_name,' ',d.last_name) AS f_name, 
a.sub_name,b.section,'o' AS rstatus,a.lecture,a.tutorial,a.practical
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details d ON d.id=b.emp_no
INNER JOIN cbcs_departments c ON a.dept_id=c.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id=CONCAT('c',a.id) AND g.sub_id=a.sub_code
WHERE a.dept_id=? AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') 
AND a.lecture<>'0'
ORDER BY f_name,a.course_id,a.branch_id,a.semester,g.group_no
)";
	  
        $query = $this->db->query($myquery,array($id,$syear,$sess,$id,$syear,$sess));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
	//========================Departmental Common==================================
	
		    function get_faculty_dept_common($id,$syear,$sess) {
      $myquery="SELECT pp.* FROM ((SELECT g.group_no,null as session_id,a.id AS map_id,a.session_year,a.`session`,a.dept_id,a.course_id,
a.branch_id,a.semester, b.emp_no,b.coordinator,b.sub_id,c.name,
 CONCAT(d.first_name,' ',d.middle_name,' ',d.last_name) AS f_name, 
a.sub_name,b.section,'c' AS rstatus,a.lecture,a.tutorial,a.practical
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details d ON d.id=b.emp_no
INNER JOIN cbcs_departments c ON a.dept_id=c.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id=CONCAT('c',a.id) AND g.sub_id=a.sub_code
WHERE a.dept_id='comm' AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') 
AND a.lecture<>'0'
ORDER BY f_name,a.course_id,a.branch_id,a.semester,g.group_no
)union
(SELECT g.group_no,null as session_id,a.id AS map_id,a.session_year,a.`session`,a.dept_id,a.course_id,
a.branch_id,a.semester, b.emp_no,b.coordinator,b.sub_id,c.name,
 CONCAT(d.first_name,' ',d.middle_name,' ',d.last_name) AS f_name, 
a.sub_name,b.section,'o' AS rstatus,a.lecture,a.tutorial,a.practical
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details d ON d.id=b.emp_no
INNER JOIN cbcs_departments c ON a.dept_id=c.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id=CONCAT('c',a.id) AND g.sub_id=a.sub_code
WHERE a.dept_id='comm' AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') 
AND a.lecture<>'0'
ORDER BY f_name,a.course_id,a.branch_id,a.semester,g.group_no
))pp
INNER JOIN user_details qq ON qq.id=pp.emp_no
WHERE qq.dept_id=?";
	  
        $query = $this->db->query($myquery,array($syear,$sess,$syear,$sess,$id));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

	
	
	//================================================================================
    
        function get_faculty_byDept($id) {
        $myquery = "select a.id,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as f_name,b.auth_id from 
user_details a 
inner join emp_basic_details b on a.id=b.emp_no
where a.dept_id='".$id."' and b.auth_id='ft' order by f_name
 " ;
      
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_total_classes($mid,$subid,$dfrom,$dto,$emp_no)
    {
		//echo $mid;echo $subid; echo $dfrom; echo $dto;echo $emp_no;die();
    if($dfrom=='01-01-1970' && $dto='01-01-1970')
    {
     $myquery = "  SELECT a.* FROM cbcs_class_engaged a where a.engaged_by='".$emp_no."' AND a.subject_offered_id='".$mid."' ORDER BY id desc" ;
    }
    else{    
    $myquery = " SELECT a.* FROM cbcs_class_engaged a WHERE a.engaged_by='".$emp_no."' AND a.subject_offered_id='".$mid."' ORDER BY id desc
	 and STR_TO_DATE(date, '%d-%m-%Y')   BETWEEN STR_TO_DATE('".$dfrom."', '%d-%m-%Y')     AND STR_TO_DATE('".$dto."', '%d-%m-%Y')" ;
    }
      
    
        $query = $this->db->query($myquery);
        //echo $this->db->last_query();die();
       
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
    function get_date_of_classes($mid,$subid,$dfrom,$dto,$gid,$emp_no)
    {
     
        if($gid>=1){
        $gr="and group_no=".$gid; 
        }else{
            $gr="";
        }
    if($dfrom=='01-01-1970' && $dto='01-01-1970')
    {
        //$myquery = " select * from class_engaged where map_id=".$mid." and sub_id='".$subid."' ".$gr."  order by STR_TO_DATE(date, '%d-%m-%Y')" ;
		
		$myquery = " SELECT a.* FROM cbcs_class_engaged a where a.engaged_by='".$emp_no."' AND a.subject_offered_id='".$mid."' ".$gr."  order by STR_TO_DATE(date, '%d-%m-%Y')" ;
 
       }
       else{    
       $myquery = " SELECT a.* FROM cbcs_class_engaged a where a.engaged_by='".$emp_no."' AND a.subject_offered_id='".$mid."' ".$gr."  and 
    STR_TO_DATE(date, '%d-%m-%Y') BETWEEN STR_TO_DATE('".$dfrom."', '%d-%m-%Y') 
    AND STR_TO_DATE('".$dto."', '%d-%m-%Y') 
    order by STR_TO_DATE(date, '%d-%m-%Y')" ;
       }


           $query = $this->db->query($myquery);
          // echo $this->db->last_query();

           if ($query->num_rows() > 0) 
           {
               return $query->result();
           } else {
               return FALSE;
           }
        
    }
    
    function get_aggr_id_year($subid,$sem)
    {
       $myquery = "select aggr_id from course_structure where id='".$subid."' and semester='".$sem."' " ;
       $query = $this->db->query($myquery);
           if ($query->num_rows() > 0) 
           {
               return $query->row();
           } else {
               return FALSE;
           }
        
    }

    function get_map_details($mapid,$empno) {
		
		if(substr($mapid, 0, 1)=='c'){
			$tbl=" cbcs_subject_offered ";
			$tbl_desc=" cbcs_subject_offered_desc ";
			
		}
		if(substr($mapid, 0, 1)=='o'){
			$tbl=" old_subject_offered ";
			$tbl_desc="old_subject_offered_desc ";
		}
		
        $myquery = "(SELECT CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name)AS fname,
d.name AS cname,e.name AS bname,b.semester,b.sub_name,CONCAT_WS('-',b.lecture,b.tutorial,b.practical)AS ltp,b.sub_code from ".$tbl_desc." a
inner join ".$tbl." b on b.id=a.sub_offered_id
INNER JOIN user_details c ON c.id=a.emp_no
INNER JOIN cbcs_courses d ON d.id=b.course_id
INNER JOIN cbcs_branches e ON e.id=b.branch_id
where a.emp_no =? AND b.id=?)" ;
//echo $myquery;      
        $query = $this->db->query($myquery,array($empno,substr($mapid,1)));
      
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
        

}
