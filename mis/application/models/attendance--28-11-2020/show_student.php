<?php
class Show_student extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_student($data)
	{
      	//print_r($data);
		$sub=$data['subject'];
		$session = $data['session'];
		$this->load->database();
		if($session === 'Summer')
			$sub_table = 'reg_summer_subject';
		else
			$sub_table = 'reg_regular_elective_opted';
		$subs=$this->get_subject_id($sub,$data);
                
		//print_r($subs);
		$this->db->select('form_id');
		$this->db->from($sub_table);
		$w=""; $i=0;
			foreach($subs as $c){
				if($i==0){$w="(`sub_id`='$c->id')";}else{$w.=" OR (`sub_id`='$c->id')"; }
			$i++;}
			$this->db->where($w);
		//$this->db->where('sub_id',$sub);
		$query=$this->db->get();	
		
		//echo $this->db->last_query(); die();
		//if($query->num_rows() >0){
		$data['form_no'] = $query->result();
	//	print_r($data['form_no']); die();
		//}else{
		//	$data['form_no']=array();
		//}     
      		//if(count($data['form_no']) < 5) $data['form_no'] =array();
	//	print_r($this->get_admn($data));
		return $this->get_admn($data);
	}
	public function get_student_summer($data)
	{
		//print_r($data);
		$sub=$data['subject'];
		$session = $data['session'];
		$this->load->database();
		$sub_table = 'reg_summer_subject';
		 $subs=$this->get_subject_id($sub,$data);
        // print_r($subs);
		$this->db->select('form_id');
		$this->db->from($sub_table);
		$w=""; $i=0;
			foreach($subs as $c){
				if($i==0){$w="(`sub_id`='$c->id')";}else{$w.=" OR (`sub_id`='$c->id')"; }
			$i++;}
			$this->db->where($w);
		//$this->db->where('sub_id',$sub);
		$query=$this->db->get();	
		
		//echo $this->db->last_query(); die();
		//if($query->num_rows() >0){
		$data['form_no'] = $query->result();
		//print_r($data['form_no']);
		//die();
		//echo "</br>";echo "</br>";
		//}else{

		//	$data['form_no']=array();
		//}     
      		//if(count($data['form_no']) < 5) $data['form_no'] =array();
	//	print_r($this->get_admn($data));
		return $this->get_admn_summer($data);
	}
        
         private function isElective($id){
            $qu=$this->db->get_where('subjects',array('id'=>$id));
            $r=$qu->row();
            if($r->elective == 0)
                return false;
              
            return true;
        } 
        
	public function get_student_honour($data)
	{
		//print_r($data); die();
		 $branch=$data['branch'];
		 $aggr_id=$data['aggr_id'];
		 $session = $data['session'];
		 $session_year=$data['session_year'];
                 
                  if($this->isElective($data['subject'])){
		$q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['subject']."' and a.`status`='B')";  
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['subject']));	
                  }else{
                   
                      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['subject']."' and a.`status`='B')"; 
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));	
                }
            //    $q
              //  $q=""
		//echo $this->db->last_query(); die();
		
		
		$data['stu_admn'] = $query->result();
		
		return $this->get_name($data);
	}
//==========================FOR Dual Degree Honours starts==========================================
public function get_student_honour_dd($data)
	{
		//print_r($data); die();
		 $branch=$data['branch'];
		 $aggr_id=$data['aggr_id'];
		 $session = $data['session'];
		 $session_year=$data['session_year'];
                 
                  if($this->isElective($data['subject'])){
		$q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and d.duration=5";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['subject']));	
                  }else{
                   
                      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and c.duration=5";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));	
                }
            //    $q
              //  $q=""
		//echo $this->db->last_query(); die();
		
		
		$data['stu_admn'] = $query->result();
		
		return $this->get_name($data);
	}
        
        
