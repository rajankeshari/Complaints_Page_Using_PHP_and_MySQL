<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Project_assign_model extends CI_Model {

function __construct() {
    parent::__construct();
    
}

function get_department_list(){
	$sql="SELECT a.* FROM departments a";
	$result=$this->db->query($sql);
	return $result->result();
}

function get_type(){
	$sql="SELECT a.`type` FROM project_guide a GROUP BY a.`type`";
	$result=$this->db->query($sql);
	return $result->result();
}

function get_student_list($id,$type){
	// $sql="SELECT a.*,CONCAT_WS(' ',u.first_name,u.middle_name,u.last_name) AS name,d.name as dept 
	// 	FROM project_guide a
	// 	LEFT JOIN user_details u ON u.id=a.admn_no
	// 	LEFT JOIN departments d ON d.id=a.department
	// 	WHERE a.`type`='$type' AND (a.guide='$id' OR a.co_guide='$id') AND a.is_status='Active'";
	$sql="(SELECT a.*,CONCAT_WS(' ',u.first_name,u.middle_name,u.last_name) AS name,d.name as dept 
FROM project_guide a
LEFT JOIN user_details u ON u.id=a.admn_no
LEFT JOIN departments d ON d.id=a.department
INNER JOIN users f ON f.id=u.id AND f.status='A'
WHERE a.`type`='$type' AND (a.guide='$id' OR a.co_guide='$id') AND a.is_status='Active' AND a.course!='JRF')
UNION 
(SELECT a.*,CONCAT_WS(' ',u.first_name,u.middle_name,u.last_name) AS name,d.name as dept 
FROM project_guide a
LEFT JOIN user_details u ON u.id=a.admn_no
LEFT JOIN departments d ON d.id=a.department
INNER JOIN stu_academic e ON e.admn_no=a.admn_no
INNER JOIN users f ON f.id=u.id AND f.status='A'
WHERE a.`type`='$type' AND (a.guide='$id' OR a.co_guide='$id') AND a.is_status='Active' AND a.course='JRF' AND e.other_rank='fulltime')";
	$result=$this->db->query($sql);
	return $result->result();
}

function get_project_data($project_id,$admn_no){
	$sql="SELECT a.*  FROM project_guide a WHERE a.id='$project_id' AND a.admn_no='$admn_no'";
	$result=$this->db->query($sql);
	return $result->result();
}

function save_project_data($data){
	if($this->db->insert('project_offer',$data))
		return true;
	else
		return false;
}

function student_project_list($project,$admn,$id){
	$sql="SELECT a.*,CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) AS name,
	(SELECT CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) FROM user_details b WHERE b.id=a.admn_no) AS stu_name, 
	if(a.guide=a.assign_by,'Guide','Co-guide') AS role
	FROM project_offer a
LEFT JOIN user_details u ON u.id=a.assign_by
WHERE a.project_id='$project' AND a.admn_no='$admn' AND (a.guide='$id' OR a.co_guide='$id') ORDER BY a.assign_date desc";
	$result=$this->db->query($sql);
	return $result->result();
}

function save_project_evalution($evalution,$fellowship,$reply_date,$id,$project_id,$guide){
	$sql="UPDATE project_offer SET evalution='$evalution',fellowship_rates='$fellowship',evalution_date='$reply_date' WHERE id='$id' AND project_id='$project_id' AND guide='$guide'"; 
	if($this->db->query($sql))
		return true;
	else
		return false;
}


function project_report_list($month,$year,$type,$dept){
	if($dept!=''){
		$d="u.dept_id='$dept' AND";
	}
	else
	{
		$d='';
	}
	$sql="SELECT a.*, CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) AS name,
CONCAT_WS(' ',u1.first_name,u1.middle_name,u1.last_name) AS stu_name,
(SELECT b.id FROM project_offer b WHERE b.month='$month' AND b.year='$year' ORDER BY  b.assign_date DESC LIMIT 1) AS print
FROM project_offer a
LEFT JOIN user_details u ON u.id=a.guide
LEFT JOIN user_details u1 ON u1.id=a.admn_no
JOIN project_guide g ON g.id=a.project_id
WHERE $d a.month='$month' AND a.year='$year' AND g.`type`='$type'
ORDER BY a.assign_date DESC";

	$query=$this->db->query($sql);
if ($query->num_rows() > 0){
	
              return $query->result();
}else{
	
              return false;
}
}

function get_department_name($dept){
	$sql="SELECT a.name FROM departments a WHERE a.id='$dept'";
	$result=$this->db->query($sql);
	return $result->result();
}

function print_fellowship_data($month,$year,$type,$dept){

	/*$sql="SELECT a.*,
	CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) AS name,
CONCAT_WS(' ',u1.first_name,u1.middle_name,u1.last_name) AS stu_name
FROM project_offer a
LEFT JOIN user_details u ON u.id=a.guide
LEFT JOIN user_details u1 ON u1.id=a.admn_no
	WHERE a.id='$id'";*/
	if($dept!=''){
		$d="u.dept_id='$dept' AND";
	}
	else
	{
		$d='';
	}
	$sql="SELECT x.* FROM 
	
(	SELECT a.*,
	CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) AS name,
CONCAT_WS(' ',u1.first_name,u1.middle_name,u1.last_name) AS stu_name
FROM project_offer a 
JOIN project_guide g ON g.id=a.project_id
JOIN user_details u ON u.id=a.guide
LEFT JOIN user_details u1 ON u1.id=a.admn_no
WHERE $d a.month='$month' AND a.year='$year' AND g.`type`='$type' GROUP BY a.admn_no,a.evalution_date ORDER BY
a.admn_no,a.evalution_date DESC 
)x
 GROUP BY x.admn_no";
	$result=$this->db->query($sql);
	return $result->result();
}
}
?>