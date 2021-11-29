<?php

class Cbcs_upload_attendance_model_new extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	
 
 /*   function get_subjects($data) {
      
$sql = " SELECT o.* FROM(
SELECT p.*,
if(  !(p.sub_code=@sub_code && p.emp_no=@emp_no  && p.offered=@offered) ,   @rank,@rank:=@rank+1) AS newgrp_col,
@sub_code:=p.sub_code,@emp_no:=p.emp_no,@offered:=p.offered

FROM(
SELECT a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category,a.sub_group,b.section,a.sub_type, 'c' AS rstatus, NULL AS total_count,b.emp_no,'cbcs' AS 'offered'
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code UNION
SELECT a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category,a.sub_group,b.section, a.sub_type, 'o' AS rstatus, NULL AS total_count,b.emp_no,'aold' AS 'offered'
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code UNION (
SELECT t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'c' AS rstatus,t.total_count,t.emp_no,'distribution_cbcs' AS 'offered'
FROM (
SELECT a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code,a.group_no)t
INNER JOIN cbcs_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session
GROUP BY t.sub_code,t.group_no) UNION (
SELECT t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'o' AS rstatus,t.total_count,t.emp_no,'distribution_old' AS 'offered'
FROM (
SELECT a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code,a.group_no)t
INNER JOIN old_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session
GROUP BY t.sub_code)
)p,(SELECT @sub_code:='',@emp_no:='', @offered:='', @rank:=0 ) sqlvar
ORDER BY p.sub_code ,p.offered DESC LIMIT 100000)o
 group BY o.sub_code,  o.newgrp_col 

";


        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }*/


function get_subjects($data) {
      
$sql = " SELECT o.*
FROM(
SELECT p.*,CONCAT(p.offered,CAST(p.sub_group AS CHAR)),
 IF(!(p.sub_code=@sub_code && p.emp_no=@emp_no && p.offered=@offered), @rank,@rank:=@rank+1) AS newgrp_col, @sub_code:=p.sub_code,@emp_no:=p.emp_no,@offered:=p.offered
FROM(

SELECT q.* from(
SELECT a.course_id,a.branch_id, a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category,
a.sub_group,b.section,a.sub_type, 'c' AS rstatus, NULL AS total_count,b.emp_no,'cbcs' AS 'offered'
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code UNION 
SELECT a.course_id,a.branch_id, a.session_year,a.`session`,a.sub_name,a.sub_code,a.sub_category,a.sub_group,b.section, a.sub_type, 'o' AS rstatus, NULL AS total_count,b.emp_no,'aold' AS 'offered'
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code 
 


UNION (
SELECT t.course_id,t.branch_id,t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'c' AS rstatus,t.total_count,t.emp_no,'distribution_cbcs' AS 'offered'
FROM (
SELECT a.course_id,a.branch_id,a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code,a.group_no  )t
INNER JOIN cbcs_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session
GROUP BY t.sub_code,t.group_no

)

UNION  (
SELECT t.course_id,t.branch_id,t.session_year,t.`session`,t1.sub_name,t.sub_code,t1.sub_category, t.group_no, NULL AS section,t1.sub_type,'o' AS rstatus,t.total_count,t.emp_no,'distribution_old' AS 'offered'
FROM (
SELECT a.course_id,a.branch_id,a.session_year,a.`session`,a.sub_code,a.group_no,b.emp_no, SUM(a.total_count) AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=?
GROUP BY a.sub_code,a.group_no  )t
INNER JOIN old_subject_offered t1 ON t1.sub_code=t.sub_code AND t1.session_year=t.session_year AND t1.session=t.session

GROUP BY t.sub_code,t.group_no

)
)q

GROUP BY sub_code,sub_group
)p,(
SELECT @sub_code:='',@emp_no:='', @offered:='', @rank:=0) sqlvar
ORDER BY p.sub_code,p.offered DESC,CONCAT(p.offered,CAST(p.sub_group AS CHAR))
LIMIT 100000)o
GROUP BY o.sub_code, o.newgrp_col";


        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id'],$data['session_year'],$data['session'],$data['emp_id']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }





    function pre_count_student($syear,$sess,$courseid,$section,$sub_code){
        
         //echo $courseid;die();
        
        if (strpos($courseid, 'online') !== false) {
            $course_id='online';
        }
        else if (strpos($courseid, 'comm') !== false) {
            $course_id='comm';
        }
       
      
       
        if($course_id=='comm'){
            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM pre_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=? AND b.status='A' and a.sub_category_cbcs_offered=?
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1' and (a.remark2='1' or a.remark2='3') group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$section,$syear,$sess));
        } 
        else if($course_id=='online'){
            $x1=explode('c', $id);
          $x1=$x1[1];

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=?  AND b.status='A' and 
        c.hod_status='1' AND c.acad_status='1'
        AND a.session_year=? AND a.`session`=?  group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess));
        
        }
        
        
        else{

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM pre_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE  a.subject_code=?   AND b.status='A'
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1' and (a.remark2='1' or a.remark2='3') group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess));

        }
        
        

        //echo $this->db->last_query();    
        

        
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }else{
            return false;    
        }
    
    
  }

  //old
  
  function old_count_student($syear,$sess,$courseid,$section,$sub_code){
        
        if (strpos($courseid, 'online') !== false) {
            $course_id='online';
        }
        else if (strpos($courseid, 'comm') !== false) {
            $course_id='comm';
        }
       
       
       
        if($course_id=='comm'){
            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM old_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=? AND b.status='A' and a.sub_category_cbcs_offered=?
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1' group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$section,$syear,$sess));
        } 
        else if($course_id=='online'){
            $x1=explode('c', $id);
          $x1=$x1[1];

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=?  AND b.status='A' and 
        c.hod_status='1' AND c.acad_status='1'
        AND a.session_year=? AND a.`session`=?  group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess));
        
        }
        
        
        else{

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM old_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE  a.subject_code=?   AND b.status='A'
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1' group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess));

        }
        
        

        //echo $this->db->last_query();    
        

        
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }else{
            return false;    
        }
    
    
  }

