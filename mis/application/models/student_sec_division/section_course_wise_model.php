<?php

class Section_course_wise_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_papers_details($admn_no,$syear,$sess,$ptype)
    {
          if($ptype=='comm'){
			  $str="WHERE t.session_year='".$syear."' AND t.session='".$sess."' and t.course_id='comm'";
		  }else{
			  $str="WHERE t.session_year='".$syear."' AND t.session='".$sess."' and t.course_id<>'comm'";
		  }
     
        
        $sql="SELECT t.* from(SELECT a.*,
(case when  CONCAT('o',o.id)=a.sub_offered_id  then o.course_id  ELSE c.course_id  end)AS course_id,
(case when  CONCAT('o',o.id)=a.sub_offered_id  then o.sub_group  ELSE c.sub_group  end)AS sub_group,
(case when  CONCAT('o',o.id)=a.sub_offered_id  then o.sub_type  ELSE c.sub_type  end)AS course_type
 FROM pre_stu_course a 
LEFT  JOIN 
old_subject_offered o ON CONCAT('o',o.id)=a.sub_offered_id
LEFT  JOIN 
cbcs_subject_offered c ON CONCAT('c',c.id)=a.sub_offered_id
WHERE a.admn_no='".$admn_no."')t  ".$str;

        
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function update_section_course_code($id,$sub_offered_id,$section)
        {
        
        $sql = "UPDATE pre_stu_course SET sub_category_cbcs_offered='".$section."' WHERE id=".$id." AND sub_offered_id='".$sub_offered_id."'" ;
        $query = $this->db->query($sql);
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
		
		function get_time_table_old($subject_code,$syear,$sess,$dept_id,$course_id,$branch_id){
			$sql = "SELECT CONCAT(a.day,a.slot_no) AS dslot
FROM tt_subject_slot_map_old a
INNER JOIN tt_map_old b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE  b.session_year='".$syear."' AND b.`session`='".$sess."'
AND b.dept_id='".$dept_id."' AND b.course_id='".$course_id."' AND b.branch_id='".$branch_id."'
 AND a.subj_code='".$subject_code."' " ;
        $query = $this->db->query($sql);
        
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
            return FALSE;
        }
			
			
		}
				function get_time_table_cbcs($subject_code,$syear,$sess,$dept_id,$course_id,$branch_id){
			$sql = "SELECT CONCAT(a.day,a.slot_no) AS dslot
FROM tt_subject_slot_map_cbcs a
INNER JOIN tt_map_cbcs b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE  b.session_year='".$syear."' AND b.`session`='".$sess."'
AND b.dept_id='".$dept_id."' AND b.course_id='".$course_id."' AND b.branch_id='".$branch_id."'
 AND a.subj_code='".$subject_code."' " ;
        $query = $this->db->query($sql);
        
		//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
             return $query->result();
        } else {
            return FALSE;
        }
				}
		
		function get_student_details($syear,$sess,$admn_no)
    {
        $sql="SELECT a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)AS stu_name,
c.name AS dname,d.name AS cname,e.name AS bname,b.photopath,a.semester,a.session_year,a.`session`
,concat(upper(a.course_id),' ( ',e.name,' )')AS discipline,f.section,b.dept_id,a.course_id,a.branch_id
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_departments c ON c.id=b.dept_id
INNER JOIN cbcs_courses d ON d.id=a.course_id
INNER JOIN cbcs_branches e ON e.id=a.branch_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=a.session_year
WHERE a.session_year=? AND a.`session`=?
AND a.hod_status='1' AND a.acad_status='1' AND a.admn_no=?";
        $query = $this->db->query($sql,array($syear,$sess,$admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }

    }
	
		function insert_time_table_old($subject_code,$syear,$sess,$dept_id,$course_id,$branch_id,$admn_no){
			
			$sql = " SELECT 'old'AS rstatus,a.subj_code,a.day,a.slot_no,b.dept_id,b.course_id,b.branch_id,b.section,b.session_year,b.`session`
FROM tt_subject_slot_map_old a
INNER JOIN tt_map_old b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE b.session_year='$syear' AND b.`session`='$sess' AND b.dept_id='$dept_id' 
AND b.course_id='$course_id' AND b.branch_id='$branch_id' AND a.subj_code='$subject_code'" ;
        $query = $this->db->query($sql);
        
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
             $sql = " insert into tt_clash_section_old(admn_no,rstatus,subj_code,DAY,slot,dept_id,course_id,branch_id,section,session_year,SESSION)
SELECT '".$admn_no."' AS admn_no,'old'AS rstatus,a.subj_code,a.day,a.slot_no,b.dept_id,b.course_id,b.branch_id,b.section,b.session_year,b.`session`
FROM tt_subject_slot_map_old a
INNER JOIN tt_map_old b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE b.session_year='$syear' AND b.`session`='$sess' AND b.dept_id='$dept_id' 
AND b.course_id='$course_id' AND b.branch_id='$branch_id' AND a.subj_code='$subject_code'" ;

 //echo $sql;
        $query = $this->db->query($sql);
       
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
             return TRUE;
        } else {
            return FALSE;
        }
			 
			 
        } else {
            return FALSE;
        }
			
			
			
			
		}
		
		function insert_time_table_cbcs($subject_code,$syear,$sess,$dept_id,$course_id,$branch_id,$admn_no){
			
			$sql = " SELECT 'cbcs'AS rstatus,a.subj_code,a.day,a.slot_no,b.dept_id,b.course_id,b.branch_id,b.section,b.session_year,b.`session`
FROM tt_subject_slot_map_cbcs a
INNER JOIN tt_map_cbcs b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE b.session_year='$syear' AND b.`session`='$sess' AND b.dept_id='$dept_id' 
AND b.course_id='$course_id' AND b.branch_id='$branch_id' AND a.subj_code='$subject_code'" ;
        $query = $this->db->query($sql);
        
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
             $sql = " insert into tt_clash_section_cbcs(admn_no,rstatus,subj_code,DAY,slot,dept_id,course_id,branch_id,section,session_year,SESSION)
SELECT '".$admn_no."' AS admn_no,'cbcs'AS rstatus,a.subj_code,a.day,a.slot_no,b.dept_id,b.course_id,b.branch_id,b.section,b.session_year,b.`session`
FROM tt_subject_slot_map_cbcs a
INNER JOIN tt_map_cbcs b ON b.map_id=a.map_id
INNER JOIN tt_days_master c ON c.id=a.day
INNER JOIN tt_slot_master d ON d.slot_no=a.slot_no
WHERE b.session_year='$syear' AND b.`session`='$sess' AND b.dept_id='$dept_id' 
AND b.course_id='$course_id' AND b.branch_id='$branch_id' AND a.subj_code='$subject_code'" ;
        $query = $this->db->query($sql);
        
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
             return TRUE;
        } else {
            return FALSE;
        }
			 
			 
        } else {
            return FALSE;
        }
			
			
			
			
		}
		
		function suggestive_section($admn_no,$subj_code,$syear,$sess){
			
		
		$sql=" 
SELECT t1.*, GROUP_CONCAT(t1.section ORDER BY t1.section) AS sugg_section
FROM(
(
SELECT t.*, COUNT(t.section)cnt, COUNT(old_day) AS class_status
FROM
(
SELECT a.admn_no,a.subj_code,a.day,a.slot,a.section,a.session_year,a.`session`,
b.admn_no AS old_admn_no
,b.subj_code AS old_sub_code,b.day AS old_day,b.slot AS old_slot,b.section AS old_section,
b.session_year AS old_session_year,b.`session` AS old_session
FROM tt_clash_section_cbcs a
LEFT JOIN tt_clash_section_old b ON a.day=b.day AND a.slot=b.slot AND a.admn_no=b.admn_no
GROUP BY a.subj_code,a.day,a.slot,a.section
ORDER BY a.subj_code,a.section,a.day,a.slot)t
GROUP BY t.subj_code,t.section)
)t1
WHERE t1.class_status=0 AND t1.admn_no=? AND t1.subj_code=?
AND t1.session_year=? AND t1.session=?
GROUP BY t1.subj_code";

    $query = $this->db->query($sql,array($admn_no,$subj_code,$syear,$sess));
	if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
		}
		
		function search_student($admn_no,$syear,$sess,$type){
			
			if($type=='comm'){
				$tbl="tt_clash_section_cbcs";
			}else{
				$tbl="tt_clash_section_old";
			}
			
			$sql="SELECT * from $tbl WHERE session_year=? AND SESSION=? AND admn_no=? ";

    $query = $this->db->query($sql,array($syear,$sess,$admn_no));
	if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
			
			
		}

}

?>