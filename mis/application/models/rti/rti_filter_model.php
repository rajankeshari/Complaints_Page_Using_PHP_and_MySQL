<?php
class Rti_filter_model extends CI_Model{

	var $table_rti_record = 'rti_record';
	var $table_cover_record = 'rti_phase';
	var $table_depts = 'departments';

	function __construct(){
		parent::__construct();
	}

	function get_rti_date($starton , $endon){
		//$query = $this->db->get_where($this->table_rti_record, array( 'uploaded_on >='=>$starton , 'replied_on <='=>$endon ) );
		
		$this->db->select('*');
		$this->db->where('STR_TO_DATE(uploaded_on, "%Y-%m-%d") >=', $starton);
		$this->db->where('STR_TO_DATE(replied_on, "%Y-%m-%d") <=', $endon);
		//$this->db->where('STR_TO_DATE(uploaded_on, "%Y-%m-%d") !=', 'STR_TO_DATE(replied_on, "%Y-%m-%d")');
		$this->db->where('full_reply !=', '');
		$this->db->from($this->table_rti_record);
		$query = $this->db->get();
		
		return $query->result();
	}

	function get_rti_cat($rticat){
		//$query = $this->db->get_where($this->table_rti_record, array( 'uploaded_on >='=>$starton , 'replied_on <='=>$endon ) );
		
		$this->db->select('*');
		$this->db->where('rti_cat =', $rticat);
		$this->db->from($this->table_rti_record);
		$query = $this->db->get();
		
		return $query->result();
	}

}