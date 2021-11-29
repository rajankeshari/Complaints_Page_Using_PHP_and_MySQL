<?php
class Defaulter_sheet_model_two extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
    
  //   public function get_all_student($data)
  //   {
  //   	$this->load->database();
  //   	$sub_id=$data['sub_id'];
  //   	$session = $data['session'];
  //   	$session_year = $data['session_year'];
		// $branch_id=$data['branch_id'];
		// $semester=$data['class_res'][0]->semester;
		// $course_id=$data['course_id'];

  //   	$query="SELECT U.id as id,U.first_name as first_name,U.middle_name as middle_name,U.last_name as last_name 
  //   			FROM reg_regular_elective_opted
  //   			INNER JOIN reg_regular_form ON reg_regular_form.form_id=reg_regular_elective_opted.form_id
  //   			INNER JOIN user_details AS U ON U.id=reg_regular_form.admn_no
  //   			WHERE session='$session' AND session_year='$session_year' AND branch_id='$branch_id'
  //   			AND semester=$semester AND course_id='$course_id'

    			
  //   			";
  //   	$query=$this->db->query($query);
  //   	print_r($query->result());

  //   }
	public function get_student($data)
	{
		$sub=$data['sub_id'];
		$session = $data['session'];
		$this->load->database();
		if($session === 'Summer'){
			$sub_table = 'reg_summer_subject';
			$this->db->select('reg_summer_subject.form_id');
		
                        $this->db->from($sub_table);
                        $this->db->join('reg_summer_form', 'reg_summer_form.form_id = reg_summer_subject.form_id','inner'); 
		$this->db->where('sub_id',$sub);
                $this->db->where('reg_summer_form.session_year',$data['session_year']);
                $this->db->where('reg_summer_form.session',$data['session']);
		$query=$this->db->get();	
		
		$data['form_no'] = $query->result();
		}else{
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
		//echo $this->db->last_query(); die();
		$data['form_no'] = $query->result();
		}
		//if(count($data['form_no']) < 5) $data['form_no'] = array();
		//print_r($data); die();
		return $this->get_admn($data);
	}

	  /*function get_subject_id($id){
          
         $q=  $this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
         $get=$this->db->query('select * from subject_mapping_des as a join subjects as b on a.sub_id=b.id join subject_mapping as c on a.map_id=c.map_id where b.subject_id=? and c.course_id=? and c.branch_id=?',array($q->subject_id,$this->input->post('course'), $this->input->post('branch')))->result();
		 //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
         return $get;
       } */
	   		function get_subject_id($id,$data){
				// echo $data['aggr_id'];
			  
			 $q=  $this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
			 $r="select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
			 $get=$this->db->query($r,array($q->subject_id,$data['aggr_id']))->result();
			 //echo $this->db->last_query();
			 //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
			 return $get;
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
            
           // echo $this->db->last_query();die();
            $data['stu_admn'] = $q->result();
           
            return $this->get_name($data);
        }

           public function get_student_minor($data)
	{
           //@anuj fro drop student 16-08-2018
$drop_stu= "hm_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$data['session_year']."'
and a.`session`='".$data['session']."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";


    
               
               //echo '<pre>';print_r($data);echo '</pre>';die();
		$sub_id=$data['sub_id'];
	//	echo $sub;
		$session = $data['session'];
		$this->load->database();
		
		$sub_table = 'hm_form';
		$this->db->select('hm_form.admn_no')
		->from($sub_table)
                ->join('hm_minor_details','hm_minor_details.form_id=hm_form.form_id')
				->join('reg_regular_form','hm_form.admn_no=reg_regular_form.admn_no')
                ->where('hm_form.minor','1')
                ->where('reg_regular_form.session_year',$data['session_year'])
				 ->where('reg_regular_form.semester',$data['semester'])
                ->where('hm_form.minor_hod_status','Y')
                ->where('hm_minor_details.dept_id',$this->session->userdata('dept_id'))
                ->where('hm_minor_details.branch_id',$data['branch_id'])
                ->where('hm_minor_details.offered','1')
                ->where($drop_stu); //@anuj fro drop student 16-08-2018
		//$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();	
	//echo $this->db->last_query();die();
		$data['stu_admn'] = $query->result();
		//print_r($data['form_no']);
		// if(count($data['form_no'])!=0)
	    	return $this->get_name($data);
		// else
		// 	return $data['form_no'];
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
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and (d.duration=4 or d.duration=5) and a.admn_no not in (
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
        
	
	public function get_admn($data)
	{
           // echo '<pre>';print_r($data);echo '<pre>';die();
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
		//echo $i; die();
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
			//$this->db->where('semester',$semester);
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
                        $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
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
			$this->db->where('hod_status','1');
			$this->db->where('acad_status','1');
                        $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$query=$this->db->get();
                       // echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
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

	
	//for repeaters student

	public function get_rep_student($data)
	{
		$sub_id=$data['sub_id'];
		
		$this->load->database();
		$this->db->select('form_id');
		$this->db->from('reg_other_subject');
		$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();	
		//echo $this->db->last_query();
		$data['rep_form_no'] = $query->result();
		//if(count($data['rep_form_no'])!=0)
	    	return $this->get_rep_admn($data);
	    //else
	    //	return $data['rep_form_no'];
	}

	public function get_rep_admn($data)
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
			$this->db->like('reason','repeater');
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_rep_admn'] = $query->result();
			//if(count($data['stu_rep_admn'])!=0)
	    	return $this->get_rep_name($data);
	    	//else
	    	//return $data['stu_rep_admn'];
		}		
	}

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
	
	public function get_class($data)
	{
		$sub_id=$data['sub_id'];
		$this->load->database();
		$this->db->select('subject_mapping.map_id');
		$this->db->from('subject_mapping_des');
		$this->db->join('subject_mapping','subject_mapping_des.map_id=subject_mapping.map_id');
		$this->db->where('subject_mapping_des.sub_id',$sub_id);
		$this->db->where('subject_mapping.semester',$data['semester']);
		$query=$this->db->get();
		//echo $this->db->last_query();
		$tmp=$query->result();
		
		if(count($tmp)!=0)
			$map_id=$tmp[0]->map_id;
		else
			return $tmp;

		$this->db->select('semester,course_id,branch_id');
		$this->db->from('subject_mapping');
		$this->db->where('map_id',$map_id);
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
		if($data['section']){
				$q="SELECT b.`map_id` FROM `subject_mapping_des` a join subject_mapping b on a.map_id=b.map_id  WHERE a.sub_id = ? AND a.emp_no = ?
and b.section=? and b.session_year=? and b.`session`=?";
				$query=$this->db->query($q,array($sub_id,$emp_no,$data['section'],$data['session_year'],$data['session']));
		}else{
			$this->load->database();
		$this->db->select('a.map_id');
		$this->db->from('subject_mapping_des a');
		$this->db->join('subject_mapping b', 'b.map_id=a.map_id', 'inner');
		$this->db->where('a.sub_id',$sub_id);
		$this->db->where('a.emp_no',$emp_no);
		$this->db->where('b.branch_id',$data['branch_id']);
		
		$query=$this->db->get();
			
			
		/*$this->load->database();
		$this->db->select('map_id');
		$this->db->from('subject_mapping_des');
		$this->db->where('sub_id',$sub_id);
		$this->db->where('emp_no',$emp_no);
		
		$query=$this->db->get();*/
		}
		//print_r($query->result());
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

	public function insert_into_class_engaged($session_id,$map_id,$sub_id,$date,$timestamp,$group_no)
	{
		$this->load->database();
		$query="INSERT INTO class_engaged (session_id,map_id,sub_id,date,timestamp,group_no) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no)";
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

	public function get_total_group($session_id,$sub_id)
	{
		$this->load->database();
		$this->db->select('*');
		$this->db->from('prac_group_attendance');
		$this->db->where('session_id',$session_id);
		$this->db->where('sub_id',$sub_id);
		$this->db->order_by("group_no","asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function get_last_submisssion_date($map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$this->db->select('date');
		$this->db->from('class_engaged');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$this->db->where('group_no',$group_no);
		$this->db->order_by("date","desc");
		$query=$this->db->get();
		return $query->result();
	}
	public function insert_into_total_class_table($map,$sub_id,$session_id1,$num_class,$timestamp,$group_no) 
	{
		$this->load->database();
		$query="INSERT INTO total_class_table (map_id,sub_id,session_id,total_class,timestamp,group_no) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no)";
	    $this->db->query($query);
	}

	public function update_into_total_class_table($map,$sub_id,$session_id1,$num_class)
	{
		$this->load->database();
		$query="UPDATE total_class_table SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id=$session_id1 AND group_no=$group_no";
	 	$this->db->query($query);
	}

	public function insert_into_absent_table($admn,$map,$sub,$session_id,$date,$timestamp,$group_no)
	{
		$this->load->database();
		$query="INSERT INTO absent_table (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no)";
	    $this->db->query($query);
	}

	public function get_absent($admn,$map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$query=$this->db->query("select count(date) as date,admn_no,sub_id FROM absent_table
									WHERE map_id = $map AND sub_id = '$sub'
									AND admn_no = '$admn' 
									AND session_id = $session_id AND group_no=$group_no AND (Remark='none' || Remark='late_reg') ");
		return $query->result();
	}
        public function get_absent_dd_hons($admn,$map,$sub,$session_id,$group_no)
	{
		$this->load->database();
		$query=$this->db->query("select count(date) as date,admn_no,sub_id FROM absent_table_dd_hons
									WHERE map_id = $map AND sub_id = '$sub'
									AND admn_no = '$admn' 
									AND session_id = $session_id AND group_no=$group_no AND (Remark='none' || Remark='late_reg')");
		return $query->result();
	}

	public function send_dsw($admission_id,$map_id,$sub_id,$session_id,$status)
	{
		$this->load->database();
		$this->db->query("UPDATE absent_table SET status=$status WHERE admn_no='$admission_id' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id");
		
	}
        
        public function send_dsw_dd_hons($admission_id,$map_id,$sub_id,$session_id,$status)
	{
		$this->load->database();
		$this->db->query("UPDATE absent_table_dd_hons SET status=$status WHERE admn_no='$admission_id' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id");
		
	}

	public function insert_into_defaulter_table($session_year,$session,$branch_id,$course_id,$semester,$sub_id,$admn,$name,$percentage,$remark)
	{
		$this->load->database();
		$query="INSERT INTO defaulter_table (session_year,session,branch_id,course_id,semester,subject_id,admission_id,name,percentage,Remark) VALUES ('$session_year','$session','$branch_id','$course_id',$semester,'$sub_id','$admn','$name','$percentage','$remark')";
		$this->db->query($query);	
	}

	public function delete_record_from_defaulter_table($session_year,$session,$branch_id,$course_id,$semester,$sub_id)
	{
		$this->load->database();
		$query="DELETE FROM defaulter_table WHERE session_year='$session_year' AND session='$session' AND branch_id='$branch_id' AND course_id='$course_id' AND semester=$semester AND subject_id='$sub_id'";
		$this->db->query($query);
	}

	
	//for repeaters student

	/*public function get_rep_student($data)
	{
		$subject=$data['subject'];
		
		$this->load->database();
		$this->db->select('sem_form_id');
		$this->db->from('stu_other_sem_reg_subject');
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
			$tmp[$i]=$data['rep_form_no'][$i]->sem_form_id;
		}
		if($i>0)
		{
			$this->db->select('admission_id');
			$this->db->from('stu_other_sem_reg_form');
			$this->db->where('session_year',$session_year);
			$this->db->where('session',$session);
			$this->db->where('branch_id',$branch);
			$this->db->where('course_id',$course);
			$this->db->where('semster',$semester);
			$this->db->like('reason','repeater');
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
			$this->db->where_in('sem_form_id',$tmp);
			$query=$this->db->get();
			$data['stu_rep_admn'] = $query->result();
			return $this->get_rep_name($data);
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
		//	$this->db->like('id','2012');
			$this->db->where_in('id',$tmp);
			$this->db->order_by("id","asc");
			$query=$this->db->get();
			return $query->result();
		}		
	}
	*/

	/*
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
			$this->db->like('reason','repeater');
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_rep_admn'] = $query->result();
			if(count($data['stu_rep_admn'])!=0)
	   			return $this->get_rep_name($data);
			else
				return $data['stu_rep_admn'];
			
		}		
	}

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

	*/

	public function get_extra_attendance($admn_no,$sub_id,$session_id,$group_no)
	{

		$this->load->database();
		$query="SELECT sum(count) as count FROM Attendance_remark_table WHERE admn_no='$admn_no' AND sub_id='$sub_id' AND session_id=$session_id AND group_no=$group_no ";
		$query=$this->db->query($query);
		return $query->result();
	}

	public function get_check_status($admn_no,$map_id,$sub_id,$session_id,$group_no)
	{

		$this->load->database();
		$query="SELECT status FROM absent_table WHERE admn_no='$admn_no' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id AND group_no=$group_no ";
		$query=$this->db->query($query);
		return $query->result();
	}
        public function get_check_status_dd_hons($admn_no,$map_id,$sub_id,$session_id,$group_no)
	{

		$this->load->database();
		$query="SELECT status FROM absent_table_dd_hons WHERE admn_no='$admn_no' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id AND group_no=$group_no ";
		$query=$this->db->query($query);
		return $query->result();
	}
        function get_course_duration($id){
            $this->load->database();
		$query=$this->db->query("select b.duration from stu_academic a inner join cs_courses b on b.id=a.course_id
where a.admn_no='".$id."';");
		return $query->row();
            
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
			$this->db->where('reg_exam_rc_form.branch_id',$branch);
			$this->db->where('reg_exam_rc_form.course_id',$course);
			$this->db->where('reg_exam_rc_subject.sub_id',$data['sub_id']);
			$this->db->where('hod_status','1');
			//$this->db->where('acad_status','1');
			$this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
			$query=$this->db->get();
				//echo $this->db->last_query(); die();
			$data['stu_admn'] = $query->result();
			return $this->get_name($data);
		
	}
}
?>