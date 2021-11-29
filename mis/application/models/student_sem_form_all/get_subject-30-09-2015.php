<?php
class Get_subject extends CI_Model
{
	var $table_subject = 'subjects';
	var $table_course_branch = 'course_branch';
	var $eleSubject = 'reg_regular_elective_opted';
	var $fee = 'reg_regular_fee';
	var $result = 'result_status';
	var $HM = 'stu_sem_honour_minor';
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	function getSubject($course_id,$branch_id,$semster,$stuid,$ty='',$dept_id='',$ele=''){
			$d=$this->getStudentAcdamicDetails($stuid);
			
			if(($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc' ) && ($semster =='1' || $semster=='2')){
				$curaid = 'comm_comm_'.$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
			}else{
				$curaid = $course_id."_".$branch_id."_".$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
			}
			
		$this->load->model('course_structure/basic_model');
		
		$agr =$this->basic_model->get_latest_aggr_id($course_id,$branch_id,$curaid);
			
		if(($course_id == 'btech' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc' ) && ($semster =='1' || $semster=='2')){
			
				$f = "comm_comm_".$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
				$agr =$this->basic_model->get_latest_aggr_id('comm','comm',$f);
				$f=$agr[0]->aggr_id;
					
		}else if(empty($agr)){
		
			$f =  $curaid = $course_id."_".$branch_id."_".$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
		}else{
				
			$f =  $agr[0]->aggr_id;
		}
		if($dept_id =="")
			$dept_id = $this->session->userdata('dept_id');
		
		//echo $semster.",".$f.",".$dept_id;
		//echo $this->session->userdata('course_id');
		if(($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc' ) && ($semster =='1' || $semster=='2')){
		if($semster =='1'){
			$stu = $this->getStuform($stuid);
			if($stu->section == '1'){$semster ='1_1';}else{$semster ='1_2';}
			
		}
		if($semster =='2'){
			if($stu->section == '2'){$semster ='2_1';}else{$semster ='2_2';}
		}
		$dept_id ='cse';
		}
		
		$data=$this->basic_model->get_subjects_by_sem_and_dept($semster,$f,$dept_id);
		
		if($ty =='1'){
			$o = false;
			foreach($data as $da){
				if(is_float($this->get_numeric($da->sequence))){
					$o = true;
					break;					
				}
				
			}
			return $o;
		}else{
		$i=0;
	
		foreach($data as $da){
			if($ele ==1){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}else{
			if(!is_float($this->get_numeric($da->sequence))){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}
			}
			$i++;
		}
		
		 
		 return $d;
		}
	}
	
	function getSubjectviaAggrid($semester,$aggr_id,$dept,$g='',$ele=''){
            
            if($g){
                $semester = $semester."_".$g;
            }
             echo $semester.$aggr_id.$dept;       
            $this->load->model('course_structure/basic_model');
           $data=$this->basic_model->get_subjects_by_sem_and_dept($semester,$aggr_id,$dept);
         
          $i=0;
           foreach($data as $da){
			if($ele ==1){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}else{
			if(!is_float($this->get_numeric($da->sequence))){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}
			}
			$i++;
		}
		
		 
		 return $d;
        }
	
	protected function get_numeric($val) {
				if (is_numeric($val)) {
					return $val + 0;
				}
				return 0;
			}
	// Get Elective Subject From Corse Structure //
	function getElective($course_id,$branch_id,$semster,$stuid){
			$d=$this->getStudentAcdamicDetails($stuid);
			
		
				 $curaid = $course_id."_".$branch_id."_".$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
			
                               // die();
		$this->load->model('course_structure/basic_model');
		
		$agr =$this->basic_model->get_latest_aggr_id($course_id,$branch_id,$curaid);
		//print_r($agr);
		 if(empty($agr)){
			$f =  $curaid = $course_id."_".$branch_id."_".$d[0]->enrollment_year."_".($d[0]->enrollment_year+1);
		}else{
				
			$f =  $agr[0]->aggr_id;
		}
			
		$this->load->model('course_structure/offer_elective_model');
                $this->load->model('student_sem_form/sbasic_model');
                $c= $this->sbasic_model->getCourseDurationById($course_id);
                $dur_year = $c/2;
               // print_r($dur_year);
                    if($semster%2 == 0){
                         $ls=$c-$semster;
                              $y =$ls/2;
                    }else{
                      $ls=$c-$semster-1;
                            $y = ($ls/2)+1;
                    }
                    //echo $f." ".$semster." ".(date('Y')+$y);
               
		$data=$this->offer_elective_model->select_elective_offered_by_aggr_id($f,$semster,(date('Y')+$y));
		//print_r($data);
		$i=0;
		foreach($data as $da){
			$d['ele'][$i]['sequence']=$da->sequence;
			$d['ele'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['ele'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			$d['ele'][$i]['id']=$da->id;
			
			$i++;
		}
		if($d){
		return $d;
		}
		
	}
	
	// get Honours Subject//
	
		function getHonourSubject($stuid,$semster){
			
			$d=$this->getStudentAcdamicDetails($stuid);
			$curaid = "honour_honour_".$d[0]->enrollment_year;
			
			$this->load->model('course_structure/basic_model');
			$agr =$this->basic_model->get_latest_aggr_id("honour","honour",$curaid);
			$data=$this->basic_model->select_honour_or_minor_offered_by_aggr_id($agr[0]->aggr_id,$semster);
			return $data;
		}
		
		// Get Minor Subject//
		function getMinorSubject($stuid,$semster){
				
			$d=$this->getStudentAcdamicDetails($stuid);
			$curaid = "minor_minor_".$d[0]->enrollment_year;
				
			$this->load->model('course_structure/basic_model');
			$agr =$this->basic_model->get_latest_aggr_id("minor","minor",$curaid);
			$this->basic_model->select_honour_or_minor_offered_by_aggr_id($agr[0]->aggr_id,$semster);
				
		}
	
	// Get Elective, Hornor, Minor Subjects and fee Details for Registration During form Filling//
	function getConfirm($id){
		$data['ele'] =$this->db->query("select * from reg_regular_elective_opted join subjects on reg_regular_elective_opted.sub_id = subjects.id where reg_regular_elective_opted.form_id='".$id."'")->result_array();
		$data['fee'] =$this->db->get_where($this->fee,array('form_id'=>$id))->result_array();
	 $q="select * from stu_sem_honour_minor join subjects on stu_sem_honour_minor.subject_id = subjects.id where stu_sem_honour_minor.form_id='".$id."'";
                $data['HM'] = $this->db->query($q)->result_array();
                $data['HMC'] = $this->db->get_where('stu_sem_honour_minor',array('form_id'=>$id))->result_array();
		return $data;
		}
	
		
		function getStudentAcdamicDetails($id){
			return $this->db->get_where('stu_academic',array('admn_no'=>$id))->result();
			}
		
		function getSubjectById($id){
			$q=$this->db->get_where($this->table_subject,array('id'=>$id));
				if($q->num_rows($q)){
					
					return $q->result();
					}
					return false;
			}
			
	function getStuform($stuid){
            $q="select * from reg_regular_form where form_id=(select max(`form_id`) from reg_regular_form where admn_no=?)";
            $qu=$this->db->query($q,array($stuid));
            if($qu->num_rows() > 0){
                return $qu->row();
            }
            return false;
        }
	
}
?>