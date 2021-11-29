<?php

class Stu_sub_weighage_model extends CI_Model
{


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
function getSessionYear(){
	$sql="select * from mis_session_year order by id desc";
	$query = $this->db->query($sql);
#echo" common ".  $this->db->last_query(); exit;

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }
}
public function getStuDetails($id,$sessionYear,$session){
$sql="select * from reg_regular_form a where a.admn_no='$id' and a.session_year='$sessionYear' and a.`session`='$session' and a.acad_status='1' and a.hod_status='1'";
	$query = $this->db->query($sql);
	//echo" common ".  $this->db->last_query(); exit;

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }
}

function get_stu_marks($sub_code,$session_year,$session,$course_id,$branch_id){
	$stu_id=$this->session->userdata('id');
	$sql="select a.*,GROUP_CONCAT(concat_ws(' - ',b.category_name,b.marks)) as marks from cbcs_marks_upload a
inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.subject_code='$sub_code' and a.admn_no='$stu_id'
and a.session_year='$session_year' and a.session='$session' and a.branch_id='$branch_id' and a.course_id='$course_id'
 group by a.admn_no";
		$query = $this->db->query($sql);
	//	echo" common ".  $this->db->last_query(); exit;

	    if ($this->db->affected_rows() > 0) {
	         return $query->result();
	     } else {
	         return false;
	     }
}
public function get_stu_subject($form_id,$stu_id,$sessionYear,$session,$section,$course_aggr_id,$course_id,$branch_id){

	if(strpos($course_aggr_id, 'comm') !== false){
		$sectionjoin="inner join stu_section_data s on a.admn_no=s.admn_no";
		$condition="and b.sub_group='$section' and c.section=s.section";
		$branch_id="comm";
		$course_id="comm";
	}else{
	//	echo "not commm";
	}

	$sql="(select a.*,b.id as offered_id,c.emp_no,concat(u.salutation,' ',u.first_name,' ',u.middle_name,' ',u.last_name) as name,b.semester from cbcs_stu_course a INNER JOIN  cbcs_subject_offered b on a.subject_code=b.sub_code
inner join cbcs_subject_offered_desc c on b.id=c.sub_offered_id
inner join user_details u on c.emp_no=u.id
$sectionjoin
where a.admn_no='$stu_id' and a.form_id='$form_id' and a.session_year='$sessionYear' and a.session='$session' $condition and  c.coordinator='1' and b.branch_id='$branch_id' and b.course_id='$course_id'
order by SUBSTR(a.sub_category,3)+0 asc)
union all
(
SELECT a.*,b.id AS offered_id,c.emp_no, CONCAT(u.salutation,' ',u.first_name,' ',u.middle_name,' ',u.last_name) AS name,b.semester
FROM old_stu_course a
INNER JOIN old_subject_offered b ON a.subject_code=b.sub_code
INNER JOIN old_subject_offered_desc c ON b.id=c.sub_offered_id
$sectionjoin
INNER JOIN user_details u ON c.emp_no=u.id
WHERE a.admn_no='$stu_id' AND a.form_id='$form_id' AND a.session_year='$sessionYear' AND a.session='$session' $condition AND c.coordinator='1' AND b.branch_id='$branch_id' AND b.course_id='$course_id'
ORDER BY SUBSTR(a.sub_category,3)+0 ASC)

";
	$query = $this->db->query($sql);
//echo" common ".  $this->db->last_query(); exit;

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }
}

/*function get_detail_weightage($subid,$syear,$sess,$empno,$course,$branch,$sem){
	$sql="select b.*,mst.sub_core_category from cbcs_marks_dist_child b
inner join cbcs_marks_dist a  on b.id=a.id
inner join cbcs_marks_dist_sub_core_master mst on mst.sub_core_category_id=b.category
where a.sub_code=? and a.session_year=? and a.`session`=? and a.emp_no=?
and a.course_id=? and a.branch_id=? and a.semester=? ";
	$query = $this->db->query($sql,array($subid,$syear,$sess,$empno,$course,$branch,$sem));
	//echo" common ".  $this->db->last_query(); exit;

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}*/

