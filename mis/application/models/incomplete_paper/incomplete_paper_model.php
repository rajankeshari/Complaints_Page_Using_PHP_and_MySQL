<?php


class Incomplete_paper_model extends CI_Model{

	public function start()
	{
		$db=$this->load->database();
		$query="SELECT * FROM resultdata WHERE passfail='F' ";
		$result=$this->db->query($query)->result();
		foreach ($result as $row) {
			$str=$row->{'reamarks'};


			echo $str." ".substr_count($str, "(");echo "<br>";
			# code...
		}
	}

	public function updation()
	{
		$db=$this->load->database();

		$queryall="SELECT form_id FROM reg_summer_form ";

		$result=$this->db->query($queryall)->result();

		foreach ($result as $row) {

			$form_id= $row->{'form_id'};

			$queryforsubject="SELECT * FROM reg_summer_subject WHERE form_id=$form_id";
			$resultofsubject=$this->db->query($queryforsubject)->result();
			
			$x=count($resultofsubject);
			if($x>2)
			$query="UPDATE reg_summer_form SET incomplete_paper='More than 2' WHERE form_id=$form_id";
			else
			$query="UPDATE reg_summer_form SET incomplete_paper='Less than 2' WHERE form_id=$form_id";
			$this->db->query($query);
			echo count($resultofsubject);
			# code...
		}
		
	}
}
?>