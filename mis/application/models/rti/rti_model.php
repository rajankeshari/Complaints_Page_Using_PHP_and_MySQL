<?php
class Rti_model extends CI_Model{

	var $table_rti_record = 'rti_record';
	var $table_cover_record = 'rti_phase';
	var $table_depts = 'departments';
	var $table_cat = 'rti_category';

	function __construct(){
		parent::__construct();
	}

	function get_depts()
	{
		$query = $this->db->get_where($this->table_depts, array('type'=>'academic'));
		return $query->result();
	}

	function get_noreply_rtino()
	{
		$query = $this->db->get_where($this->table_rti_record, array('full_reply'=>' '));
		return $query->result();
	}

	function get_rti_cat()
	{
		$query = $this->db->query("SELECT cat FROM rti_category");
		return $query->result();
	}

	function insert_rti($data)
	{
		$this->db->insert($this->table_rti_record,$data);
	}

/*	function insert_filer($full_path_rtifile){

		$rtino=$_POST['rtino'];
		$filername=$_POST['filername'];
		$fileradd=$_POST['fileradd'];
		$filedon=$_POST['filedon'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$pin_code=$_POST['pin_code'];
		$rti_cat=$_POST['rti_cat'];
		
		$sql="INSERT INTO rti_record (rti_no, filer_name, filer_add, city, state, pin_code, rti_cat, rti_file, filed_on) 
			VALUES (".$this->db->escape($rtino).", 
					".$this->db->escape($filername).",
					".$this->db->escape($fileradd).",
					".$this->db->escape($city).",
					".$this->db->escape($state).",
					".$this->db->escape($pin_code).",
					".$this->db->escape($rti_cat).",
					".$this->db->escape($full_path_rtifile).",
					".$this->db->escape($filedon)."
					)";

		$this->db->query($sql);
		//if($this->db->affected_rows()==1)
		//	return $rtino;
	}
*/
	function get_rti_details_by_rtino($rtino)
	{
		$query = $this->db->get_where($this->table_rti_record,array('rti_no'=>$rtino));
		//if($query->num_rows() > 0) 
			return $query->result();
	}

	function get_cover_details_by_rtino($rtino , $dep)
	{
		$query = $this->db->get_where($this->table_cover_record,array('rti_no'=>$rtino , 'sent_to'=>$dep));
		//if($query->num_rows() > 0) 
			return $query->result();
	}

	function insert_cover($data)
	{
		$this->db->insert($this->table_cover_record,$data);
	}

/*	function insert_phase($full_path_cover){

		$rtino=$_POST['rtino'];
		$sentto=$_POST['sentto'];
		
		$sql="INSERT INTO rti_phase (rti_no, sent_to, cover_letter) 
			VALUES (".$this->db->escape($rtino).",
					".$this->db->escape($sentto).", 
					".$this->db->escape($full_path_cover)."
					)";
		
		$this->db->query($sql);

	}
*/
	function view_reply_recived(){
		$this->load->library('session');
        $rtino_p=$_POST['rtino'];
        $this->session->set_flashdata('item', $rtino_p);
		
		$view_reply=$this->db->query("SELECT reply, sent_to FROM rti_phase WHERE 
			reply NOT IN ('') AND rti_no='".$rtino_p."' ");

		if ($view_reply -> num_rows() > 0) {
			return $view_reply->result();
		}else{
			return NULL;
		}
	}

	function view_reply_pending(){
		$temp=$_POST['rtino'];
		$view_reply=$this->db->query("SELECT sent_to FROM rti_phase WHERE 
			reply IN ('') AND rti_no='".$temp."'");

		if ($view_reply -> num_rows() > 0) {
			return $view_reply->result();
		}else{
			return NULL;
		}
	}

	function insert_full($data)
	{
		extract($data);
	    $this->db->where('rti_no', $rti_no);
	    $this->db->update($record, array('full_reply' => $full_reply));
	    //return true;
		//$this->db->insert($this->table_cover_record,$data);
	}//update $table_name set title='$title' where emp_no=$id

/*	function insert_fullreply($full_path_fullreply){
		$this->load->library('session');  //$_POST['rtino'];
		$temp=$this->session->flashdata('item');
		
		$sql="UPDATE rti_record SET full_reply ='".$full_path_fullreply."' 
			WHERE rti_no='".$temp."' ";

		$this->db->query($sql);
	}
*/
}