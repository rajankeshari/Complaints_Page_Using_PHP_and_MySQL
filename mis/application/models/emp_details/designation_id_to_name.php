<?php

class Designation_id_to_name extends CI_Model{

	public function execute()
	{
		$db=$this->load->database();
		$res=$this->db->get('designations')->result();

		$ar = array( );
		foreach ($res as $row) {

			$ar[$row->id]=$row->name;
		}
		return $ar;
	}

}


?>