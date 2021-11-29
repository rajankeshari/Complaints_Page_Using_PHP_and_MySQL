<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of commonClassModel
 *
 * @author Ritu Raj <rituraj00@rediffmail.com>
 */
class Common_class_model extends CI_Model {
    private static $db;
       function __construct() {
        // Call the Model constructor
        parent::__construct();
        self::$db = &get_instance()->db;
         
    }
	
	public static function get_section_of_stu_static($admn_no,$session_yr){
	
	
	$sql= " select section from  stu_section_data ssd where ssd.session_year=? and ssd.admn_no=? ";
	  $query = self::$db->query($sql, array($session_yr,$admn_no));
        return ($query->num_rows() > 0 ? $query->row() : false);
    }
	
     public static function get_current_exam_static() {
      /*  $sql = " select  syear as session_year,  
                (case 
                  when (b.session='Monsoon'  and b.`exam_type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`exam_type`='S') then  '2'                   
                  when (b.session='Monsoon'  and b.`exam_type`='O') then  '1'
                  when b.session='Winter'  and b.`exam_type`='R' then  '4'
                  when b.session='Winter'  and b.`exam_type`='E' then  '5'
                  when b.session='Winter'  and b.`exam_type`='S' then  '6'
                  when b.session='Summer'  and b.`exam_type`='R' then  '7'
                  when b.session='Winter'  and b.`exam_type`='O' then  '4'                                                                                                                   
               end) as custom_exm_type 
               ,b.session,b.`exam_type` from exam_held_time b  order by b.session_year desc, custom_exm_type desc limit 1";*/
			   
		$sql="select  syear as session_year,b.session,b.`exam_type` from exam_held_time b  order by id desc limit 1 ";	   
        $query = self::$db->query($sql);
        return ($query->num_rows() > 0 ? $query->result() : false);
    }
    
  /*  function check_gpa_ogpa_upto_static($admn_no, $sem = 4) {
        $lst = '';
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst . "' ";
        }
        //echo  $s_replace .'<br/>' ;

        
        $sql = "SELECT z.*
FROM((
SELECT B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN a.core_cgpa else a.cgpa end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) else 'N/A' end) AS H_ogpa,a.hstatus 


FROM final_semwise_marks_foil a
WHERE a.admn_no=? AND a.course<>'MINOR' " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC)B  GROUP BY B.sem) 

UNION (

SELECT A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus 
 FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A GROUP BY A.sem_code) 

)z   GROUP BY z.sem order by sem    " ;
        $query = self::$db->query($sql, array($admn_no, $admn_no));
       // echo self::$db->last_query(); die();
        return ($query->num_rows() > 0 ? $query->result() : false);                   
    }*/
    
  
	
	// get unique list of  course sturctures taken by students  for given course,branch,semester,session,session yr
    public static function get_all_unique_course_sturcture($session_yr,$session,$course_id, $branch=null, $sem=null,$type=null){      
      //  echo 'crs'.$type;
	   switch($type){
		case 'regular' :case 'R'   :$table= ($session=='Summer'? 'reg_summer_form':'reg_regular_form'); break;
		case 'other'  :case 'O' :case 'spl'  :case 'S':case 'espl'  :case 'E'   : $table='reg_other_form'; break;
		
		default: $table='reg_regular_form'; break;
	   }
	   $rep=null;
	   if($session<>'Summer' &&  !($session=='Winter'&& ($type=='spl'||  $type=='S'))){
		   $rep= " and rg.semester= '".$sem."' ";	   
	    if($type=='other' ||  $type=='O' ||  $type=='spl'||  $type=='S'){
		   $rep= " and FIND_IN_SET('" . $sem . "' ,rg.semester) ";
	     }
	   }
	   
	     if($session=='Summer' && ( $type=='regular'||  $type=='R')){
			 $rep= " and rg.semester= '".$sem."' ";	   
		 }
	   
	   
	   
     if($course_id=='comm'){
		 
           $sql=" select distinct rg.course_aggr_id from  $table rg where  rg.session_year=? and rg.`session`=?  and rg.semester=?  and
                   rg.course_aggr_id like 'comm_comm%'  "; 
           	$secure_array = array( $session_yr,$session,($session=='Monsoon'?'1':($session=='Summer'?$sem:'2')) );
     }else{   
           $sql=" select distinct rg.course_aggr_id from  $table rg  where  rg.session_year=? and rg.`session`=? $rep   and
                  rg.course_id=? and  rg.branch_id=? ";
           	$secure_array = array($session_yr,$session,$course_id,$branch );
     }
     $query = self::$db->query($sql, $secure_array); //echo self::$db->last_query(); die();'<br/><br/><br/>'; 
    if ($query->num_rows() > 0)return $query->result(); else return 0;
	/*else {
		 if($course_id=='comm'){
		 
           $sql=" select distinct rg.course_aggr_id from  reg_other_form rg  where  rg.session_year=? and rg.`session`=? and rg.semester=?   and
                   rg.course_aggr_id like 'comm_comm%'  "; 
           	$secure_array = array( $session_yr,$session,($session=='Monsoon'?'1':($session=='Summer'?$sem:'2')) );
     }else{   
           $sql=" select distinct rg.course_aggr_id from  reg_other_form rg  where  rg.session_year=? and rg.`session`=? and rg.semester=?   and
                  rg.course_id=? and  rg.branch_id=? ";
           	$secure_array = array($session_yr,$session,$sem,$course_id,$branch );
     }
		
		    $query = self::$db->query($sql, $secure_array); //echo self::$db->last_query(); die();'<br/><br/><br/>'; 
             if ($query->num_rows() > 0)return $query->result();else return 0;         
	}
	
	*/
    
}
 /*function get_all_res_from_root_storage($admn_no){
        $sql=" set @var='".$admn_no."' " ;
           $query = self::$db->query($sql);

 $sql1="select  v.*, @core_status_var := v.core_status,@hon_status_var :=v.H_status,	  		     	
	@remark_str_core_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_core_var,  if(core_remark_str_var<>'',core_remark_str_var,null )) ))as Overallstatus_core,
        if(v.hon_tot_crdtpt='N/A','N/A',( @remark_str_hon_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_hon_var,  if(hon_remark_str_var<>'',hon_remark_str_var,null ),@remark_str_core_var ) )) ) )as Overallstatus_hons,						
			 ( @core_tot_pt_var := v.core_tot_crdtpt + @core_tot_pt_var) as  core_tot_crdtpt_sem   ,
          ( @core_tot_hr_var := v.core_tot_crdthr + @core_tot_hr_var ) as  core_tot_crdthr_sem  ,     
          
          
		     if( v.core_status ='PASS' , format(( @core_tot_pt_var/ @core_tot_hr_var  ),5) ,'INC' ) as  core_ogpa,								 							  

 
        if(v.hon_tot_crdtpt='N/A','N/A', (  @hon_tot_pt_var :=( case when v.semester=5 then (@core_tot_pt_var + (v.hon_tot_crdtpt-v.core_tot_crdtpt) ) else 
		   @hon_tot_pt_var + v.hon_tot_crdtpt   end )  ) ) as  hon_tot_crdtpt_sem ,		   		  
 
	
	     if(v.hon_tot_crdthr ='N/A','N/A',( @hon_tot_hr_var :=( case when v.semester=5 then( @core_tot_hr_var +(v.hon_tot_crdthr-v.core_tot_crdthr) ) else 
		  
		  @hon_tot_hr_var + v.hon_tot_crdthr  end))) as  hon_tot_crdthr_sem ,

    if(v.hon_tot_crdtpt='N/A','N/A',(   if( v.H_status ='PASS'   and v.core_status ='PASS' , format((@hon_tot_pt_var/ @hon_tot_hr_var ),5),'INC'  ) )) as  hon_ogpa       		 			 
from
(
select z.session_year,z.session,z.semester,z.`type`, sum(z.core_tot_crdthr) as core_tot_crdthr   ,sum(z.core_tot_crdtpt)  as core_tot_crdtpt ,
(case when z.H_only is not null then sum(z.hon_tot_crdthr) else 'N/A' end) as hon_tot_crdthr,
(case when z.H_only is not null then sum(z.hon_tot_crdtpt) else 'N/A' end) as hon_tot_crdtpt ,      
  FORMAT( (sum(z.core_tot_crdtpt)/sum(z.core_tot_crdthr)),5) as core_gpa, 
  (case when z.H_only is not null then format((sum(z.hon_tot_crdtpt)/sum(z.hon_tot_crdthr)),5) else 'N/A' end) as h_gpa,
  z.core_status ,
  (case when z.H_only is not null then	z.H_status2  else 'N/A' end) as H_status, 
    concat_ws( ',',IF ( ( z.core_status='FAIL'   ), concat(z.semester,'-','INC'),null))as  core_remark_str_var,
    (case when z.H_only is not null then 	(	concat_ws( ',',IF ( ( z.H_status2='FAIL'   ), concat(z.semester,'-','INC'),null) ) ) else 'N/A' end ) as  hon_remark_str_var,    
     z.core_fail_sub_list,     (case when z.H_only is not null then  z.H_fail_sub_list else 'N/A' end) as H_fail_sub_list    
 from(
(
 select x.session_year,x.session,x.sem as semester,x.`type` ,sum(x.core_crdthr) as core_tot_crdthr,sum(x.core_tot) as core_tot_crdtpt, sum(x.crdthr_with_H) as hon_tot_crdthr,sum(x.H_tot) as hon_tot_crdtpt, 
 x.count_core_failed_sub,x.core_fail_sub_list,x.count_H_failed_sub, x.H_fail_sub_list, x.core_status,x.H_status2 ,x.H_only
from(
SELECT 
b.session_year,b.session, CONVERT(cs.semester,UNSIGNED INTEGER) as sem,b.`type` , (case 
        when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)<>0  then
             (case 
                  when (b.session='Monsoon'  and b.`type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '2' 
                  when( b.session='Summer'  and b.`type`='R') then  '3' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '4'
                  when b.session='Winter'  and b.`type`='R' then  '5'
                  when b.session='Winter'  and b.`type`='S' then  '6'
                  when b.session='Summer'  and b.`type`='R' then  '7'
                  when b.session='Winter'  and b.`type`='O' then  '8'                                                                  
               end) 
               
         when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)=0  then
             (case 
                  when b.session='Winter'  and b.`type`='R' then  '1'
                  when b.session='Winter'  and b.`type`='S' then  '2'
                  when b.session='Summer'  and b.`type`='R' then  '3'
                  when b.session='Winter'  and b.`type`='O' then  '4'
                   when (b.session='Monsoon'  and b.`type`='R') then  '5' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '6' 
                  when( b.session='Summer'  and b.`type`='R') then  '7' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '8'                                                               
               end)          
 end) as custom_exm_type,
 b.subject_id ,sum(s.credit_hours) as custom_crdthr_sum,sum(s.credit_hours*gp.points) as custom_crdtpt_sum,s.credit_hours,gp.points,(s.credit_hours*gp.points) as crdtpt,a.grade
,SUM(IF (  (a.grade = 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')), 1, 0)) AS count_core_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')),s.subject_id, NULL)) SEPARATOR ',') AS core_fail_sub_list,
SUM(IF ((a.grade = 'F'and  (cs.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and cs.aggr_id  LIKE 'honour%'),s.subject_id, NULL)) SEPARATOR ',') AS H_fail_sub_list,
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F' ),(s.credit_hours*gp.points),null) )  as core_tot,	    
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'and  a.grade <> 'F' ), s.credit_hours,null) )  as core_crdthr,	     
 IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and a.grade = 'F') THEN 1  END),'FAIL',(IF((sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), (s.credit_hours*gp.points),null) )/sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), s.credit_hours,null) )  )<5,'FAIL','PASS')) )  as  core_status,
  SUM( IF ( ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and  a.grade <> 'F'), 1, 0))as  count_core_passed_sub,    
   ( sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F'),(s.credit_hours*gp.points),null) ) + sum( IF((cs.aggr_id LIKE 'honour%' and  a.grade <> 'F'),  (s.credit_hours*gp.points),null) ) 	)  as H_tot	,	
	(sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and  a.grade <> 'F' ), s.credit_hours,null)) +   sum( IF((cs.aggr_id LIKE 'honour%'and  a.grade <> 'F'    ), s.credit_hours,null) )  ) as crdthr_with_H,	 
     IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' and a.grade = 'F')or (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and a.grade = 'F' )) THEN 1 END),  (  IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' AND a.grade = 'F')) THEN 1 end  ),'FAIL', IF(COUNT(DISTINCT CASE WHEN (((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ) AND a.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ), IF(COUNT(DISTINCT CASE WHEN (cs.aggr_id LIKE 'honour%' and a.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2,
		 	 sum( IF((cs.aggr_id LIKE 'honour%'), s.credit_hours,null) )  as H_crdthr,		 
  sum( IF((cs.aggr_id LIKE 'honour%'), (s.credit_hours*gp.points),null) )  as H_only	 
FROM marks_subject_description a
join   marks_master b  on a.marks_master_id=b.id
join  course_structure cs on cs.id=b.subject_id
 and b.`status`='Y' and  a.admn_no=@var 
 join  subjects s on s.id=b.subject_id
 join grade_points gp on gp.grade=a.grade
 group by b.session_year,b.session,b.`type` order by  sem desc ,b.session_year desc, custom_exm_type desc  )x 
 group by semester) 
union all(
SELECT A.*
FROM (
SELECT  
a.ysession as session_year,a.wsms as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,a.examtype AS type,
sum(a.crdhrs) as core_tot_crdthr,sum(a.crpts)  as tot_crdtpt,'N/A' as hon_tot_crdthr,'N/A' as hon_tot_crdtpt,
  SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0)) AS count_core_failed_sub, group_concat(if((TRIM(a.crpts=0) OR TRIM(a.crpts)=''),a.subje_code, null))  as core_fail_sub_list,'N/A' as count_H_failed_sub,'N/A' as H_fail_sub_list,
 (case when SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0))>0 then  'FAIL' else 'PASS'  end )  as core_status,'N/A' as H_status2 ,null as H_only
FROM tabulation1 a
WHERE a.adm_no=@var and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,semester DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.semester )  
 )z group by z.semester 
)v ,
( select  @remark_str_core_var := '',  @core_tot_pt_var := 0,	@core_tot_hr_var :=0 ,   	@core_status_var := '',	   @remark_str_hon_var :='',@hon_tot_pt_var := 0,  @hon_tot_hr_var :=0 	,@hon_status_var := '') SQLVars ";


         $query = self::$db->query($sql1);
       //  echo self::$db->last_query(); die();
        return ($query->num_rows() > 0 ? $query->result() : false);
       
}
    
   */ 
    
