<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fee_model extends CI_Model {
	
	private $db_name='parentlive';
	// private $db_name='parentbeta';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
  	}
  


public function get_stu_detail($name){
    $session_year = $name['session_year'];
    $session = $name['session'];
	// $sql ="SELECT rrg.admn_no,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name,rrg.course_id,rrg.branch_id,rrg.semester,ud.category,ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,
	// (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
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
	ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
	FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session'
	AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)rrg  INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
	INNER JOIN user_details ud ON ud.id=rrg.admn_no	
	LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
	LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) AND sfd.session_year=rrg.session_year 
	AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime',concat(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
	LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/GROUP BY rrg.admn_no	ORDER BY rrg.course_id,rrg.branch_id";

	$query = $this->db->query($sql);
	// echo $this->db->last_query(); die();
	if($query->num_rows() > 0)
		return $query->result();
	else
		return false;
}

public function get_waive_stu_detail($name){
	$session_year = $name['session_year'];
    $session = $name['session'];
  //   $sql = "SELECT m.* FROM (SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
  //   	bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m
		// UNION SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
		// ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
		// sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
		// rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
		// rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
		// INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
		// INNER JOIN user_details ud ON ud.id=r.admn_no
		// INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
		// INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
		// sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT m.admn_no FROM (SELECT b.admn_no AS admn_no,
		// b.session_year,b.`session`FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
		// bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m) GROUP BY r.admn_no
		// ORDER BY r.course_id,r.branch_id)k";

	// $sql = "(SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT x.* FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x GROUP BY x.session_year AND x.session)y INNER JOIN bank_fee_waiver_details b ON b.flag_id=y.flag_id AND b.session_year=y.session_year AND b.`session`=y.session WHERE b.session_year='$session_year' AND b.`session`='$session') UNION 	
	// 	SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
	// 	ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
	// 	sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
	// 	rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
	// 	rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
	// 	INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
	// 	INNER JOIN user_details ud ON ud.id=r.admn_no
	// 	INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
	// 	INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
	// 	sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT b.admn_no AS admn_no FROM (SELECT x.* FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on
	// 	DESC LIMIT 1000000)x GROUP BY x.session_year AND x.session)y INNER JOIN bank_fee_waiver_details b ON b.flag_id=y.flag_id AND b.session_year=y.session_year AND b.`session`=y.session
	// 	WHERE b.session_year='$session_year' AND b.`session`='$session') GROUP BY r.admn_no ORDER BY r.course_id,r.branch_id)k";

	$sql = "SELECT p.other_rank,p.admn_no,p.session_year,p.session,p.stu_name,p.domain_name,p.course_id,p.branch_id,p.semester,p.category,
	if(p.physically_challenged IS NULL OR p.physically_challenged='','no',p.physically_challenged) AS physically_challenged,
	(case when b.waive_percentage IS NULL then 0 ELSE b.waive_percentage END) AS waive_percentage,
-- ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee,
   (case when  b.tution_fee  IS NULL then  p.tution_fee ELSE  b.tution_fee END ) AS tution_fee ,
   (case when  b.other_fee  IS NULL then  p.other_fee ELSE   b.other_fee END ) AS other_fee ,
      (case when  b.total_fee  IS NULL then  p.total_fee+b.last_sem_bal  ELSE  ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)+(b.last_sem_bal))  END ) AS total_fee 	
 from(SELECT sa.other_rank,rrg.session_year,rrg.`session`,rrg.admn_no, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name, 
if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category, ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1'
GROUP BY rrg.admn_no)rrg INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
INNER JOIN user_details ud ON ud.id=rrg.admn_no
LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) AND sfd.session_year=rrg.session_year AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime', CONCAT(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/
GROUP BY rrg.admn_no ORDER BY rrg.course_id,rrg.branch_id)p
LEFT JOIN 
  (
    SELECT  * FROM  bank_fee_waiver_details WHERE flag_id =
	 ( SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1) 
	) b
	
      ON b.session_year=p.session_year AND b.`session`=p.session AND b.admn_no=p.admn_no";

    $query = $this->db->query($sql);
	// echo $this->db->last_query(); die();
	if($query->num_rows() > 0)
		return $query->result();
	else
		return false;

}

