<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_request extends MY_Controller
{
	function __construct()
	{

		parent::__construct(array('stu', 'emp', 'hod', 'hos', 'pce', 'dsw', 'pce_da5'));
		date_default_timezone_set('Asia/Kolkata');

		$this->addJS("edc_booking/booking.js");

		$this->load->model ('edc_booking/edc_booking_model');
		$this->load->model('edc_booking/edc_allotment_model');
		$this->load->model('edc_booking/edc_guest_model');
		$this->load->model('user_model');
	}

	function auth_is($auth)
	{
		foreach($this->session->userdata('auth') as $a){
			if($a == $auth)
				return;
		}
		$this->session->set_flashdata('flashWarning', 'You do not have access to that page!');
		redirect('home');
	}

	function head($dept_id)
	{
		//get the hod or hos of dept
		//if dept is academic then hod otherwise hos for nonacademic
		if($this->edc_booking_model->is_academic($dept_id))	//returns true if academic dept
			return 'hod';
		else return 'hos';
	}

	//this function retrieves all the pending, approved, rejected and new applications for the corresponding auth
	function app_list($auth) {
		$this->auth_is($auth);

		if($auth == 'dsw' || $auth == 'pce')
			$dept_id = 'all';
		else $dept_id = $this->session->userdata('dept_id');

		$res = $this->edc_booking_model->requests ("Pending", $auth, $dept_id);
		$total_rows_pending = count($res);
		$data_array_pending = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_pending[$sno]=array();
			$j=1;
			$data_array_pending[$sno][$j++] = $row['app_num'];
			$data_array_pending[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_pending[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_pending[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}

		if($auth == 'hod' || $auth == 'hos' || $auth == 'dsw' || $auth == 'pce')
		{
			$res = $this->edc_booking_model->requests ("Cancel", $auth, $dept_id);
			$total_rows_cancel = count($res);
			$data_array_cancel = array();
			$sno = 1;
			foreach ($res as $row)
			{
				$data_array_cancel[$sno]=array();
				$j=1;
				$data_array_cancel[$sno][$j++] = $row['app_num'];
				$data_array_cancel[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
				$data_array_cancel[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
				$data_array_cancel[$sno][$j++] = $row['no_of_guests'];
				$data_array_cancel[$sno][$j++] = $row['deny_reason'];
				$sno++;
			}
		}

		$res = $this->edc_booking_model->requests ("Approved", $auth, $dept_id);
		$total_rows_approved = count($res);
		$data_array_approved = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_approved[$sno]=array();
			$j=1;
			$data_array_approved[$sno][$j++] = $row['app_num'];
			$data_array_approved[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_approved[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_approved[$sno][$j++] = $row['no_of_guests'];
			$data_array_approved[$sno]['check_out'] = $row['check_out'];
			$data_array_approved[$sno]['pce_status'] = $row['pce_status'];
			$data_array_approved[$sno]['guest_checked_in'] = count($this->edc_guest_model->guests($row['app_num']));
			$sno++;
		}

		$res = $this->edc_booking_model->requests ("Rejected", $auth, $dept_id);	//in case of pce_da5, there won't be any rejected apps
		$total_rows_rejected = count($res);
		$data_array_rejected = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_rejected[$sno]=array();
			$j=1;
			$data_array_rejected[$sno][$j++] = $row['app_num'];
			$data_array_rejected[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_rejected[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_rejected[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}

		$res = $this->edc_booking_model->new_applications ($auth, $dept_id); //function arguments: auth dept_id
		$total_new_apps = count($res);
		$data_array_new_apps = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_new_apps[$sno]=array();
			$j=1;
			$data_array_new_apps[$sno][$j++] = $row['app_num'];
			$data_array_new_apps[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_new_apps[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_new_apps[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}

		$data = array(
			'data_array_pending' => $data_array_pending,
			'total_rows_pending' => $total_rows_pending,
			'data_array_cancel' => $data_array_cancel,
			'total_rows_cancel' => $total_rows_cancel,
			'data_array_approved' => $data_array_approved,
			'total_rows_approved' => $total_rows_approved,
			'data_array_rejected' => $data_array_rejected,
			'total_rows_rejected' => $total_rows_rejected,
			'data_array_new_apps' => $data_array_new_apps,
			'total_new_apps' => $total_new_apps,
			'auth' => $auth
		);

		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/view_requests',$data);
		$this->drawFooter();
	}

	//this function retrieves all the pending, approved and new applications for pce_da5
	function pce_da5_app_list()
	{
		$this->auth_is('pce_da5');
		$res = $this->edc_booking_model->requests ("Pending", 'pce_da5', 'all');
		$total_rows_pending = count($res);
		$data_array_pending = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_pending[$sno]=array();
			$j=1;
			$data_array_pending[$sno][$j++] = $row['app_num'];
			$data_array_pending[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_pending[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_pending[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}

		$res = $this->edc_booking_model->requests ("Approved", 'pce_da5', 'all');
		$total_rows_approved = count($res);
		$data_array_approved = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_approved[$sno]=array();
			$j=1;
			$data_array_approved[$sno][$j++] = $row['app_num'];
			$data_array_approved[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_approved[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_approved[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}
		$res = $this->edc_booking_model->new_applications ('pce_da5', 'all'); //function arguments: auth dept_id
		$total_new_apps = count($res);
		$data_array_new_apps = array();
		$sno = 1;
		foreach ($res as $row)
		{
			$data_array_new_apps[$sno]=array();
			$j=1;
			$data_array_new_apps[$sno][$j++] = $row['app_num'];
			$data_array_new_apps[$sno][$j++] = date('j M Y g:i A', strtotime($row['app_date']));
			$data_array_new_apps[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_new_apps[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}
		$data = array(
			'data_array_pending' => $data_array_pending,
			'total_rows_pending' => $total_rows_pending,
			'data_array_approved' => $data_array_approved,
			'total_rows_approved' => $total_rows_approved,
			'data_array_new_apps' => $data_array_new_apps,
			'total_new_apps' => $total_new_apps,
			'auth' => 'pce_da5'
		);

		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/pce_da5_requests',$data);
		$this->drawFooter();
	}

	//this function displays the application details for respective auths
	//can be accessed by clicking on the application number link in app list
	function details ($app_num, $auth) {
		$this->auth_is($auth);
		$data = $this->edc_booking_model->registration_details($app_num);
		if(!count($data)) {
			$this->session->set_flashdata('flashError', 'Application '.$app_num.' is not valid!');
			redirect('home');
		}
		$data['app_date'] = date('j M Y g:i A', strtotime($data['app_date']));
		$data['user'] = $this->user_model->getNameById($data['user_id']);

		$dept = $this->user_model->getById($data['user_id'])->dept_id; //returns the department of the applicant
		if($this->edc_booking_model->is_academic($dept))	//returns true if academic dept
				$academic = 'yes';
		else $academic = 'no';
		$data['academic'] = $academic;
		$data['auth'] = $auth;
		$data['dept'] = $dept;

		$data['rooms'] = $this->edc_allotment_model->allotted_rooms($app_num);
		$data['no_of_rooms'] = count($data['rooms']);

		//stu, emp, head, dsw, pce, pce_da5 have same view
		//caretaker has different view for looking at details before allotting room
		//caretaker has different view for allotting room
 		$this->drawHeader ("Booking Details");
 		if($auth == 'pce_da5' && $data['ctk_allotment_status'] == 'Pending')
			$this->load->view('edc_booking/pce_da5',$data);
		else $this->load->view('edc_booking/booking_details',$data);
		$this->drawFooter();
	}

	//this function handles the notifications and passes control to details
	function notification_handler($app_num, $auth) {
		$this->auth_is($auth);
		$data = $this->edc_booking_model->registration_details($app_num);

		if($data['pce_status'] == 'Cancelled' ||
			$data['hod_status'] == 'Cancel' ||
			$data['dsw_status'] == 'Cancel' ||
			$data['pce_status'] == 'Cancel')
		{
			$this->session->set_flashdata('flashSuccess', 'This request has been Cancelled by Applicant');
		}
		else if((($auth == 'hod' || $auth == 'hos') && $data['hod_status'] == 'Approved') ||
				($auth == 'dsw' && $data['dsw_status'] == 'Approved') ||
				($auth == 'pce_da5' && $data['ctk_allotment_status'] == 'Approved') ||
				($auth == 'pce' && $data['pce_status'] == 'Approved'))
		{
			$this->session->set_flashdata('flashSuccess', 'This request has been already Approved');
		}
		else if((($auth == 'hod' || $auth == 'hos') && $data['hod_status'] == 'Rejected') ||
				($auth == 'dsw' && $data['dsw_status'] == 'Rejected') ||
				($auth == 'pce_da5' && $data['ctk_allotment_status'] == 'Rejected') ||
				($auth == 'pce' && $data['pce_status'] == 'Rejected'))
		{
			$this->session->set_flashdata('flashSuccess', 'This request has been already Rejected');
		}
		redirect('edc_booking/booking_request/details/'.$app_num.'/'.$auth);
	}

	//this function updates the status of current actor and next actor
	//it also sends notifications to the next actor
	function official_action($app_num, $auth) {
		$this->auth_is($auth);
		$status = $this->input->post ('status');
		$reason = $this->input->post ('reason');

		if ($status == "Approved")
			$reason = "NULL";

		$b_detail = $this->edc_booking_model->registration_details($app_num);
		if($b_detail['check_out'] < date('Y-m-d H:i:s')) {
			$this->edc_booking_model->cancel_request($app_num);
			$this->edc_booking_model->set_cancel_reason($app_num, 'Checkout time expired');
			$this->session->set_flashdata('flashWarning', 'Cannot complete operation, invalid application, checkout time expired');
			redirect('home');
		}

		//official action after user requests cancellation
		if($b_detail['hod_status'] === 'Cancel' ||
			$b_detail['hod_status'] === 'Cancelled' ||
			$b_detail['dsw_status'] === 'Cancel' ||
			$b_detail['dsw_status'] === 'Cancelled' ||
			$b_detail['pce_status'] === 'Cancelled') {
				$this->session->set_flashdata('flashError','Cannot complete action! Applicant has cancelled booking request.');
				redirect('edc_booking/booking_request/app_list/'.$auth);
		}

		$this->edc_booking_model->update_action($app_num, $auth, $status, $reason);

		$this_user = '';
		$to_id = ''; //this is the id to which notification is to be sent
		$to_auth = '';
		$to_msg = '';
		$to_msg_header = 'Approve/Reject Pending Request';
		$user_id = $b_detail['user_id'];
		$user_auth = '';

		//set user_auth
		if($this->user_model->getById($user_id)->auth_id == 'stu')
			$user_auth = 'stu';
		else $user_auth = 'emp';

		//set this_user
		switch($auth)
		{
			case 'hod': $this_user = 'Head of Department';
						break;
			case 'hos': $this_user = 'Head of Section';
						break;
			case 'dsw': $this_user = 'Dean of Students Welfare';
						break;
			case 'pce': $this_user = 'PCE';
		}

		//set to_auth, to_msg;
		switch($auth)
		{
			case 'hod':
			case 'hos':
			case 'dsw': $to_auth = 'pce_da5';
						$to_msg = 'Pending for Room Allotment';
						$to_header = 'EDC Room Allotment Request';
						break;
			case 'pce_da5':
						$to_auth = 'pce';
						$to_msg = 'Pending for your Approval/Rejection';
						$to_header = 'EDC Room Booking Request';
						break;
			case 'pce':	$to_auth = $user_auth;
						$to_msg = 'Approved';
						$to_msg_header = 'EDC Booking Status';
		}

		//set to_id
		if($auth == 'pce')
			$to_id = $user_id;
		else {
			$res = $this->user_model->getUsersByDeptAuth('all', $to_auth); //get the users to whom approval/rejection requests are to be sent
			foreach($res as $row)
				$to_id = $row->id;
		}

		//set academic
		$dept = $this->user_model->getById($b_detail['user_id'])->dept_id; //returns the department of the applicant
		if($this->edc_booking_model->is_academic($dept))	//returns true if academic dept
			$academic = true;
		else
			$academic = false;
		$res = $this->user_model->getUsersByDeptAuth($dept, $this->head($dept)); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$hod_id = $row->id;
		$hod_auth = $this->head($dept);

		$res = $this->user_model->getUsersByDeptAuth('all', 'pce'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$pce = $row->id;

		$res = $this->user_model->getUsersByDeptAuth('all', 'pce_da5'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$pce_da5_id = $row->id;

		$res = $this->user_model->getUsersByDeptAuth('all', 'dsw'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$dsw_id = $row->id;
		if($status != "Approved")
		{					
			if($this->edc_booking_model->app_num_present($app_num))
			{
				$this->edc_booking_model->delete_allotted($app_num);
			}
		}
		if ($status == "Approved")
			//verify the notification link
			$this->notification->notify ($to_id, $to_auth, $to_msg_header, "EDC Room Booking Request (Application No. : ".$app_num." ) is ".$to_msg.".", "edc_booking/booking_request/notification_handler/".$app_num."/".$to_auth, "");
		else
		{
			//if current user is dsw|hod|hos, send notification to user only
			//if current user is pce, send notification to user, hod/hos, caretaker
			if($auth == 'hod' || $auth == 'hos' || $auth == 'dsw')
				$this->notification->notify ($user_id, $user_auth, "EDC Booking Status", "Your Request for EDC Room Allotment (Application No. : ".$app_num." ) has been Rejected by ".$this_user.".", "edc_booking/booking_request/details/".$app_num.'/'.$user_auth, "");
			else if($auth == 'pce')
			{
				$users = array(
					array(
						'id' => $user_id, 'auth' => $user_auth
					),
					array(
						'id' => $pce_da5_id, 'auth' => 'pce_da5'
					),
					/*array(
						'id' => $to_id, 'auth' => 'hod' //this to_id is set to user_id for pce
					),*/
					array(
						'id' => $hod_id, 'auth' => $hod_auth
					),
					array(
						'id' => $dsw_id, 'auth' => 'dsw'
					)
				);

				//if user is employee, then send notification to emp, hos/hod/head, caretaker
				//if user is student, then send notification to stu, dsw, caretaker
				
				
				foreach($users as $user)
				{
					//if invalid combination then continue
					//(emp, dsw), (stu, hod/hos/head), (emp[academic], hos/head), (emp[non-academic], hod)
					if(($user_auth == 'emp' && $user['auth'] == 'dsw') || ($user_auth == 'stu' && ($user['auth'] == 'hod' || $user['auth'] == 'hos')) || ($academic && $user['auth'] == 'hos') || (!$academic && $user['auth'] == 'hod'))
						continue;
					$this->notification->notify ($user['id'], $user['auth'], "EDC Booking Status", "Your Request for EDC Room Allotment (Application No. : ".$app_num.") has been Rejected by ".$this_user.".", "edc_booking/booking_request/details/".$app_num."/".$user['auth'], "");
				}
			}
		}
		$this->session->set_flashdata('flashSuccess','Request has been successfully '.$status.'.');
		redirect('edc_booking/booking_request/app_list/'.$auth);
	}

	//this function handles notifications for cancellation
	function cancel($app_num, $auth) {
		$this->auth_is($auth);
		$data= $this->edc_booking_model->registration_details($app_num);
		$data['app_date'] = date('j M Y g:i A', strtotime($data['app_date']));
		$data['user'] = $this->user_model->getNameById($data['user_id']);

		$dept = $this->user_model->getById($data['user_id'])->dept_id; //returns the department of the applicant
		if($this->edc_booking_model->is_academic($dept))	//returns true if academic dept
				$academic = 'yes';
		else $academic = 'no';
		$data['academic'] = $academic;
		$data['auth'] = $auth;

		$data['rooms'] = $this->edc_allotment_model->allotted_rooms($app_num);
		$data['no_of_rooms'] = count($data['rooms']);

		$this->drawHeader ("Booking Details");
		$this->load->view('edc_booking/booking_details', $data);
		$this->drawFooter();
	}

	function cancellation($app_num, $auth) {
		$this->auth_is($auth);
		$data = $this->edc_booking_model->registration_details($app_num);
		if($this->edc_booking_model->app_num_present($app_num))
			{
				$this->edc_booking_model->delete_allotted($app_num);
			}
		//cancellation after official rejection
		if($data['hod_status'] === 'Rejected' ||
			$data['dsw_status'] === 'Rejected' ||
			$data['pce_status'] === 'Rejected') {
				$this->session->set_flashdata('flashError', 'Error! Application has been rejected already.');
				if($auth === 'stu' || $auth === 'emp')
					redirect('edc_booking/booking/track_status');
				else redirect('edc_booking/booking_request/app_list/'.$auth);
		}

		//user cancellation after pce forced cancellation
		if(($auth === 'stu' || $auth === 'emp') && $data['pce_status'] === 'Cancelled') {
			$this->session->set_flashdata('flashError', 'Error! Application has been rejected already.');
			redirect('edc_booking/booking/track_status');
		}

		if($auth == 'stu' || $auth == 'emp' || $auth == 'pce') {
			$cancel_reason = $this->input->post('cancel_reason');
			if($cancel_reason)
				$this->edc_booking_model->set_cancel_reason($app_num, $cancel_reason);
		}

		$dept = $this->user_model->getById($data['user_id'])->dept_id; //returns the department of the applicant
		$to_auth = $this->head($dept);

		$user_auth = $this->edc_booking_model->user_auth($app_num)['auth_id'];
		$res = $this->user_model->getUsersByDeptAuth($dept, $to_auth); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$to_id = $row->id;

		$res = $this->user_model->getUsersByDeptAuth('all', 'pce'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$pce = $row->id;

		$res = $this->user_model->getUsersByDeptAuth('all', 'pce_da5'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$pce_da5_id = $row->id;

		$res = $this->user_model->getUsersByDeptAuth('all', 'dsw'); //get the users to whom approval/rejection requests are to be sent
		foreach($res as $row)
			$to_id = $row->id;

		//button will only be shown if request is not cancelled, ie. checkin date is not set to 1970
		//if so, when viewing the application details, it'll show cancelled by Applicant and the reason
		if($auth == 'emp') //applicant can communicate with hod/hos, dsw, ctk only (in case of emp personal)
		{
			//if the first recepient of notification after registration is pending, then simply drop the request
			if($data['purpose'] == 'Official' && $data['hod_status'] == 'Pending') {
				$this->edc_booking_model->cancel_request($app_num); //function arguments: app_num, status column to be set to cancelled
				$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation (Application No. : '.$app_num.') has been Approved successfully.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);
				$this->session->set_flashdata('flashSuccess', 'Booking Cancellation has been approved successfully.');
				redirect('edc_booking/booking/history');
			}
			else if($data['purpose'] == 'Personal' && $data['ctk_allotment_status'] == 'Pending') {
				$this->edc_booking_model->cancel_request($app_num);
				$this->session->set_flashdata('flashSuccess', 'Booking Cancellation has been approved successfully.');
				redirect('edc_booking/booking/history');
			}
			//if the first recepient of notification is HOD, ask him to approve cancellation
			else if($data['purpose'] == 'Official' && $data['hod_status'] == 'Approved') {
				$this->edc_booking_model->cancel($app_num, 'hod_status');
				$this->notification->notify($to_id, $to_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation for Application No. : '.$app_num, 'edc_booking/booking_request/cancel/'.$app_num.'/'.$to_auth);
			}
			//if the first recepient of notification is CTK, but pce has not yet approved, then drop it and send notification to caretaker
			else if($data['ctk_allotment_status'] == 'Approved' && $data['pce_status'] == 'Pending') {
				$this->edc_booking_model->cancel_request($app_num);
				$this->notification->notify($pce_da5_id, 'pce_da5', 'EDC Booking Cancellation', 'Request for EDC Room Booking (Application No. : '.$app_num.') has been Cancelled by Applicant', 'edc_booking/booking_request/details/'.$app_num.'/pce_da5');
				$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation (Application No. : '.$app_num.') has been Approved successfully.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);
				$this->session->set_flashdata('flashSuccess', 'Booking Cancellation has been approved successfully.');
				redirect('edc_booking/booking/history');
			}
			else if($data['pce_status'] == 'Approved') {
				$this->edc_booking_model->cancel($app_num, 'pce_status');
				$this->notification->notify($pce, 'pce', 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation for Application No. : '.$app_num, 'edc_booking/booking_request/cancel/'.$app_num.'/pce');
			}

			$this->session->set_flashdata('flashSuccess', 'Booking Cancellation Request has been successfully sent.');
			redirect('edc_booking/booking/track_status');
		}
		else if($auth == 'stu') {
			if($data['dsw_status'] == 'Pending') {
				$this->edc_booking_model->cancel_request($app_num);
				$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation (Application No. : '.$app_num.') has been Approved successfully.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);
				$this->session->set_flashdata('flashSuccess', 'Booking Cancellation has been approved successfully.');
				//redirect('edc_booking/booking/history');
			}
			else if($data['dsw_status'] == 'Approved') {
				$this->edc_booking_model->cancel($app_num, 'dsw_status');
				$this->notification->notify($to_id, 'dsw', 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation for Application No. : '.$app_num, 'edc_booking/booking_request/cancel/'.$app_num.'/dsw');
			}

			$this->session->set_flashdata('flashSuccess', 'Booking Cancellation Request has been successfully sent.');
			redirect('edc_booking/booking/track_status');
		}
		else if($auth == 'hod' || $auth == 'hos'|| $auth == 'dsw') {
			//if after approval of cancellation by hod/hos or dsw, ctk is yet to allot rooms, then drop request
			if($data['ctk_allotment_status'] == 'Pending'){
				$this->edc_booking_model->cancel_request($app_num);
				$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation (Application No. : '.$app_num.') has been Approved successfully.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);
			}
			//if after approval of cancellation by hod/hos or dsw, pce is pending then drop request and send notification to ctk
			else if($data['pce_status'] == 'Pending') {
				$this->edc_booking_model->cancel_request($app_num);
				$this->notification->notify($pce_da5_id, 'pce_da5', 'EDC Booking Cancellation', 'Request for EDC Room Booking (Application No. : '.$app_num.') has been Cancelled by Applicant', 'edc_booking/booking_request/details/'.$app_num.'/pce_da5');
				$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation (Application No. : '.$app_num.') has been Approved successfully.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);
			}
			//if after approval of cancellation by hod/hos or dsw, pce is approved then send cancellation request to pce
			else if($data['pce_status'] == 'Approved') {
				$res = $this->user_model->getUsersByDeptAuth('all', 'pce'); //get the users to whom approval/rejection requests are to be sent
				foreach($res as $row)
					$to_id = $row->id;
				$this->edc_booking_model->cancel($app_num, 'pce_status');
				$this->notification->notify($to_id, 'pce', 'EDC Booking Cancellation', 'Request for EDC Room Booking Cancellation for Application No. : '.$app_num, 'edc_booking/booking_request/cancel/'.$app_num.'/pce');
			}

			$this->session->set_flashdata('flashSuccess', 'Booking Cancellation Request has been Approved.');
			redirect('edc_booking/booking_request/app_list/'.$auth);
		}
		else if($auth == 'pce') {
			$this->edc_booking_model->cancel_request($app_num);
			//to hod/hos/dsw
			if($data['purpose'] == 'Official')
				$this->notification->notify($to_id, $to_auth, 'EDC Booking Cancellation', 'EDC Room Booking (Application No. : '.$app_num.') has been Cancelled by PCE.', 'edc_booking/booking_request/cancel/'.$app_num.'/'.$to_auth);
			//to pce_da5
			$this->notification->notify($pce_da5_id, 'pce_da5', 'EDC Booking Cancellation', 'EDC Room Booking (Application No. : '.$app_num.') has been Cancelled by PCE.', 'edc_booking/booking_request/details/'.$app_num.'/pce_da5');
			//to user
			$this->notification->notify($data['user_id'], $user_auth, 'EDC Booking Cancellation', 'EDC Room Booking (Application No. : '.$app_num.') has been Cancelled by PCE.', 'edc_booking/booking_request/details/'.$app_num.'/'.$user_auth);

			$this->session->set_flashdata('flashSuccess', 'Booking Cancellation Request has been Approved.');
			redirect('edc_booking/booking_request/app_list/'.$auth);
		}
	}
}
