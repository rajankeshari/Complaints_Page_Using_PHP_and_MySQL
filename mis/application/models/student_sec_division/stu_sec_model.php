<?php

class Stu_sec_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getStatus()
	{
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$this->db->select('status');
		$this->db->from('stu_section_status');
		$this->db->where('session_year', $session_year);
		$this->db->where('statustype', 'confirm');
		$query = $this->db->get();
		if(!$query->result())
			return false;
		else
			return $query->result();
	}

	function getPreparatory()
	{
		$curr_year = date("Y");
		$session_year = ($curr_year-1).'-'.($curr_year);
		$this->db->select('admn_no');
		$this->db->from('stu_prep_data');
		$this->db->where('session_year', $session_year);

		return $result = $this->db->get()->result();

	}

	function pending_insert($data)
	{
		if($this->db->insert('stu_pending_section',$data))
			return TRUE;
		else
			return FALSE;
	}
	
	function getPendingStudent($dept = 'all',$auth = 'all')
	{
		$query = $this->db->select('stu_pending_section.admn_no, first_name, middle_name, last_name, section')
							->from('user_details')
							->join('stu_pending_section','user_details.id = stu_pending_section.admn_no', 'inner');
		return $query->get()->result();
	}


	function getNewRegistered()
	{
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$query = $this->db->query("SELECT DISTINCT admn_no FROM stu_academic WHERE enrollment_year='$curr_year' 
									AND semester = 1 AND admn_based_on = 'iitjee'");
		return $query->result();
	}

	
	function getSection()
	{
		$this->db->distinct();
		$this->db->select('section');
		$this->db->from('stu_pending_section');
		$this->db->order_by('section', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function updatePending($data,$id)
	{
		$this->db->update('stu_pending_section',$data,array('admn_no'=>$id));
	}


	function confirm_insert($data)
	{
		if($this->db->insert('stu_section_data',$data))
			return TRUE;
		else
			return FALSE;
	}

	function deletePendingDetails()
	{
		$this->db->truncate('stu_pending_section');
	}

	function setPendingGroup($data)
	{
		$this->db->insert('pending_section_group_rel', $data);
	}

	function getPendingGroup()
	{
		$query = $this->db->get('pending_section_group_rel');
		return $query->result();
	}
	
	function deletePendingGroup()
	{
		$this->db->truncate('pending_section_group_rel');
	}

	function updatePendingGroup($group, $section, $session_year)
	{
		$data = array('group'=>$group);
		$this->db->where('section', $section);
		$this->db->where('session_year', $session_year);
		$this->db->update('pending_section_group_rel', $data);		
	}

	function setPreparatory()
	{
		$curr_year = date("Y");
		$curr_session_year = ($curr_year).'-'.($curr_year+1);
		$this->db->select('admn_no');
		$this->db->from('stu_academic');
		$this->db->where('enrollment_year', $curr_year);
		$this->db->where('semester', -1);
		$result = $this->db->get()->result();
		foreach($result as $row)
		{
			$data = array('admn_no'=>$row->admn_no, 'session_year'=>$curr_session_year);
			$this->db->insert('stu_prep_data', $data);
		}
	}

	function setConfirmSection()
	{
		$data = $this->db->get('stu_pending_section')->result(); 
    	foreach($data as $row) 
    	{ 
        	$this->db->insert('stu_section_data', $row); 
   	 	}
   	 	$this->deletePendingDetails();
	}

	function setConfirmGroup()
	{
		$data = $this->db->get('pending_section_group_rel')->result(); 
    	foreach($data as $row) 
    	{ 
        	$this->db->insert('section_group_rel', $row); 
   	 	}
   	 	$this->deletePendingGroup();
	}

	function setGroupInRegistration()
	{
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$query = $this->db->query("SELECT `admn_no`, A.section, `group` 
											FROM stu_section_data AS A 
											INNER JOIN section_group_rel as B
											ON A.section = B.section AND A.session_year = B.session_year
											WHERE A.session_year = '$session_year'");
		$result = $query->result();
		foreach($result as $row)
		{
			$query = $this->db->query("UPDATE reg_regular_form SET section = $row->group WHERE admn_no = '".$row->admn_no."' AND semester = '1'");
		}

	}

	function setStatus()
	{
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$data = array('session_year'=>$session_year, 'statustype'=>'confirm', 'status'=>1);
		$this->db->insert('stu_section_status', $data);
	}

	function getConfirmDetails($session_year)
	{
		$query = $this->db->query("SELECT T.admn_no, `first_name`, `middle_name`, `last_name`, T.section as sec, T.group as grp
									FROM (SELECT `admn_no`, A.section, `group` 
											FROM stu_section_data AS A 
											INNER JOIN section_group_rel as B
											ON A.section = B.section AND A.session_year = B.session_year
											WHERE A.session_year = '$session_year') AS T
									INNER JOIN  user_details
									ON T.admn_no = user_details.id ORDER BY T.admn_no");
		return $query->result();
	}

	function getName($admn_no)
	{
		$this->db->select('first_name, middle_name, last_name');
		$this->db->from('user_details');
		$this->db->where('id', $admn_no);
		$query = $this->db->get();
		return $query->result();
	}
}

