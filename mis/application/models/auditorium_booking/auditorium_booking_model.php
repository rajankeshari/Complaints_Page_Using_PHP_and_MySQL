<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'auditorium_constants.php';
class Auditorium_booking_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	public function getAllAuditoriums(){
		$q = "SELECT details.auditorium_id, details.name as aud_name, ".
			"details.incharge_id, ud.first_name, ud.middle_name, ud.last_name, ud.dept_id, dept.name as dept_name ".
			"FROM ".Auditorium_constants::$DETAILS_TABLE." as details ".
			"INNER JOIN user_details as ud ".
			"ON details.incharge_id = ud.id ".
			"INNER JOIN departments as dept ".
			"ON ud.dept_id = dept.id ".
			"WHERE 1";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getAuditoriumDetails($aud_id){
		$q = "SELECT details.auditorium_id, details.name as aud_name, ".
			"details.incharge_id, ud.first_name, ud.middle_name, ud.last_name, ud.dept_id, dept.name as dept_name ".
			"FROM ".Auditorium_constants::$DETAILS_TABLE." as details ".
			"INNER JOIN user_details as ud ".
			"ON details.incharge_id = ud.id ".
			"INNER JOIN departments as dept ".
			"ON ud.dept_id = dept.id ".
			"WHERE details.auditorium_id = '$aud_id'";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getAllDepartments(){
		$q = "SELECT id, name FROM departments";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getAllDesignations(){
		$dept_id = $this->input->post('curr_dept');
		$q = "SELECT DISTINCT designations.id, designations.name ".
			"FROM designations INNER JOIN user_details ".
			"INNER JOIN emp_basic_details ".
			"ON designations.id = emp_basic_details.designation ".
			"AND user_details.id = emp_basic_details.emp_no where dept_id LIKE '$dept_id' ORDER BY designations.name";
		// $q = "SELECT id, name FROM designations";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getDesignationNameById($desig_id){
		$q = "SELECT name FROM designations WHERE id = '$desig_id' ";
		$res = $this->db->query($q)->result_array();
		return $res[0]['name'];
	}

	public function getUserDesignation($user_id){
		$q = "SELECT emp_no, designation FROM emp_basic_details WHERE emp_no = '$user_id'";
		$res = $this->db->query($q)->result_array();
		array_push($res, array('desig_name' => $this->getDesignationNameById($res[0]['designation'])));

		return $res;
	}

	public function getAllEmpOfDesigType($incharge_dept, $desig){
		$q = "SELECT ebd.emp_no, ebd.designation, ud.first_name, ud.middle_name, ud.last_name ".
			"FROM emp_basic_details AS ebd ".
			"INNER JOIN user_details AS ud ".
			"ON ebd.emp_no = ud.id ".
			"WHERE ud.dept_id = '$incharge_dept' AND ebd.designation = '$desig'";

		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getUserNameById($id){
		$q = "SELECT first_name, middle_name, last_name FROM user_details WHERE id = '$id'";
		$res = $this->db->query($q)->result_array();
		$name = $res[0]['first_name'];
		if (strlen($res[0]['middle_name'])) $name .= " ".$res[0]['middle_name'];
		if (strlen($res[0]['last_name'])) $name .=  " ".$res[0]['last_name'];
		return $name; 
	}

	public function editDetailsOfAuditoriumId($aud_name, $incharge_id, $aud_id){
		$q = "UPDATE ".Auditorium_constants::$DETAILS_TABLE." SET name = '$aud_name', incharge_id = '$incharge_id' ".
			"WHERE auditorium_id = '$aud_id'";
		$this->db->query($q);
	}

	public function removeAuditorium($aud_id){
		$q = "DELETE FROM ".Auditorium_constants::$DETAILS_TABLE. " WHERE auditorium_id = '$aud_id'";
		$this->db->query($q);
	}

	public function insertNewAuditorium($aud_name, $incharge_id){
		$q = "INSERT INTO ".Auditorium_constants::$DETAILS_TABLE.
			" (name, incharge_id) VALUES ('$aud_name', '$incharge_id')";
		$this->db->query($q);
	}

	public function getAuditoriumNames(){
		$q = "SELECT auditorium_id, name AS aud_name, incharge_id ".
			"FROM ".Auditorium_constants::$DETAILS_TABLE. " WHERE 1";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getDeptFromCurrentBooking(){
		$q = "SELECT departments.name AS dept_name, departments.id ".
			"FROM departments WHERE departments.id IN ".
			"( SELECT DISTINCT user_details.dept_id FROM user_details ".
				"INNER JOIN ".Auditorium_constants::$STATUS_TABLE. " as t ON ".
				"t.applier_id = user_details.id)";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getNameFromCurrentBooking(){
		$q = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.id ".
			"FROM user_details as ud WHERE ud.id IN ".
			"(SELECT DISTINCT applier_id FROM ".Auditorium_constants::$STATUS_TABLE. " ".
				"WHERE 1)";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getIndenterFromBookedByDept($dept){
		$q = "SELECT ud.first_name, ud.middle_name, ud.last_name, ud.id ".
			"FROM user_details as ud WHERE ud.id IN ".
			"(SELECT DISTINCT applier_id FROM ".Auditorium_constants::$STATUS_TABLE. " ".
				"WHERE 1) AND ud.dept_id = '$dept'";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getBookingStatusOnConstraints($aud_id, $applier_id, $start_date, $end_date, $status, $dept_id){
		$q = "";
		$res = array();
		$date = new DateTime();
		$unix_ts = $date->getTimestamp();
		$auth_id = $this->session->userdata['id'];

		if (strlen($start_date) == 0 || strlen($end_date) == 0){
			$q = "SELECT ud.first_name, ud.middle_name, ud.last_name, t.book_from, t.book_to, t.purpose, d.name as dept_name, t.status, t.id ".
				"FROM departments as d, ".Auditorium_constants::$STATUS_TABLE. " as t INNER JOIN user_details as ud ON ".
				"ud.id = t.applier_id WHERE ".
				"t.applier_id LIKE '$applier_id' AND t.auditorium_id LIKE '$aud_id' AND ud.dept_id = d.id AND ".
				"UNIX_TIMESTAMP(t.book_from) >= '$unix_ts' AND t.status LIKE '$status' AND ud.dept_id LIKE '$dept_id' ".
				" AND t.approver_id = '$auth_id'";
		}
		else{
			$q = "SELECT ud.first_name, ud.middle_name, ud.last_name, t.book_from, t.book_to, t.purpose, d.name as dept_name, t.status, t.id ".
				"FROM departments as d, ".Auditorium_constants::$STATUS_TABLE. " as t INNER JOIN user_details as ud ON ".
				"ud.id = t.applier_id WHERE ".
				"t.applier_id LIKE '$applier_id' AND t.auditorium_id LIKE '$aud_id' AND ".
				"UNIX_TIMESTAMP(t.book_from) >= UNIX_TIMESTAMP('$start_date') AND ".
				"UNIX_TIMESTAMP(t.book_to) <= UNIX_TIMESTAMP('$end_date') AND ud.dept_id = d.id AND t.status LIKE '$status' ".
				"AND ud.dept_id LIKE '$dept_id' AND t.approver_id = '$auth_id'";
		}
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	public function getUserDetailsById($id){
		$q_applier_details = "SELECT id, first_name, middle_name, last_name, dept_id FROM user_details WHERE id = '$id'";
		$applier_details = $this->db->query($q_applier_details)->result_array();
		if (count($applier_details)){
			return $applier_details[0];
		}
		else return FALSE;
	}

	public function getDepartmentNameById($id){
		$q = "SELECT id, name FROM departments WHERE id = '$id'";
		$res = $this->db->query($q)->result_array();
		if (count($res)) return $res[0];
	}

	public function getAuditoriumApplicationDetails($id){
		$res = array();
		$q = "SELECT * FROM ". Auditorium_constants::$STATUS_TABLE ." WHERE id = '$id' ";
		$basic_details = $this->db->query($q)->result_array();
		if (count($basic_details) == 0){
			return $res;
		}
		$applier_id = $basic_details[0]['applier_id'];

		$applier_details = $this->getUserDetailsById($applier_id);
		$applier_dept_details = $this->getDepartmentNameById($applier_details['dept_id']);

		$aud_details = $this->getAuditoriumDetails($basic_details[0]['auditorium_id']);
		$res = array(
			"basic_details" => $basic_details[0],
			"applier_details" => $applier_details,
			"applier_dept_details" => $applier_dept_details,
			"aud_details" => $aud_details[0]
			); 

		return $res;
	}

	public function acceptBookingRequest($id, $aud_id, $action){
		/*
		*@id Booking id number
		*@aud_id Auditorium id
		*@action enum {accept, cancel}
		*/
		$auth_id = $this->session->userdata['auth'];

		$rl = "SELECT request_level FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$id'";
		$request_level = $this->db->query($rl)->result_array()[0]['request_level'];

		$qa = "SELECT applier_id FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$id'";
		$applier_id = $this->db->query($qa)->result_array()[0]['applier_id'];

		$q_auth = "SELECT auth_id FROM users WHERE id = '$applier_id'";
		$applier_auth = $this->db->query($q_auth)->result_array()[0]['auth_id'];

		if ($request_level == Auditorium_constants::$REQUEST_HEAD){
			//request has been now approved by the HOD/HOS/DSW
			//request now has to be sent to INCHARGE

			$qi = "SELECT incharge_id FROM ".Auditorium_constants::$DETAILS_TABLE. " WHERE auditorium_id = '$aud_id'";
			$incharge_id = $this->db->query($qi)->result_array()[0]['incharge_id'];

			$qu = "UPDATE ".Auditorium_constants::$STATUS_TABLE . 
				" SET approver_id = '$incharge_id', request_level = ".Auditorium_constants::$REQUEST_INCHARGE .
				" WHERE id = '$id' ";

			$this->db->query($qu);
			return array('id' => $id, 'aud_id' => $aud_id, 'next_approver_id' => $incharge_id, 'applier_id' => $applier_id, 'applier_auth' => $applier_auth);
		}
		// else if ($request_level == Auditorium_constants::$REQUEST_INCHARGE){
		// 	//request has now been approved by the incharge
		// 	//request now has to bee sent to DIRECTOR for final approval

		// 	$qd = "SELECT id FROM user_auth_types WHERE auth_id = 'dt'";
		// 	$dt_id = $this->db->query($qd)->result_array()[0]['id'];
		// 	$qu = "UPDATE ".Auditorium_constants::$STATUS_TABLE." ".
		// 		"SET approver_id = '$dt_id', request_level = ".Auditorium_constants::$REQUEST_DIRECTOR. " ".
		// 		"WHERE id = '$id' ";
		// 	$this->db->query($qu);
		// 	return array('id' => $id, 'aud_id' => $aud_id, 'next_approver_id' => $dt_id, 'applier_id' => $applier_id, 'applier_auth' => $applier_auth); 
		// }

		else if ($request_level == Auditorium_constants::$REQUEST_INCHARGE) {
			//final approval for accept or cancel
			$s = Auditorium_constants::$ACCEPT_BOOKING;
			if ($action == 'cancel'){
				$s = Auditorium_constants::$CANCEL_BOOKING;
			}

			$qf = "UPDATE ".Auditorium_constants::$STATUS_TABLE." SET status = ".$s." ".
				" WHERE id = '$id'";
			$this->db->query($qf);
			return array('id' => $id, 'aud_id' => $aud_id, 'next_approver_id' => "final", 'applier_id' => $applier_id, 'applier_auth' => $applier_auth); 
		}
	}

	public function rejectBookingRequest($id){
		//Restore the original status of the booking upon rejection 
		//Pending -> reject change status to rejected
		//accepted -> cancel change status to accepted
		$q = "SELECT status FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$id'";
		$current_status = $this->db->query($q)->result_array()[0]['status'];
		$s = 0;
		if ($current_status == Auditorium_constants::$WAITING_CANCEL_AT_ACCEPT){
			$s = Auditorium_constants::$ACCEPT_BOOKING;
		}
		else if ($current_status == Auditorium_constants::$WAITING_CANCEL_AT_PENDING){
			$s = Auditorium_constants::$REJECT_BOOKING;
		}
		else if ($current_status == Auditorium_constants::$PENDING_BOOKING){
			$s = Auditorium_constants::$REJECT_BOOKING;
		}
		
		$q = "UPDATE ".Auditorium_constants::$STATUS_TABLE." SET status = ".$s. " WHERE id = '$id'";
		$this->db->query($q);
		$qret = "SELECT applier_id, status FROM ".Auditorium_constants::$STATUS_TABLE. " WHERE id = '$id'";
		$qauth = "SELECT auth_id FROM users WHERE id = '$id'";
		$applier_id = $this->db->query($qret)->result_array();
		$applier_auth = $this->db->query($qauth)->result_array();

		return array('applier_id' => $applier_id[0]['applier_id'], 'applier_auth' => $applier_auth[0]['auth_id']);
	}

	public function getApplierInformation($applier_id){
		$q = "SELECT ud.id, ud.salutation, ud.first_name, ud.middle_name, ud.last_name, ud.photopath, ud.dept_id FROM user_details as ud ".
				"WHERE id = '$applier_id'";
		$q_auth = "SELECT auth_id FROM users WHERE id = '$applier_id'";
		$applier_auth = $this->db->query($q_auth)->result_array();
		$applier_sem = 0;
		if ($applier_auth[0]['auth_id'] == 'stu'){
			$q_sem = "SELECT semester FROM stu_academic WHERE admn_no = '$applier_id'";
			$applier_sem = $this->db->query($q_sem)->result_array()[0]['semester'];
		}
		$res = $this->db->query($q)->result_array();
		$res[0]['dept_name'] =  $this->getDepartmentNameById($res[0]['dept_id'])['name'];
		$res[0]['semester'] = $applier_sem;
		return $res[0];
	}
}

?>