<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dsw_fee_waiver_model extends CI_Model {
	function __construct(){
		parent::__construct();
  }

	public function get_stu_detail($name){
    $session_year = $name['session_year'];
    $session = $name['session'];

		// $sql ="SELECT rrg.admn_no,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name,rrg.course_id,rrg.branch_id,ud.category,ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,
		// (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee, 0 as waive_percentage
		// FROM reg_regular_form rrg INNER JOIN users u ON u.id=rrg.admn_no
		// INNER JOIN user_details ud ON ud.id=rrg.admn_no
		// INNER JOIN emaildata ed ON ed.admission_no=rrg.admn_no
		// INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=rrg.session_year 
		// AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND sfd.course_id=rrg.course_id
		// WHERE rrg.session_year='$session_year' AND rrg.`session`='$session'
		// AND rrg.hod_status='1' AND rrg.acad_status='1' AND u.`status`='A'
		// ORDER BY rrg.course_id,rrg.branch_id";

		$sql = "SELECT sa.other_rank,rrg.admn_no,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name,
	if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category,
	ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee,0 as waive_percentage
	FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session'
	AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)rrg  INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
	INNER JOIN user_details ud ON ud.id=rrg.admn_no	
	LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
	LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) AND sfd.session_year=rrg.session_year 
	AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime',concat(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
	LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/GROUP BY rrg.admn_no	ORDER BY rrg.course_id,rrg.branch_id";

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;


	}

	public function insert_waiver_detail($stu_detail,$session_year,$session){
		// echo $session."<br>";
		// echo $session_year;
		// echo "<pre>";
		// print_r($stu_detail);
		// exit;
		
		$this->db->trans_start();
		$this->session->userdata('id');

		$insert_one=array(
			'session_year'=>$session_year,
			'session'=>$session,
			'created_by'=>$this->session->userdata('id')
		);
		$this->db->insert('bank_fee_waiver_flag', $insert_one);
		$falg_id = $this->db->insert_id();
	
		foreach ($stu_detail as $key => $value) {
			$insert_two = array(
				'flag_id'=>$falg_id,
				'session_year'=>$value['session_year'],
				'session'=>$value['session'],
				'stu_name'=>$value['stu_name'],
				'admn_no'=>$value['admn_no'],
				'email_id'=>$value['email'],
				'course'=>$value['course'],
				'branch'=>$value['branch'],
				'category'=>$value['category'],
				'pwd_status'=>$value['pwd_status'],
				'tution_fee'=>$value['tution_fee'],
				'other_fee'=>$value['other_fee'],
				'total_fee'=>$value['total_fee'],
				'waive_percentage'=>$value['waive_percentage'],
				'created_by'=>$this->session->userdata('id')
			);
			// print_r($insert_two);
			// exit;
			$this->db->insert('bank_fee_waiver_details', $insert_two);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}
		else{
			$this->db->trans_commit();
			return true;
		}

	}

	public function get_fee_waiver_student($name){
		// $sql = "SELECT a.* FROM bank_fee_waiver_details a WHERE a.session_year='$name[session_year]' AND a.`session`='$name[session]' ORDER BY a.course,a.branch";

		$sql = "SELECT  a.* FROM  bank_fee_waiver_details a INNER JOIN bank_fee_waiver_flag b ON  a.flag_id = (SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1) 
		AND a.session_year=b.session_year AND a.`session`=b.session  WHERE a.session_year='$name[session_year]' AND a.`session`='$name[session]' GROUP BY a.admn_no,a.session_year,a.`session`";

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}


/* end of class  */
}
?>