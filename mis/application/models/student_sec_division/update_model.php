<?php

class Update_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getSection()
	{
		$curr_year = date("Y");
		$curr_session = $curr_year.'-'.($curr_year+1);
		
		$query = $this->db->query("SELECT A.section as section, `group`, COUNT(*) as count
							FROM stu_section_data AS A
							INNER JOIN (SELECT * FROM section_group_rel WHERE session_year = '$curr_session') AS B
							ON A.section = B.section AND A.session_year = B.session_year
							GROUP BY section");
		return $query->result();

	}

	function sectionExist($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		$this->db->select('section');
		$this->db->from('section_group_rel');
		$this->db->where('session_year', $session_year);
		$this->db->where('section', $data);
		$query = $this->db->get();
		if($query->result())
			return $query->result();
		return false;
	}

	function studentExist($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		$this->db->select('admn_no');
		$this->db->from('stu_section_data');
		$this->db->where('session_year', $session_year);
		$this->db->where('admn_no', $data);
		$query = $this->db->get();
		if($query->result())
			return $query->result();
		return false;
	}

	function getGroup($data)
	{
		$this->db->select('group');
		$this->db->from('section_group_rel');
		$this->db->where('session_year', $data['session_year']);
		$this->db->where('section', $data['section']);
		$query = $this->db->get();
		return $query->result();
	}
	function updateSec($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		$section = $data['section'];
		$admn_no = $data['admn_no'];
		if($this->db->update('stu_section_data', array('section'=>$section), array('admn_no'=>$admn_no,'session_year'=>$session_year)))
			return true;
		return false;
	}

}