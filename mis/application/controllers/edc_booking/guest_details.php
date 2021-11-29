<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guest_details extends MY_Controller {
	function __construct()
	{
		parent::__construct(array('pce_da5', 'pce'));
		date_default_timezone_set('Asia/Kolkata');

		$this->load->model ('edc_booking/edc_booking_model', '', TRUE);
		$this->load->model('user_model');
		$this->load->model('edc_booking/edc_allotment_model');
		$this->load->model('edc_booking/edc_guest_model');
	}

	function auth_is($auth) {
		foreach($this->session->userdata('auth') as $a) {
			if($a == $auth)
				return;
		}
		$this->session->set_flashdata('flashWarning', 'You do not have access to that page!');
		redirect('home');
	}

	function index() {
		$this->auth_is('pce_da5');
		$res = $this->edc_booking_model->allotted_applications();

		$total_rows_approved = count($res);
		$data_array_approved = array();

		$sno = 0;
		foreach ($res as $row) {
			$data_array_approved[$sno]=array();
			$j=1;
			$data_array_approved[$sno][$j++] = $row['app_num'];
			$data_array_approved[$sno][$j++] = $this->user_model->getNameById($row['user_id']);
			$data_array_approved[$sno][$j++] = date('j M Y g:i A', strtotime($row['check_in']));
			$data_array_approved[$sno][$j++] = date('j M Y g:i A', strtotime($row['check_out']));
			$data_array_approved[$sno][$j++] = $row['no_of_guests'];
			$sno++;
		}
		$data['data_array_approved'] = $data_array_approved;
		$data['total_rows_approved'] = $total_rows_approved;

		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/show_allotted_applications', $data);
		$this->drawFooter();
	}

	function insert_guest($app_num, $type) {
		$this->auth_is('pce_da5');

		$data = array('app_num' => $app_num,
						'name'=> filter_var($this->input->post('name'), FILTER_SANITIZE_STRING),
					  'designation'=> filter_var($this->input->post('designation'), FILTER_SANITIZE_STRING),
					  'address'=> trim($this->input->post('address')),
					  'gender'=> $this->input->post('gender'),
					  'contact'=> filter_var($this->input->post('contact'), FILTER_SANITIZE_NUMBER_INT),
					  'email'=> filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL),
						'group_count' => $this->input->post('group_count'),
						'check_in' => date('Y-m-d H:i:s'));

		$group_count = intval($data['group_count']);
		$b_detail = $this->edc_booking_model->registration_details($data['app_num']);
  	//checking in before check in date is error
		//this restriction is put because if any applicant checks in before his appointed time
		//then there might be clashes with other bookings
		if(time() < strtotime($b_detail['check_in'])) {
			$this->session->set_flashdata('flashError', 'Checking In before Check In DateTime not allowed');
			redirect('edc_booking/guest_details');
		}
		//get identity card
		$upload = $this->upload_file('identity_card' , $data['app_num']);
		if($upload)
			$data['identity_card'] = $upload['file_name'];

		if($type === 'group')
			$room_ids = $this->input->post('rooms'); //getting only room id
		else if($type === 'individual')
			$room_ids = array(0 => $this->input->post('rooms'));

		//for group, if guest count is more than available capacity of rooms, show error
		//if guest count is lower than selected rooms, distribute the guests appropriately
		$rooms = $this->edc_allotment_model->room_group_details($room_ids);
		$free_space = 0;
		foreach($rooms as $room) {
			if($room['room_type'] === 'AC Suite') //AC Suite is already not selected if it is full, but still for more security
				$free_space += 1 - $room['checked'];
			else $free_space += 2 - $room['checked'];
		}
		if($free_space < $data['group_count']) {
			$this->session->set_flashdata('flashError', 'Not enough space available for given number of guests!');
			redirect('edc_booking/guest_details/edit/'.$app_num);
		}
		//distribute rooms to guests (This algo allots suite ac to guests first, so bill will be higher in this case
		//if lower possible bill is preferred, then, first all double rooms have to be filled)
			//ALGO FAVOURING SUITE AC since number of selected rooms is more than guest count, so comfort preferred (assumed)
			//first allot all the suite ac
			//for all those rooms which are left in rooms array, first add 1 to all
			//if guest count still left, add 1 to all rooms left with free space
		foreach($rooms as $key => $val) {
			if($rooms[$key]['room_type'] === 'AC Suite') {
				$rooms[$key]['checked'] = 1;
				if(!--$group_count)
					break;
			}
		}
		//Now for double ac rooms
		while($group_count) { 							//rooms are filled incrementally by 1 so as to fill as many rooms as possible and till all guests are allotted room
			foreach($rooms as $key => $val) { //by doing this, we don't save rooms but give as much room as possible (increased revenue)
				if($rooms[$key]['room_type'] === 'Double Bedded AC' && $rooms[$key]['checked'] < 2) {
					$rooms[$key]['checked'] += 1;
					if(!--$group_count)
						break;
				}
			}
		}
		//add details to database
		$this->edc_guest_model->insert_guest_details($data);
		$guest_id = $this->edc_guest_model->guest_id($data);
		$this->edc_guest_model->insert_guest_rooms($guest_id, $room_ids);
		$this->edc_guest_model->room_checkin($rooms);

		$this->session->set_flashdata('flashSuccess','Check In Successful.');
		redirect('edc_booking/guest_details/edit/'.$app_num);
	}

	function edit($app_num='') { //send current checked in data, user count, room availability with free space for each room
		$this->auth_is('pce_da5');
		$data = array();
		$data['app_detail'] = $this->edc_booking_model->registration_details($app_num);
 		$data['rooms'] = $this->edc_allotment_model->allotted_rooms($app_num);

		//get guest details
		//get guest rooms details
		$data['guest_details'] = $this->edc_guest_model->guests($app_num);
		foreach($data['guest_details'] as $key => $val)
			 $data['guest_details'][$key]['rooms'] = $this->edc_guest_model->guest_rooms($val['id']);
		//to show guest number in drop down menu for number of guests, need to find out how many guests are entered for the app_num
		//depending on how many guests are left to checkin, it'll show the number of guests for group tab
		$data['guest_count'] = $this->edc_guest_model->guest_count($app_num); //this count is not the guest count of the guests that are entered in database. it is the total number of guests staying (groups + individual)
		$data['checked_in_guest_count'] = $this->edc_guest_model->checked_in_guest_count($app_num);

		$this->addJS('edc_booking/guest_management.js');
		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/add_checkin_checkout', $data);
		$this->drawFooter();
	}

	function test() {
		$app_details = $this->edc_booking_model->registration_details('EDC1465276081');
		$data = $app_details;
		$data['guest_id'] = 28;
		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/check_out_confirmation', $data);
		$this->drawFooter();
	}

	function confirm_checkout($guest_id) {
		$this->auth_is('pce_da5');

		$guest = $this->edc_guest_model->guest_details($guest_id);
		$app_details = $this->edc_booking_model->registration_details($guest['app_num']);
		$this->session->set_flashdata('flashWarning', 'Since current time is past the checkout time mentioned in application, no further guest will be entertained if there is no remaining checked in guest for this application');
		$data = $app_details;
		$data['guest_id'] = $guest_id;
		if($app_details['check_out'] < date('Y-m-d H:i:s')) {
			$this->drawHeader('Executive Development Center');
			$this->load->view('edc_booking/check_out_confirmation', $data);
			$this->drawFooter();
		}
		else $this->add_checkout($guest_id);
	}

	function add_checkout($guest_id) {
		$this->auth_is('pce_da5');
		//since the guest can be denied checkin to a particular room until that room is free
		//or the ctk can shift guests in that room with passed checkout time to another room or any other arrangement
		//no additional stuff is added currently
		$this->edc_guest_model->checkout($guest_id); //for updating checkout time
		$guest = $this->edc_guest_model->guest_details($guest_id);
		$this->edc_guest_model->room_checkout($guest); //for freeing rooms
		$guest_count = $this->edc_guest_model->guest_count($guest['app_num']);
		if($app_details['check_out'] < date('Y-m-d H:i:s') || $no_of_guests === $guest_count)
			$this->edc_booking_model->set_check_out($guest['app_num']); //for updating application checkout time
		$this->session->set_flashdata('flashSuccess','Check Out Successfull.');
		redirect('edc_booking/guest_details/edit/'.$guest['app_num']);
	}

	function share_days($guest, $room) {
		$daytime = 86400;
		$share_days = 0;
		//since no two guests other than our guest will be staying at the same room at same time
		$previous_checkout_day = 0;
		//if this room has been shared by 2 or more guests already, no need to pay since
		//the cost of booking this room for this application has already been done
		$room_paid = $this->edc_guest_model->room_checkin_paid($guest, $room);
		if($room_paid)
			$guest['check_in'] = date('Y-m-d H:i:s', strtotime(explode(' ', $guest['check_in'])[0]) + $daytime);
		//only if checkin checkout of guest is valid, can be invalid if the guest checked in after 2 guests
		//and is leaving on same day, ex. you check in at 10am and leave at 5pm already sharing with someone
		//and someone already checked out before 10 am who also shared before you.
		//if that room has been paid for full by someone else, its free for subsequent guests coming under same app
		if($guest['check_in'] < $guest['check_out']) {
			foreach($room['guests'] as $share_guest) {
				if($share_guest['check_in'] < $guest['check_in']) //if guest already staying there, cost calculated from our guest's checkin day
					$share_guest['check_in'] = $guest['check_in'];
				if(!$share_guest['check_out'] || $share_guest['check_out'] > $guest['check_out']) //if guest staying after our guest, cost calculated upto our guest's checkout day
					$share_guest['check_out'] = $guest['check_out'];
				//if a new guest came in a shared day, calculate his shared from next day onwards since the day is already shared
				if(strtotime(explode(' ', $share_guest['check_in'])[0]) === $previous_checkout_day)
				//if a checkout has been made in that day (remember that our guest is already staying) then calculate cost starting next day
					$share_guest['check_in'] = date('Y-m-d H:i:s', strtotime(explode(' ', $share_guest['check_in'])[0]) + $daytime);
				if($share_guest['check_in'] < $share_guest['check_out']) { //if after conditions above, the guest checkin checkout still valid, calculate shared days
					$days = ((strtotime(explode(' ', $share_guest['check_out'])[0])  - strtotime(explode(' ', $share_guest['check_in'])[0])) / $daytime) + 1;
					$share_days += $days;
					$previous_checkout_day = strtotime(explode(' ', $share_guest['check_out'])[0]);
				}
			}
		}
		return $share_days;
	}
	function calculate_bill_per_room($guest_id) {
		$daytime = 86400;
		//generates bill for a guest/app both group and individual
			// to calculate total bill, use algorithm:
			// for any guest, he will pay the cost of suite rooms all by himself
			// for shared rooms, he will pay for full for those days when room was used by him alone (not shared)
			// and he will pay half the price for those days when room was used in shared mode.
		$guest = $this->edc_guest_model->guest_details($guest_id);
		$app_detail = $this->edc_booking_model->registration_details($guest['app_num']);
		$guest_rooms = $this->edc_guest_model->guest_rooms($guest_id);
		$shared_rooms = $this->edc_guest_model->shared_rooms($guest); //will contain details of rooms and guests which shared the double bedded room ordered by checkin
		$tariff = $this->edc_booking_model->tariff($guest['app_num']);
		//for non shared room
		$room_map = array();
		foreach($guest_rooms as $room) {
			if($room['room_type'] === 'AC Suite') {
				if($app_detail['purpose'] === 'Personal')
					$rate = intval($tariff['suite_personal']);
				else $rate = intval($tariff['suite_official']);
			}
			else {//calculate cost for all double rooms, it'll be overridden during shared charge calculation
				if($app_detail['purpose'] === 'Personal')
					$rate = intval($tariff['double_personal']);
				else $rate = intval($tariff['double_official']);
			}

			$room_map[$room['id']] = $room;
			$room_map[$room['id']]['tariff'] = $rate;
			$days = ((strtotime(explode(' ', $guest['check_out'])[0]) - strtotime(explode(' ', $guest['check_in'])[0])) / $daytime) + 1;
			$room_map[$room['id']]['charge'] = $rate * $days; //86400 is value of a day in time
		}
		//for double
		foreach($shared_rooms as $room) {
			if($app_detail['purpose'] === 'Personal')
				$rate = intval($tariff['double_personal']);
			else $rate = intval($tariff['double_official']);
			$days = ((strtotime(explode(' ', $guest['check_out'])[0]) - strtotime(explode(' ', $guest['check_in'])[0])) / $daytime) + 1;
			//if the first day had been paid for exclude the first day
			$room_paid = $this->edc_guest_model->room_checkin_paid($guest, $room);
			if($room_paid)
				$days--;
			$share_days = $this->share_days($guest, $room);
			echo $days.' '.$share_days;
			$room_map[$room['id']]['charge'] = ($rate * (2 * $days - $share_days)) / 2; //simplified form of nonshared charge + shared charge
		}

		$i = 0;
		$rooms = array();
		foreach($room_map as $room)
			$rooms[$i++] = $room;
		return $rooms;
	}

	function bill_total_sum($rooms) {
			$total_sum = 0;
			foreach($rooms as $room)
				$total_sum += $room['charge'];
			return $total_sum;
	}

	function generate_bill($guest_id) {
		$this->auth_is('pce_da5');

		$guest_details = $this->edc_guest_model->guest_details($guest_id);
		$guest_details['rooms'] = $this->calculate_bill_per_room($guest_id);
		$guest_details['total_sum'] = $this->bill_total_sum($guest_details['rooms']);
		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/bill', $guest_details);
		$this->drawFooter();
	}	

	function generate_receipt($guest_id) {
		$this->auth_is('pce_da5');

		$guest_details = $this->edc_guest_model->guest_details($guest_id);
		$guest_details['rooms'] = $this->calculate_bill_per_room($guest_id);
		$guest_details['total_sum'] = $this->bill_total_sum($guest_details['rooms']);
		$this->edc_guest_model->set_paid_data($guest_id, $guest_details['total_sum']);
		$this->drawHeader('Executive Development Center');
		$this->load->view('edc_booking/bill_receipt', $guest_details);
		$this->drawFooter();
	}

 	function upload_file($name ='', $app_num='') {
		$this->auth_is('pce_da5');
		$config['upload_path'] = 'assets/files/edc_booking/'.$this->edc_booking_model->registration_details($app_num)['user_id'].'/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']  = '1024';

		if(isset($_FILES[$name]['name'])) {
      if($_FILES[$name]['name'] == "")
    		$filename = "";
      else {
        $filename=$this->security->sanitize_filename(strtolower($_FILES[$name]['name']));
        $ext =  strrchr( $filename, '.' ); // Get the extension from the filename.
        $filename='IDFILE_'.$app_num.$ext;
    	}
    }
    else {
    	$this->session->set_flashdata('flashError','ERROR: File Name not set.');
    	redirect('edc_booking/guest_details/');
    }

		$config['file_name'] = $filename;

		if(!is_dir($config['upload_path']))	//create the folder if it's not already exists
			mkdir($config['upload_path'],0777,TRUE);

		$this->load->library('upload', $config);

		if(!$this->upload->do_multi_upload($name)) { //do_multi_upload is back compatible with do_upload
			$this->session->set_flashdata('flashError',$this->upload->display_errors('',''));
			redirect('edc_booking/guest_details/');
			return FALSE;
		}
		else {
			$upload_data = $this->upload->data();
			return $upload_data;
		}
	}

	function search($auth) {
		$this->drawHeader('EDC Guest History Search');
		$this->load->view('edc_booking/room_booking_history', array('auth' => $auth));
		$this->drawFooter();
	}

	function display_booking_history() {
		$data = array('name' => filter_var($this->input->post('name'), FILTER_SANITIZE_STRING),
					  'rooms' => $this->input->post('checkbox_rooms'));

		$data['check_in'] = date("Y-m-d", strtotime($this->input->post('check_in')));
		$data['check_out'] = date("Y-m-d", strtotime($this->input->post('check_out')));
		$data['date'] = date("Y-m-d", strtotime($this->input->post('date')));

		$history['auth'] = $this->input->post('auth');
		$history['guests'] = $this->edc_guest_model->room_occupance_history($data); //guest details for unique guest entries

		foreach($history['guests'] as $key => $val) {
			$history['guests'][$key]['rooms'] = $this->edc_guest_model->guest_rooms($val['id']);
		}

		$this->drawHeader('EDC Guest History Details');
		$this->load->view('edc_booking/room_booking_history_view', $history);
		$this->drawFooter();
	}

	function room_planning($building) {
		if($this->edc_allotment_model->no_of_rooms($building) === 1) {
			$this->load->view('edc_booking/no_room_data.php');
		}
		else {
			$result_uavail_rooms = $this->edc_allotment_model->check_unavail(date('Y-m-d H:i:s'),date('Y-m-d H:i:s'));
			$floor_array = $this->edc_allotment_model->floors($building);

			$flr = 1;
			foreach($floor_array as $floor)
			{
				$temp_query = $this->edc_allotment_model->rooms($building,$floor['floor']);
				$result_floor_wise[$flr][0] = $temp_query;
				$result_floor_wise[$flr++][1] = $floor['floor'];
			}

			$data_array = array();
			$i = 0;
			foreach($result_floor_wise as $floor)
			{
				$sno=1;
				$data_array[$i][0] = $floor[1];
				foreach($floor[0] as $row)
				{
					$flag=0;
					foreach($result_uavail_rooms as $room_unavailable)
					{
						if($row['id']==$room_unavailable['room_id'])
							$flag = 1;
					}
					$data_array[$i][$sno][0] = $row['id'];
					$data_array[$i][$sno][1] = $row['room_no'];
					$data_array[$i][$sno][2] = $row['room_type'];
					if($flag==0)
						$data_array[$i][$sno][3] = 1;
					else
						$data_array[$i][$sno][3] = 0;
					$data_array[$i][$sno][4] = $row['blocked'];
					$data_array[$i][$sno++][5] = $row['remark'];
				}
				$i++;
			}

			$data['building'] = $building;
			$data['floor_room_array'] = $data_array;
			$data['room_array'] = $this->edc_allotment_model->room_types();
			$this->load->view('edc_booking/room_checkbox',$data);
		}
	}

	function guest_info($user_id, $guest_id) {
		$guest = $this->edc_guest_model->guest_details($guest_id);
		$guest['user_id'] = $user_id;
		$this->drawHeader("Executive Development Center");
		$this->load->view('edc_booking/guest_info', $guest);
		$this->drawFooter();
	}
}
