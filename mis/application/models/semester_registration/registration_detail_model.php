<?php
	/**
      * Author: Raj (rajthegreat80)
     */


	/*
   				GLOBAL VARIABLE :

   				department
   				course
				semester
				category
				admn_no
	*/

	class Registration_detail_model extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}

   		public function check()
		{
			$newar=array();
			$db=$this->load->database();
			//echo $this->input->post('semester') ."<br>";
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
			$branch_list=array();
			foreach($newar[$this->input->post('department')] as $key => $value) 
			{
				
				foreach($value as $x)
					$branch_list[$x]=1;
			}



			return $branch_list;
		}

   		public function result()
   		{
   			if($this->input->post('department'))
            {	
            	if($this->input->post('branch'))
            		$this->db->where('branch_id',$this->input->post('branch'));
            	else
            	{
            		$string="( ";
            		$branch=$this->check();
            		$i=0;
            		foreach ($branch as $key => $value) 
            		{
            		 
            				if($i==count($branch)-1)
            				{
            					$string.=" branch_id = '$key' ";
            				}
            				else
            				{
            					$string.=" branch_id = '$key' OR ";
            				}
            				$i++;

            		}
            		$string.=" ) ";
					$this->db->where($string);
 



            	}
            }
   			
			if($this->input->post('admn_no'))
            	$this->db->where('admn_no',strtolower($this->input->post('admn_no')));
        	
            if($this->input->post('session_year'))
            	$this->db->where('session_year',$this->input->post('session_year'));

            if($this->input->post('session'))
            	$this->db->where('session',$this->input->post('session'));

        	 // if($this->input->post('branch'))
         	//     	$this->db->where('branch_id',$this->input->post('branch'));


            
        	

        	if($this->input->post('course'))
            	$this->db->where('course_id',$this->input->post('course'));

        	if($this->input->post('semester'))
            	$this->db->where('semester',$this->input->post('semester'));
			
			
				if($this->input->post('sem_type')=='Regular')
					$res=$this->db->get('reg_regular_form');
				else if($this->input->post('sem_type')=='Summer')
					$res=$this->db->get('reg_summer_form');
				else if($this->input->post('sem_type')=='Other')
					$res=$this->db->get('reg_other_form');
				else
					$res=$this->db->get('reg_regular_form');


			$res=$res->result();
			$name=array();

		
			foreach ($res as $row) 
			{
				$this->db->where('id',$row->admn_no);
				
				$x=$this->db->get('user_details');

				$x=$x->result();
				if(count($x))
					$name[]=($x[0]->first_name)." ".($x[0]->middle_name)." ".($x[0]->last_name);
			}					
				
			$data=array();
			$data["name"]=$name;
			$data["list"]=$res;			
			
			return $data;			

   		}
   	}			