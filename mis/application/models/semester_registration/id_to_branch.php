


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Id_to_branch extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function branch_name()
	{
		$newar=array();
		$db=$this->load->database();
		//echo $this->input->post('semster') ."<br>";
		$result=$this->db->get('cs_branches')->result();

		for($i=0;$i<count($result);$i++)
		{
			$branch_id=$result[$i]->{'id'};
			$name=$result[$i]->{'name'};
			
				$newar[$branch_id]=$name;
			

		}

		return $newar;	
		//print_r($newar);
	}
}
	
?>