    /*function check_gpa_ogpa_upto_static($admn_no, $sem=10 ) {
      //  echo $admn_no.','. $sem . $table.'<br/>';
        $table='final_semwise_marks_foil';
        $lst = '';$lst_old = '';
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
     
        $sql = "SELECT z.*
FROM((
SELECT 'newsys' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN a.core_cgpa else a.cgpa end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                           (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id


FROM " .$table. "    a
WHERE a.admn_no=? AND a.course<>'MINOR' " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.exam_type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC)B  GROUP BY B.sem) 

UNION (

SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id
 FROM tabulation1 a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A GROUP BY A.sem_code) 

)z   GROUP BY z.sem order by sem    " ;

        $query = self::$db->query($sql, array($admn_no, $admn_no));
     //echo self::$db->last_query(); die();
        return ($query->num_rows() > 0 ? $query->result() : false);                   
    }

    */
	
	
/*	
function get_all_res_from_root_storage($admn_no){
        $sql=" set @var='".$admn_no."' " ;
           $query = self::$db->query($sql);

           $table1= " marks_subject_description ";
           $table2= " marks_master ";
           $table3= " tabulation1 ";
 $sql1="select  v.*, @core_status_var := v.core_status,@hon_status_var :=v.H_status,	  		     	
	@remark_str_core_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_core_var,  if(core_remark_str_var<>'',core_remark_str_var,null )) ))as Overallstatus_core,
        if(v.hon_tot_crdtpt='N/A','N/A',( @remark_str_hon_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_hon_var,  if(hon_remark_str_var<>'',hon_remark_str_var,null ),@remark_str_core_var ) )) ) )as Overallstatus_hons,						
			 ( @core_tot_pt_var := v.core_tot_crdtpt + @core_tot_pt_var) as  core_tot_crdtpt_sem   ,
          ( @core_tot_hr_var := v.core_tot_crdthr + @core_tot_hr_var ) as  core_tot_crdthr_sem  ,     
          
          
		     if( v.core_status ='PASS' , format(( @core_tot_pt_var/ @core_tot_hr_var  ),5) ,'INC' ) as  core_ogpa,								 							  

 
        if(v.hon_tot_crdtpt='N/A','N/A', (  @hon_tot_pt_var :=( case when v.semester=5 then (@core_tot_pt_var + (v.hon_tot_crdtpt-v.core_tot_crdtpt) ) else 
		   @hon_tot_pt_var + v.hon_tot_crdtpt   end )  ) ) as  hon_tot_crdtpt_sem ,		   		  
 
	
	     if(v.hon_tot_crdthr ='N/A','N/A',( @hon_tot_hr_var :=( case when v.semester=5 then( @core_tot_hr_var +(v.hon_tot_crdthr-v.core_tot_crdthr) ) else 
		  
		  @hon_tot_hr_var + v.hon_tot_crdthr  end))) as  hon_tot_crdthr_sem ,

    if(v.hon_tot_crdtpt='N/A','N/A',(   if( v.H_status ='PASS'   and v.core_status ='PASS' , format((@hon_tot_pt_var/ @hon_tot_hr_var ),5),'INC'  ) )) as  hon_ogpa       		 			 
from
(
select z.session_year,z.session,z.semester,z.`type`, sum(z.core_tot_crdthr) as core_tot_crdthr   ,sum(z.core_tot_crdtpt)  as core_tot_crdtpt ,
(case when z.H_only is not null then sum(z.hon_tot_crdthr) else 'N/A' end) as hon_tot_crdthr,
(case when z.H_only is not null then sum(z.hon_tot_crdtpt) else 'N/A' end) as hon_tot_crdtpt ,      
  FORMAT( (sum(z.core_tot_crdtpt)/sum(z.core_tot_crdthr)),5) as core_gpa, 
  (case when z.H_only is not null then format((sum(z.hon_tot_crdtpt)/sum(z.hon_tot_crdthr)),5) else 'N/A' end) as h_gpa,
  z.core_status ,
  (case when z.H_only is not null then	z.H_status2  else 'N/A' end) as H_status, 
    concat_ws( ',',IF ( ( z.core_status='FAIL'   ), concat(z.semester,'-','INC'),null))as  core_remark_str_var,
    (case when z.H_only is not null then 	(	concat_ws( ',',IF ( ( z.H_status2='FAIL'   ), concat(z.semester,'-','INC'),null) ) ) else 'N/A' end ) as  hon_remark_str_var,    
     z.core_fail_sub_list,     (case when z.H_only is not null then  z.H_fail_sub_list else 'N/A' end) as H_fail_sub_list    
 from(
(
 select x.session_year,x.session,x.sem as semester,x.`type` ,sum(x.core_crdthr) as core_tot_crdthr,sum(x.core_tot) as core_tot_crdtpt, sum(x.crdthr_with_H) as hon_tot_crdthr,sum(x.H_tot) as hon_tot_crdtpt, 
 x.count_core_failed_sub,x.core_fail_sub_list,x.count_H_failed_sub, x.H_fail_sub_list, x.core_status,x.H_status2 ,x.H_only
from(
SELECT 
b.session_year,b.session, CONVERT(cs.semester,UNSIGNED INTEGER) as sem,b.`type` , (case 
        when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)<>0  then
             (case 
                  when (b.session='Monsoon'  and b.`type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '2' 
                  when( b.session='Summer'  and b.`type`='R') then  '3' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '4'
                  when b.session='Winter'  and b.`type`='R' then  '5'
                  when b.session='Winter'  and b.`type`='S' then  '6'
                  when b.session='Summer'  and b.`type`='R' then  '7'
                  when b.session='Winter'  and b.`type`='O' then  '8'                                                                  
               end) 
               
         when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)=0  then
             (case 
                  when b.session='Winter'  and b.`type`='R' then  '1'
                  when b.session='Winter'  and b.`type`='S' then  '2'
                  when b.session='Summer'  and b.`type`='R' then  '3'
                  when b.session='Winter'  and b.`type`='O' then  '4'
                   when (b.session='Monsoon'  and b.`type`='R') then  '5' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '6' 
                  when( b.session='Summer'  and b.`type`='R') then  '7' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '8'                                                               
               end)          
 end) as custom_exm_type,
 b.subject_id ,sum(s.credit_hours) as custom_crdthr_sum,sum(s.credit_hours*gp.points) as custom_crdtpt_sum,s.credit_hours,gp.points,(s.credit_hours*gp.points) as crdtpt,a.grade
,SUM(IF (  (a.grade = 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')), 1, 0)) AS count_core_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')),s.subject_id, NULL)) SEPARATOR ',') AS core_fail_sub_list,
SUM(IF ((a.grade = 'F'and  (cs.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and cs.aggr_id  LIKE 'honour%'),s.subject_id, NULL)) SEPARATOR ',') AS H_fail_sub_list,
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F' ),(s.credit_hours*gp.points),null) )  as core_tot,	    
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'and  a.grade <> 'F' ), s.credit_hours,null) )  as core_crdthr,	     
 IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and a.grade = 'F') THEN 1  END),'FAIL',(IF((sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), (s.credit_hours*gp.points),null) )/sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), s.credit_hours,null) )  )<5,'FAIL','PASS')) )  as  core_status,
  SUM( IF ( ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and  a.grade <> 'F'), 1, 0))as  count_core_passed_sub,    
   ( sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F'),(s.credit_hours*gp.points),null) ) + sum( IF((cs.aggr_id LIKE 'honour%' and  a.grade <> 'F'),  (s.credit_hours*gp.points),null) ) 	)  as H_tot	,	
	(sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and  a.grade <> 'F' ), s.credit_hours,null)) +   sum( IF((cs.aggr_id LIKE 'honour%'and  a.grade <> 'F'    ), s.credit_hours,null) )  ) as crdthr_with_H,	 
     IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' and a.grade = 'F')or (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and a.grade = 'F' )) THEN 1 END),  (  IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' AND a.grade = 'F')) THEN 1 end  ),'FAIL', IF(COUNT(DISTINCT CASE WHEN (((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ) AND a.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ), IF(COUNT(DISTINCT CASE WHEN (cs.aggr_id LIKE 'honour%' and a.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2,
		 	 sum( IF((cs.aggr_id LIKE 'honour%'), s.credit_hours,null) )  as H_crdthr,		 
  sum( IF((cs.aggr_id LIKE 'honour%'), (s.credit_hours*gp.points),null) )  as H_only	 
FROM ".$table1." a
join   ".$table2." b  on a.marks_master_id=b.id
join  course_structure cs on cs.id=b.subject_id
join subject_mapping sm on sm.map_id=b.sub_map_id
 and b.`status`='Y' and  a.admn_no=@var 
 join  subjects s on s.id=b.subject_id
 join grade_points gp on gp.grade=a.grade
 group by b.session_year,b.session,b.`type` order by  sem desc ,b.session_year desc, custom_exm_type desc  )x 
 group by semester) 
union all(
SELECT A.*
FROM (
SELECT  
a.ysession as session_year,a.wsms as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,a.examtype AS type,
sum(a.crdhrs) as core_tot_crdthr,sum(a.crpts)  as tot_crdtpt,'N/A' as hon_tot_crdthr,'N/A' as hon_tot_crdtpt,
  SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0)) AS count_core_failed_sub, group_concat(if((TRIM(a.crpts=0) OR TRIM(a.crpts)=''),a.subje_code, null))  as core_fail_sub_list,'N/A' as count_H_failed_sub,'N/A' as H_fail_sub_list,
 (case when SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0))>0 then  'FAIL' else 'PASS'  end )  as core_status,'N/A' as H_status2 ,null as H_only
FROM ".$table3." a
WHERE a.adm_no=@var and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,semester DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.semester )  
 )z group by z.semester 
)v ,
( select  @remark_str_core_var := '',  @core_tot_pt_var := 0,	@core_tot_hr_var :=0 ,   	@core_status_var := '',	   @remark_str_hon_var :='',@hon_tot_pt_var := 0,  @hon_tot_hr_var :=0 	,@hon_status_var := '') SQLVars ";


         $query = self::$db->query($sql1);
//         echo self::$db->last_query(); die();
  //      return ($query->num_rows() > 0 ? $query->result() : false);
         
             if($query->num_rows() > 0 ){
            return $query->result();
        }else{
           $table1= " alumni_marks_subject_description ";
           $table2= " marks_master ";
           $table3= " alumni_tabulation1 ";
             $sql2="select  v.*, @core_status_var := v.core_status,@hon_status_var :=v.H_status,	  		     	
	@remark_str_core_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_core_var,  if(core_remark_str_var<>'',core_remark_str_var,null )) ))as Overallstatus_core,
        if(v.hon_tot_crdtpt='N/A','N/A',( @remark_str_hon_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_hon_var,  if(hon_remark_str_var<>'',hon_remark_str_var,null ),@remark_str_core_var ) )) ) )as Overallstatus_hons,						
			 ( @core_tot_pt_var := v.core_tot_crdtpt + @core_tot_pt_var) as  core_tot_crdtpt_sem   ,
          ( @core_tot_hr_var := v.core_tot_crdthr + @core_tot_hr_var ) as  core_tot_crdthr_sem  ,     
          
          
		     if( v.core_status ='PASS' , format(( @core_tot_pt_var/ @core_tot_hr_var  ),5) ,'INC' ) as  core_ogpa,								 							  

 
        if(v.hon_tot_crdtpt='N/A','N/A', (  @hon_tot_pt_var :=( case when v.semester=5 then (@core_tot_pt_var + (v.hon_tot_crdtpt-v.core_tot_crdtpt) ) else 
		   @hon_tot_pt_var + v.hon_tot_crdtpt   end )  ) ) as  hon_tot_crdtpt_sem ,		   		  
 
	
	     if(v.hon_tot_crdthr ='N/A','N/A',( @hon_tot_hr_var :=( case when v.semester=5 then( @core_tot_hr_var +(v.hon_tot_crdthr-v.core_tot_crdthr) ) else 
		  
		  @hon_tot_hr_var + v.hon_tot_crdthr  end))) as  hon_tot_crdthr_sem ,

    if(v.hon_tot_crdtpt='N/A','N/A',(   if( v.H_status ='PASS'   and v.core_status ='PASS' , format((@hon_tot_pt_var/ @hon_tot_hr_var ),5),'INC'  ) )) as  hon_ogpa       		 			 
from
(
select z.session_year,z.session,z.semester,z.`type`, sum(z.core_tot_crdthr) as core_tot_crdthr   ,sum(z.core_tot_crdtpt)  as core_tot_crdtpt ,
(case when z.H_only is not null then sum(z.hon_tot_crdthr) else 'N/A' end) as hon_tot_crdthr,
(case when z.H_only is not null then sum(z.hon_tot_crdtpt) else 'N/A' end) as hon_tot_crdtpt ,      
  FORMAT( (sum(z.core_tot_crdtpt)/sum(z.core_tot_crdthr)),5) as core_gpa, 
  (case when z.H_only is not null then format((sum(z.hon_tot_crdtpt)/sum(z.hon_tot_crdthr)),5) else 'N/A' end) as h_gpa,
  z.core_status ,
  (case when z.H_only is not null then	z.H_status2  else 'N/A' end) as H_status, 
    concat_ws( ',',IF ( ( z.core_status='FAIL'   ), concat(z.semester,'-','INC'),null))as  core_remark_str_var,
    (case when z.H_only is not null then 	(	concat_ws( ',',IF ( ( z.H_status2='FAIL'   ), concat(z.semester,'-','INC'),null) ) ) else 'N/A' end ) as  hon_remark_str_var,    
     z.core_fail_sub_list,     (case when z.H_only is not null then  z.H_fail_sub_list else 'N/A' end) as H_fail_sub_list    
 from(
(
 select x.session_year,x.session,x.sem as semester,x.`type` ,sum(x.core_crdthr) as core_tot_crdthr,sum(x.core_tot) as core_tot_crdtpt, sum(x.crdthr_with_H) as hon_tot_crdthr,sum(x.H_tot) as hon_tot_crdtpt, 
 x.count_core_failed_sub,x.core_fail_sub_list,x.count_H_failed_sub, x.H_fail_sub_list, x.core_status,x.H_status2 ,x.H_only
from(
SELECT 
b.session_year,b.session, CONVERT(cs.semester,UNSIGNED INTEGER) as sem,b.`type` , (case 
        when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)<>0  then
             (case 
                  when (b.session='Monsoon'  and b.`type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '2' 
                  when( b.session='Summer'  and b.`type`='R') then  '3' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '4'
                  when b.session='Winter'  and b.`type`='R' then  '5'
                  when b.session='Winter'  and b.`type`='S' then  '6'
                  when b.session='Summer'  and b.`type`='R' then  '7'
                  when b.session='Winter'  and b.`type`='O' then  '8'                                                                  
               end) 
               
         when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)=0  then
             (case 
                  when b.session='Winter'  and b.`type`='R' then  '1'
                  when b.session='Winter'  and b.`type`='S' then  '2'
                  when b.session='Summer'  and b.`type`='R' then  '3'
                  when b.session='Winter'  and b.`type`='O' then  '4'
                   when (b.session='Monsoon'  and b.`type`='R') then  '5' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '6' 
                  when( b.session='Summer'  and b.`type`='R') then  '7' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '8'                                                               
               end)          
 end) as custom_exm_type,
 b.subject_id ,sum(s.credit_hours) as custom_crdthr_sum,sum(s.credit_hours*gp.points) as custom_crdtpt_sum,s.credit_hours,gp.points,(s.credit_hours*gp.points) as crdtpt,a.grade
,SUM(IF (  (a.grade = 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')), 1, 0)) AS count_core_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')),s.subject_id, NULL)) SEPARATOR ',') AS core_fail_sub_list,
SUM(IF ((a.grade = 'F'and  (cs.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and cs.aggr_id  LIKE 'honour%'),s.subject_id, NULL)) SEPARATOR ',') AS H_fail_sub_list,
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F' ),(s.credit_hours*gp.points),null) )  as core_tot,	    
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'and  a.grade <> 'F' ), s.credit_hours,null) )  as core_crdthr,	     
 IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and a.grade = 'F') THEN 1  END),'FAIL',(IF((sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), (s.credit_hours*gp.points),null) )/sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), s.credit_hours,null) )  )<5,'FAIL','PASS')) )  as  core_status,
  SUM( IF ( ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and  a.grade <> 'F'), 1, 0))as  count_core_passed_sub,    
   ( sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F'),(s.credit_hours*gp.points),null) ) + sum( IF((cs.aggr_id LIKE 'honour%' and  a.grade <> 'F'),  (s.credit_hours*gp.points),null) ) 	)  as H_tot	,	
	(sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and  a.grade <> 'F' ), s.credit_hours,null)) +   sum( IF((cs.aggr_id LIKE 'honour%'and  a.grade <> 'F'    ), s.credit_hours,null) )  ) as crdthr_with_H,	 
     IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' and a.grade = 'F')or (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and a.grade = 'F' )) THEN 1 END),  (  IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' AND a.grade = 'F')) THEN 1 end  ),'FAIL', IF(COUNT(DISTINCT CASE WHEN (((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ) AND a.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ), IF(COUNT(DISTINCT CASE WHEN (cs.aggr_id LIKE 'honour%' and a.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2,
		 	 sum( IF((cs.aggr_id LIKE 'honour%'), s.credit_hours,null) )  as H_crdthr,		 
  sum( IF((cs.aggr_id LIKE 'honour%'), (s.credit_hours*gp.points),null) )  as H_only	 
FROM ".$table1." a
join   ".$table2." b  on a.marks_master_id=b.id
join  course_structure cs on cs.id=b.subject_id
join subject_mapping sm on sm.map_id=b.sub_map_id
 and b.`status`='Y' and  a.admn_no=@var 
 join  subjects s on s.id=b.subject_id
 join grade_points gp on gp.grade=a.grade
 group by b.session_year,b.session,b.`type` order by  sem desc ,b.session_year desc, custom_exm_type desc  )x 
 group by semester) 
union all(
SELECT A.*
FROM (
SELECT  
a.ysession as session_year,a.wsms as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,a.examtype AS type,
sum(a.crdhrs) as core_tot_crdthr,sum(a.crpts)  as tot_crdtpt,'N/A' as hon_tot_crdthr,'N/A' as hon_tot_crdtpt,
  SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0)) AS count_core_failed_sub, group_concat(if((TRIM(a.crpts=0) OR TRIM(a.crpts)=''),a.subje_code, null))  as core_fail_sub_list,'N/A' as count_H_failed_sub,'N/A' as H_fail_sub_list,
 (case when SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0))>0 then  'FAIL' else 'PASS'  end )  as core_status,'N/A' as H_status2 ,null as H_only
FROM ".$table3." a
WHERE a.adm_no=@var and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,semester DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.semester )  
 )z group by z.semester 
)v ,
( select  @remark_str_core_var := '',  @core_tot_pt_var := 0,	@core_tot_hr_var :=0 ,   	@core_status_var := '',	   @remark_str_hon_var :='',@hon_tot_pt_var := 0,  @hon_tot_hr_var :=0 	,@hon_status_var := '') SQLVars ";
  $query2 = self::$db->query($sql2);
  return ($query2->num_rows() > 0 ? $query2->result() : false);
        }
         
       
}
    
    */
	
	
	function get_all_res_from_root_storage($admn_no){
        $sql=" set @var='".$admn_no."' " ;
           $query = self::$db->query($sql);

           $table1= " marks_subject_description ";
           $table2= " marks_master ";
           $table3= " tabulation1 ";
 $sql1="select  v.*, @core_status_var := v.core_status,@hon_status_var :=v.H_status,	  		     	
	@remark_str_core_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_core_var,  if(core_remark_str_var<>'',core_remark_str_var,null )) ))as Overallstatus_core,
        if(v.hon_tot_crdtpt='N/A','N/A',( @remark_str_hon_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_hon_var,  if(hon_remark_str_var<>'',hon_remark_str_var,null ),@remark_str_core_var ) )) ) )as Overallstatus_hons,						
	      ( @core_tot_pt_var := v.core_tot_crdtpt + @core_tot_pt_var) as  core_tot_crdtpt_sem   ,
          ( @core_tot_hr_var := v.core_tot_crdthr + @core_tot_hr_var ) as  core_tot_crdthr_sem  ,     
          
          
		     if( v.core_status ='PASS' , format(( @core_tot_pt_var/ @core_tot_hr_var  ),5) ,'INC' ) as  core_ogpa,								 							  

 
        if(v.hon_tot_crdtpt='N/A','N/A', (  @hon_tot_pt_var :=( case when v.semester=5 then (@core_tot_pt_var + (v.hon_tot_crdtpt-v.core_tot_crdtpt) ) else 
		   @hon_tot_pt_var + v.hon_tot_crdtpt   end )  ) ) as  hon_tot_crdtpt_sem ,		   		  
 
	
	     if(v.hon_tot_crdthr ='N/A','N/A',( @hon_tot_hr_var :=( case when v.semester=5 then( @core_tot_hr_var +(v.hon_tot_crdthr-v.core_tot_crdthr) ) else 
		  
		  @hon_tot_hr_var + v.hon_tot_crdthr  end))) as  hon_tot_crdthr_sem ,

    if(v.hon_tot_crdtpt='N/A','N/A',(   if( v.H_status ='PASS'   and v.core_status ='PASS' , format((@hon_tot_pt_var/ @hon_tot_hr_var ),5),'INC'  ) )) as  hon_ogpa       		 			 
