<?php
class Show_student extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_student($data)
	{
		$sub=$data['subject'];
		$session = $data['session'];
		$this->load->database();
		if($session === 'Summer')
			$sub_table = 'reg_summer_subject';
		else
			$sub_table = 'reg_regular_elective_opted';
		$this->db->select('form_id');
		$this->db->from($sub_table);
		$this->db->where('sub_id',$sub);
		$query=$this->db->get();	
		$data['form_no'] = $query->result();
	    return $this->get_admn($data);
	}

	public function get_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['class_res'][0]->semester;
		$course=$data['course'];
		$this->load->database();
		$tmp=array();
		for($i=0;$i<count($data['form_no']);$i++)
		{
			$tmp[$i]=$data['form_no'][$i]->form_id;
		}
		if($i>0)
		{
			$form_table = 'reg_regular_form';
			if($session === 'Summer')
				$form_table = 'reg_summer_form';
			$this->db->select('admn_no');
			$this->db->from($form_table);
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
		else
		{
			$this->db->select('admn_no');
			$this->db->from('reg_regular_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
	}
        
      

	public function get_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_admn']);$i++)
		{
			$tmp[$i]=$data['stu_admn'][$i]->admn_no;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}
	}

	public function get_subject_name($data='')
	{
		$subject_id=$data['subject'];
		$this->load->database();
		$this->db->select('name');
		$this->db->from('subjects');
		$this->db->where('id',$subject_id);
		$this->db->order_by("name","asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function get_class($data)
	{
		$subject_id=$data['subject'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('sub_id',$subject_id);
		$query=$this->db->get();
		$tmp=$query->result();
		$map_id=$tmp[0]->map_id;

		$this->db->select('semester,course_id,branch_id');
		$this->db->from('subject_mapping');
		$this->db->where('map_id',$map_id);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_course($data)
	{

		$branch=$data['branch'];
		$session_year=$data['session_year'];
		$session=$data['session'];
		$semester=$data['semester'];
		
		$form_table = 'reg_regular_form';
		if($session === 'Summer')
			$form_table = 'reg_summer_form';
		$this->load->database();
		$this->db->select('course_id');
		$this->db->from($form_table);
		$this->db->where('branch_id',$branch);
		$this->db->where('semester',$semester);
		$this->db->where('session_year',$session_year);
		$this->db->where('session',$session);
		$this->db->distinct();
		$query=$this->db->get();
		$course_id=$query->result();
		return $this->get_course_name($course_id);
	}

	public function get_course_name($course_id)
	{
		$course_id_arr=array();
		for($i=0;$i<count($course_id);$i++)
			$course_id_arr[$i]=$course_id[$i]->course_id;
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('courses');
		$this->db->where_in('id',$course_id_arr);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rep_student($data)
	{
		$subject=$data['subject'];
		
		$this->load->database();
		$this->db->select('form_id');
		$this->db->from('reg_other_subject');
		$this->db->where('sub_id',$subject);
		$query=$this->db->get();	
		$data['rep_form_no'] = $query->result();
	    return $this->get_rep_admn($data);
	}

	public function get_rep_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['class_res'][0]->semester;
		$course=$data['course'];
		$this->load->database();
		$tmp=array();
		for($i=0;$i<count($data['rep_form_no']);$i++)
		{
			$tmp[$i]=$data['rep_form_no'][$i]->form_id;
		}
		if($i>0)
		{
			$this->db->select('admn_no');
			$this->db->from('reg_other_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->like('reason','repeater');
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_rep_admn'] = $query->result();
			return $this->get_rep_name($data);
		}		
	}

	public function get_rep_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_rep_admn']);$i++)
		{
			$tmp[$i]=$data['stu_rep_admn'][$i]->admission_id;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}		
	}
	public function get_course_name_from_course_id($course_id)
	{
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('courses');
		$this->db->where('id',$course_id);
		$query=$this->db->get();
		return $query->result();
	}
}
?>