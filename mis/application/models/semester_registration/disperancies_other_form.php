


<?php
  /*
  author - rajesh kumar sinha
  				*/

class Disperancies_other_form extends CI_Model{

	/*
		the below function computes the actual fee for a particular category
		by taking the most dominant fee (fee occuring the highest no. of times)
	*/
		public function value($str)
		{
			if($str=='General')return 0;
			else if($str=='obc')return 1;
			else if($str=='SC')return 2;
			else
			{
				return 3;
			}
		}
		public function dominent_element($feearray)
		{
			$tobereturned=0;
			$maxcount=0;
			$ans=0;
			$temp=0;
			 for($i=0;$i<count($feearray);$i++)
		 	{
				if($ans==$feearray[$i])
				{
					$temp++;
				}
				else
				{
					$temp=1;
					$ans=$feearray[$i];
				}
				if($temp>=$maxcount)
				{
					$maxcount=$temp;
					$tobereturned=$feearray[$i];
				}
			//echo $feearray[$i]."<br>";
			}
			return $tobereturned;
		}

	public function printforme($result)
	{
		for($i=0;$i<count($result);$i++)
		{
			print_r($result[$i]);echo "<br>";
		}echo "<br>";
	}
	public function find_dominant($arr)
	{
		$resultarray=array();
		$genfeearray=array();
		$obcfeearray=array();
		$scfeearray=array();
		$stfeearray=array();
		for($i=0;$i<min(count($arr),1000);$i++)
		{
			$tempadmn_no=$arr[$i]->{'admission_id'};
			//echo $tempadmn_no."<br>";
			//$this->db->get('user_details');
			$categoryresult=$this->db->query("SELECT * FROM user_details WHERE id='".$tempadmn_no."'")->result();
			$feeresult=$this->db->query("SELECT * FROM stu_other_sem_reg_fee WHERE stu_id='".$tempadmn_no."'")->result();
			if(count($categoryresult)&&count($feeresult))
			{
				if("General"==$categoryresult[0]->{'category'})
				{
					$genfeearray[]=$feeresult[0]->{'fee_amt'};
				}
				if("obc"==$categoryresult[0]->{'category'})
				{
					$obcfeearray[]=$feeresult[0]->{'fee_amt'};
				}
				if("SC"==$categoryresult[0]->{'category'})
				{
					$scfeearray[]=$feeresult[0]->{'fee_amt'};
				}
				if("ST"==$categoryresult[0]->{'category'})
				{
					$stfeearray[]=$feeresult[0]->{'fee_amt'};
				}	
			}
			
				//if(count($feearray)>500)break;
		}
		
		sort($genfeearray);
		sort($obcfeearray);
		sort($scfeearray);
		sort($stfeearray);
		/*
		print_r($genfeearray);echo "<br>";
		print_r($obcfeearray);echo "<br>";
		print_r($scfeearray);echo "<br>";
		print_r($stfeearray);echo "<br>";
		*/
		$resultarray[]=$this->dominent_element($genfeearray);
		$resultarray[]=$this->dominent_element($obcfeearray);
		$resultarray[]=$this->dominent_element($scfeearray);
		$resultarray[]=$this->dominent_element($stfeearray);
		return $resultarray;
		
		
	}
	public function check($sem,$session_year,$course,$session)
	{
		$db=$this->load->database();
		//echo $this->input->post('semster') ."<br>";

		
			$this->db->where('semster',intval($sem));
		

		
			$this->db->where('session_year',$session_year);
		
			$this->db->where('course_id',$course);
		
			$this->db->where('session',$session);


		$resultforfeecomparison=$this->db->get('stu_other_sem_reg_form')->result();
		//$this->printforme($resultforfeecomparison);

		$actualfee=$this->find_dominant($resultforfeecomparison);

		$this->db->where('semster',intval($sem));
		

		
			$this->db->where('session_year',$session_year);
		
			$this->db->where('course_id',$course);
		
			$this->db->where('session',$session);

		if($this->input->post('admn_no'))
		{
			$this->db->where('admission_id',$this->input->post('admn_no'));
		}
			
		if($this->input->post('branch_id'))
		{
			$this->db->where('branch_id',$this->input->post('branch_id'));
		}	
		
		$res=$this->db->get('stu_other_sem_reg_form')->result();
		
		// to be processed
		//$this->printforme($res);
		$output=array();
		//this will be shown to user


		for($i=0;$i<count($res);$i++)
		{
			//print_r($res[$i]);
			$var=$res[$i]->{'admission_id'};
			$query="SELECT * FROM stu_other_sem_reg_fee WHERE stu_id='".$var."'  ";
			$resfromfee=$this->db->query($query)->result();
			//stu_sem_reg_fee database gives the fee submitted by a student
			//print_r($resfromfee);
			//echo "<br>";
			$this->db->get('user_details');
			$this->db->where('id',$var);
			if($this->input->post('category'))
			{
				$this->db->where('category',$this->input->post('category'));
			}
			if($this->input->post('department_list'))
			{
				$this->db->where('dept_id',$this->input->post('department_list'));
			}
			
			$resfromuser=$this->db->get('user_details')->result();
			//print_r($resfromuser);echo "<br>";echo "<br>";
			//user_details database is used for finding the name, department and category of a student
			
			

					if(count($resfromuser)&&count($resfromfee))
					{
						$nameofperson=$resfromuser[0]->{'first_name'}." ".$resfromuser[0]->{'middle_name'}." ".$resfromuser[0]->{'last_name'};
						
						$output[]=array('id'=>$resfromuser[0]->{'id'},'name'=>$nameofperson,'category'=>$resfromuser[0]->{'category'},'branch_id'=>$res[$i]->{'branch_id'},'fee_amt'=>$resfromfee[0]->{'fee_amt'},'isvalid'=>false);
						//print_r($resoffee[$j]);		
					}
							
		}
		//$this->printforme($output);
			
		
		for($j=0;$j<count($output);$j++)
		{
			if($output[$j]['fee_amt']==$actualfee[$this->value($output[$j]['category'])])
			{
				$output[$j]['isvalid']=true;	
			}
			//print_r($output[$j]);echo "<br>";
		}
			
		return $output;

	}
}
	
?>