from
(
select z.session_year,z.session,z.semester,z.`type`, sum(z.core_tot_crdthr) as core_tot_crdthr   ,sum(z.core_tot_crdtpt)  as core_tot_crdtpt ,
(case when z.H_only is not null then z.hon_tot_crdthr else 'N/A' end) as hon_tot_crdthr,
(case when z.H_only is not null then sum(z.hon_tot_crdtpt) else 'N/A' end) as hon_tot_crdtpt ,      
  FORMAT( (sum(z.core_tot_crdtpt)/sum(z.core_tot_crdthr)),5) as core_gpa, 
  (case when z.H_only is not null then format((sum(z.hon_tot_crdtpt)/sum(z.hon_tot_crdthr)),5) else 'N/A' end) as h_gpa,            
  (case when (SUM(z.core_tot_crdtpt) / sum(z.core_tot_crdthr))<5  then 'FAIL' else	z.core_status end) as core_status,					
  (CASE
       WHEN z.H_only IS NOT NULL THEN 	if((sum(core_tot_crdtpt )/sum(core_tot_crdthr))<5 or (sum(hon_tot_crdtpt) /sum(hon_tot_crdthr))<5  , 'FAIL' ,	z.H_status2 )
         ELSE 'N/A'
        END) AS H_status,            
            
       CONCAT_WS(',', IF((		(	Case when (sum(core_tot_crdtpt) /sum(core_tot_crdthr))<5  then 'FAIL' else z.core_status end) = 'FAIL'), CONCAT(z.semester, '-', 'INC'), NULL)) AS core_remark_str_var,
            (CASE
                WHEN z.H_only IS NOT NULL THEN (CONCAT_WS(',', IF((( Case when( (sum(core_tot_crdtpt) /sum(core_tot_crdthr))<5 or (sum(hon_tot_crdtpt) /sum(hon_tot_crdthr))<5 ) then 'FAIL' else	z.H_status2 end) = 'FAIL'), CONCAT(z.semester, '-', 'INC'), NULL)))
                ELSE 'N/A'
            END) AS hon_remark_str_var,  
     z.core_fail_sub_list,     (case when z.H_only is not null then  z.H_fail_sub_list else 'N/A' end) as H_fail_sub_list    
 from(
(
 select x.session_year,x.session,x.sem as semester,x.`type` ,sum(x.core_crdthr) as core_tot_crdthr,sum(x.core_tot) as core_tot_crdtpt, sum(x.crdthr_with_H) as hon_tot_crdthr,sum(x.H_tot) as hon_tot_crdtpt, 
 x.count_core_failed_sub,x.core_fail_sub_list,x.count_H_failed_sub, x.H_fail_sub_list, x.core_status,x.H_status2 ,x.H_only,2 as cc 
from(
SELECT 
b.session_year,b.session, CONVERT(cs.semester,UNSIGNED INTEGER) as sem,b.`type` , /*(case 
        when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)<>0  then
             (case 
                  when (b.session='Monsoon'  and b.`type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '2' 
                  when( b.session='Summer'  and b.`type`='R') then  '3' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '4'
                  when b.session='Winter'  and b.`type`='R' then  '5'
                  when b.session='Winter'  and b.`type`='S' then  '6'
                  when b.session='Summer'  and b.`type`='R' then  '7'
                  when b.session='Winter'  and b.`type`='O' then  '8'                                                                  
               end) 
               
         when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)=0  then
             (case 
                  when b.session='Winter'  and b.`type`='R' then  '1'
                  when b.session='Winter'  and b.`type`='S' then  '2'
                  when b.session='Summer'  and b.`type`='R' then  '3'
                  when b.session='Winter'  and b.`type`='O' then  '4'
                   when (b.session='Monsoon'  and b.`type`='R') then  '5' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '6' 
                  when( b.session='Summer'  and b.`type`='R') then  '7' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '8'                                                               
               end)          
 end) as custom_exm_type,*/
 b.subject_id ,sum(s.credit_hours) as custom_crdthr_sum,sum(s.credit_hours*gp.points) as custom_crdtpt_sum,s.credit_hours,gp.points,(s.credit_hours*gp.points) as crdtpt,a.grade
,SUM(IF (  (a.grade = 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')), 1, 0)) AS count_core_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')),s.subject_id, NULL)) SEPARATOR ',') AS core_fail_sub_list,
SUM(IF ((a.grade = 'F'and  (cs.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and cs.aggr_id  LIKE 'honour%'),s.subject_id, NULL)) SEPARATOR ',') AS H_fail_sub_list,
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F' ),(s.credit_hours*gp.points),null) )  as core_tot,	    
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'and  a.grade <> 'F' ), s.credit_hours,null) )  as core_crdthr,	     
 IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and a.grade = 'F') THEN 1  END),'FAIL',/*,(IF((sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), (s.credit_hours*gp.points),null) )/sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), s.credit_hours,null) )  )<5,'FAIL','PASS'))*/'PASS' )  as  core_status,
  SUM( IF ( ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and  a.grade <> 'F'), 1, 0))as  count_core_passed_sub,    
   ( sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F'),(s.credit_hours*gp.points),null) ) + sum( IF((cs.aggr_id LIKE 'honour%' and  a.grade <> 'F'),  (s.credit_hours*gp.points),null) ) 	)  as H_tot	,	
	(sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and  a.grade <> 'F' ), s.credit_hours,null)) +   sum( IF((cs.aggr_id LIKE 'honour%'and  a.grade <> 'F'    ), s.credit_hours,null) )  ) as crdthr_with_H,	 
     IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' and a.grade = 'F')or (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and a.grade = 'F' )) THEN 1 END),  (  IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' AND a.grade = 'F')) THEN 1 end  ),'FAIL', IF(COUNT(DISTINCT CASE WHEN (((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ) AND a.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ), IF(COUNT(DISTINCT CASE WHEN (cs.aggr_id LIKE 'honour%' and a.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2,
		 	 sum( IF((cs.aggr_id LIKE 'honour%'), s.credit_hours,null) )  as H_crdthr,		 
  sum( IF((cs.aggr_id LIKE 'honour%'), (s.credit_hours*gp.points),null) )  as H_only	 
FROM ".$table1." a
join   ".$table2." b  on a.marks_master_id=b.id
join  course_structure cs on cs.id=b.subject_id
 join subject_mapping sm on sm.map_id=b.sub_map_id
 and b.`status`='Y' and  a.admn_no=@var 
 join  subjects s on s.id=b.subject_id
 join grade_points gp on gp.grade=a.grade
 group by b.session_year,b.session,b.`type` order by  sem desc ,b.session_year desc/*, custom_exm_type*/ ,b.id desc  )x 
 group by semester) 
union all(
SELECT A.*
FROM (
SELECT  
a.ysession as session_year,a.wsms as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,a.examtype AS type,
  SUM(IF((TRIM(a.crpts = 0) OR TRIM(a.crpts) = ''), 0,a.crdhrs )) as core_tot_crdthr,sum(a.crpts)  as tot_crdtpt,'N/A' as hon_tot_crdthr,'N/A' as hon_tot_crdtpt,
  SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0)) AS count_core_failed_sub, group_concat(if((TRIM(a.crpts=0) OR TRIM(a.crpts)=''),a.subje_code, null))  as core_fail_sub_list,'N/A' as count_H_failed_sub,'N/A' as H_fail_sub_list,
 (case when SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0))>0 then  'FAIL' else 'PASS'  end )  as core_status,'N/A' as H_status2 ,null as H_only,1 as cc
FROM ".$table3." a
WHERE a.adm_no=@var and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,semester DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.semester )     order by semester desc, cc desc
 )z group by z.semester 
)v ,
( select  @remark_str_core_var := '',  @core_tot_pt_var := 0,	@core_tot_hr_var :=0 ,   	@core_status_var := '',	   @remark_str_hon_var :='',@hon_tot_pt_var := 0,  @hon_tot_hr_var :=0 	,@hon_status_var := '') SQLVars ";


         $query = self::$db->query($sql1);
