<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ft_online_crf_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getlist()
	{
		$empid = $this->session->userdata('id');
		$auth = $this->session->userdata('auth');
		if(in_array('adri',$auth)){
			$token = '';
		}
		else{		
			$token = "ci.fic_emp_no='".$empid."' AND ";
		}
		$sql = "SELECT ci.instrument,cif.facility,cics.* FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id 
		INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id WHERE $token ci.status='Active' AND 
		cif.status='Active' GROUP BY cics.detail_id ORDER BY detail_id DESC";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if ($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function getname($id)
	{
		$sql = "SELECT CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as fname,u.dept_id FROM user_details u WHERE u.id='$id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function show_detail($detail_id)
	{
		$empid = $this->session->userdata('id');
		$auth = $this->session->userdata('auth');
		if(in_array('adri',$auth)){
			$token = '';
		}
		else{		
			$token = "ci.fic_emp_no='".$empid."' AND ";
		}
		$sql = "SELECT ci.instrument,ci.email,ci.fic_emp_no,ci.remark1,cif.facility,cics.* FROM crf_inst_cost_and_slot cics 
		INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id 
		WHERE $token cics.detail_id='$detail_id' AND cics.status='Active'";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	public function booked_detail($detail_id){
		// $sql="SELECT cics.analysis_charge,cbsn.dates,cbsn.day_sample,cbsn.night_sample,cbsn.d_start_time,cbsn.d_end_time,cbsn.n_start_time,cbsn.n_end_time,cbsn.charge FROM crf_inst_cost_and_slot cics INNER JOIN  crf_booking_s_n_c cbsn ON 
		// cics.detail_id=cbsn.detail_id	WHERE cics.detail_id='$detail_id' ORDER BY cbsn.dates DESC";

		$sql = "SELECT CONCAT_WS(' ',cbu.salutation,cbu.first_name,if(cbu.middle_name='NA','',cbu.middle_name),cbu.family_name) AS uname,cbu.dept,cbu.user_purpose,
		cbu.booking_TS AS created_date,cics.analysis_charge,cbsn.dates,cbsn.day_sample,cbsn.night_sample,cbsn.d_start_time,cbsn.d_end_time,cbsn.n_start_time,
		cbsn.n_end_time,cbsn.charge FROM crf_inst_cost_and_slot cics INNER JOIN  crf_booking_s_n_c cbsn ON cics.detail_id=cbsn.detail_id INNER JOIN crf_booking_user
		cbu ON cbu.user_id=cbsn.user_id	WHERE cics.detail_id='$detail_id' ORDER BY cbsn.dates ASC";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		// exit;
		if ($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
}

