<?php

class Insert_model extends CI_Model
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
			return true;
		return false;
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
			return true;
		return false;
	}

	function secGrpRelExist($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		$this->db->select('section');
		$this->db->from('section_group_rel');
		$this->db->where('session_year', $session_year);
		$this->db->where('section', $data['section']);
		$this->db->where('group', $data['group']);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		if($query->result())
			return true;
		return false;
	}


	function insertStuData($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		$studetails = array('admn_no'=>$data['admn_no'], 'section'=>$data['section'], 'session_year'=>$session_year);
		$this->db->insert('stu_section_data', $studetails);
	}

	function insertSecGrpRel($data)
	{
		$curr_year = date('Y');
		$session_year = $curr_year.'-'.($curr_year+1);
		
		$secgrprel =  array('section'=>$data['section'], 'group'=>$data['group'], 'session_year'=>$session_year);
		$this->db->insert('section_group_rel', $secgrprel);
	}

}