//        echo self::$db->last_query(); die();
  //      return ($query->num_rows() > 0 ? $query->result() : false);
         
             if($query->num_rows() > 0 ){
            return $query->result();
        }else{
           $table1= " alumni_marks_subject_description ";
           $table2= " marks_master ";
           $table3= " alumni_tabulation1 ";
         $sql2="select  v.*, @core_status_var := v.core_status,@hon_status_var :=v.H_status,	  		     	
	@remark_str_core_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_core_var,  if(core_remark_str_var<>'',core_remark_str_var,null )) ))as Overallstatus_core,
        if(v.hon_tot_crdtpt='N/A','N/A',( @remark_str_hon_var :=TRIM(LEADING ',' from (concat_ws(',' , @remark_str_hon_var,  if(hon_remark_str_var<>'',hon_remark_str_var,null ),@remark_str_core_var ) )) ) )as Overallstatus_hons,						
	      ( @core_tot_pt_var := v.core_tot_crdtpt + @core_tot_pt_var) as  core_tot_crdtpt_sem   ,
          ( @core_tot_hr_var := v.core_tot_crdthr + @core_tot_hr_var ) as  core_tot_crdthr_sem  ,     
          
          
		     if( v.core_status ='PASS' , format(( @core_tot_pt_var/ @core_tot_hr_var  ),5) ,'INC' ) as  core_ogpa,								 							  

 
        if(v.hon_tot_crdtpt='N/A','N/A', (  @hon_tot_pt_var :=( case when v.semester=5 then (@core_tot_pt_var + (v.hon_tot_crdtpt-v.core_tot_crdtpt) ) else 
		   @hon_tot_pt_var + v.hon_tot_crdtpt   end )  ) ) as  hon_tot_crdtpt_sem ,		   		  
 
	
	     if(v.hon_tot_crdthr ='N/A','N/A',( @hon_tot_hr_var :=( case when v.semester=5 then( @core_tot_hr_var +(v.hon_tot_crdthr-v.core_tot_crdthr) ) else 
		  
		  @hon_tot_hr_var + v.hon_tot_crdthr  end))) as  hon_tot_crdthr_sem ,

    if(v.hon_tot_crdtpt='N/A','N/A',(   if( v.H_status ='PASS'   and v.core_status ='PASS' , format((@hon_tot_pt_var/ @hon_tot_hr_var ),5),'INC'  ) )) as  hon_ogpa       		 			 
from
(
select z.session_year,z.session,z.semester,z.`type`, sum(z.core_tot_crdthr) as core_tot_crdthr   ,sum(z.core_tot_crdtpt)  as core_tot_crdtpt ,
(case when z.H_only is not null then z.hon_tot_crdthr else 'N/A' end) as hon_tot_crdthr,
(case when z.H_only is not null then sum(z.hon_tot_crdtpt) else 'N/A' end) as hon_tot_crdtpt ,      
  FORMAT( (sum(z.core_tot_crdtpt)/sum(z.core_tot_crdthr)),5) as core_gpa, 
  (case when z.H_only is not null then format((sum(z.hon_tot_crdtpt)/sum(z.hon_tot_crdthr)),5) else 'N/A' end) as h_gpa,            
  (case when (SUM(z.core_tot_crdtpt) / sum(z.core_tot_crdthr))<5  then 'FAIL' else	z.core_status end) as core_status,					
  (CASE
       WHEN z.H_only IS NOT NULL THEN 	if((sum(core_tot_crdtpt )/sum(core_tot_crdthr))<5 or (sum(hon_tot_crdtpt) /sum(hon_tot_crdthr))<5  , 'FAIL' ,	z.H_status2 )
         ELSE 'N/A'
        END) AS H_status,            
            
       CONCAT_WS(',', IF((		(	Case when (sum(core_tot_crdtpt) /sum(core_tot_crdthr))<5  then 'FAIL' else z.core_status end) = 'FAIL'), CONCAT(z.semester, '-', 'INC'), NULL)) AS core_remark_str_var,
            (CASE
                WHEN z.H_only IS NOT NULL THEN (CONCAT_WS(',', IF((( Case when( (sum(core_tot_crdtpt) /sum(core_tot_crdthr))<5 or (sum(hon_tot_crdtpt) /sum(hon_tot_crdthr))<5 ) then 'FAIL' else	z.H_status2 end) = 'FAIL'), CONCAT(z.semester, '-', 'INC'), NULL)))
                ELSE 'N/A'
            END) AS hon_remark_str_var,  
     z.core_fail_sub_list,     (case when z.H_only is not null then  z.H_fail_sub_list else 'N/A' end) as H_fail_sub_list    
 from(
(
 select x.session_year,x.session,x.sem as semester,x.`type` ,sum(x.core_crdthr) as core_tot_crdthr,sum(x.core_tot) as core_tot_crdtpt, sum(x.crdthr_with_H) as hon_tot_crdthr,sum(x.H_tot) as hon_tot_crdtpt, 
 x.count_core_failed_sub,x.core_fail_sub_list,x.count_H_failed_sub, x.H_fail_sub_list, x.core_status,x.H_status2 ,x.H_only,2 as cc 
from(
SELECT 
b.session_year,b.session, CONVERT(cs.semester,UNSIGNED INTEGER) as sem,b.`type` , /*(case 
        when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)<>0  then
             (case 
                  when (b.session='Monsoon'  and b.`type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '2' 
                  when( b.session='Summer'  and b.`type`='R') then  '3' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '4'
                  when b.session='Winter'  and b.`type`='R' then  '5'
                  when b.session='Winter'  and b.`type`='S' then  '6'
                  when b.session='Summer'  and b.`type`='R' then  '7'
                  when b.session='Winter'  and b.`type`='O' then  '8'                                                                  
               end) 
               
         when mod( if (cs.semester like '%_%',left(cs.semester,1),cs.semester )  ,2)=0  then
             (case 
                  when b.session='Winter'  and b.`type`='R' then  '1'
                  when b.session='Winter'  and b.`type`='S' then  '2'
                  when b.session='Summer'  and b.`type`='R' then  '3'
                  when b.session='Winter'  and b.`type`='O' then  '4'
                   when (b.session='Monsoon'  and b.`type`='R') then  '5' 
                  when (b.session='Monsoon'  and b.`type`='S') then  '6' 
                  when( b.session='Summer'  and b.`type`='R') then  '7' 
                  when (b.session='Monsoon'  and b.`type`='O') then  '8'                                                               
               end)          
 end) as custom_exm_type,*/
 b.subject_id ,sum(s.credit_hours) as custom_crdthr_sum,sum(s.credit_hours*gp.points) as custom_crdtpt_sum,s.credit_hours,gp.points,(s.credit_hours*gp.points) as crdtpt,a.grade
,SUM(IF (  (a.grade = 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')), 1, 0)) AS count_core_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%')),s.subject_id, NULL)) SEPARATOR ',') AS core_fail_sub_list,
SUM(IF ((a.grade = 'F'and  (cs.aggr_id like 'honour%')), 1, 0)) AS count_H_failed_sub,
GROUP_CONCAT((IF((a.grade= 'F' and cs.aggr_id  LIKE 'honour%'),s.subject_id, NULL)) SEPARATOR ',') AS H_fail_sub_list,
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F' ),(s.credit_hours*gp.points),null) )  as core_tot,	    
  sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'and  a.grade <> 'F' ), s.credit_hours,null) )  as core_crdthr,	     
 IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and a.grade = 'F') THEN 1  END),'FAIL',/*,(IF((sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), (s.credit_hours*gp.points),null) )/sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ), s.credit_hours,null) )  )<5,'FAIL','PASS'))*/'PASS' )  as  core_status,
  SUM( IF ( ((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' )  and  a.grade <> 'F'), 1, 0))as  count_core_passed_sub,    
   ( sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' and  a.grade <> 'F'),(s.credit_hours*gp.points),null) ) + sum( IF((cs.aggr_id LIKE 'honour%' and  a.grade <> 'F'),  (s.credit_hours*gp.points),null) ) 	)  as H_tot	,	
	(sum( IF((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and  a.grade <> 'F' ), s.credit_hours,null)) +   sum( IF((cs.aggr_id LIKE 'honour%'and  a.grade <> 'F'    ), s.credit_hours,null) )  ) as crdthr_with_H,	 
     IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' and a.grade = 'F')or (cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%'  and a.grade = 'F' )) THEN 1 END),  (  IF(COUNT(DISTINCT CASE WHEN ((cs.aggr_id LIKE 'honour%' AND a.grade = 'F')) THEN 1 end  ),'FAIL', IF(COUNT(DISTINCT CASE WHEN (((cs.aggr_id NOT LIKE 'honour%' and cs.aggr_id NOT LIKE 'minor%' ) AND a.grade = 'F')) THEN 1 end  ) ,'FAIL','N/A' )  ) ), IF(COUNT(DISTINCT CASE WHEN (cs.aggr_id LIKE 'honour%' and a.grade <> 'F') THEN 1 END),'PASS','N/A') ) as H_status2,
		 	 sum( IF((cs.aggr_id LIKE 'honour%'), s.credit_hours,null) )  as H_crdthr,		 
  sum( IF((cs.aggr_id LIKE 'honour%'), (s.credit_hours*gp.points),null) )  as H_only	 
FROM ".$table1." a
join   ".$table2." b  on a.marks_master_id=b.id
join  course_structure cs on cs.id=b.subject_id
 join subject_mapping sm on sm.map_id=b.sub_map_id
 and b.`status`='Y' and  a.admn_no=@var 
 join  subjects s on s.id=b.subject_id
 join grade_points gp on gp.grade=a.grade
 group by b.session_year,b.session,b.`type` order by  sem desc ,b.session_year desc/*, custom_exm_type*/ ,b.id desc  )x 
 group by semester) 
union all(
SELECT A.*
FROM (
SELECT  
a.ysession as session_year,a.wsms as session ,CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester ,a.examtype AS type,
  SUM(IF((TRIM(a.crpts = 0) OR TRIM(a.crpts) = ''), 0,a.crdhrs )) as core_tot_crdthr,sum(a.crpts)  as tot_crdtpt,'N/A' as hon_tot_crdthr,'N/A' as hon_tot_crdtpt,
  SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0)) AS count_core_failed_sub, group_concat(if((TRIM(a.crpts=0) OR TRIM(a.crpts)=''),a.subje_code, null))  as core_fail_sub_list,'N/A' as count_H_failed_sub,'N/A' as H_fail_sub_list,
 (case when SUM(IF ((TRIM(a.crpts=0) OR TRIM(a.crpts)=''), 1, 0))>0 then  'FAIL' else 'PASS'  end )  as core_status,'N/A' as H_status2 ,null as H_only,1 as cc
