


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Id_to_duration extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function check()
	{
		$newar=array();
		$db=$this->load->database();
		//echo $this->input->post('semster') ."<br>";
		$result=$this->db->get('courses')->result();

		for($i=0;$i<count($result);$i++)
		{
			$id=$result[$i]->{'id'};
			$duration=$result[$i]->{'duration'};
			$newar[$id]=$duration;
		}

		//print_r($newar);
		return $newar;
	}
}
	
?>
