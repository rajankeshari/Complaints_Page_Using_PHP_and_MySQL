<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Stu_project_assign_model extends CI_Model {

function __construct() {
    parent::__construct();
    
}

function stu_project_list($admn){
	$sql="SELECT a.*, CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) AS name, IF(a.guide=a.assign_by,'Guide','Co-guide') AS role
FROM project_offer a
/*INNER JOIN project_guide g ON g.id=a.project_id*/
LEFT JOIN user_details u ON u.id=a.assign_by
WHERE a.admn_no='$admn' /*AND g.is_status='active'*/
ORDER BY a.assign_date DESC";
	$result=$this->db->query($sql);
	return $result->result();
}

function update_student_reply($stu_reply,$reply_file,$reply_date,$id,$project_id,$admn){
	$sql="UPDATE project_offer SET stu_reply='$stu_reply',stu_reply_file='$reply_file',stu_reply_date='$reply_date' WHERE id='$id' AND project_id='$project_id' AND admn_no='$admn'";

	if($this->db->query($sql))
		return true;
	else
		return false;
	
}

function get_guide_id($id){
	$sql="SELECT a.guide FROM project_offer a WHERE a.id='$id'";
	$result=$this->db->query($sql);
	return $result->result();
}

}
