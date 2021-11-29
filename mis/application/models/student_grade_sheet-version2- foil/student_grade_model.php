<?php

class Student_grade_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_course_agg_id($a_id, $session, $session_year, $et = '') {
        $sql = " select form_id,admn_no,course_aggr_id,semester,course_id from reg_regular_form 
where session_year='" . $session_year . "' and session='" . $session . "' and hod_status='1'
and acad_status='1' and admn_no='" . $a_id . "' ";

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    //-----------------------------------------------
    
    function stu_details1($adm_no,$sess,$sy)
    {
       /* $sql = "
            SELECT
  `user_details`.`id`,
  Concat(`user_details`.`first_name`, ' ', `user_details`.`middle_name`, ' ',
  `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
   `reg_regular_form`.`semester`,
  `reg_regular_form`.`session_year`,
  `reg_regular_form`.`session`,
  `reg_regular_form`.`section`,
  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_regular_form` ON `user_details`.`id` =
    `reg_regular_form`.`admn_no`
WHERE
  `user_details`.`id` = '".$adm_no."'
  and `reg_regular_form`.`session` ='".$sess."'
  and `reg_regular_form`.`session_year`='".$sy."'
     ";*/
	         $sql = "
            SELECT
  `user_details`.`id`,
  concat_ws(' ', `user_details`.`first_name`,  `user_details`.`middle_name`, `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
   `reg_regular_form`.`semester`,
  `reg_regular_form`.`session_year`,
  `reg_regular_form`.`session`,
  `reg_regular_form`.`section`,
  ssd.section as st_section,
  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_regular_form` ON `user_details`.`id` = `reg_regular_form`.`admn_no`
  left join stu_section_data ssd on ssd.admn_no = `reg_regular_form`.admn_no and ssd.session_year= `reg_regular_form`.`session_year`
WHERE
  `user_details`.`id` = '".$adm_no."'
  and `reg_regular_form`.`session` ='".$sess."'
  and `reg_regular_form`.`session_year`='".$sy."'
     ";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    //----------------------------------------------------------
    function stu_details($adm_no,$sem,$sy=null,$sess=null) {
       /* $sql = " select ssd.section as st_section ,a.*,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name as dept_nm,e.name as course_nm,f.name as branch_nm,c.enrollment_year from reg_regular_form a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
inner join cs_courses e on e.id=c.course_id
inner join cs_branches f on f.id=c.branch_id
left join stu_section_data ssd on ssd.admn_no = a.admn_no and ssd.session_year= a.`session_year`
where a.admn_no=? and a.semester=?
and a.hod_status='1' and a.acad_status='1'
and a.session_year=? and a.session=?
";*/
     
        //and a.semester=".$sem."
//order by a.form_id desc limit 1
//
        $sql="SELECT ssd.section AS st_section,a.*, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name AS dept_nm,e.name AS course_nm, CASE WHEN c.enrollment_year < '2015' AND c.branch_id='ap' THEN 'Applied Physics' ELSE f.name END branch_nm,c.enrollment_year
FROM reg_regular_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN stu_academic c ON c.admn_no=b.id
INNER JOIN departments d ON d.id=b.dept_id
INNER JOIN cs_courses e ON e.id=c.course_id
INNER JOIN cs_branches f ON f.id=c.branch_id
LEFT JOIN stu_section_data ssd ON ssd.admn_no = a.admn_no AND ssd.session_year= a.`session_year`
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1' AND a.session_year=? AND a.session=?";
        
        $query = $this->db->query($sql,array($adm_no,$sem,$sy,$sess));
		
	//	 echo $this->db->last_query(); die();
		
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    //-summer
    function stu_details_summer($adm_no,$sem,$sy=null,$sess=null) {
     /*   $sql = "SELECT ssd.section AS st_section,a.*, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,b.dept_id,b.photopath, c.course_id,c.branch_id,d.name AS dept_nm,e.name AS course_nm,f.name AS branch_nm
FROM reg_summer_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN stu_academic c ON c.admn_no=b.id
INNER JOIN departments d ON d.id=b.dept_id
INNER JOIN cs_courses e ON e.id=c.course_id
INNER JOIN cs_branches f ON f.id=c.branch_id
inner join reg_summer_subject g on g.form_id=a.form_id
inner join course_structure h on h.id=g.sub_id
LEFT JOIN stu_section_data ssd ON ssd.admn_no = a.admn_no AND ssd.session_year= a.`session_year`
WHERE a.admn_no='".$adm_no."' AND h.semester=".$sem." AND a.hod_status='1' AND a.acad_status='1'
group by a.admn_no";*/
     
        //and a.semester=".$sem."
//order by a.form_id desc limit 1
//
        $sql="SELECT ssd.section AS st_section,a.*, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,b.dept_id,b.photopath, c.course_id,c.branch_id,d.name AS dept_nm,e.name AS course_nm,CASE WHEN c.enrollment_year < '2015' AND c.branch_id='ap' THEN 'Applied Physics' ELSE f.name END branch_nm,c.enrollment_year,a.timestamp
FROM reg_summer_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN stu_academic c ON c.admn_no=b.id
INNER JOIN departments d ON d.id=b.dept_id
INNER JOIN cs_courses e ON e.id=c.course_id
INNER JOIN cs_branches f ON f.id=c.branch_id
INNER JOIN reg_summer_subject g ON g.form_id=a.form_id
INNER JOIN course_structure h ON h.id=g.sub_id
LEFT JOIN stu_section_data ssd ON ssd.admn_no = a.admn_no AND ssd.session_year= a.`session_year`
WHERE a.admn_no='".$adm_no."' AND h.semester=".$sem." AND a.hod_status='1' AND a.acad_status='1' and a.session_year='".$sy."' and a.session='".$sess."'
GROUP BY a.session_year,a.admn_no  
order by a.timestamp desc limit 1 ";
        $query = $this->db->query($sql);
		
	//	 echo $this->db->last_query(); die();
		
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    //---

    function get_formid_from_elective_table($adm_no) {
        $sql = " select form_id from reg_regular_form where admn_no='" . $adm_no . "' and hod_status='1'and acad_status='1' ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_elec_subject_list_by_admnno_gradesheet($sem, $agg_id, $fid) {
        $sql = "                  
SELECT
  `a`.`id`,
  `a`.`semester`,
  `a`.`aggr_id`,
  `a`.`sequence`,
  `b`.`subject_id`,
  `b`.`name`,
  `b`.`credit_hours`
FROM
  `course_structure` AS `a`
  INNER JOIN `subjects` AS `b` ON `a`.`id` = `b`.`id`
  INNER JOIN `reg_regular_elective_opted`
    ON `a`.`id` = `reg_regular_elective_opted`.`sub_id`
WHERE
  `a`.`semester` = '" . $sem . "' AND
  `a`.`aggr_id` = '" . $agg_id . "' AND
  `reg_regular_elective_opted`.`form_id` = " . $fid . " AND
  `b`.`elective` != '0'";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_grade($admn_no, $sy, $sess, $sub_id) {
        $sql = " select b.grade from marks_master as a  
inner join marks_subject_description as b on a.id=b.marks_master_id
where b.admn_no='" . $admn_no . "'
and a.session_year='" . $sy . "'
and a.`session`='" . $sess . "'
and subject_id='" . $sub_id . "' ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_grade_points($id) {
        $sql = "select points from grade_points where grade='" . $id . "'  "; //In grade_points table point respect to grade is store


        $query = $this->db->query($sql);
        
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_previous_result_byAdmnNo($id) {
        // $sql="select right(sem_code,1) as semcode,session,examtype,gpa,ogpa,passfail,reamarks from resultdata where admn_no='".$id."'";
        $sql = "select  group_concat(A.gpa) as gpa, group_concat(A.ogpa) as ogpa from
(select admn_no,right(sem_code,1) as semcode,session,examtype,gpa,ogpa,passfail,reamarks from resultdata where admn_no='" . $id . "'

)A
group by A.admn_no";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_honour_gradesheet($admn_no, $sem) {
        $sql = "select  subjects.id,subjects.subject_id,subjects.name,subjects.credit_hours
 from 
(select hf1.admn_no from  hm_form hf1  where hf1.honours='1' and hf1.honour_hod_status='Y' and hf1.admn_no='" . $admn_no . "')k
left join stu_academic on stu_academic.admn_no=k.admn_no
inner join course_structure on course_structure.aggr_id=concat('honour','_',stu_academic.branch_id,'_2013_2014') and course_structure.semester='" . $sem . "'

left join subjects on  subjects.id =  course_structure.id";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_minor_gradesheet($admn_no, $sem) {
        $sql = " select  subjects.id,subjects.subject_id,subjects.name,subjects.credit_hours
 from 
( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered='1' and hf2.minor='1' and hf2.minor_hod_status='Y' and hf2.admn_no='" . $admn_no . "'
)k 
inner join course_structure cs2 on cs2.aggr_id=concat('minor','_',k.branch_id,'_2013_2014') and cs2.semester='" . $sem . "'
left join subjects on  subjects.id =  cs2.id";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_student_group($id) {
        $sql = "select `group` from section_group_rel where section=(select section from stu_section_data where admn_no='" . $id . "') ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_student_pass_fail($id, $sy) {
        //     $sql="select passfail from resultdata where admn_no='".$id."' and right(sem_code,1)=".$sy; 
        $sql = "select passfail from resultdata where admn_no='" . $id . "' and right(sem_code,1)=" . $sy . " order by passfail desc limit 1";




        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    //--------------------------------For Repeaters carryover------------------------------------
  //type="R"  is  by default set  for others type=s for special, type=j for  jrf
    function stu_details_others($adm_no,$sem,$type='R',$sy=null,$sess=null) {
        
       if($type=='O'){$type='R';} //for other
    if($type=='R' || $type=='S' || $type=='E'){ $semester_replace=" and a.semester like '%".$sem."%' ";  $type_replace=" and a.type='".$type."' ";  $join1=" inner ";  $join2=" inner "; }
        else if($type=='J') {$semester_replace=""; $type_replace="";   $join1=" left ";  $join2=" left ";}
        $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name as dept_nm,e.name as course_nm,CASE WHEN c.enrollment_year < '2015' AND c.branch_id='ap' THEN 'Applied Physics' ELSE f.name END branch_nm,c.enrollment_year from reg_exam_rc_form a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
".$join1."  join cs_courses e on e.id=c.course_id
".$join2."   join cs_branches f on f.id=c.branch_id
where a.session_year='".$sy."' and a.session='".$sess."'  and a.admn_no='".$adm_no."'  ".$semester_replace."  ".$type_replace."
and a.hod_status='1' and a.acad_status='1'

union

select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,b.photopath,
c.course_id,c.branch_id,d.name as dept_nm,e.name as course_nm,CASE WHEN c.enrollment_year < '2015' AND c.branch_id='ap' THEN 'Applied Physics' ELSE f.name END branch_nm,c.enrollment_year from reg_other_form a
inner join user_details b on a.admn_no=b.id
inner join stu_academic c on c.admn_no=b.id
inner join departments d on d.id=b.dept_id
".$join1."  join cs_courses e on e.id=c.course_id
".$join2."   join cs_branches f on f.id=c.branch_id
where a.session_year='".$sy."' and a.session='".$sess."' and a.admn_no='".$adm_no."'  ".$semester_replace."  ".$type_replace."
and a.hod_status='1'  and a.acad_status='1' ";
        
        
        $query = $this->db->query($sql);
       // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    //----------------------------------------------------
    
    function stu_details_others1($adm_no,$sess,$syear) {
        $sql = "SELECT
  `user_details`.`id`,
  Concat_ws(' ',`user_details`.`first_name`, `user_details`.`middle_name`, `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
  `reg_exam_rc_form`.`semester`,
  `reg_exam_rc_form`.`session_year`,
  `reg_exam_rc_form`.`session`,

  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_exam_rc_form` ON `user_details`.`id` =
    `reg_exam_rc_form`.`admn_no`
    WHERE
 `user_details`.`id` = '".$adm_no."'
  and session='".$sess."'
  and session_year='".$syear."'
     ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    
    //-----------------------------------------------------------------

    public function get_subject_list_for_others_gradesheet_byFormId($id) {

        $sql = " select a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name,b.credit_hours from reg_exam_rc_subject as a inner join subjects as b                       on a.sub_id=b.id where form_id='" . $id . "' ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

 function grade_sheet_details($admn_no, $sem,$course=null,$sy=null,$sess=null) {   
        if($course=='minor')$where_add="  and e.course_id='minor' " ; else   $where_add="";        
$sql = "  select  f.core_cgpa,f.cgpa, f.ctotcrpts,f.core_ctotcrpts,f.ctotcrhr,f.core_ctotcrhr ,  grade_points.points ,  (grp.credit_hours*grade_points.points)  as totcrdtpt ,grp.* from (  select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` as stype from 
(select a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id, b.`type`, a.admn_no from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`status`='Y' and b.`type`='R'
and b.session_year='".$sy."' and b.`session`='".$sess."'    
) A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id where e.semester='".$sem."' ".$where_add."  group by C.sid)grp
left join final_semwise_marks_foil f on f.admn_no=grp.admn_no and f.session_yr=grp.session_year and  f.`session`=grp.session and f.`type`=grp.type and f.semester=grp.semester
	  and  lower(f.course)=lower(grp.course_id)
left join grade_points on grade_points.grade=grp.grade   order by grp.semester,grp.seq+0 asc";



        $query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
                
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

 function grade_sheet_details_jrf($admn_no,$sy,$sess) {    
        $sql = "select B.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select A.*,c.name,c.subject_id as sid,c.credit_hours,c.`type` from 
(
select a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."'  ) A
inner join subjects as c on A.subject_id=c.id 
) B
inner join subject_mapping as e on B.sub_map_id = e.map_id 
where B.session_year='".$sy."' and B.`session`='".$sess."'
group by B.sid order by e.semester
";



        $query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }   
    
    //-----------------------Restricted Student to view gradesheet----------------------

    public function check_restricted_student($a_id, $sem,$sy=null,$sess=null) {
        $sql = " select * from stu_result_restriction where admn_no=? and semester=? and over_reason='NA' and session_year=? and session=?";

        $query = $this->db->query($sql,array($a_id, $sem,$sy,$sess));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function fetch_restricted_student($sy, $sess,$et) {
    //    $sql = "select * from stu_result_restriction where session_year='" . $sy . "' and session='" . $sess . "' and over_reason='NA'";
$sql="select * from stu_result_restriction where session_year=? and session=? and exam_type=? and 
over_reason='NA'";



        $query = $this->db->query($sql,array($sy, $sess,$et));

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //--------------------------Feedback---------------------------------------


    public function check_feedback_student($a_id, $sem) {
        $sql = "select COALESCE(semester_" . $sem . ",'N') as  reply from fb_student_feedback where admn_no='" . $a_id . "' ";
        //   $sql="select semester_".$sem." from fb_student_feedback where admn_no='".$a_id."'";

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    //=================Student details based on admn_number

    public function get_student_details($a_id) {
        $sql = "SELECT
concat(  `user_details`.`first_name`,' ', `user_details`.`middle_name`,' ',`user_details`.`last_name`) as sname,
  `user_details`.`dept_id`,
  `stu_academic`.`course_id`,
  `stu_academic`.`branch_id`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
   where  `user_details`.`id`='" . $a_id . "' ";


        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function update_rem_reason($oreason, $ordate, $fid) {

        $sql = "update stu_result_restriction set over_reason='" . $oreason . "',over_timestamp='" . $ordate . "' where id='" . $fid . "'";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_exam_held($sess,$sy,$type='R') {
        $sql = "select * from exam_held_time where status='yes' and exam_type='".$type."' and syear='".$sy."' and session='".$sess."'";
        $query = $this->db->query($sql);
      //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function romanic_number($integer, $upcase = true) {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
    
    //------------------------------- Student Details Based on Regular Registration Form------------------
    // also  being used in feedback
    function stu_details_regular($adm_no,$sy,$sess) {
        $sql = " SELECT
  `user_details`.`id`,
  concat_ws(' ',`user_details`.`first_name`, `user_details`.`middle_name`, `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
  `reg_regular_form`.`semester`,
  `reg_regular_form`.`session_year`,
  `reg_regular_form`.`session`,
  `reg_regular_form`.`section`,
  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_regular_form` ON `user_details`.`id` =
    `reg_regular_form`.`admn_no`
WHERE
  `user_details`.`id` = '".$adm_no."' and
   `reg_regular_form`.`session_year`='".$sy."' and
    `reg_regular_form`.`session`='".$sess."' and
         `reg_regular_form`.`hod_status`='1' and 
        `reg_regular_form`.`acad_status`='1'  
  ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    
    //------------Result Declaration-------------------------------
    
  /*  function get_result_declaration($did,$cid,$bid,$sem,$et,$sy,$sess,$sec_name,$status_type=1)
    { 
        if($status_type=='1' || $status_type=='2' ){
          $replace=" and status='".$status_type."'  ";
        }
        else  if($status_type=='both' ){
          $replace=" and ( status='1' or status='0' or status is null) ";
        }
        if(( strtoupper($cid)==strtoupper('b.tech') ||  strtoupper($cid)== strtoupper('dualdegree') ||  strtoupper($cid)== strtoupper('int.m.sc')||  strtoupper($cid)== strtoupper('int.msc.tech')||  strtoupper($cid)== strtoupper('int.m.tech') ||  strtoupper($cid)== strtoupper('comm')  ) && $sem<3){
		$sql = "select * from result_declaration_log where  upper(dept_id)='COMM' 
                and upper(course_id)='COMM' and upper(branch_id)='COMM'
                and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace."  and  section='".$sec_name."'";
		
		}
                
		else if( strtoupper($cid)== strtoupper('jrf')){
            
            $sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' 
                and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."'
                and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."' ".$replace."";
            
        }
	 else{
	 
		$sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' 
                and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."'
                and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'  ".$replace."";

	 }
         if(($sem==-1 || $sem==0)&&  strtoupper($cid)!= strtoupper('jrf') ){
                    $sql = "select * from result_declaration_log where dept_id='PREP' 
                and course_id='PREP' and branch_id='PREP'
                and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace." ";
                }

        $query = $this->db->query($sql);
	//echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	
	*/
	
	
	/*function get_result_declaration($did,$cid,$bid,$sem,$et,$sy,$sess,$sec_name,$status_type=1)
    { 
        
		if($et=='special'){ $et='spl';}
		
		if($status_type=='1' || $status_type=='2' ){
          $replace=" and status='".$status_type."'  ";
        }
        else  if($status_type=='both' ){
          $replace=" and ( status='1' or status='0' or status is null) ";
        }
       if(( strtoupper($cid)==strtoupper('b.tech') ||  strtoupper($cid)== strtoupper('dualdegree') ||  strtoupper($cid)== strtoupper('int.m.sc')||  strtoupper($cid)== strtoupper('int.msc.tech')||  strtoupper($cid)== strtoupper('int.m.tech') ||  strtoupper($cid)== strtoupper('comm')  ) && $sem<3){
	  $sql = "select * from result_declaration_log where  upper(dept_id)='COMM' and upper(course_id)='COMM' and upper(branch_id)='COMM' and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace."  and  section='".$sec_name."'";		
	}            
	else if( strtoupper($cid)== strtoupper('jrf')){            
           $sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."' and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."' ".$replace."";            
        }
       else if( $et== 'prep' && $did=='all' ){
           $sql = "select * from result_declaration_log where  course_id='PREP' and branch_id='PREP' and semester='-1' and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace." ";
        }                  
       else{	 
	   $sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."' and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'  ".$replace."";
        }        
        $query = $this->db->query($sql);//echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) 
            return $query->row();
         else 
            return FALSE;        
    }*/
	
	function get_result_declaration($did,$cid,$bid,$sem,$et,$sy,$sess,$sec_name,$status_type=1)
    { 
        if($status_type=='1' || $status_type=='2' ){
          $replace=" and status='".$status_type."'  ";
        }
        else  if($status_type=='both' ){
          $replace=" and ( status='1' or status='0' or status is null) ";
        }
       if(( strtoupper($cid)==strtoupper('b.tech') ||  strtoupper($cid)== strtoupper('dualdegree') ||  strtoupper($cid)== strtoupper('int.m.sc')||  strtoupper($cid)== strtoupper('int.msc.tech')||  strtoupper($cid)== strtoupper('int.m.tech') ||  strtoupper($cid)== strtoupper('comm')  ) && $sem<3){
	  $sql = "select * from result_declaration_log where  upper(dept_id)='COMM' and upper(course_id)='COMM' and upper(branch_id)='COMM' and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace."  and  section='".$sec_name."'";		
	}            
	else if( strtoupper($cid)== strtoupper('jrf')){            
           $sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."' and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."' ".$replace."";            
        }
       else if( $et== 'prep' && $did=='all' ){
           $sql = "select * from result_declaration_log where  course_id='PREP' and branch_id='PREP' and semester='".$sem."' and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'   ".$replace." ";
        }                  
       else{	 
	   $sql = "select * from result_declaration_log where upper(dept_id)='".strtoupper($did)."' and upper(course_id)='".strtoupper($cid)."' and upper(branch_id)='".strtoupper($bid)."' and semester=".$sem." and exam_type='".$et."' and s_year='".$sy."' and session='".$sess."'  ".$replace."";
        }        
        $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) 
            return $query->row();
         else 
            return FALSE;        
    }



	
     function count_row_regular($adm_no)
    {
        $sql = "select semester  from reg_regular_form where admn_no='".$adm_no."' and reg_regular_form.hod_status='1' and reg_regular_form.acad_status='1' and session_year='2015-2016' and session='Monsoon'";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function count_row_others($adm_no)
    {
        $sql = "select semester  from reg_exam_rc_form where admn_no='".$adm_no."' and reg_exam_rc_form.hod_status='1' and reg_exam_rc_form.acad_status='1' and session_year='2015-2016' and session='Monsoon'";



        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	function get_stu_minor_status($adm_no,$sem)
    {
        $sql = "select * from hm_form  where admn_no='".$adm_no."' and semester=".$sem." and minor='1' and minor_hod_status='Y'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
            return FALSE;
        }
    }
    
    
    
     function get_minor_branch_name($fno)
    {
        $sql = " select a.*,b.name as bnm,c.name as dnm from hm_minor_details a 
               inner join cs_branches b on b.id=a.branch_id
               inner join departments c on c.id=a.dept_id
               where form_id='".$fno."' and offered='1'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	// to get Latest exam_type wth other info  w.r.t. semester  & admn_no & course & branch & dept 
	function get_latest_exam_type($admno,$did,$cid,$bid,$sem,$h_status=null){
    if($h_status=='Y') $status="status"; else $status="core_status";             
    $sql="select z.* from(
			(
			SELECT B.*
			FROM (
			SELECT a.".$status." AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr 
			FROM final_semwise_marks_foil a
			WHERE a.admn_no=? and  a.course<>'MINOR' and a.semester=?			
			GROUP BY a.session_yr,a.session,a.semester,a.exam_type			
			ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
			GROUP BY B.sem) 
			UNION (
			SELECT A.*
			FROM (
			SELECT a.passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession as session_yr
			FROM tabulation1 a
			WHERE a.adm_no=? and a.sem_code not like 'PREP%' and  right(a.sem_code,1)=?
			GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms			
			ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
			GROUP BY A.sem_code)
			)z group by z.sem 
         ";
             
      
          
         $query = $this->db->query($sql, array($admno,$sem,$admno,($sem=='10'?'X':$sem)));       
      //  echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
           return $query->row();
        else {
            return 0;
        }
	}
	
    
    function get_examtype_others($admno,$session,$did,$cid,$bid,$sem)
    {		 
	   $sql = " select distinct tb.examtype from tabulation1 tb where tb.adm_no='".$admno."'  and   tb.sem_code= (select d.semcode from dip_m_semcode d where d.deptmis='".$did."' and d.course='".$cid."'  and d.branch='".$bid."' and d.sem='".$sem."')group by tb.examtype,tb.wsms,tb.sem_code order by tb.examtype desc,tb.wsms desc limit 1";
        $query = $this->db->query($sql);
      //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function get_examtype_check($admno,$session,$sess_yr,$did,$cid,$bid,$sem)
    {
        $sql=  " select distinct tb.examtype from reg_exam_rc_form tb where tb.admn_no='".$admno."' and tb.session='".$session."' and tb.session_year='".$sess_yr."'   and  tb.course_id='".$cid."'  and tb.branch_id='".$bid."' and tb.semester='".$sem."'  order by examtype desc limit 1";        
        $query = $this->db->query($sql);        
        // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function get_dept_cou_branch_by_admn_no($admno,$sem)
    {
        $sql = "select a.id,a.dept_id,b.course_id,b.branch_id,r.semester from user_details a 
               inner join stu_academic b on a.id=b.admn_no 
	 inner join reg_exam_rc_form r on a.id=r.admn_no
	 where a.id='".$admno."' and r.semester like '%".$sem."%' and r.hod_status='1' and r.acad_status='1' union select a.id,a.dept_id,b.course_id,b.branch_id,r.semester from user_details a 
               inner join stu_academic b on a.id=b.admn_no 
	 inner join reg_other_form r on a.id=r.admn_no
	 where a.id='".$admno."' and r.semester like '%".$sem."%' and r.hod_status='1' and r.acad_status='1'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    function get_others_sub_marks($adm_no,$sem,$type='O',$sy,$sess)
    {
        
       if($sess=='Summer'){
           
          
 $sql ="select A.*,c.name AS name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester= '".$sem."' and type='R' and session_yr='".$sy."' and session='".$sess."' ))A
               left join tabulation1 s on s.subje_code=A.sub_code and s.adm_no='".$adm_no."'
               left join subjects as c on ((CASE WHEN A.mis_sub_id <>''  THEN A.mis_sub_id=c.id ELSE A.sub_code=c.subject_id END))
                 group by A.sub_code";
        
           
       }
       else{
           
           if($type=='R'){$type='O';}
        /*$sql="Select A.*,s.name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester like '%".$sem."%' and type='".$type."' ))A
               inner join subjects s on s.subject_id=A.sub_code
                 group by A.sub_code";*/
        if($type=='O' ){
				 $sql ="select A.*, COALESCE(c.name,s.subje_name) AS name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester= '".$sem."' and type='".$type."' and session_yr='".$sy."' and session='".$sess."' ))A
               left join tabulation1 s on s.subje_code=A.sub_code and s.adm_no='".$adm_no."'
               left join subjects as c on ((CASE WHEN A.mis_sub_id <>''  THEN A.mis_sub_id=c.id ELSE A.sub_code=c.subject_id END))
                 group by A.sub_code";
        }
        //type R changed on 18 July 2016 for Summer result
           if($type=='S'||$type=='E'){
				 $sql ="select A.*,c.name AS name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester= '".$sem."' and type='".$type."' and session_yr='".$sy."' and session='".$sess."' ))A
               left join tabulation1 s on s.subje_code=A.sub_code and s.adm_no='".$adm_no."'
               left join subjects as c on ((CASE WHEN A.mis_sub_id <>''  THEN A.mis_sub_id=c.id ELSE A.sub_code=c.subject_id END))
                 group by A.sub_code";
        }
        
       } // end of else
        //(A.mis_sub_id=c.id or A.sub_code=c.subject_id) changes with (CASE WHEN A.mis_sub_id <>''  THEN A.mis_sub_id=c.id ELSE A.sub_code=c.subject_id END)
       $tsemester=$this->student_grade_model_new->get_course_duration_sem($adm_no);
       
      
       if($tsemester[0]->auth_id=='prep' && $tsemester<>null){
           $sql ="select A.*,c.name AS name from (SELECT * FROM final_semwise_marks_foil_desc WHERE foil_id=(SELECT id FROM final_semwise_marks_foil
              WHERE admn_no='".$adm_no."' AND semester= '".$sem."' and type='R' and session_yr='".$sy."' and session='".$sess."' ))A
               left join tabulation1 s on s.subje_code=A.sub_code and s.adm_no='".$adm_no."'
               left join subjects as c on ((CASE WHEN A.mis_sub_id <>''  THEN A.mis_sub_id=c.id ELSE A.sub_code=c.subject_id END))
                 group by A.sub_code";
           
       } 
        $query = $this->db->query($sql);        
      //  echo $this->db->last_query(); die();             
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }        
    }  
    
    
    function  get_others_stu_status($admn_no,$session,$session_year,$type,$mis_sub_code){
        
     $sql=" select stu_status from marks_subject_description where admn_no=? and marks_master_id=(select id  from marks_master m1 where m1.`session`=? 
            and m1.session_year=? and m1.`type`=? and m1.subject_id=? and m1.status='Y') ";
  
       $query = $this->db->query($sql,array($admn_no,$session,$session_year,$type,$mis_sub_code));
        
     //    echo $this->db->last_query(); die();
             
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }   
    }
    
   /* function check_partial_stu($id,$admn_no){
        $q="select max(published_on) as  published_on,status from result_declaration_log_partial_details where  res_dec_id='$id' and admn_no='$admn_no' ";
        $q=$this->db->query($q);
        if($q->row()->published_on !=null){
            return $q->row();
        }
        return false;
    }
    function check_partial_stu_multi($id,$admn_no,$type=null){
        if($type==1 || $type==2)
        $replace="  and rdec_type='".$type."' ";
        else 
        $replace="";
        $q="select published_on  as  published_on,status from result_declaration_log_partial_details where  res_dec_id='$id' and admn_no='$admn_no'  ".$replace."";
        $query=$this->db->query($q);
         if ($this->db->affected_rows() >= 0) 
            return $query->result();
         else 
            return false;
    }
    
        */
		
		
   function check_partial_stu($id,$admn_no){
        $q="select max(published_on) as  published_on,status from result_declaration_log_partial_details where  res_dec_id='$id' and admn_no='$admn_no' ";
        $q=$this->db->query($q);
        if($q->row()->published_on !=null){
            return $q->row();
        }
        return false;
    }
    function check_partial_stu_multi($id,$admn_no,$type=null){
        if($type==1 || $type==2)
        $replace="  and rdec_type='".$type."' ";
        else 
        $replace="";
        $q="select published_on  as  published_on,status,actual_published_on,rdec_type from result_declaration_log_partial_details where  res_dec_id='$id' and admn_no='$admn_no'  ".$replace."";
        $query=$this->db->query($q);
         if ($this->db->affected_rows() >= 0) 
            return $query->result();
         else 
            return false;
    }
    
    
    
    function get_stu_honours_status($adm_no,$sem,$sess,$sy)
    {
        $sql = "select * from hm_form  where admn_no='".$adm_no."' and semester=".$sem." and honours='1' and honour_hod_status='Y' and session='".$sess."' and session_year='".$sy."'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_stu_honours_status_new($adm_no,$sem)
    {
        $sql = "select * from hm_form  where admn_no='".$adm_no."' and semester=".$sem." and honours='1' and honour_hod_status='Y' limit 1";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
            return FALSE;
        }
    }
	 //---------------------------------------------------------------------------------------------------
    
            public function fetch_absentees($sy, $sess,$et,$res_type) {
                
        if($et=="Regular"){$et='R';} if($et=="Other"){$et='O';}if($et=="Special"){$et='S';}
    $sql="SELECT a.*,b.session_year,b.`session`,b.`type`,b.subject_id,d.subject_id,d.name,c.semester,concat_ws(' ',e.first_name ,e.middle_name,e.last_name) as stu_name,f.course_id,f.branch_id
FROM marks_subject_description a
INNER JOIN marks_master b ON a.marks_master_id=b.id
INNER JOIN subject_mapping c ON c.map_id=b.sub_map_id
inner join subjects d on b.subject_id=d.id
inner join user_details e on e.id=a.admn_no
inner join stu_academic f on f.admn_no=a.admn_no
WHERE  b.session_year=? and b.`session`=? and b.`type`=?
and a.stu_status=?";



            $query = $this->db->query($sql,array($sy, $sess,$et,$res_type));
//echo $this->db->last_query();die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    
    
        public function update_marks_subject_description_for_absenties($id) 
        {
            
         $sql = "update marks_subject_description set stu_status=null where id=".$id;

        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
                
        
            
        }
    //----------------------------------------------------------------------------------------------------------
        public function get_details($admn_no){
            
            $sql="select a.session_year,b.dept_id,a.course_id,a.branch_id,a.semester from reg_regular_form a
inner join user_details b on a.admn_no=b.id
where a.semester=(select max(semester)as semester from reg_regular_form where admn_no=?)
and a.admn_no=?";



            $query = $this->db->query($sql,array($admn_no,$admn_no));
//echo $this->db->last_query();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
            
        }
        //checking for minor honours not being used
        public function get_student_session_year($admn_no,$sem){
            
            $sql="select distinct session_year from reg_regular_form where admn_no=? and semester=?";
            $query = $this->db->query($sql,array($admn_no,$sem));
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
            
        }
     
}

?>