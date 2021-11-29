<?php
class Get_subject extends CI_Model
{


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}


function getStudentAcdamicDetails($id){
			return $this->db->get_where('stu_academic',array('admn_no'=>$id))->result();
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

function getdates_1($date){
	 $myquery = "select * from sem_date_open_close_tbl where session_year='2019-2020' and session='Winter' order by id desc limit 1";

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
SELECT v.*,IFNULL(cso.id,oso.id) as sub_offered_id,
if(v.sub_code=oso.sub_code,concat('o',oso.id),if(v.sub_code=cso.sub_code,concat('c',cso.id),'')) as sub_offered_id,
 IF((CASE WHEN cso.sub_code IS NULL THEN oso.sub_code ELSE cso.sub_code END) IS NULL, s.subject_id,
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

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND  (case when v.course<>'comm' then   v.branch=cso.branch_id  else 1=1  end ) and  v.session_yr=cso.session_year and v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id and  v.session_yr=oso.session_year and v.session=oso.session
/* LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)*/
LEFT JOIN subjects s ON 
(CASE WHEN v.mis_sub_id IS NOT NULL    THEN s.id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is   null) then s.subject_id END)= 
(CASE WHEN v.mis_sub_id IS NOT NULL  THEN  v.mis_sub_id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is  null) then v.sub_code END)
";

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

LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=v.sub_code AND v.course=cso.course_id AND v.branch=cso.branch_id and  v.session_yr=cso.session_year and v.session=cso.session
LEFT JOIN old_subject_offered oso ON oso.sub_code=v.sub_code AND v.course=oso.course_id AND v.branch=oso.branch_id and  v.session_yr=oso.session_year and v.session=oso.session
/*LEFT JOIN subjects s ON (CASE WHEN v.mis_sub_id IS NOT NULL THEN s.id ELSE s.subject_id END)=(CASE WHEN v.mis_sub_id IS NOT NULL THEN v.mis_sub_id ELSE v.sub_code END)*/
LEFT JOIN subjects s ON 
(CASE WHEN v.mis_sub_id IS NOT NULL    THEN s.id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is   null) then s.subject_id END)= 
(CASE WHEN v.mis_sub_id IS NOT NULL  THEN  v.mis_sub_id  when  v.mis_sub_id is null  and (cso.sub_code is  null   and  oso.sub_code is  null) then v.sub_code END)

";

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
/*
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
 /*   $sql="SELECT if($en_year < 2019,concat('o',b.id),concat('c',b.id)) as id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
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
*/
function get_offered_subject_list($sem,$course,$branch,$sess,$sy,$en_year,$stu_auth){
    if($en_year < 2019 || ($en_year==2019 && $stu_auth=='prep'))
        $table='old_subject_offered';
    else
        $table='cbcs_subject_offered';

    $j="if($en_year < 2019,concat('o',b.id),concat('c',b.id)) as id";
    //echo $en_year.'--'. $atu_auth;
    if($en_year==2019 && $stu_auth=='prep'){
        $j="if($en_year = 2019,concat('o',b.id),concat('c',b.id)) as id";
    }

    $sql="SELECT $j,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
FROM $table b
where b.semester='$sem' and b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
group by b.id
order by b.id";

    $query=$this->db->query($sql);
    //echo $this->db->last_query();exit;
        return $query->result();
}

