<?php

class Cbcs_upload_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	

    function get_subjects($data) {
      

      
 
        $sql = "SELECT cgpa.*,v.*,cce.group_no as ee_group ,cce.subject_offered_id from
(SELECT A.*,B.*,NULL AS map_id,D.name AS cname,C.name AS bname,'cbcs' as rstatus
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." AND B.sub_type<>'Practical' AND B.contact_hours<>0 )v
 LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('c', CAST(v.sub_offered_id AS CHAR))
 AND    ( case  when  v.course_id='comm' then   cgpa.section=v.section else 1=1 end)   
 LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no 
 AND    ( case  when  v.course_id='comm' then   cce.section=v.section else 1=1 end)
 union 
 SELECT cgpa.*,u.*,cce.group_no as ee_group ,cce.subject_offered_id FROM(
 SELECT A.*,B.*,D.name AS cname,C.name AS bname,'old' as rstatus
FROM old_subject_offered_desc AS A
INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." AND B.sub_type<>'Practical' AND B.contact_hours<>0)u
LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('o', CAST(u.sub_offered_id AS CHAR))
    AND    ( case  when  u.course_id='comm' then   cgpa.section=u.section else 1=1 end)   

LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no  
AND    ( case  when  u.course_id='comm' then   cce.section=u.section else 1=1 end)   

 ";
        $query = $this->db->query($sql, array($data['emp_id'],$data['session'],$data['session_year'],$data['emp_id'],$data['session'],$data['session_year']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_prac_subjects($data) {

        $sql = "SELECT cgpa.*,v.*,cce.group_no as ee_group ,cce.subject_offered_id from
(SELECT A.*,B.*,NULL AS map_id,D.name AS cname,C.name AS bname,'cbcs' as rstatus
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." AND B.sub_type='Practical' AND B.contact_hours<>0 )v
 LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('c', CAST(v.sub_offered_id AS CHAR))
 AND    ( case  when  v.course_id='comm' then   cgpa.section=v.section else 1=1 end)   
 LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no 
 AND    ( case  when  v.course_id='comm' then   cce.section=v.section else 1=1 end)
 union 
 SELECT cgpa.*,u.*,cce.group_no as ee_group ,cce.subject_offered_id FROM(
 SELECT A.*,B.*,D.name AS cname,C.name AS bname,'old' as rstatus
FROM old_subject_offered_desc AS A
INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." AND B.sub_type='Practical' AND B.contact_hours<>0)u
LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('o', CAST(u.sub_offered_id AS CHAR))
    AND    ( case  when  u.course_id='comm' then   cgpa.section=u.section else 1=1 end)   

LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no  
AND    ( case  when  u.course_id='comm' then   cce.section=u.section else 1=1 end) ";
        $query = $this->db->query($sql, array($data['emp_id'],$data['session'],$data['session_year'],$data['emp_id'],$data['session'],$data['session_year']));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_student($data){
		//echo substr($data['sub_category'], 0,2);die();
          //echo '<pre>';       print_r($data);echo '</pre>'; die();
        if($data['rstatus']=='old'){$tbl=' old_stu_course ';}
        if($data['rstatus']=='cbcs'){ $tbl=' cbcs_stu_course ';}		 
		
		
		
      if($data['course_id']=='comm'){
        $con="  a.sub_category_cbcs_offered=?";
        $p=array($data['section'],$data['sub_code'],$data['session_year'],$data['session']);
      }
	  else if($data['course_id']=='online'){
		  
		  $con=" a.branch=?";
        $p=array($data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
      }
		else if($data['course_id']=='minor'){
			$con=" a.course=? and a.branch=?";
        $p=array($data['course_id'],$data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
			
		}	
		else if((substr($data['sub_category'], 0,2)=='OE') || (substr($data['sub_category'], 0,2)=='DE')){
			$con=" a.course=? and a.sub_offered_id=?";
        $p=array($data['course_id'],$data['sub_offered_id'],$data['sub_code'],$data['session_year'],$data['session']);
			
		}			
	  
	  else{
        $con=" a.course=? and a.branch=?";
        $p=array($data['course_id'],$data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
      }	  	  	

      $sql = "SELECT c.id, c.first_name,c.middle_name,c.last_name FROM ".$tbl."  a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE   ".$con." AND a.subject_code=? AND b.session_year=? AND b.`session`=? and 
b.hod_status='1' AND b.acad_status='1' 
ORDER BY c.id   	 $str_add " ;

        $query = $this->db->query($sql, $p);
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


    }
	
	
	
	

    function insert($data)
  {
    if($this->db->insert('cbcs_class_engaged',$data))
      //return TRUE;
                        return $this->db->insert_id();
    else
      return FALSE;
  }

    function insert_batch($data)
  {
    if($this->db->insert_batch('cbcs_absent_table',$data))
      return TRUE;
    else
      return FALSE;
  } 

  function get_absent_details($id,$group,$section,$cid){

	if($cid=='comm'){
		$sql = "SELECT  *  FROM cbcs_class_engaged WHERE subject_offered_id=? and  group_no=?    and section =?     ORDER BY id /*DESC LIMIT 1*/";
		 $query = $this->db->query($sql, array($id,$group,$section));
		
	}else{
		
		$sql = "SELECT  *  FROM cbcs_class_engaged WHERE subject_offered_id=? and  group_no=?   ORDER BY id ";
		 $query = $this->db->query($sql, array($id,$group));
	}
   
    //echo $this->db->last_query();

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


  }
  
    function get_absent_count($sub_map_id,$admn_no){
	//if($admn_no=='16je002574'){	print_r($sub_map_id); 
	
	$p=implode(',',$sub_map_id);
	
    $sql = "SELECT COUNT(*)AS cnt FROM cbcs_absent_table a WHERE a.class_engaged_id in ($p) AND a.admn_no=?";

//echo $sql;
        $query = $this->db->query($sql,array($admn_no));
		//echo $this->db->last_query();
		//die();}
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


  }

   function get_total_class($id,$section=null,$group=null){
	   if($group) $str_add= "  and  group_no='$group' ";
	   
 if($section){
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged WHERE subject_offered_id=?  and  section=? ".$str_add;
   $query = $this->db->query($sql, array($id,$section));
 }else{
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged WHERE subject_offered_id=? ".$str_add;
     $query = $this->db->query($sql, $id);
 }
 

           
        
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->count;
        } else {
            return false;
        }


  }
  
  
   function get_last_class($sub_offered_id,$emp_no,$date,$section){
	  if($section){
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged WHERE subject_offered_id=? and engaged_by=? and  section=? and date=?";
	   $query = $this->db->query($sql, array($sub_offered_id,$emp_no,$section,$date));
	 }else{
		 $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged WHERE subject_offered_id=? and engaged_by=? and date=?";
		 $query = $this->db->query($sql, array($sub_offered_id,$emp_no,$date));
	 }
	 
	 if ($this->db->affected_rows() > 0) {
		return $query->row()->count;
	} else {
		return false;
	}
  }
  
/*  function getstartgroup($subid, $groupno, $sessionid = null, $map = null) {

        if ($sessionid != null) {
            $q = "select group_start from cbcs_prac_group_attendance a where a.sub_id=? and a.group_no=? and a.session_id=? and a.map_id=?";
            $r = $this->db->query($q, array($subid, $groupno, $sessionid, $map));
        } else {
            $q = "select group_start from cbcs_prac_group_attendance a where a.sub_id=? and a.group_no=? and a.map_id=?";
            $r = $this->db->query($q, array($subid, $groupno, $map));
        }
        if ($r->num_rows() > 0) {
            return $r->row()->group_start;
        }
    }*/
  public function get_class($data)
	{
            //print_r($data); die();
                
		/*$sub_id=$data['sub_id'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();
		$tmp=$query->result();
		if(count($tmp)!=0)
			$map_id=$tmp[0]->map_id;
		else
			return $tmp; */
                  if(isset($data['map_id'])){      
		$this->db->select('semester,course_id,branch_id');
		$this->db->from('cbcs_subject_offered');
		$this->db->where('id',$data['map_id']);
                
		$query=$this->db->get();
               // echo $this->db->last_query(); die();
		return $query->result();
                  }
	}
        
        
      
     public function insert_into_class_engaged($engaged_by,$map_id,$date,$timestamp,$group_no,$class_no,$tot_class,$section='')
	{
           // echo $section; die();
            if($section){
                $query="INSERT INTO cbcs_class_engaged (engaged_by,subject_offered_id,date,timestamp,group_no,class_no,total_class,section) VALUES($engaged_by,'$map_id','$date','$timestamp',$group_no,$class_no,$tot_class,'$section')";
            }else{
		$query="INSERT INTO cbcs_class_engaged (engaged_by,subject_offered_id,date,timestamp,group_no,class_no,total_class) VALUES($engaged_by,'$map_id','$date','$timestamp',$group_no,$class_no,$tot_class)";
            }
            $this->db->query($query);
            // echo $this->db->last_query();
            return  $this->db->insert_id();
	}
	
	
	 public function get_id_class_engaged($engaged_by,$map_id,$date,$group,$section='')
	{
           // echo $section; die();
            if($section){
                $query="select id  from   cbcs_class_engaged  where  engaged_by='$engaged_by' and  subject_offered_id='$map_id' and  date='$date' and  group_no = '$group'  and  section='$section' ";
			
            }else{
		$query=" select id  from   cbcs_class_engaged  where  engaged_by='$engaged_by' and  subject_offered_id='$map_id' and  date='$date'   and  group_no = '$group' ";
            }
            $pr=$this->db->query($query);
            //echo $this->db->last_query(); die();
            return  $pr->row()->id;
	}
	
	
	
       /* public function insert_into_class_engaged_jrf($session_id,$map_id,$sub_id,$date,$timestamp,$group_no,$class_no,$tot_class,$section='')
	{
           // echo $section; die();
            if($section){
                $query="INSERT INTO cbcs_class_engaged_jrf (session_id,map_id,sub_id,date,timestamp,group_no,class_no,total_class,section) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no,$tot_class,'$section')";
            }else{
		$query="INSERT INTO class_engaged_jrf (session_id,map_id,sub_id,date,timestamp,group_no,class_no,total_class) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no,$tot_class)";
            }
            $this->db->query($query);
	}*/

        public function insert_into_absent_table($class_eng_id,$admn,$marked_by){                                       
                $query=" INSERT INTO cbcs_absent_table (class_engaged_id,admn_no,marked_by) VALUES($class_eng_id,'$admn',$marked_by) ";             
		
	    $this->db->query($query);
            //echo $this->db->last_query();
	}
  //====================================06-01-2020 Start==========================
  public function get_absent_status($class_eng_id,$admn,$marked_by){                                       
  
    $sql = "select * from  cbcs_absent_table where class_engaged_id=? and admn_no=? and marked_by=?";
    $query = $this->db->query($sql, array($class_eng_id,$admn,$marked_by));

      if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
  }
  //====================================06-01-2020 END=========================
	
	  function get_no_of_student($syear,$sess,$cid,$bid,$scode,$p1,$p2,$sec="",$rstatus,$sub_category,$sub_offered_id){
		  if($p1=='2' && $p2=='2'){
			  $con='AND a.hod_status<>? AND a.acad_status<>?';
			  
		  }
		  if($p1=='1' && $p2=='1'){
			  $con='AND a.hod_status=? AND a.acad_status=?';
			  
		  }
		    
		  
		  if($rstatus=='old'){$tbl=' old_stu_course ';}
		  if($rstatus=='cbcs'){ $tbl=' cbcs_stu_course ';}
		  
if($cid=='comm'){
	
	 $sql = "SELECT COUNT(a.admn_no) AS ctr
FROM reg_regular_form a
INNER JOIN ".$tbl." c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
WHERE a.session_year=? AND a.`session`=?
AND  c.subject_code=? ".$con." AND c.sub_category_cbcs_offered=? ";
       $query = $this->db->query($sql, array($syear,$sess,$scode,$p1,$p2,$sec));
	
}
else if($cid=='online'){
	$sql = "SELECT COUNT(a.admn_no) AS ctr
FROM reg_regular_form a
INNER JOIN ".$tbl." b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year=? AND a.`session`=? AND b.branch=? AND b.subject_code=? ".$con;
       $query = $this->db->query($sql, array($syear,$sess,$bid,$scode,$p1,$p2));
	
}
else if($cid=='minor'){
	$sql = "SELECT COUNT(a.admn_no) AS ctr
FROM reg_regular_form a
INNER JOIN ".$tbl." b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year=? AND a.`session`=? AND b.course=? and b.branch=? AND b.subject_code=? ".$con;
       $query = $this->db->query($sql, array($syear,$sess,$cid,$bid,$scode,$p1,$p2));
	
}
else if((substr($sub_category, 0,2)=='OE') || (substr($sub_category, 0,2)=='DE')){
			$sql = "SELECT COUNT(a.admn_no) AS ctr
FROM reg_regular_form a
INNER JOIN ".$tbl." b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year=? AND a.`session`=? AND b.course=? and b.sub_offered_id=? AND b.subject_code=? ".$con;
       $query = $this->db->query($sql, array($syear,$sess,$cid,$sub_offered_id,$scode,$p1,$p2));
			
		}	
else{
    $sql = "SELECT COUNT(a.admn_no) AS ctr
FROM reg_regular_form a
INNER JOIN ".$tbl." b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year=? AND a.`session`=? AND b.course=? 
AND b.branch=? AND b.subject_code=? ".$con;
       $query = $this->db->query($sql, array($syear,$sess,$cid,$bid,$scode,$p1,$p2));
}
		
		//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


  }
  
  function get_class_engaged($sub_offered_id,$empno,$group,$sec,$course_id){
	  
	  if($course_id=='comm'){
		$sql = "SELECT a.* FROM cbcs_class_engaged a WHERE a.subject_offered_id=? AND a.engaged_by=? and a.group_no=? and a.section=?";
		$query = $this->db->query($sql,array($sub_offered_id,$empno,$group,$sec));
	  }
	  else{
		  $sql = "SELECT a.* FROM cbcs_class_engaged a WHERE a.subject_offered_id=? AND a.engaged_by=? and a.group_no=?";
		$query = $this->db->query($sql,array($sub_offered_id,$empno,$group));
		  
	  }
		
		//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	  
	  
  }
  
  //Single
  

	  
    function get_student_single($data){
       //  echo '<pre>';       print_r($data);echo '</pre>'; die();
        if($data['rstatus']=='old'){$tbl=' old_stu_course ';}
        if($data['rstatus']=='cbcs'){ $tbl=' cbcs_stu_course ';}		 
		
		if($data['group_no']){
			 $str_add= " limit  ".($data['group_start']-1).",".$data['group_end']." ";
			
		}
		
      if($data['course_id']=='comm'){
        $con="  a.sub_category_cbcs_offered=?";
        $p=array($data['section'],$data['sub_code'],$data['session_year'],$data['session']);
      }
	  else if($data['course_id']=='online'){
		  
		  $con=" a.branch=?";
        $p=array($data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
      }	  
	  else if((substr($data['sub_category'], 0,2)=='OE') || (substr($data['sub_category'], 0,2)=='DE')){
			$con=" a.course=? and a.sub_offered_id=?";
			$p=array($data['course_id'],$data['sub_offered_id'],$data['sub_code'],$data['session_year'],$data['session']);
			
		}
	  else{
        $con=" a.course=? and a.branch=?";
        $p=array($data['course_id'],$data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
      }	  	  	

      $sql = "SELECT c.id, c.first_name,c.middle_name,c.last_name FROM ".$tbl."  a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE   ".$con." AND a.subject_code=? AND b.session_year=? AND b.`session`=? and 
b.hod_status='1' AND b.acad_status='1' 
ORDER BY c.id   	 $str_add " ;

        $query = $this->db->query($sql, $p);
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


    }

    function check_active_session_year($id){
      $sql = "SELECT a.* FROM mis_session_year a WHERE a.session_year=? " ;
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function check_active_session($id){
      $sql = "SELECT a.* FROM mis_session a WHERE a.session=? " ;
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

	  
	
	
}

?>