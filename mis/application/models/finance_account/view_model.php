<?php

/**
* 
*/
class View_model extends CI_model
{
	
	function getPayDetailsPrint($emp_no,$mon,$yr){

		$this->db->where('YR',$yr);
		$this->db->where('MON',$mon);
		$this->db->where('EMPNO',$emp_no);
		$res= $this->db->get('acc_pay_details');

		if($res->num_rows() > 0)
			return $res->result();

		return false;

	}

	function getPayDetailsPrintTemp($emp_no,$mon,$yr){

		$this->db->where('YR',$yr);
		$this->db->where('MON',$mon);
		$this->db->where('EMPNO',$emp_no);
		$res= $this->db->get('acc_pay_details_temp');

		if($res->num_rows() > 0){
			return $res->result();
		}else{
			return getPayDetailsPrint($emp_no,$mon,$yr);
		}

		return false;

	}
}


 ?>