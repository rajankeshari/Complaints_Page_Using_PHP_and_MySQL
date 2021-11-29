

<?php
  /*
  author - rajesh kumar sinha
  				*/

class Load_summer_table extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function check()
	{
		$db=$this->load->database();
		
		$query=" SELECT * FROM stu_fee_database_summer ";
		$res=$this->db->query($query)->result();
			

		return $res;
	}
}
	
?>
