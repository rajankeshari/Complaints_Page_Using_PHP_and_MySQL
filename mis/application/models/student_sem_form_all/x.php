<?php
 class x extends CI_Model
 {
 	var $table_hm_form = 'hm_form';
	var $table_hm_minor_details = 'hm_minor_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function get_hm_form($userid)
	{
        $query = $this->db->query("SELECT form_id,honours,honours_agg_id,honour_hod_status,minor,minor_hod_status,dept_id 
        	FROM hm_form WHERE admn_no='$userid'");
       return $query->result();
	}
	function get_hm_minor_details($fm_id)
	{
         $query = $this->db->query("SELECT minor_agg_id ,dept_id, course_id, branch_id
         	FROM hm_minor_details 
         	WHERE form_id=$fm_id and offered='1'");
         return $query->result();
	}
	//Getting honours_aggr_id if offered  
	function get_hm_elective($userid)
	{
		$query = $this->db->query("SELECT honours_agg_id,form_id FROM hm_form WHERE admn_no='$userid' and honours='1' and honour_hod_status='Y' ");
       if($query->num_rows()>0)
       	return $query->result();
       return false;
	}
 }
?>