public function final_fee_submit($session_year,$session){
	// $sql = "SELECT m.* FROM (SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
 //    	bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m
	// 	UNION SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
	// 	ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
	// 	sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
	// 	rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
	// 	rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
	// 	INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
	// 	INNER JOIN user_details ud ON ud.id=r.admn_no
	// 	INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
	// 	INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
	// 	sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT m.admn_no FROM (SELECT b.admn_no AS admn_no,
	// 	b.session_year,b.`session`FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x INNER JOIN 
	// 	bank_fee_waiver_details b ON b.flag_id=x.flag_id WHERE b.session_year='$session_year' AND b.`session`='$session')m) GROUP BY r.admn_no
	// 	ORDER BY r.course_id,r.branch_id)k";

	// $sql = "(SELECT b.admn_no AS admn_no,b.session_year,b.`session`,b.stu_name AS stu_name,b.email_id AS email,b.course AS course_id,b.branch AS branch_id,b.category,b.pwd_status,b.tution_fee,b.other_fee,b.waive_percentage,((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee FROM (SELECT x.* FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on DESC LIMIT 1000000)x GROUP BY x.session_year AND x.session)y INNER JOIN bank_fee_waiver_details b ON b.flag_id=y.flag_id AND b.session_year=y.session_year AND b.`session`=y.session WHERE b.session_year='$session_year' AND b.`session`='$session') UNION 	
	// 	SELECT k.* FROM (SELECT r.admn_no AS admn_no,r.session_year,r.session,CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,
	// 	ed.domain_name AS email ,r.course_id,r.branch_id,ud.category,ud.physically_challenged AS pwd_status,sfd.tution_fee_amt AS tution_fee,
	// 	sfd.fee_amt AS other_fee,0 AS waive_percentage, (sfd.tution_fee_amt+sfd.fee_amt) AS total_fee FROM (SELECT rrg.admn_no AS admn_no,
	// 	rrg.session_year,rrg.session,rrg.course_id,rrg.branch_id,rrg.semester FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND 
	// 	rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1' GROUP BY rrg.admn_no)r 
	// 	INNER JOIN users u ON u.id=r.admn_no AND u.status='A'
	// 	INNER JOIN user_details ud ON ud.id=r.admn_no
	// 	INNER JOIN emaildata ed ON ed.admission_no=r.admn_no
	// 	INNER JOIN stu_fee_database_regular sfd ON sfd.category=ud.category AND sfd.session_year=r.session_year AND sfd.`session`=r.`session` AND 
	// 	sfd.semester=r.semester AND sfd.course_id=r.course_id WHERE r.admn_no NOT IN (SELECT b.admn_no AS admn_no FROM (SELECT x.* FROM (SELECT w.* FROM bank_fee_waiver_flag w ORDER BY w.created_on
	// 	DESC LIMIT 1000000)x GROUP BY x.session_year AND x.session)y INNER JOIN bank_fee_waiver_details b ON b.flag_id=y.flag_id AND b.session_year=y.session_year AND b.`session`=y.session
	// 	WHERE b.session_year='$session_year' AND b.`session`='$session') GROUP BY r.admn_no ORDER BY r.course_id,r.branch_id)k";

// 	$sql = "SELECT p.other_rank,p.admn_no,p.session_year,p.session,p.stu_name,p.domain_name,p.course_id,p.branch_id,p.semester,p.category,p.physically_challenged,
// 	(case when b.waive_percentage IS NULL then 0 ELSE b.waive_percentage END) AS waive_percentage,
// -- ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee,
//    (case when  b.tution_fee  IS NULL then  p.tution_fee ELSE  b.tution_fee END ) AS tution_fee ,
//    (case when  b.other_fee  IS NULL then  p.other_fee ELSE   b.other_fee END ) AS other_fee ,
//       (case when  b.total_fee  IS NULL then  p.total_fee  ELSE  ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee))  END ) AS total_fee 	
//  from(SELECT sa.other_rank,rrg.session_year,rrg.`session`,rrg.admn_no, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name, 
// if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category, ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
// FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1'
// GROUP BY rrg.admn_no)rrg INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
// INNER JOIN user_details ud ON ud.id=rrg.admn_no
// LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
// LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) AND sfd.session_year=rrg.session_year AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime', CONCAT(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
// LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/
// GROUP BY rrg.admn_no ORDER BY rrg.course_id,rrg.branch_id)p
// LEFT JOIN 
//   (
//     SELECT  * FROM  bank_fee_waiver_details WHERE flag_id =
// 	 ( SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1) 
// 	) b
	
//       ON b.session_year=p.session_year AND b.`session`=p.session AND b.admn_no=p.admn_no";

      $sql = "SELECT p.other_rank,p.admn_no,p.session_year,p.session,p.stu_name,p.domain_name,p.course_id,p.branch_id,p.semester,p.category,
      if(p.physically_challenged IS NULL OR p.physically_challenged='','no',p.physically_challenged) AS physically_challenged,
	(case when b.waive_percentage IS NULL then 0 ELSE b.waive_percentage END) AS waive_percentage,
-- ((b.tution_fee-(b.tution_fee*(b.waive_percentage/100)))+(b.other_fee)) AS total_fee,
   (case when  b.tution_fee  IS NULL then  p.tution_fee ELSE  b.tution_fee END ) AS tution_fee ,
   (case when  b.other_fee  IS NULL then  p.other_fee ELSE   b.other_fee END ) AS other_fee ,
      (case when  b.total_fee  IS NULL then  round(p.total_fee+b.last_sem_bal)  ELSE  round((round(b.tution_fee)-round(b.tution_fee*(b.waive_percentage/100)))+round(b.other_fee)+(b.last_sem_bal))  END ) AS total_fee 	
 from(SELECT sa.other_rank,rrg.session_year,rrg.`session`,rrg.admn_no, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name, 
if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category, ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' AND rrg.hod_status='1' AND rrg.acad_status='1'
GROUP BY rrg.admn_no)rrg INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
INNER JOIN user_details ud ON ud.id=rrg.admn_no
LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) AND sfd.session_year=rrg.session_year AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND if(rrg.course_id='jrf' AND sa.other_rank='parttime', CONCAT(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no /*WHERE rrg.course_id='jrf'*/
GROUP BY rrg.admn_no ORDER BY rrg.course_id,rrg.branch_id)p
LEFT JOIN 
  (
    SELECT  * FROM  bank_fee_waiver_details WHERE flag_id =
	 ( SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1) 
	) b
	
      ON b.session_year=p.session_year AND b.`session`=p.session AND b.admn_no=p.admn_no";

    $query = $this->db->query($sql);
	// echo $this->db->last_query(); die();
	if($query->num_rows() > 0){
		$final_array = array();
		$first =$query->result();
		// return $first;
		foreach ($first as $key => $value) {
			$final_array[$key] = array(
				'student_name' => $value->stu_name,
				'admn_no' => $value->admn_no,
				'email_id' => $value->domain_name,
				'session_year' => $value->session_year,
				'session' => $value->session,
				'course_id' => $value->course_id,
				'branch_id' => $value->branch_id,
				'category' => $value->category,
				'pwd_status' => $value->physically_challenged,
				'amount' => $value->total_fee,
				'fine_amount' => '0',
				'total_amount' => $value->total_fee,
				'verification_status' => '1',
				'payment_status' => '0'
			);
		}
		// $final_array = array_unique($final_array);
		// return $final_array;
		if(!empty($final_array)){
			// return $final_array;
			$this->db->trans_start();

			$this->db->insert_batch('bank_fee_details', $final_array);

			$CI = &get_instance();
     		$this->db2 = $CI->load->database($this->db_name, TRUE);
     		$this->db2->insert_batch('bank_fee_details', $final_array);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return false;
			}
			else{
				$this->db->trans_commit();
				return true;
			}
			// $this->db->insert_batch('bank_fee_details_test', $final_array);

			// $CI = &get_instance();

   //  		$this->db2 = $CI->load->database($this->db_name, TRUE);

   //  		$this->db2->insert_batch('bank_fee_details_test', $final_array);

		}
		else{
			return false;
		}
		
	}
	else{
		return false;
	}
}

