<?php
class View_attendance_sheet_model_two extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_student($data)
	{//echo $data['aggr_id'];
      
		 $sub=$data['sub_id'];
		$session = $data['session'];
		$this->load->database();
		if($session === 'Summer')
			$sub_table = 'reg_summer_subject';
		else
			$sub_table = 'reg_regular_elective_opted';
		 $subs=$this->get_subject_id($sub,$data);
		$this->db->select('form_id');
		$this->db->from($sub_table);
                 $w=""; $i=0;
			foreach($subs as $c){
				if($i==0){$w="(`sub_id`='$c->id')";}else{$w.=" OR (`sub_id`='$c->id')"; }
			$i++;}
                        $this->db->where($w);
		//$this->db->where('sub_id',$sub);
		$query=$this->db->get();	
		
		//echo $this->db->last_query(); 
		if($query->num_rows() >0){
		$data['form_no'] = $query->result();
		}else{
			$data['form_no']=array();
		}     
      		//if(count($data['form_no']) < 5) $data['form_no'] =array();
	 
	    	return $this->get_admn($data);
	}

	function get_subject_id($id,$data){
			// echo $data['aggr_id'];
          
         $q=  $this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
		 $r="select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
		 $get=$this->db->query($r,array($q->subject_id,$data['aggr_id']))->result();
		 //echo $this->db->last_query();
		 //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
         return $get;
       } 

          public function get_student_prep($data)
	{
		
		$semester = $data['semester'];
		
		 $aggr_id=$data['aggr_id'];
		 $session = $data['session'];
		 $session_year=$data['session_year'];
		$q="SELECT `admn_no` FROM (`reg_regular_form`) WHERE `semester` = ? AND `hod_status` = '1' AND `acad_status` = '1' AND `session` =? AND `session_year` = ? and course_aggr_id=?";
 
		$query=$this->db->query($q,array($semester,$session,$session_year,$aggr_id));	
		$data['stu_admn'] = $query->result();
		//echo $this->db->last_query(); die();
		
  	    return $this->get_name($data);
	
	}

	 public function get_student_comm($data){
             //echo '<pre>';print_r($data);echo '</pre>';die();
          //@anuj fro drop student 16-08-2018
$drop_stu= "reg_regular_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$data['session_year']."'
and a.`session`='".$data['session']."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";   
             
            $this->db->select('reg_regular_form.admn_no')
                    ->from('reg_regular_form')
                    ->join('stu_section_data','stu_section_data.admn_no=reg_regular_form.admn_no')
                    ->where('reg_regular_form.hod_status','1')
                    ->where('reg_regular_form.acad_status','1')
                    ->where('reg_regular_form.session_year',$data['session_year'])
                    ->where('reg_regular_form.session',$data['session'])
                    ->where('reg_regular_form.semester',$data['semester'])
                    ->where('stu_section_data.section',$data['section'])
                    ->where('course_aggr_id',$data['aggr_id'])
                    ->where($drop_stu); //@anuj fro drop student 16-08-2018
            $q=$this->db->get();
            $data['stu_admn'] = $q->result();
           //echo $this->db->last_query();die();
            return $this->get_name($data);
        }
		
		public function  get_student_Summer_comm($data){
			 $this->db->select('reg_summer_form.admn_no')
                    ->from('reg_summer_form')
                    ->join('stu_section_data','stu_section_data.admn_no=reg_summer_form.admn_no')
					->join ('reg_summer_subject','reg_summer_subject.form_id=reg_summer_form.form_id')
                    ->where('reg_summer_form.hod_status','1')
                    ->where('reg_summer_form.acad_status','1')
                    ->where('reg_summer_form.session_year',$data['session_year'])
                    ->where('reg_summer_form.session',$data['session'])
                    /*->where('reg_summer_form.semester',$data['semester'])*/
                    ->where('stu_section_data.section',$data['section'])
					->where('reg_summer_subject.sub_id',$data['sub_id']);
                    
            $q=$this->db->get();
			//echo $this->db->last_query();die();
            $data['stu_admn'] = $q->result();
			 return $this->get_name($data);
		 }
		

          public function get_student_minor($data)
	{
              
             // echo '<pre>';print_r($data);echo '</pre>';die();
		$sub_id=$data['sub_id'];
	//	echo $sub;
		$session = $data['session'];
		/*$this->load->database();
		
		$sub_table = 'hm_form';
		$this->db->select('hm_form.admn_no')
		->from($sub_table)
                ->join('hm_minor_details','hm_minor_details.form_id=hm_form.form_id')
                ->where('hm_form.minor','1')
                ->where('hm_form.session_year',$data['session_year'])
                ->where('hm_form.minor_hod_status','Y')
                ->where('hm_minor_details.dept_id',$this->session->userdata('dept_id'))
                ->where('hm_minor_details.branch_id',$data['branch_id'])
                ->where('hm_minor_details.offered','1')
                 ->where('hm_minor_details.minor_agg_id',$data['aggr_id']);
		//$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();	*/
		  $q ="select  hm_form.admn_no from hm_form join hm_minor_details on hm_form.form_id=hm_minor_details.form_id JOIN reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no where hm_form.minor_hod_status ='Y' and hm_minor_details.dept_id='".$this->session->userdata('dept_id')."' and hm_minor_details.branch_id='".$data['branch_id']."' and hm_minor_details.offered='1' and reg_regular_form.session_year='".$data['session_year']."' and reg_regular_form.`session`='".$data['session']."' and reg_regular_form.semester='".$data['semester']."' and hm_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$data['session_year']."'
and a.`session`='".$data['session']."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";
			
            $query=$this->db->query($q);
            
           // echo $this->db->last_query();die();
		$data['stu_admn'] = $query->result();
	
		// if(count($data['form_no'])!=0)
	    	return $this->get_name($data);
		// else
		// 	return $data['form_no'];
	}
        
       /* public function get_student_honour($data)
	{
		/*$sub_id=$data['sub_id'];
	
		$session = $data['session'];
		$this->load->database();
		
		$sub_table = 'hm_form';
		$this->db->select('hm_form.admn_no')
		->from($sub_table)
                ->where('honours','1')
                ->where('session_year',$data['session_year'])				
                ->where('honour_hod_status','Y')
				
                ->where('honours_agg_id',$data['aggr_id']);

		$query=$this->db->get();	
		$data['stu_admn'] = $query->result();*/
		
	/*	 $branch=$data['branch'];
		 $aggr_id=$data['aggr_id'];
		 $session = $data['session'];
		 $session_year=$data['session_year'];
		$q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=?";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));	
		
		//echo $this->db->last_query(); die();
		
		
		$data['stu_admn'] = $query->result();
		 
	    	return $this->get_name($data);
	
	} */
        private function isElective($id){
            $qu=$this->db->get_where('subjects',array('id'=>$id));
            $r=$qu->row();
            if($r->elective == 0)
                return false;
              
            return true;
        }
        
        public function get_student_honour($data)
	{
		//echo '<pre>';print_r($data); echo '</pre>';die();
		 $branch=$data['branch'];
		 $aggr_id=$data['aggr_id'];
		 $session = $data['session'];
		 $session_year=$data['session_year'];
                 
                 if($this->isElective($data['sub_id'])){
		$q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and d.duration=4  and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['sub_id']));	
                 }else{
               
                    $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and (c.duration=4 or c.duration=5) and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";
 
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
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and d.duration=5 and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['subject']));	
                  }else{
                   
                      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and c.duration=5 and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";
 
		$query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));	
                }
            //    $q
              //  $q=""
		//echo $this->db->last_query(); die();
		
		
		$data['stu_admn'] = $query->result();
		
		return $this->get_name($data);
	}
        
        
