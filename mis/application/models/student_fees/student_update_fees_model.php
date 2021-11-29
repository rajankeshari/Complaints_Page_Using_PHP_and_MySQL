<?php
Class Student_update_fees_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function get_first_list($id,$sess_yr,$sess){
		$sql="(SELECT a.session_year,a.`session`,a.semester,a.form_id,a.hod_status,a.admn_no,r.fee_amt,r.fee_date,r.transaction_id,'Regular' as 't_type',a.acad_status 
from reg_regular_form a
left join reg_regular_fee r on r.form_id=a.form_id 
where a.session_year='$sess_yr' and a.session='$sess' and a.admn_no='$id' /*and a.hod_status='1'*/)
union
(SELECT a.session_year,a.`session`,a.semester,a.form_id,a.hod_status,a.admn_no,r.fee_amt,r.fee_date,r.transaction_id,'Other' as 't_type',a.acad_status from reg_other_form a 
left join reg_other_fee r on r.form_id=a.form_id 
where a.session_year='$sess_yr' and a.session='$sess' and a.admn_no='$id' /*and a.hod_status='1'*/)
union
(SELECT a.session_year,a.`session`,a.semester,a.form_id,a.hod_status,a.admn_no,r.fee_amt,r.fee_date,r.transaction_id,'Summer' as 't_type',a.acad_status from reg_summer_form a
left join reg_summer_fee r on r.form_id=a.form_id  
where a.session_year='$sess_yr' and a.session='$sess' and a.admn_no='$id' /*and a.hod_status='1'*/)
union
(SELECT a.session_year,a.`session`,a.semester,a.form_id,a.hod_status,a.admn_no,r.fee_amt,r.fee_date,r.transaction_id,'Exam' as 't_type',a.acad_status from reg_exam_rc_form a
left join reg_exam_rc_fee r on r.form_id=a.form_id  
where a.session_year='$sess_yr' and a.session='$sess' and a.admn_no='$id' /*and a.hod_status='1'*/)
union
(SELECT a.session_year,a.`session`,a.semester,a.form_id,a.hod_status,a.admn_no,r.fee_amt,r.fee_date,r.transaction_id,'Idle' as 't_type',a.acad_status from reg_idle_form a
left join reg_idle_fee r on r.form_id=a.form_id  
where a.session_year='$sess_yr' and a.session='$sess' and a.admn_no='$id' /*and a.hod_status='1'*/)

";
	$result=$this->db->query($sql);
	return $result->result();
	}

// 	function get_second_list($id,$sess_yr,$sess){
// 		$sql="select rr.* from reg_other_fee rr
// left join reg_other_form rf on rf.form_id=rr.form_id
// where rf.admn_no='$id' and rf.session_year='$sess_yr' and rf.session='$sess' and rf.hod_status='1'";
// 	$result=$this->db->query($sql);
// 	return $result->result();
// 	}

	//Get form id
	function get_form_id($id){
		$sql="SELECT form_id FROM `reg_regular_form` WHERE admn_no='$id' AND session_year='2019-2020' AND session='Monsoon'";
		$result=$this->db->query($sql);
		return $result->result();
	}

	function update_reg_fees($id,$dop,$amount,$transId,$slip,$f_id,$tno){
		
		$col='admn_no';
		if($tno == 'Regular'){
			$table='reg_regular_fee';
		}
		elseif($tno == 'Other'){
			$table='reg_other_fee';
		}
		elseif($tno == 'Summer'){
			$table='reg_summer_fee';
		}
		elseif($tno == 'Exam'){
			$table='reg_exam_rc_fee';
		}
		elseif($tno == 'Idle'){
			$table='reg_idle_fee';
			$col='admn_id';
		}			

		$sql="UPDATE $table t set t.late_receipt_path=t.receipt_path WHERE t.form_id='$f_id'";
		$this->db->query($sql);
		$sql1="UPDATE $table SET fee_amt='$amount',fee_date='$dop',transaction_id='$transId',receipt_path='$slip' WHERE form_id='$f_id' AND $col='$id'";
		/*if($id=='17MT001580')
		{
			echo $sql1;
			die();
		}*/
		if($this->db->query($sql1))
			return true;
		else
			return false;
		
	}
}
?>