FROM ".$table3." a
WHERE a.adm_no=@var and a.sem_code not like 'PREP%'
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,semester DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.semester )     order by semester desc, cc desc
 )z group by z.semester 
)v ,
( select  @remark_str_core_var := '',  @core_tot_pt_var := 0,	@core_tot_hr_var :=0 ,   	@core_status_var := '',	   @remark_str_hon_var :='',@hon_tot_pt_var := 0,  @hon_tot_hr_var :=0 	,@hon_status_var := '') SQLVars ";

  $query2 = self::$db->query($sql2);
  return ($query2->num_rows() > 0 ? $query2->result() : false);
        }
         
       
}
	
    
    function check_gpa_ogpa_upto_static($admn_no, $sem=10,$start_sem=null,$onlyminor=null ) {
       // echo $admn_no.','. $sem . $table.'<br/>';
	 if($onlyminor=='Y') 
	$minor_str=  " AND upper(a.course)='MINOR' ";
	 else
	  $minor_str=  " AND a.course<>'MINOR' ";
  
        $table='final_semwise_marks_foil';
        $table2='tabulation1';
        $lst = '';$lst_old = '';
		if($sem){
				
        for ($i = $sem; $i >= ($start_sem<>null?$start_sem:1); $i--) {
            $lst.=$i . ($i ==  ($start_sem<>null?$start_sem:1) ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i ==  ($start_sem<>null?$start_sem:1) ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		}
        $sql = "SELECT z.*
FROM((
SELECT 'newsys' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type,a.type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN  FORMAT(a.core_cgpa,5) else  FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                           (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id


FROM " .$table. "    a
WHERE a.admn_no=?  $minor_str " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC)B  GROUP BY B.sem ,IF( B.session_yr>='2019-2020',B.session_yr, NULL), IF(B.session_yr>='2019-2020',B.session, NULL)) 

UNION all(

SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type , a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id
 FROM " .$table2. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A GROUP BY A.sem_code) /*order by sem desc*/
order by sem, core_status desc
)z   GROUP BY z.sem  ,IF( z.session_yr>='2019-2020',session_yr,null),IF( z.session_yr>='2019-2020',z.session_yr, NULL), IF(z.session_yr>='2019-2020',z.session, NULL)   
   /*ORDER BY   sem ,IF( z.session_yr>='2019-2020',session_yr,null), IF( z.session_yr>='2019-2020',(case when session='Monsoon' then '1' when session='Winter' then '2' when  session='Summer' then '3' end ),NULL)*/
ORDER BY z.session_yr,(CASE WHEN SESSION='Monsoon' THEN '1' WHEN SESSION='Winter' THEN '2' WHEN SESSION='Summer' THEN '3' END)
  " ;

        $query = self::$db->query($sql, array($admn_no, $admn_no));
   //  echo self::$db->last_query(); die();
       
        if($query->num_rows() > 0 ){
            return $query->result();
        }else{
               $table='alumni_final_semwise_marks_foil';
        $table2='alumni_tabulation1';
               $sql2 = "SELECT z.*
FROM((
SELECT 'newsyssec' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type, a.type,NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN FORMAT(a.core_cgpa,5) else FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                           (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id


FROM " .$table. "    a
WHERE a.admn_no=? $minor_str " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC)B  GROUP BY B.sem) 

UNION all (

SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type, a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id
 FROM " .$table2. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC)A GROUP BY A.sem_code) /*order by sem desc*/
order by sem, core_status desc
)z   GROUP BY z.sem  ,IF( z.session_yr>='2019-2020',z.session_yr, NULL), IF(z.session_yr>='2019-2020',z.session, NULL)   
ORDER BY   sem  ,IF( z.session_yr>='2019-2020',session_yr,null), IF( z.session_yr>='2019-2020',(case when session='Monsoon' then '1' when session='Winter' then '2' when  session='Summer' then '3' end ),NULL)   " ;
                $query2 = self::$db->query($sql2, array($admn_no, $admn_no));
 return ($query2->num_rows() > 0 ? $query2->result() : false);                   
        
        }

	
	
	
	}
 
    
     public static function getCourseByDept($dept_id,$type=1,$crs=null,$br=null) {
        if ($type==1) {
            $and = "  and (b.course_id!='honour' and b.course_id!='minor') ";
        } else {
            $and = "";
        }
        
        
        if($crs!=null and $br!=null){
            $and2="  and  ( lower(b.course_id)='".$crs."' and lower(b.branch_id)='".$br."') ";
        }else{
            $and2="";
        }


        if ($dept_id != "comm") {
            $sql = "select concat(x.course_id,'(',x.branch_id,')') as sheet_name ,x.course_id,cs_courses.duration from(
                      select  a.dept_id,upper(b.course_id) as course_id,b.branch_id  from dept_course a  join course_branch b  on a.course_branch_id=b.course_branch_id  and a.dept_id=? and b.course_id!='capsule' " . $and . "  " . $and2 . "  and b.course_id!=?
                        group by b.course_id,b.branch_id)x
                         left join cs_courses on cs_courses.id=x.course_id";
            $secure_array = array($dept_id, 'comm');
        
        
        } else {
            //   echo 'section_id'. $this->input->post('section_name'); die();
            $sql = "select concat(x.course_id,'(','" . $br. "',')') as sheet_name ,x.course_id,cs_courses.duration from(
                      select  a.dept_id,upper(b.course_id) as course_id,b.branch_id  from dept_course a  join course_branch b  on a.course_branch_id=b.course_branch_id  and b.course_id!='capsule' " . $and . "   and b.course_id=?
                        group by b.course_id,b.branch_id)x
                         left join cs_courses on cs_courses.id=x.course_id";
            $secure_array = array('comm');
            
           
         
        }
           $query = self::$db->query($sql, $secure_array); //echo self::$db->last_query(); die();'<br/><br/><br/>'; 
           if ($query->num_rows() > 0)return $query->result(); else return 0;    
        
    }



function checkHonsStud($admn_no,$sem){
        $secure_array = array($admn_no,$sem);
         $sql="select hf1.admn_no from  hm_form hf1  where hf1.admn_no=? and  hf1.honours='1' and hf1.honour_hod_status='Y'  and  hf1.semester >=?";
         $query = self::$db->query($sql, $secure_array); //echo self::$db->last_query(); die();'<br/><br/><br/>'; 
          if ($query->num_rows() > 0)return 'Y'; else return 'N';    
     }

function getStudentIncomingMinor($dept,$sesion,$session_year,$exm_type,$branch, $sem, $admn_no = null) {                                
        $admn_no = preg_replace('/\s+/', '', $admn_no);
      if($session<>'Summer'  &&  !($session=='Winter' && $exm_type=='spl' ))  { 
                $table=( $exm_type == 'regular' ?' reg_regular_form ' : ' reg_other_form ');
                $sem_list_str=( $exm_type  == 'regular' ? ' rgf.semester=? ' : ' rgf.semester like ? ' );
          
        if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and hf2.admn_no in(" . $admn_no . ") ";
                $secure_array = array('1', '1', 'Y',  5, $dept, $branch,$session_year,( $exm_type == 'regular' ?$sem: '%'.$sem.'%'),$sesion,'2','2');
            } else {
                $replacer1 = " and hf2.admn_no=? ";
                $secure_array = array($admn_no, '1', '1', 'Y',  5, $dept, $branch,$session_year,( $exm_type == 'regular' ?$sem: '%'.$sem.'%'),$sesion,'2','2');
            }
        } else {
            $replacer1 = "";
            $secure_array = array('1', '1', 'Y', 5, $dept, $branch,$session_year,( $exm_type == 'regular' ?$sem: '%'.$sem.'%'),$sesion,'2','2');
        }
        $sql = "
                 select x.* from
                 (select null as  both_status_string,
                       null AS both_status_string_old , b.name AS br_name,dpt.name as dept_name , A.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as st_name ,A.dept_id,  A.branch_id , A.semester
                 from 
                ( select hf2.semester ,hf2.admn_no,hf2.dept_id,hm_minor_details.dept_id as from_dept,branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                         " . $replacer1 . "  and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?    and hf2.semester>=?  
								  and hm_minor_details.dept_id=?  and hm_minor_details.branch_id=?  
                    )A 
                      
                       inner join user_details ud on ud.id=A.admn_no                        
                       left join departments dpt on dpt.id =A.dept_id                       
                       LEFT join cs_branches b on b.id=A.branch_id
                       group by  A.admn_no order by A.admn_no )x
                       inner join  $table  rgf on rgf.admn_no=x.admn_no and rgf.session_year=? and $sem_list_str and rgf.`session`=? and  rgf.hod_status<>? and rgf.acad_status<>?   ORDER BY x.admn_no                    				   
             ";
     }else{
         
            if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and hf2.admn_no in(" . $admn_no . ") ";
               // $secure_array = array('1', '1', 'Y', $this->input->post('session_year'), 5,6, $this->input->post('dept'), $branch);
                $secure_array=array('1','1','Y',/*$this->input->post('session_year'),*/5,$dept, $branch,$sem,$session,$session_year,2,2);             
            } else {
                $replacer1 = " and hf2.admn_no=? ";
                //$secure_array = array($admn_no, '1', '1', 'Y', $this->input->post('session_year'), 5,6, $this->input->post('dept'), $branch);
                 $secure_array=array($admn_no,'1','1','Y',/*$this->input->post('session_year'),*/5,$dept,$branch,$sem,$session,$session_year,2,2);             
            }
        } else {
            $replacer1 = "";
            //$secure_array = array('1', '1', 'Y', $this->input->post('session_year'), 5,6, $this->input->post('dept'), $branch);
            $secure_array=array('1','1','Y',/*$this->input->post('session_year'),*/5,$dept,$branch,$sem,$session,$session_year,2,2);             
        }
         
             $sql="  select /*IF((z.hod_status='1' AND z.acad_status='1') ,'','Pending') as */ null as  both_status_string, null AS both_status_string_old ,z.admn_no,z.stu_name as  st_name,z.dept_name,z.br_name,z.dept_id,  z.branch_id , z.semester
                 from
                  (select dpt.name as dept_name , A.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as stu_name ,A.dept_id,  A.branch_id , A.semester,c.sub_id,e.name,e.subject_id,x.hod_status,x.acad_status,b.name AS br_name
                 from 
                ( select hf2.semester ,hf2.admn_no,hf2.dept_id,hm_minor_details.dept_id as from_dept,branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id  " . $replacer1 . " 
                          and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?  and hf2.semester>=? 
								  and hm_minor_details.dept_id=?  and hm_minor_details.branch_id=?  
                    )A 
                      
                      inner join user_details ud on ud.id=A.admn_no                        
                       left join departments dpt on dpt.id =A.dept_id
                        LEFT join cs_branches b on b.id=A.branch_id
                       inner join reg_summer_form x on x.admn_no=A.admn_no
INNER JOIN reg_summer_subject c ON c.form_id=x.form_id
INNER JOIN course_structure d ON d.id=c.sub_id  and  d.semester=?  and d.aggr_id like 'minor%'
INNER JOIN subjects e ON e.id=d.id
 and x.session=? and  x.session_year=? AND x.hod_status<>? AND x.acad_status<>? )z
 group by z.admn_no
ORDER BY z.admn_no";
              
         }


        $query = $this->db->query($sql, $secure_array);
         //echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }

    function checkStudentMinor($dept,$branch,$start,$eachset,$option,$admn_no,$sem) {          
// echo  'dept:'.$dept.'crs:'.$crs.'br:'.$branch.'strt'.$start.'end:'.$eachset .'option'.$option.',admn'.$admn_no.'sem'.$sem ;die();	
        $admn_no = preg_replace('/\s+/', '', $admn_no);
    if ($admn_no == null) {
          if($option==2)
             $rep=" limit ".$start." ,".$eachset."";
          else
           $rep=''; 
      }
        else
        $rep=''; 
        
   
              
          
         if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and hf2.admn_no in(" . $admn_no . ") ";
                $secure_array = array('1', '1', 'Y',  $sem, $dept, $branch);
            } else {
                $replacer1 = " and hf2.admn_no=? ";
                $secure_array = array($admn_no, '1', '1', 'Y',  $sem, $dept, $branch);
            }
			} else {
            $replacer1 = "";
            $secure_array = array('1', '1', 'Y', $sem, $dept, $branch);
        }
        
        $sql = "           
                 select A.*
                 from 
                ( select hf2.semester ,hf2.admn_no,hf2.dept_id,hm_minor_details.dept_id as dept,branch_id as branch from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                         " . $replacer1 . "  and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?    and hf2.semester>=?  
								  and hm_minor_details.dept_id=?  and hm_minor_details.branch_id=?  
                    )A  ".$rep." ";                 
                 
           
   


      $query = self::$db->query($sql,$secure_array);		
      //   echo self::$db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }

	
function get_sub_missing_foil_static($sessyr,$sess,$dept,$course,$br,$sem) {

    $pp=array($sess,$sessyr,$dept,$course,$br,$sem ,'R');

	    $table1='final_semwise_marks_foil';
        $table2='final_semwise_marks_foil_desc';	
	    //$table3='alumni_final_semwise_marks_foil';
        //$table4='alumni_final_semwise_marks_foil_desc';
		$table5='final_semwise_marks_foil_freeze';
        $table6='final_semwise_marks_foil_desc_freeze';				
		
		$secure_array=  array($sessyr,$sess,$dept,$course,$br,$sem ,'R');
	
$sql="	
 select *,  Max(n.tctr) AS ctr from  (
SELECT c.*
FROM (
SELECT y.semester,  x.*, GROUP_CONCAT(x.sub_code), COUNT(x.sub_code) AS tctr,y.hstatus,y.session,y.session_yr
FROM $table2 x
JOIN   $table1 y ON x.foil_id=y.id AND
y.session_yr=? and y.session=? and  lower(y.dept)=?  AND lower(y.course)=? AND lower(y.branch)=?   and semester=? AND TYPE=? 
GROUP BY x.foil_id
ORDER BY y.hstatus)c
GROUP BY c.tctr,c.hstatus 
ORDER BY c.hstatus,c.tctr asc )n
group by  n.hstatus
HAVING n.tctr < ctr";
 
$query = self::$db->query($sql,$secure_array);		
     if($query->num_rows() > 0 )return $query->result();
    else {
		
	$sql="	
 select *,  Max(n.tctr) AS ctr from  (
SELECT c.*
FROM (
SELECT y.semester,  x.*, GROUP_CONCAT(x.sub_code), COUNT(x.sub_code) AS tctr,y.hstatus,y.session,y.session_yr
FROM   $table2 x
JOIN   $table1 y ON x.foil_id=y.id AND
y.session_yr=? and y.session=? and  lower(y.dept)=?  AND lower(y.course)=? AND lower(y.branch)=?   and semester=? AND TYPE=? 
GROUP BY x.foil_id
ORDER BY y.hstatus)c
GROUP BY c.tctr,c.hstatus 
ORDER BY c.hstatus,c.tctr asc )n
group by  n.hstatus
HAVING n.tctr < ctr";
$query = self::$db->query($sql,$secure_array);					             			 	       
       return ($query->num_rows() > 0 ? $query->result() : false);                   		   
		}  
}   
    function match_foil_foil_desc_static($admn_no, $sem,$option,$option2) {
		 //  echo $admn_no.','. $sem . $option.'<br/>';
			 $sql=" set @var=? " ;
	   	     $admn_no = preg_replace('/\s+/', '', $admn_no);
		if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
				
                $admn_no = "'" . implode("','", explode(',',  $admn_no )) . "'";
				$query = self::$db->query($sql,''.$admn_no.'' );				
                $replacer1 = "   a.admn_no in(@var) ";
				 $replacer2= "   a.adm_no in(@var) ";
               
            } else {
				$query = self::$db->query($sql,$admn_no);				
                $replacer1 = "  a.admn_no=@var ";
				$replacer2= "   a.adm_no =@var ";

            }
        } else 
            $replacer1 = "";
		
		
		// echo self::$db->last_query();
		
	
           
     
	    $table= 'tabulation1';
        $table1='final_semwise_marks_foil';
        $table2='final_semwise_marks_foil_desc';		
		$table0= 'alumni_tabulation1';
		$table3='alumni_final_semwise_marks_foil';
        $table4='alumni_final_semwise_marks_foil_desc';
		$s_replace=null;
		if($sem<>null){
        $lst = '';$lst_old = '';
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		}


$replace_sql_txt_start= "SELECT 
SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, 
GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr,
sum(x.sum_crpts) as sum_cumm_crpts ,x.ctotcrpts ,sum(x.sum_crdthr) as sum_cumm_crdthr,x.ctotcrhr, format((sum(x.sum_crpts)/sum(x.sum_crdthr) ),5)as sum_cumm_cgpa,format( x.cgpa,5) as cgpa,
sum(x.sum_core_crpts) as sum_core_cumm_crpts ,x.core_ctotcrpts ,sum(x.sum_core_cr_hr) as sum_core_cumm_crdthr,x.core_ctotcrhr, format((sum(x.sum_core_crpts)/sum(x.sum_core_cr_hr) ),5)as sum_core_cumm_cgpa,format( x.core_cgpa,5) as core_cgpa,x.exam_type
FROM 
(";
$replace_sql_txt_end= " order by v.sem desc)x ";

if($option=='partqry'){
	$replace_sql_txt_start= "";
	$replace_sql_txt_end= "  order by v.sem ";
}


//  checking  minor option present or not
if($option2=='all'){
	$minor_str=" and   upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str=" UNION all(
SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT 
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_cr_hr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept,null as course,null as branch 
FROM $table a
WHERE    /*a.adm_no=@var*/ $replacer2  and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code)  order by sem, core_passfail desc ";
}
else{
	$minor_str=" and  upper(a.course)<>'MINOR' ";
    $minor_str2=" NOT LIKE 'honour%'  ";
	$union_str="";
}		

// end

$sql = "
               $replace_sql_txt_start

select  

v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id,v.dept,v.course,v.branch
                     

from(

select z.* from(
(
SELECT 'newsys' as rec_from ,B.* 
FROM (
select      
	x.sum_core_crpts,x.core_tot_cr_pts,x.sum_core_cr_hr,x.core_tot_cr_hr,x.sum_core_gpa ,x.core_gpa,
x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.core_passfail,x.type,x.exam_type, x.sem_code, x.sem
,x.core_cgpa,x.core_ctotcrhr,x.core_ctotcrpts,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept ,x.course,x.branch
 from
(SELECT 

sum(fd.cr_pts) as sum_crpts,sum(fd.cr_hr) as sum_crdthr, format((sum(fd.cr_pts)/sum(fd.cr_hr)),5)  as sum_gpa , 
 a.status AS passfail, a.core_status as core_passfail,a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester,
 a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
 a.core_tot_cr_pts,a.core_tot_cr_hr,a.core_gpa,a.core_cgpa,a.core_ctotcrhr,a.core_ctotcrpts,
 
SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_pts, 0)) AS sum_core_crpts,
SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_hr, 0)) AS sum_core_cr_hr,                          
                          
                          
FORMAT((SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_pts, 0))/ SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_hr, 0))),5) AS sum_core_gpa  ,a.id as foil_id ,a.dept    ,a.course,a.branch                    
                          
