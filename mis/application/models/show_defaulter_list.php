<?php
class Show_defaulter_list extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_student($data)
	{
		$sub=$data['subject'];
	//	echo $sub;
		$this->load->database();
		$this->db->select('sem_form_id');
		$this->db->from('stu_sem_reg_subject');
		$this->db->where('sub_id',$sub);
		$query=$this->db->get();	
		return $query->result();
	}
	public function get_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$tmp=array();
		for($i=0;$i<count($data['form_no']);$i++)
		{
		//	echo $data['form_no'][$i]->sem_form_id;
			$tmp[$i]=$data['form_no'][$i]->sem_form_id;
		}
		if($i>0)
		{
			$this->load->database();
			$this->db->select('admission_id');
			$this->db->from('stu_sem_reg_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where_in('sem_form_id',$tmp);
			$query=$this->db->get();
			return $query->result();
		}
		else
		{
			redirect("employee/attendance/error");	
		}
	}

	public function get_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_admn']);$i++)
		{
		//	echo $data['form_no'][$i]->sem_form_id;
			$tmp[$i]=$data['stu_admn'][$i]->admission_id;
		}
		if($i>0)
		{
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
			$this->db->where_in('id',$tmp);
			$query=$this->db->get();
			return $query->result();
		}
	}
	public function get_map_id($data)
	{
		$sub=$data['subject'];
		$t_id=$data['emp_id'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('sub_mapping_des');
		$this->db->where('subject_id',$sub);
		$this->db->where('teacher_id',$t_id);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_session_id($data)
	{
		$this->load->database();
		$session=$data['session'];
		$session_year=$data['session_year'];
		$this->db->select('session_id');
		$this->db->from('session_track');
		$this->db->where('session',$session);
		$this->db->where('session_year',$session_year);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_total_class($map,$sub,$session_id)
	{
		$this->load->database();
		$this->db->select('total');
		$this->db->from('total_class');
		$this->db->where('map_id',$map);
		$this->db->where('subject_id',$sub);
		$this->db->where('session_id',$session_id);
		$query=$this->db->get();
		return $query;
	}
	public function get_absent($admn,$map,$sub,$session_id)
	{
		$this->load->database();
		/*$this->db->select count('date');
		$this->db->from('absent_table');
		$this->db->where('addmission_id',$admn);
		$this->db->where('map_id',$map);
		$this->db->where('subject_id',$sub);
		$this->db->where('session_id',$session_id);*/
		$query=$this->db->query("select count(date) as date FROM absent_table
									WHERE map_id = $map AND subject_id = '$sub'
									AND addmission_no = '$admn' 
									AND session_id = $session_id");
		return $query->result();
	}
}
?>