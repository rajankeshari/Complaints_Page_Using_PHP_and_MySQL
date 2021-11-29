<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fee_model extends CI_Model {
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
  	}
  


public function get_stu_detail($name){
    $session_year = $name['session_year'];
    $session = $name['session'];
	$sql ="SELECT rrg.admn_no,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name,rrg.course_id,rrg.branch_id,rrg.semester,ud.category,ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,
	(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
	FROM reg_regular_form rrg INNER JOIN users u ON u.id=rrg.admn_no
	INNER JOIN user_details ud ON ud.id=rrg.admn_no
	INNER JOIN emaildata ed ON ed.admission_no=rrg.admn_no
	INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=rrg.session_year 
	AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND sfd.course_id=rrg.course_id
	WHERE rrg.session_year='$session_year' AND rrg.`session`='$session'
	AND rrg.hod_status='1' AND rrg.acad_status='1' AND u.`status`='A'
	ORDER BY rrg.course_id,rrg.branch_id";

	$query = $this->db->query($sql);
	// echo $this->db->last_query();
	if($query->num_rows() > 0)
		return $query->result();
	else
		return false;
}

public function get_waive_stu_detail($name){
	$session_year = $name['session_year'];
    $session = $name['session'];
    $sql = "SELECT m.* FROM (SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
    	bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m
		UNION SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
		ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
		sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
		rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
		rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
		INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
		INNER JOIN user_details ud ON ud.id=r.admn_no
		INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
		INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
		sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT m.admn_no FROM (SELECT b.admn_no AS admn_no,
		b.session_year,b.`session`FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
		bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m) GROUP BY r.admn_no
		ORDER BY r.course_id,r.branch_id)k";

    $query = $this->db->query($sql);
	// echo $this->db->last_query();
	if($query->num_rows() > 0)
		return $query->result();
	else
		return false;

}

public function final_fee_submit($session_year,$session){
	$sql = "SELECT m.* FROM (SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
    	bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m
		UNION SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
		ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
		sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
		rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
		rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
		INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
		INNER JOIN user_details ud ON ud.id=r.admn_no
		INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
		INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
		sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT m.admn_no FROM (SELECT b.admn_no AS admn_no,
		b.session_year,b.`session`FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
		bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m) GROUP BY r.admn_no
		ORDER BY r.course_id,r.branch_id)k";

    $query = $this->db->query($sql);
	// echo $this->db->last_query();
	if($query->num_rows() > 0){
		$final_array = array();
		$first =$query->result();
		// return $first;
		foreach ($first as $key => $value) {
			$final_array[$key] = array(
				'student_name' => $value->stu_name,
				'admn_no' => $value->admn_no,
				'email_id' => $value->email,
				'session_year' => $value->session_year,
				'session' => $value->session,
				'course_id' => $value->course_id,
				'branch_id' => $value->branch_id,
				'category' => $value->category,
				'pwd_status' => $value->pwd_status,
				'amount' => $value->total_fee,
				'fine_amount' => '0',
				'total_amount' => $value->total_fee,
				'verification_status' => '1',
				'payment_status' => '0'

			);
		}
		if($this->db->insert_batch('bank_fee_details', $final_array) && $this->db2->insert_batch('bank_fee_details', $final_array)){
			// echo $this->db->last_query();
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}




/* end  of Model */
	
}
?>
