<?php
class Get_subject_jrf extends CI_Model
{


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}



	
	function  get_mtech_data($sy,$sess,$s_code,$sub_offer_id,$branch,$s_sem){
		$sub_offer_id1=$sub_offer_id;
		$type_chk=  substr($sub_offer_id, 0, 1); 
		$sub_offer_id=substr($sub_offer_id,  1);
		//$tbl2=( substr($sub_offer_id, 0, 1)=='c'? 'cbcs_subject_offered' :'old_subject_offered');
		
		  $date=date('Y-m-d H:i:s');
		  
		  if($type_chk=='c')
		  {
			  	$tbl2='cbcs_subject_offered';
				$tbl3='cbcs_subject_offered_desc';
		  	$query1=$this->db->query("select a.id from $tbl2 a 
			where a.session_year='$sy' and a.`session`='$sess'  and a.sub_code='$s_code' and a.course_id='jrf' and a.branch_id='$branch'
			group by a.id");
			//echo $this->db->last_query();
$sql1="select a.id from $tbl2 a 
			where a.session_year='$sy' and a.`session`='$sess' and a.sub_code='$s_code' and a.course_id='jrf' and a.branch_id='$branch' group by a.id";
		//  echo '<br>'.$sql1; //die();
			//  if(strtolower($this->session->userdata('id'))=='18dr0066')  echo '<br>'.$sql1.',num:'.$query1->num_rows(); 
				$rowcount = $query1->num_rows();
				if($rowcount==0){
					$sql="INSERT INTO $tbl2 
					SELECT null as id,a.session_year,a.session,a.dept_id,'jrf' as course_id,'$branch' as branch_id,'$s_sem' as semester,a.unique_sub_pool_id,a.unique_sub_id,a.sub_name,a.sub_code,a.lecture,a.tutorial,a.practical,a.credit_hours,a.contact_hours,a.sub_type,a.wef_year,a.wef_session,a.pre_requisite,a.pre_requisite_subcode,a.fullmarks,a.no_of_subjects,a.sub_category,a.sub_group,a.criteria,a.minstu,a.maxstu,a.remarks, created_by,'$date' as created_on,'' as last_updated_by,null as last_updated_on,a.action 
					FROM $tbl2 a
					WHERE a.id='$sub_offer_id'  and a.sub_code='$s_code' " ;
				     //echo '<br>'.$sql; //die();
					// if(strtolower($this->session->userdata('id'))=='18dr0066')  echo '<br>'.$sql; 
				    
				    $query = $this->db->query($sql);                      
					$lastinsert=($this->db->insert_id()? $type_chk.$this->db->insert_id():false);
					$lastinsert1=($this->db->insert_id()? $this->db->insert_id():false);
					
					
					$sql2="INSERT INTO $tbl3 
					SELECT null as desc_id, $lastinsert1 as sub_offered_id , a.part,a.emp_no, a.coordinator, a.sub_id, a.section from   $tbl3 a 	WHERE a.sub_offered_id='$sub_offer_id'  and a.sub_id='$s_code' " ;
					
				     //echo '<br>'.$sql; //die();
				// if(strtolower($this->session->userdata('id'))=='18dr0066') { echo '<br>'.$sql;echo '<br>'.$sql2; }
				    
				    $query = $this->db->query($sql2);                      
					
					
					
					return $lastinsert;
				}
				else
				{
					$result=$query1->result();
					return $type_chk.$result[0]->id;
				}
		  
				}
		  else if($type_chk=='o'){
			   	$tbl2='old_subject_offered';
				$tbl3='old_subject_offered_desc';
			$query1=$this->db->query("select a.id from $tbl2 a 
			where a.session_year='$sy' and a.`session`='$sess'  and  a.sub_code='$s_code' and a.course_id='jrf' and a.branch_id='$branch' group by a.id");
                  
		$sql1="select a.id from $tbl2 a 
			where a.session_year='$sy' and a.`session`='$sess' and a.sub_code='$s_code' and a.course_id='jrf' and a.branch_id='$branch' group by a.id";
		//  echo '<br>'.$sql1; //die();
			 // if(strtolower($this->session->userdata('id'))=='18dr0066')  echo '<br>'.$sql1.',num:'.$query1->num_rows(); 
				$rowcount = $query1->num_rows();
				if($rowcount==0){
				$sql="INSERT INTO $tbl2
					SELECT null as id,a.session_year,a.session,a.dept_id,'jrf' as course_id,'$branch' as branch_id,'$s_sem' as semester,a.unique_sub_pool_id,a.unique_sub_id,a.sub_name,a.sub_code,a.lecture,a.tutorial,a.practical,a.credit_hours,a.contact_hours,a.sub_type,a.wef_year,a.wef_session,a.pre_requisite,a.pre_requisite_subcode,a.fullmarks,a.no_of_subjects,a.sub_category,a.sub_group,a.criteria,a.minstu,a.maxstu,a.remarks,created_by,'$date' as created_on,'' as last_updated_by,null as last_updated_on,a.action, 0 as map_id 
					FROM $tbl2 a	WHERE a.id='$sub_offer_id' and a.sub_code='$s_code'";
				 // if(strtolower($this->session->userdata('id'))=='18dr0066')  echo '<br>'.$sql; 
				    $query = $this->db->query($sql);	
					
					$lastinsert=($this->db->insert_id()? $type_chk.$this->db->insert_id():false);
					$lastinsert1=($this->db->insert_id()? $this->db->insert_id():false);
					
					
					$sql2="INSERT INTO $tbl3 
					SELECT null as desc_id, $lastinsert1 as sub_offered_id , a.part,a.emp_no, a.coordinator, a.sub_id, a.section from   $tbl3 a 	WHERE a.sub_offered_id='$sub_offer_id'  and a.sub_id='$s_code' " ;
					
				     //echo '<br>'.$sql; //die();
					//if(strtolower($this->session->userdata('id'))=='18dr0066') { echo '<br>'.$sql;echo '<br>'.$sql2; }
				    
				    $query = $this->db->query($sql2);           
					
					 
			
				    return $lastinsert;
				
				}
				else
				{
					$result=$query1->result();
					return $type_chk.$result[0]->id;
				}
		
				}
		
	}


function getStudentAcdamicDetails($id){
			//return $this->db->get_where('stu_academic',array('admn_no'=>$id))->result();
			/*$sql=" select x.*,y.branch_id as inherit_branch_id from  stu_academic  x  join reg_regular_form y on x.admn_no=y.admn_no and  y.hod_status='1' and y.acad_status='1' and  y.admn_no='$id' 			limit 1";*/
			$sql="select x.*,x.branch_id as inherit_branch_id from  stu_academic  x where x.admn_no='".$id."'";
			$query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
			}

function getbasicdetails ($id){
				return $this->db->get_where('user_details',array('id'=>$id))->result();
			}

function getcoursename($course_name){
	return $this->db->get_where('cbcs_courses',array('id'=>$course_name))->result();
}
function getbranchname($branch_name){
	return $this->db->get_where('cbcs_branches',array('id'=>$branch_name))->result();
}
function getdates($date){
	 $myquery = "select * from sem_date_open_close_tbl order by id desc limit 1";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}

function getresultstatus($id){
	$myquery = "select * from (select * from final_semwise_marks_foil_freezed where admn_no='$id' order by actual_published_on desc limit 1000)x group by x.semester";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}
function getbacklogpapers($id){
	/*$myquery = "select a.sub_code, c.name, c.type, b.semester, a.grade from
(select * from (select * from final_semwise_marks_foil_freezed where admn_no=? order by actual_published_on desc limit 1000)x group by x.semester) b
inner join final_semwise_marks_foil_desc_freezed a on b.id=a.foil_id
inner join subjects c on c.id=a.mis_sub_id
where a.grade='F' order by b.semester";*/

    $myquery="
SELECT v.*,IFNULL(cso.id,oso.id) as sub_offered_id, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
(CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
     s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,
         IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,
             (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
                 IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$id'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no AND fd.grade='F'
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)";

        $query = $this->db->query($myquery,$id);
      //  echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}

function get_pass_papers($id){
    $myquery="
SELECT v.*, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
(CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END)) AS subcode,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
     s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,
         IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,
             (CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
                 IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical, IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial, IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$id'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no AND fd.grade<>'F'
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id
LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)";

        $query = $this->db->query($myquery,$id);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}


//subject offer in current semester.
function present_offered_subject($id){
    $sql="(select x.subject_code from
    (SELECT a.*
    FROM cbcs_stu_course a
    WHERE a.admn_no='$id'
    ) x
    join
    (SELECT a.session_year,a.session
    FROM reg_regular_form a
    WHERE a.admn_no='$id' order by a.semester desc limit 1) y
    on y.session_year=x.session_year and y.session=x.session)
    union
    (select x.subject_code from
    (SELECT a.*
    FROM old_stu_course a
    WHERE a.admn_no='$id'
    ) x
    join
    (SELECT a.session_year,a.session
    FROM reg_regular_form a
    WHERE a.admn_no='$id' order by a.semester desc limit 1) y
    on y.session_year=x.session_year and y.session=x.session)";
    $query=$this->db->query($sql);
		//echo $this->db->last_query();die();
    return $query->result();
}


function course_offer_check($sess,$sy,$id){
    $sql="(select a.sub_code from cbcs_subject_offered a where a.session_year='$sy' and a.`session`='$sess')
union
(select a.sub_code from old_subject_offered a where a.session_year='$sy' and a.`session`='$sess')";
/*$sql="(
SELECT a.sub_code,b.alternate_subject_code,c.alternate_subject_code
FROM cbcs_subject_offered a
left join alternate_course b on b.session_year=a.session_year and b.`session`=a.`session` and b.admn_no='$id' and b.old_subject_code=a.sub_code
left join alternate_course_all c on a.sub_code=b.old_subject_code
where a.session_year='$sy' and a.`session`='$sess') UNION
(
SELECT a.sub_code,b.alternate_subject_code,c.alternate_subject_code
FROM old_subject_offered a
left join alternate_course b on b.session_year=a.session_year and b.`session`=a.`session` and b.admn_no='$id' and b.old_subject_code=a.sub_code
left join alternate_course_all c on a.sub_code=b.old_subject_code
where a.session_year='$sy' and a.`session`='$sess')";*/

$query=$this->db->query($sql);
    return $query->result();
}


//check for alternate subject---------------------------------
function alternate_course_check($sess,$sy,$id){
    /*$sql="(select a.sub_code from cbcs_subject_offered a where a.session_year='$sy' and a.`session`='$sess')
union
(select a.sub_code from old_subject_offered a where a.session_year='$sy' and a.`session`='$sess')";*/
/*$sql="SELECT a.subject_id,ifnull(d.id,e.id) as sub_offered_id,b.alternate_subject_code as new_code1,b.alternate_subject_name as new_sub1,b.alternate_subject_lecture as l1,b.alternate_subject_tutorial as t1,b.alternate_subject_practical as p1,c.alternate_subject_code as new_code2,c.alternate_subject_name as new_sub2,c.alternate_subject_lecture as l2,c.alternate_subject_tutorial as t2,c.alternate_subject_practical as p2
FROM subjects a
left join alternate_course b on b.admn_no='$id' and b.old_subject_code=a.subject_id and b.session_year='$sy' AND b.`session`='$sess'
left join alternate_course_all c on a.subject_id=c.old_subject_code
LEFT JOIN cbcs_subject_offered d on trim(a.subject_id)=d.sub_code
LEFT JOIN old_subject_offered e on trim(a.subject_id)=e.sub_code
#where a.subject_id='ESC14151'
group by trim(a.subject_id)";*/
$sql="SELECT a.subject_id,
#IF((CASE WHEN d.id IS NULL THEN concat('c',d.id) ELSE concat('o',e.id) END)) AS subcode,
if(d.id is null,concat('o',e.id),concat('c',d.id)) as sub_offer_id,
/*ifnull(d.id,e.id) as sub_offered_id,*/b.alternate_subject_code AS new_code1,b.alternate_subject_name AS new_sub1,b.alternate_subject_lecture AS l1,b.alternate_subject_tutorial AS t1,b.alternate_subject_practical AS p1,c.alternate_subject_code AS new_code2,c.alternate_subject_name AS new_sub2,c.alternate_subject_lecture AS l2,c.alternate_subject_tutorial AS t2,c.alternate_subject_practical AS p2
FROM subjects a
LEFT JOIN cbcs_subject_offered d on trim(a.subject_id)=d.sub_code
LEFT JOIN old_subject_offered e on trim(a.subject_id)=e.sub_code
LEFT JOIN alternate_course b ON b.admn_no='$id' AND b.old_subject_code=a.subject_id AND b.session_year='$sy' AND b.`session`='$sess'
LEFT JOIN alternate_course_all c ON a.subject_id=c.old_subject_code  group by trim(a.subject_id)";

$query=$this->db->query($sql);
//echo  $this->db->last_query(); exit;
    return $query->result();
}

function gettotalcredits($courseid, $branchid){
	$myquery = "select * from cbcs_curriculam_master where cbcs_credit_points_master=(select id from cbcs_credit_points_master where course_id=? and branch_id=?)";

        $query = $this->db->query($myquery,array($courseid, $branchid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
}

function get_current_semester($id){
    $sql="select a.semester from stu_academic a where a.admn_no='$id'";
    $query=$this->db->query($sql);
    return $query->result();
}

function get_offered_subject_list($sem,$course,$branch,$sess,$sy,$en_year){
    if($en_year < 2019)
        $table='old_subject_offered';
    else
        $table='cbcs_subject_offered';
    /*$sql="(SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
FROM old_subject_offered b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
group by b.id
order by b.id)
union
(SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
FROM cbcs_subject_offered b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
group by b.id
order by b.id)";*/
    $sql="SELECT if($en_year < 2019,concat('o',b.id),concat('c',b.id)) as id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
FROM $table b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
group by b.id
order by b.id";
    // $sql="SELECT b.*
    // FROM cbcs_subject_offered b
    // where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
    // group by b.id
    // order by b.id";
    $query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
        return $query->result();
}

function get_offered_subject_list_jrf($sess,$sy,$dept){

        
     $sql="  select x.*,cs_branches.name  from (SELECT   'OLD'as type, concat('o',b.id) as id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,b.course_id,b.branch_id FROM old_subject_offered b where b.dept_id='$dept'  and  b.session_year='$sy' and b.`session`='$sess' /*and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))*/ group by b.id 
	         union 
	        SELECT 'CBCS' as type,concat('c',b.id) as id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,b.course_id,b.branch_id FROM cbcs_subject_offered b where b.dept_id='$dept' and  b.session_year='$sy' and b.`session`='$sess' /*and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))*/ group by b.id 
			
	          order by course_id ,branch_id,semester )x
			  left join cs_branches   on x.branch_id=cs_branches.id   order by FIELD(x.course_id, 'jrf') desc , x.branch_id,x.semester
			  ";
	//	 echo $sql; exit;
   
    // $sql="SELECT b.*
    // FROM cbcs_subject_offered b
    // where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
    // group by b.id
    // order by b.id";
    $query=$this->db->query($sql);
			/* if(strtolower($this->session->userdata('id'))=='18dr0177'){
		   echo $this->db->last_query(); die();
	}*/
	
    //echo $this->db->last_query();exit;
        return $query->result();
}



function get_cbcs_elective_type_list($sem,$course,$branch,$sess,$sy){
    $sql="SELECT b.sub_category
FROM cbcs_subject_offered b
WHERE b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and INSTR (b.sub_category,'DE')
group by b.sub_category";
$query=$this->db->query($sql);
    //echo $this->db->last_query();
        return $query->result();
}

function get_cbcs_offered_elective_subject_list($sem,$course,$branch,$sess,$sy){
    $sql="SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,'cbcs' as type
FROM cbcs_subject_offered b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and INSTR (b.sub_category,'DE') /*(!INSTR (b.sub_category,'DC') and !INSTR (b.sub_category,'DP'))*/
group by b.id
order by b.id";
    // $sql="SELECT b.*
    // FROM cbcs_subject_offered b
    // where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
    // group by b.id
    // order by b.id";
    $query=$this->db->query($sql);
    //echo $this->db->last_query();
        return $query->result();
}

    function get_old_offered_elective_subject_list($sem,$course,$branch,$sess,$sy){
    $sql="SELECT b.id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,'old' as type
FROM old_subject_offered b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and  INSTR (b.sub_category,'DE') /*(!INSTR (b.sub_category,'DC') and !INSTR (b.sub_category,'DP'))*/
group by b.id
order by b.id";
    // $sql="SELECT b.*
    // FROM cbcs_subject_offered b
    // where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
    // group by b.id
    // order by b.id";
    $query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
        return $query->result();
}

    function get_enrollment_year($id){
        $sql="select a.enrollment_year from stu_academic a where a.admn_no='$id'";
        $query=$this->db->query($sql);
    //echo $this->db->last_query();
        return $query->result();
    }



    function check_honours_list($id){
        $sql="select x.* from
(SELECT a.*,b.lecture,b.tutorial,b.practical,b.sub_type,b.semester
FROM old_stu_course a
join old_subject_offered b on b.id=a.sub_offered_id
WHERE a.admn_no='$id' and a.course='honour'
) x
join
(SELECT a.session_year,a.session
FROM reg_regular_form a
WHERE a.admn_no='$id' order by a.semester desc limit 1) y
on y.session_year=x.session_year and y.session=x.session";

$query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
        return $query->result();
    }
    function get_honours_list($sy,$sess,$sem,$id){
        $sql="select c.* from old_subject_offered c where c.session_year='$sy' and c.`session`='$sess' and c.semester='$sem' and c.course_id='honour'
and c.branch_id='cse'";

$query=$this->db->query($sql);
    //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function check_minor_list($id){
        $sql="select x.* from
(SELECT a.*,b.lecture,b.tutorial,b.practical,b.sub_type,b.semester
FROM old_stu_course a
join old_subject_offered b on b.id=a.sub_offered_id
WHERE a.admn_no='$id' and a.course='minor'
) x
join
(SELECT a.session_year,a.session
FROM reg_regular_form a
WHERE a.admn_no='$id' order by a.semester desc limit 1) y
on y.session_year=x.session_year and y.session=x.session";
    $query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
    return $query->result();
    }

    function get_minor_list($sy,$sess,$sem,$id,$branch_id){
        $sql="select c.* from old_subject_offered c where c.session_year='$sy' and c.`session`='$sess' and c.semester='$sem' and c.course_id='minor'
and c.branch_id='$branch_id'";

$query=$this->db->query($sql);
    //echo $this->db->last_query();
        return $query->result();
    }

    function get_drop_paper($id){//get subject not in pass, fail list.
        $sql="SELECT a.*,if(strcmp(s.id,a.sub_id)=0,s.subject_id,a.sub_id) as sub_id,IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END) IS NULL,
 s.name, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_name ELSE cso.sub_name END)) AS subname,IF((CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END) IS NULL, s.lecture,(CASE WHEN cso.sub_code IS NULL THEN oso.lecture ELSE cso.lecture END)) AS lecture,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END) IS NULL, s.practical, (CASE WHEN cso.sub_code IS NULL THEN oso.practical ELSE cso.practical END)) AS practical,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END) IS NULL, s.tutorial, (CASE WHEN cso.sub_code IS NULL THEN oso.tutorial ELSE cso.tutorial END)) AS tutorial,
IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END) IS NULL, s.`type`, (CASE WHEN cso.sub_code IS NULL THEN oso.sub_type ELSE cso.sub_type END)) AS sub_type, IF((CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END) IS NULL, s.credit_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.credit_hours ELSE cso.credit_hours END)) AS credit_hours, IF((CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END) IS NULL, s.contact_hours, (CASE WHEN cso.sub_code IS NULL THEN oso.contact_hours ELSE cso.contact_hours END)) AS contact_hours
FROM stu_exam_absent_mark a
left join subjects s on /*s.subject_id=a.sub_id*/ (s.id=a.sub_id or s.subject_id=a.sub_id)
left join cbcs_subject_offered cso on cso.sub_code=a.sub_id
left join old_subject_offered oso on oso.sub_code=a.sub_id
WHERE a.admn_no='$id' AND a.`status`='B' and if(strcmp(s.id,a.sub_id)=0,s.subject_id,a.sub_id) not in(SELECT v.sub_code
FROM
(
SELECT y.session_yr,y.session,y.dept,y.course,y.branch,y.semester, fd.mis_sub_id, fd.sub_code, fd.grade,y.admn_no
FROM


(
SELECT x.*
FROM

(
SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`
FROM final_semwise_marks_foil_freezed AS a
WHERE a.admn_no='$id'
ORDER BY a.semester,a.admn_no,a.actual_published_on DESC
LIMIT 10000)x
GROUP BY x.semester) y
JOIN
final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no
GROUP BY fd.sub_code
ORDER BY y.session_yr,y.dept,y.course,y.branch,y.semester,fd.sub_code)v
#LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id
#LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id
#LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)
)
GROUP BY a.sub_id";

$query=$this->db->query($sql);
  //  echo $this->db->last_query();
        return $query->result();
    }



    function insert_into_reg_regular_fees($data){
        $this->db->insert('reg_regular_fee', $data);
        // Return the id of inserted row
        return $idOfInsertedData = $this->db->insert_id();
    }

    function insert_into_reg_regular_form($data1){
        if($this->db->insert('reg_regular_form', $data1))
         return true;
        else
            return false;
    }
//=====================Anuj Start====================================================================================
    function get_time_table_map_id($sy,$sess,$did,$cid,$bid){

        $sql="(SELECT a.map_id FROM tt_map_old a WHERE a.session_year=? AND a.`session`=? AND a.dept_id=? AND a.course_id=? AND a.branch_id=?)
                union
                (SELECT a.map_id FROM tt_map_cbcs a WHERE a.session_year=? AND a.`session`=? AND a.dept_id=? AND a.course_id=? AND a.branch_id=?)";

        $query=$this->db->query($sql,array($sy,$sess,$did,$cid,$bid,$sy,$sess,$did,$cid,$bid));
		// /	echo	$this->db->last_query();die();
        return $query->result();
    }




    function get_time_table_clash($map_id,$sub_id){
			//print_r($map_id);
			$mp=implode(",",$map_id);
//			print_r($mp); exit;
        $sql="SELECT a.day,a.slot_no,a.venue_id,concat(a.day,a.slot_no,a.venue_id) AS dsv FROM tt_subject_slot_map_cbcs a WHERE a.map_id in($mp) AND a.subj_code=?
               union
              SELECT a.day,a.slot_no,a.venue_id,concat(a.day,a.slot_no,a.venue_id) AS dsv FROM tt_subject_slot_map_old a WHERE a.map_id in ($mp) AND a.subj_code=? ";
//echo $sql; exit;
	  $query = $this->db->query($sql,array($sub_id,$sub_id));

//	echo $this->db->last_query(); die();
    if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }

    }

    function stu_photo_sign($id){

        $sql="SELECT a.photopath,b.signpath FROM user_details a INNER JOIN stu_prev_certificate b ON b.admn_no=a.id
                WHERE a.id=? GROUP BY a.id";
    $query = $this->db->query($sql,array($id));
    if ($this->db->affected_rows() > 0) {
        return $query->row();
    } else {
        return false;
    }

    }

    function get_subject_type($sub_code){
        $sql="SELECT a.sub_type FROM cbcs_subject_offered a WHERE a.sub_code=? UNION SELECT a.sub_type FROM old_subject_offered a WHERE a.sub_code=?";
    $query = $this->db->query($sql,array($sub_code,$sub_code));
		//echo $this->db->last_query();
    if ($this->db->affected_rows() > 0) {
        return $query->row();
    } else {
        return false;
    }

    }

	function get_cbcs_open_elective($sess,$sy,$course_id){
    /*$sql=" SELECT p.* FROM(
 SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,'cbcs' as type FROM old_subject_offered b WHERE b.session_year=? AND b.session=? AND
(b.sub_category LIKE 'DE%' || b.sub_category LIKE 'OE%')
union
 SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,'cbcs' as type FROM cbcs_subject_offered b WHERE b.session_year=? AND b.session=? AND
(b.sub_category LIKE 'DE%' || b.sub_category LIKE 'OE%')
)p";*/

$sql="SELECT b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category,'cbcs' AS TYPE
FROM cbcs_subject_offered b
WHERE b.session_year=? AND b.session=? AND
(b.sub_category LIKE 'DE%' || b.sub_category LIKE 'OE%') and b.course_id=?";

    $query=$this->db->query($sql,array($sess,$sy,$course_id));
    //echo $this->db->last_query();exit;
        return $query->result();
}


    function get_cbcs_open_elective_count($syear,$sess,$did,$cid,$bid,$sem){

$sql=" SELECT sub_category,COUNT(sub_category)AS cnt
FROM cbcs_subject_offered WHERE session_year=? AND SESSION=?
AND dept_id=? AND course_id=? AND branch_id=? AND semester=? AND sub_category  LIKE '%OE%'
GROUP BY sub_category ";

    $query=$this->db->query($sql,array($syear,$sess,$did,$cid,$bid,$sem));
    //echo $this->db->last_query();
        return $query->result();
}

        //=====================Anuj End====================================================================================


    function save_subject_without_elective($insertData){
        if($this->db->insert('pre_stu_course', $insertData))
            return true;
        else
            return false;
    }

		function get_time_table($subject_code,$session_year,$session){
		$sub_code="'".implode("','", $subject_code)."'";
//	$dt=	str_replace('\',"",$sub_code);
//		print_r($sub_code); //exit;
			$sql="select x.day,x.slot_no,x.venue_id,x.subj_code from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs z on x.map_id=z.map_id
where x.subj_code in ($sub_code) and z.session_year='$session_year' and z.`session`='$session'
union all
select x.day,x.slot_no,x.venue_id,x.subj_code from tt_subject_slot_map_old x
inner join tt_map_old z on x.map_id=z.map_id
where x.subj_code in ($sub_code)  and z.session_year='$session_year' and z.`session`='$session'";
//echo $sql;exit;
	$query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
	if ($this->db->affected_rows() > 0) {
			return $query->result();
	} else {
			return FALSE;
	}
		}

function get_time_table_entry($subject_code,$session_year,$session){

//	$dt=	str_replace('\',"",$sub_code);
//		print_r($sub_code); //exit;
		$sql="select x.day,x.slot_no,x.venue_id,x.subj_code from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs z on x.map_id=z.map_id
where x.subj_code  ='$subject_code' and z.session_year='$session_year' and z.`session`='$session'
union all
select x.day,x.slot_no,x.venue_id,x.subj_code from tt_subject_slot_map_old x
inner join tt_map_old z on x.map_id=z.map_id
where x.subj_code ='$subject_code'  and z.session_year='$session_year' and z.`session`='$session'";
//echo $sql;exit;
$query = $this->db->query($sql);
//echo $this->db->last_query(); die();
if ($this->db->affected_rows() > 0) {
		return $query->result();
} else {
		return 0;
}
}
function getLTP($subject_code,$session_year,$session){
	$sql="select concat(x.lecture,x.tutorial,x.practical) as ltp from cbcs_subject_offered x where x.session_year='$session_year' and x.`session`='$session' and x.sub_code='$subject_code'
union
select concat(x.lecture,x.tutorial,x.practical) as ltp from old_subject_offered x where x.session_year='$session_year' and x.`session`='$session' and x.sub_code='$subject_code'";
//echo $sql;exit;
$query = $this->db->query($sql);
//echo $this->db->last_query(); die();
if ($this->db->affected_rows() > 0) {
	return $query->row()->ltp;
} else {
	return false;
}
}

function get_course_component_list($course_id,$sem,$branch_id,$sy,$sess,$stu_auth){
	$semester=$this->session->userdata('semester');
      /*  $sql="SELECT b.course_component,b.sequence,b.sem
        from cbcs_coursestructure_policy b
where b.course_id='$course_id' and b.sem='$sem' and (b.course_component!='DC' AND b.course_component!='DP' AND b.course_component!='OE' AND b.course_component!='DE/OE')";*/
 $table='cbcs_coursestructure_policy';
        if(($stu_auth=='ug' && $semester=1) || ($stu_auth=='prep' && $semester=1)){
            $table='cbcs_comm_coursestructure_policy';
        }

$sql="SELECT b.course_component,b.sequence,b.sem,c.sub_category,c.id
FROM $table b
 join cbcs_subject_offered c on b.course_id=c.course_id AND b.sem=c.semester AND ((CONCAT(b.course_component,b.sequence)=c.sub_category) OR(CONCAT(b.course_component,b.sequence)=c.unique_sub_pool_id))
WHERE b.course_id='$course_id' AND b.sem='$sem' AND (b.course_component!='DC' AND b.course_component!='DP' AND b.course_component!='OE' AND b.course_component!='DE/OE' AND b.course_component!='IC')
AND c.branch_id='$branch_id' AND c.session_year='$sy' AND c.`session`='$sess'
group by b.course_component,b.sequence";
        $query=$this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    function get_subject_with_course_component($course_id,$branch_id,$sem,$sy,$sess,$stu_auth,$id){
$semester=$this->session->userdata('semester');
        if(($stu_auth=='ug' && $semester=1) || ($stu_auth=='prep' && $semester=1)){
            $table='cbcs_comm_coursestructure_policy';
            $sql="SELECT a.*,b.course_component AS c_comp,b.sequence
FROM cbcs_subject_offered a
LEFT JOIN  $table b ON b.course_id=a.course_id AND b.sem=a.semester AND ((CONCAT(b.course_component,b.sequence)=a.sub_category) OR(CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id))
join stu_section_data c on c.session_year=a.session_year and c.admn_no='$id'
WHERE a.course_id='$course_id' and a.semester='$sem' and a.branch_id='$branch_id' and a.session_year='$sy' and a.`session`='$sess' and a.sub_group=if(c.section in ('A','B','C','D'),1,2)
group by a.id
ORDER BY a.id";
        }
        else{
            $table='cbcs_coursestructure_policy';
        $sql="SELECT a.*,b.course_component as c_comp,b.sequence
FROM cbcs_subject_offered a
left JOIN $table b ON b.course_id=a.course_id AND b.sem=a.semester AND ((CONCAT(b.course_component,b.sequence)=a.sub_category)OR(CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id))
where a.course_id='$course_id' and a.semester='$sem' and a.branch_id='$branch_id' and a.session_year='$sy' and a.`session`='$sess'
ORDER BY a.id";
        }

        $query=$this->db->query($sql);
    //    echo $this->db->last_query();exit;
        return $query->result();
    }

    function get_subject_details($subject_code){
        $sql="SELECT a.* FROM cbcs_subject_offered a WHERE a.sub_code='$subject_code'";
        $query=$this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result();
    }


}

?>
