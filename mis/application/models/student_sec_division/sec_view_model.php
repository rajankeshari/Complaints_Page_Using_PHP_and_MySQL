<?php

class Sec_view_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getSessionYear()
	{
		$this->db->distinct();
		$this->db->select('session_year');
		$this->db->from('section_group_rel');
	//	$this->db->order by ('session_year');
		$query = $this->db->get();
		return $query->result();
	}

	function getStudents($session_year)
	{
		$query = $this->db->query("SELECT T.admn_no, `first_name`, `middle_name`, `last_name`, T.section as sec, T.group as grp
									FROM (SELECT `admn_no`, A.section, `group` 
											FROM stu_section_data AS A 
											INNER JOIN section_group_rel as B
											ON A.section = B.section AND A.session_year = B.session_year
											WHERE A.session_year = '$session_year') AS T
									INNER JOIN  user_details
									ON T.admn_no = user_details.id");
		return $query->result();
	}
}