FROM $table1 a join $table2 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE /*a.admn_no=@var*/ $replacer1   $minor_str  AND (a.semester!= '0' and a.semester!='-1')   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type
/*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY x.session_yr desc  ,  x.semester DESC,   x.tot_cr_pts desc)B
GROUP BY B.sem) 
 $union_str

)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars
$replace_sql_txt_end ";
 //echo $sql;
 $query = self::$db->query($sql);
  
    //echo self::$db->last_query(); 
       
        if($query->num_rows() > 0 )return $query->result();
        else{
	if($option2=='all'){
	$minor_str=" and  upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str=" UNION all(
SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT 
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_crdthr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id ,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept ,null as course,null as branch
FROM $table0 a
WHERE /*a.adm_no=@var*/  $replacer2 and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code) order by sem, core_passfail desc";
}
else{
	$minor_str=" and  upper(a.course)<>'MINOR' ";
    $minor_str2=" NOT LIKE 'honour%'  ";
	$union_str="";
}		
			
               
      $sql2 = "
 $replace_sql_txt_start


select  
v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id ,v.dept ,v.course,v.branch  
                     

from(

select z.* from(
(
SELECT 'newsyssec' as rec_from ,B.* 
FROM (
select      
	x.sum_core_crpts,x.core_tot_cr_pts,x.sum_core_cr_hr,x.core_tot_cr_hr,x.sum_core_gpa ,x.core_gpa,
x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.core_passfail,x.type,x.exam_type, x.sem_code, x.sem
,x.core_cgpa,x.core_ctotcrhr,x.core_ctotcrpts,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept ,x.course,x.branch  
 from
(SELECT 

sum(fd.cr_pts) as sum_crpts,sum(fd.cr_hr) as sum_crdthr, format((sum(fd.cr_pts)/sum(fd.cr_hr)),5)  as sum_gpa , 
 a.status AS passfail, a.core_status as core_passfail,a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester,
 a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
 a.core_tot_cr_pts,a.core_tot_cr_hr,a.core_gpa,a.core_cgpa,a.core_ctotcrhr,a.core_ctotcrpts,
 
SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_pts, 0)) AS sum_core_crpts,
SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_hr, 0)) AS sum_core_cr_hr,                          
                          
                          
FORMAT((SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_pts, 0))/ SUM(IF (  (   fd.mis_sub_id <>'' and  lower(a.course)<>'jrf'  and  d.aggr_id  $minor_str2   
                     
                          ), fd.cr_hr, 0))),5) AS sum_core_gpa   ,a.id as foil_id  ,a.dept ,a.course,a.branch                            
                          
FROM $table3 a join $table4 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE /*a.admn_no=@var*/ $replacer1  $minor_str AND (a.semester!= '0' and a.semester!='-1')   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type
/*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY x.session_yr desc  ,  x.semester DESC,   x.tot_cr_pts desc)B
GROUP BY B.sem)
$union_str 


)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars

$replace_sql_txt_end ";


 $query2 = self::$db->query($sql2);
  //echo self::$db->last_query(); die();
 return ($query2->num_rows() > 0 ? $query2->result() : false);                   
        
        }

	
	
	
	}

	
	
	function match_foil_foil_desc_jrf_static($admn_no, $sem,$option,$option2) {
		$sy=$_POST['session_year'];
		//echo  $this->input->post('session_year'); die();
		//  echo  $sy=$_POST['session_year']; die();
		 //  echo $admn_no.','. $sem . $option.'<br/>';
			 $sql=" set @var=? " ;
	   	     $admn_no = preg_replace('/\s+/', '', $admn_no);
		if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
				
                $admn_no = "'" . implode("','", explode(',',  $admn_no )) . "'";
				$query = self::$db->query($sql,''.$admn_no.'' );				
                $replacer1 = "   a.admn_no in(@var) ";
				 $replacer2= "   a.adm_no in(@var) ";
               
            } else {
				$query = self::$db->query($sql,$admn_no);				
                $replacer1 = "  a.admn_no=@var ";
				$replacer2= "   a.adm_no =@var ";

            }
        } else 
            $replacer1 = "";
		
		
		// echo self::$db->last_query();
		
	
           
     
	    $table= 'tabulation1';
        $table1='final_semwise_marks_foil';
        $table2='final_semwise_marks_foil_desc';		
		$table0= 'alumni_tabulation1';
		$table3='alumni_final_semwise_marks_foil';
        $table4='alumni_final_semwise_marks_foil_desc';
		$s_replace=null;
		if($sem<>null){
        $lst = '';$lst_old = '';
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		}


$replace_sql_txt_start= "SELECT 
SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, 
GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr,
sum(x.sum_crpts) as sum_cumm_crpts ,x.ctotcrpts ,sum(x.sum_crdthr) as sum_cumm_crdthr,x.ctotcrhr, format((sum(x.sum_crpts)/sum(x.sum_crdthr) ),5)as sum_cumm_cgpa,format( x.cgpa,5) as cgpa,
sum(x.sum_core_crpts) as sum_core_cumm_crpts ,x.core_ctotcrpts ,sum(x.sum_core_cr_hr) as sum_core_cumm_crdthr,x.core_ctotcrhr, format((sum(x.sum_core_crpts)/sum(x.sum_core_cr_hr) ),5)as sum_core_cumm_cgpa,format( x.core_cgpa,5) as core_cgpa,x.exam_type,
x.session_yr ,x.session,x.type 
FROM 
(";
$replace_sql_txt_end= " order by v.session_yr desc,v.session desc,v.custom_exm_type)x ";

if($option=='partqry'){
	$replace_sql_txt_start= "";
	$replace_sql_txt_end= "  order by v.session_yr desc,v.session desc,v.custom_exm_type ";
}


//  checking  minor option present or not
if($option2=='all'){
	$minor_str=" and   upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str=" UNION all(
SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT null as custom_exm_type,
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_cr_hr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept,null as course,null as branch 
FROM $table a
WHERE    /*a.adm_no=@var*/ $replacer2  and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.session_yr,A.SESSION,A.exam_type)  order by sem, core_passfail desc ";
}
else{
	$minor_str=" and  upper(a.course)<>'MINOR' ";
    $minor_str2=" NOT LIKE 'honour%'  ";
	$union_str="";
}		

// end

$sql = "
               $replace_sql_txt_start

select  

v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id,v.dept,v.course,v.branch
                     