//==========================FOR Dual Degree Honours Ends==========================================        
	 //commmented as  schedule code was not working  while inserting  schedule @11-apr-16 @rituraj, need further verificaition whether change disturbs other module or not by the team handling the attendance & schedule. However new code was taken from server-50. Best possibility that all goes right.
	function get_subject_id($id,$data){
			// echo $data['aggr_id'];
          
         $q=  $this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
		 $r="select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
		 $get=$this->db->query($r,array($q->subject_id,$data['aggr_id']))->result();
		 //echo $this->db->last_query();
         //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
         return $get;
    } 
	// end 
	
	// nEW function taken from  server-50  ignoring  previous one above  @11-apr-16 @rituraj
	/* function get_subject_id($id){
         //echo $id; die();
         $q=$this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
		 $get=$this->db->query('select * from subject_mapping_des as a join subjects as b on a.sub_id=b.id join subject_mapping as c on a.map_id=c.map_id where b.subject_id=? and c.course_id=? and c.branch_id=?',array($q->subject_id,$this->input->post('course'), $this->input->post('branch')))->result();
         //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
         return $get;
       } */
// end
	public function get_admn($data)
	{
		//print_r($data);
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['semester'];
		$course=$data['course'];
		$this->load->database();
		$tmp=array();
                
		for($i=0;$i<count($data['form_no']);$i++)
		{       
                        
			if($session === 'Summer'){
                            $tmp[$i]=$data['form_no'][$i]->form_id;
                        }else{
                            $tmp[$i]=$this->verify_session_syear($data['form_no'][$i]->form_id,$session_year,$session);
                        }
				
			
		}
                //@anuj 12-09-2017 mis complaint 888 Dr Tanmoy Maity complaint for subject A C Controller 
                // change has been made. it was fetching form_id from elective opted and was not comparing with
                // reg_regular table for session year and session, verify_session_syear function is implemented for this
                // and strlen(implode($tmp) is also implemented for this.
                if (strlen(implode($tmp)) == 0){
                    $i=0;
                }
				
		 //@anuj fro drop student 16-08-2018
$drop_stu= "admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$semester." and a.sub_id='".$data['subject']."' and a.`status`='B')";		
                
              
		if($i>0)
		{
			//echo "hello i am here";
			$form_table = 'reg_regular_form';
			if($session === 'Summer')
				$form_table = 'reg_summer_form';
			$this->db->select('admn_no');
			$this->db->from($form_table);
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status !=','2');
			$this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
		else
		{
			//echo "hello";die();
			$this->db->select('admn_no');
			$this->db->from('reg_regular_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->where('course_aggr_id',trim($data['aggr_id']));
			$this->db->where('hod_status','1');
			$this->db->where('acad_status !=','2');
			$this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$query=$this->db->get();
				//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
	}
        
      public function get_admn_summer($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['class_res'][0]->semester;
		$course=$data['course'];
		$this->load->database();
		$tmp=array();
		for($i=0;$i<count($data['form_no']);$i++)
		{
			$tmp[$i]=$data['form_no'][$i]->form_id;
		}
		if($i>0)
		{
			//echo "hello i am here";
			/*$form_table = 'reg_regular_form';
			if($session === 'Summer')*/
				$form_table = 'reg_summer_form';
			$this->db->select('admn_no');
			$this->db->from($form_table);
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			if($course!='honour' && $course!='minor'){
			$this->db->where('course_id',$course);
			}
			//$this->db->where('semester',$semester); // commented as semester match not required @rituraj/21-5-18
			//$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
		$this->db->where('acad_status !=','2');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
		else
		{
			//echo "hello";
			$this->db->select('admn_no');
			$this->db->from('reg_summer_form');
			//$this->db->from('reg_regular_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			if($course!='honour' && $course!='minor'){
			$this->db->where('course_id',$course);  // commented as course is not working in honour
			}
		//	$this->db->where('semester',$semester);  // commented as semester match not required @rituraj/21-5-18
			//$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
		$this->db->where('acad_status !=','2');
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		}
	}

	public function get_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_admn']);$i++)
		{
			$tmp[$i]=$data['stu_admn'][$i]->admn_no;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}
	}

	public function get_subject_name($data='')
	{
		$subject_id=$data['subject'];
		if(empty($subject_id)){
			$subject_id=$data['comm_details']->sub_id;
		}
		$this->load->database();
		$this->db->select('name');
		$this->db->from('subjects');
		$this->db->where('id',$subject_id);
		$this->db->order_by("name","asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function get_class($data)
	{
		$subject_id=$data['subject'];
		$this->load->database();
                //=====================================
    $this->db->select('a.map_id');
    $this->db->from('subject_mapping_des a'); 
    $this->db->join('subject_mapping b', 'b.map_id=a.map_id', 'inner');
    $this->db->where(array('a.sub_id' => $subject_id, 'b.session_year' => $data['session_year'], 'b.session' => $data['session']));
    $query = $this->db->get(); 
                
                //=======================================
                
                
//		$this->db->select('map_id');
//		$this->db->from('subject_mapping_des');
//		$this->db->where('sub_id',$subject_id);
//                
//		$query=$this->db->get();
//                echo $this->db->last_query();die();
		$tmp=$query->result();
		$map_id=$tmp[0]->map_id;

		$this->db->select('semester,course_id,branch_id');
		$this->db->from('subject_mapping');
		$this->db->where('map_id',$map_id);
		$query=$this->db->get();
                
		return $query->result();
	}

	public function get_course($data)
	{

		$branch=$data['branch'];
		$session_year=$this->input->post('session_year');
		$session=$data['session'];
		$semester=$data['semester'];
		
		$form_table = 'reg_regular_form';
		if($session === 'Summer')
			$form_table = 'reg_summer_form';
		$this->load->database();
		$this->db->select('course_id');
		$this->db->from($form_table);
		$this->db->where('branch_id',$branch);
		$this->db->where('semester',$semester);
		$this->db->where('session_year',$session_year);
		$this->db->where('session',$session);
		$this->db->distinct();
		$query=$this->db->get();
		$course_id=$query->result();
		return $this->get_course_name($course_id);
	}

	public function get_course_name($course_id)
	{
		$course_id_arr=array();
		for($i=0;$i<count($course_id);$i++)
			$course_id_arr[$i]=$course_id[$i]->course_id;
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('courses');
		$this->db->where_in('id',$course_id_arr);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rep_student($data)
	{
		$subject=$data['subject'];
		
		$this->load->database();
		$this->db->select('form_id');
		$this->db->from('reg_other_subject');
		$this->db->where('sub_id',$subject);
		$query=$this->db->get();	
		$data['rep_form_no'] = $query->result();
	    return $this->get_rep_admn($data);
	}

	public function get_rep_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['class_res'][0]->semester;
		$course=$data['course'];
		$this->load->database();
		$tmp=array();
		for($i=0;$i<count($data['rep_form_no']);$i++)
		{
			$tmp[$i]=$data['rep_form_no'][$i]->form_id;
		}
		if($i>0)
		{
			$this->db->select('admn_no');
			$this->db->from('reg_other_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			$this->db->like('reason','rep');
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			//echo $this->db->last_query(); die();
			$data['stu_admn']=$query->result();
			return $this->get_name($data);
		}		
	}

	public function get_rep_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_rep_admn']);$i++)
		{
			$tmp[$i]=$data['stu_rep_admn'][$i]->admission_id;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}		
	}
	public function get_course_name_from_course_id($course_id)
	{
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('courses');
		$this->db->where('id',$course_id);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_section1($data)
	{
		$emp_id=$data['emp_id'];
		$session_year=$data['session_year'];
		$session=$data['session'];
		$subject=$data['subject'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('emp_no',$emp_id);
		$this->db->where('sub_id',$subject);
		$q=$this->db->get();
		$map=$q->result()[0]->map_id;

		$this->db->select('section');
		$this->db->from('subject_mapping');
		$this->db->where('map_id',$map);
		$q=$this->db->get();
		return $q->result();
	}
	public function get_section2($session_year)
	{
		$this->load->database();
		$this->db->select('section');
		$this->db->from('section_group_rel');
		$this->db->where('session_year',$session_year);
		$query=$this->db->get();
		return $query->result();
	}
	
	/*public function get_student_common($data)
	{
            //print_r($data);
		$session_year=$data['session_year'];
		$section=$data['section'];
		$this->load->database();
                if($data['session']<>'Summer'){
		$this->db->select('admn_no');
		$this->db->from('stu_section_data');
		$this->db->where('section',$section);
		$this->db->where('session_year',$session_year);
                }else{
                    $this->db->select('reg_summer_form.admn_no');
		$this->db->from('stu_section_data');
                 $this->db->join('reg_summer_form', 'reg_summer_form.admn_no = stu_section_data.admn_no','inner'); 
		$this->db->where('stu_section_data.section',$section);
		$this->db->where('stu_section_data.session_year',$session_year);
                }
                
		$query=$this->db->get();
		//echo $this->db->last_query(); die();
		$data['stu_admn']=$query->result();
		return $this->get_name($data);
	}*/
	public function get_student_common($data)
	{
            //echo $data['comm_details']->aggr_id;
            //echo '<pre>';print_r($data);echo '<pre>';die();
		$session_year=$data['session_year'];
		$section=$data['section'];
		$this->load->database();
                
                $drop_stu= "stu_section_data.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['comm_details']->aggr_id)."' and a.session_year='".$session_year."'
and a.`session`='".$data['session']."' and a.semester='".$data['comm_details']->semester."' and a.sub_id='".$data['comm_details']->sub_id."' and a.`status`='B')";

                
                
                if($data['session']<>'Summer'){
		$this->db->select('admn_no');
		$this->db->from('stu_section_data');
		$this->db->where('section',$section);
		$this->db->where('session_year',$session_year);
                $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
                }else{
                    $this->db->select('reg_summer_form.admn_no');
		$this->db->from('stu_section_data');
                 $this->db->join('reg_summer_form', 'reg_summer_form.admn_no = stu_section_data.admn_no','inner'); 
		$this->db->where('stu_section_data.section',$section);
		$this->db->where('stu_section_data.session_year',$session_year);
                $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
                }
                
		$query=$this->db->get();
		//echo $this->db->last_query(); die();
		$data['stu_admn']=$query->result();
		return $this->get_name($data);
	}
        //@anuj 16-08-2018 
        function get_common_details($syear,$sess,$id,$sec){
            $sql = "select a.emp_no,a.sub_id,c.aggr_id,b.semester,b.section from subject_mapping_des a 
inner join subject_mapping b on a.map_id=b.map_id
inner join course_structure c on c.id=a.sub_id
where b.session_year=? and b.`session`=?
and a.emp_no=? and c.aggr_id like 'comm%' and b.section=? group by a.sub_id";
	        $query = $this->db->query($sql,array($syear,$sess,$id,$sec));
				
			        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            return $query->row();
			        } else {
			            return false;
			        }
            
        }
	
	
	public function get_student_prep($data)
	{
		$session_year=$data['session_year'];
		$semester=$data['semester'];
		$session = $data['session'];
		$this->load->database();
		$this->db->select('admn_no');
		$this->db->from('reg_regular_form');
		$this->db->where('semester',$semester);
		$this->db->where('hod_status','1');
		$this->db->where('acad_status','1');
		$this->db->where('session',$session);
		$this->db->where('session_year',$session_year);
		$query=$this->db->get();
		//echo $this->db->last_query(); die();
		$data['stu_admn']=$query->result();
		return $this->get_name($data);
	}
        
        function verify_session_syear($id,$syear,$sess){
            $sql = "select * from reg_regular_form where form_id=? and session_year=? and session=? and hod_status='1'";
	        $query = $this->db->query($sql,array($id,$syear,$sess));
				
			        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            return $query->row()->form_id;
			        } else {
			            return false;
			        }
            
        }
        
        //======Jrf attendance starts=============================================================
        
        public function get_admn_jrf($data)
	{
		//echo '<pre>';print_r($data);echo '</pre>'; die();
                
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch'];
		$semester=$data['semester'];
		$course=$data['course'];
		$this->load->database();
		
				
		 //@anuj fro drop student 16-08-2018
$drop_stu= "admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$semester." and a.sub_id='".$data['subject']."' and a.`status`='B')";		
                
              
			$this->db->select('reg_exam_rc_form.admn_no');
			$this->db->from('reg_exam_rc_form');
                        $this->db->join('reg_exam_rc_subject', 'reg_exam_rc_subject.form_id = reg_exam_rc_form.form_id');
			$this->db->where('reg_exam_rc_form.session_year',$session_year);
			$this->db->where('reg_exam_rc_form.session',$session);
			//$this->db->where('reg_exam_rc_form.branch_id',$branch);
			//$this->db->where('reg_exam_rc_form.course_id',$course);
			$this->db->where('reg_exam_rc_subject.sub_id',$data['subject']);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status !=','2');
			$this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$query=$this->db->get();
				//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		
	}
        
        
        
        //=====Jrf attendance ends================================================================
        function get_student_dept($id){
             $sql = "select a.dept_id from user_details a where a.id=?";
	        $query = $this->db->query($sql,array($id));
		        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            return $query->row();
			        } else {
			            return false;
			        }
            
            
        }

        function get_student_common_other($data){
        	

        	//echo '<pre>';print_r($data);echo '</pre>';
if($data['session']=="Monsoon"){
			$x1='1_1';$y1='1_2';
		}
		if($data['session']=="Winter"){
			$x1='2_1';$y1='2_2';
		}

             $sql = "SELECT z.admn_no
FROM (
SELECT b.*,c.aggr_id,c.semester, CASE WHEN c.semester='".$x1."' THEN 'A' WHEN c.semester='".$y1."' THEN 'E' END AS section,a.admn_no
FROM reg_other_subject b
INNER JOIN reg_other_form a ON a.form_id=b.form_id
INNER JOIN course_structure c ON c.id=b.sub_id
WHERE a.session_year=? AND a.`session`=? AND c.aggr_id LIKE '%comm%' AND a.hod_status='1' AND a.acad_status='1')z
WHERE z.section=? AND z.sub_id=?";
	        $query = $this->db->query($sql,array($data['session_year'],$data['session'],$data['section'],$data['comm_details']->sub_id));

	        //echo $this->db->last_query();die();
		        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            //return $query->row();
                        	$data['stu_admn']=$query->result();
						return $this->get_name($data);
			        } else {
			            return false;
			        }
            
            
        }
		function get_student_cbcs($data){
			$sql = "SELECT a.*,c.id,c.first_name,c.middle_name,c.last_name FROM cbcs_stu_course a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=a.admn_no
WHERE a.subject_code=? AND b.session_year=? AND b.`session`=? and a.course=? and a.branch=? order by c.id ";
	        $query = $this->db->query($sql,array($data['subject'],$data['session_year'],$data['session'],$data['course'],$data['branch']));
		        //if ($this->db->affected_rows() >= 0) {
                                if ($query->num_rows() > 0){
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		//=============================JRF on 22-08-2019================
		
		function get_class_jrf_cbcs($data){
			
			//print_r($data);
			
			if($data['rstatus']=='cbcs'){
				$tbl="cbcs_subject_offered";
			}else{
				$tbl="old_subject_offered";
			}				
			$sql = "SELECT `semester`, `course_id`, `branch_id` FROM (`".$tbl."`) WHERE `id` = ?";
	        $query = $this->db->query($sql,array($data['aggr_id']));
		      
                   if ($query->num_rows() > 0){
			            return $query->result();
			        } else {
			            return false;
			        }
		}
		function get_admn_jrf_name($data){
			
			
			
			if($data['rstatus']=='cbcs'){
				$sql = "SELECT `reg_regular_form`.`admn_no`
FROM (`reg_regular_form`)
JOIN `cbcs_stu_course` ON `cbcs_stu_course`.`form_id` = `reg_regular_form`.`form_id`
WHERE `reg_regular_form`.`session_year` = '".$data['session_year']."' AND `reg_regular_form`.`session` = '".$data['session']."' 
AND `cbcs_stu_course`.`subject_code` = '".$data['subject']."' AND `reg_regular_form`.`hod_status` = '1' AND 
`reg_regular_form`.`acad_status` != '2' AND `reg_regular_form`.`admn_no` NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id='".$data['aggr_id']."' AND a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."' AND a.semester=".$data['semester']." 
AND a.sub_id='".$data['subject']."' AND a.`status`='B')
";
	        $query = $this->db->query($sql);
				
			}else{
				//$tbl="old_subject_offered";
				$sql = "SELECT `reg_regular_form`.`admn_no`
FROM (`reg_regular_form`)
JOIN `old_stu_course` ON `old_stu_course`.`form_id` = `reg_regular_form`.`form_id`
WHERE `reg_regular_form`.`session_year` = '".$data['session_year']."' AND `reg_regular_form`.`session` = '".$data['session']."' 
AND `old_stu_course`.`subject_code` = '".$data['subject']."' AND `reg_regular_form`.`hod_status` = '1' AND 
`reg_regular_form`.`acad_status` != '2' AND `reg_regular_form`.`admn_no` NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id='293' AND a.session_year='".$data['session_year']."' AND a.`session`='".$data['session']."' AND a.semester=".$data['semester']." 
AND a.sub_id='".$data['subject']."' AND a.`status`='B')
";
	       $query = $this->db->query($sql);
				
			}		

			$data['stu_admn'] = $query->result();
			return $this->get_name($data);			
			
		      
                  /* if ($query->num_rows() > 0){
			            return $query->result();
			        } else {
			            return false;
			        }*/
			
		}
		
		public function get_subject_name_jrf($data='')
	{
		
		if($data['rstatus']=='cbcs'){
				$subject_id=$data['sub_code'];
		if(empty($subject_id)){
			$subject_id=$data['comm_details']->sub_id;
		}
		$this->load->database();
		$this->db->select('sub_name');
		$this->db->from('cbcs_subject_offered');
		$this->db->where('sub_code',$subject_id);
			//$this->db->group_by('sub_name');
		$this->db->order_by("sub_name","asc");
		$query=$this->db->get();
		return $query->result();
				
				
			}else{
				
				$subject_id=$data['sub_code'];
		if(empty($subject_id)){
			$subject_id=$data['comm_details']->sub_id;
		}
		$this->load->database();
		$this->db->select('sub_name');
		$this->db->from('old_subject_offered');
		$this->db->where('sub_code',$subject_id);
		//$this->db->group_by('sub_name');
		$this->db->order_by("sub_name","asc");
		$query=$this->db->get();
		return $query->result();
			}	
			
		
	}
	
	function get_student_as_upload($data){
       //echo '<pre>';
      // print_r($data);echo '</pre>'; die();
        if($data['rstatus']=='old'){$tbl=' old_stu_course ';}
        if($data['rstatus']=='cbcs'){ $tbl=' cbcs_stu_course ';}		 
      if($data['course_id']=='comm'){
        $con="  d.section=?";
        $p=array($data['section'],$data['sub_code'],$data['session_year'],$data['session']);
      }else{
        $con=" b.course_id=? and b.branch_id=?";
        $p=array($data['course_id'],$data['branch_id'],$data['sub_code'],$data['session_year'],$data['session']);
      }	  	  	

      $sql = "SELECT c.id, c.first_name,c.middle_name,c.last_name FROM ".$tbl."  a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
left JOIN stu_section_data d ON d.admn_no=a.admn_no AND d.session_year=a.session_year
INNER JOIN user_details c ON c.id=b.admn_no
WHERE   ".$con." AND a.subject_code=? AND b.session_year=? AND b.`session`=? and 
b.hod_status='1' AND b.acad_status='1' 
ORDER BY c.id   	 $str_add " ;

        $query = $this->db->query($sql, $p);
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }


    }
		
}
?>