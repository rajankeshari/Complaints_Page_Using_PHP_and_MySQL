<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('student/stu_validation_details_model','',TRUE);
		$this->load->model('user/user_details_model','',TRUE);
	}

	public function index()
	{
		$this->addJS('student/student_reject_reason_script.js');
		
		$data['stu_validation_details']=$this->stu_validation_details_model->getValidationDetails();
		$this->drawHeader("Validation Requests");
		$this->load->view('student/validation/index',$data);
		$this->drawFooter();
	}


	function validate_step($admn_no='', $step=-1)
	{
if(! ( ($this->authorization->is_auth('acad_ar'))|| ($this->authorization->is_auth('acad_dr'))   )) {
			$this->session->set_flashdata('flashError','You are not authorized.');
			redirect('home');
			return;
		}

		if($admn_no == '') {
			redirect('student/validate');
			return;
		}

		$this->addJS('student/student_reject_reason_script.js');

		$this->load->model('user/users_model');
		$this->load->model('user/user_other_details_model','',TRUE);
		$this->load->model('departments_model','',TRUE);
		$this->load->model('student/student_details_model','',TRUE);
		$this->load->model('student/student_other_details_model','',TRUE);
		$this->load->model('student/student_fee_details_model','',TRUE);
		$this->load->model('student/student_academic_model','',TRUE);
		$this->load->model('user/user_address_model','',TRUE);
		$this->load->model('student/student_education_details_model','',TRUE);
		$this->load->model('student/stu_validation_details_model','',TRUE);

		$data['admn_no']=$admn_no;
		$data['step']=$step;

		$data['stu_validation_details'] = $this->stu_validation_details_model->getValidationDetailsById($admn_no);

		//initialization
		$data['pending_photo'] = false;
		$data['pending_basic_details'] = false;
		$data['pending_education_details'] = false;

		if($data['stu_validation_details'])	{

			$users = $this->users_model->getUserById($admn_no);
			$user_details = $this->user_details_model->getPendingDetailsById($admn_no);
			$user_other_details = $this->user_other_details_model->getPendingDetailsById($admn_no);
			$pending_stu_basic_details = $this->student_details_model->get_pending_student_details_by_id($admn_no);
			$pending_stu_other_details = $this->student_other_details_model->get_pending_student_other_details_by_id($admn_no);
			$pending_stu_fee_details = $this->student_fee_details_model->get_pending_stu_fee_details_by_id($admn_no);
			$pending_stu_academic_details = $this->student_academic_model->get_pending_stu_academic_details_by_id($admn_no);
			//approved details from real tables and rejected/pending details from pending tables
			//case 0 : profile pic status
			if($data['stu_validation_details']->profile_pic_status != 'approved' && $user_details)
				$data['pending_photo'] = $user_details->photopath;

			//case 1 : basic details status
			$data['pending_stu'] = false;
			if($data['stu_validation_details']->basic_details_status != 'approved' && $users && $user_details 
				&& $user_other_details && $pending_stu_basic_details && $pending_stu_other_details 
				&& $pending_stu_fee_details  && $pending_stu_academic_details) {				
						$user = (object)(array_merge((array)$users,(array)$user_details,
														(array)$user_other_details, 
														(array)$pending_stu_other_details,
														(array)$pending_stu_basic_details,
														(array)$pending_stu_fee_details,
														(array)$pending_stu_academic_details)
											);

				$data['pending_stu'] = $user;
				$data['pending_dept'] = $this->departments_model->getDepartmentById($user->dept_id)->name;
				$data['pending_course'] = $this->student_details_model->getCourseByCourseId($user->course_id)->name;
				$data['pending_branch'] = $this->student_details_model->getBranchByBranchId($user->branch_id)->name;
				$data['pending_permanent_address'] = $this->user_address_model->getPendingDetailsById($admn_no,'permanent');
				$data['pending_present_address'] = $this->user_address_model->getPendingDetailsById($admn_no,'present');
				$data['pending_correspondence_address'] = $this->user_address_model->getPendingDetailsById($admn_no, 'correspondence');
			}

			$data['photo'] = $this->user_details_model->getUserById($admn_no)->photopath;

			$users = $this->users_model->getUserById($admn_no);
			$user_details = $this->user_details_model->getUserById($admn_no);
			$user_other_details = $this->user_other_details_model->getUserById($admn_no);
			$stu_basic_details = $this->student_details_model->get_student_details_by_id($admn_no);
			$stu_other_details = $this->student_other_details_model->get_student_other_details_by_id($admn_no);
			$stu_fee_details = $this->student_fee_details_model->get_stu_fee_details_by_id($admn_no);
			$stu_academic_details = $this->student_academic_model->get_stu_academic_details_by_id($admn_no);

			$user = (object)(array_merge((array)$users,(array)$user_details,
														(array)$user_other_details, 
														(array)$stu_other_details,
														(array)$stu_basic_details,
														(array)$stu_fee_details,
														(array)$stu_academic_details)
											);

			$data['stu'] = $user;

			$data['permanent_address'] = $this->user_address_model->getAddrById($admn_no,'permanent');
			$data['present_address'] = $this->user_address_model->getAddrById($admn_no,'present');
			$data['correspondence_address'] = $this->user_address_model->getAddrById($admn_no,'correspondence');
	
			$data['department'] = $this->departments_model->getDepartmentById($user->dept_id)->name;
			$data['course'] = $this->student_details_model->getCourseByCourseId($user->course_id)->name;
			$data['branch'] = $this->student_details_model->getBranchByBranchId($user->branch_id)->name;
			$data['pending_stu_education_details'] = false;
			if($data['stu_validation_details']->educational_status != 'approved' && $users && $user_details && $user_other_details) {
			 	$data['pending_stu_education_details'] = $this->student_education_details_model->getPendingStuEduById($admn_no);
			}

			$data['stu_education_details'] = $this->student_education_details_model->getStuEduById($admn_no);
		}
		else {
			$this->session->set_flashdata('flashInfo','The student '.$admn_no.' details have been Approved');
			redirect('student/validate');
			return;
		}
		$this->drawHeader("Student Validation","<h4><b>Admission No. </b>< ".$admn_no.' ></h4>');
		$this->load->view('student/validation/index',array('stu_validation_details'=>array($data['stu_validation_details'])));
		$this->load->view('student/validation/view',$data);
		$this->drawFooter();
	}

	function validate_details($stu_id, $step)
	{
		if(!   ( ($this->authorization->is_auth('acad_ar'))|| ($this->authorization->is_auth('acad_dr'))   ))
		{
			$this->session->set_flashdata('flashError','You are not authorized.');
			redirect('home');
			return;
		}
		$this->load->model('user/users_model');
		$this->load->model('user/user_details_model','',TRUE);
		$this->load->model('user/user_auth_types_model','',TRUE);
		$this->load->model('user/user_other_details_model','',TRUE);
		$this->load->model('student/student_details_model','',TRUE);
		$this->load->model('student/student_other_details_model','',TRUE);
		$this->load->model('student/student_fee_details_model','',TRUE);
		$this->load->model('student/student_academic_model','',TRUE);
		$this->load->model('user/user_address_model','',TRUE);
		$this->load->model('student/student_type_model','',TRUE);
		$this->load->model('student/student_education_details_model','',TRUE);	

		switch($step)
		{
			case 0: $form = 'profile_pic_status'; $msg='profile picture';break;
			case 1:	$form = 'basic_details_status'; $msg='basic details';break;
			case 2: $form = 'educational_status'; $msg='educational details';break;
		}

		$user = $this->users_model->getUserById($stu_id);
		$date = date("Y-m-d H:i:s",time());

		if($this->input->post('approve'.$step))
		{
			//insert details from pending tables to real tables
			switch($step) {
				case 0:
						$this->db->trans_start();
						$res=$this->user_details_model->getUserById($stu_id);
						$old_photo = ($res == FALSE)?	FALSE:$res->photopath;
						$new_photo = $this->user_details_model->getPendingDetailsById($stu_id)->photopath;

						$this->user_details_model->updateById(array('photopath'=>$new_photo),$stu_id);

						//if old_photo and new_photo have same name ( in case of adding student, data is copied in pending tables too, but one image present) then it should not be deleted.
						if($old_photo && $old_photo != $new_photo)	unlink(APPPATH.'../assets/images/'.$old_photo);

						$basic_status = $this->stu_validation_details_model->getValidationDetailsById($stu_id)->basic_details_status;
						if($basic_status == 'approved')
							$this->user_details_model->deletePendingDetailsWhere(array('id'=>$stu_id));

						$this->db->trans_complete();
						break;

				case 1:
						$this->db->trans_start();

						$details = (array)($this->user_details_model->getPendingDetailsById($stu_id));
						unset($details['photopath']);
						$this->user_details_model->updateById($details,$stu_id);

						$details = (array)($this->user_other_details_model->getPendingDetailsById($stu_id));
						$this->user_other_details_model->updateById($details,$stu_id);

						$details = (array)($this->user_address_model->getPendingDetailsById($stu_id,'present'));
						$this->user_address_model->updatePresentAddrById($details,$stu_id);

						$details = (array)($this->user_address_model->getPendingDetailsById($stu_id,'permanent'));
						$this->user_address_model->updatePermanentAddrById($details,$stu_id);

						$details1 = (array)($this->user_address_model->getPendingDetailsById($stu_id,'correspondence'));
						$details2 = (array)($this->user_address_model->getAddrById($stu_id,'correspondence'));
						if($details1 && !$details2)
						{
							$this->user_address_model->insert($details1);
							$this->user_address_model->deletePendingCorrespondenceAddrById($stu_id);
						}
						else if(!$details1 && $details2)
						{
							$this->user_address_model->delete_record($details2);
						}

						$details = (array)($this->student_details_model->get_pending_student_details_by_id($stu_id));
						$this->student_details_model->update_by_id($details,$stu_id);

						$details = $this->student_other_details_model->get_pending_student_other_details_by_id($stu_id);
						if($details)
							$this->student_other_details_model->update_by_id($details, $stu_id);

						$details = $this->student_fee_details_model->get_pending_stu_fee_details_by_id($stu_id);
						if($details)
							$this->student_fee_details_model->update_by_id($details, $stu_id);

						$details = $this->student_academic_model->get_pending_stu_academic_details_by_id($stu_id);
						if($details)
							$this->student_academic_model->update_by_id($details,$stu_id);

						$profile_pic_status = $this->stu_validation_details_model->getValidationDetailsById($stu_id)->profile_pic_status;
						if($profile_pic_status == 'approved')
							$this->user_details_model->deletePendingDetailsWhere(array('id'=>$stu_id));

						$this->user_other_details_model->deletePendingDetailsWhere(array('id'=>$stu_id));
						$this->user_address_model->deletePendingDetailsWhere(array('id'=>$stu_id));
						$this->student_details_model->deletePendingDetailsWhere(array('admn_no'=>$stu_id));
						$this->student_academic_model->deletePendingDetailsWhere(array('admn_no'=>$stu_id));
						$this->student_other_details_model->deletePendingDetailsWhere(array('admn_no'=>$stu_id));

						$this->db->trans_complete();
						break;

				case 2:

						$this->db->trans_start();
						//delete records from real table
						$this->student_education_details_model->delete_record(array('admn_no'=>$stu_id));
						//move details from pending table to real table
						$this->student_education_details_model->moveDetailsFromPendingById($stu_id);

						$this->db->trans_complete();
						break;
			}

			//pending --> approved
			$this->stu_validation_details_model->updateById(array($form => 'approved'),$stu_id);
			// delete reject details for the same
			$this->stu_validation_details_model->deleteRejectReasonWhere(array('admn_no'=>$stu_id, 'step'=>$step));
			
			//Notify student about the same
			if($user->auth_id == 'stu' && $user->password !='')
			{
				$this->notification->notify($stu_id,'stu', "Validation Request Approved", "Your validation request for ".$msg." have been approved.", "student/view/index/".(($step==0)? $step:($step-1)),"success");
			}
		}
		else if($this->input->post('reject'.$step))
		{
			//pending --> rejected
			$this->stu_validation_details_model->updateById(array($form => 'rejected'),$stu_id);
			// insert or update reject details
			$reason=$this->stu_validation_details_model->getRejectReasonWhere(array('admn_no'=>$stu_id, 'step'=>$step));
			if($reason)
				$this->stu_validation_details_model->updateRejectReason(array('reason'=>$this->input->post('reason'.$step)),array('admn_no'=>$stu_id,'step'=>$step));
			else
				$this->stu_validation_details_model->insertRejectReason(array('admn_no'=>$stu_id,
																				'step'=>$step,
																				'reason'=>$this->input->post('reason'.$step),
																				'created_date'=> $date));
			//Notify Student about the same
			if($user->auth_id == 'stu' && $user->password !='')
			{
				$this->notification->notify($stu_id,'stu', "Validation Request Rejected", "Your validation request for ".$msg." have been rejected. Contact the Academic Section for the same.", "student/view/index/".(($step==0)? $step:($step-1)),"error");
			}

			//Notify Deo of student about the same
			$res = $this->user_auth_types_model->getUserIdByAuthId('acad_da1');
			foreach($res as $row)
			{
				$this->notification->notify($row->id,'acad_da1', "Validation Request Rejected", "Validation request for student ".$stu_id." ".$msg." have been rejected.", "student/validate","error");
			}
		}

		//If all the status are approved
		$this->stu_validation_details_model->deleteValidationDetailsWhere(array('profile_pic_status'=> 'approved',
																				'basic_details_status'=> 'approved',
																				'educational_status'=> 'approved'
																				));
		$stu_validation_details = $this->stu_validation_details_model->getValidationDetailsById($stu_id);
		if($stu_validation_details)
			redirect('student/validate/validate_step/'.$stu_id);
		else
		{
			//for new user
			if($user->auth_id == 'stu' && $user->password =='')
			{
				$pass='p';
				$encode_pass=$this->authorization->strclean($pass);
				$encode_pass=$this->authorization->encode_password($encode_pass,$date);
				$this->users_model->update(array('password' => $encode_pass, 'created_date' => $date), array('id' => $stu_id));
			}
			redirect('student/validate');
		}
	}
}
/* End of file validate.php */
/* Location: mis/application/controllers/student/validate.php */