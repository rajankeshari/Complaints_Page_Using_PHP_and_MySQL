


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Department_branch_list extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function check()
	{
		$newar=array();
		$db=$this->load->database();
		//echo $this->input->post('semster') ."<br>";
		$result=$this->db->get('dept_course')->result();

		for($i=0;$i<count($result);$i++)
		{
			$dept=$result[$i]->{'dept_id'};
			$id=$result[$i]->{'course_branch_id'};
			
				$query="SELECT * FROM course_branch WHERE course_branch_id= '".$id."' ";
				$resultfromtable=$this->db->query($query)->result();
				//print_r($resultfromtable);
				if(count($resultfromtable))
				{
					$newar[$dept][$resultfromtable[0]->course_id][]=$resultfromtable[0]->branch_id;
				}
			

		}


		return $newar;
	}
}
	
?>
