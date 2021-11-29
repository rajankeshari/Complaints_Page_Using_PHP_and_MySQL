
<?php
class Exam_schedule_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function get_department()
	{
		$this->db->select('*');
		$this->db->from('departments');
		$this->db->where('type','academic');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_course_id($department)
	{
		$this->db->select('*');
		$this->db->from('course_branch');
		$this->db->like('branch_id',$department);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_course($department)
	{
		$course_id=$this->get_course_id($department);
		for($i=0;$i<count($course_id);$i++)
		{
			$tmp[$i]=$course_id[$i]->course_id;
		}
		$this->db->select('*');
		$this->db->from('cs_courses');
		$this->db->where_in('id',$tmp);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_branches_by_course_and_dept($course,$dept){
		$query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id
									FROM branches INNER JOIN course_branch 
									ON course_branch.branch_id = branches.id INNER JOIN dept_course 
									ON dept_course.course_branch_id = course_branch.course_branch_id 
									WHERE course_branch.course_id = '".$course."' AND 
									dept_course.dept_id = '".$dept."' AND 
									branches.id != 'comm' AND 
									branches.id != 'honour' AND 
									branches.id != 'minor'");
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	public function get_semester($course)
	{
		$this->db->select('duration');
		$this->db->from('cs_courses');
		$this->db->where('id',$course);
		$query=$this->db->get();
		return $query->result_array()[0];
	}
	public function get_venue()
	{
		$this->db->distinct();
		$this->db->select('dept_id');
		$this->db->from('exam_seating');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_class($venue)
	{
		$this->db->select('*');
		$this->db->from('exam_seating');
		$this->db->where('dept_id',$venue);
		$query=$this->db->get();
		return $query->result();
	}
	public function check_shift($data)
	{
		$this->db->select('*');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$data['session_year']);
		$this->db->where('session',$data['session']);
		$this->db->where('type',$data['type']);
	//	$this->db->where('section',0);
		$query=$this->db->get();
		if($query->num_rows()==0)
			return 0;
		else
		{
			$map=$query->result()[0]->map_id;
			$this->db->select('*');
			$this->db->from('exam_shift');
			$this->db->where('map_id',$map);
			$query=$this->db->get();
			if($query->num_rows()==0)
				return 0;
			else
				return $query->result();
		}
	}
	public function get_time($map,$shift)
	{
		$this->db->select('*');
		$this->db->from('exam_shift');
		$this->db->where('map_id',$map);
		$this->db->where('shift',$shift);
		$query=$this->db->get();
		return $query->result();
	}
	public function load_discipline($data)
	{
		$array=array('session_year'=>$data[0],'session'=>$data[1],'dept_id'=>$data[2]);
		$this->db->select('*');
		$this->db->from('subject_mapping');
		$this->db->where($array);
		$query=$this->db->get();
		return $query->result();
	}
	public function load_subject($data)
	{

		$array=array('session_year'=>$data['session_year'],'session'=>$data['session'],'dept_id'=>$data['dept'],
			'course_id'=>$data['course'],'branch_id'=>$data['branch'],'semester'=>$data['sem']);

		$this->db->select('map_id');
		$this->db->from('subject_mapping');
		$this->db->where($array);
		$query=$this->db->get();
		if($query->num_rows()>0)
			$map=$query->result()[0]->map_id;
		else
			return 0;


		$this->db->select('sub_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('map_id',$map);
		$query=$this->db->get();
		if($query->num_rows()>0)
			$sub_tmp = $query->result();
		else
			return 0;

		for($i=0;$i<count($sub_tmp);$i++)
			$sub_id[$i]=$sub_tmp[$i]->sub_id;

		$this->db->select('id,subject_id,name');
		$this->db->from('subjects');
		$this->db->where('type','Theory');
		$this->db->where_in('id',$sub_id);
		$query=$this->db->get();
		return $query->result();
	}
	public function insert_map($session_year,$session,$type)
	{
		$array=array(
			'map_id'=>'',
			'session_year'=>$session_year,
			'session'=>$session,
		//	'section'=>'0',
			'type'=>$type
			);
		$query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
			'type'=>$type));
		if($query->num_rows()==0)
			$this->db->insert('exam_schedule_mapping',$array);
		$query=$this->db->get_where('exam_schedule_mapping',array('session_year'=>$session_year,'session'=>$session,
									'type'=>$type));
		return $query->result()[0]->map_id;
	}
	public function insert_shift($in)
	{
		$this->db->insert('exam_shift',$in);
	}
	public function check($arr)
	{
		$query=$this->db->get_where('exam_schedule',$arr);
		return $query->num_rows();
	}
	public function insert($in)
	{
		$this->db->insert('exam_schedule',$in);
	}
	public function update($in)
	{
		$this->db->update('exam_schedule',$in,array('map_id'=>$in['map_id'],'subject_code'=>$in['subject_code']));
	}
	public function get_sub_id($sub,$sub_name)
	{
		$this->db->select('id');
		$this->db->from('subjects');
		$this->db->where('subject_id',$sub);
		$this->db->where('name',$sub_name);
		$query=$this->db->get();
		return $query->result()['0']->id;
	}
	public function show_schedule($data)
	{
		$this->db->select('map_id');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$data['session_year']);
		$this->db->where('session',$data['session']);
		$this->db->where('type',$data['type']);
		$q=$this->db->get();
		$map=$q->result()[0]->map_id;

		$this->db->select('*');
		$this->db->from('exam_schedule');
		$this->db->where('map_id',$map);
		$query=$this->db->get();
		return $query->result();
	}
	public function view_schedule($data)
	{
		$this->db->select('map_id');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$data['session_year']);
		$this->db->where('session',$data['session']);
		$this->db->where('type',$data['type']);
		$q=$this->db->get();
		$map=$q->result()[0]->map_id;
		
		$this->db->select('*');
		$this->db->from('exam_schedule');
		$this->db->where('map_id',$map);
		$this->db->where('semester',$data['sem']);
		$this->db->where('course_id',$data['course']);
		$this->db->where('branch_id',$data['branch']);
		$query=$this->db->get();
		return $query->result();		
	}
	public function check_status($data)
	{
		for($i=0;$i<count($data['discipline']);$i++){
		$this->db->select('map_id');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$data['discipline'][$i]->session_year);
		$this->db->where('session',$data['discipline'][$i]->session);
		$this->db->where('section',0);
		$this->db->where('type',$data[3]);

		$q=$this->db->get();
		$map=$q->result();
		if(count($map)==0)
			$status[$i]=0;
		else
		{
			$this->db->select('*');
			$this->db->from('exam_schedule');
			$this->db->where('map_id',$map[0]->map_id);
			$this->db->where('semester',$data['discipline'][$i]->semester);
			$this->db->where('course_id',$data['discipline'][$i]->course_id);
			$this->db->where('branch_id',$data['discipline'][$i]->branch_id);
			$query=$this->db->get();
			if($query->num_rows()==0)
				$status[$i]=0;
			else
				$status[$i]=1;	
		}
		}
		if(count($data['discipline']))
			return $status;
		else
			return 0;
	}
}
?>