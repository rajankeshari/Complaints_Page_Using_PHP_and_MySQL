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
	ud.physically_challenged,if(rrg.timestamp IS NULL,0,rrg.timestamp) AS date_of_pre_registration,if(sfd.tution_fee_amt IS NULL,0,sfd.tution_fee_amt) AS tution_fee,if(sfd.fee_amt IS NULL,0,sfd.fee_amt) AS other_fee,if((sfd.tution_fee_amt+sfd.fee_amt) IS NULL,0,(sfd.tution_fee_amt+sfd.fee_amt)) AS total_fee,0 as fee_waiving_amount,0 as last_sem_bal,0 as fine_pre_registration
	FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session'
	AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)rrg  INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
	INNER JOIN user_details ud ON ud.id=rrg.admn_no
	LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
	LEFT JOIN stu_fee_database_regular sfd ON case when ud.physically_challenged='yes' and sa.course_id NOT IN ('jrf-p') then sfd.category LIKE '%PWD' when ud.category='OBC' then sfd.category LIKE 'OB%' when ud.category='OBC-NCL' then sfd.category LIKE 'OB%' else sfd.category=ud.category END AND sfd.session_year=rrg.session_year
	AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime',concat(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
	LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/GROUP BY rrg.admn_no	ORDER BY rrg.course_id,rrg.branch_id";
	//exit;
		$query = $this->db->query($sql);
		//echo $this->db->last_query(); die();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;


	}

	public function get_session_year(){

		$query = $this->db->select('*')
						  ->from('mis_session_year')
						  ->get();

		return $query->result_array();
	}

	public function get_session(){

		$query = $this->db->select('*')
						  ->from('mis_session')
						  ->get();

		return $query->result_array();
	}

	public function insert_waiver_detail($stu_detail,$session_year,$session){
		// echo $session."<br>";
		// echo $session_year;
		// echo "<pre>";
		// print_r($stu_detail);
		// exit;

		// $sql = "select * from bank_fee_waiver_flag order by ";

		$count_error = 0;
		$count_success = 0;

		//$this->db->trans_start();
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
				'last_sem_bal'=>$value['last_sem_bal'],
				'total_fee'=>$value['total_fee'],
				'waive_amount'=>$value['waive_amount'],
				'fine_late_pre_registration' => $value['fine_late_pre_registration'],
				'created_by'=>$this->session->userdata('id')
			);

			// echo '<pre>';
			// print_r($insert_two);
			// echo '</pre>';
			//exit;
			$this->db->insert('bank_fee_waiver_details', $insert_two);
			$insert_id = $this->db->insert_id();
			if ($insert_id != '') {

				$count_success++;
			}
			else{

				$count_error++;
			}

	// 		$sql = "SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1";
	// $query = $this->db->query($sql);
	// $flag_array = $query->result_array();
	// $flag_array_id = $flag_array[0]['flag_id'];

		   // echo $this->db->last_query();
		}