from(

select z.* from(
(
SELECT 'newsys' as rec_from ,B.* 
FROM (
select x.custom_exm_type,     
	x.sum_core_crpts,x.core_tot_cr_pts,x.sum_core_cr_hr,x.core_tot_cr_hr,x.sum_core_gpa ,x.core_gpa,
x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.core_passfail,x.type,x.exam_type, x.sem_code, x.sem
,x.core_cgpa,x.core_ctotcrhr,x.core_ctotcrpts,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept ,x.course,x.branch
 from
(SELECT (case 
                  when (a.session='Monsoon'  and a.`type`='J') then  '1' 
                  when (a.session='Monsoon'  and a.`type`='R') then  '2'                  
                  when a.session='Winter'  and a.`type`='J' then  '3'
                  when a.session='Winter'  and a.`type`='R' then  '4'
                  when( a.session='Summer'  and a.`type`='R') then  '5' 


               end)  as custom_exm_type,sum(fd.cr_pts) as sum_crpts,sum( IF (( ".($sy>=Exam_tabulation_config::$jrf_relative_start_session_yr? "": " TRIM(fd.grade)='D' OR " )."   TRIM(fd.grade)='F'),   0,fd.cr_hr)    ) as sum_crdthr, format((sum(fd.cr_pts)/sum(IF ((".($sy>=Exam_tabulation_config::$jrf_relative_start_session_yr? "": " TRIM(fd.grade)='D' OR " )."   TRIM(fd.grade)='F'),   0,fd.cr_hr)  )),5) as sum_gpa , 
a.status AS passfail, a.core_status as core_passfail,a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester, a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
 a.core_tot_cr_pts,a.core_tot_cr_hr,a.core_gpa,a.core_cgpa,a.core_ctotcrhr,a.core_ctotcrpts, SUM(fd.cr_pts) AS sum_core_crpts, SUM(IF ((".($sy>=Exam_tabulation_config::$jrf_relative_start_session_yr? "": " TRIM(fd.grade)='D' OR " )."   TRIM(fd.grade)='F'),   0,fd.cr_hr) ) AS sum_core_cr_hr,
 FORMAT((SUM(IF ((".($sy>=Exam_tabulation_config::$jrf_relative_start_session_yr? "": " TRIM(fd.grade)='D' OR " )."  TRIM(fd.grade)='F'),   0,fd.cr_pts) )/ SUM(IF ((".($sy>=Exam_tabulation_config::$jrf_relative_start_session_yr? "": " TRIM(fd.grade)='D' OR " )."  TRIM(fd.grade)='F'),   0,fd.cr_hr) )),5) AS sum_core_gpa ,a.id as foil_id ,a.dept ,a.course,a.branch 
 FROM $table1 a join $table2 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE /*a.admn_no=@var*/ $replacer1   $minor_str   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type

ORDER BY x.session_yr desc , x.custom_exm_type desc)B 
GROUP BY   B.session_yr,B.session,B.type) 
 $union_str

)z 

group by  z.session_yr,z.session,z.type  order by  z.session_yr desc ,z.session desc ,z.custom_exm_type desc )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars
$replace_sql_txt_end ";
 //echo $sql;
 $query = self::$db->query($sql);
  
    //echo self::$db->last_query(); 
       
        if($query->num_rows() > 0 )return $query->result();
        else{
	if($option2=='all'){
	$minor_str=" and  upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str=" UNION all(
SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT null as custom_exm_type,
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_crdthr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id ,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept ,null as course,null as branch
FROM $table0 a
WHERE /*a.adm_no=@var*/  $replacer2 and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.session_yr,A.SESSION,A.exam_type) order by sem, core_passfail desc";
}
else{
	$minor_str=" and  upper(a.course)<>'MINOR' ";
    $minor_str2=" NOT LIKE 'honour%'  ";
	$union_str="";
}		
			
               
      $sql2 = "
 $replace_sql_txt_start


select  
v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id ,v.dept ,v.course,v.branch  
                     

from(

select z.* from(
(
SELECT 'newsys' as rec_from ,B.* 
FROM (
select   x.custom_exm_type,   
	x.sum_core_crpts,x.core_tot_cr_pts,x.sum_core_cr_hr,x.core_tot_cr_hr,x.sum_core_gpa ,x.core_gpa,
x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.core_passfail,x.type,x.exam_type, x.sem_code, x.sem
,x.core_cgpa,x.core_ctotcrhr,x.core_ctotcrpts,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept ,x.course,x.branch
 from
(SELECT (case 
                  when (a.session='Monsoon'  and a.`type`='J') then  '1' 
                  when (a.session='Monsoon'  and a.`type`='R') then  '2'                  
                  when a.session='Winter'  and a.`type`='J' then  '3'
                  when a.session='Winter'  and a.`type`='R' then  '4'
                  when( a.session='Summer'  and a.`type`='R') then  '5' 


               end)  as custom_exm_type,sum(fd.cr_pts) as sum_crpts,sum( IF ((TRIM(fd.grade='D') OR TRIM(fd.grade='F')),   0,fd.cr_hr)    ) as sum_crdthr, format((sum(fd.cr_pts)/sum(IF ((TRIM(fd.grade='D') OR TRIM(fd.grade='F')),   0,fd.cr_hr)  )),5) as sum_gpa , 
a.status AS passfail, a.core_status as core_passfail,a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester, a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
 a.core_tot_cr_pts,a.core_tot_cr_hr,a.core_gpa,a.core_cgpa,a.core_ctotcrhr,a.core_ctotcrpts, SUM(fd.cr_pts) AS sum_core_crpts, SUM(IF ((TRIM(fd.grade='D') OR TRIM(fd.grade='F')),   0,fd.cr_hr) ) AS sum_core_cr_hr,
 FORMAT((SUM(IF ((TRIM(fd.grade='D') OR TRIM(fd.grade='F')),   0,fd.cr_pts) )/ SUM(IF ((TRIM(fd.grade='D') OR TRIM(fd.grade='F')),   0,fd.cr_hr) )),5) AS sum_core_gpa ,a.id as foil_id ,a.dept ,a.course,a.branch 
 FROM $table3 a join $table4 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE /*a.admn_no=@var*/ $replacer1   $minor_str   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type

ORDER BY x.session_yr desc , x.semester DESC, x.custom_exm_type desc)B 
GROUP BY   B.session_yr,B.session,B.type) 
$union_str 


)z 

group by  z.session_yr,z.session,z.type  order by  z.session_yr desc ,z.session desc ,z.custom_exm_type desc)v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars

$replace_sql_txt_end ";


 $query2 = self::$db->query($sql2);
  //echo self::$db->last_query(); die();
 return ($query2->num_rows() > 0 ? $query2->result() : false);                   
        
        }

	
	
	
	}
	

	 function match_oldsys_self_desc_static($admn_no, $sem,$option) {
		 //  echo $admn_no.','. $sem . $option.'<br/>';
			 $sql=" set @var=? " ;
	   	     $admn_no = preg_replace('/\s+/', '', $admn_no);
		if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
				
                $admn_no = "'" . implode("','", explode(',',  $admn_no )) . "'";
				$query = self::$db->query($sql,''.$admn_no.'' );				
                $replacer1 = "   a.admn_no in(@var) ";
				 $replacer2= "   a.adm_no in(@var) ";
               
            } else {
				$query = self::$db->query($sql,$admn_no);				
                $replacer1 = "  a.admn_no=@var ";
				$replacer2= "   a.adm_no =@var ";

            }
        } else 
            $replacer1 = "";
		
		
		// echo self::$db->last_query();
		
	
           
     
	    $table= 'tabulation1';        
		$table0= 'alumni_tabulation1';
		
		$s_replace=null;
		if($sem<>null){
        $lst = '';$lst_old = '';
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
       
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
       
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		}