function get_detail_weightage($subid,$syear,$sess,$empno,$course,$branch,$sem){
	$sql="select b.* from cbcs_marks_dist_child b
inner join cbcs_marks_dist a  on b.id=a.id

where a.sub_code=? and a.session_year=? and a.`session`=? and a.emp_no=?
and a.course_id=? and a.branch_id=? and a.semester=? ";
	$query = $this->db->query($sql,array($subid,$syear,$sess,$empno,$course,$branch,$sem));
	//echo" common ".  $this->db->last_query(); exit;

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}

function get_detail_weightage_download($subid,$syear,$sess,$empno,$course,$branch,$sem){
	$sql="select a.lecture_plan_path from cbcs_marks_dist a where
	a.session_year='$syear' and a.`session`='$sess' and a.branch_id='$branch' and a.course_id='$course' and a.semester='$sem' and a.sub_code='$subid' ";
	$query = $this->db->query($sql,array($subid,$syear,$sess,$empno,$course,$branch,$sem));
	//echo" common ".  $this->db->last_query();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}
function get_student_all_subject_common($admm_no, $sem,$sec, $syear, $sess){

		$sem_sec=$sem.'_'.$sec;
		$sql = "(select a.course_id as course,a.branch_id as branch,a.semester as semester,a.session_year as session_year,a.`session` as session, c.*,g.subject_id as subject_code,g.name as subject_name,f.aggr_id,f.semester as sem,concat_ws(' ' ,d.first_name,d.middle_name,d.last_name)as name from reg_regular_form a
inner join subject_mapping b on b.session_year=a.session_year and b.`session`=a.`session` and b.aggr_id=a.course_aggr_id and b.semester=a.semester
inner join subject_mapping_des c on c.map_id=b.map_id
inner join user_details d on d.id=c.emp_no
inner join stu_section_data e on e.admn_no=a.admn_no and e.session_year=a.session_year and e.section=b.section
inner join course_structure f on f.id =c.sub_id /*and f.semester=a.semester*/ and f.sequence like '%.%'
inner join subjects g on g.id=f.id
where a.admn_no='$admm_no' and a.session_year='$syear' and a.`session`='$sess'
and a.hod_status='1' and a.acad_status='1'
group by c.sub_id)

union all
(select a.course_id as course,a.branch_id as branch,a.semester as semester,a.session_year as session_year,a.`session` as session, c.*,g.subject_id as subject_code,g.name as subject_name,f.aggr_id,f.semester as sem,concat_ws(' ' ,d.first_name,d.middle_name,d.last_name)as name from reg_regular_form a
inner join subject_mapping b on b.session_year=a.session_year and b.`session`=a.`session` and b.aggr_id=a.course_aggr_id and b.semester=a.semester
inner join subject_mapping_des c on c.map_id=b.map_id
inner join user_details d on d.id=c.emp_no
inner join stu_section_data e on e.admn_no=a.admn_no and e.session_year=a.session_year and e.section=b.section
inner join course_structure f on f.id =c.sub_id /*and f.semester=a.semester*/ and f.sequence not like '%.%'
inner join subjects g on g.id=f.id
inner join reg_regular_elective_opted h on h.form_id=a.form_id
where a.admn_no='$admm_no' and a.session_year='$syear' and a.`session`='$sess'
and a.hod_status='1' and a.acad_status='1'
group by c.sub_id)
";

		$query = $this->db->query($sql,array($admm_no, $sem,$sec, $syear, $sess,$admm_no, $sem,$sec, $syear, $sess));

	# echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}
}
function get_student_all_subject($admm_no,$sem,$syear,$sess)
{

		$sql = "select x.* from((select a.subject_id,a.name,'Core' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.course_aggr_id from reg_regular_form a  where a.admn_no=? and
a.semester=? and a.session_year=? and a.`session`=? and a.hod_status='1' and a.acad_status='1') and b.semester=?
and b.sequence not like '%.%')
union
(
select d.subject_id,d.name,
CASE WHEN c.aggr_id like '%honour%' THEN 'Elective(Honour)' when c.aggr_id  like '%minor%' THEN 'Elective(Minor)'  WHEN c.aggr_id NOT LIKE '%honour%' THEN 'Elective'  else 'Elective' END AS paper_type,d.id as sub_id,c.aggr_id
from reg_regular_elective_opted a
inner join reg_regular_form b on a.form_id=b.form_id
inner join course_structure c on c.id=a.sub_id
inner join subjects d on d.id=a.sub_id
where b.admn_no=? and  b.semester=?
and b.session_year=? and b.`session`=?
and b.hod_status='1' and b.acad_status='1')
union
(
select a.subject_id,a.name,'Honours' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.honours_agg_id from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y' limit 1)
and b.semester=? and b.sequence not like '%.%'
)
union
(
select a.subject_id,a.name,'Minor' as 'paper_type',a.id as sub_id,b.aggr_id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(
select m.minor_agg_id from hm_minor_details m where
m.form_id=(select a.form_id from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y')
and m.offered='1')
and b.semester=?))x
group by x.sub_id
";
$query = $this->db->query($sql,array($admm_no,$sem,$syear,$sess,$sem,$admm_no,$sem,$syear,$sess,$admm_no,$sem,$admm_no,$sem));



	 //echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}

}
public function get_details_core($stu_id,$sessionYear,$session) {

		$curr_status = $this->get_student_latest_status($stu_id,$sessionYear,$session); //latest registration from reg_regular_form
		$form_id = $curr_status->form_id;
		$sy = $curr_status->session_year;
		$sess = $curr_status->session;
		$aggid = $curr_status->course_aggr_id;
		$sem = $curr_status->semester;
		$tmp_cid = explode('_', $aggid);
		if ($tmp_cid[0] == 'comm') {
				$sec = $this->get_section($admn_no, $sy);
				$mapid = $this->get_map_id_comm($sy, $sess, $aggid, $sem, $sec); //mapid from subject_mapping
		} else {
				$mapid = $this->get_map_id($sy, $sess, $aggid, $sem); //mapid from subject_mapping
		}
		$hon = $this->get_honours($admn_no, $sem, $sy, $sess);
		$minor = $this->get_minor($admn_no, $sem, $sy, $sess);

		$sql = "select a.id,b.subject_id as subject_code,b.name as subject_name,'Core' as papertype,
concat_ws(' ',d.first_name,d.middle_name,d.last_name) as faculty,d.photopath,e.name as deptnm,c.emp_no,c.map_id from course_structure a
inner join subjects b on a.id=b.id
inner join subject_mapping_des c on c.sub_id=b.id
inner join user_details d on d.id=c.emp_no
inner join departments e on e.id=d.dept_id
where a.aggr_id=? and a.semester like '%?%' and a.sequence not like '%.%'
and (b.`type`='Theory' or b.`type`='Sessional') and c.map_id=?  group by c.sub_id,c.emp_no";

		$query = $this->db->query($sql, array($aggid, (int) $sem, $mapid->map_id));

	//	echo $this->db->last_query();die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}
}
public function get_details_elective($stu_id,$sessionYear,$session) {
		$curr_status = $this->get_student_latest_status($stu_id,$sessionYear,$session); //latest registration from reg_regular_form
		$form_id = $curr_status->form_id;
		$sy = $curr_status->session_year;
		$sess = $curr_status->session;
		$aggid = $curr_status->course_aggr_id;
		$sem = $curr_status->semester;
		$tmp_cid = explode('_', $aggid);
		if ($tmp_cid[0] == 'comm') {
				$sec = $this->get_section($admn_no, $sy);
				$mapid = $this->get_map_id_comm($sy, $sess, $aggid, $sem, $sec); //mapid from subject_mapping
		} else {
				$mapid = $this->get_map_id($sy, $sess, $aggid, $sem); //mapid from subject_mapping
		}
		$hon = $this->get_honours($admn_no, $sem, $sy, $sess);
		$minor = $this->get_minor($admn_no, $sem, $sy, $sess);

		$sql = "
SELECT A.*,s.name,'Elective' AS papertype, CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name) AS faculty,d.photopath,e.name AS deptnm,
c.sub_id as sub_id1,c.map_id,c.emp_no
FROM
(
SELECT a.sub_id,b.subject_id,a.form_id
FROM reg_regular_elective_opted a
INNER JOIN subjects b ON b.id=a.sub_id
WHERE a.form_id=?)A
INNER JOIN subjects s ON s.subject_id=A.subject_id
INNER JOIN subject_mapping_des c ON c.sub_id=s.id
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN departments e ON e.id=d.dept_id
WHERE A.form_id=? AND c.map_id=?  group by A.sub_id,c.emp_no";

		$query = $this->db->query($sql, array($form_id, $form_id, $mapid->map_id));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}
}
public function get_details_elective_count($stu_id,$sessionYear,$session, $subid) {
		$curr_status = $this->get_student_latest_status($admn_no); //latest registration from reg_regular_form
		$form_id = $curr_status->form_id;
		$sy = $curr_status->session_year;
		$sess = $curr_status->session;
		$aggid = $curr_status->course_aggr_id;
		$sem = $curr_status->semester;
		$mapid = $this->get_map_id($sy, $sess, $aggid, $sem); //mapid from subject_mapping
		$hon = $this->get_honours($admn_no, $sem, $sy, $sess);
		$minor = $this->get_minor($admn_no, $sem, $sy, $sess);

		$sql = "select count(x.sub_id)as count_sum from
(SELECT A.*,s.name,'Elective' AS papertype, CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name) AS faculty,d.photopath,e.name AS deptnm,
c.sub_id as sub_id1,c.map_id,c.emp_no
FROM
(
SELECT a.sub_id,b.subject_id,a.form_id
FROM reg_regular_elective_opted a
INNER JOIN subjects b ON b.id=a.sub_id
WHERE a.form_id=?)A
INNER JOIN subjects s ON s.id=A.sub_id
INNER JOIN subject_mapping_des c ON c.sub_id=s.id
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN departments e ON e.id=d.dept_id
WHERE A.form_id=? AND c.map_id=?  group by A.sub_id,c.emp_no)x
where x.sub_id=?";

		$query = $this->db->query($sql, array($form_id, $form_id, $mapid->map_id, $subid));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->row()->count_sum;
		} else {
				return false;
		}
}
public function get_details_honour($stu_id,$sessionYear,$session) {
		$curr_status = $this->get_student_latest_status($stu_id,$sessionYear,$session); //latest registration from reg_regular_form
		$form_id = $curr_status->form_id;
		$sy = $curr_status->session_year;
		$sess = $curr_status->session;
		$aggid = $curr_status->course_aggr_id;
		$sem = $curr_status->semester;
		$mapid = $this->get_map_id($sy, $sess, $aggid, $sem); //mapid from subject_mapping
		$hon = $this->get_honours($admn_no, $sem, $sy, $sess);
		$minor = $this->get_minor($admn_no, $sem, $sy, $sess);

		$sql = "
select a.sub_id,b.subject_id,b.name,'Honours' as papertype,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as faculty,c.photopath,e.name as deptnm,a.emp_no,a.map_id from subject_mapping_des a
inner join subjects b on a.sub_id=b.id inner join user_details c on c.id=a.emp_no
inner join departments e on e.id=c.dept_id
where a.map_id=? and (b.`type`='Theory' or b.`type`='Sessional')  group by a.sub_id,a.emp_no
";

		$query = $this->db->query($sql, array($hon->map_id));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}
}

    function get_map_id($sy, $sess, $aggid, $sem) {
        $sql = "select a.* from subject_mapping a where a.session_year=? and a.`session`=? and a.aggr_id=? and a.semester like '%?%' order by timestamp desc limit 1";

        $query = $this->db->query($sql, array($sy, $sess, $aggid, (int) $sem));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
		function get_honours($id, $sem, $syear, $sess) {
		$sql = "select b.* from hm_form a inner join subject_mapping b on (b.aggr_id=a.honours_agg_id and b.semester like '%?%')
where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y' and b.session_year=? and b.`session`=?;";

		$query = $this->db->query($sql, array((int) $sem, $id, $syear, $sess));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->row();
		} else {
				return false;
		}
}

