<?php

class Edc_booking_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function user_auth ($app_num)
	{
		$query = $this->db->query('
			SELECT auth_id
			FROM users
			WHERE id IN (
				SELECT user_id
				FROM edc_registration_details
				WHERE app_num = "'.$app_num.'")
		');
		return $query->row_array();
	}

	function current_tariff() {
		$query = $this->db->query ('
			SELECT id
			FROM edc_tariff
			WHERE wef < CURRENT_TIMESTAMP
			ORDER BY wef DESC
			LIMIT 1
		');

		return intval($query->row_array()['id']);
	}

	function tariff($app_num) {
		$query = $this->db->query('
			SELECT et.*
			FROM edc_tariff AS et,
				edc_registration_details AS erd
			WHERE et.id = erd.tariff
				AND app_num = "'.$app_num.'"
		');

		return $query->row_array();
	}

	function add_tariff($data) {
		$this->db->insert('edc_tariff', $data);
	}

	function insert_edc_registration_details ($data) {
		$this->db->insert('edc_registration_details',$data);
	}

	function requests($status, $auth, $dept_id) {
		$_pending = '';

		//for each auth, if it IS fetching pending, then it can't show those who are SET to cancel OR cancelled for previous authority
		if($status === 'Pending') {
			switch($auth) {
				case 'dsw':
				case 'hod':
				case 'hos': $_pending = ' AND (pce_status IS NULL)';
							break;
				case 'pce_da5':
							$_pending = ' AND (hod_status = "Approved" OR hod_status IS NULL) AND (dsw_status = "Approved" OR dsw_status IS NULL) AND (pce_status IS NULL)';
							break;
				case 'pce': $_pending = ' AND (hod_status = "Approved" OR hod_status IS NULL) AND (dsw_status = "Approved" OR dsw_status IS NULL) AND (ctk_allotment_status = "Approved")';
							break;
			}
		}

		switch($auth) {
			case 'dsw': $query = $this->db->query('SELECT * FROM edc_registration_details WHERE dsw_status = "'.$status.'"'.$_pending.' ORDER BY app_date DESC');
						break;
			case 'hod':
			case 'hos': $query = $this->db->query('SELECT * FROM edc_registration_details WHERE purpose = "Official" AND hod_status = "'.$status.'"'.$_pending.' AND user_id IN (SELECT id FROM user_details WHERE dept_id = "'.$dept_id.'") ORDER BY app_date DESC');
						break;
			case 'pce_da5':
						$query = $this->db->query('SELECT * FROM edc_registration_details WHERE ctk_allotment_status = "'.$status.'"'.$_pending.' ORDER BY app_date DESC');
						break;
			case 'pce': $query = $this->db->query('SELECT * FROM edc_registration_details WHERE pce_status = "'.$status.'"'.$_pending.' ORDER BY app_date DESC');
						break;
		}

		return $query->result_array();
	}

	function new_applications($auth, $dept_id) {
		if($auth == 'hos' || $auth == 'hod')
			$query = $this->db->query('SELECT erd.*
										FROM edc_registration_details AS erd
											INNER JOIN
										    user_details AS ud
										ON erd.user_id = ud.id
										WHERE purpose != "Official"
										AND dept_id = "'.$dept_id.'"
										AND (
											pce_status IS NULL
											OR pce_status = "Pending"
										)');
		else if($auth == 'dsw')
			$query = $this->db->query('SELECT erd.* FROM edc_registration_details AS erd INNER JOIN users ON erd.user_id = users.id WHERE auth_id = "stu" AND (pce_status IS NULL OR pce_status = "Pending") ORDER BY app_date DESC');
		else if($auth == 'pce_da5')
			$query = $this->db->query('SELECT * FROM edc_registration_details WHERE app_num NOT IN (SELECT app_num FROM edc_registration_details WHERE ctk_allotment_status != "") ORDER BY app_date DESC');
		else if($auth == 'pce')
			$query = $this->db->query('SELECT * FROM edc_registration_details WHERE app_num NOT IN (SELECT app_num FROM edc_registration_details WHERE pce_status != "") ORDER BY app_date DESC');

		return $query->result_array();
	}

	function registration_details($app_num) {
		$this->db->WHERE('app_num', $app_num);
		$query = $this->db->get('edc_registration_details');
			return $query->row_array();
	}

	function building() {
		$this->db->WHERE('app_num',NULL);
		$query = $this->db->get('edc_room_details');
		return $query->result_array();
	}

	function update_action($app_num, $auth, $status, $reason) {
		$_null = '';
		if($auth == 'dsw'){
			$col = 'dsw_status';
			$next_user = 'ctk_allotment_status';
			$ts = 'dsw_action_timestamp';
			$_null = ', hod_status = NULL';
		}
		else if($auth == 'hod' || $auth == 'hos'){
			$col = 'hod_status';
			$next_user = 'ctk_allotment_status';
			$ts = 'hod_action_timestamp';
			$_null = ', dsw_status = NULL';
		}
		else if($auth == 'pce_da5'){
			$col = 'ctk_allotment_status';
			$next_user = 'pce_status';
			$ts = 'ctk_action_timestamp';
		}
		else if($auth == 'pce'){
			$col = 'pce_status';
			$ts = 'pce_action_timestamp';
		}
		if($auth != 'pce')
			$this->db->query('
				UPDATE edc_registration_details
				SET '.$col.' = "'.$status.'"'.$_null.', '.$next_user.' = "Pending", '.$ts.' = now(), deny_reason = "'.$reason.'"
				WHERE app_num = "'.$app_num.'"
			');
		else $this->db->query('
			UPDATE edc_registration_details
			SET '.$col.' = "'.$status.'", '.$ts.' = now(), deny_reason = "'.$reason.'"
			WHERE app_num = "'.$app_num.'"
		');
	}

	function cancel($app_num, $col)	{
		$this->db->query('
			UPDATE edc_registration_details
			SET '.$col.' = "Cancel"
			WHERE app_num = "'.$app_num.'"
		');
	}

	function cancel_request($app_num) {
		$this->db->query('
			UPDATE edc_registration_details
			SET hod_status = "Cancelled",
				dsw_status = "Cancelled",
				ctk_allotment_status = "Cancelled",
				pce_status = "Cancelled",
				cancellation_date = now()
			WHERE app_num = "'.$app_num.'"
		');
		$this->db->query('
			DELETE FROM edc_booking_details
			WHERE app_num = "'.$app_num.'"
		');
	}

	function set_cancel_reason($app_num, $reason) {
		$this->db->query('
			UPDATE edc_registration_details
			SET deny_reason = "'.$reason.'"
			WHERE app_num = "'.$app_num.'"
		');
	}

	function set_check_out($app_num) {
		//if the total number of guests that checked in is not equal to the no_of_guests
		//mentioned in application, then keep the check_out to application's check_out
		//but if all guests have checked out then set the max check_out of guests as check_out
		$check_out = $this->db->query('
			SELECT MAX(check_out) AS check_out
			FROM edc_guest_details
			WHERE app_num = "'.$app_num.'"
		')->row_array()['check_out'];

		$this->db->query('
			UPDATE edc_registration_details
			SET check_out = "'.$check_out.'"
			WHERE app_num = "'.$app_num.'"
		');
	}

	function tariff_history() {
		$query = $this->db->query('
			SELECT *
			FROM edc_tariff
			ORDER BY id DESC
		');
		return $query->result_array();
	}

	function pending_booking_details ($user_id) {
		$query = $this->db->query('
			SELECT *
			FROM edc_registration_details
			WHERE user_id="'.$user_id.'"
				AND (
					pce_status = "Pending"
					OR pce_status IS NULL)
			ORDER BY app_date DESC
		');

		return $query->result_array();
	}

	function booking_history ($user_id, $status) {
		if ($status == 'Approved') {
			$this->db->WHERE('user_id',$user_id);
			$this->db->WHERE('pce_status = "'.$status.'" OR pce_status = "Cancel"');
			$query = $this->db->order_by('app_num','DESC')->get('edc_registration_details');
			return $query->result_array();
		}
		else if($status == 'Rejected') {
			$this->db->WHERE('user_id = "'.$user_id.'" AND (hod_status = "Rejected" OR dsw_status = "Rejected" OR pce_status = "Rejected")');
			$query = $this->db->get('edc_registration_details');
			return $query->result_array();
		}
		else if($status == 'Cancelled') {
			$this->db->WHERE('user_id = "'.$user_id.'" AND pce_status = "Cancelled"');
			$query = $this->db->order_by('app_date', 'DESC')->get('edc_registration_details');
			return $query->result_array();
		}
	}

	function allotted_applications() {
		//all those applications which have checkout time greater than current timestamp
		//and those applications which have guests checked in
		//WHETHER NEW GUESTS SHOULD BE WELCOMED AFTER CHECKOUT OF A GUEST AFTER APPLICATION CHECKOUT TIME
		$query = $this->db->query('
			SELECT DISTINCT erd.*
			FROM edc_registration_details AS erd
			WHERE pce_status = "Approved"
				AND erd.check_out > CURRENT_TIMESTAMP
			ORDER BY app_date DESC
		');
		return $query->result_array();
	}

	function no_of_guests($app_num) {
		$query = $this->db->query('
			SELECT no_of_guests
			FROM edc_registration_details
			WHERE app_num = "'.$app_num.'"
		');
		return intval($query->row_array()['no_of_guests']);
	}

	function is_academic($dept_id) {
		$query = $this->db->query('SELECT * FROM departments
									WHERE type="academic"
									AND id="'.$dept_id.'"');
		return count($query->result_array());
	}

	function room_type($room_id) {
		$query = $this->db->query('
			SELECT room_type
			FROM edc_room_details
			WHERE id = "'.$room_id.'"
		');
		return $query->row_array()['room_type'];
	}
	function delete_allotted($app_num)
	{
		$this->db->where('app_num',$app_num);
		$this->db->delete('edc_booking_details');
	}
	function app_num_present($app_num)
	{
		$query = $this->db->get_where('edc_booking_details',array('app_num'=>$app_num));
		if(count ($query->result_array()))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
