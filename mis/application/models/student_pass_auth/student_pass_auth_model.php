<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	Class Student_pass_auth_model extends CI_Model
	{
		var $table1 = 'user_details';
		var $table2 = 'password_change_count';
		var $table3 = 'users';

		function __construct()
		{
			parent::__construct();
		}


		public function getDOBbyId($id)
		{
			// $query = $this->db->select('dob')->from($this->$table)->where('id',$id)->get();
			$query = $this->db->get_where($this->table1, array('id' => $id));
			// echo $query;
			if($query->num_rows()===1)
			{
				return $query->row()->dob;
			}
			else return false;

		}

		public function getPassChangeCountById($id)
		{
			$query = $this->db->get_where($this->table2, array('id' => $id ));


			if($query->num_rows()===0)
			{
				return 0;
			}
			else if($query->num_rows()===1)
			{
				return $query->row()->count;
				echo $query->row()->count;
			}
			else
			{
				return -1;
			}
		}

		public function incrementCounterById($id)
		{
			$count = $this->getPassChangeCountById($id);
			if($count===0)
			{
				$data = array(
					'id' => $id, 
					'count' => 1
					);
				$this->db->insert($this->table2,$data);
			}
			else
			{
				$count = $count+1;
				$data = array(
					'id' => $id,
					'count' => $count
					);
				$this->db->set('count',$count);
				$this->db->where('id',$id);
				$this->db->update($this->table2);
			}
		}

		public function getAuthID($id)
		{
			$query = $this->db->get_where($this->table3,array('id' => $id ));
			if($query->num_rows()===1)
			{
				return $query->row()->auth_id;
			}
			else return false;
		}


	}

?>