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
	
	function get_faculty_class_hours_new($id,$syear,$sess) {

//echo $id; echo $syear; echo $sess; die();

      $myquery="SELECT o.*
FROM(
SELECT p.*, CONCAT(p.offered, CAST(p.sub_group AS CHAR)), IF(!(p.sub_code=@sub_code && p.emp_no=@emp_no && p.offered=@offered), @rank,@rank:=@rank+1) AS newgrp_col, @sub_code:=p.sub_code,@emp_no:=p.emp_no,@offered:=p.offered
FROM(
SELECT q.*
FROM(
SELECT a.course_id,a.branch_id, a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category, a.sub_group,b.section,a.sub_type, 'c' AS rstatus, NULL AS total_count,b.emp_no,'cbcs' AS 'offered'
FROM cbcs_subject_offered a 
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? and a.dept_id=?
GROUP BY a.sub_code UNION
SELECT a.course_id,a.branch_id, a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category,a.sub_group,b.section, a.sub_type, 'o' AS rstatus, NULL AS total_count,b.emp_no,'aold' AS 'offered'
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? and c.dept_id=?
GROUP BY a.sub_code UNION (
SELECT t.course_id,t.branch_id,t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'c' AS rstatus,t.total_count,t.emp_no,'distribution_cbcs' AS 'offered'
FROM (
SELECT a.course_id,a.branch_id,a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? and c.dept_id=?
GROUP BY a.sub_code,a.group_no)t
INNER JOIN cbcs_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session
GROUP BY t.sub_code,t.group_no) UNION (
SELECT t.course_id,t.branch_id,t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'o' AS rstatus,t.total_count,t.emp_no,'distribution_old' AS 'offered'
FROM (
SELECT a.course_id,a.branch_id,a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
INNER JOIN user_details c ON c.id=b.emp_no
WHERE a.session_year=? AND a.`session`=? and c.dept_id=?
GROUP BY a.sub_code,a.group_no)t
INNER JOIN old_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session
GROUP BY t.sub_code,t.group_no))q
GROUP BY sub_code,sub_group)p,(
SELECT @sub_code:='',@emp_no:='', @offered:='', @rank:=0) sqlvar
ORDER BY p.sub_code,p.offered DESC, CONCAT(p.offered, CAST(p.sub_group AS CHAR))
LIMIT 100000)o
GROUP BY o.sub_code, o.newgrp_col";
	  
        $query = $this->db->query($myquery,array($syear,$sess,$id,$syear,$sess,$id,$syear,$sess,$id,$syear,$sess,$id));

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
	
	function get_faculty_dept_common_new($deptid,$syear,$sess) {
      $myquery="SELECT tt.*,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name)AS faculty ,
cc.name AS programme,
cb.name AS branch

FROM 
(
SELECT b.session_year,b.`session`,b.dept_id,b.course_id,b.branch_id,b.semester,c.emp_no,c.section,b.lecture,b.tutorial,b.practical
,b.id,b.sub_name,b.sub_code,'c' AS rstatus
FROM (SELECT a.* FROM cbcs_stu_course a WHERE a.session_year=? AND a.`session`=?
GROUP BY a.sub_offered_id)t
INNER JOIN cbcs_subject_offered b ON b.id=t.sub_offered_id and  b.session_year='".$syear."' AND b.`session`='".$sess."'
INNER JOIN cbcs_subject_offered_desc c ON c.sub_offered_id=b.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id= CONCAT('c',b.id) AND g.sub_id=b.sub_code
WHERE b.dept_id='comm' AND b.contact_hours!=0 
UNION /*ALL */ 
SELECT b.session_year,b.`session`,b.dept_id,b.course_id,b.branch_id,b.semester,c.emp_no,c.section,b.lecture,b.tutorial,b.practical
,b.id,b.sub_name,b.sub_code,'o' AS rstatus
FROM (SELECT a.* FROM old_stu_course a WHERE a.session_year=? AND a.`session`=?
GROUP BY a.sub_offered_id)t
INNER JOIN old_subject_offered b ON b.id=t.sub_offered_id and  b.session_year='".$syear."' AND b.`session`='".$sess."'
INNER JOIN old_subject_offered_desc c ON c.sub_offered_id=b.id
LEFT JOIN cbcs_prac_group_attendance g ON g.subject_id= CONCAT('o',b.id) AND g.sub_id=b.sub_code
WHERE b.dept_id='comm' AND b.contact_hours!=0 )tt
INNER JOIN user_details ud ON ud.id=tt.emp_no
INNER JOIN cbcs_courses cc ON cc.id=tt.course_id
INNER JOIN cbcs_branches cb ON cb.id=tt.branch_id
WHERE ud.dept_id=?
";
	  
        $query = $this->db->query($myquery,array($syear,$sess,$syear,$sess,$deptid));

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
    function get_total_classes($syear,$sess,$sub_code,$section,$group,$emp_no)
    {
		//echo $syear;echo $sess; echo $sub_code; echo $section;echo $group;echo $emp_no;die();
   
   if($group==''){ $group='0'; }

   $myquery = "SELECT a.* FROM cbcs_class_engaged_course_wise a
   WHERE    a.session_year=? AND a.`session`=? 
   AND a.engaged_by=? AND a.course_code=? AND a.group_no=?  " ;
      
    
        $query = $this->db->query($myquery,array($syear,$sess,$emp_no,$sub_code,$group));
       // echo $this->db->last_query();die();
       
        if ($query->num_rows() > 0) {
            return $query->result();
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


    function get_faculty($id)
{

  $myquery = "SELECT CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)AS faculty FROM user_details a WHERE a.id=?" ;
//echo $myquery;      
        $query = $this->db->query($myquery,array($id));
      
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }


}

function get_ltp($sy,$sess,$sub_code){

$myquery = "(SELECT CONCAT_ws('-',a.lecture,a.tutorial,a.practical)AS ltp FROM cbcs_subject_offered a
WHERE a.session_year=? AND a.`session`=?
AND a.sub_code=?)
union
(SELECT CONCAT_ws('-',a.lecture,a.tutorial,a.practical)AS ltp FROM old_subject_offered a
WHERE a.session_year=? AND a.`session`=?
AND a.sub_code=?)" ;
//echo $myquery;      
        $query = $this->db->query($myquery,array($sy,$sess,$sub_code,$sy,$sess,$sub_code));
      
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

}



    
        

}


