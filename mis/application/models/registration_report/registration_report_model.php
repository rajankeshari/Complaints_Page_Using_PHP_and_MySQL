<?php
class Registration_report_model extends CI_model{

public function sessiondata()
  {
   $datas=array();
   $stmtd="select id,name from cbcs_courses";
   $excdep=$this->db->query($stmtd);
   $valuesd=$excdep->result_array();
   $datas['course']=$valuesd;




   $stmty="select session_year from mis_session_year";
   $excutey=$this->db->query($stmty);
   //if($excutey->num_rows() > 0)
     
      $valuesy=$excutey->result_array();
      $datas['session_year']=$valuesy;
      // $r=count($valuesy);
      // $q=$valuesy[$r-1]['session_year'];
      // $syear=($q)+1;
      // $i=($syear)+1;
      // $nextyear[0]['session_year']=$syear."-".$i; 
      // $datas['session_year']=array_merge($datas['session_year'],$nextyear);
      // echo "<pre>";
      // print_r($datas['session_year']);
      // exit;
    
   //
   // $stmtactive="select session_year from mis_session_year where active='1'";
   // $exeactive=$this->db->query($stmtactive);
   // $activevalues=$exeactive->result_array();
   // $p=$activevalues[0]['session_year'];
   // $syear=($p)+1;
   // $i=($syear)+1;
   // $yearnext[0]['session_year']=$syear."-".$i;
   // $datas['session_year']=array_merge($datas['session_year'],$yearnext);



   $stmts="select * from mis_session ";
   $excutes=$this->db->query($stmts); 
   $values=$excutes->result_array();
   $datas['session']=$values;
   
   
   return $datas;

  }
  public function genrate_report($sess,$sessyear,$course)
  {
     $smt="SELECT x.*, rg.semester from (SELECT t.form_id, t.id,u.dept_id,t.sub_category_cbcs_offered,t.branch ,t.admn_no, CONCAT_WS(' ',u.`first_name`, u.`middle_name`, u.`last_name`) AS stu_name,t.sub_category,t.course_aggr_id,course,t.session_year,subject_name,t.subject_code, GROUP_CONCAT('',t.subject_code) AS subject, GROUP_CONCAT('',t.sub_category) AS subcode
    FROM user_details u
    INNER JOIN cbcs_stu_course AS t ON u.id=t.admn_no
    WHERE t.session_year='$sessyear' AND t.`session`='$sess' AND t.course='$course'
    GROUP BY t.admn_no desc UNION 
    SELECT o.form_id, o.id,u.dept_id,o.sub_category_cbcs_offered,o.branch,o.admn_no, CONCAT_WS(' ',u.`first_name`, u.`middle_name`, u.`last_name`) AS stu_name,o.sub_category,o.course_aggr_id,course,o.session_year,subject_name,o.subject_code, GROUP_CONCAT('',o.sub_category) AS subject, GROUP_CONCAT('',o.subject_code) AS subcode
    FROM user_details u
    INNER JOIN old_stu_course AS o ON u.id=o.admn_no
    WHERE o.session_year='$sessyear' AND o.`session`='$sess' AND o.course='$course'
    GROUP BY o.admn_no desc) x
    JOIN reg_regular_form rg ON rg.admn_no=x.admn_no AND rg.session_year='$sessyear'  AND rg.session='$sess'
	";
     $query=$this->db->query($smt);
	  echo $this->db->last_query();die();
     if($query)
     {
     $result=$query->result_array();
     return $result;
     }
     else
     {
     return false;
     }



  } 
  
  
  public function get_regular_form_info($sess,$sessyear,$course){
	  $sql="select  rg.admn_no from  reg_regular_form rg  where  rg.hod_status='1' and rg.acad_status='1' and rg.session_year=? and rg.`session`=?
	   and rg.course_id=? ";
  }
  
  
   public function backpaper_finder($sess,$sessyear,$course_id=null,$admn_no=null)
  {
	  
 $sem_str= "".($sess=='Monsoon'?  " and  a.session_yr<'$sessyear'	 " :  (  $sess=='Winter'?  " and  a.session_yr<='$sessyear'	and !( a.session_yr='$sessyear' and  ( a.session='Winter' or  a.session='Summer') ) " : " and a.session_yr<='$sessyear'  and   !(a.session='Summer') " )  )."";


      $this->load->model('result_declaration/result_declaration_config');		 
	  $cid=$course_id;
	  $wrong_release_list_arr=null;$wrong_release_list_arr=null;
	  
	 
$str_pub1=" ,a.actual_published_on ";
$str_pub2=" ,x.actual_published_on ";

	  
	  
	  
if($course_id<>null){
       $append_and= " and course_id='$course_id' ";
	   
}

if($admn_no<>null){
$append_admn1= " and a.admn_no='$admn_no' ";
       $append_admn2= " and  p.admn_no='$admn_no' ";


 if(( strtoupper($cid)==strtoupper('b.tech') ||  strtoupper($cid)== strtoupper('dualdegree') ||  strtoupper($cid)== strtoupper('int.m.sc')||  strtoupper($cid)== strtoupper('int.msc.tech')||  strtoupper($cid)== strtoupper('int.m.tech')  ) ){	        
// getting studentds whose redeclaration gone wrong for monsoon/winter 18-19 thus ogpa will be calaculated  based on based_on_publish_date,however students not falls on this category will be  based on actual published_on	   
	$wrong_release_list_arr=Result_declaration_config::$hons_wrong_redec_list;
	// echo '#wrong_release_list_arr:';echo'<pre>'; print_r($wrong_release_list_arr);echo'</pre>'.'<br/>';die();
	}
	else{
	  $wrong_release_list_arr=null;
	  }

      
if(in_array(strtoupper($admn_no),$wrong_release_list_arr)){
	$str_pub1=" ,a.published_on ";
	$str_pub2=" ,x.published_on ";
}
else{
	$str_pub1=" ,a.actual_published_on ";
	$str_pub2=" ,x.actual_published_on ";
}

}


$secure_array=array($sessyear,$sess,$sessyear,$sess,$sessyear,$sess);
     $smt="SELECT   concat(cd.name ,'[',u.dept,']') AS dept ,
concat(cc.name ,'[',u.course,']') AS course,
concat(cb.name ,'[',u.branch,']') AS  branch ,u.admn_no, CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS st_name  
,u.failed_sub_ctr,u.failed_sub,u.registered_sub
FROM (
SELECT h.dept,h.course,h.branch,h.admn_no, 
COUNT(h.admn_no) AS failed_sub_ctr,
GROUP_CONCAT( distinct  CONCAT_ws(' # ',h.subname,h.ltp,h.sub_code) order by h.subname   SEPARATOR ',<br>')AS failed_sub
,GROUP_CONCAT( distinct  CONCAT_ws(' # ',h.subject_name,h.reg_sub_ltp,h.subject_code)order by h.subject_name  SEPARATOR ',<br>') AS registered_sub FROM(

select g.*,p.subject_code,p.subject_name,
p.ltp AS  reg_sub_ltp


 from (
SELECT z1.* from
(
SELECT v.*
FROM (
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade, fd.cr_pts, fd.cr_hr, 
y.admn_no ,fd.mis_sub_id,
IF(ac.alternate_subject_code IS NOT NULL, ac.alternate_subject_code, IF(acl.alternate_subject_code IS NOT NULL, acl.alternate_subject_code, fd.sub_code)) AS  alt_sub_code,
 IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, 
 IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub,
 if(o.course_id IS NULL , if(cso.course_id IS  NULL, if(cs.id IS NOT NULL, 'honour',NULL ),cso.course_id ) ,o.course_id) AS course_id,cs.id

,



if( cso.sub_code IS NULL ,if(o.sub_code IS NULL, concat(s.lecture,'-',s.tutorial,'-',s.practical ),
concat(o.lecture,'-',o.tutorial,'-',o.practical ) )  ,
 concat(cso.lecture,'-',cso.tutorial,'-',cso.practical ))  AS ltp

 ,
if( cso.sub_code IS NULL ,if(o.sub_code IS NULL, s.name,
o.sub_name )  ,
cso.sub_name)  AS subname
 FROM ( SELECT x.* FROM ( SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,
a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts , if( rg.semester<>a.semester, a.semester, null)  as sem ,
rg.semester as reg_sem ,  a.published_on, a.actual_published_on
FROM final_semwise_marks_foil_freezed AS a
join  reg_regular_form rg  on rg.admn_no=a.admn_no and  rg.hod_status='1' and rg.acad_status='1' and rg.session_year=? and rg.`session`=?

and    UPPER(a.course)<>'MINOR' AND (a.semester!= '0' AND a.semester!='-1') and a.course<>'jrf'    $append_and   $append_admn1

$sem_str
ORDER BY a.admn_no,a.semester $str_pub1 /*a.actual_published_on*/ DESC

LIMIT 100000000)x   
GROUP BY x.admn_no, x.semester, IF(x.session_yr >= '2019-2020', x.session_yr, NULL), IF(x.session_yr >= '2019-2020', x.session, NULL)
    /*having  x.semester<= x.reg_sem*/   order by  x.admn_no,x.semester $str_pub2 /*x.actual_published_on*/ desc limit 100000000) y JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND

fd.admn_no=y.admn_no 
LEFT JOIN alternate_course ac ON ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code 
LEFT JOIN alternate_course_all acl ON acl.alternate_subject_code=fd.sub_code
left join old_subject_offered o on o.sub_code=fd.sub_code

and o.session_year=y.session_yr and o.`session`=y.session AND 
o.dept_id=y.dept and  y.course =o.course_id 
and o.branch_id=(case when y.dept='comm'  then 'comm' else   y.branch END )
 AND (case when y.dept='comm' then  (case when y.branch IN('A','B','C','D') then  '1' ELSE '2' END )=o.sub_group ELSE 1=1 END)


LEFT JOIN cbcs_subject_offered cso on cso.sub_code=fd.sub_code and cso.session_year=y.session_yr and cso.`session`=y.session AND 
cso.dept_id=y.dept and  y.course =cso.course_id  and cso.branch_id=(case when y.dept='comm'  then 'comm' else   y.branch END )
 AND (case when y.dept='comm' then  (case when  y.branch IN('A','B','C','D') then  '1' ELSE '2' END )=cso.sub_group  ELSE 1=1 END)



LEFT JOIN  subjects s ON s.id=fd.mis_sub_id  AND y.session_yr<'2019-2020'  

LEFT JOIN  course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND  cs.semester=y.semester AND y.session_yr<'2019-2020'


ORDER BY  y.admn_no, newsub,  fd.cr_pts desc,y.session_yr DESC limit 10000000 )v
GROUP BY v.admn_no, v.newsub  ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub   limit 10000000 ) z1
GROUP BY z1.admn_no,z1.sub_code  having   z1.grade in('F','I') 
)g

left join 
      ( select csc.admn_no,csc.session_year,csc.`session`, csc.subject_code,csc.subject_name,concat(csc1.lecture,'-',csc1.tutorial,'-',csc1.practical) AS ltp  from  cbcs_stu_course csc  
         JOIN  cbcs_subject_offered csc1 ON csc1.id=csc.sub_offered_id
		    and  csc.session_year=? and   csc.`session`=?
      
           union all
         select cso.admn_no,cso.session_year,cso.`session`, cso.subject_code,cso.subject_name,concat(oso1.lecture,'-',oso1.tutorial,'-',oso1.practical) AS ltp  from  old_stu_course cso
                  JOIN  old_subject_offered oso1 ON oso1.id=cso.sub_offered_id
			   AND   cso.session_year=? and   cso.`session`=?	
		) p
		
		 on  p.admn_no=g.admn_no and p.subject_code=g.alt_sub_code  $append_admn2



)h GROUP BY h.admn_no  
 )u LEFT JOIN user_details ud  ON  ud.id=u.admn_no
  
LEFT JOIN cbcs_departments cd ON cd.id=u.dept 
LEFT JOIN cbcs_courses cc ON cc.id=u.course
LEFT JOIN cbcs_branches cb ON cb.id=u.branch
 
 order by u.dept,u.course,u.branch,u.admn_no
	 
	";
     $query=$this->db->query($smt,$secure_array);
	 
	// echo $this->db->last_query(); die();
     if($query)
     {
     $result=$query->result_array();
     return $result;
     }
     else
     {
     return false;
     }



  }  
  
  
  
  
  



}

?>