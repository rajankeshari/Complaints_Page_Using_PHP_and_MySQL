<?php
	
	/**
      * Author: Rajesh kumar sinha
     */
	class Emp_detail_model extends CI_Model{

		public function solve()
		{
			/*
			echo $this->input->post('department')."<br>";

			echo $this->input->post('faculty')."<br>";
			echo $this->input->post('gender')."<br>";
			echo $this->input->post('category')."<br>";
			echo $this->input->post('joined_after')."<br>";
			echo $this->input->post('retired_before')."<br>";	
			*/

			//$this->db->select('user_details.first_name');
			if($this->input->post('department'))
			{
				$this->db->where('user_details.dept_id',$this->input->post('department'));
			}
			if($this->input->post('faculty'))
			{
				$this->db->where('emp_basic_details.auth_id',$this->input->post('faculty'));
			}
			if($this->input->post('gender'))
			{
				$this->db->where('user_details.sex',$this->input->post('gender'));
			}
			if($this->input->post('category'))
			{
				$this->db->where('user_details.category',$this->input->post('category'));
			}
			
			
			if($this->input->post('joined_after'))
			{
				$datestart=$this->input->post('joined_after')."-01-01";
				//echo $datestart."<br>";
				$this->db->where("emp_basic_details.joining_date >= '$datestart' ");
			}

			
			if($this->input->post('retired_before'))
			{
				$dateend=$this->input->post('retired_before')."-12-31";
				//echo $dateend."<br>";
				$this->db->where("emp_basic_details.retirement_date <= '$dateend' " );
			}


			
			$res=$this->db->get('user_details INNER JOIN emp_basic_details ON emp_basic_details.emp_no=user_details.id INNER JOIN user_other_details ON user_other_details.id=user_details.id')->result();


			return $res;
		}
		
	}


?>