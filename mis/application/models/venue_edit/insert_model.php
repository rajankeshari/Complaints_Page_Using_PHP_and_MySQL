<?php

class Insert_model extends CI_Model{

	public function insert($ar)
	{
		$db=$this->load->database();
		$this->db->insert('venue',$ar);
	}

	public function check_availiablity($ar)
	{
		$db=$this->load->database();
		$query="SELECT * FROM venue WHERE dept_id='".$ar['dept_id']."' and room_no='".$ar['room_no']."' ";
		$res=$this->db->query($query)->result();
		if(count($res))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}

?>