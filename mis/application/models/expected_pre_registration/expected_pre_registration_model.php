<?php
class expected_pre_registration_model extends CI_Model{



  public function sessiondata()
  {
   $datas=array();
   $stmtd="select id,name from cbcs_departments where type='academic' and status='1'";
   $excdep=$this->db->query($stmtd);
   $valuesd=$excdep->result_array();
   $datas['department']=$valuesd;




   $stmty="select session_year from mis_session_year";
   $excutey=$this->db->query($stmty);
   //if($excutey->num_rows() > 0)
     
      $valuesy=$excutey->result_array();
      $datas['session_year']=$valuesy;
      $r=count($valuesy);
      $q=$valuesy[$r-1]['session_year'];
      $syear=($q)+1;
      $i=($syear)+1;
      $nextyear[0]['session_year']=$syear."-".$i; 
      $datas['session_year']=array_merge($datas['session_year'],$nextyear);
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



   $stmts="select * from mis_session where `session`!= 'summer' ";
   $excutes=$this->db->query($stmts); 
   $values=$excutes->result_array();
   $datas['session']=$values;
   
   
   return $datas;

  }  

  public function get_course_bydept_cs($dept_id)
  {

   
    $query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM 
    courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON 
    dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id ='".$dept_id."'");
  
   if($query->num_rows() > 0)
    return $query->result();
   else
    return false;

  }
  
  public function get_branch_by_course($course,$dept){
    // echo $course;
    // echo $dept;

    // return "Model data".$course.$dept;
        
  $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM cs_branches INNER JOIN course_branch ON course_branch.branch_id = cs_branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '".$course."' AND dept_course.dept_id = '".$dept."'");
    if($query->num_rows() > 0)
      return $query->result();
    else
      return false;
  }

  public function studentadmnodetails($s,$sy,$department,$course=null,$branch=null)
  { 
  if($department<>'all' )
   $dept_str=" AND dp.id='$department' ";
  else
	$dept_str="";
   if(!empty($course) && !empty($branch))
   $crs_br_str=" and cd.id='$course' and cb.id='$branch' ";
  else
   $crs_br_str='';
   

    $data=array();
    $j=0;
   $stmt="select p.* from 
    (select x.*,j.thesis_status from (SELECT dp.name,cd.duration,cd.duration*2 as totalduration,rrf.semester, cd.duration*2-rrf.semester as duesem , branch_id,admn_no,rrf.session_year,rrf.session 
		, CONCAT_WS(' ',ud.`first_name`,   ud.`middle_name`,   ud.`last_name`) AS  stu_name, ud.photopath, cd.name  AS  crs_name, cb.name AS br_name ,cd.id as crs_id,cb.id as br_id
	
	from  reg_regular_form as  rrf 
    INNER JOIN cbcs_courses as cd ON rrf.course_id=cd.id
    INNER JOIN user_details as ud ON ud.id=rrf.admn_no INNER JOIN departments as dp on dp.id=ud.dept_id 
	INNER JOIN cbcs_branches cb  ON   cb.id=rrf.branch_id
	INNER JOIN  users  u on u.id=rrf.admn_no
	where    u.status='A' and
	session ='$s' and session_year='$sy' AND rrf.hod_status='1' AND rrf.acad_status ='1' $dept_str    $crs_br_str
    UNION
    SELECT  dp.name,cd.duration,cd.duration*2 as totalduratoin,rif.semester,cd.duration*2-rif.semester as duesem ,branch_id,admn_no,rif.session_year,rif.session , CONCAT_WS(' ',ud.`first_name`,   ud.`middle_name`,   ud.`last_name`) AS  stu_name, ud.photopath, cd.name  AS  crs_name, cb.name AS br_name,cd.id as crs_id,cb.id as br_id
   from reg_idle_form as rif 
    INNER JOIN cbcs_courses as cd ON rif.course_id=cd.id 
    INNER JOIN user_details as ud ON ud.id=rif.admn_no INNER JOIN departments as dp on dp.id=ud.dept_id
	INNER JOIN cbcs_branches cb  ON   cb.id=rif.branch_id
	INNER JOIN  users  u on u.id=rif.admn_no
	where    u.status='A' and  session ='$s' and session_year='$sy' AND  rif.hod_status ='1' AND rif.acad_status ='1' $dept_str  $crs_br_str)x
	 left  JOIN jrf_evaluation_main j ON j.admn_no=x.admn_no) p   where  (p.thesis_status is  null or p.thesis_status='0')
	ORDER BY p.crs_name,p.br_name,p.admn_no ";
        
    $exc=$this->db->query($stmt);
    $result=$exc->result_array();

    
   // echo $this->db->last_query(); die();

    return $result;

  }

