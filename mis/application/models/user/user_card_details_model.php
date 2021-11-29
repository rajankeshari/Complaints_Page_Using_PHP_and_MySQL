<?php

class User_card_details_model extends CI_Model
{

	var $table = 'user_card_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}

	function getCardById($id)
	{
		if($id != '')
		{
			$query = $this->db->where('id',$id)->get($this->table);
			if($query->num_rows() === 1)
				return $query->row();
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}
}
