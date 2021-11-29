


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Disperancies_summer_form extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		
	public function check()
	{


		if($this->input->post('session_year'))
		{
			$this->db->where('reg_summer_form.session',$this->input->post('session_year'));
		}

		if($this->input->post('course'))
		{
			$this->db->where('reg_summer_form.course_id',$this->input->post('course'));
		}
		if($this->input->post('branch_id'))
		{
			$this->db->where('reg_summer_form.branch_id',$this->input->post('branch_id'));
		}
		if($this->input->post('semester'))
		{
			$this->db->where('reg_summer_form.semester',$this->input->post('semester'));
		}
		if($this->input->post('admn_no'))
		{
			$this->db->where('reg_summer_form.admn_no',$this->input->post('admn_no'));
		}

			if($this->input->post('category'))
			{
				$this->db->where('user_details.category',$this->input->post('category'));
			}
			if($this->input->post('department_list'))
			{
				$this->db->where('user_details.dept_id',$this->input->post('department_list'));
			}
		$res=$this->db->get('reg_summer_form join user_details ON reg_summer_form.admn_no=user_details.id join reg_summer_fee ON reg_summer_form.admn_no=reg_summer_fee.admn_no')->result();
		
		//print_r($res);		
		$output=array();
		//this will be shown to user


		for($i=0;$i<count($res);$i++)
		{
			//print_r($res[$i]);
			//user_details database is used for finding the name, department and category of a student
			
				$query="SELECT * FROM stu_fee_database_summer WHERE incomplete_paper='".$res[$i]->{'incomplete_paper'}."' and session_year='".$res[$i]->{'session'}."'  and category= '".$res[$i]->{'category'}."' ";
				
				//echo $res[$i]->{'incomplete_paper'};
				$actualfee=$this->db->query($query)->result();

				//print_r($actualfee);
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
