<?php

class Faculty_model extends CI_Model
{
	public function get_faculty_subjects($data)
	{
		$teacher_id = $data['id'];
		$session = $data['session'];
		$session_year = $data['session_year'];
	//	$basic_query = " SELECT DISTINCT sub_id AS subject_id FROM subject_mapping_des WHERE emp_no  = '$teacher_id' AND map_id IN (SELECT map_id FROM subject_mapping WHERE session = '$session' AND session_year = '$session_year') AND sub_id IN (SELECT id FROM subjects WHERE type = 'Theory' OR type = 'Sessional') ";

	$basic_query = "SELECT p.* FROM(
(
SELECT  CONCAT('c_',a.sub_id) AS subject_id,a.section,a.sub_offered_id,a.emp_no,a.sub_id,b.dept_id,b.course_id,b.branch_id,b.semester
,b.sub_name,b.sub_code,b.session_year,b.`session` FROM cbcs_subject_offered_desc a
INNER JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
WHERE a.emp_no='".$teacher_id."' AND b.session_year='".$session_year."' AND b.`session`='".$session."')
UNION
(
SELECT  CONCAT('o_',a.sub_id) AS subject_id,a.section,a.sub_offered_id,a.emp_no,a.sub_id,b.dept_id,b.course_id,b.branch_id,b.semester
,b.sub_name,b.sub_code,b.session_year,b.`session` FROM old_subject_offered_desc a
INNER JOIN old_subject_offered b ON b.id=a.sub_offered_id
WHERE a.emp_no='".$teacher_id."' AND b.session_year='".$session_year."' AND b.`session`='".$session."')
)p GROUP BY p.subject_id";


		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_groups()
	{
		$basic_query = " SELECT group_no,group_name FROM fbs_group ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_parameters()
	{
		$basic_query = " SELECT group_no,parameter_no,parameter_name FROM fbs_parameter ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback($subject_id,$emp_no,$map_id)
	{
		$basic_query = " SELECT * FROM fbs_feedback_details WHERE subject_id = '$subject_id' AND teacher_id = '$emp_no' AND map_id = '$map_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_ratings($feedback_id)
	{

		$basic_query = " SELECT * FROM fbs_feedback WHERE feedback_id = '$feedback_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_subject_info_from_subject_id($subject_id)
	{
	// 18-4-19	$basic_query = " SELECT id,subject_id,name FROM subjects WHERE id = '$subject_id' ";
	$basic_query = " SELECT c.id,c.subject_id,c.name, cs.aggr_id FROM subjects c join  course_structure cs on cs.id=c.id   WHERE c.id = '$subject_id' ";
		return $this->db->query($basic_query)->result();
	}
	//--------------
	public function check_sequence($id)
	{
		$basic_query = "select a.sequence from course_structure a where a.id='".$id."'";
		return $this->db->query($basic_query)->row()->sequence;
	}

	//---------------

	public function get_map_id_by_subject_id($subject_id,$emp_no,$syear,$sess)
	{
		//echo $subject_id; echo $emp_no;die();
		//$basic_query = " SELECT map_id FROM subject_mapping_des WHERE sub_id = '$subject_id' AND emp_no = '$emp_no' ";
		$tmp=explode('_',$subject_id);
		if($tmp[0]=='c'){
		$tbl="cbcs_subject_offered"	;
		$tbl_desc="cbcs_subject_offered_desc";
		}
		if($tmp[0]=='o'){
		$tbl="old_subject_offered"	;
		$tbl_desc="old_subject_offered_desc";
		}
		$basic_query="SELECT a.sub_offered_id AS map_id
FROM ".$tbl_desc." a
INNER JOIN ".$tbl." b ON b.id=a.sub_offered_id
WHERE a.emp_no='".$emp_no."' AND b.session_year='".$syear."' AND b.`session`='".$sess."' AND a.sub_id='".$tmp[1]."'
GROUP BY a.sub_offered_id";
		return $this->db->query($basic_query)->result();
	}
	public function get_department_name_by_department_id($dept_id)
	{
		$basic_query = " SELECT name FROM departments WHERE id = '$dept_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_branch_name_by_branch_id($branch_id)
	{
		$basic_query = " SELECT name FROM cbcs_branches WHERE id = '$branch_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_course_name_by_course_id($course_id)
	{
		$basic_query = " SELECT name FROM cbcs_courses WHERE id = '$course_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_subject_details($subject_id,$map_id)
	{
		$tmp=explode('_',$subject_id);
		if($tmp[0]=='c'){
			$tbl="cbcs_subject_offered"	;
			$tbl_desc="cbcs_subject_offered_desc";
		}
	if($tmp[0]=='o'){
		$tbl="old_subject_offered"	;
		$tbl_desc="old_subject_offered_desc";
	}

		//$basic_query = " SELECT * FROM subject_mapping WHERE map_id = '$map_id' ";
		$basic_query = "SELECT * FROM ".$tbl." WHERE id=".$map_id;
		return $this->db->query($basic_query)->result();
	}
	public function get_user_name_by_user_id($teacher_id)
	{
		$basic_query = " SELECT concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$teacher_id' ";
		return $this->db->query($basic_query)->result();
	}

	public function get_feedback_groups_fbr()
	{
		$basic_query = " SELECT group_no,group_name FROM fbr_group ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_parameters_fbr()
	{
		$basic_query = " SELECT group_no,parameter_no,parameter_name FROM fbr_parameter ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_fbr($subject_id)
	{
		$basic_query = " SELECT * FROM fbr_feedback_details WHERE subject_id = '$subject_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_ratings_fbr($feedback_id)
	{
		$basic_query = " SELECT * FROM fbr_feedback WHERE feedback_id = '$feedback_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_photo_path_by_faculty_id($id)
	{
		$basic_query = " SELECT photopath FROM user_details WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	function get_tot_class_by_sub_id_map_id($subject_id,$map_id,$fid){
		$tmp=explode('_',$subject_id);
		$oid=$tmp[0].$map_id;
		$id=$fid;

		$basic_query = "SELECT total_class FROM cbcs_class_engaged WHERE subject_offered_id = '$oid'
		AND engaged_by='$id' /*AND section='B'*/ ORDER BY id DESC LIMIT 1 ";
		return $this->db->query($basic_query)->result();


	}
	public function get_count_students_registered($session,$session_yr,$course,$br,$sub_code1,$section,$emp_no)
	{
		$tmp=explode('_',$sub_code1);
		$sub_code=	$tmp[1];
if($course=='comm'){
	
	$sec=$this->get_faculty_section($session_yr,$session,$sub_code,$emp_no);
	if (substr_count($sec, ',') > 0) {
                  $sec = "'" . implode("','", explode(',', $sec)) . "'";
    }
	else{
		$sec = "'" . $sec . "'";
	}
	
			
		$basic_query = " SELECT COUNT(p.admn_no) AS ctr FROM( (SELECT a.* FROM reg_regular_form a
INNER JOIN cbcs_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN stu_section_data c ON c.admn_no=a.admn_no AND c.session_year='2019-2020'
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code' AND c.section in (".$sec ."))union
(SELECT a.*
FROM reg_regular_form a
INNER JOIN old_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN stu_section_data c ON c.admn_no=a.admn_no AND c.session_year='2019-2020'
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code' AND c.section in (".$sec .")))p";
}else{
	$basic_query = " SELECT COUNT(p.admn_no) AS ctr FROM( (SELECT a.* FROM reg_regular_form a
INNER JOIN cbcs_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code')union
(SELECT a.*
FROM reg_regular_form a
INNER JOIN old_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code'))p";

}
		$temp= $this->db->query($basic_query)->result();
			//	echo"<br>tmp :". print_r($temp->ctr);

			 //echo $this->db->last_query();die();
		   return  $temp[0]->ctr;
	}
	//count only feedback given students
	public function get_count_students_given_feedback($session,$session_yr,$course,$br,$sub_code1,$section,$sem,$emp_no)
	{
		$tmp=explode('_',$sub_code1);
		$sub_code=	$tmp[1];
		
		

if($course=='comm'){
			$sec=$this->get_faculty_section($session_yr,$session,$sub_code,$emp_no);
	if (substr_count($sec, ',') > 0) {
                  $sec = "'" . implode("','", explode(',', $sec)) . "'";
    }
	else{
		$sec = "'" . $sec . "'";
	}
	
		$basic_query = " SELECT COUNT(x.admn_no)AS ctr  FROM(
SELECT (a.admn_no)
FROM fb_student_feedback_cbcs a
WHERE a.admn_no IN (SELECT p.admn_no FROM( (SELECT a.* FROM reg_regular_form a
INNER JOIN cbcs_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN stu_section_data c ON c.admn_no=a.admn_no AND c.session_year='2019-2020'
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code' AND c.section in (".$sec ."))union
(SELECT a.*
FROM reg_regular_form a
INNER JOIN old_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN stu_section_data c ON c.admn_no=a.admn_no AND c.session_year='2019-2020'
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code' AND c.section in (".$sec .")))p ) GROUP BY a.admn_no)x";
}else{
	$basic_query = " SELECT COUNT(x.admn_no)AS ctr  FROM(
SELECT (a.admn_no)
FROM fb_student_feedback_cbcs a
WHERE a.admn_no IN ( SELECT p.admn_no FROM( (SELECT a.* FROM reg_regular_form a
INNER JOIN cbcs_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code')union
(SELECT a.*
FROM reg_regular_form a
INNER JOIN old_stu_course b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE a.session_year='$session_yr' AND a.`session`='$session' AND a.hod_status='1' AND a.acad_status='1'
AND b.course='$course' AND b.branch='$br' AND b.subject_code='$sub_code'))p) GROUP BY a.admn_no)x";

}

		$temp= $this->db->query($basic_query)->result();
			//	echo"<br>tmp :". print_r($temp->ctr);

			// echo $this->db->last_query();
		   return  $temp[0]->ctr;
	}
	
	function get_faculty_section($session_yr,$session,$sub_code,$fid)
	{
		$basic_query = "select group_concat(b.section ORDER BY b.section)AS section from cbcs_subject_offered a
inner join cbcs_subject_offered_desc b on b.sub_offered_id=a.id
where b.emp_no='".$fid."' and a.session_year='".$session_yr."' and a.`session`='".$session."'
and a.sub_code='".$sub_code."' GROUP BY a.sub_code ";
		return $this->db->query($basic_query)->row()->section;
		
	}

}
