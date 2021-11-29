<?php
	
	/**
      * Author: Raj(rajthegreat)
     */
	class Emp_det_by_id extends CI_Model{

		public function solve($emp_no)
		{
			
			$this->db->where('emp_basic_details.emp_no',$emp_no);
			
			
			$res=$this->db->get('user_details INNER JOIN emp_basic_details ON emp_basic_details.emp_no=user_details.id INNER JOIN user_other_details ON user_other_details.id=user_details.id')->result();


			return $res[0];
		}
		
	}


?>