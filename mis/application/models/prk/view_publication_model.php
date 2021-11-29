<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class View_publication_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getOwnPublications($id){
		$query = " SELECT rec_id FROM prk_ism_author WHERE emp_id = '$id' ";
		return $this->db->query($query)->result();
	}
	
	public function checkApprovalOfPublication($rec_id){
		$query = " SELECT * FROM prk_ism_author WHERE rec_id = '$rec_id' AND (notify_status = '0' OR notify_status = '2')  ";
		if ($this->db->query($query)->num_rows() == 0)
			return true;
		return false;
	}
	public function getPublicationType($rec_id){
		$query = " SELECT type FROM prk_record WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->type;
	}
	public function getPublicationDetails($rec_id,$type){
		if ($type == 1 || $type == 2)
			$query = " SELECT * FROM prk_journal WHERE rec_id = '$rec_id' ";
		if ($type == 3 || $type == 4)
			$query = " SELECT * FROM prk_conference WHERE rec_id = '$rec_id' ";
		if ($type == 5 || $type == 6)
			$query = " SELECT * FROM prk_book WHERE rec_id = '$rec_id' ";
		if ($type == 7)
			$query = " SELECT * FROM prk_patent WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getfile($rec_id){
		$query = " SELECT path FROM publication_keeper WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getIsmAuthors($rec_id){
		$query = " SELECT emp_id,position FROM prk_ism_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getUserNameByUserId($id){
		$query = " SELECT concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
	}
	public function getOtherAuthors($rec_id){
		$query = " SELECT concat(first_name,' ',middle_name,' ',last_name) AS name,position FROM prk_other_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
}
