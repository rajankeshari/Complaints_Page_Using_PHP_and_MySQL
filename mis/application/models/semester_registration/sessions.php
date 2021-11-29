


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Sessions extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function value()
	{
		$session_year_list_object=$this->db->get("reg_regular_form");

			$session_year_list=array();
			
			foreach($session_year_list_object->result() as $row)
			{
					if(in_array($row->session_year,$session_year_list))
						continue;
					$session_year_list[] = $row->session_year ;
			}
			//print_r($session_year_list);
			return $session_year_list;
	}
}
	
?>
