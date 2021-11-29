<?php

class Insert_model extends CI_Model{

	public function insert($ar)
	{
		$db=$this->load->database();
		$this->db->insert('exam_seating',$ar);
	}

	public function check_availiablity($ar)
	{
		$db=$this->load->database();
		$query="SELECT * FROM exam_seating WHERE dept_id='".$ar['dept_id']."' and class_name='".$ar['class_name']."' ";
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