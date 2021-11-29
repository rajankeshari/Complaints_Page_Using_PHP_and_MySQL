<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_ajax extends CI_Controller
{
	function __construct()
    {
        parent::__construct();
    }

	public function update_branch($course = '',$dept = '')
	{
		if($course)
		{
			$this->load->model('course_structure/basic_model','',TRUE);
			$data['branches'] = $this->basic_model->get_branches_by_course_and_dept($course,$dept);
			$this->load->view('student/ajax/student_update_branches',$data);
		}
		else
		{
			$data['courses']='';
			$this->load->view('student/ajax/student_update_courses',$data);
		}
	}

	public function update_courses($dept = '')
	{
		$this->load->model('course_structure/basic_model','',TRUE);
		$data['courses'] = $this->basic_model->get_course_offered_by_dept($dept);
		$this->load->view('student/ajax/student_update_courses',$data);
	}

	function check_if_user_exists($id = '')
	{
		if($id !== '')
		{
			$this->load->model('user/user_details_model','',TRUE);
			$data['user'] = $this->user_details_model->getUserById($id);
			if($data['user'])
				$this->load->view('student/ajax/student_update_user_id',$data);
		}
	}

	function check_if_rejected_user_exists($id = '')
	{
		if($id !== '')
		{
			$this->load->model('student/student_rejected_detail_model','',TRUE);
			$data['user'] = $this->student_rejected_detail_model->get_stu_status_details_by_id($id);
			if($data['user'])
				$this->load->view('student/ajax/student_update_user_id',$data);
		}
	}

	function check_if_user_for_validation_exists($id = '')
	{
		if($id !== '')
		{
			$this->load->model('student/student_details_to_approve','',TRUE);
			$data['user'] = $this->student_details_to_approve->get_all_stu_details_by_id($id);
			if($data['user'])
				$this->load->view('student/ajax/student_update_user_id',$data);
		}
	}


	public function edit_record($form = -1, $s = -1)
	{
		$stu_id=$this->session->userdata('EDIT_STUDENT_ID');
		$this->load->model('student/student_education_details_model','',TRUE);
		$data['stu_education_details']=$this->student_education_details_model->getStuEduByIdOrder($stu_id,$s);
		if($s != -1) {
			$pending = $this->student_education_details_model->getPendingStuEduByIdOrder($stu_id,$s);
			if(!$pending)
				$data['stu_education_details']=$this->student_education_details_model->getStuEduByIdOrder($stu_id,$s);
			else
				$data['stu_education_details']=$pending;
		}
		
		$data['sno']=$s;
		$this->load->view('student/ajax/edit_record',$data);
	}


	public function delete_record($form = -1, $s = -1)
	{
		$stu_id=$this->session->userdata('EDIT_STUDENT_ID');
		$this->load->model('student/student_education_details_model','',TRUE);
		$pending = $this->student_education_details_model->getPendingStuEduById($stu_id);
		if(!$pending)
			$this->student_education_details_model->copyDetailstoPendingById($stu_id);
		if($s != -1)	
			$this->student_education_details_model->deletePendingDetailsWhere(array('admn_no'=>$stu_id, 'sno'=>$s));
		$this->edit_validation($stu_id,'educational_status');
			
		$data['form'] = $form;
		$data['stu_id'] = $stu_id;
		$this->load->view('student/ajax/delete_record',$data);
	}

	private function edit_validation($stu_id,$form)
	{
		$this->load->model('student/stu_validation_details_model','',TRUE);
		$res = $this->stu_validation_details_model->getValidationDetailsById($stu_id);
		//If no entry in the emp_validation_details table then insert the record else update the record.
		if($res == FALSE)
		{
			$validation_details = array('admn_no'=>$stu_id,
										'profile_pic_status'=> 'approved',
										'basic_details_status'=> 'approved',
										'educational_status'=> 'approved',
										'created_date'=> date('Y-m-d H:i:s',time()));
			$validation_details[$form] = 'pending';
			$this->stu_validation_details_model->insert($validation_details);
		}
		else
		{
			$this->stu_validation_details_model->updateById(array($form => 'pending'),$stu_id);
		}

		//Notify Employee about the change in details
		$this->load->model('user/users_model','',TRUE);
		$user = $this->users_model->getUserById($stu_id);
		if($user->auth_id == 'stu' && $user->password !='')
		{
			$msg='';
			switch($form)
			{
				case 'profile_pic_status' : $msg = "Your photograph have been successfully edited by Data Entry Operator ".$this->session->userdata('id')." and sent for validation.";break;
				case 'basic_details_status' : $msg = "Your basic details have been successfully edited by Data Entry Operator ".$this->session->userdata('id')." and sent for validation.";break;
				case 'education_details_status' : $msg = "Your educational qualifications have been successfully edited by Data Entry Operator ".$this->session->userdata('id')." and sent for validation.";break;
			}
			$this->notification->notify($stu_id, 'stu', "Details Edited", $msg, "student/view/index/".(($this->session->userdata('EDIT_STUDENT_FORM')==0)? $this->session->userdata('EDIT_STUDENT_FORM'):($this->session->userdata('EDIT_STUDENT_FORM')-1)));
		}
		//Notify Assistant registrar for validation
		$this->load->model('user/user_details_model','',TRUE);
		$user = $this->user_details_model->getUserById($stu_id);
		$stu_name = ucwords($user->salutation.' '.$user->first_name.(($user->middle_name != '')? ' '.$user->middle_name: '').(($user->last_name != '')? ' '.$user->last_name: ''));
		$this->load->model('user/user_auth_types_model','',TRUE);
		$res = $this->user_auth_types_model->getUserIdByAuthId('acad_ar');
		foreach ($res as $row)
		{
			if($row->id == $stu_id)	continue;
			$this->notification->notify($row->id, 'acad_ar', "Validation Request", "Please validate ".$stu_name." details", "student/validate/validate_step/".$stu_id);
		}
	}
}
?>