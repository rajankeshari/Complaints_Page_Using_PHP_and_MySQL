<?php
	class Stu_details extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		function get_details($admn_no)
		{
			$this->load->database();
			
                        
                        $q="SELECT a.admn_no,a.enrollment_year,b.dept_id,a.course_id,a.branch_id,a.semester,
concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,c.duration,
(c.duration*2)as tot_sem,if(f.admn_no is not null,concat_ws(' ',c.name,'[Honours]'),c.name)as cname,
CASE WHEN e.enrollment_year < '2015' AND e.branch_id='ap' THEN 'Applied Physics' ELSE d.name END bname,
e.enrollment_year, IF(f.admn_no IS NOT NULL, CONCAT_WS(' ',c.name,'[Minor]'),c.name) AS cname_m
FROM stu_academic a
INNER JOIN user_details b ON a.admn_no=b.id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join stu_academic e on e.admn_no=b.id
left join hm_form f on (f.admn_no=a.admn_no and f.honours='1' and f.honour_hod_status='Y')

WHERE a.admn_no='".$admn_no."'";
                       
                       
                       
			$query = $this->db->query($q);
                       //echo $this->db->last_query(); die();
			return $query->result();
		}
                function alumni_get_details($admn_no)
		{
			$this->load->database();
			
                        
                        $q="SELECT a.admn_no,a.enrollment_year,b.dept_id,a.course_id,a.branch_id,a.semester,
concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,c.duration,
(c.duration*2)as tot_sem,if(f.admn_no is not null,concat_ws(' ',c.name,'[Honours]'),c.name)as cname,
CASE WHEN e.enrollment_year < '2015' AND e.branch_id='ap' THEN 'Applied Physics' ELSE d.name END bname,
e.enrollment_year, IF(f.admn_no IS NOT NULL, CONCAT_WS(' ',c.name,'[Minor]'),c.name) AS cname_m
FROM alumni_academic a
INNER JOIN alumni_details b ON a.admn_no=b.id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join alumni_academic e on e.admn_no=b.id
left join hm_form f on (f.admn_no=a.admn_no and f.honours='1' and f.honour_hod_status='Y')

WHERE a.admn_no='".$admn_no."'";
                       
                       
                       
			$query = $this->db->query($q);
                       //echo $this->db->last_query(); die();
			return $query->result();
		}
                function get_details_minor($admn_no)
		{
			$this->load->database();
			//semester is being fixed 5 just becase all semester have same dept course branch, not joining semester will 
                        //return more than one record.
                        
                        $q="select a.admn_no as stu_name,a.dept as dept_id,a.course as course_id,a.branch as branch_id,b.name as dname,SUBSTRING_INDEX(c.name,' ',1) as cname,d.name as bname from final_semwise_marks_foil a
inner join departments b on b.id=a.dept
inner join cs_courses c on c.id=a.course
inner join cs_branches d on d.id=a.branch
where a.admn_no='".$admn_no."' and a.course='MINOR' and a.semester=5";
                       
                       
                       
			$query = $this->db->query($q);
                       //echo $this->db->last_query(); die();
			return $query->result();
		}
		function get_branch($branch_id)
		{
			$this->load->database();
			$q = "SELECT name FROM branches WHERE id = '$branch_id'";
			$query = $this->db->query($q);
			return $query->result();
		}
		function get_course($course_id)
		{
			$this->load->database();
			$q = "SELECT name FROM courses WHERE id = '$course_id'";
			$query = $this->db->query($q);
			return $query->result();
		}
		function get_result($admn_no)
		{
			$this->load->database();
			/*$q = "SELECT DISTINCT stu_name,adm_no,crdhrs,subje_name,tabulation1.grade,sem,f.points
				  FROM tabulation1 
				  INNER JOIN dip_m_semcode on tabulation1.sem_code = dip_m_semcode.semcode
				  INNER JOIN grade_points as f on tabulation1.grade = f.grade
				  WHERE adm_no = '$admn_no' ORDER BY sem";*/
                        
                              $q = "SELECT DISTINCT stu_name,adm_no,crdhrs,subje_order,subje_name,theory,sessional,practiocal,totalmarks,tabulation1.grade,gpa,ogpa,sem,f.points,ysession,wsms,examtype,rpublished
        FROM tabulation1
        INNER JOIN dip_m_semcode ON tabulation1.sem_code = dip_m_semcode.semcode
        INNER JOIN grade_points AS f ON tabulation1.grade = f.grade
        WHERE adm_no = '".$admn_no."'
        ORDER BY sem,subje_order";
			$query = $this->db->query($q);
                        //echo $this->db->last_query();die();
			return $query->result();
		}
		function grade_sheet_details($admn_no) 
		{
			   
			$sql = "select C.*,f.points,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
			(select B.*,d.sequence  as seq from
			(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from 
			(select a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
			inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' ) A
			inner join subjects as c on A.subject_id=c.id ) B
			inner join course_structure as d on B.subject_id=d.id ) C
			inner join subject_mapping as e on C.sub_map_id = e.map_id 
			inner join grade_points as f on C.grade = f.grade
			 group by C.sid order by e.semester,C.seq+0 asc";



			        $query = $this->db->query($sql);
				
			        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		function new_table_fail($adm_no)
		{
			$sql = "select final_semwise_marks_foil.semester, final_semwise_marks_foil.status, concat_ws('',s.subje_name,e.name) as name
					from final_semwise_marks_foil
					inner join final_semwise_marks_foil_desc 
					on final_semwise_marks_foil.id = final_semwise_marks_foil_desc.foil_id
					left join tabulation1 s on s.subje_code=final_semwise_marks_foil_desc.sub_code
					inner join subjects as e on final_semwise_marks_foil_desc.mis_sub_id = e.id
					
					where final_semwise_marks_foil.admn_no = '$adm_no'";



			        $query = $this->db->query($sql);
				//echo $this->db->last_query();die();
			       // if ($this->db->affected_rows() >= 0) {
                                 if ($query->num_rows() > 0){
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		 function get_others_sub_marks($adm_no,$sem,$cid,$et,$sy,$sess)
	    {
				//et as S has been added due to amrit lal 14MS0000034, may be delete later
				
                     if($et=='R' || $et=='J' /*|| $et=='S'*/){
	      
					 $sql ="select x.* from (select A.*,concat_ws('',c.name) as name,SUBSTRING_INDEX(d.aggr_id,'_',1) as subtype from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and course='".$cid."' and session_yr='".$sy."' and type='".$et."'and session='".$sess."'))A
	               /*left join tabulation1 s on s.subje_code=A.sub_code*/
	               left join subjects as c on A.mis_sub_id=c.id
                       left join course_structure as d on d.id=c.id
	                 group by subtype,A.sub_code)x group by x.sub_code";
                     }
                     else{
                         $sql ="select x.* from (select A.*,concat_ws('',c.name) as name,SUBSTRING_INDEX(d.aggr_id,'_',1) as subtype from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and course='".$cid."' and session_yr='".$sy."' and type='".$et."'and session='".$sess."'))A
	               /*left join tabulation1 s on s.subje_code=A.sub_code*/
	               left join subjects as c on A.sub_code=c.subject_id
                       left join course_structure as d on d.id=c.id
                       /*where SUBSTRING_INDEX(d.aggr_id,'_',1)='".$cid."'*/
	                 group by subtype,A.sub_code)x group by x.sub_code";
                     }
	        $query = $this->db->query($sql); 
              //  if($sem==5)
	       //  echo $this->db->last_query();die();
                 //die();             
	      //  if ($this->db->affected_rows() > 0) {
                if ($query->num_rows() > 0){
	            return $query->result();
	        } else {
	            return FALSE;
	        }        
	    }  
            
             function alumni_get_others_sub_marks($adm_no,$sem,$cid,$et,$sy,$sess)
	    {
                     if($et=='R' || $et=='J'){
	      
					 $sql ="select x.* from (select A.*,concat_ws('',c.name) as name,SUBSTRING_INDEX(d.aggr_id,'_',1) as subtype from (SELECT * FROM alumni_final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM alumni_final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and course='".$cid."' and session_yr='".$sy."' and type='".$et."'and session='".$sess."'))A
	               /*left join tabulation1 s on s.subje_code=A.sub_code*/
	               left join subjects as c on A.mis_sub_id=c.id
                       left join course_structure as d on d.id=c.id
	                 group by subtype,A.sub_code)x group by x.sub_code";
                     }
                     else{
                         $sql ="select x.* from (select A.*,concat_ws('',c.name) as name,SUBSTRING_INDEX(d.aggr_id,'_',1) as subtype from (SELECT * FROM alumni_final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM alumni_final_semwise_marks_foil
	              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and course='".$cid."' and session_yr='".$sy."' and type='".$et."'and session='".$sess."'))A
	               /*left join tabulation1 s on s.subje_code=A.sub_code*/
	               left join subjects as c on A.sub_code=c.subject_id
                       left join course_structure as d on d.id=c.id
                       /*where SUBSTRING_INDEX(d.aggr_id,'_',1)='".$cid."'*/
	                 group by subtype,A.sub_code)x group by x.sub_code";
                     }
	        $query = $this->db->query($sql); 
              //  if($sem==5)
	       //  echo $this->db->last_query();die();
                 //die();             
	      //  if ($this->db->affected_rows() > 0) {
                if ($query->num_rows() > 0){
	            return $query->result();
	        } else {
	            return FALSE;
	        }        
	    }  
            
            //--------------------@anuj------------------------------------------------------
            function max_exam_type_semBased($admn_no,$sem)
            {
                if($sem==10){$sem='X';}
//                $sql="SELECT b.sem,MAX(a.examtype) AS max_exam,a.sem_code FROM tabulation1 AS a
//                        inner join dip_m_semcode b on a.sem_code=b.semcode
//                        WHERE a.adm_no=? AND b.sem=?";
//                $sql="
//SELECT ysession, wsms, examtype,sem_code
//FROM tabulation1 a
//WHERE a.adm_no = ? AND
//RIGHT(a.sem_code, 1) = ?
//GROUP BY a.ysession,a.examtype
//ORDER BY a.ysession desc,a.examtype desc limit 1";
                $sql="SELECT ysession, wsms, examtype,sem_code FROM tabulation1 a WHERE a.adm_no = ? AND RIGHT(a.sem_code, 1) = ? 
GROUP BY a.ysession,a.sem_code,a.examtype,a.wsms ORDER BY a.ysession desc,cast(reverse(a.sem_code) as unsigned) desc,a.wsms desc,a.examtype desc limit 1";

                    $query = $this->db->query($sql,array($admn_no,$sem));
                    
                    //echo $this->db->last_query(); die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0){
                    
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            function alumni_max_exam_type_semBased($admn_no,$sem)
            {
                if($sem==10){$sem='X';}
//                $sql="SELECT b.sem,MAX(a.examtype) AS max_exam,a.sem_code FROM tabulation1 AS a
//                        inner join dip_m_semcode b on a.sem_code=b.semcode
//                        WHERE a.adm_no=? AND b.sem=?";
//                $sql="
//SELECT ysession, wsms, examtype,sem_code
//FROM tabulation1 a
//WHERE a.adm_no = ? AND
//RIGHT(a.sem_code, 1) = ?
//GROUP BY a.ysession,a.examtype
//ORDER BY a.ysession desc,a.examtype desc limit 1";
                $sql="SELECT ysession, wsms, examtype,sem_code FROM alumni_tabulation1 a WHERE a.adm_no = ? AND RIGHT(a.sem_code, 1) = ? 
GROUP BY a.ysession,a.sem_code,a.examtype,a.wsms ORDER BY a.ysession desc,cast(reverse(a.sem_code) as unsigned) desc,a.wsms desc,a.examtype desc limit 1";

                    $query = $this->db->query($sql,array($admn_no,$sem));
                    
                    //echo $this->db->last_query(); die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0){
                    
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            
            function get_result_from_tabulation1($admn_no,$semcode,$et,$y,$wsms)
            {
                /*$sql="select * from tabulation1 where adm_no=? and sem_code=? and examtype=? order by subje_order";*/
                
               /* $sql="select a.*,b.sem from tabulation1 a
inner join dip_m_semcode b on b.semcode=a.sem_code
where a.adm_no=? and a.sem_code=? and a.examtype=? and ysession=? group by subje_code order by a.subje_order";*/

$sql="select A.* from (SELECT a.*,b.sem
FROM tabulation1 a
INNER JOIN dip_m_semcode b ON b.semcode=a.sem_code
WHERE a.adm_no=? AND a.sem_code=? AND a.examtype=? AND a.ysession=? and a.wsms=?
GROUP BY  a.subje_code)A
GROUP BY A.subje_order
ORDER BY A.subje_order";
               

                    $query = $this->db->query($sql,array($admn_no,$semcode,$et,$y,$wsms));
            
                  //  if($this->db->affected_rows() >=0)
                      if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            function alumni_get_result_from_tabulation1($admn_no,$semcode,$et,$y,$wsms)
            {
                /*$sql="select * from tabulation1 where adm_no=? and sem_code=? and examtype=? order by subje_order";*/
                
               /* $sql="select a.*,b.sem from tabulation1 a
inner join dip_m_semcode b on b.semcode=a.sem_code
where a.adm_no=? and a.sem_code=? and a.examtype=? and ysession=? group by subje_code order by a.subje_order";*/

$sql="select A.* from (SELECT a.*,b.sem
FROM alumni_tabulation1 a
INNER JOIN dip_m_semcode b ON b.semcode=a.sem_code
WHERE a.adm_no=? AND a.sem_code=? AND a.examtype=? AND a.ysession=? and a.wsms=?
GROUP BY  a.subje_code)A
GROUP BY A.subje_order
ORDER BY A.subje_order";
               

                    $query = $this->db->query($sql,array($admn_no,$semcode,$et,$y,$wsms));
            
                  //  if($this->db->affected_rows() >=0)
                      if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            function get_number_of_semester($admn_no)
            {
               $sql="select group_concat(semester order by semester) as sem from reg_regular_form where admn_no=? and hod_status='1' and acad_status='1' group by admn_no";
                    $query = $this->db->query($sql,array($admn_no));
                    
                    //echo $this->db->last_query(); die();
                   // if($this->db->affected_rows() >=0)
                      if ($query->num_rows() > 0){
                     
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function get_number_of_semester_summer($admn_no)
            {
               $sql="
                    select GROUP_CONCAT(c.semester order by c.semester)  AS sem from reg_summer_form a
                    inner join reg_summer_subject b on a.form_id=b.form_id
                    inner join course_structure c on c.id=b.sub_id
                    where a.admn_no=? GROUP BY admn_no";
                    $query = $this->db->query($sql,array($admn_no));
                    
                    
                      if ($query->num_rows() > 0){
                     
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            
             function get_number_of_semester_from_other($admn_no,$sem)
            {
//               
                 $sql="SELECT GROUP_CONCAT(DISTINCT semester
ORDER BY semester) AS sem
FROM final_semwise_marks_foil
WHERE admn_no=? and semester<=?
GROUP BY admn_no ";
                 $query = $this->db->query($sql,array($admn_no,$sem));
                    
                   // echo $this->db->last_query(); die();
                
                   if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function alumni_get_number_of_semester_from_other($admn_no,$sem)
            {
//               
                 $sql="SELECT GROUP_CONCAT(DISTINCT semester
ORDER BY semester) AS sem
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? and semester<=?
GROUP BY admn_no ";
                 $query = $this->db->query($sql,array($admn_no,$sem));
                    
                   // echo $this->db->last_query(); die();
                
                   if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            

            function get_exam_type($admn_no,$sem,$cid)
            {
             /*  $sql="select `type` from reg_exam_rc_form where admn_no='".$admn_no."'  and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1'";*/
                 
              /* $sql="select A.* from (select `type` from reg_exam_rc_form where admn_no='".$admn_no."' and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1'
union
select `type` from reg_other_form where admn_no='".$admn_no."' and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1')A
order by A.`type` desc limit 1";*/ /* It was working*/
                 
        $sql="SELECT `type` FROM final_semwise_marks_foil WHERE admn_no='".$admn_no."' AND semester LIKE '%".$sem."%' and course='".$cid."'   and status='PASS' order by tot_cr_pts desc limit 1 "; /*and status='PASS'*/
                    $query = $this->db->query($sql);
                    
                    //echo $this->db->last_query();die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function alumni_get_exam_type($admn_no,$sem,$cid)
            {
             /*  $sql="select `type` from reg_exam_rc_form where admn_no='".$admn_no."'  and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1'";*/
                 
              /* $sql="select A.* from (select `type` from reg_exam_rc_form where admn_no='".$admn_no."' and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1'
union
select `type` from reg_other_form where admn_no='".$admn_no."' and semester like '%".$sem."%' AND hod_status='1' AND acad_status='1')A
order by A.`type` desc limit 1";*/ /* It was working*/
                 
        $sql="SELECT `type` FROM alumni_final_semwise_marks_foil WHERE admn_no='".$admn_no."' AND semester LIKE '%".$sem."%' and course='".$cid."'   and status='PASS' order by tot_cr_pts desc limit 1 "; /*and status='PASS'*/
                    $query = $this->db->query($sql);
                    
                    //echo $this->db->last_query();die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function get_exam_type_fail($admn_no,$sem,$cid)
            {
                              
        $sql="SELECT `type` FROM final_semwise_marks_foil WHERE admn_no='".$admn_no."' AND semester LIKE '%".$sem."%' and course='".$cid."'    order by tot_cr_pts desc limit 1 "; /*and status='PASS'*/
                    $query = $this->db->query($sql);
                    
                   // echo $this->db->last_query();die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function alumni_get_exam_type_fail($admn_no,$sem,$cid)
            {
                              
        $sql="SELECT `type` FROM alumni_final_semwise_marks_foil WHERE admn_no='".$admn_no."' AND semester LIKE '%".$sem."%' and course='".$cid."'    order by tot_cr_pts desc limit 1 "; /*and status='PASS'*/
                    $query = $this->db->query($sql);
                    
                   // echo $this->db->last_query();die();
                  //  if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            
            function get_final_details($id)
            {
				if($id){
               $sql="select * from final_semwise_marks_foil where id='".$id."'";
                    $query = $this->db->query($sql);
                    
                  //  echo $this->db->last_query(); die();
               //     if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
				}else{
					return false;
				}
            }
            function alumni_get_final_details($id)
            {
				if($id){
               $sql="select * from alumni_final_semwise_marks_foil where id='".$id."'";
                    $query = $this->db->query($sql);
                    
                  //  echo $this->db->last_query(); die();
               //     if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
				}else{
					return false;
				}
            }
            
            function get_stu_session_year_regular($admn_no,$sem)
            {
                $sql="select a.session_year,a.`session` from  reg_regular_form a where a.admn_no=? and a.semester=?
and a.hod_status='1' and a.acad_status='1'";
                    $query = $this->db->query($sql,array($admn_no,$sem));
                    
                
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
            function get_stu_session_year_summer($admn_no)
            {
                $sql="select a.session_year,a.`session` from  reg_summer_form a where a.admn_no=? 
and a.hod_status='1' and a.acad_status='1'";
                    $query = $this->db->query($sql,array($admn_no));
                    
                
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
                
            }
            
            function get_stu_session_year_other($admn_no,$sem,$cou_id)
            {
                
                // working till 2 sep 16 it was returning two record so changed
                
              /*  $sql="(SELECT a.session_year,a.`session`
FROM reg_exam_rc_form a
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1')union

(SELECT a.session_year,a.`session`
FROM reg_other_form a
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1')";

                
                $sql="select C.* from (
select A.* from
(
SELECT a.session_year,a.`session`
FROM reg_exam_rc_form a
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1')A
UNION 
select B.* from
(
SELECT a.session_year,a.`session`
FROM reg_other_form a
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1')B
)C order by C.session_year,C.session desc limit 1";
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem));*/ 
                    
                $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=? and status='PASS'
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1) and  status='PASS' 
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                               $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=? and status='PASS'
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1)
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
                    }
            }
            function alumni_get_stu_session_year_other($admn_no,$sem,$cou_id)
            {
                
                
                    
                $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=? and status='PASS'
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1) and status='PASS'
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                         $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=? and status='PASS'
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1)
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
                    }
            }
            function get_stu_session_year_other_fail($admn_no,$sem,$cou_id)
            {
                
               
                    
                $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=?
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1) and  status='PASS'
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                            
                $sql="SELECT *
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=?
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1) 
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                     return false;
                    }
                    }
            }
            function alumni_get_stu_session_year_other_fail($admn_no,$sem,$cou_id)
            {
                
               
                    
                $sql="SELECT *
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=?
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1)  and status='PASS' 
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                          $sql="SELECT *
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND `type`=(
SELECT `type`
FROM alumni_final_semwise_marks_foil
WHERE admn_no=? AND semester=? AND course=?
ORDER BY `session_yr` DESC,`type` DESC
LIMIT 1) 
ORDER BY id DESC
LIMIT 1";
                
                    $query = $this->db->query($sql,array($admn_no,$sem,$admn_no,$sem,$cou_id));
					 if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
						return false;
					
                    }
            }
	}		
            
            function get_details_for_transcript($id){
                $sql="select a.* from final_semwise_marks_foil a where a.admn_no=? and a.course<>'MINOR' and a.status='PASS'
order by a.semester desc limit 1";
                
                    $query = $this->db->query($sql,array($id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
									$sql="select a.* from final_semwise_marks_foil a where a.admn_no=? and a.course<>'MINOR'
order by a.semester desc limit 1";
                
                    $query = $this->db->query($sql,array($id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
                    }
            }
			
			function alumni_get_details_for_transcript($id){
                $sql="select a.* from alumni_final_semwise_marks_foil a where a.admn_no=? and a.course<>'MINOR' 
order by a.semester desc limit 1";
                
                    $query = $this->db->query($sql,array($id));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }
			function get_course_common($admn_no,$sem){
                $sql="select a.course from final_semwise_marks_foil a where a.admn_no=? and a.semester=?";
                $query = $this->db->query($sql,array($admn_no,$sem));
			//echo $this->db->last_query();	
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
            }

            
            //-----------------------------------------After CBCS--------------------------
			function get_subject_after_cbcs($syear,$sess,$admn_no){
                $sql="select t1.* from(
select t.* ,
CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END AS name,
CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END AS sub_type,
CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END AS lecture,
CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END AS tutorial,
CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END AS practical

from
(
select p.* from(
SELECT a.*
FROM final_semwise_marks_foil_desc_freezed a
INNER JOIN final_semwise_marks_foil_freezed b ON b.id=a.foil_id
WHERE b.session_yr=? AND b.`session`=? AND a.admn_no=?
order by a.cr_pts desc limit 100
)p group by p.sub_code

)t
LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=t.sub_code 
LEFT JOIN old_subject_offered oso ON oso.sub_code=t.sub_code
)t1 group by t1.sub_code
order by t1.lecture desc,t1.tutorial desc,t1.practical desc";
                $query = $this->db->query($sql,array($syear,$sess,$admn_no));
			//echo $this->db->last_query();	die();
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
            }
			
			function get_final_details_cbcs($id)
            {
				if($id){
               $sql="select * from final_semwise_marks_foil_freezed where id='".$id."'";
                    $query = $this->db->query($sql);
                    
                  //  echo $this->db->last_query(); die();
               //     if($this->db->affected_rows() >=0)
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
				}else{
					return false;
				}
            }
			
			function get_cbcs_result_declaration($id)
            {
				if($id){
               $sql="select published_on from final_semwise_marks_foil_freezed a WHERE a.admn_no=?  order by id desc limit 1";
                    $query = $this->db->query($sql,array($id));
                    
                  
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->row();
                    }
                    else
                    {
                        return false;
                    }
				}else{
					return false;
				}
            }
			
			function check_from_cbcs_registration($admn_no)
            {
				
               $sql="select a.* from reg_regular_form a where a.admn_no=? and  a.session_year='2019-2020'";
                    $query = $this->db->query($sql,array($admn_no));
                    
                  
                    if ($query->num_rows() > 0)
                    { 
                      
                         return $query->result();
                    }
                    else
                    {
                        return false;
                    }
				
            }
			
			
			
			

	}
?>