  public function studentfailpass($admino)
  {

    $stmt2="SELECT g.*,p.subject_code from (SELECT z1.* from(SELECT v.*FROM (
    SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester,fd.sub_code, fd.grade, fd.cr_pts, fd.cr_hr, y.admn_no , IF(ac.alternate_subject_code IS NOT NULL,ac.old_subject_code, 
    IF(acl.alternate_subject_code IS NOT NULL,acl.old_subject_code,fd.sub_code)) AS newsub,
    if(o.course_id IS NULL ,if(cs.id IS NOT NULL, 'honour',NULL ),o.course_id) AS course_id,cs.id
    FROM ( SELECT x.* FROM ( SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts , if( rg.semester<>a.semester, a.semester, null)  as sem ,
    rg.semester as reg_sem ,  a.published_on, a.actual_published_on
    FROM final_semwise_marks_foil_freezed AS a
   join  reg_regular_form rg  on rg.admn_no=a.admn_no and  rg.hod_status='1' and rg.acad_status='1' and rg.session_year='2019-2020' and rg.`session`='Winter'
   WHERE  /*a.admn_no=@var AND*/  UPPER(a.course)<>'MINOR' AND
                      (a.semester!= '0' AND a.semester!='-1') and a.course<>'jrf'   and a.admn_no='$admino'
   ORDER BY a.admn_no,a.semester,a.actual_published_on desc
    LIMIT 100000000)x    GROUP BY x.admn_no, IFNULL(x.sem, x.session_yr)    /*having  x.semester<= x.reg_sem*/   order by  x.admn_no,x.semester,x.actual_published_on desc limit 100000000) y JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND
    fd.admn_no=y.admn_no LEFT JOIN alternate_course ac ON ac.session_year=y.session_yr AND ac.`session`=y.session AND
    ac.admn_no=y.admn_no AND ac.alternate_subject_code=fd.sub_code AND fd.sub_code='F'
   LEFT JOIN alternate_course_all acl ON acl.session_year=y.session_yr AND acl.`session`=y.session AND
   acl.alternate_subject_code=fd.sub_code
   left join old_subject_offered o on o.sub_code=fd.sub_code and o.session_year=y.session_yr and o.`session`=y.session and o.dept_id=y.dept and (case when o.course_id='honour' then 'honour' else y.course end)=o.course_id and o.branch_id=y.branch
    LEFT JOIN  course_structure cs ON cs.id= fd.mis_sub_id AND cs.aggr_id LIKE '%honour%' AND  cs.semester=y.semester AND y.session_yr<'$sy'
    ORDER BY  y.admn_no, newsub,  fd.cr_pts desc,y.session_yr DESC limit 10000000 )v
    GROUP BY v.admn_no, v.newsub  ORDER BY v.admn_no, v.session_yr,v.dept,v.course,v.branch,v.semester,v.newsub   limit 10000000 ) z1
   GROUP BY z1.admn_no,z1.sub_code  having   z1.grade in('F','I') )g
   left join 
    ( select  csc.admn_no,csc.session_year,csc.`session`, csc.subject_code  from  cbcs_stu_course csc  where  csc.session_year='$sy' and   csc.`session`='$s'
   union all
    select  cso.admn_no,cso.session_year,cso.`session`, cso.subject_code from  old_stu_course cso  where  cso.session_year='$sy' and   cso.`session`='$s'      
   ) p
   on  p.admn_no=g.admn_no and p.subject_code=g.sub_code and  p.admn_no='$admino'";
   $exc2=$this->db->query($stmt2);
    if($exc2->num_rows() == 0)
      return false;
    else
      return $exc2->result_array();
   //echo $this->db->last_query(); die();
   //$result2=$exc2->result_array();
  }

public function fecthstudentdeatils($admno)
{

 $stmt ="SELECT rrf.admn_no,cd.duration*2-rrf.semester,cd.duration*2.semester,cd.duration,
 rrf.session_year,rrf.`session`, cc.name, ud.salutation,ud.first_name,ud.middle_name,
 ud.last_name,ud.sex,ud.category,ud.dob,ud.email,ud.photopath, ud.marital_status,
 ud.physically_challenged,dp.name AS department,br.name AS branch, 
 sa.admn_no,rrf.semester,rrf.session,rrf.session_year,rrf.acad_status,rrf.hod_status,
 rrf.acad_status,rrf.timestamp FROM user_details AS ud INNER JOIN departments AS dp ON ud.dept_id=dp.id 
 INNER JOIN stu_academic AS sa ON ud.id=sa.admn_no INNER JOIN branches AS br ON sa.branch_id=br.id 
 INNER JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no INNER JOIN cbcs_courses as cc ON rrf.course_id= cc.id
  INNER JOIN cbcs_courses as cd ON rrf.course_id=cd.id WHERE rrf.admn_no='$admno' limit 1";

  $quey=$this->db->query($stmt);
  $row=$quey->result_array();
  return $row;

}

public function get_other_details($admno)
{

 $stmt ="SELECT a.guardian_name,b.father_name,
CASE c.sex 	WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' ELSE 'Other' 	end gender,
DATE_FORMAT(c.dob,'%d-%m-%Y') AS dob
 FROM stu_other_details a
INNER JOIN user_other_details b ON b.id=a.admn_no
INNER JOIN user_details c ON c.id=a.admn_no
WHERE a.admn_no=?";

  $quey=$this->db->query($stmt,array($admno));
  $row1=$quey->row();
  return $row1;

}
public function get_final_data_from_convocation($admn_no,$session_yr)
{

 $stmt ="SELECT admn_no FROM convocation_admin_final WHERE yop=? and upper(admn_no)=?";

  $quey=$this->db->query($stmt,array($session_yr,strtoupper($admn_no)));
  //$row1=$quey->row();
  if($quey->num_rows() == 0)
      return false;
  else
	  return true;

}


}

