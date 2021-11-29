<?php


class Hod_model extends CI_Model
{
	public function get_departments()
	{
		$basic_query = " SELECT id,name FROM departments WHERE type = 'academic' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_faculty_subjects($data)
	{
		$teacher_id = $data['id'];
		$session = $data['session'];
		$session_year = $data['session_year'];
		$basic_query = " SELECT sub_id AS subject_id FROM subject_mapping_des WHERE emp_no = '$teacher_id' AND map_id IN (SELECT map_id FROM subject_mapping WHERE session = '$session' AND session_year = '$session_year') AND sub_id IN (SELECT id FROM subjects WHERE type = 'Theory' OR type = 'Sessional')";
		return $this->db->query($basic_query)->result();
	}
	public function get_faculty_by_department($id)
	{
		$basic_query = " select a.id,concat(a.salutation,' ',a.first_name,' ',a.middle_name,' ',a.last_name) AS name from user_details a 
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
where c.`status`='A' and b.auth_id='ft'
and a.dept_id='".$id."' order by a.first_name,a.middle_name,a.last_name ";
		return $this->db->query($basic_query)->result();
	}
	public function get_faculty_name_by_department($id)
	{
		$basic_query = " SELECT id,concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id LIKE '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_groups_fbs()
	{
		$basic_query = " SELECT group_no,group_name FROM fbs_group ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_parameters_fbs()
	{
		$basic_query = " SELECT group_no,parameter_no,parameter_name FROM fbs_parameter ";
		return $this->db->query($basic_query)->result();
	}
	public function get_subject_info_from_subject_id($subject_id)
	{
		$basic_query = " SELECT subject_id,name FROM subjects WHERE id = '$subject_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_department_name_by_department_id($department)
	{
		$basic_query = " SELECT name FROM departments WHERE id = '$department' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_course_name_by_course_id($course_id)
	{
		$basic_query = " SELECT name FROM courses WHERE id = '$course_id' ";
		return $this->db->query($basic_query)->result(); 
	}
	public function get_feedback_fbs($subject_id,$emp_no,$map_id)
	{
		$basic_query = " SELECT * FROM fbs_feedback_details WHERE subject_id = '$subject_id' AND teacher_id = '$emp_no' AND map_id = '$map_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_ratings($feedback_id)
	{
		$basic_query = " SELECT * FROM fbs_feedback WHERE feedback_id = '$feedback_id' ";
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
	public function get_courses()
	{
		$basic_query = " SELECT * FROM courses ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_groups_fbe()
	{
		$basic_query = " SELECT group_no,group_name FROM fbe_group ";
		return $this->db->query($basic_query)->result();
	}
	public function get_feedback_parameters_fbe()
	{
		$basic_query = " SELECT group_no,parameter_no,parameter_name FROM fbe_parameter ";
		return $this->db->query($basic_query)->result();
	}
	public function get_exit_feedback($data)
	{
		$passing_year = $data['passing_year'];
		$course = $data['course'];
		$department = $data['department'];
		$basic_query = " SELECT * FROM fbe_feedback_details WHERE batch_id IN (SELECT batch_id FROM fbe_batch_details ";
		if ($passing_year != 'select')
			$basic_query .= "WHERE year_of_graduation = '$passing_year' ";
		if ($course != 'select')
		{
			if ($passing_year != 'select')
				$basic_query .= "AND course = '$course' ";
			else
				$basic_query .= "WHERE course = '$course' ";
		}
		if ($department != 'select')
		{
			if ($passing_year != 'select' || $course != 'select')
				$basic_query .= "AND branch = '$department' ";
			else
				$basic_query .= "WHERE branch = '$department' ";
		}
		$basic_query .= ")";
		//echo $basic_query;
		return $this->db->query($basic_query)->result();
	}
	public function get_user_name_by_user_id($teacher_id)
	{
		$basic_query = " SELECT concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$teacher_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_map_id_by_subject_id($subject_id,$emp_no)
	{
		$basic_query = " SELECT map_id FROM subject_mapping_des WHERE sub_id = '$subject_id' AND emp_no = '$emp_no' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_subject_details($map_id)
	{
		$basic_query = " SELECT * FROM subject_mapping WHERE map_id = '$map_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_ratings_fbe($feedback_id)
	{
		$basic_query = " SELECT * FROM fbe_feedback WHERE feedback_id = '$feedback_id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_photo_path_by_faculty_id($id)
	{
		$basic_query = " SELECT photopath FROM user_details WHERE id = '$id' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_branch_name_by_branch_id($branch_id)
	{
		$basic_query = " SELECT name FROM branches WHERE id = '$branch_id' ";
		return $this->db->query($basic_query)->result();
	}
}
?>