public function edit_stu_fee_detail($session_year,$session,$admn_no){

	// $query = $this->db->get_where('bank_fee_details', array('session_year' => $session_year,'session' =>$session,'admn_no' =>$admn_no));
	
	$query = $this->db->get_where('bank_fee_details', array('session_year' => $session_year,'session' =>$session,'admn_no' =>$admn_no, 'payment_status !='=>'1'));

	// echo $this->db->last_query();

	if($query->num_rows() > 0){

		return $query->result();
	}
	else{
		return false;
	}
}


public function edit_final_stu_fee_detail($name){
	$h_id=$name['h_id'];
	$b_category=$name['b_edit_category'];
	$b_pwd_status=$name['b_pwd_status'];
	$b_amount=$name['b_edit_amount'];
	$b_fine=$name['b_edit_fine'];
	$remarks=$name['remarks'];
	$b_total_amount=(int)$b_amount+(int)$b_fine;


	$this->db->trans_start();

	$query = $this->db->get_where('bank_fee_details', array('id' => $h_id));	
	$prev_data=$query->result();
	$admn_no = $prev_data[0]->admn_no;
	$l_session_year=$prev_data[0]->session_year;
	$l_session=$prev_data[0]->session;
	$prev_total_amount=((int)$prev_data[0]->amount+(int)$prev_data[0]->fine_amount);

	// echo $this->db->last_query(); 
	
	$log_array=array(
		'bank_fee_id'=>$h_id,
		'admn_no'=>$admn_no,
		'prev_category'=>$prev_data[0]->category,
		'prev_pwd_status'=>$prev_data[0]->pwd_status,
		'prev_amount'=>$prev_data[0]->amount,
		'prev_fine'=>$prev_data[0]->fine_amount,	
		'prev_total_amount'=>$prev_total_amount,
		'updated_category'=>$b_category,
		'updated_pwd_status'=>$b_pwd_status,
		'updated_amount'=>$b_amount,
		'updated_fine'=>$b_fine,
		'updated_total_amount'=>$b_total_amount,
		'updated_by'=>$this->session->userdata('id'),
		'remarks'=>$remarks
	);


	$update_array=array(
		'category'=>$b_category,
		'pwd_status'=>$b_pwd_status,
		'amount'=>$b_amount,
		'fine_amount'=>$b_fine,
		'total_amount'=>$b_total_amount
	);

	$user_detail_array=array(
		'category'=>$b_category,
		'physically_challenged'=>$b_pwd_status
	);

	
	$this->db->insert('bank_fee_details_log', $log_array);

	$this->db->where('id', $h_id);
	$this->db->update('bank_fee_details', $update_array);

	
	$this->db->where('id', $admn_no);
	$this->db->update('user_details', $user_detail_array);


	$CI = &get_instance();
	$this->db2 = $CI->load->database($this->db_name, TRUE);
	
	
	
	$query2 = $this->db2->get_where('bank_fee_details', array('session_year' => $l_session_year,'session' =>$l_session,'admn_no' =>$admn_no));	
	$l_data=$query2->result();
	
	

	// $this->db2->insert('bank_fee_details_log', $log_array);

	$this->db2->where('id', $l_data[0]->id);
	$this->db2->update('bank_fee_details', $update_array);
	
	//echo $this->db2->last_query(); 
	
	// $this->db2->where('id', $admn_no);
	// $this->db2->update('user_details', $user_detail_array);
	
	// echo "<pre>";
	// print_r($log_array);
	// print_r($update_array);

	$this->db->trans_complete();

	if ($this->db->trans_status() === FALSE){
		$this->db->trans_rollback();
		// $data['error'] = 'false';
		// return $data;
		return false;
	}
	else{
		$this->db->trans_commit();
		// $data['success'] ='true';
		// return $data;
		return true;
	}



}

