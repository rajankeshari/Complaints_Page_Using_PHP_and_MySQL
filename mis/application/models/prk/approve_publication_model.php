<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Approve_publication_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getPendingApprovalPublications($id){
		$query = " SELECT rec_id FROM prk_ism_author WHERE emp_id = '$id' AND notify_status = '0' ";
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
	public function approvePublication($rec_id,$emp_id){
		$query = " UPDATE prk_ism_author SET notify_status = '1' WHERE rec_id = '$rec_id' AND emp_id = '$emp_id' ";
		$this->db->query($query);
	}
	public function declinePublication($rec_id,$emp_id){
		$query = " UPDATE prk_ism_author SET notify_status = '2' WHERE rec_id = '$rec_id' AND emp_id = '$emp_id' ";
		$this->db->query($query);
	}
	public function getPublicationOwner($rec_id){
		$query = " SELECT owner FROM prk_record WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->owner;
	}
}