$replace_sql_txt_start= "SELECT 
SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, 
GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr,
sum(x.sum_crpts) as sum_cumm_crpts ,x.ctotcrpts ,sum(x.sum_crdthr) as sum_cumm_crdthr,x.ctotcrhr, format((sum(x.sum_crpts)/sum(x.sum_crdthr) ),5)as sum_cumm_cgpa,format( x.cgpa,5) as cgpa,
sum(x.sum_core_crpts) as sum_core_cumm_crpts ,x.core_ctotcrpts ,sum(x.sum_core_cr_hr) as sum_core_cumm_crdthr,x.core_ctotcrhr, format((sum(x.sum_core_crpts)/sum(x.sum_core_cr_hr) ),5)as sum_core_cumm_cgpa,format( x.core_cgpa,5) as core_cgpa,x.exam_type
FROM 
(";
$replace_sql_txt_end= " order by v.sem desc)x ";

if($option=='partqry'){
	$replace_sql_txt_start= "";
	$replace_sql_txt_end= "  order by v.sem ";
}


//  checking  minor option present or not

	$minor_str=" and   upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str="
SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT 
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_cr_hr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept 
FROM $table a
WHERE    /*a.adm_no=@var*/ $replacer2  and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code";


// end

$sql = "
               $replace_sql_txt_start

select  

v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id,v.dept
                     

from(

select z.* from(

 $union_str

)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars
$replace_sql_txt_end ";
 //echo $sql;
 $query = self::$db->query($sql);
  
    //echo self::$db->last_query(); 
       
        if($query->num_rows() > 0 )return $query->result();
        else{
	
	$minor_str=" and  upper(a.course)<>'MINOR' ";
	$minor_str2=" NOT LIKE 'honour%'  ";
	
$union_str=" 
SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT 
SUM(a.crpts) AS sum_core_crpts,a.totcrpts AS core_tot_cr_pts, SUM(a.crdhrs) AS sum_core_crdthr,a.totcrhr AS core_tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_core_gpa, a.gpa as core_gpa,

SUM(a.crpts) AS sum_crpts,a.totcrpts AS tot_cr_pts, SUM(a.crdhrs) AS sum_crdthr,a.totcrhr AS tot_cr_hr, FORMAT((SUM(a.crpts) / SUM(a.crdhrs)),5) AS sum_gpa, a.gpa, 
a.ysession AS session_yr, a.wsms AS SESSION, a.passfail, a.passfail as core_passfail,  NULL AS TYPE,a.examtype AS exam_type, a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,
a.ogpa AS core_cgpa, a.ctotcrhr as  core_ctotcrhr ,a.ctotcrpts as core_ctotcrpts,a.ogpa AS cgpa, a.ctotcrhr,a.ctotcrpts ,a.id as foil_id ,SUBSTRING(a.sem_code, 1, CHAR_LENGTH(a.sem_code) - 1) AS dept 
FROM $table0 a
WHERE /*a.adm_no=@var*/  $replacer2 and a.sem_code not like 'PREP%' $s_replace_old
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code";
	
               
      $sql2 = "
 $replace_sql_txt_start


select  
v.sum_core_crpts,v.core_tot_cr_pts,v.sum_core_cr_hr,v.core_tot_cr_hr,v.sum_core_gpa ,v.core_gpa,
v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 
(@core_tot_hr_var := v.sum_core_cr_hr + @core_tot_hr_var) AS sum_core_ctotcrhr, v.core_ctotcrhr,	
(@core_tot_pt_var := v.sum_core_crpts + @core_tot_pt_var) AS sum_core_ctotcrpts, v.core_ctotcrpts, 
FORMAT(((@core_tot_pt_var)/ (@core_tot_hr_var)),5) AS sum_core_cgpa,
((@core_tot_pt_var)/ (@core_tot_hr_var)) as sum_core_cgpa_write, v.core_cgpa,
( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id ,v.dept 
                     

from(

select z.* from(
(

$union_str 


)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0, @core_tot_pt_var:= 0,	@core_tot_hr_var:=0) SQLVars

$replace_sql_txt_end ";


 $query2 = self::$db->query($sql2);
  //echo self::$db->last_query(); die();
 return ($query2->num_rows() > 0 ? $query2->result() : false);                   
        
        }

	
	
	
	}

	
	
	
	  function match_foil_foil_desc_minor_static($admn_no, $sem,$option,$option2) {
		
		 $sql=" set @var=? " ;
           $query = self::$db->query($sql,$admn_no);				
       // echo $admn_no.','. $sem . $option.'<br/>';
	    $table= 'tabulation1';
        $table1='final_semwise_marks_foil';
        $table2='final_semwise_marks_foil_desc';		
		$table0= 'alumni_tabulation1';
		$table3='alumni_final_semwise_marks_foil';
        $table4='alumni_final_semwise_marks_foil_desc';
		$s_replace=null;
		if($sem<>null){
        $lst = '';$lst_old = '';
        for ($i = $sem; $i >= $option2; $i--) {
            $lst.=$i . ($i == $option2 ? "" : ",");
           // $lst_old.= ($i == 10?"'X'":$i) . ($i == $option2 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
           // $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            //$s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		}


$replace_sql_txt_start= "SELECT 
SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, 
GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr,x.sem,
sum(x.sum_crpts) as sum_cumm_crpts ,x.ctotcrpts ,sum(x.sum_crdthr) as sum_cumm_crdthr,x.ctotcrhr, format((sum(x.sum_crpts)/sum(x.sum_crdthr) ),5)as sum_cumm_cgpa,format( x.cgpa,5) as cgpa,x.exam_type

FROM 
(";
$replace_sql_txt_end= " order by v.sem desc)x ";

if($option=='partqry'){
	$replace_sql_txt_start= "";
	$replace_sql_txt_end= "  order by v.sem ";
}

	$minor_str=" and   upper(a.course)='MINOR' ";

	$union_str="";



$sql = "
               $replace_sql_txt_start

select  


v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 

( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail,v.rec_from,v.foil_id,v.dept
                     

from(

select z.* from(
(
SELECT 'newsys' as rec_from ,B.* 
FROM (
select      
x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.type,x.exam_type, x.sem_code, x.sem
,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept 
 from
(SELECT 

sum(fd.cr_pts) as sum_crpts,sum(fd.cr_hr) as sum_crdthr, format((sum(fd.cr_pts)/sum(fd.cr_hr)),5)  as sum_gpa , 
 a.status AS passfail, a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester,
 a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
 
a.id as foil_id ,a.dept                        
                          
FROM $table1 a join $table2 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE a.admn_no=@var   $minor_str  AND (a.semester!= '0' and a.semester!='-1')   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type
/*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY x.session_yr desc  ,  x.semester DESC,   x.tot_cr_pts desc)B
GROUP BY B.sem) 
 $union_str

)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0 ) SQLVars
$replace_sql_txt_end ";
 //echo $sql;
 $query = self::$db->query($sql);
   // echo self::$db->last_query(); die();
       
        if($query->num_rows() > 0 )return $query->result();
        else{
	
	$minor_str=" and   upper(a.course)='MINOR' ";
	$minor_str2=" LIKE 'minor%'  ";
	$union_str="";
		
			
               
      $sql2 = "
 $replace_sql_txt_start


select  

v.sum_crpts, v.tot_cr_pts,  v.sum_crdthr, v.tot_cr_hr , v.sum_gpa ,v.gpa, 

( @tot_hr_var := v.sum_crdthr + @tot_hr_var) as  sum_ctotcrhr, v.ctotcrhr,	( @tot_pt_var := v.sum_crpts + @tot_pt_var) as  sum_ctotcrpts,v.ctotcrpts ,
format((( @tot_pt_var )/ ( @tot_hr_var ) ) ,5) as  sum_cgpa,
(( @tot_pt_var )/ ( @tot_hr_var ) ) as sum_cgpa_write ,  v.cgpa,
v.session_yr,v.session, v.type, v.exam_type, v.sem_code, v.sem, v.passfail, v.core_passfail,v.rec_from,v.foil_id ,v.dept 
                     

from(

select z.* from(
(
SELECT 'newsyssec' as rec_from ,B.* 
FROM (
select      

x.sum_crpts, x.tot_cr_pts, x.sum_crdthr, x.tot_cr_hr, x.sum_gpa,x.gpa, x.session_yr,x.session, x.passfail, x.type,x.exam_type, x.sem_code, x.sem
,x.cgpa,x.ctotcrhr,x.ctotcrpts,x.foil_id ,x.dept 
 from
(SELECT 

sum(fd.cr_pts) as sum_crpts,sum(fd.cr_hr) as sum_crdthr, format((sum(fd.cr_pts)/sum(fd.cr_hr)),5)  as sum_gpa , 
 a.status AS passfail, a.`type`, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,a.semester,
 a.tot_cr_pts ,a.tot_cr_hr,a.gpa ,a.cgpa ,a.ctotcrhr,a.ctotcrpts ,
a.id as foil_id  ,a.dept                             
                          
FROM $table3 a join $table4 fd on fd.foil_id=a.id
LEFT JOIN course_structure d ON d.id=fd.mis_sub_id 
WHERE a.admn_no=@var $minor_str AND (a.semester!= '0' and a.semester!='-1')   $s_replace   group by fd.foil_id ) x
GROUP BY x.session_yr,x.session,x.semester,x.type
/*ORDER BY a.session_yr,a.semester DESC, a.tot_cr_pts DESC, a.exam_type DESC)B*/
ORDER BY x.session_yr desc  ,  x.semester DESC,   x.tot_cr_pts desc)B
GROUP BY B.sem)
$union_str 


)z 

group by z.sem /*order by z.sem desc*/ )v,(SELECT @tot_pt_var:= 0,	@tot_hr_var:=0) SQLVars

$replace_sql_txt_end ";


 $query2 = self::$db->query($sql2);
  //echo self::$db->last_query(); die();
 return ($query2->num_rows() > 0 ? $query2->result() : false);                   
        
        }

	
	
	
	}
	
	
	
	

    
    function check_gpa_ogpa_static($admn_no, $sem,$option ) {
		
		if($option==null){
			$option_str_end=" GROUP BY B.sem) )z   GROUP BY z.sem order by sem   ";
			$option_str_start=" SELECT z.* FROM( (   ";
			
			$option_str_end_old= " GROUP BY A.sem_code /*order by sem desc*/  ";
			
		}
		else				
		$option_str_end=$option_str_start=$option_str_end_old=null;
		
		
     //  echo $admn_no.','. $sem . $table.'<br/>';
      $table='final_semwise_marks_foil';
      $query1= $query2=null;
        
            $s_replace = "  and a.semester ='" . $sem . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . ($sem == 10?"'X'":$sem) . "' ";
        
     
        $sql = "
$option_str_start
SELECT 'newsys' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type,a.type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN  FORMAT(a.core_cgpa,5) else FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE  FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                          (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id,a.branch


FROM " .$table. "    a
WHERE a.admn_no=? AND a.course<>'MINOR'  AND (a.semester!= '0' and a.semester!='-1') " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC limit 10000000)B  

 $option_str_end " ;

        $query = self::$db->query($sql, $admn_no);
		if($query->num_rows() > 0) $latest=1;
     //echo self::$db->last_query(); die();
         
         if(!($query->num_rows() > 0 )){
			
  $table='tabulation1';
             $sql = "SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type, a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id, null as branch
 FROM " .$table. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC limit 10000000)A 


$option_str_end_old ";

			
                  
              
      $query= $query1 = self::$db->query($sql, $admn_no);
	  $f1=$query1->num_rows();
	 
	   
	}
		 
  
      if((!($f1 > 0 ) ) && $latest<>1 ){
		
		   
		   $table='alumni_final_semwise_marks_foil';           
               $sql = "$option_str_start 
SELECT 'newsyssec' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type, a.type,NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN FORMAT(a.core_cgpa,5) else FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE  FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                           (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id,a.branch


FROM " .$table. "    a
WHERE a.admn_no=? AND a.course<>'MINOR'  AND (a.semester!= '0' and a.semester!='-1') " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC limit 10000000 )B  $option_str_end " ;
 //echo $sql;
 $query=$query2 = self::$db->query($sql, $admn_no);
  $f2=$query2->num_rows();
  
         //return ($query->num_rows() > 0 ? $query3->result() : false);    
   //if( $admn_no=='2012JE0621') echo 'qry'. $query2->num_rows();   
		
        }
		  if ((!($f2> 0 ))   && $latest<>1){
		     $table='alumni_tabulation1';
             $sql = "SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type, a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id, null as branch
 FROM " .$table. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC limit 10000000)A 

$option_str_end_old ";
 $query= self::$db->query($sql, $admn_no);
         //

		 
        }
     return ($query->num_rows() > 0 ? ($option==null?$query->row():$query->result()) : false);    
     
}

 function check_gpa_ogpa_static_version2($admn_no, $sem ) {
     //  echo $admn_no.','. $sem . $table.'<br/>';
        $table='final_semwise_marks_foil';
           $table2='tabulation1';
        
            $s_replace = "  and a.semester ='" . $sem . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . ($sem == 10?"'X'":$sem) . "' ";
        
     
        $sql = "SELECT z.*
FROM(
(
SELECT 'newsys' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type,a.type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN FORMAT(a.core_cgpa,5) else  FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                          (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id


FROM " .$table. "    a
WHERE a.admn_no=? AND a.course<>'MINOR'  AND (a.semester!= '0' and a.semester!='-1') " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC limit 10000000)B  GROUP BY B.sem) 
union all
(SELECT 'oldsys' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type, a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id
 FROM " .$table2. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC limit 10000000)A GROUP BY A.sem_code)
order by sem, core_status desc



)z   GROUP BY z.sem order by sem    " ;

        $query = self::$db->query($sql,  array($admn_no,$admn_no));
     //echo self::$db->last_query(); die();
       
        if(!($query->num_rows() > 0 )){
                  
               $table='alumni_final_semwise_marks_foil';       
                $table2='alumni_tabulaton1';			   
               $sql = "SELECT z.*
FROM((
SELECT 'newsyssec' as rec_from,B.*
FROM (
SELECT (CASE WHEN ((a.hstatus='Y') ) THEN a.core_status ELSE  a.status end) AS core_status, a.exam_type, a.type,NULL AS sem_code, a.semester AS sem,a.session_yr,a.session,
(CASE WHEN (a.status = 'FAIL'  and a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'  ELSE         				  							  
(CASE WHEN ((a.hstatus='Y') ) THEN FORMAT(a.core_gpa,5) ELSE  FORMAT(a.gpa,5) end) END) AS gpa,
(CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) else 'N/A' end) AS GPA_with_H, 
                          
                          (CASE WHEN (a.hstatus='Y') then a.status  else 'N/A' end) AS H_status,                          
                          (CASE WHEN (a.status = 'FAIL' and a.hstatus<>'Y' ) THEN 'INC'  WHEN (a.core_status = 'FAIL' and a.hstatus='Y' ) THEN 'INC'     ELSE (CASE WHEN (a.hstatus='Y') THEN FORMAT(a.core_cgpa,5) else FORMAT(a.cgpa,5) end ) end) as ogpa ,
                          (CASE WHEN (a.hstatus='Y') then (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.cgpa,5) END) else 'N/A' end) AS H_ogpa,a.hstatus ,
                           (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) as totcrhr_h ,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) as totcrpts_h ,
  a.core_tot_cr_hr  as  totcrhr ,a.core_tot_cr_pts as totcrpts,  (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END)  as ctotcrpts_h  , (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr  ELSE 'N/A' END)as ctotcrhr_h,
  a.core_ctotcrpts as ctotcrpts ,a.core_ctotcrhr  as ctotcrhr,a.id as foil_id


FROM " .$table. "    a
WHERE a.admn_no=? AND a.course<>'MINOR'  AND (a.semester!= '0' and a.semester!='-1') " . $s_replace . "		GROUP BY a.session_yr,a.session,a.semester,a.type	
ORDER BY a.session_yr DESC, a.semester DESC, a.tot_cr_pts DESC limit 10000000)B  GROUP BY B.sem)

union all
(SELECT 'oldsyssec' as rec_from,A.*
FROM (
SELECT a.passfail as  core_status, a.examtype AS exam_type, a.examtype as type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession AS session_yr, a.wsms as session,
 (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail')  ) THEN 'INC'   ELSE a.gpa END) AS gpa , 'N/A' as GPA_with_H,   'N/A' as H_status,    (CASE WHEN ((TRIM(a.passfail)='F' OR TRIM(a.passfail)='FAIL' OR TRIM(a.passfail)='fail') ) THEN 'INC'     ELSE a.ogpa  end) as ogpa , 'N/A' as H_ogpa, 'N' as hstatus,
 
 'N/A' as totcrhr_h , 'N/A' as  totcrpts_h ,a.totcrhr ,a.totcrpts, 'N/A' as  ctotcrpts_h  ,'N/A' as ctotcrhr_h,
  a.ctotcrpts ,a.ctotcrhr  ,a.id as foil_id
 FROM " .$table2. "   a
WHERE a.adm_no=? AND a.sem_code NOT LIKE 'PREP%' " . $s_replace_old . "		GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms	
ORDER BY a.ysession DESC,sem DESC, a.wsms DESC,a.totcrpts DESC,a.examtype DESC limit 10000000)A GROUP BY A.sem_code)
order by sem, core_status desc
 
)z   GROUP BY z.sem order by sem    " ;
       $query = self::$db->query($sql,  array($admn_no,$admn_no));
	   
		}
   
     return ($query->num_rows() > 0 ? $query->row() : false);    
     }
	
   function check_one_time_pass($admn_no, $sem = 10 ,$h_status='N', $start_sem=null,$onlyminor=null) {
	   
	 if($onlyminor=='Y') 
	$minor_str=  " AND upper(a.course)='MINOR' ";
	 else
	  $minor_str=  " AND a.course<>'MINOR' ";
  
      
     
		
		
		
       
	   
		   if ($h_status == 'Y'){
			   
             $status = "status ";
			 $str3=", a.passfail AS passfail_core ";
		     $str2=" , a.core_status AS passfail_core ";
		     $str1= " ,SUM(IF ((TRIM(x.passfail_core='F') OR TRIM(x.passfail_core)='FAIL' OR TRIM(x.passfail_core)='fail'), 1, 0)) AS count_status_core, GROUP_CONCAT(IF((TRIM(x.passfail_core)='F' OR TRIM(x.passfail_core)='FAIL' OR TRIM(x.passfail_core)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr_core " ;
           }else{
            $status = "core_status ";
			$str3=$str2=$str1="";
		   }
        $lst = '';
        for ($i = $sem; $i >= ($start_sem<>null?$start_sem:1); $i--) {
            $lst.=$i . ($i == ($start_sem<>null?$start_sem:1) ? "" : ",");						
            $lst_old.= ($i == 10?"'X'":$i) . ($i == ($start_sem<>null?$start_sem:1) ? "" : ",");
			
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
        //echo  $s_replace .'<br/>' ;

        $sql = "SELECT SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr  $str1
		              
FROM (
	select z.* from(
			(
			SELECT B.*
			FROM (
			SELECT  a." . $status . "  AS passfail ".$str2." ,a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr ,a.session 
			FROM final_semwise_marks_foil a
			WHERE a.admn_no=? $minor_str " . $s_replace . "			
			/*GROUP BY a.session_yr,a.session,a.semester,a.type			*/
			ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
			/*GROUP BY B.sem*/) 
			UNION (
			SELECT A.*
			FROM (
			SELECT a.passfail ".$str3.",a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession as session_yr,a.wsms
			FROM tabulation1 a
			WHERE a.adm_no=? and a.sem_code not like 'PREP%' " . $s_replace_old . "		
			/*GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms			*/
			ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
			/*GROUP BY A.sem_code*/)
			)z /*group by z.sem */
			)x
         ";


 $query = self::$db->query($sql, array($admn_no,$admn_no));
        
      // echo self::$db->last_query(); die();
         if($query->num_rows() > 0){
			 return $query->row();
         }
		 else{
			  $sql = "SELECT SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status, GROUP_CONCAT(IF((TRIM(x.passfail)='F' OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), CONCAT('Sem-',x.sem,':','INC'), NULL) SEPARATOR ', ') AS incstr  $str1
		              
FROM (
	select z.* from(
			(
			SELECT B.*
			FROM (
			SELECT  a." . $status . "  AS passfail ".$str2." ,a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr ,a.session 
			FROM alumni_final_semwise_marks_foil a
			WHERE a.admn_no=? $minor_str " . $s_replace . "			
			/*GROUP BY a.session_yr,a.session,a.semester,a.type			*/
			ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
			/*GROUP BY B.sem*/) 
			UNION (
			SELECT A.*
			FROM (
			SELECT a.passfail ".$str3.",a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession as session_yr,a.wsms
			FROM alumni_tabulation1 a
			WHERE a.adm_no=? and a.sem_code not like 'PREP%' " . $s_replace_old . "		
			/*GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms			*/
			ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
			/*GROUP BY A.sem_code*/)
			)z /*group by z.sem */
			)x
         ";


 $query = self::$db->query($sql, array($admn_no,$admn_no));
        
      // echo self::$db->last_query(); die();
         if($query->num_rows() > 0)
			 return $query->row(); 
			 else return false;
		 }


		 
    }

                        
      

   	
	
	
}