// In the table bank_fee_waiver_details the field earlier used was  waive_percentage which has now been changed to waive_amount Rajesh Mishra 8.7.2021

		$sql = "SELECT p.other_rank,p.admn_no,p.session_year,p.session,p.stu_name,p.domain_name,p.course_id,p.branch_id,p.semester,p.category,
		if(p.physically_challenged IS NULL OR p.physically_challenged='','no',p.physically_challenged) AS physically_challenged,
	  (case when b.waive_amount IS NULL then 0 ELSE b.waive_amount END) AS waive_amount,
  -- ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee,
	 (case when  b.tution_fee  IS NULL then  p.tution_fee ELSE  b.tution_fee END ) AS tution_fee ,
	 (case when  b.other_fee  IS NULL then  p.other_fee ELSE   b.other_fee END ) AS other_fee ,
	 (case when b.last_sem_bal IS NULL then 0 ELSE b.last_sem_bal END) AS last_sem_bal,
	 (case when b.fine_late_pre_registration IS NULL then 0 ELSE b.fine_late_pre_registration END) AS fine_late_pre_registration,
		(case when  b.total_fee  IS NULL then  round(p.total_fee)  ELSE  round((round(b.tution_fee)-round(b.waive_amount)))+round(b.other_fee)+(b.last_sem_bal)+(b.fine_late_pre_registration)  END ) AS total_fee
   from(SELECT sa.other_rank,rrg.session_year,rrg.`session`,rrg.admn_no, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name,
  if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category, ud.physically_challenged,if(sfd.tution_fee_amt IS NULL,0,sfd.tution_fee_amt) AS tution_fee,if(sfd.fee_amt IS NULL,0,sfd.fee_amt) AS other_fee,if((sfd.tution_fee_amt+sfd.fee_amt) IS NULL,0,(sfd.tution_fee_amt+sfd.fee_amt)) AS total_fee
  FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1'
  GROUP BY rrg.admn_no)rrg INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
  INNER JOIN user_details ud ON ud.id=rrg.admn_no
  LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
  LEFT JOIN stu_fee_database_regular sfd ON case when ud.physically_challenged='yes' and sa.course_id NOT IN ('jrf-p') then sfd.category LIKE '%PWD' when ud.category='OBC' then sfd.category LIKE 'OB%' when ud.category='OBC-NCL' then sfd.category LIKE 'OB%' else sfd.category=ud.category END AND sfd.session_year=rrg.session_year AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime', CONCAT(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
  LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/
  GROUP BY rrg.admn_no ORDER BY rrg.course_id,rrg.branch_id)p
  LEFT JOIN
	(
	  SELECT  * FROM  bank_fee_waiver_details WHERE flag_id =
	   ( SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1)
	  ) b

		ON b.session_year=p.session_year AND b.`session`=p.session AND b.admn_no=p.admn_no";

	  $query = $this->db->query($sql);
	  //echo $this->db->last_query(); die();
	  if($query->num_rows() > 0){
		  $final_array = array();
		  $first =$query->result();
		  // return $first;
		  foreach ($first as $key => $value) {

			   if ($value->waive_amount > $value->tution_fee) {

				$value->total_fee = $value->other_fee + $value->fine_late_pre_registration + $value->last_sem_bal;

			   }

			  $updated_in_temp = array(

				  'admn_no' => $value->admn_no,
				  'flag_id' => $falg_id,
				  'session_year' => $value->session_year,
				  'session' => $value->session,
				  'stu_name' => $value->stu_name,
				  'domain_name' => $value->domain_name,
				  'course_id' => $value->course_id,
				  'branch_id' => $value->branch_id,
				  'semester' => $value->semester,
				  'category' => $value->category,
				  'physically_challenged' => $value->physically_challenged,
				  'waive_amount' => $value->waive_amount,
				  'fine_late_pre_registration' => $value->fine_late_pre_registration,
				  'last_sem_bal' => $value->last_sem_bal,
				  'tution_fee' => $value->tution_fee,
				  'other_fee' => $value->other_fee,
				  'total_fee' => $value->total_fee,
				  'created_on' => date('d-m-Y H:i:s'),
				  'created_by' => $this->session->userdata('id'),
				  'bank_fee_id' => '',
				  'bank_fee_updated_status' => 'not_sync',
				  'reason_for_bank_fee_not_updated' => ''

			  );

			  $this->db->insert('temp_sem_fee_waiver_details',$updated_in_temp);
			  $insert_id = $this->db->insert_id();

			  if ($insert_id != '') {

				$count_success++;
			}
			else{

				$count_error++;
			}

			//echo $this->db->last_query(); //die();

			}

		}


		if ($count_error > 0) {

			return false;
		}

		else{

			return true;
		}

		//exit;


	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE){
	// 		echo $this->db->last_query();
	// 		echo 'trans_failed';
	// 		exit;
	// 		$this->db->trans_rollback();
	// //exit;
	// 		return false;
	// 	}
	// 	else{
	// 		$this->db->trans_commit();
	// 		exit;
	// 		return true;
	// 	}



	//}

   //}

	}

	public function get_fee_waiver_student($name){
		// $sql = "SELECT a.* FROM bank_fee_waiver_details a WHERE a.session_year='$name[session_year]' AND a.`session`='$name[session]' ORDER BY a.course,a.branch";

		// insert into temp table to track the data being inserted in bank_fee_table in mis


		// $sql = "SELECT  a.* FROM  bank_fee_waiver_details a INNER JOIN bank_fee_waiver_flag b ON  a.flag_id = (SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1)
		// AND a.session_year=b.session_year AND a.`session`=b.session  WHERE a.session_year='$name[session_year]' AND a.`session`='$name[session]' GROUP BY a.admn_no,a.session_year,a.`session`";
		$session_year = $name['session_year'];
		$session = $name['session'];
		$sql = "SELECT  * FROM temp_sem_fee_waiver_details b WHERE b.flag_id = (SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1) and b.session_year='".$session_year."' AND b.`session`= '".$session."' GROUP BY b.admn_no,b.session_year,b.`session`";

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