public function get_individual_student_details($session_year,$session,$admn_no){

	$sql = "SELECT * FROM reg_regular_form rrg INNER JOIN bank_fee_details bfd ON rrg.session_year=bfd.session_year AND rrg.`session`=bfd.`session` AND 
	rrg.admn_no=bfd.admn_no WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' /*AND rrg.hod_remark='Late Registration'*/
	AND rrg.admn_no='$admn_no'";
	
	$query = $this->db->query($sql);
	// echo $this->db->last_query(); 
	// die();
	if($query->num_rows() > 0){
		$data['status']='available';
	}
	else
	{
		$sql = "SELECT p.other_rank,p.admn_no,p.session_year,p.session,p.stu_name,p.domain_name,p.course_id,p.branch_id,p.semester,p.category,
		if(p.physically_challenged IS NULL OR p.physically_challenged='','no',p.physically_challenged) AS physically_challenged,
		(case when b.waive_percentage IS NULL then 0 ELSE b.waive_percentage END) AS waive_percentage,
		(case when  b.tution_fee  IS NULL then  p.tution_fee ELSE b.tution_fee END ) AS tution_fee ,
		(case when  b.other_fee  IS NULL then  p.other_fee ELSE b.other_fee END ) AS other_fee,
		(case when  b.total_fee  IS NULL then  p.total_fee ELSE b.total_fee END) AS total_fee ,
		b.last_sem_bal,0 as fine,b.flag_id
		from(SELECT sa.other_rank,rrg.session_year,rrg.`session`,rrg.admn_no, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ed.domain_name, 
		if(rrg.course_id='jrf' AND sa.other_rank='parttime','jrf-p',rrg.course_id) AS course_id,rrg.branch_id,rrg.semester,ud.category,
		ud.physically_challenged,sfd.tution_fee_amt AS tution_fee,sfd.fee_amt AS other_fee,(sfd.tution_fee_amt+sfd.fee_amt) AS total_fee
		FROM(SELECT * FROM reg_regular_form rrg WHERE rrg.session_year='$session_year' AND rrg.`session`='$session' AND rrg.admn_no='$admn_no' AND rrg.hod_status='1' AND rrg.acad_status='1' /*AND rrg.hod_remark='Late Registration'*/
		GROUP BY rrg.admn_no)rrg INNER JOIN users u ON u.id=rrg.admn_no AND u.`status`='A'
		INNER JOIN user_details ud ON ud.id=rrg.admn_no
		LEFT JOIN stu_academic sa ON sa.admn_no=rrg.admn_no
		LEFT JOIN stu_fee_database_regular sfd ON if(ud.category='OBC' OR ud.category='OBC-NCL',sfd.category like 'OB%',sfd.category=ud.category) 
		AND sfd.session_year=rrg.session_year AND sfd.`session`=rrg.`session` AND sfd.semester=rrg.semester AND 
		if(rrg.course_id='jrf' AND sa.other_rank='parttime', CONCAT(rrg.course_id,'-p')=sfd.course_id,sfd.course_id=rrg.course_id)
		LEFT JOIN emaildata ed ON ed.admission_no=rrg.admn_no
		GROUP BY rrg.admn_no ORDER BY rrg.course_id,rrg.branch_id)p
		LEFT JOIN(SELECT  * FROM  bank_fee_waiver_details WHERE flag_id =
		(SELECT f.flag_id  FROM  bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1))b
		ON b.session_year=p.session_year AND b.`session`=p.session AND b.admn_no=p.admn_no";

		$query = $this->db->query($sql);
		// echo $this->db->last_query(); 
		if($query->num_rows() > 0){
			// return true;
			$data['status']='ok';
			$data['data']=$query->result();			
		}
		else{
			$data['status']='false';
		}		
	}
	return $data;
}


