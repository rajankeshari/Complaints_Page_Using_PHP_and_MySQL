<?php
class Defaulter_sheet_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_session_year()
	{
		$emp_id=$this->session->userdata('id');
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT session_year
FROM subject_mapping_des AS A
INNER JOIN subject_mapping AS B ON A.map_id = B.map_id where B.session_year<>'' and B.session_year<>0
ORDER BY session_year;");
		return $query->result();
	}
	public function get_subjects($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type!='Practical';");

		$result= $query->result();
		//print_r($result);
		return $result;
	
	}

	public function get_prac_subjects($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	

			$query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and type='Practical';");

		$result= $query->result();
		//print_r($result);
		return $result;
	
	}

	public function get_branch($data,$subject)
	{
		$emp_id=$data['emp_id'];
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT branch_id
								FROM sub_mapping 
								INNER JOIN (SELECT map_id 
											FROM sub_mapping_des
											WHERE teacher_id = '$emp_id' AND subject_id='$subject') AS t
								ON t.map_id = sub_mapping.map_id ;");
		$branch_id=array();
		return $query->result();
	}

	public function get_branch_name($branch_id)
	{
		$branch_id_arr=array();
		for($i=0;$i<count($branch_id);$i++)
			$branch_id_arr[$i]=$branch_id[$i]->branch_id;
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id_arr);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_branch_name_again($branch_id)
	{
		$this->load->database();
		$this->db->select('name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id);
		$query=$this->db->get();
		return $query->result();
	}
}
?>