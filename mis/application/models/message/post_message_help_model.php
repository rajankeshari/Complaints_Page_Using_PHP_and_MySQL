<?php
class Post_message_help_model extends CI_Model
{
	var $table_depts = 'departments';
	var $table_course = 'courses';
	var $table_groups='message_group';
	var $table_group='user_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	//insert message and individual pair in message individual
	public function insert_individual($data)
	{

		$no_of_individuals = $data['no_of_individuals'];

		for ($i = 1; $i <= $no_of_individuals; $i++)
		{
			$temp = $data['groups'][$i]['individual_name'];
			$message_id=$data['message_id'];
			//echo $data['groups'][$i]['individual_name']." ".$temp;
			$query = "INSERT INTO message_individuals VALUES ('$message_id','$temp')";
			$this->db->query($query);
		}
		return true;
	}
	//delete all the entry in this table so that edited message should not viewed by the previous
	//user
	public function delete_individual($message_id)
	{
		
		$query="DELETE from message_individuals where message_id='$message_id'";
		$this->db->query($query);
	}
	//delete all 
	public function delete_group($message_id)
	{
		
		$query="DELETE from message_group_id where message_id='$message_id'";
		$this->db->query($query);
	}
	public function delete_group_global($message_id)
	{
		
		$query="DELETE from message_group_global_id where message_id='$message_id'";
		$this->db->query($query);
	}
	