//==========================FOR Dual Degree Honours Ends==========================================    
	
	public function get_admn($data)
	{   
            //print_r($data);die();
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch_id=$data['branch_id'];
		$semester=$data['class_res'][0]->semester;
		$course_id=$data['course_id'];
		$this->load->database();
		$tmp=array();
		for($i=0;$i<count($data['form_no']);$i++)
		{
			$tmp[$i]=$data['form_no'][$i]->form_id;
		}
                //@anuj fro drop student 16-08-2018
$drop_stu= "admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$semester." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";


                
		if($i>0)
		{

			$form_table = 'reg_regular_form';
			if($session === 'Summer')
				$form_table = 'reg_summer_form';
			$this->db->select('admn_no');
			$this->db->from($form_table);
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch_id);
			$this->db->where('course_id',$course_id);
			
			if($session <>'Summer')$this->db->where('semester',$semester);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
                        $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			//if(count($data['stu_admn'])!=0)
				return $this->get_name($data);
			//else
			//	return $data['stu_admn'];
		}
			else
		{
			$this->db->select('admn_no');
			$this->db->from('reg_regular_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch_id);
			$this->db->where('course_id',$course_id);
			$this->db->where('semester',$semester);
			$this->db->where('course_aggr_id',$data['aggr_id']);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
                        $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018

			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			//if(count($data['stu_admn'])!=0)
				return $this->get_name($data);
			//else
			//	return $data['stu_admn'];
		}
	}
	
		
	public function get_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_admn']);$i++)
		{
		//	echo $data['form_no'][$i]->sem_form_id;
			$tmp[$i]=$data['stu_admn'][$i]->admn_no;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
		//	$this->db->like('id','2012');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
		
	
			return $query->result();
		}
	}

	public function get_subject_name($data='')
	{
		$sub_id=$data['sub_id'];
		$this->load->database();
		$this->db->select('name');
		$this->db->from('subjects');
		$this->db->where('id',$sub_id);
		$this->db->order_by("name","asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function get_class($data)
	{
		/*$sub_id=$data['sub_id'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();
		$tmp=$query->result();
		if(count($tmp)!=0)
			$map_id=$tmp[0]->map_id;
		else
			return $tmp;*/

		$this->db->select('semester,course_id,branch_id');
		$this->db->from('subject_mapping');
		$this->db->where('map_id',$data['map_id']);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_course($data)
	{

		$branch=$data['branch'];
		$session_year=$data['session_year'];
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
		if(count($course_id)!=0)
			return $this->get_course_name($course_id);
		else
			return $course_id;
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

	public function get_course_name_again($course_id)
	{
		$this->load->database();
		$this->db->select('name');
		$this->db->from('courses');
		$this->db->where_in('id',$course_id);
		$query=$this->db->get();
		return $query->result();
	}


	public function get_map_id($data)
	{
		$sub_id=$data['sub_id'];
		$emp_no=$data['emp_id'];
		$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('sub_id',$sub_id);
		$this->db->where('emp_no',$emp_no);
		$query=$this->db->get();
		
		return $query->result();
	}

	public function get_session_id($session,$session_year,$semester,$branch_id,$course_id)
	{
		$this->load->database();
		$this->db->select('session_id');
		$this->db->from('session_track');
		$this->db->where('session',$session);
		$this->db->where('session_year',$session_year);
		$this->db->where('semester',$semester);
		$this->db->where('branch_id',$branch_id);
		$this->db->where('course_id',$course_id);
		$query=$this->db->get();
		return $query->result();
	}

	public function insert_into_session_track($session,$session_year,$semester,$branch_id,$course_id)
	{
		$this->load->database();
		$query="INSERT INTO session_track (session,session_year,semester,branch_id,course_id) VALUES('$session','$session_year',$semester,'$branch_id','$course_id')";
	    $this->db->query($query);
	}

	public function insert_into_class_engaged($session_id,$map_id,$sub_id,$date,$timestamp)
	{
		$this->load->database();
		$query="INSERT INTO class_engaged (session_id,map_id,sub_id,date,timestamp) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp')";
		$this->db->query($query);
	}

	public function get_total_class($map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$this->db->select('total_class');
		$this->db->from('total_class_table');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$this->db->where('group_no',$group_no);
		$query=$this->db->get();
		return $query->result();
	}
        public function get_total_class_dd_hons($map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$this->db->select('total_class');
		$this->db->from('total_class_table_dd_hons');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$this->db->where('group_no',$group_no);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_total_class_jrf($map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$this->db->select('total_class');
		$this->db->from('total_class_table_jrf');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$this->db->where('group_no',$group_no);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_total_group($session_id,$sub_id,$map_id)
	{
		$this->load->database();
		$this->db->select('*');
		$this->db->from('prac_group_attendance');
		$this->db->where('session_id',$session_id);
		$this->db->where('sub_id',$sub_id);
		$this->db->where('map_id',$map_id);
		$this->db->order_by("group_no","asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function insert_into_total_class_table($map,$sub_id,$session_id1,$num_class,$timestamp) 
	{
		$this->load->database();
		$query="INSERT INTO total_class_table (map_id,sub_id,session_id,total_class,timestamp) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp')";
	    $this->db->query($query);
	}

	public function update_into_total_class_table($map,$sub_id,$session_id1,$num_class)
	{
		$this->load->database();
		$query="UPDATE total_class_table SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id=$session_id1 ";
	 	$this->db->query($query);
	}

	public function insert_into_absent_table($admn,$map,$sub,$session_id,$date,$timestamp)
	{
		$this->load->database();
		$query="INSERT INTO absent_table (admn_no,map_id,sub_id,session_id,date,timestamp,Remark) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none')";
	    $this->db->query($query);
	}

	public function get_date_of_class($session_id,$map_id,$sub_id,$group_no)
	{
		$this->load->database();
		$query="SELECT date,class_no FROM class_engaged WHERE session_id=$session_id AND sub_id='$sub_id' AND map_id=$map_id AND group_no='$group_no'";
		$query=$this->db->query($query);
              //  echo $this->db->last_query();
		return $query->result();
	}
        public function get_date_of_class_dd_hons($session_id,$map_id,$sub_id,$group_no)
	{
		$this->load->database();
		$query="SELECT date,class_no FROM class_engaged_dd_hons WHERE session_id=$session_id AND sub_id='$sub_id' AND map_id=$map_id AND group_no='$group_no'";
		$query=$this->db->query($query);
              //  echo $this->db->last_query();
		return $query->result();
	}
	public function get_date_of_class_jrf($session_id,$map_id,$sub_id,$group_no)
	{
		$this->load->database();
		$query="SELECT date,class_no FROM class_engaged_jrf WHERE session_id=$session_id AND sub_id='$sub_id' AND map_id=$map_id AND group_no='$group_no'";
		$query=$this->db->query($query);
              //  echo $this->db->last_query();
		return $query->result();
	}

	//for repeaters student

	public function get_rep_student($data)
	{
		$sub_id=$data['sub_id'];
		
		$this->load->database();
		$this->db->select('form_id');
		$this->db->from('reg_other_subject');
		$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();	
		$data['rep_form_no'] = $query->result();
		if(count($data['rep_form_no'])!=0)
	    	return $this->get_rep_admn($data);
	    else
	    	return $data['rep_form_no'];
	}
	
	public function get_rep_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch_id'];
		$semester=$data['class_res'][0]->semester;
		$course=$data['course_id'];
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

	/*public function get_rep_admn($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch_id=$data['branch_id'];
		$semester=$data['class_res'][0]->semester;
		$course_id=$data['course_id'];
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
			$this->db->where('branch_id',$branch_id);
			$this->db->where('course_id',$course_id);
			$this->db->where('semester',$semester);
			$this->db->like('reason','rep');
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_admn']=$query->result();
			return $this->get_name($data);
		}		
	}*/

	public function get_rep_name($data)
	{
		$tmp=array();
		for($i=0;$i<count($data['stu_rep_admn']);$i++)
		{
			$tmp[$i]=$data['stu_rep_admn'][$i]->admn_no;
		}
		if($i>0)
		{
			$this->load->dbutil();
			$this->load->database();
			$this->db->select('id,first_name,middle_name,last_name');
			$this->db->from('user_details');
		//	$this->db->like('id','2012');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}		
	}
        //====================================JRF attendance upload============================
        
        public function get_student_jrf($data)
	{
		//echo '<pre>';print_r($data);echo '</pre>'; die();
                
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch=$data['branch_id'];
		$semester=$data['semester'];
		$course=$data['course_id'];
		$this->load->database();
		
				
		 //@anuj fro drop student 16-08-2018
$drop_stu= "admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$session_year."'
and a.`session`='".$session."' and a.semester=".$semester." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";		
                
              
			$this->db->select('reg_exam_rc_form.admn_no');
			$this->db->from('reg_exam_rc_form');
                        $this->db->join('reg_exam_rc_subject', 'reg_exam_rc_subject.form_id = reg_exam_rc_form.form_id');
			$this->db->where('reg_exam_rc_form.session_year',$session_year);
			$this->db->where('reg_exam_rc_form.session',$session);
			//$this->db->where('reg_exam_rc_form.branch_id',$branch);
			//$this->db->where('reg_exam_rc_form.course_id',$course);
			$this->db->where('reg_exam_rc_subject.sub_id',$data['sub_id']);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$query=$this->db->get();
				//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		
	}

	public function get_rep_student_comm($data)
	{
		//print_r($data);die();
		if($data['session']=="Monsoon"){
			$x1='1_1';$y1='1_2';
		}
		if($data['session']=="Winter"){
			$x1='2_1';$y1='2_2';
		}
		
		$sql = "select z.form_id from
(select b.*,c.aggr_id,c.semester,
CASE WHEN c.semester='".$x1."' THEN 'A' WHEN c.semester='".$y1."' THEN 'E' END as section
 from reg_other_subject b
inner join reg_other_form a on a.form_id=b.form_id
inner join course_structure c on c.id=b.sub_id
where a.session_year=? and a.`session`=?
and c.aggr_id like '%comm%' and a.hod_status='1' and a.acad_status='1')z where z.section=? and z.sub_id=?
";

        $query = $this->db->query($sql,array($data['session_year'],$data['session'],$data['section'],$data['sub_id']));
		
		//echo $this->db->last_query();die();
		$data['rep_form_no'] = $query->result();
		//if(count($data['rep_form_no'])!=0)
	    	return $this->get_rep_admn_comm($data);
	    //else
	    //	return $data['rep_form_no'];
	}

	public function get_rep_admn_comm($data)
	{
		$session_year = $data['session_year'];
		$session=$data['session'];
		$branch_id=$data['branch_id'];
		$semester=$data['semester'];
		$course_id=$data['course_id'];
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
			$this->db->like('reason','rep');
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
			$this->db->where_in('form_id',$tmp);
                       // $this->db->where('course_aggr_id',$data['aggr_id']);
			$query=$this->db->get();
			$data['stu_admn']=$query->result();
			return $this->get_name($data);
	    	//else
	    	//return $data['stu_rep_admn'];
		}		
	}
}
?>