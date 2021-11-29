<?php
class Dsw_attendance_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function courses()
	{
		$this->load->database();
		$result1 = $this->db->query("SELECT id FROM courses ");
		$result1 = $result1->result();
		$result = array();
		foreach ($result1 as $row) 
		{
			$result[$row->id] = 0;
		}
		return $result;
	}
	public function depart()
	{
		$this->load->database();
		$result = $this->db->query("SELECT * FROM departments WHERE type = 'academic' ");
		$result = $result->result();
		return $result;
	}

	public function get_branches($data)
	{
		$this->load->database();
		if($data['depart_id'] == "All")
		{
			$result = $this->db->query("SELECT DISTINCT C.map_id ,C.semester
									FROM course_branch as A
									INNER JOIN dept_course as B
									ON A.course_branch_id = B.course_branch_id
									INNER JOIN subject_mapping as C
									ON C.branch_id = A.branch_id
									AND C.course_id = A.course_id 
									WHERE C.session_year = '$data[session_year]'
									AND C.session = '$data[session]'
									ORDER BY C.course_id , C.branch_id ,C.semester");
			$result = $result->result();
		}
		else if($data[depart_id] == 'comm'){
			
			$result = $this->db->query("SELECT DISTINCT C.map_id ,C.semester
										FROM course_branch as A
										INNER JOIN dept_course as B
										ON A.course_branch_id = B.course_branch_id
										INNER JOIN subject_mapping as C
										ON C.branch_id = A.branch_id
										AND C.course_id = A.course_id 
										WHERE B.dept_id = '$data[depart_id]'
										AND C.session_year = '$data[session_year]'
										AND C.session = '$data[session]'
										ORDER BY C.course_id , C.branch_id ,C.semester");
			$result = $result->result();
			
		}
		else
		{
			$result = $this->db->query("SELECT DISTINCT C.map_id ,C.semester
										FROM course_branch as A
										INNER JOIN dept_course as B
										ON A.course_branch_id = B.course_branch_id
										INNER JOIN subject_mapping as C
										ON C.branch_id = A.branch_id
										AND C.course_id = A.course_id 
										WHERE B.dept_id = '$data[depart_id]'
										and C.dept_id<>'comm'
										AND C.session_year = '$data[session_year]'
										AND C.session = '$data[session]'
										ORDER BY C.course_id , C.branch_id ,C.semester");
			$result = $result->result();
		}
		return $result;
	}

	public function getStudents($data)
	{

		$session_year = $data['session_year'];
		$session = $data['session'];
		$depart = $data['depart_id'];

		$this->load->database();
		
		$result = array();
		$i = 0;
		foreach ($data['map_id'] as $row) 
		{
			$result1 = $this->db->query("SELECT distinct admn_no as admission_id , sub_id as subject_id ,map_id ,status
									 	 FROM absent_table 
										 WHERE  map_id = '$row->map_id'
										 AND status != 0
										 ORDER BY admission_id ");

			($result1 = $result1->result()  );
			//print_r($result1);
			if(count($result1))
			{	
				foreach ($result1 as $col) 
				{
					array_push($result,$col );
				}
				
			}
		} 
				
		foreach ($result as $row) 
		{
			$query = $this->db->query("SELECT name as sub_name , subject_id as sub_code  
									   FROM subjects
									   WHERE id = '$row->subject_id ' ");
			($query = $query->result()) ;
			$row->sub_name = $query[0]->sub_name;
			$row->sub_code = $query[0]->sub_code;
		}
		//print_r($result);
		//echo '<br>' ;
		foreach ($result as $row) 
		{
			$query = $this->db->query("SELECT course_id , branch_id , semester as semster  
									   FROM reg_regular_form
									   WHERE admn_no = '$row->admission_id' order by form_id desc ");
			($query = $query->result()) ;
			$row->course_id = $query[0]->course_id;
			$row->course_id = $query[0]->course_id;
			$row->semester = $query[0]->semster;
		}
		//print_r($result);
		
		foreach ($result as $row) 
		{
			$temp = $this->db->query("SELECT total_class
							FROM total_class_table
							WHERE map_id = $row->map_id 
							AND sub_id = '$row->subject_id' ");
			$temp = $temp->result();
			$row->total_class = $temp[0]->total_class;
			//print_r($row);
			$query=$this->db->query("SELECT count(date) as date FROM absent_table
									 WHERE map_id = $row->map_id AND sub_id = '$row->subject_id'
									 AND admn_no = '$row->admission_id' 
									 AND Remark='none'
									 AND status != 0 ");
			//echo "bhagat";
			($temp_1 = $query->result()) ;
			//print_r($temp_1);
						$res=$this->getModFromtech($row->subject_id,$row->admission_id);
						
			$percent = (($temp[0]->total_class - $temp_1[0]->date)+($res->count)) *100;
			$percent = (float)(($percent/$temp[0]->total_class));

			$percent = round($percent,2);
			$row->percent = $percent;
		}
		//print_r($result);
		
		
		foreach ($result as $row) 
		{
			$temp1 = $this->db->query("SELECT first_name , middle_name , last_name
									   FROM user_details
									   WHERE id = '$row->admission_id'  ");
							
			//$row->total_class = $temp[0]->total_class;

			
			($temp1 = $temp1->result()) ;
			$row->stu_name = $temp1[0]->first_name.' '.$temp1[0]->middle_name.' '.$temp1[0]->last_name;

	
		 }
		 //print_r($result);
		return ($result);		
	}
        
//==================================Dual Degree or 5 year Honours case Starts===========================================
        public function getStudents_dd_hons($data)
	{

		$session_year = $data['session_year'];
		$session = $data['session'];
		$depart = $data['depart_id'];

		$this->load->database();
		
		$result = array();
		$i = 0;
		foreach ($data['map_id'] as $row) 
		{
			$result1 = $this->db->query("SELECT distinct admn_no as admission_id , sub_id as subject_id ,map_id ,status
									 	 FROM absent_table_dd_hons 
										 WHERE  map_id = '$row->map_id'
										 AND status != 0
										 ORDER BY admission_id ");

			($result1 = $result1->result()  );
			//print_r($result1);
			if(count($result1))
			{	
				foreach ($result1 as $col) 
				{
					array_push($result,$col );
				}
				
			}
		} 
				
		foreach ($result as $row) 
		{
			$query = $this->db->query("SELECT name as sub_name , subject_id as sub_code  
									   FROM subjects
									   WHERE id = '$row->subject_id ' ");
			($query = $query->result()) ;
			$row->sub_name = $query[0]->sub_name;
			$row->sub_code = $query[0]->sub_code;
		}
		//print_r($result);
		//echo '<br>' ;
		foreach ($result as $row) 
		{
			$query = $this->db->query("SELECT course_id , branch_id , semester as semster  
									   FROM reg_regular_form
									   WHERE admn_no = '$row->admission_id' order by form_id desc ");
			($query = $query->result()) ;
			$row->course_id = $query[0]->course_id;
			$row->course_id = $query[0]->course_id;
			$row->semester = $query[0]->semster;
		}
		//print_r($result);
		
		foreach ($result as $row) 
		{
			$temp = $this->db->query("SELECT total_class
							FROM total_class_table_dd_hons
							WHERE map_id = $row->map_id 
							AND sub_id = '$row->subject_id' ");
			$temp = $temp->result();
			$row->total_class = $temp[0]->total_class;
			//print_r($row);
			$query=$this->db->query("SELECT count(date) as date FROM absent_table_dd_hons
									 WHERE map_id = $row->map_id AND sub_id = '$row->subject_id'
									 AND admn_no = '$row->admission_id' 
									 AND Remark='none'
									 AND status != 0 ");
			//echo "bhagat";
			($temp_1 = $query->result()) ;
			//print_r($temp_1);
						$res=$this->getModFromtech($row->subject_id,$row->admission_id);
						
			$percent = (($temp[0]->total_class - $temp_1[0]->date)+($res->count)) *100;
			$percent = (float)(($percent/$temp[0]->total_class));

			$percent = round($percent,2);
			$row->percent = $percent;
		}
		//print_r($result);
		
		
		foreach ($result as $row) 
		{
			$temp1 = $this->db->query("SELECT first_name , middle_name , last_name
									   FROM user_details
									   WHERE id = '$row->admission_id'  ");
							
			//$row->total_class = $temp[0]->total_class;

			
			($temp1 = $temp1->result()) ;
			$row->stu_name = $temp1[0]->first_name.' '.$temp1[0]->middle_name.' '.$temp1[0]->last_name;

	
		 }
		 //print_r($result);
		return ($result);		
	}
//==================================Dual Degree or 5 year Honours case Ends===========================================


public function send_exam_section($result)
	{
		$this->load->database();
		foreach ($result as $row) 
		{	
				$this->load->database();
				$status=2;
				$this->db->query("UPDATE absent_table 
								 SET status=$status 
								 WHERE admn_no='$row[admission_id]' 
								 AND map_id=$row[map_id] 
								 AND sub_id='$row[subject_id]' 
								");
			
		}
	}
        public function send_exam_section_dd_hons($result)
	{
		$this->load->database();
		foreach ($result as $row) 
		{	
				$this->load->database();
				$status=2;
				$this->db->query("UPDATE absent_table_dd_hons 
								 SET status=$status 
								 WHERE admn_no='$row[admission_id]' 
								 AND map_id=$row[map_id] 
								 AND sub_id='$row[subject_id]' 
								");
			
		}
	}

	public function change_status($map_id,$subject_id,$admission_id)
	{
		$this->load->database();
		
		$this->db->query("UPDATE absent_table 
							 SET status=0 
							 WHERE admn_no='$admission_id' 
							 AND map_id=$map_id 
							 AND sub_id='$subject_id' 
							 ");

	}
        public function change_status_dd_hons($map_id,$subject_id,$admission_id)
	{
		$this->load->database();
		
		$this->db->query("UPDATE absent_table_dd_hons 
							 SET status=0 
							 WHERE admn_no='$admission_id' 
							 AND map_id=$map_id 
							 AND sub_id='$subject_id' 
							 ");

	}
        function get_student_status($map_id,$subject_id,$admission_id){
            $q="select * from absent_table where admn_no='".$admission_id."' and map_id='".$map_id."' and sub_id='".$subject_id."'";
            $query = $this->db->query($q);
            return $query->result();
        }

	public function send_notification($subject_id,$map_id)
	{
		$this->load->database();
		$teacher_id = $this->db->query("SELECT emp_no  as teacher_id
		                         FROM subject_mapping_des
		                         WHERE map_id = $map_id
		                         AND sub_id = '$subject_id'
									 ");
		$teacher_id = $teacher_id->result();
		//print_r($temp);
		return $teacher_id;
	}

	public function  get_subjectname($subject_id)
	{
		$this->load->database();
		$sub_name = $this->db->query("SELECT subject_id, name
		                         FROM subjects
		                         WHERE id = '$subject_id'
		                          ");
		$sub_name = $sub_name->result();
		return $sub_name;
	}

	public function get_remarks($data)
	{
		//print_r($data['result']);
		$this->load->database();
		foreach ($data['result'] as $row)
		{
			$temp = $this->db->query("SELECT enrollment_year as year
			                 FROM stu_academic
			                 WHERE admn_no = '$row->admission_id' 
			                  ");
			$temp = $temp->result();
			$year = (int)$temp[0]->year;
			//$cmp = (int)$
			if($year >= 2013)
			{
				
				$temp_new = $this->db->query("SELECT Remark 
				                         FROM defaulter_remark_table
				                         WHERE sl_no = 1 ");
				 $remark='';
                                if(!empty($temp_new))
				$remark = $temp_new->Remark;
				$row->remark = $remark.' '.$data['session_year'] ;
			}
			elseif (0) 
			{
				
			}
			else
			{
				if($row->percent < 60)
				{
					//echo  'hello';
					$temp_new = $this->db->query("SELECT Remark 
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 2 ");
					$temp_new = $temp_new->result();
					$remark = $temp_new[0]->Remark;
					$row->remark = $remark;
				}
				else
				{
					$temp_new = $this->db->query("SELECT Remark 
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 3 ");
					$temp_new = $temp_new->result();
					$remark = $temp_new[0]->Remark;
					$row->remark = $remark;
				}	
				
				
			}
		}
		//return $data;
	}
	public function get_remarks_new($result)
	{
		//print_r($data['result']);
		$this->load->database();
		foreach ($result as $row)
		{
			$temp = $this->db->query("SELECT enrollment_year as year
			                 FROM stu_academic
			                 WHERE admn_no = '$row->admission_id' 
			                  ");
			$temp = $temp->result();
			$year = (int)$temp[0]->year;
			//$cmp = (int)$
			if($year >= 2013)
			{
				
				$temp_new = $this->db->query("SELECT Remark 
				                         FROM defaulter_remark_table
				                         WHERE sl_no = 1 ");
				$temp_new = $temp_new->result();
				$remark = $temp_new[0]->Remark;
				$row->remark = $remark.' '.$data['session_year'] ;
			}
			elseif (0) 
			{
				
			}
			else
			{
				if($row->percent < 60)
				{
					//echo  'hello';
					$temp_new = $this->db->query("SELECT Remark 
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 2 ");
					$temp_new = $temp_new->result();
					$remark = $temp_new[0]->Remark;
					$row->remark = $remark;
				}
				else
				{
					$temp_new = $this->db->query("SELECT Remark 
					                         FROM defaulter_remark_table
					                         WHERE sl_no = 3 ");
					$temp_new = $temp_new->result();
					$remark = $temp_new[0]->Remark;
					$row->remark = $remark;
				}	
				
				
			}
		}
		//return $data;
	}
	
	function getdeptname($id){
		$q="select * from departments a where a.id=?";
		$qu=$this->db->query($q,array($id));
		return $qu->row()->name;
	}
	
	function getModFromtech($subid,$stuid){
			$q=$this->db->get_where('Attendance_remark_table',['sub_id'=>$subid,'admn_no'=>$stuid]);
			if($q->num_rows() >0)
			return	$q->row();
		return false;
	}
}

?>