function get_minor($id, $sem, $syear, $sess) {
		$sql = "select c.* from hm_form a inner join hm_minor_details b on b.form_id=a.form_id
inner join subject_mapping c on (c.aggr_id=b.minor_agg_id and c.semester like '%?%') where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y' and b.offered='1' and c.session_year=? and c.`session`=? ;";

		$query = $this->db->query($sql, array((int) $sem, $id, $syear, $sess));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->row();
		} else {
				return false;
		}
}

function get_details_facutly($id, $sy, $sess) {


		$sql = "select b.*,c.subject_id,c.name,a.semester,f.name as deptnm,h.name as cname,i.name as bname from subject_mapping a
inner join subject_mapping_des b on a.map_id=b.map_id
inner join subjects c on c.id=b.sub_id
inner join course_structure d on d.id=c.id
inner join dept_course e on e.aggr_id=d.aggr_id
inner join departments f on f.id=e.dept_id
inner join course_branch g on g.course_branch_id=e.course_branch_id
inner join cs_courses h on h.id=g.course_id
inner join cs_branches i on i.id=g.branch_id
where a.session_year=? and a.`session`=?
and b.emp_no=? and (c.`type`='Theory' or c.`type`='Sessional')group by b.sub_id
";

		$query = $this->db->query($sql, array($sy, $sess, $id));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
				return $query->result();
		} else {
				return false;
		}
}
function get_student_latest_status($stu_id,$sessionYear,$session) {
		//$sql = "select a.* from reg_regular_form a where a.admn_no=? and hod_status='1' and acad_status='1' order by a.semester desc limit 1;";

		$sql = "select a.* from reg_regular_form a where a.admn_no=? and a.session_year=? and a.session=?  and hod_status='1' and acad_status='1' order by a.timestamp desc limit 1;";


		$query = $this->db->query($sql, array($stu_id,$sessionYear,$session));

		//echo $this->db->last_query(); die();
		if ($this->db->affected_rows() >= 0) {
	//    print_r(query->row());
				return $query->row();
		} else {
				return false;
		}
}
public function get_section($id, $session_year) {
			 $basic_query = " SELECT section FROM stu_section_data WHERE admn_no = '$id' AND session_year = '$session_year' ";
			 $result = $this->db->query($basic_query)->result();
			 if (count($result) == 0) {
					 return NULL;
			 } else
					 return $result[0]->section;
	 }

	 function get_map_id_comm($sy, $sess, $aggid, $sem, $sec) {

			 $sql = "select a.* from subject_mapping a where a.session_year=? and a.`session`=? and a.aggr_id=? and a.semester=? and a.section=?";

			 $query = $this->db->query($sql, array($sy, $sess, $aggid, $sem, $sec));

			 //echo $this->db->last_query(); die();
			 if ($this->db->affected_rows() >= 0) {
					 return $query->row();
			 } else {
					 return false;
			 }
	 }

	 function get_subject_name($id) {

			 $sql = "select concat(a.name,'(',a.subject_id,')')as subj_name from subjects a where a.id=?";

			 $query = $this->db->query($sql, array($id));

			 //echo $this->db->last_query();
			 if ($this->db->affected_rows() >= 0) {
					 return $query->row();
			 } else {
					 return false;
			 }
	 }

	 //Functionality of mark drop
	 function get_mapping_details($map_id) {
			 $sql = "select * from subject_mapping where map_id=?";

			 $query = $this->db->query($sql, array($map_id));

			 // echo $this->db->last_query();
			 if ($this->db->affected_rows() >= 0) {
					 return $query->row();
			 } else {
					 return false;
			 }
	 }
	 public function get_details_minor($admn_no) {
			 $curr_status = $this->get_student_latest_status($stu_id,$sessionYear,$session); //latest registration from reg_regular_form
			 $form_id = $curr_status->form_id;
			 $sy = $curr_status->session_year;
			 $sess = $curr_status->session;
			 $aggid = $curr_status->course_aggr_id;
			 $sem = $curr_status->semester;
			 $mapid = $this->get_map_id($sy, $sess, $aggid, $sem); //mapid from subject_mapping
			 $hon = $this->get_honours($admn_no, $sem, $sy, $sess);
			 $minor = $this->get_minor($admn_no, $sem, $sy, $sess);

			 $sql = "
select a.sub_id,b.subject_id,b.name,'Minor' as papertype,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as faculty,c.photopath,e.name as deptnm,a.emp_no,a.map_id from subject_mapping_des a
inner join subjects b on a.sub_id=b.id inner join user_details c on c.id=a.emp_no
inner join departments e on e.id=c.dept_id
where a.map_id=? and (b.`type`='Theory' or b.`type`='Sessional') group by a.sub_id,a.emp_no
";

			 $query = $this->db->query($sql, array($minor->map_id));

			 //echo $this->db->last_query(); die();
			 if ($this->db->affected_rows() >= 0) {
					 return $query->result();
			 } else {
					 return false;
			 }
	 }


}
