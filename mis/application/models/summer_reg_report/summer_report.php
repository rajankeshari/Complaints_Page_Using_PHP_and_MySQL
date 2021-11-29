<?php

class Summer_report extends CI_Model {

    function __construct() {
        parent::__construct();
    }
	function get_summer_student($syear,$did,$cid,$bid,$sem)
    {
		$join_append='';$join_sel1='';$join_sel2='';
       $param="a.session_year='".$syear."'  and a.`session`='Summer'     /*and a.hod_status='1' and a.acad_status='1'*/" ;
       
       if($did=='comm' && $cid=='comm' && $bid=='comm' && $sem==""){
         $param=$param." and ((d.semester  like '1_1') or (d.semester  like '1_2') or (d.semester  like '2_1') or (d.semester  like '2_2') ) and  a.course_aggr_id like 'comm_comm%'";
		  $join_append= " INNER JOIN stu_section_data ssd on ssd.admn_no=a.admn_no and ssd.session_year='".$syear."'  ";
		  
		  $join_sel1=' ,ssd.section ' ;
		  $join_sel2=' ,A.section ';
       }
       else{ 
	   
      
       if($did!='none' && $did!='comm')
       {
           $param=$param." and b.dept_id='".$did."'";
       }
       if($cid!='none' && $cid!='comm')
       {
           $param=$param." and a.course_id='".$cid."'";
       }
       if($bid!='none' && $bid!='comm' )
       {
           $param=$param." and a.branch_id='".$bid."'";
       }
      
	  
       if($sem!="" && $did!='comm')
       {
           $param=$param." and d.semester  like '%".$sem."%'";
       }
	   if($sem!="" && $did=='comm')
       {
           $param=$param." and  substring(d.semester,1,1)='".$sem."'  and  a.course_aggr_id like 'comm_comm%'";
       }
	    if($bid<>'none' && $cid<>'none' && $bid<>'comm' && $cid<>'comm' ){	   
	    $param=$param." and  a.course_aggr_id not like 'comm_comm%' ";
		}
	   
    }    

	$sql = " select A.fathers_annual_income, A.hod_remark,A.acad_remark,IF((A.hod_status='1' AND A.acad_status='1'),'Approved',(IF((A.hod_status='2' or  A.acad_status='2'),'Rejected','Pending'))) AS both_status_string,
case (A.hod_status) when '0' then 'Pending' when '1' then 'Approved' when '2' then 'Rejected'   end as hod_status,
case (A.acad_status) when '0' then 'Pending' when '1' then 'Approved' when '2' then 'Rejected'   end as acad_status,A.dept_id,A.course_id,A.branch_id,A.dname,A.cname,A.bname ".$join_sel2." ,A.admn_no,A.stu_name,A.category,A.physically_challenged, count(subject_id)as tot,group_concat(subject_id) as sub_id,group_concat(name) as sub_name,group_concat(sub_id) as subject, group_concat(DISTINCT to_be_app_sem ORDER BY to_be_app_sem ASC SEPARATOR ',') as semester,A.transaction_id,A.fee_amt,A.fee_date,GROUP_CONCAT(concat(name,'[',subject_id,']')) AS mix_sub_list
 from(
select  od.fathers_annual_income,a.admn_no,a.timestamp as t, a.hod_remark,a.acad_remark,a.hod_status,a.acad_status,a.form_id,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester as curr_semester,a.session_year,a.`session`,c.sub_seq,c.sub_id,d.semester as to_be_app_sem,d.aggr_id,
e.subject_id,e.name,f.name as dname,g.name as cname,h.name as bname,b.category,b.physically_challenged,rsf.transaction_id,rsf.fee_amt,DATE_FORMAT(rsf.fee_date,'%d-%m-%Y') as fee_date ".$join_sel1."
 from reg_summer_form a 
inner join reg_summer_fee  rsf on rsf.form_id=a.form_id and rsf.admn_no=a.admn_no 
inner join stu_other_details od on od.admn_no=a.admn_no
inner join user_details b on b.id=a.admn_no

 ".$join_append."
inner join reg_summer_subject c on c.form_id=a.form_id
inner join course_structure d on d.id=c.sub_id
inner join subjects e on e.id=d.id
inner join departments f on f.id=b.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
where ".$param."
order by b.dept_id,a.course_id,a.branch_id,a.admn_no,t DESC limit 10000 )A
group by A.admn_no,A.form_id
order by A.dept_id,A.course_id,A.branch_id,A.admn_no";

        $query = $this->db->query($sql);

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

  
    
    //----------------------------FAIL LIST----------------------------------------
    
    function get_fail_student_list($syear,$did,$cid,$bid,$sem)
    {
       //$param="a.grade='F'  and b.session_year='".$syear."' and b.`type`<>'J'" ;
        //$param="a.`status`='FAIL' and a.course<>'JRF' and a.course<>'MINOR'";
        $param="upper(a.course)<>'JRF' and upper(a.course)<>'MINOR'";
       if($did!='none')
       {
           $param=$param." and a.dept='".$did."'";
       }
       if($cid!='none')
       {
           $param=$param." and a.course='".$cid."'";
       }
       if($bid!='none')
       {
           $param=$param." and a.branch='".$bid."'";
       }
       if($sem!="")
       {
           $param=$param." and a.semester  like '%".$sem."%'";
       }
       
     /*   $sql = "select a.admn_no,concat_ws(' ',d.first_name,d.middle_name,d.last_name) as stu_name,a.grade,b.session_year,b.`session`,
b.`type`,c.dept_id,c.course_id,c.branch_id,e.name as dname,f.name as cname,g.name as bname,group_concat(b.subject_id) as sub_id,
group_concat(s.subject_id) as subject_id,group_concat(semester)as semester,group_concat(s.name) as sub_name
from marks_subject_description a 
inner join marks_master b on b.id=a.marks_master_id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join user_details d on d.id=a.admn_no
inner join departments e on e.id=c.dept_id
inner join cs_courses f on f.id=c.course_id
inner join cs_branches g on g.id=c.branch_id
inner join subjects  s on s.id=b.subject_id
where ".$param."
and b.`status`='Y' 
group by a.admn_no
order by c.dept_id,c.course_id,c.branch_id,c.semester";   */
       
      /* $sql="
select a.dept as dept_id,a.course as course_id,a.branch as branch_id,f.name AS dname,g.name AS cname,h.name AS bname,a.admn_no,
concat_ws(' ',u.first_name,u.middle_name,u.last_name)as stu_name,a.session_yr,a.`session`,a.semester,b.mis_sub_id,s.name as sub_name
,GROUP_CONCAT(b.sub_code) AS sub_id,GROUP_CONCAT(s.name) AS sub_name,GROUP_CONCAT(b.mis_sub_id) AS subject,
GROUP_CONCAT(a.semester) AS semester
from final_semwise_marks_foil a 
inner join final_semwise_marks_foil_desc b on a.id=b.foil_id
inner join subjects s on s.id=b.mis_sub_id
inner join user_details u on u.id=a.admn_no
INNER JOIN departments f ON f.id=a.dept
INNER JOIN cs_courses g ON g.id=a.course
INNER JOIN cs_branches h ON h.id=a.branch
where ".$param."
and b.grade='F'
GROUP BY A.admn_no
order by a.dept,a.course,a.branch";*/
       $sql="
 select d.name as dname,cs.name as cname ,br.name as bname, concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name)  stu_name,  k.* from
 (SELECT x.dept as dept_id,x.course as course_id,x.branch as  branch_id,x.admn_no,SUM(x.count_fail_sub) AS subject_fail_count, GROUP_CONCAT(x.sub_list SEPARATOR ', ') AS sub_id,GROUP_CONCAT(x.mis_sub_list SEPARATOR ', ') AS sub_name,GROUP_CONCAT(x.sem SEPARATOR ', ') AS semester,GROUP_CONCAT(  concat(x.session,'[TYPE-',x.type,',EXAMTYPE-',x.exam_type,']' ) SEPARATOR ', ') AS session_list 
FROM 

(
SELECT z.*
FROM(


SELECT B.*
FROM (
SELECT a.dept,a.course,a.branch, a.admn_no, a.type, a.session,a.status AS passfail, a.exam_type, a.semester AS sem, GROUP_CONCAT(IF((TRIM(b.grade)='F'), b.sub_code, NULL) SEPARATOR ', ') AS sub_list, GROUP_CONCAT(IF((TRIM(b.grade)='F'), (SELECT name FROM subjects WHERE id = b.mis_sub_id) , NULL) SEPARATOR ', ') AS mis_sub_list,   SUM(IF (TRIM(b.grade='F'), 1, 0)) AS count_fail_sub
FROM final_semwise_marks_foil a
INNER JOIN final_semwise_marks_foil_desc b ON a.id=b.foil_id  and  ".$param." 
/* and  a.admn_no='15ms000259'*/
GROUP BY a.admn_no, a.session_yr,a.semester,a.exam_type
ORDER BY a.session_yr DESC,a.semester DESC, a.exam_type DESC)B
GROUP BY B.admn_no,B.sem 

)z


)x GROUP BY x.admn_no  having subject_fail_count<>0  
)k
left join  departments d on   d.id=k.dept_id 
left join  cs_courses cs on   upper(cs.id)=upper(k.course_id)
left join  cs_branches br on   upper(br.id)=upper(k.branch_id)
left join user_details ud on ud.id=k.admn_no
order by k.dept_id,k.course_id,k.branch_id,k.admn_no
";

        $query = $this->db->query($sql);

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    
    
    //----------------------------------------------Comparision List---------------------------
    
    function get_comparision_student_list($syear,$did,$cid,$bid,$sem)
    {
       //$param="a.grade='F'  and b.session_year='".$syear."' and b.`type`<>'J'" ;
      //  $param="a.`status`='FAIL' and a.course<>'JRF' and a.course<>'MINOR'";
       // $param="a.session_year='".$syear."' and a.hod_status='1' and a.acad_status='1'";
        $param="upper(a.course)<>'JRF' and upper(a.course)<>'MINOR'";
        $param1="a.session_year='".$syear."' and a.hod_status='1' and a.acad_status='1'";
       if($did!='none')
       {
           $param=$param." and a.dept='".$did."'";
           $param1=$param1." and b.dept_id='".$did."'";
           
       }
       if($cid!='none')
       {
           $param=$param." and a.course='".$cid."'";
           $param1=$param1." and a.course_id='".$cid."'";
           
       }
       if($bid!='none')
       {
           $param=$param." and a.branch='".$bid."'";
           $param1=$param1." and a.branch_id='".$bid."'";
       }
       if($sem!="")
       {
           $param=$param." and a.semester  like '%".$sem."%'";
           $param1=$param1." and a.semester  like '%".$sem."%'";
           
       }
      

       $sql="
           select q.*,  COALESCE (p.sub_id,'Not Appeared') as appeared_list,COALESCE (p.sub_name,'Not Appeared') as sub_name1,COALESCE (p.semester,'Not Appeared') as applied_sem from 
(
 select d.name as dname,cs.name as cname ,br.name as bname, concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name)  stu_name,  k.* from
 (SELECT x.dept as dept_id,x.course as course_id,x.branch as  branch_id,GROUP_CONCAT(x.sem SEPARATOR ', ') AS semester,GROUP_CONCAT(  concat(x.session,'[TYPE-',x.type,',EXAMTYPE-',x.exam_type,']' ) SEPARATOR ', ') AS session_list ,x.admn_no, GROUP_CONCAT(x.mis_sub_list SEPARATOR ', ') AS sub_name,SUM(x.count_fail_sub) AS subject_fail_count,GROUP_CONCAT(x.sub_list SEPARATOR ', ') AS    failed_sub_list
FROM 

(
SELECT z.*
FROM(


SELECT B.*
FROM (
SELECT a.dept,a.course,a.branch, a.admn_no, a.type, a.session,a.status AS passfail, a.exam_type, GROUP_CONCAT(IF((TRIM(b.grade)='F'), a.semester, NULL) SEPARATOR ', ') AS sem, GROUP_CONCAT(IF((TRIM(b.grade)='F'), b.sub_code, NULL) SEPARATOR ', ') AS sub_list, GROUP_CONCAT(IF((TRIM(b.grade)='F'), (SELECT name FROM subjects WHERE id = b.mis_sub_id) , NULL) SEPARATOR ', ') AS mis_sub_list,   SUM(IF (TRIM(b.grade='F'), 1, 0)) AS count_fail_sub
FROM final_semwise_marks_foil a
INNER JOIN final_semwise_marks_foil_desc b ON a.id=b.foil_id  and  ".$param."
/* and  a.admn_no='15ms000259'*/
GROUP BY a.admn_no, a.session_yr,a.semester,a.exam_type
ORDER BY a.session_yr DESC,a.semester DESC, a.exam_type DESC)B
GROUP BY B.admn_no,B.sem 

)z


)x GROUP BY x.admn_no  having subject_fail_count<>0  
)k
left join  departments d on   d.id=k.dept_id 
left join  cs_courses cs on   upper(cs.id)=upper(k.course_id)
left join  cs_branches br on   upper(br.id)=upper(k.branch_id)
left join user_details ud on ud.id=k.admn_no
order by k.dept_id,k.course_id,k.branch_id,k.admn_no)q
left join
(select A.dept_id,A.course_id,A.branch_id,A.dname,A.cname,A.bname,A.admn_no,A.stu_name,group_concat(subject_id  SEPARATOR ', ') as sub_id,group_concat(name SEPARATOR ', ') as sub_name,group_concat(sub_id) as subject, group_concat(to_be_app_sem SEPARATOR ', ') as semester
 from(
select a.form_id, a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester as curr_semester,a.session_year,a.`session`,c.sub_seq,c.sub_id,d.semester as to_be_app_sem,d.aggr_id,
e.subject_id,e.name,f.name as dname,g.name as cname,h.name as bname
 from reg_summer_form a 
inner join user_details b on b.id=a.admn_no
inner join reg_summer_subject c on c.form_id=a.form_id
inner join course_structure d on d.id=c.sub_id
inner join subjects e on e.id=d.id
inner join departments f on f.id=b.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
where ".$param1."
order by b.dept_id,a.course_id,a.branch_id,a.admn_no)A
group by A.admn_no
order by A.dept_id,A.course_id,A.branch_id,A.admn_no)p

on p.admn_no=q.admn_no order by q.dept_id,q.course_id,q.branch_id,q.admn_no
";

        $query = $this->db->query($sql);

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

//----------------------------Subject List--------------------------------------------------------
    
    function get_report_subjectwise($syear,$did,$cid,$bid,$sem)
    {
       $param="a.session_year='".$syear."' and a.hod_status='1' and a.acad_status='1'" ;
       
       if($did!='none')
       {
           $param=$param." and ud.dept_id='".$did."'";
       }
       if($cid!='none')
       {
           $param=$param." and a.course_id='".$cid."'";
       }
       if($bid!='none')
       {
           $param=$param." and a.branch_id='".$bid."'";
       }
       if($sem!="")
       {
           $param=$param." and d.semester  like '%".$sem."%'";
       }
       
        $sql = "select a.form_id,ud.dept_id,a.course_id,a.branch_id,f.name as dname,g.name as cname,h.name as bname,
		         group_concat(concat( concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name),'[',a.admn_no,']')  SEPARATOR ',\n')as stu_name,a.admn_no,
rss.sub_id,s.name as sub_name,cs.semester as semester,group_concat(admn_no SEPARATOR ',\n') as stu_admn_no,count(admn_no)as no_of_student
from reg_summer_form a 
inner join user_details ud on ud.id=a.admn_no
inner join reg_summer_subject rss on rss.form_id=a.form_id
inner join subjects s on s.id=rss.sub_id
inner join course_structure cs on cs.id=rss.sub_id
inner join departments f on f.id=ud.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
where ".$param."
group by rss.sub_id
order by ud.dept_id,a.course_id,a.branch_id,s.name,a.admn_no";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	//--------------------------------------GET REPORT SUBJECT MAPPING----------------------------------//
	
	function get_report_subject_mapping($syear,$did,$cid,$bid,$sem)
    {
       $param="a.session_year='".$syear."' and a.hod_status='1' and a.acad_status='1'" ;
       
       if($did!='none')
       {
           $param=$param." and ud.dept_id='".$did."'";
       }
       if($cid!='none')
       {
           $param=$param." and a.course_id='".$cid."'";
       }
       if($bid!='none')
       {
           $param=$param." and a.branch_id='".$bid."'";
       }
       if($sem!="")
       {
           $param=$param." and d.semester  like '%".$sem."%'";
       }
       
        $sql = "select A.sub_id as status,A.emp_no,a.form_id,ud.dept_id,a.course_id,a.branch_id,f.name as dname,g.name as cname,h.name as bname,group_concat(concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name)SEPARATOR ',\n')as stu_name,a.admn_no,
rss.sub_id,s.name as sub_name,cs.semester as semester,group_concat(admn_no SEPARATOR '\n') as stu_admn_no,count(admn_no)as no_of_student
from reg_summer_form a 
inner join user_details ud on ud.id=a.admn_no
inner join reg_summer_subject rss on rss.form_id=a.form_id
inner join subjects s on s.id=rss.sub_id
inner join course_structure cs on cs.id=rss.sub_id
inner join departments f on f.id=ud.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
left JOIN (
select b.sub_id,b.emp_no,a.creater_id from subject_mapping a join subject_mapping_des b on a.map_id=b.map_id where a.`session`='Summer' and a.session_year='".$syear."'
) A on A.sub_id = rss.sub_id
where ".$param."
group by rss.sub_id
order by ud.dept_id,a.course_id,a.branch_id,s.name,a.admn_no";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //------------------------------------Other student list----------------------------------------
    
    
        function get_other_student_list($syear,$did,$cid,$bid,$sem)
    {
            
       $param="a.session_year='".$syear."' AND a.hod_status<>'2' AND a.acad_status<>'2' and course_id<>'jrf' and a.`session`='Winter' and a.`type`='S'" ;
       $param1="a.session_year='".$syear."' AND a.hod_status<>'2' AND a.acad_status<>'2' and course_id<>'jrf' and a.`session`='Winter' and a.`type`='S'  and a.reason='Special'" ;
       
       if($did=='comm' && $cid=='comm' && $bid=='comm'){
         $param=$param." and (d.semester  like '1_1') or (d.semester  like '1_2') or (d.semester  like '2_1') or (d.semester  like '2_2')";
         $param1=$param1." and (d.semester  like '1_1') or (d.semester  like '1_2') or (d.semester  like '2_1') or (d.semester  like '2_2')";
       }
       else{ 
      
       if($did!='none' && $did!='comm')
       {
           $param=$param." and b.dept_id='".$did."'";
           $param1=$param1." and b.dept_id='".$did."'";
       }
       if($cid!='none' && $cid!='comm')
       {
           $param=$param." and a.course_id='".$cid."'";
           $param1=$param1." and a.course_id='".$cid."'";
       }
       if($bid!='none' && $bid!='comm' )
       {
           $param=$param." and a.branch_id='".$bid."'";
           $param1=$param1." and a.branch_id='".$bid."'";
       }
      
       if($sem!="" && $did!='comm')
       {
           $param=$param." and d.semester  like '%".$sem."%'";
           $param1=$param1." and d.semester  like '%".$sem."%'";
       }
    }    
        $sql = "(SELECT A.dept_id,A.course_id,A.branch_id,A.dname,A.cname,A.bname,A.admn_no,A.stu_name, GROUP_CONCAT(subject_id SEPARATOR ', ') AS sub_id, GROUP_CONCAT(name) AS sub_name, GROUP_CONCAT(sub_id SEPARATOR ', ') AS subject, GROUP_CONCAT(to_be_app_sem) AS semester
,case A.hod_status when '1' then 'Approved' when '0' then 'Pending' when '2' then 'Rejected' end as hod_status ,
case A.acad_status when '1' then 'Approved' when '0' then 'Pending' when '2' then 'Rejected' end as acad_status,'July' as mon_status            
FROM(
SELECT a.form_id, a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, b.dept_id,a.course_id,a.branch_id,a.semester AS curr_semester,a.session_year,a.`session`,c.sub_seq,c.sub_id,d.semester AS to_be_app_sem,d.aggr_id, e.subject_id,e.name,f.name AS dname,g.name AS cname,h.name AS bname,a.hod_status,a.acad_status
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN reg_exam_rc_subject c ON c.form_id=a.form_id
INNER JOIN course_structure d ON d.id=c.sub_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN departments f ON f.id=b.dept_id
INNER JOIN cs_courses g ON g.id=a.course_id
INNER JOIN cs_branches h ON h.id=a.branch_id
WHERE ".$param."
ORDER BY b.dept_id,a.course_id,a.branch_id,a.admn_no)A
GROUP BY A.admn_no
ORDER BY A.dept_id,A.course_id,A.branch_id,A.admn_no,mon_status)
union
(SELECT A.dept_id,A.course_id,A.branch_id,A.dname,A.cname,A.bname,A.admn_no,A.stu_name, GROUP_CONCAT(subject_id SEPARATOR ', ') AS sub_id, GROUP_CONCAT(name) AS sub_name, GROUP_CONCAT(sub_id SEPARATOR ', ') AS subject, GROUP_CONCAT(to_be_app_sem) AS semester
,case A.hod_status when '1' then 'Approved' when '0' then 'Pending' when '2' then 'Rejected' end as hod_status ,
case A.acad_status when '1' then 'Approved' when '0' then 'Pending' when '2' then 'Rejected' end as acad_status,'August' as mon_status            
FROM(
SELECT a.form_id, a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name, b.dept_id,a.course_id,a.branch_id,a.semester AS curr_semester,a.session_year,a.`session`,c.sub_seq,c.sub_id,d.semester AS to_be_app_sem,d.aggr_id, e.subject_id,e.name,f.name AS dname,g.name AS cname,h.name AS bname,a.hod_status,a.acad_status
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN reg_other_subject c ON c.form_id=a.form_id
INNER JOIN course_structure d ON d.id=c.sub_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN departments f ON f.id=b.dept_id
INNER JOIN cs_courses g ON g.id=a.course_id
INNER JOIN cs_branches h ON h.id=a.branch_id
WHERE ".$param1."
ORDER BY b.dept_id,a.course_id,a.branch_id,a.admn_no)A
GROUP BY A.admn_no
ORDER BY A.dept_id,A.course_id,A.branch_id,A.admn_no,mon_status)


";

        $query = $this->db->query($sql);

       // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    //-------------------------------------------------------------------------------
    
    function get_course_summer($syear,$sess)
    {
       
       
        $sql = "select distinct(a.course_id) as id ,b.name from subject_mapping a
inner join cs_courses b on a.course_id=b.id
where a.session_year=? and a.`session`=?
order by a.course_id";

        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_summer_student_by_course($syear,$sess,$cid)
    {
              
        $sql = "select a.form_id,a.admn_no,d.id as cid,c.id as did,e.id as bid,c.name as dname,d.name as cname,e.name as bname,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as stu_name,a.session_year,a.`session` from reg_summer_form a 
inner join reg_summer_subject b on b.form_id=a.form_id
inner join user_details ud on ud.id=a.admn_no
inner join departments c on c.id=ud.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
inner join course_structure f on f.id=b.sub_id
inner join subjects g on g.id=b.sub_id
where a.session_year=? and a.`session`=? and d.id=?
and a.hod_status='1' and a.acad_status='1'
group by a.admn_no
order by d.id,c.id,e.id,a.admn_no
";

        $query = $this->db->query($sql,array($syear,$sess,$cid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_summer_student_by_admn_no($syear,$sess,$admno)
    {
       
       
        $sql = "select  x.admn_no,group_concat(concat(x.sub_id,'[' , x.subject_id, ']','(',x.name,')' ) ) as sub_list
,group_concat(x.semester)  as sem_list from
(select A.form_id,A.admn_no,p.sub_id,s.subject_id,s.name,cs.semester 
from (
select a.* from reg_summer_form a 
inner join reg_summer_subject b on a.form_id=b.form_id
where a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1'
and a.admn_no=?)A
inner join reg_summer_subject p on A.form_id=p.form_id
inner join subjects s on s.id=p.sub_id
inner join course_structure cs on cs.id=p.sub_id
group by p.sub_id,cs.semester)x
group by x.admn_no



";

        $query = $this->db->query($sql,array($syear,$sess,$admno));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    //-------------------------------------Other
    
        
    

}

?>