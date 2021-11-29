<?php
class Post_circular_help_model extends CI_Model
{
	var $table_depts = 'departments';
	var $table_course = 'courses';
	var $table_groups='notice_group';
	var $table_group='user_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	//insert notice id and user id of user who can view this notice.
	public function insert_individual($data)
	{

		$no_of_individuals = $data['no_of_individuals'];

		for ($i = 1; $i <= $no_of_individuals; $i++)
		{
			$temp = $data['groups'][$i]['individual_name'];
			$circular_id=$data['circular_id'];
			//echo $data['groups'][$i]['individual_name']." ".$temp;
			$query = "INSERT INTO circular_individuals VALUES ('$circular_id','$temp')";
			$this->db->query($query);
		}
		return true;
	}
	//delete all the entry in this table so that edited message should not viewed by the previous
	//user.
	public function delete_individual($circular_id)
	{
		
		$query="DELETE from circular_individuals where circular_id='$circular_id'";
		$this->db->query($query);
	}
	//delete the group so that edited circular should be view by previous group 
	public function delete_group($circular_id)
	{
		
		$query="DELETE from circular_group_id where circular_id='$circular_id'";
		$this->db->query($query);
	}
	public function delete_group_global($circular_id)
	{
		
		$query="DELETE from circular_group_global_id where circular_id='$circular_id'";
		$this->db->query($query);
	}
	//delete a group of particular user as well circular associated with that group
	public function del_group($group_name,$id_user)
	{
		$query="DELETE FROM notice_group WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
		$query="DELETE FROM circular_group_id WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
	}
	//only delete the entry so that a particular circular should not view by this group
	public function del_group_notice($group_name,$created_by)
	{
		
		$query="DELETE FROM circular_group_id WHERE group_id='$group_name' and created_by='$created_by'";
		$this->db->query($query);

	}
	//retrive all the group which has been created by a particular user
	function get_groups($id_user)
	{
		$query = $this->db->get_where($this->table_groups,array('created_by'=>$id_user));
		
		return $query->result();
	}

	//get all the member of a particular group 
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
	//insert circular and group name  for sending a new circular to a particular group
	function get_group($data)
	{
		$temp = $data['grp_selection'];
		$circular_id=$data['circular_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO circular_group_id VALUES ('$circular_id','$temp','$created_by')";
		$this->db->query($query);
	}
	function get_group_global($data)
	{
		$temp = $data['grp_selection'];
		$circular_id=$data['circular_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO circular_group_global_id VALUES ('$circular_id','$temp')";
		$this->db->query($query);
	}
	//insert a user in a group if only if user is not already in group
	function put_group_class($data1,$data2,$data3,$data4)
	{		
			$query2=$this->db->query("SELECT * from notice_group where group_id='$data1' and created_by='$data2'and user_id='$data3'");
		
			if($query2->num_rows()==0)
			{
				$query = "INSERT INTO notice_group VALUES ('$data1','$data2','$data3','$data4')";
				$this->db->query($query);
			}
			
	}
	//delete a particular user from group
	function del_group_class($data1,$data2,$data3,$data4)
	{		
			
				$query = "DELETE FROM notice_group 
				where  group_id='$data1' and created_by='$data2'
				 and user_id='$data3' ";
				$this->db->query($query);
	} 
	//return 1 if there is a group of particular name by particular use.
	function search_group($data1)
	{		
			$query2=$this->db->query("SELECT * from notice_group where group_id='$data1'");
		
			return $query2->num_rows();
			
	}
	// return number of group created by particular user
	function no_of_groups($data1)
	{		
		$query2=$this->db->query("SELECT DISTINCT group_id from notice_group where created_by='$data1'");
		
			return $query2->num_rows();
			
	}
	//transfering all the data from group to individual table
	function put_group_ind($data)
	{
		$members = $data['member_id'];
		$circular_id=$data['circular_id'];
		foreach ($members as $key => $member) {
			$query = "INSERT INTO circular_individuals VALUES ('$circular_id','$member')";
			$this->db->query($query);
		
		}
	}
	//return all the user entry which will be shown in create_group view page
	function create_group()
	{
		
		$res = $this->db->query("SELECT DISTINCT user_details.id, salutation, first_name, middle_name, last_name, departments.name as dept_name
		 FROM user_details INNER JOIN departments
		  ON user_details.dept_id = departments.id  ORDER BY dept_name DESC;");
		return $res;
	}
	//delete a particular entries from general whose circular_id is given
	public function delete_general($circular_id)
	{
		
		$query="DELETE from circular_general where circular_id='$circular_id'";
		$this->db->query($query);

		$query="DELETE from circular_gen_emp where circular_id='$circular_id'";
		$this->db->query($query);
	}
	//get all the dept exist in the college
	function get_depts()
	{
		$query = $this->db->get($this->table_depts);
		return $query->result();
	}
	//all the department which applicable for the student
	function get_depts_stu()
	{
		$query = $this->db->get_where($this->table_depts,array('type' => 'academic' ));
		return $query->result();
	}
	//get all the courses that is provided by college
	function get_course()
	{
		
		
		$names = array('comm', 'honour', 'minor');
		$this->db->where_not_in('id', $names);
		$query = $this->db->get($this->table_course);
		return $query->result();
	}
	//get all the courses that is provided by a paricular department
	function get_course_by_dept($dept)
	{
		$this->db->where_not_in('id', $names);
		$query = $this->db->get($this->table_course);
		return $query->result();
	}
	//get a extra field for a course 
	function get_course_where($course)
	{
		$query = $this->db->get_where($this->table_course,array('id'=> $course));
		return $query->result();
	}
	//insert a entry in general table
	function put_general($data)
	{
		$circular_id=$data['circular_id'];
		$circular_cat = $data['cat_selection'];
		$dept_id = $data['dept_selection'];
		$auth_id = $data['emp_selection1'];
 		$course_id = $data['course_selection1'];
 		$sem= $data['sem_selection'];
 		if($circular_cat=='stu' )
 		{
 			$query = "INSERT INTO circular_general VALUES ('$circular_id','$circular_cat','$dept_id','$course_id','$sem')";
			$this->db->query($query);
 		}
 		else
 		{
 			$query = "INSERT INTO circular_gen_emp VALUES ('$circular_id','$circular_cat','$dept_id','$auth_id')";
			$this->db->query($query);
 			
 		}
		
	}
	//get auth id for a particular user such as emp,stu;
	function get_auth($id_user)
	{
		$auth_id = $this->db->select('auth_id')->where('id',$id_user)->get('users');
		$auth_cat = $auth_id->row()->auth_id;
		echo $auth_cat;
		return $auth_cat;
	}
	//get all the user of a particular department
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
	//delete a notice if incorrectly send
	function delete_notice_send_incorectly($circular_id)
	{
		echo $circular_id;
		$query="DELETE from info_circular_details where circular_id='$circular_id'";
		$this->db->query($query);
	}
}