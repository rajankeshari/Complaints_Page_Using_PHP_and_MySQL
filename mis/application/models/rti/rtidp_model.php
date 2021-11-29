<?php
class Rtidp_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function update_rti(){
		$session_dep = $this->session->userdata('dept_name');
		//$department='Computer Science and Engineering';

		$not_replied=$this->db->query("SELECT rti_record.rti_file, rti_phase.rti_no, rti_phase.cover_letter FROM rti_record, rti_phase WHERE 
			rti_phase.reply='' AND rti_phase.sent_to='$session_dep' AND rti_phase.rti_no=rti_record.rti_no ");

		if ($not_replied -> num_rows() > 0) {
			return $not_replied->result();
		}else{
			return NULL;
		}
		
	}

/*	function insert_reply($full_path_reply){
		$rtino=$_POST['rtino'];
		$session_dep = $this->session->userdata('dept_name');
		
		//$temp=".$this->db->escape($full_path_reply)."
		$sql="UPDATE rti_phase SET reply ='$full_path_reply' 
			WHERE rti_no='$rtino' AND sent_to='$session_dep' ";

		$this->db->query($sql);
	}
*/
	function insert_r($data)
	{
		extract($data);
		$array = array('rti_no' => $rti_no, 'sent_to' => $session_dep);
	    $this->db->where($array);
	    $this->db->update($phase, array('reply' => $reply));
	}
}	