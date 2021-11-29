<?php
class Add_form extends CI_Model
{
	var $sem_form = 'reg_regular_form';
	var $sem_fee = 'reg_regular_fee';
	var $sem_subject = 'reg_regular_elective_opted';
	var $carryover = 'reg_carryover_form';
	var $HM = 'stu_sem_honour_minor';
	var $Cbranch = 'stu_change_branch';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function insertSemForm($data){
		$this->db->insert($this->sem_form, $data);
		return $this->db->_error_message(); 
	}
	function updateSemForm($data,$con,$formtype=null){
		$this->db->update(($formtype=='O'|| $formtype=='S'?'reg_other_form':$this->sem_form), $data,$con);
		return $this->db->_error_message(); 
	}
	function insertSemFee($data){
		$this->db->insert($this->sem_fee, $data);
		return $this->db->_error_message(); 
	}
	function updateSemFee($data,$con){
		$this->db->update($this->sem_fee, $data,$con);
		return $this->db->_error_message(); 
	}
	
	function insertSemSubject($data){
		$this->db->insert($this->sem_subject, $data);
		return $this->db->_error_message(); 
	}
	 function updateSemSubject($data,$con){
		$this->db->update($this->sem_subject, $data,$con);
		return $this->db->_error_message(); 
	}
	function insertCarryover($data){
		$this->db->insert($this->carryover, $data);
		return $this->db->_error_message(); 
	}
	
	function insertHM($data){
		$this->db->insert($this->HM, $data);
		return $this->db->_error_message();
	}
	
	function insertCB($data){
		$this->db->insert($this->Cbranch, $data);
		return $this->db->_error_message();
	}
	
	function delSemSubject($data){
		$this->db->delete($this->sem_subject, array('form_id'=>$data));
		return $this->db->_error_message();
	}
	
	function getSemSubject($data){
		$q=$this->db->get_where($this->sem_subject, array('form_id'=>$data));
		//echo $q->num_rows(); die();
		if($q->num_rows() > 0)
			return $q->num_rows();
		
		return false;
	}
}?>