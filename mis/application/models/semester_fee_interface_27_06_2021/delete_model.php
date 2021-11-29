<?php

class Delete_model extends CI_Model{

	public function execute($key)
	{
		$db=$this->load->database();
		$this->db->where('key',$key);
		//$query="DELETE FROM 'stu_fee_database_regular' WHERE 'key' = $key";
		$this->db->delete('stu_fee_database_regular');
	}
	public function execute_summer($key)
	{
		$db=$this->load->database();
		$this->db->where('key',$key);
		$this->db->delete('stu_fee_database_summer');
	}

}


?>