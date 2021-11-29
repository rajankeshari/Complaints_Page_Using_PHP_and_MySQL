


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Disperancies_form extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function check()
	{
		$db=$this->load->database();
		

		/*
		echo $this->input->post('session_year') ."<br>";
		echo $this->input->post('session')."<br>";
		echo $this->input->post('course')."<br>";
		echo $this->input->post('branch_id')."<br>";
		echo $this->input->post('semester')."<br>";
		echo $this->input->post('admn_no')."<br>";
		*/
		if($this->input->post('session_year'))
		{
			$this->db->where('reg_regular_form.session_year',$this->input->post('session_year'));
		}
		if($this->input->post('session'))
		{
			$this->db->where('reg_regular_form.session',$this->input->post('session'));
		}
		if($this->input->post('course'))
		{
			$this->db->where('reg_regular_form.course_id',$this->input->post('course'));
		}
		if($this->input->post('branch_id'))
		{
			$this->db->where('reg_regular_form.branch_id',$this->input->post('branch_id'));
		}
		if($this->input->post('semester'))
		{
			$this->db->where('reg_regular_form.semester',$this->input->post('semester'));
		}
		if($this->input->post('admn_no'))
		{
			$this->db->where('reg_regular_form.admn_no',$this->input->post('admn_no'));
		}

			if($this->input->post('category'))
			{
				$this->db->where('user_details.category',$this->input->post('category'));
			}
			if($this->input->post('department_list'))
			{
				$this->db->where('user_details.dept_id',$this->input->post('department_list'));
			}
		$res=$this->db->get('reg_regular_form join user_details ON reg_regular_form.admn_no=user_details.id join reg_regular_fee ON reg_regular_form.admn_no=reg_regular_fee.admn_no')->result();
		
		//print_r($res);		
		$output=array();
		//this will be shown to user


		for($i=0;$i<count($res);$i++)
		{
			//print_r($res[$i]);
			//print_r($resfromuser);echo "<br>";echo "<br>";
			//user_details database is used for finding the name, department and category of a student
			
				$query="SELECT fee_amt FROM stu_fee_database_regular WHERE session_year='".$res[$i]->{'session_year'}."' and course_id='".$res[$i]->{'course_id'}."' and semester='".$res[$i]->{'semester'}."' and session='".$res[$i]->{'session'}."' and category= '".$res[$i]->{'category'}."' ";
				$actualfee=$this->db->query($query)->result();
				if(count($actualfee))
				{
					$compare=$actualfee[0]->{'fee_amt'};
					//print_r($compare);echo "<br>";
					
					$nameofperson=$res[$i]->{'first_name'}." ".$res[$i]->{'middle_name'}." ".$res[$i]->{'last_name'};
					if($res[$i]->{'fee_amt'}==$compare)
					{
						if(!in_array(array('id'=>$res[$i]->{'id'},'name'=>$nameofperson,'category'=>$res[$i]->{'category'},'branch_id'=>$res[$i]->{'branch_id'},'fee_amt'=>$res[$i]->{'fee_amt'},'isvalid'=>true), $output))
						$output[]=array('id'=>$res[$i]->{'id'},'name'=>$nameofperson,'category'=>$res[$i]->{'category'},'branch_id'=>$res[$i]->{'branch_id'},'fee_amt'=>$res[$i]->{'fee_amt'},'isvalid'=>true);
						
					}
					else
					{
						if(!in_array(array('id'=>$res[$i]->{'id'},'name'=>$nameofperson,'category'=>$res[$i]->{'category'},'branch_id'=>$res[$i]->{'branch_id'},'fee_amt'=>$res[$i]->{'fee_amt'},'isvalid'=>0), $output))
						$output[]=array('id'=>$res[$i]->{'id'},'name'=>$nameofperson,'category'=>$res[$i]->{'category'},'branch_id'=>$res[$i]->{'branch_id'},'fee_amt'=>$res[$i]->{'fee_amt'},'isvalid'=>0);
				
					}
				}
					
			
			
			
		}
        return $output ;
			

	}
}
	
?>