public function add_individual_student_details($name){
	$flag_id=$name['flag_id'];
	$admn_no=$name['admn_no'];
	$branch_id=$name['branch_id'];
	$category=$name['category'];
	if($category==='OBC-NCL'){
		$category='OBC';
	}
	$course_id=$name['course_id'];
	if($course_id==='jrf-p'){
		$course_id='jrf';
	}
	$email_id=$name['email_id'];
	$pwd_status=$name['pwd_status'];
	$semester=$name['semester'];
	$session=$name['session'];
	$session_year=$name['session_year'];
	$stu_name=$name['stu_name'];
	
	$tution_fee=$name['tution_fee'];
	$other_fee=$name['other_fee'];
	$fine=$name['fine'];
	$waive_percentage=$name['waive_percentage'];
	$last_sem_bal=$name['last_sem_bal'];
	$total_fee=$name['total_amount']; // for bank_fee_details

	$amount=(int)$total_fee-(int)$fine;

	$waiver_total_amount=(int)$tution_fee+(int)$other_fee;

	$bank_fee_amount = (int)$tution_fee+(int)$other_fee;
	$bank_fee_total_amount = (int)$tution_fee+(int)$other_fee+(int)$fine;

	if(is_null($flag_id) || empty($flag_id)){
		$fsql="SELECT * FROM bank_fee_waiver_flag f ORDER BY f.created_on DESC LIMIT 1";
		$fquery = $this->db->query($fsql);
		if($fquery->num_rows() > 0){			
			$flag_id=$fquery->row()->flag_id;
		}
		else{
			return false;
		}
	}


	$this->db->trans_start();

	$waiver_array=array(
		'flag_id'=>$flag_id,
		'session_year'=>$session_year,
		'session'=>$session,
		'stu_name'=>$stu_name,
		'admn_no' => $admn_no,
		'email_id'=>$email_id,
		'course'=>$course_id,
		'branch'=>$branch_id,
		'category'=>$category,
		'pwd_status'=>$pwd_status,
		'tution_fee'=>$tution_fee,
		'other_fee'=>$other_fee,
		'last_sem_bal'=>$last_sem_bal,
		'total_fee'=>$waiver_total_amount,
		'waive_percentage'=>$waive_percentage,
		'created_by'=>$this->session->userdata('id')		
	);
	$this->db->insert('bank_fee_waiver_details', $waiver_array);


	$bank_fee_array=array(
		'student_name' => $stu_name,
		'admn_no' => $admn_no,
		'email_id' => $email_id,
		'session_year' => $session_year,
		'session' => $session,
		'course_id' => $course_id,
		'branch_id' => $branch_id,
		'category' => $category,
		'pwd_status' => $pwd_status,
		'amount' => $amount,
		'fine_amount' => $fine,
		'total_amount' => $total_fee,
		'verification_status' => '1',
		'payment_status' => '0'
	);

	// echo "<pre>";
	// print_r($waiver_array);
	// print_r($bank_fee_array);
	
	$this->db->insert('bank_fee_details', $bank_fee_array);

	
	$CI = &get_instance();
	$this->db2 = $CI->load->database($this->db_name, TRUE);

	$query2 = $this->db2->get_where('bank_fee_details', array('session_year' => $session_year,'session' =>$session,'admn_no' =>$admn_no));	


	if($query2->num_rows() > 0){
		$update_array = array(
			'category'=>$category,
			'pwd_status'=>$pwd_status,
			'amount'=>$bank_fee_amount,
			'fine_amount'=>$fine,
			'total_amount'=>$bank_fee_total_amount
		);
		$this->db2->where(array('session_year' => $session_year,'session' =>$session,'admn_no' =>$admn_no));
		$this->db2->update('bank_fee_details', $update_array);
	}
	else{
		$this->db2->insert('bank_fee_details', $bank_fee_array);
	}
	// $this->db2->insert('bank_fee_details', $bank_fee_array);

	$this->db->trans_complete();

	if ($this->db->trans_status() === FALSE){
		$this->db->trans_rollback();
		// $data['error'] = 'false';
		// return $data;
		return false;
	}
	else{
		$this->db->trans_commit();
		// $data['success'] ='true';
		// return $data;
		return true;
	}

}




/* end  of Model */
	
}
?>
