


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Courses extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function value()
	{
		$course_object=$this->db->get("cs_courses");

			$course_list=array();
			
			foreach($course_object->result() as $row)
			{
					if(in_array($row->id,$course_list))
						continue;
					$course_list[] = $row->id ;
			}
			//print_r($course_list);
			return $course_list;
	}
}
	
?>
