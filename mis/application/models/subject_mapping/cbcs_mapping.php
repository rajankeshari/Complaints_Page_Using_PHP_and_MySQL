<?php

class Cbcs_mapping extends CI_Model
{
	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	   function get_details_regular($syear,$sess,$dept_id,$course_id)
    {
         //echo $syear; echo $sess; echo $dept_id;die();
		 if($course_id=='jrf'){
			$con=" WHERE x.course_id='jrf' order by x.course_id,x.branch_id,x.semester";
		 }
		 if($course_id=='nonjrf'){
			$con=" WHERE x.course_id!='jrf' order by x.course_id,x.branch_id,x.semester";
		 }
		 
		  $sql="
SELECT x.*,cd.name AS dname,cc.name AS cname,cb.name AS bname FROM(
(
SELECT a.id,a.dept_id,a.course_id,a.branch_id,a.semester,'old' AS rstatus,b.section
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
GROUP BY a.dept_id,a.course_id,a.branch_id,a.semester) UNION (
SELECT a.id,a.dept_id,a.course_id,a.branch_id,a.semester,'cbcs' AS rstatus,b.section
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
GROUP BY a.dept_id,a.course_id,a.branch_id,a.semester)
)x
INNER JOIN cbcs_departments cd  ON cd.id=x.dept_id
INNER JOIN cbcs_courses cc ON cc.id=x.course_id
INNER JOIN cbcs_branches cb ON cb.id=x.branch_id".$con;

        
        $query = $this->db->query($sql,array($syear,$sess,$dept_id,$syear,$sess,$dept_id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
        
    }
	
	function get_details_common($syear,$sess,$dept_id)
    {
         
		 
		  $sql="SELECT x.*,cd.name AS dname,cc.name AS cname,cb.name AS bname FROM(
SELECT a.dept_id,a.course_id,a.branch_id,a.semester,'cbcs' AS rstatus,b.section FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id 
WHERE a.session_year=? AND a.`session`=? AND a.course_id='comm' 
GROUP BY a.dept_id,a.course_id,a.branch_id,a.semester,b.section
)x
INNER JOIN cbcs_departments cd  ON cd.id=x.dept_id
INNER JOIN cbcs_courses cc ON cc.id=x.course_id
INNER JOIN cbcs_branches cb ON cb.id=x.branch_id ORDER BY x.section";

        
        $query = $this->db->query($sql,array($syear,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
        
    }
	function get_details_prep($syear,$sess)
    {
         
		 
		  $sql="SELECT x.*,cd.name AS dname,cc.name AS cname,cb.name AS bname FROM(
SELECT 'prep' as dept_id ,a.course_id,a.branch_id,a.semester,'old' AS rstatus,b.section FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id 
WHERE a.session_year=? AND a.`session`=? AND a.course_id='prep' 
GROUP BY a.course_id,a.branch_id,a.semester
)x
INNER JOIN cbcs_departments cd  ON cd.id=x.dept_id
INNER JOIN cbcs_courses cc ON cc.id=x.course_id
INNER JOIN cbcs_branches cb ON cb.id=x.branch_id";

        
        $query = $this->db->query($sql,array($syear,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
        
    }
	function get_mapping_details($data){
		
		
		if($data['rstatus']=='old'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';$sub_off_id="CONCAT('o',b.sub_offered_id)";}
		if($data['rstatus']=='cbcs'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';$sub_off_id="CONCAT('c',b.sub_offered_id)";}
		if($data['course_id']=='comm'){
			$sec_con= " AND b.section=? ";
			$con=array($data['session_year'],$data['session'],$data['dept_id'],$data['course_id'],$data['branch_id'],$data['semester'],$data['section']);
		}else{
			$con=array($data['session_year'],$data['session'],$data['dept_id'],$data['course_id'],$data['branch_id'],$data['semester']);
			
		}
		
		$sql="SELECT a.sub_name,a.sub_code,b.emp_no,CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name)AS faculty,b.sub_offered_id,".$sub_off_id."AS sub_offered_id1,d.name AS dname,b.coordinator,a.dept_id,a.course_id,a.branch_id,a.semester,b.section,b.part,b.desc_id FROM ".$tbl." a
LEFT JOIN ".$tbl_desc." b ON b.sub_offered_id=a.id
LEFT JOIN user_details c ON c.id=b.emp_no
LEFT JOIN cbcs_departments d ON d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
AND a.course_id=? AND a.branch_id=? AND a.semester=? ".$sec_con." 
ORDER BY a.sub_name
";

        
        $query = $this->db->query($sql,$con);

       //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
		
		
	}
	function get_mapping_details_add_teacher($data){
		
		
		if($data['rstatus']=='old'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';$sub_off_id="CONCAT('o',b.sub_offered_id)";}
		if($data['rstatus']=='cbcs'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';$sub_off_id="CONCAT('c',b.sub_offered_id)";}
		if($data['section']!=""){
			$sec_con= " AND b.section=? ";
			$con=array($data['session_year'],$data['session'],$data['dept_id'],$data['course_id'],$data['branch_id'],$data['semester'],$data['section']);
		}else{
			$con=array($data['session_year'],$data['session'],$data['dept_id'],$data['course_id'],$data['branch_id'],$data['semester']);
			
		}
		
		$sql="SELECT a.sub_name,a.sub_code,b.emp_no,CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name)AS faculty,b.sub_offered_id,".$sub_off_id."AS sub_offered_id1,d.name AS dname,b.coordinator,a.dept_id,a.course_id,a.branch_id,a.semester,b.section,b.part,b.desc_id FROM ".$tbl." a
LEFT JOIN ".$tbl_desc." b ON b.sub_offered_id=a.id
LEFT JOIN user_details c ON c.id=b.emp_no
LEFT JOIN cbcs_departments d ON d.id=c.dept_id
WHERE a.session_year=? AND a.`session`=? AND a.dept_id=?
AND a.course_id=? AND a.branch_id=? AND a.semester=? ".$sec_con." GROUP BY b.sub_offered_id
ORDER BY a.sub_name
";

        
        $query = $this->db->query($sql,$con);

       //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
		
		
	}
	function get_description_id($data){
		
		//echo substr($data['mapId'], 1);
		
		
		if(substr($data['mapId'], 0, 1)=='o'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';}
		if(substr($data['mapId'], 0, 1)=='c'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';}
		//AND section='';
		
		if($data['course_id']=='comm'){
			$sql="SELECT a.* FROM ".$tbl_desc." a WHERE a.sub_offered_id=? AND a.emp_no=? AND a.sub_id=?  and a.section=?";
			$query = $this->db->query($sql,array(substr($data['mapId'], 1),$data['oldt'],$data['subId'],$data['section']));
		}
			
		else{
			$sql="SELECT a.* FROM ".$tbl_desc." a WHERE a.sub_offered_id=? AND a.emp_no=? AND a.sub_id=? ";
			$query = $this->db->query($sql,array(substr($data['mapId'], 1),$data['oldt'],$data['subId']));
		}

       //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

		
	}
	
	function get_description_id_to_add($data){
		
		//echo substr($data['mapId'], 1);
		
		
		if(substr($data['sub_id'], 0, 1)=='o'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';}
		if(substr($data['sub_id'], 0, 1)=='c'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';}
		//AND section='';
		if($data['course_id']=='comm'){
			$sql="SELECT a.* FROM ".$tbl_desc." a WHERE a.sub_offered_id=? and a.section=?";
        $query = $this->db->query($sql,array(substr($data['sub_id'], 1),$data['section']));
		}
			
		else{
			$sql="SELECT a.* FROM ".$tbl_desc." a WHERE a.sub_offered_id=? ";
			$query = $this->db->query($sql,array(substr($data['sub_id'], 1)));
		}
		
		

       //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

		
	}
	
	function update_description($desc_id,$data){
		
		//echo substr($data['mapId'], 1);
		
		
		if(substr($data['mapId'], 0, 1)=='o'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';}
		if(substr($data['mapId'], 0, 1)=='c'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';}
		
		$sql="UPDATE ".$tbl_desc." SET emp_no=?  WHERE desc_id=? ";

        
        $query = $this->db->query($sql,array($data['teacherId'],$desc_id));

       //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }

		
	}
	
		function insert_mapping_des($data,$tbl_desc){
			if($this->db->insert($tbl_desc,$data))
				return true;
			else
			return false; 
	  
	   }
	   
	   function delMapDes($data){
		   
		   if(substr($data['sub_offered_id'], 0, 1)=='o'){ $tbl='old_subject_offered'; $tbl_desc='old_subject_offered_desc';}
			if(substr($data['sub_offered_id'], 0, 1)=='c'){ $tbl='cbcs_subject_offered'; $tbl_desc='cbcs_subject_offered_desc';}
		
		$this->db->delete($tbl_desc,array('sub_offered_id'=>substr($data['sub_offered_id'], 1),'desc_id'=>$data['desc_id']));
		return true;
		
	}
	
	function is_course_coordinator($session_year,$session,$emp_no,$sub_code){
		$sql="SELECT a.* FROM  cbcs_assign_course_coordinator a WHERE a.session_year=? AND a.`session`=? 
               AND a.co_emp_id=? AND a.sub_code=? ";
			$query = $this->db->query($sql,array($session_year,$session,$emp_no,$sub_code));
			if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
		
	}
	


} 
?>