	public function del_group($group_name,$id_user)
	{
		$query="DELETE FROM message_group WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
		$query="DELETE FROM message_group_id WHERE group_id='$group_name'and created_by='$id_user'";
		$this->db->query($query);
	}
	public function del_group_message($group_name,$created_by)
	{
		
		$query="DELETE FROM message_group_id WHERE group_id='$group_name' and created_by='$created_by'";
		$this->db->query($query);

	}
	function get_groups($user_id)
	{
		$query = $this->db->get_where($this->table_groups,array('created_by'=>$user_id));
		
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
		$message_id=$data['message_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO message_group_id VALUES ('$message_id','$temp','$created_by')";
		$this->db->query($query);
	}
	function get_group_global($data)
	{
		$temp = $data['grp_selection'];
		$message_id=$data['message_id'];
		$created_by=$data['id_user'];
		$query = "INSERT INTO message_group_global_id VALUES ('$message_id','$temp')";
		$this->db->query($query);
	}
	
	function put_group_class($data1,$data2,$data3,$data4)
	{		
		//for inserting member in group not individual
			$query2=$this->db->query("SELECT * from message_group where group_id='$data1' and created_by='$data2'and user_id='$data3'");
		
			if($query2->num_rows()==0)
			{
				$this->db->insert('message_group',array('group_id'=>$data1,'created_by'=>$data2,'user_id'=>$data3,'date_of_creation'=>$data4));
			}
			
	}
	function del_group_class($data1,$data2,$data3,$data4)
	{		
			
				$query = "DELETE FROM message_group 
				where  group_id='$data1' and created_by='$data2'
				 and user_id='$data3' ";
				$this->db->query($query);
	} 
	function search_group($data1)
	{		
			$query2=$this->db->query("SELECT * from message_group where group_id='$data1'");
		
			return $query2->num_rows();
			
	}
	
	function no_of_groups($data1)
	{		
		$query2=$this->db->query("SELECT DISTINCT group_id from message_group where created_by='$data1'");
		
			return $query2->num_rows();
			
	}
	function put_group_ind($data)
	{
		$members = $data['member_id'];
		$message_id=$data['message_id'];
		foreach ($members as $key => $member) {
			$query = "INSERT INTO message_individuals VALUES ('$message_id','$member')";
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
	
	public function delete_general($message_id)
	{
		
		$query="DELETE from message_general where message_id='$message_id'";
		$this->db->query($query);

		$query="DELETE from message_gen_emp where message_id='$message_id'";
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
		$message_id=$data['message_id'];
		$message_cat = $data['cat_selection'];
		$dept_id = $data['dept_selection'];
		$auth_id = $data['emp_selection1'];
 		$course_id = $data['course_selection1'];
 		$sem= $data['sem_selection'];
 		if($message_cat=='stu' )
 		{
 			$query = "INSERT INTO message_general VALUES ('$message_id','$message_cat','$dept_id','$course_id','$sem')";
			$this->db->query($query);
 		}
 		else
 		{
 			$query = "INSERT INTO message_gen_emp VALUES ('$message_id','$message_cat','$dept_id','$auth_id')";
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
	function delete_message_send_incorectly($message_id)
	{
		echo $message_id;
		$query="DELETE from info_message_details where message_id='$message_id'";
		$this->db->query($query);
	}

	public function get_message_by_id($user_id='')
	{
		if(!empty($user_id))
		{
			$result=$this->db->where(array('issued_by'=>$user_id))->get('message_details');

			if($result)
			{	//print_r($this->db->last_query());
				return($result->result_array());
			}
		}
		return array();
	}

	public function receiver_individual($msg_id='')
	{
		if(!empty($msg_id))
		{
			$result=$this->db->select('I.user_id,U.first_name,U.last_name,U.middle_name')->from('message_individuals as I')->where(array('I.message_id'=>$msg_id))->join('user_details as U','U.id=I.user_id','left')->get();

			if($result)
			{	//print_r($this->db->last_query());
				return($result->result_array());
			}
		}
		return array();
	}

	public function receiver_group($msg_id)
	{
		if(!empty($msg_id))
		{
		       $result=$this->db->select('G.group_id,U.id as user_id,U.first_name,U.last_name,U.middle_name')
							->from('message_group_id as G')->where(array('G.message_id'=>$msg_id))
							->join('message_group as I','I.group_id=G.group_id','left')
							->join('user_details as U','U.id=I.user_id','left')
							->get();

			if($result)
			{	//print_r($this->db->last_query());
				return($result->result_array());
			}
		}
		return array();
	}

	public function receiver_global_group($msg_id)
	{
		if(!empty($msg_id))
		{
		       $result=$this->db->select('G.group_id,U.id as user_id,U.first_name,U.last_name,U.middle_name')
							->from('message_group_global_id as G')->where(array('G.message_id'=>$msg_id))
							->join('message_group_global as I','I.group_id=G.group_id','left')
							->join('user_details as U','U.id=I.user_id','left')
							->get();

			if($result)
			{	//print_r($this->db->last_query());
				return($result->result_array());
			}
		}
		return array();
	}
	

	public function receiver_general($table='' ,$msg_id='')
	{
		if(!empty($msg_id)&&!empty($table))
		{
		    $result=$this->db->where(array('message_id'=>$msg_id))->get($table);

			if($result)
			{	//print_r($this->db->last_query());
				return($result->result_array());
			}
		}
		return array();
	}

	public function getAllEmployees()
	{
		$query = $this->db->select('E.emp_no,U.salutation,U.first_name,U.last_name,U.middle_name')
						  ->from('emp_basic_details as E')
						  ->join('user_details as U', 'E.emp_no=U.id','inner')
						  ->where('E.emp_no NOT IN (select M.user_id from message_assigned as M where M.status in (1,2) )')
						  ->order_by('U.first_name')->get();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}


	public function get_active_users()
	{
		    $result=$this->db->select('M.assign_id,M.user_id,M.assigned_by,M.assigned_date,M.total_quota,M.used,U.first_name,U.last_name,U.middle_name')
		    				 ->from('message_assigned as M')
		    				 ->where(array('M.status'=>1))
		    				 ->join('user_details as U', 'U.id=M.user_id')
		    				 ->get();

			if($result)
			{	
				return($result->result());
			}
			return array();
	}


	public function get_inactive_users()
	{
		    $result=$this->db->select('M.assign_id,M.user_id,M.assigned_by,M.assigned_date,M.total_quota,M.used,U.first_name,U.last_name,U.middle_name')
		    				 ->from('message_assigned as M')
		    				 ->where(array('M.status'=>2))
		    				 ->join('user_details as U', 'U.id=M.user_id')
		    				 ->get();

			if($result)
			{	
				return($result->result());
			}
			return array();
	}


	public function get_archived_users()
	{
		    $result=$this->db->select('M.assign_id,M.user_id,M.assigned_by,M.assigned_date,M.total_quota,M.used,U.first_name,U.last_name,U.middle_name')
		    				 ->from('message_assigned as M')
		    				 ->where(array('M.status'=>3))
		    				 ->join('user_details as U', 'U.id=M.user_id')
		    				 ->get();

			if($result)
			{	
				return($result->result());
			}
			return array();
	}

	public function get_assigned_quota($id)
	{
		if(!empty($id))
		{
			$result=$this->db->where(array('assign_id'=>$id))->get('message_assign_history');
			if($result)
			{	
				return($result->result());
			}
		}
		return array();
	}

	public function get_user_message_quota($id='')
	{
		if(!empty($id))
		{
			$result=$this->db->where(array('user_id'=>$id))->get('message_assigned');
			if($result)
			{	
				return($result->result()[0]);
			}
		}
		return array();
	}
}