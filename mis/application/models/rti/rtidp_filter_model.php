<?php
class Rtidp_filter_model extends CI_Model{

	var $table_rti_record = 'rti_record';
	var $table_cover_record = 'rti_phase';
	var $table_depts = 'departments';

	function __construct(){
		parent::__construct();
	}

	function get_rti_date($starton , $endon){
		$session_dep = $this->session->userdata('dept_name');

		$query=$this->db->query("SELECT rti_record.filer_name, rti_record.rti_cat, rti_phase.rti_no, rti_phase.sent_on, 
			rti_phase.replied_on FROM rti_record, rti_phase WHERE STR_TO_DATE(rti_phase.sent_on, '%Y-%m-%d') >= '$starton'
			AND STR_TO_DATE(rti_phase.replied_on, '%Y-%m-%d') <= '$endon' AND rti_phase.sent_to IN ('$session_dep') 
			AND rti_phase.rti_no=rti_record.rti_no AND rti_phase.reply NOT IN ('') ");

		/*
		$this->db->select('*');
		$this->db->where('STR_TO_DATE(sent_on, "%Y-%m-%d") >=', $starton);
		$this->db->where('STR_TO_DATE(replied_on, "%Y-%m-%d") <=', $endon);
		//$this->db->where('STR_TO_DATE(uploaded_on, "%Y-%m-%d") !=', 'STR_TO_DATE(replied_on, "%Y-%m-%d")');
		$this->db->where('reply !=', '');
		$this->db->where('sent_to =', $session_dep);
		$this->db->from($this->table_cover_record);
		$query = $this->db->get();
		*/

		return $query->result();
	}

	function get_rti_cat($rticat){
		$session_dep = $this->session->userdata('dept_name');
		//$query = $this->db->get_where($this->table_cover_record, array( 'uploaded_on >='=>$starton , 'replied_on <='=>$endon ) );
		
		$query=$this->db->query("SELECT rti_record.filer_name, rti_phase.reply, rti_phase.rti_no, rti_phase.sent_on, 
			rti_phase.replied_on FROM rti_record, rti_phase WHERE rti_record.rti_cat IN ('$rticat') AND 
			rti_phase.sent_to IN ('$session_dep') AND rti_phase.rti_no=rti_record.rti_no ");
		
		return $query->result();
	}

}