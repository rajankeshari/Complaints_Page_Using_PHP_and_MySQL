<?php
class Defaulter_model extends CI_Model
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
								FROM sub_mapping_des AS A
								INNER JOIN sub_mapping AS B ON A.map_id = B.map_id 
								WHERE teacher_id = '$emp_id' 
								ORDER BY session_year;");
		return $query->result();
	}
	public function get_subjects($data='')
	{
		$emp_id=$this->session->userdata('id');
		$this->load->database();
		if($data!== '')
		{
			$session=$data['session'];
			$session_year=$data['session_year'];
			$query= $this->db->query("SELECT S.subject_id as s_id, name,newt.subject_id as n_id
							FROM (SELECT session,session_year,subject_id, semester, teacher_id, A.map_id
								FROM sub_mapping_des AS A
								INNER JOIN sub_mapping AS B ON A.map_id = B.map_id) AS newt
							INNER JOIN subjects AS S ON S.id = newt.subject_id
							WHERE newt.teacher_id =  '$emp_id' AND session='$session'
							 AND session_year='$session_year' ;");

		$result= $query->result();
		return $result;
		}
	}
}
?>