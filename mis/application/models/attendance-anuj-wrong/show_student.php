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
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and d.duration=4";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['subject']));	
                  }else{
                   
                      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and c.duration=4";
 
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
			//$this->db->where('acad_status','1');
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
			//$this->db->where('acad_status','1');
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
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			//$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
		//	$this->db->where('acad_status','1');
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
			$this->db->where('course_id',$course);
			$this->db->where('semester',$semester);
			//$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
		//	$this->db->where('acad_status','1');
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
	
	public function get_student_common($data)
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
}
?>