<?php
class Post_notice_help_model extends CI_Model
{
	var $table_depts = 'departments';
	var $table_course = 'courses';
	var $table_groups='notice_group';
	var $table_group='user_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	//	parent::__construct(array('hod','est_ar','exam_dr','dt','dsw','admin','da1','hos','tpo','info','adaa','adcm','adhm','adii','adis','adop','adpg','adpp','adrec','adsa','adsr','adsw','adug','dean_acad','dean_fnp','dean_iraa','dean_rnd'));

	}
	//insert notice and individual pair in notice individual
	public function insert_individual($data)
	{

		$no_of_individuals = $data['no_of_individuals'];

		for ($i = 1; $i <= $no_of_individuals; $i++)
		{
			$temp = $data['groups'][$i]['individual_name'];
			$notice_id=$data['notice_id'];
			//echo $data['groups'][$i]['individual_name']." ".$temp;
			$query = "INSERT INTO notice_individuals VALUES ('$notice_id','$temp')";
			$this->db->query($query);
		//	echo $this->db->last_query(); die();
		}
		return true;
	}
	//delete all the entry in this table so that edited message should not viewed by the previous
	//user
	public function delete_individual($notice_id)
	{
		
		$query="DELETE from notice_individuals where notice_id='$notice_id'";
		$this->db->query($query);
	}
	//delete all 
	public function delete_group($notice_id)
	{
		
		$query="DELETE from notice_group_id where notice_id='$notice_id'";
		$this->db->query($query);
	}
	public function delete_group_global($notice_id)
	{
		
		$query="DELETE from notice_group_global_id where notice_id='$notice_id'";
		$this->db->query($query);
	}
	
	public function del_group($group_name,$id_user)
	{
		$query="DELETE FROM notice_group WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
		$query="DELETE FROM notice_group_id WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
	}
	public function del_group_notice($group_name,$created_by)
	{
		
		$query="DELETE FROM notice_group_id WHERE group_id='$group_name' and created_by='$created_by'";
		$this->db->query($query);

	}
	function get_groups($auth_id)
	{
		$query = $this->db->get_where($this->table_groups,array('created_by'=>$auth_id));
		
		return $query->result();
	}
	function get_group_member($group_id,$user_id)
	{

		$this->db->where(array('group_id'=>$group_id,'created_by'=>$user_id));
		$query =$this->db->select('user_id,user_details.first_name as first_name,
			user_details.middle_name as middle_name,user_details.last_name as last_name')
			->from($this->table_groups)
			->join("user_details", $this->table_groups.".user_id = user_details.id")
			->get();

		
		return $query->result();
	}
	function get_group($data)
	{
		$temp = $data['grp_selection'];
		$notice_id=$data['notice_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO notice_group_id VALUES ('$notice_id','$temp','$created_by')";
		$this->db->query($query);
	}
	function get_group_global($data)
	{
		$temp = $data['grp_selection'];
		$notice_id=$data['notice_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO notice_group_global_id VALUES ('$notice_id','$temp')";
		$this->db->query($query);
	}
	
	function put_group_class($data1,$data2,$data3,$data4)
	{		
		//for inserting member in group not individual
			$query2=$this->db->query("SELECT * from notice_group where group_id='$data1' and created_by='$data2'and user_id='$data3'");
		
			if($query2->num_rows()==0)
			{
				$query = "INSERT INTO notice_group VALUES ('$data1','$data2','$data3','$data4')";
				$this->db->query($query);
			}
			
	}
	function del_group_class($data1,$data2,$data3,$data4)
	{		
			
				$query = "DELETE FROM notice_group 
				where  group_id='$data1' and created_by='$data2'
				 and user_id='$data3' ";
				$this->db->query($query);
	} 
	function search_group($data1)
	{		
			$query2=$this->db->query("SELECT * from notice_group where group_id='$data1'");
		
			return $query2->num_rows();
			
	}
	
	function no_of_groups($data1)
	{		
		$query2=$this->db->query("SELECT DISTINCT group_id from notice_group where created_by='$data1'");
		
			return $query2->num_rows();
			
	}
	function put_group_ind($data)
	{
		$members = $data['member_id'];
		$notice_id=$data['notice_id'];
		foreach ($members as $key => $member) {
			$query = "INSERT INTO notice_individuals VALUES ('$notice_id','$member')";
			$this->db->query($query);
		
		}
	}
	function create_group()
	{
		
		$res = $this->db->query("SELECT DISTINCT user_details.id, salutation, first_name, middle_name, last_name, departments.name as dept_name
		 FROM user_details INNER JOIN departments
		  ON user_details.dept_id = departments.id  ORDER BY dept_name DESC;");
		return $res;
	}
	
	public function delete_general($notice_id)
	{
		
		$query="DELETE from notice_general where notice_id='$notice_id'";
		$this->db->query($query);

		$query="DELETE from notice_gen_emp where notice_id='$notice_id'";
		$this->db->query($query);
	}
	function get_depts()
	{
		$query = $this->db->get($this->table_depts);
		return $query->result();
	}
	function get_depts_stu()
	{
		$query = $this->db->get_where($this->table_depts,array('type' => 'academic' ));
		return $query->result();
	}
	function get_course()
	{
		
		
		$names = array('comm', 'honour', 'minor');
		$this->db->where_not_in('id', $names);
		$query = $this->db->get($this->table_course);
		return $query->result();
	}
	function get_course_by_dept($dept)
	{
		$this->db->where_not_in('id', $names);
		$query = $this->db->get($this->table_course);
		return $query->result();
	}
	function get_course_where($course)
	{
		$query = $this->db->get_where($this->table_course,array('id'=> $course));
		return $query->result();
	}
	
	function put_general($data)
	{
		$notice_id=$data['notice_id'];
		$notice_cat = $data['cat_selection'];
		$dept_id = $data['dept_selection'];
		$auth_id = $data['emp_selection1'];
 		$course_id = $data['course_selection1'];
 		$sem= $data['sem_selection'];
 		if($notice_cat=='stu' )
 		{
 			$query = "INSERT INTO notice_general VALUES ('$notice_id','$notice_cat','$dept_id','$course_id','$sem')";
			$this->db->query($query);
 		}
 		else
 		{
 			$query = "INSERT INTO notice_gen_emp VALUES ('$notice_id','$notice_cat','$dept_id','$auth_id')";
			$this->db->query($query);
 			
 		}
		
	}
	function get_auth($id_user)
	{
		$auth_id = $this->db->select('auth_id')->where('id',$id_user)->get('users');
		$auth_cat = $auth_id->row()->auth_id;
		
		return $auth_cat;
	}
	
	function getUsersByDeptAuth($dept = 'all',$auth = 'all')
	{
		$query = $this->db->select('user_details.id, salutation, first_name, middle_name, last_name, departments.name as dept_name')
							->from('user_details')
							->join('departments','user_details.dept_id = departments.id');

		if($auth != 'all')	$query = $this->db->join('user_auth_types','user_details.id = user_auth_types.id')
												->where('user_auth_types.auth_id',$auth);
		if($dept != 'all')	$query = $this->db->where('user_details.dept_id',$dept);

		return $query->get()->result();
	}
	function delete_notice_send_incorectly($notice_id)
	{
		echo $notice_id;
		$query="DELETE from info_notice_details where notice_id='$notice_id'";
		$this->db->query($query);
	}
}