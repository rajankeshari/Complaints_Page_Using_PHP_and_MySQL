<?php

class Insert_data extends CI_Model{

	public function solve($ar)
	{
		$db=$this->load->database();
		$this->db->insert("stu_fee_database_regular",$ar);
	}
	public function check_in_regular_db($ar)
	{
		$db=$this->load->database();
		
		$query="SELECT * FROM stu_fee_database_regular WHERE session_year='".$ar['session_year']."' And session='".$ar['session']."' And course_id='".$ar['course_id']."' And semester='".$ar['semester']."' And category='".$ar['category']."' ";
		$res=$this->db->query($query)->result();
		if(count($res))return 1;
		else return 0;	
	}
	public function solve_summer($ar)
	{
		$db=$this->load->database();
		$this->db->insert("stu_fee_database_summer",$ar);
	}
	public function check_in_summer_db($ar)
	{
		$db=$this->load->database();
		
		$query="SELECT * FROM stu_fee_database_summer WHERE session_year='".$ar['session_year']."' And incomplete_paper='".$ar['incomplete_paper']."' And category='".$ar['category']."' ";
		$res=$this->db->query($query)->result();
		if(count($res))return 1;
		else return 0;	
	}
}


?>