function get_offered_subject_list_jrf($sess,$sy,$en_year){
    if($en_year < 2019)
        $table='old_subject_offered';
    else
        $table='cbcs_subject_offered';

    $sql="SELECT if($en_year < 2019,concat('o',b.id),concat('c',b.id)) as id,b.semester,b.sub_name,b.sub_code,b.lecture,b.tutorial,b.practical,b.sub_type,b.sub_category
FROM $table b
where  b.course_id='$course' and b.branch_id='$branch' and b.session_year='$sy' and b.`session`='$sess' and (INSTR (b.sub_category,'DC') or INSTR (b.sub_category,'DP'))
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
    function get_honours_list($sy,$sess,$sem,$id,$branch){
        $sql="select c.* from old_subject_offered c where c.session_year='$sy' and c.`session`='$sess' and c.semester='$sem' and c.course_id='honour'
and c.branch_id='$branch'";

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
	//		echo	$this->db->last_query();die();
        return $query->result();
    }




    function get_time_table_clash($map_id,$sub_id){
			//print_r($map_id);
			$mp=implode(",",$map_id);
//			print_r($mp); exit;
        $sql="SELECT a.day,a.slot_no,concat(a.day,a.slot_no) AS dsv FROM tt_subject_slot_map_cbcs a WHERE a.map_id in($mp) AND a.subj_code=?
 union
SELECT a.day,a.slot_no,concat(a.day,a.slot_no) AS dsv FROM tt_subject_slot_map_old a WHERE a.map_id in ($mp) AND a.subj_code=? ";
//echo $sql; exit;
	  $query = $this->db->query($sql,array($sub_id,$sub_id));

	//echo $this->db->last_query(); //die();
    if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }

    }
		function chekalternate($session_year,$session,$value){
			$sql="select x.alternate_subject_code from alternate_course_all x where x.session_year='$session_year' and x.`session`='Monsoon' and x.old_subject_code='$value'
union
select x.alternate_subject_code from alternate_course x where x.session_year='$session_year' and x.`session`='Monsoon' and x.old_subject_code='$value' ";
//echo $sql; exit;
	$query = $this->db->query($sql);

	//echo "<br>".$this->db->last_query();echo "<br>"; //die();echo "<br>"
	if ($this->db->affected_rows() > 0) {
			return $query->row()->alternate_subject_code;
	} else {
			return 0;
	}
		}

		function chekforcomm($session_year,$session,$value){
			$sql="select a.course_id from cbcs_subject_offered a where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$value'
union
select a.course_id from old_subject_offered a where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$value' ";
//echo $sql; exit;
	$query = $this->db->query($sql);

	//echo "<br>".$this->db->last_query();echo "<br>"; //die();echo "<br>"
	if ($this->db->affected_rows() > 0) {
			return $query->row()->course_id;
	} else {
			return 0;
	}
		}


		function cbcs_tt_map_id($session_year,$session,$value){
			$sql="select x.map_id from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs y on x.map_id=y.map_id
where x.subj_code='$value' and y.session_year='$session_year' AND y.`session`='$session' group by x.map_id";
//echo $sql; exit;
	$query = $this->db->query($sql);

	//echo "<br>".$this->db->last_query();echo "<br>"; //die();echo "<br>"
	if ($this->db->affected_rows() > 0) {
			return $query->result();
	} else {
			return 0;
	}
		}
    function stu_photo_sign($id){

        $sql="SELECT a.photopath,b.signpath FROM user_details a LEFT JOIN stu_prev_certificate b ON b.admn_no=a.id
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
(b.sub_category LIKE 'DE%' || b.sub_category LIKE 'OE%') and b.course_id=? group by b.sub_code order by b.sub_name ";

    $query=$this->db->query($sql,array($sess,$sy,$course_id));
    //echo $this->db->last_query();exit;
        return $query->result();
}


    function get_cbcs_open_elective_count($syear,$sess,$did,$cid,$bid,$sem){

$sql=" SELECT sub_category,COUNT(sub_category)AS cnt
FROM cbcs_subject_offered WHERE session_year=? AND SESSION=?
AND dept_id=? AND course_id=? AND branch_id=? AND semester=? AND sub_category  LIKE '%OE%'
GROUP BY sub_category ";

/*$sql=" SELECT sub_category,COUNT(sub_category)AS cnt
FROM cbcs_subject_offered WHERE session_year=? AND SESSION=?
AND dept_id=? AND course_id=? AND branch_id=? AND semester=? AND sub_category  LIKE '%OE%' AND (unique_sub_pool_id='NA' OR unique_sub_pool_id='')
GROUP BY sub_category ";*/

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
	
	
	
			function clashcommsection($sub_code,$session_year,$session,$course,$branch){
			$sub_code="'".implode("','", $subject_code)."'";
			$sql="
			select count((section)) AS cnt_sections
	from (SELECT x.*, (GROUP_CONCAT(DISTINCT(subj_code))) AS clash_paper, GROUP_CONCAT(map_id) AS clash_map_id, GROUP_CONCAT(section) AS sections
	FROM (

	SELECT CONCAT('c',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section as section
	FROM tt_subject_slot_map_cbcs x
	INNER JOIN tt_map_cbcs z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.`session`='$session' AND (z.course_id='$course' OR z.course_id='comm') AND (z.branch_id='$branch' OR z.branch_id= 'comm')
	UNION
	SELECT CONCAT('o',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section as section
	FROM tt_subject_slot_map_old x
	INNER JOIN tt_map_old z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.`session`='$session' AND (z.course_id='$course' OR z.course_id='comm') AND (z.branch_id='$branch' OR z.branch_id= 'comm')
	ORDER BY subj_code desc  limit 1000


	) x
	GROUP BY x.day,x.slot_no
	HAVING COUNT(clash_paper) > 1) f group by f.subj_code having cnt_sections > 0 ";
			$query = $this->db->query($sql);
		//echo $this->db->last_query(); die();
			if ($this->db->affected_rows() > 0) {
					return $query->row()->cnt_sections;
			} else {
					return FALSE;
			}
		}
		
			function getcountofferedsection($sub_code,$session_year,$session,$course,$branch){
	$sql="select count(*) as offered_sec_count from cbcs_subject_offered_desc a
inner join cbcs_subject_offered b on a.sub_offered_id=b.id where b.session_year='$session_year' and b.`session`='$session'
and b.sub_code='$sub_code'";
		$query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() > 0) {
				return $query->row()->offered_sec_count;
		} else {
				return FALSE;
		}
		}

		function get_time_clash_index($subject_code,$session_year,$session,$course_id,$branch_id){
			$sub_code="'".implode("','", $subject_code)."'";
		
			$sql="
			SELECT y.*, GROUP_CONCAT( y.clash_paper ) AS final_clash,REPLACE(group_concat(y.sec),'notcomm','') as sections
			FROM (
			SELECT x.*,
			GROUP_CONCAT(  distinct x.subj_code ) AS clash_paper, GROUP_CONCAT(map_id) AS clash_map_id,GROUP_CONCAT( (case when x.section IS NULL then 'notcomm' ELSE  x.section END)) AS sec
			FROM (
			SELECT CONCAT('c',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section
			FROM tt_subject_slot_map_cbcs x
			INNER JOIN tt_map_cbcs z ON x.map_id=z.map_id
			WHERE x.subj_code IN ($sub_code)
			AND z.session_year='$session_year' AND z.`session`='$session'
			AND (z.course_id='$course_id' or z.course_id='comm') AND (z.branch_id='$branch_id' or z.branch_id= 'comm')
			UNION
			SELECT CONCAT('o',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section
			FROM tt_subject_slot_map_old x
			INNER JOIN tt_map_old z ON x.map_id=z.map_id
			WHERE x.subj_code IN ($sub_code)
			 AND z.session_year='$session_year' AND z.`session`='$session' AND (z.course_id='$course_id' or z.course_id='comm') AND (z.branch_id='$branch_id'
				or z.branch_id= 'comm')

			 ORDER BY day,slot_no,subj_code limit 1000
				) x
			GROUP BY  x.day,x.slot_no
			HAVING COUNT(clash_paper) > 1 AND  INSTR(sec,'notcomm')>0
			) y
			GROUP BY y.clash_paper
			";
		
		/*	$sql="
			SELECT y.*, GROUP_CONCAT(DISTINCT(y.clash_paper)) AS final_clash
			FROM (
			SELECT x.*, GROUP_CONCAT( distinct (subj_code)) AS clash_paper, GROUP_CONCAT(map_id) AS clash_map_id,GROUP_CONCAT(section) as sections
			FROM (
			SELECT CONCAT('c',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section
			FROM tt_subject_slot_map_cbcs x
			INNER JOIN tt_map_cbcs z ON x.map_id=z.map_id
			WHERE x.subj_code IN ($sub_code)
			AND z.session_year='$session_year' AND z.`session`='$session'
			AND (z.course_id='$course_id' or z.course_id='comm') AND (z.branch_id='$branch_id' or z.branch_id= 'comm')
			 UNION 
			SELECT CONCAT('o',x.map_id) AS map_id,x.day,x.slot_no,x.subj_code,z.course_id,z.branch_id,z.section
			FROM tt_subject_slot_map_old x
			INNER JOIN tt_map_old z ON x.map_id=z.map_id
			WHERE x.subj_code IN ($sub_code)
			 AND z.session_year='$session_year' AND z.`session`='$session' AND (z.course_id='$course_id' or z.course_id='comm') AND (z.branch_id='$branch_id'
				or z.branch_id= 'comm')

				ORDER BY subj_code limit 1000
				) x
			GROUP BY  x.day,x.slot_no
			HAVING COUNT(clash_paper) > 1) y
			GROUP BY y.clash_paper";

			$sql="
			select y.*, group_concat(DISTINCT(y.clash_paper)) as final_clash from (
	select x.*, group_concat(subj_code) as clash_paper ,group_concat(map_id) as clash_map_id from
	(SELECT concat('c',x.map_id) as map_id,x.day,x.slot_no,x.subj_code
	FROM tt_subject_slot_map_cbcs x
	INNER JOIN tt_map_cbcs z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.course_id=('$course_id' or 'comm') AND z.branch_id=('$branch_id' or 'comm')

	 UNION ALL
	SELECT concat('o',x.map_id) as map_id,x.day,x.slot_no,x.subj_code
	FROM tt_subject_slot_map_old x
	INNER JOIN tt_map_old z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.`session`='$session' AND z.course_id=('$course_id' or 'comm') AND z.branch_id=('$branch_id' or 'comm')) x
	group by x.day,x.slot_no having count(clash_paper) > 1 ) y
	group by  y.subj_code
			"; */


	/*		$sql="
			select y.*, group_concat(DISTINCT(y.clash_paper)) as final_clash from (
	select x.*, group_concat(subj_code) as clash_paper ,group_concat(map_id) as clash_map_id from
	(SELECT concat('c',x.map_id) as map_id,x.day,x.slot_no,x.subj_code
	FROM tt_subject_slot_map_cbcs x
	INNER JOIN tt_map_cbcs z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.`session`='$session' and z.course_id='$course_id' or z.course_id='comm' and z.branch_id='$branch_id' or z.branch_id= 'comm'

	 UNION ALL
	SELECT concat('o',x.map_id) as map_id,x.day,x.slot_no,x.subj_code
	FROM tt_subject_slot_map_old x
	INNER JOIN tt_map_old z ON x.map_id=z.map_id
	WHERE x.subj_code IN ($sub_code) AND z.session_year='$session_year' AND z.`session`='$session' and z.course_id='$course_id' or z.course_id='comm' and z.branch_id='$branch_id' or z.branch_id= 'comm') x
	group by x.map_id having count(clash_paper) > 1 ) y
	group by  y.subj_code
			";
*/

			$query = $this->db->query($sql);
	//	echo $this->db->last_query();// die();
			if ($this->db->affected_rows() > 0) {
					return $query->result();
			} else {
					return FALSE;
			}


		}

		function get_time_table($subject_code,$session_year,$session,$course,$branch){
		$sub_code="'".implode("','", $subject_code)."'";
//	$dt=	str_replace('\',"",$sub_code);
//		print_r($sub_code); //exit;
/*$sql="select x.map_id,x.day,x.slot_no,x.subj_code from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs z on x.map_id=z.map_id
where x.subj_code in ($sub_code) and z.session_year='$session_year' and z.`session`='$session'
union all
select x.map_id,x.day,x.slot_no,x.subj_code from tt_subject_slot_map_old x
inner join tt_map_old z on x.map_id=z.map_id
where x.subj_code in ($sub_code)  and z.session_year='$session_year' and z.`session`='$session'"; */

$sql="select concat('c',x.map_id) as map_id,x.day,x.slot_no,x.subj_code from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs z on x.map_id=z.map_id
where x.subj_code in ($sub_code) and z.session_year='$session_year' and z.`session`='$session'
AND (z.course_id='$course' or z.course_id='comm') AND (z.branch_id='$branch'
or z.branch_id= 'comm')
union all
select concat('o',x.map_id) as map_id,x.day,x.slot_no,x.subj_code from tt_subject_slot_map_old x
inner join tt_map_old z on x.map_id=z.map_id
where x.subj_code in ($sub_code)  and z.session_year='$session_year' and z.`session`='$session'
AND (z.course_id='$course' or z.course_id='comm') AND (z.branch_id='$branch'
or z.branch_id= 'comm')";

//echo $sql;exit;
	$query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
	if ($this->db->affected_rows() > 0) {
			return $query->result();
	} else {
			return FALSE;
	}
		}

		function getClashwith($new_c,$slot_parms,$sess_r,$sy_r,$course_id,$branch_id,$flag,$chkin){
			$sub_code="'".implode("','", $chkin)."'";
			if($flag==2){
				$sem=$this->session->userdata('semester')+1;
				$extraParam="and x.map_id in(
select a.map_id from tt_map_cbcs a where a.session='Winter' AND a.session_year='2019-2020' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.semester='$sem'
union
select a.map_id from tt_map_old a where a.session='Winter' AND a.session_year='2019-2020' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.semester='$sem'
)";
			}
			//	echo"<br>". $slot_parms;
				$sql="select x.subj_code,y.section from tt_subject_slot_map_old x
inner join tt_map_old y on y.map_id=x.map_id
where x.day='$slot_parms[0]' and x.slot_no='$slot_parms[1]' and x.subj_code <> '$new_c' and x.subj_code in($sub_code) and y.session='$sess_r' and y.session_year='$sy_r' $extraParam

union

select x.subj_code,y.section from tt_subject_slot_map_cbcs x
inner join tt_map_cbcs y on y.map_id=x.map_id
where x.day='$slot_parms[0]' and x.slot_no='$slot_parms[1]' and x.subj_code <> '$new_c' and x.subj_code in($sub_code) and y.session='$sess_r' and y.session_year='$sy_r'  $extraParam";
//	echo $sql;
		$query = $this->db->query($sql);
		echo"<br>". $this->db->last_query();// die();
		if ($this->db->affected_rows() > 0) {
				return $query->result();
		} else {
				return FALSE;
		}
				//echo "<br>".$new_c;
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
function getLTP($subject_code,$session_year,$session,$course,$branch){
	$sql="select concat(x.lecture,x.tutorial,x.practical) as ltp from cbcs_subject_offered x where x.session_year='$session_year' and x.`session`='$session' and x.sub_code='$subject_code' and x.course_id='$course' and x.branch_id='$branch'
union
select concat(x.lecture,x.tutorial,x.practical) as ltp from old_subject_offered x where x.session_year='$session_year' and x.`session`='$session' and x.sub_code='$subject_code' and x.course_id='$course' and x.branch_id='$branch'";
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

/*$sql="SELECT b.course_component,b.sequence,b.sem,c.sub_category,c.id
FROM $table b
 join cbcs_subject_offered c on b.course_id=c.course_id AND b.sem=c.semester AND ((CONCAT(b.course_component,b.sequence)=c.sub_category) OR(CONCAT(b.course_component,b.sequence)=c.unique_sub_pool_id))
WHERE b.course_id='$course_id' AND b.sem='$sem' AND (b.course_component!='DC' AND b.course_component!='DP' AND b.course_component!='OE' AND b.course_component!='DE/OE' AND b.course_component!='IC')
AND c.branch_id='$branch_id' AND c.session_year='$sy' AND c.`session`='$sess'
group by b.course_component,b.sequence";*/
$sql="SELECT b.course_component,b.sequence,b.sem,c.sub_category,c.id
FROM $table b
 join cbcs_subject_offered c on b.course_id=c.course_id AND b.sem=c.semester AND ((CONCAT(b.course_component,b.sequence)=c.sub_category) OR(CONCAT(b.course_component,b.sequence)=c.unique_sub_pool_id))
WHERE b.course_id='$course_id' AND b.sem='$sem' AND (b.course_component!='DC' AND b.course_component!='DP' AND b.course_component!='OE'  AND b.course_component!='IC')
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
ORDER BY cast(b.sequence as UNSIGNED)";
        }
        else{
            $table='cbcs_coursestructure_policy';
        $sql="SELECT a.*,b.course_component as c_comp,b.sequence
FROM cbcs_subject_offered a
left JOIN $table b ON b.course_id=a.course_id AND b.sem=a.semester AND ((CONCAT(b.course_component,b.sequence)=a.sub_category)OR(CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id))
where a.course_id='$course_id' and a.semester='$sem' and a.branch_id='$branch_id' and a.session_year='$sy' and a.`session`='$sess'
ORDER BY cast(b.sequence as UNSIGNED)";
        }

        $query=$this->db->query($sql);
        #echo $this->db->last_query();exit;
        return $query->result();
    }

  /*  function get_subject_details($subject_code){
        $sql="SELECT a.* FROM cbcs_subject_offered a WHERE a.sub_code='$subject_code'";
        $query=$this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result();
    }*/
	// get subject code for de
    function get_subject_details($subject_code,$course_id,$branch_id,$sy,$sess){
        $sql="SELECT a.* FROM cbcs_subject_offered a WHERE a.sub_code='$subject_code' and a.session_year='$sy' and a.session='$sess' and a.course_id='$course_id' and a.branch_id='$branch_id'";
        $query=$this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result();
    }
// get subject code for open elective
    function get_subject_details_open_elective($subject_code,$course_id,$branch_id,$sy,$sess){
        $sql="SELECT a.* FROM cbcs_subject_offered a WHERE a.sub_code='$subject_code' and a.session_year='$sy' and a.session='$sess' and a.course_id='$course_id' /*and a.branch_id='$branch_id'*/";
        $query=$this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result();
    }

		function check_already_register($id,$sy,$sess,$course_id,$branch_id,$sem_1){
		        $sql="SELECT a.*
		FROM reg_regular_form a
		WHERE a.admn_no='$id' AND a.course_id='$course_id' AND a.branch_id='$branch_id' AND a.session_year='$sy' AND a.`session`='$sess' and a.semester='$sem_1'";
		        $query=$this->db->query($sql);
		        if($query->num_rows() > 0){
		            //echo $this->db->last_query();exit;
		            return true;
		        }
		        else{
		            return false;
		        }
		    }
			
			function get_jrf_branch($id){
				
				$sql="select branch_id from reg_regular_form where admn_no='$id'";
		        $query=$this->db->query($sql);
		        if($query->num_rows() > 0){
		            
		            return $query->row()->branch_id;
		        }
		        else{
		            return false;
		        }
				
				
			}


}

?>