function cbcs_old_student($syear,$sess,$courseid,$section,$sub_code){
        
        if (strpos($courseid, 'online') !== false) {
            $course_id='online';
        }
        else if (strpos($courseid, 'comm') !== false) {
            $course_id='comm';
        }
       
       
       
        if($course_id=='comm'){
            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=? AND b.status='A' and a.sub_category_cbcs_offered=?
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1'  group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$section,$syear,$sess));
        } 
        else if($course_id=='online'){
            $x1=explode('c', $id);
          $x1=$x1[1];

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=?  AND b.status='A' and 
        c.hod_status='1' AND c.acad_status='1'
        AND a.session_year=? AND a.`session`=?  group by a.admn_no union SELECT a.* FROM old_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE a.subject_code=?  AND b.status='A' and 
        c.hod_status='1' AND c.acad_status='1'
        AND a.session_year=? AND a.`session`=?  group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess,$sub_code,$syear,$sess));
        
        }
        
        
        else{

            $sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE  a.subject_code=?   AND b.status='A'
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1'  group by a.admn_no union SELECT a.* FROM old_stu_course a 
        INNER JOIN users b ON b.id=a.admn_no
        INNER JOIN reg_regular_form c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
        WHERE  a.subject_code=?   AND b.status='A'
        AND a.session_year=? AND a.`session`=? and 
        c.hod_status='1' AND c.acad_status='1'  group by a.admn_no)t";
        $query = $this->db->query($sql,array($sub_code,$syear,$sess,$sub_code,$syear,$sess));

        }
        
        

        //echo $this->db->last_query();    
        

        
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }else{
            return false;    
        }
    
    
  }

  function count_student_optional_tbl($session_year,$session,$emp_no,$sub_code,$group_no){
    $sql = "SELECT a.group_no,a.count_from,sum(a.count_to)AS count_to,sum(a.total_count)AS total_count
FROM cbcs_optional_mapping a
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=? AND a.`session`=? 
AND b.emp_no=? AND a.sub_code=? and a.group_no=?
GROUP BY a.sub_code";
        $query = $this->db->query($sql,array($session_year,$session,$emp_no,$sub_code,$group_no));

         if ($query->num_rows() > 0)
        {
            return $query->row();
        }else{
            return false;    
        }


  }

  function get_student($data){
    //echo substr($data['sub_category'], 0,2);die();
          //echo '<pre>';       print_r($data);echo '</pre>'; die();
        if($data['rstatus']=='o'){$tbl=' old_stu_course ';}
        if($data['rstatus']=='c'){ $tbl=' cbcs_stu_course ';}     
    

      $sql = "

SELECT c.id, c.first_name,c.middle_name,c.last_name
FROM ".$tbl." a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE b.session_year=? AND b.`session`=? AND a.subject_code=? AND b.hod_status='1' AND b.acad_status='1'
ORDER BY c.id " ;

        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['sub_code']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


    }

       function get_last_class($syear,$sess,$sub_code,$emp_no,$date,$section){
      if($section){
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=?and engaged_by=? and  section=? and date=?";
       $query = $this->db->query($sql, array($syear,$sess,$sub_code,$emp_no,$section,$date));
     }else{
         $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=?and engaged_by=? and  date=?";
         $query = $this->db->query($sql, array($syear,$sess,$sub_code,$emp_no,$date));
     }
     
     if ($this->db->affected_rows() > 0) {
        return $query->row()->count;
    } else {
        return false;
    }
  }

     function get_total_class($syear,$sess,$sub_code,$section=null,$group=null){

//echo $syear; echo $sess; echo $sub_code; echo $section; echo $group;die();

       if($group) $str_add= "  and  group_no='$group' ";
       
 if($section){
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=?  and  section=? ".$str_add;
   $query = $this->db->query($sql, array($syear,$sess,$sub_code,$section));
 }else{
     $sql = "SELECT  COUNT(*)+1 AS count FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=? ".$str_add;
     $query = $this->db->query($sql, array($syear,$sess,$sub_code,$section));
 }
 

           
        
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->count;
        } else {
            return false;
        }


  }

  public function insert_into_class_engaged($engaged_by,$syear,$sess,$sub_code,$date,$timestamp,$group_no,$class_no,$tot_class,$section='')
    {
           // echo $section; die();
            if($section){
                $query="INSERT INTO cbcs_class_engaged_course_wise (engaged_by,session_year,session,course_code,date,timestamp,group_no,class_no,total_class,section) VALUES('$engaged_by','$syear','$sess','$sub_code','$date','$timestamp',$group_no,$class_no,$tot_class,'$section')";
            }else{
        $query="INSERT INTO cbcs_class_engaged_course_wise (engaged_by,session_year,session,course_code,date,timestamp,group_no,class_no,total_class) VALUES('$engaged_by','$syear','$sess','$sub_code','$date','$timestamp',$group_no,$class_no,$tot_class)";
            }
            $this->db->query($query);
            // echo $this->db->last_query();
            return  $this->db->insert_id();
    }
    public function insert_into_absent_table($class_eng_id,$admn,$marked_by){                                       
                $query=" INSERT INTO cbcs_absent_table_course_wise (class_engaged_id,admn_no,marked_by) VALUES($class_eng_id,'$admn',$marked_by) ";             
        
        $this->db->query($query);
            //echo $this->db->last_query();
    }


  function get_absent_details($syear,$sess,$sub_code,$group,$section,$cid){

    if($cid=='comm'){
        $sql = "SELECT  *  FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=? and  group_no=?    and section =?     ORDER BY id /*DESC LIMIT 1*/";
         $query = $this->db->query($sql, array($syear,$sess,$sub_code,$group,$section));
        
    }else{
        
        $sql = "SELECT  *  FROM cbcs_class_engaged_course_wise WHERE session_year=? and session=? and course_code=? and  group_no=?   ORDER BY id ";
         $query = $this->db->query($sql, array($syear,$sess,$sub_code,$group));
    }
   
    //echo $this->db->last_query();

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


  }


      function get_absent_count($syear,$sess,$sub_code,$admn_no,$emp_no){
    
    $sql = "SELECT  COUNT(*)AS cnt from cbcs_absent_table_course_wise a
INNER JOIN cbcs_class_engaged_course_wise b ON b.id=a.class_engaged_id
WHERE b.session_year=? AND b.`session`=? AND b.course_code=?
AND a.admn_no=? AND a.marked_by=?";

//echo $sql;
        $query = $this->db->query($sql,array($syear,$sess,$sub_code,$admn_no,$emp_no));
        //echo $this->db->last_query();
        //die();}
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


  }



    function get_group_details($syear,$sess,$sub_code,$group_no){
   
         $sql = "SELECT t1.group_no,t1.total_count,FORMAT(FLOOR(t1.cumulative_sum),0)AS cumulative_sum FROM(
SELECT t.* ,(@csum := @csum + t.total_count) as cumulative_sum FROM(
SELECT a.group_no,SUM(a.total_count)AS total_count
FROM cbcs_optional_mapping a WHERE a.session_year=?
AND a.`session`=? AND a.sub_code=?
GROUP BY a.group_no
)t,(select @csum:=0)sqlvar)t1
WHERE t1.group_no=?" ;

        $query = $this->db->query($sql, array($syear,$sess,$sub_code,$group_no));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


    }


