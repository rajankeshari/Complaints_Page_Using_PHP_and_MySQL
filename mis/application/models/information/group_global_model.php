<?php

/**
 * Author: Vivek Kumar
* Email: vivek0739@users.noreply.github.com
* Date: 20 june 2015
*/
class Group_global_model extends CI_Model
{
   var $table_depts = 'departments';
	var $table_course = 'courses';
	var $table_groups='info_group_global';
	var $table_group='user_details';
    function __construct()
    {
        parent::__construct();
    }
    function put_group_class($data1,$data2,$data3,$data4)
	{		
		//for inserting member in group not individual
			$query2=$this->db->query("SELECT * from info_group_global where group_id='$data1' and user_id='$data3'");
		
			if($query2->num_rows()==0)
			{
				$query = "INSERT INTO info_group_global VALUES ('$data1','$data3','$data4')";
				$this->db->query($query);
			}
			
	}
	function get_group_member($group_id,$user_id)
	{

		$this->db->where('group_id',$group_id);
		$query =$this->db->select('user_id,user_details.first_name as first_name,
			user_details.middle_name as middle_name,user_details.last_name as last_name')
			->from($this->table_groups)
			->join("user_details", $this->table_groups.".user_id = user_details.id")
			->get();

		
		return $query->result();
	}
	function get_groups()
	{
		$query = $this->db->get($this->table_groups);
		
		return $query->result();
	}
	public function del_group($group_name,$id_user)
	{
		$query="DELETE FROM info_group_global WHERE group_id='$group_name'";
		$this->db->query($query);
		
	}
	function del_group_class($data1,$data2,$data3,$data4)
	{		
			
				$query = "DELETE FROM info_group_global 
				where  group_id='$data1' 
				 and user_id='$data3' ";
				$this->db->query($query);
	} 
}