<?php
class Cbcs_attendance_group_formation_list_model extends CI_Model
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
		$sub_id=$data['sub_id'];
	//	echo $sub;
		$session = $data['session'];
		$this->load->database();
		if($session === 'Summer')
			$sub_table = 'reg_summer_subject';
		else
			$sub_table = 'reg_regular_elective_opted';
		$this->db->select('form_id');
		$this->db->from($sub_table);
		$this->db->where('sub_id',$sub_id);
		$query=$this->db->get();	
		$data['form_no'] = $query->result();
		//print_r($data['form_no']);
		// if(count($data['form_no'])!=0)
	    	return $this->get_admn($data);
		// else
		// 	return $data['form_no'];
	}
	
	public function get_admn($data)
	{
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
			$this->db->where('semester',$semester);
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
			$this->db->where_in('form_id',$tmp);
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			//if(count($data['stu_admn'])!=0)
				return $this->get_name($data);
			//else
				//return $data['stu_admn'];
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
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
			$query=$this->db->get();
			$data['stu_admn'] = $query->result();
			//if(count($data['stu_admn'])!=0)
				return $this->get_name($data);
			//else
				//return $data['stu_admn'];
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

	// public function get_class($data)
	// {
	// 	$sub_id=$data['sub_id'];
	// 	$this->load->database();
	// 	$this->db->select('map_id');
	// 	$this->db->from('subject_mapping_des');
	// 	$this->db->where('sub_id',$sub_id);
	// 	$this->db->order_by('map_id','desc');
 //        $this->db->limit('1');
	// 	$query=$this->db->get();
	// 	$tmp=$query->result();
	// 	if(count($tmp)!=0)
	// 		$map_id=$tmp[0]->map_id;
	// 	else
	// 		return $tmp;

	// 	$this->db->select('semester,course_id,branch_id');
	// 	$this->db->from('subject_mapping');
	// 	$this->db->where('map_id',$map_id);
	// 	$query=$this->db->get();
	// 	return $query->result();
	// }

	public function get_class($data,$tbl)
	{
		if($tbl=='cbcs'){
			$tbl_temp='cbcs_subject_offered_desc';
			$tbl_temp1='cbcs_subject_offered';
		}else{
			$tbl_temp='old_subject_offered_desc';
			$tbl_temp1='old_subject_offered';
		}
		
		$sub_id=$data['sub_id'];
		$semester=$data['semester'];
		$this->load->database();
		$this->db->select(''.$tbl_temp.'.sub_offered_id');
		$this->db->from($tbl_temp);		
		$this->db->join(''.$tbl_temp1.'', ''.$tbl_temp1.'.id = '.$tbl_temp.'.sub_offered_id');
		$this->db->where(array(''.$tbl_temp.'.sub_id' =>$sub_id,''.$tbl_temp1.'.semester'=>$semester));
		$this->db->order_by(''.$tbl_temp.'.sub_offered_id','desc');
        $this->db->limit('1');
		$query=$this->db->get();
		 //echo $query; die();
		$tmp=$query->result();
		
	//	 echo $this->db->last_query(); die();
		if(count($tmp)!=0)
			$sub_offered_id=$tmp[0]->sub_offered_id;
		else
			return $tmp;

		$this->db->select('semester,course_id,branch_id');
		$this->db->from($tbl_temp1);
		$this->db->where('id',$sub_offered_id);
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

	public function insert_into_class_engaged($session_id,$map_id,$sub_id,$date,$timestamp)
	{
		$this->load->database();
		$query="INSERT INTO class_engaged (session_id,map_id,sub_id,date,timestamp) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp')";
		$this->db->query($query);
	}

	public function get_total_class($map,$sub,$session_id)
	{
		$this->load->database();
		$this->db->select('total_class');
		$this->db->from('total_class_table');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_last_submisssion_date($map,$sub,$session_id)
	{
		$this->load->database();
		$this->db->select('date');
		$this->db->from('class_engaged');
		$this->db->where('map_id',$map);
		$this->db->where('sub_id',$sub);
		$this->db->where('session_id',$session_id);
		$this->db->order_by("date","desc");
		$query=$this->db->get();
		return $query->result();
	}

	// public function insert_into_prac_group_attendance($session_id,$sub_id,$group_no,$group_start,$group_end,$mapid)
	// {
	// 	$this->load->database();
	// 	$query="INSERT INTO prac_group_attendance(session_id,sub_id,group_no,group_start,group_end,map_id) VALUES($session_id,'$sub_id',$group_no + 1,'$group_start','$group_end',$mapid)";
	//     $this->db->query($query);
	// }

	public function insert_into_prac_group_attendance($s_id,$sub_id,$group_no,$group_start,$group_end,$section)
	{
		$this->load->database();
		$query="INSERT INTO cbcs_prac_group_attendance(subject_id,sub_id,group_no,group_start,group_end,section) VALUES('$s_id','$sub_id','$group_no','$group_start','$group_end','$section')";
	    $this->db->query($query);
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

	public function get_absent($admn,$map,$sub,$session_id)
	{
		$this->load->database();
		$query=$this->db->query("select count(date) as date FROM absent_table
									WHERE map_id = $map AND sub_id = '$sub'
									AND admn_id = '$admn' 
									AND session_id = $session_id AND (Remark='none' || Remark='late_reg')");
		return $query->result();
	}

	// public function get_detail_from_prac_group_attendance($data)
	// {
	// 	$sub=$data['sub_id'];
	// 	$session_id=$data['session_id'];
	// 	$tmap_id=$data['tmap_id'];
	// 	$this->load->database();
	// 	$query=$this->db->query("select * FROM prac_group_attendance
	// 								WHERE sub_id = '$sub'
	// 								AND session_id = $session_id
	// 								AND map_id = $tmap_id ");
	// 	return $query->result();
	// }

	public function get_detail_from_prac_group_attendance($data)
	{
		$sub=$data['sub_id'];
		//$session_id=$data['session_id'];
		$tmap_id=$data['tmap_id'];
		$this->load->database();
		$query=$this->db->query("select * FROM cbcs_prac_group_attendance
									WHERE sub_id = '$sub'
									/*AND session_id = $session_id*/
									AND subject_id = $tmap_id ");
		return $query->result();
	}

	public function send_dsw($admission_id,$map_id,$sub_id,$session_id,$status)
	{
		$this->load->database();
		$this->db->query("UPDATE absent_table SET status=$status WHERE admn_id='$admission_id' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id");
		
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


	// public function get_map_id_reg($session,$session_year,$semester,$branch_id,$course_id)
	// {
	// 	$this->load->database();
	// 	$this->db->select('map_id');
	// 	$this->db->from('subject_mapping');
	// 	$this->db->where('session',$session);
	// 	$this->db->where('session_year',$session_year);
	// 	$this->db->where('semester',$semester);
	// 	$this->db->where('branch_id',$branch_id);
	// 	$this->db->where('course_id',$course_id);
	// 	$query=$this->db->get();
	// 	return $query->result();
	// }

	public function get_map_id_reg($session,$session_year,$semester,$branch_id,$course_id,$tbl)
	{
		if($tbl=='cbcs'){
			
			$tbl_temp='cbcs_subject_offered';
		}else{
			
			$tbl_temp='old_subject_offered';
		}
		
		$this->load->database();
		$this->db->select('id');
		$this->db->from($tbl_temp);
		$this->db->where('session',$session);
		$this->db->where('session_year',$session_year);
		$this->db->where('semester',$semester);
		$this->db->where('branch_id',$branch_id);
		$this->db->where('course_id',$course_id);
		$query=$this->db->get();
		return $query->result();
	}

	// public function get_map_id_comm($session,$session_year,$semester,$branch_id,$course_id,$sec)
	// {
	// 	$this->load->database();
	// 	$this->db->select('map_id');
	// 	$this->db->from('subject_mapping');
	// 	$this->db->where('session',$session);
	// 	$this->db->where('session_year',$session_year);
	// 	$this->db->where('semester',$semester);
	// 	$this->db->where('branch_id',$branch_id);
	// 	$this->db->where('course_id',$course_id);
	// 	$this->db->where('section',$sec);
	// 	$query=$this->db->get();
	// 	return $query->result();
	// }

	public function get_map_id_comm($session,$session_year,$semester,$branch_id,$course_id,$sec)
	{
		$this->load->database();
		// $this->db->select('id');
		// $this->db->from('cbcs_subject_offered');
		// $this->db->where('session',$session);
		// $this->db->where('session_year',$session_year);
		// $this->db->where('semester',$semester);
		// $this->db->where('branch_id',$branch_id);
		// $this->db->where('course_id',$course_id);
		//$this->db->where('section',$sec);
		$sql="SELECT a.`id`
FROM `cbcs_subject_offered` a
join cbcs_subject_offered_desc b on b.`sub_offered_id`=a.`id`
WHERE a.`session` = '$session' AND a.`session_year` = '$session_year' AND a.`semester` = '$semester' AND a.`branch_id` = '$branch_id' AND a.`course_id` = '$course_id' AND b.`section`='$sec'";
		//$query=$this->db->get();
		$query=$this->db->query($sql);
		return $query->result();
	}


}
?>