function fetch_group_rows($data){

    $sql = "SELECT a.* 
FROM cbcs_optional_mapping a 
INNER JOIN cbcs_optional_mapping_desc b ON b.map_id=a.id
WHERE a.session_year=?  AND a.`session`=?
AND a.sub_code=? AND b.emp_no=? AND a.group_no=?" ;

        $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['sub_code'],$data['emp_no'],$data['group_no']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }



}

function get_student_groupwise($session_year,$session,$sub_code,$course_id,$branch_id,$start1,$end){

    if($start1=='1'){
        $start=0;
    }else{
        $start=$start1-1;
    }

     $sql = "(SELECT c.id, c.first_name,c.middle_name,c.last_name 
FROM cbcs_stu_course a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE b.session_year=? AND b.`session`=? AND a.subject_code=? 
AND b.hod_status='1' AND b.acad_status='1'
AND b.course_id=? AND b.branch_id=?
ORDER BY c.id  LIMIT ?,?) union 
(SELECT c.id, c.first_name,c.middle_name,c.last_name 
FROM old_stu_course a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE b.session_year=? AND b.`session`=? AND a.subject_code=? 
AND b.hod_status='1' AND b.acad_status='1'
AND b.course_id=? AND b.branch_id=?
ORDER BY c.id  LIMIT ?,?)  " ;

        $query = $this->db->query($sql, array($session_year,$session,$sub_code,$course_id,$branch_id,(int)$start,(int)$end,$session_year,$session,$sub_code,$course_id,$branch_id,(int)$start,(int)$end));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


}

function get_student_course($session_year,$session,$sub_code){

    

     $sql = "SELECT GROUP_concat(DISTINCT(a.course_id) SEPARATOR ',') AS course_id FROM cbcs_subject_offered a WHERE a.session_year=?
AND a.`session`=? AND a.sub_code=? group by a.sub_code
union
SELECT GROUP_concat(DISTINCT(a.course_id) SEPARATOR ',') AS course_id FROM old_subject_offered a WHERE a.session_year=?
AND a.`session`=? AND a.sub_code=? group by a.sub_code" ;

        $query = $this->db->query($sql, array($session_year,$session,$sub_code,$session_year,$session,$sub_code));

        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


}

function  get_course_branch_semester($id,$syear,$sess){
	$sql = "SELECT a.* FROM reg_regular_form a
WHERE a.admn_no=? and a.session_year=? AND a.`session`=?
  AND a.hod_status='1' AND a.acad_status='1'" ;

        $query = $this->db->query($sql, array($id,$syear,$sess));

        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	
}
   
    
 
	  
	
	
}

?>