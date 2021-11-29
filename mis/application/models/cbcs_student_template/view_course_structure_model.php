<?php
class View_course_structure_model Extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	//get Department list
	function get_department_list(){
		$sql="SELECT a.* FROM cbcs_departments a WHERE a.type='academic' AND a.status='1' ORDER BY a.name";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get course list
	function get_course_list($course){
		$sql="SELECT a.* FROM cbcs_courses a WHERE a.status='1' and a.id='$course'";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get branch list
	function get_branch_list($branch){
		$sql="SELECT a.* FROM cbcs_branches a WHERE a.status='1' and a.id='$branch'";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get session year list
	function get_session_year_list(){
		$sql="SELECT a.session_year FROM mis_session_year a ORDER BY a.id desc";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//Get Session list
	function get_session_list(){
		$sql="SELECT a.session FROM mis_session a";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//Subject List
	function get_offered_subject_details($dept){
		$q='';
		if($dept != ''){
			$q=" WHERE a.dept_id='".$dept."' ";
		}
		$sql="SELECT a.*,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id $q
group by a.id
ORDER BY a.id";
		$result=$this->db->query($sql);
		//echo $this->db->last_query();exit;
		return $result->result();

	}

	function get_offered_subject_filter_structure($dept,$course,$branch,$sem,$sess,$sy){
	if($branch == 'comm' and $course == 'comm'){
$sql="SELECT a.sub_category,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name
FROM cbcs_subject_offered a
JOIN cbcs_comm_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component /*AND e.course_id=a.course_id*/
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='comm' AND a.course_id='comm' AND a.branch_id='comm' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' 
GROUP BY b.course_component,b.sequence
ORDER BY a.id";
	}else{
	$sql="SELECT a.sub_category,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' 
GROUP BY b.course_component,b.sequence
ORDER BY a.id";
}
	$result=$this->db->query($sql);
		//echo $this->db->last_query();exit;
	return $result->result();
	}
	//Subject List With Filter
	function get_offered_subject_filter($dept,$course,$branch,$sem,$sess,$sy){

		/*$sql="SELECT a.*,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp,f.name AS d_name,g.name AS c_name,h.name AS b_name
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND CONCAT(b.course_component,b.sequence)=a.sub_category
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' 
ORDER BY a.id";*/ 
if($branch == 'comm' and $course == 'comm'){
$sql="SELECT a.*,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp,f.name AS d_name,g.name AS c_name,h.name AS b_name
FROM cbcs_subject_offered a
JOIN cbcs_comm_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component /*AND e.course_id=a.course_id*/
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='comm' AND a.course_id='comm' AND a.branch_id='comm' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' 
group by a.id
ORDER BY a.id";
}
else
{
$sql="SELECT a.*,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp,f.name AS d_name,g.name AS c_name,h.name AS b_name
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' 
group by a.id
ORDER BY a.id";
}
		$result=$this->db->query($sql);
		//echo $this->db->last_query();exit;
		return $result->result();
	}

	function get_offered_subject_filter_structure_new($dept,$course,$branch,$sem,$sess,$sy,$cc){
		$sql="SELECT a.sub_category,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' and b.course_component='$cc'
GROUP BY b.course_component,b.sequence
ORDER BY a.id";

	$result=$this->db->query($sql);
		//echo $this->db->last_query();exit;
	return $result->result();
	}


	function get_offered_subject_filter_new($dept,$course,$branch,$sem,$sess,$sy,$cc){

		$sql="SELECT a.*,b.`status`,b.lecture AS c_lecture,b.tutorial AS c_tutorial, b.practical AS c_practical,b.course_component,b.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp,f.name AS d_name,g.name AS c_name,h.name AS b_name
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
LEFT JOIN cbcs_departments f ON f.id=a.dept_id
LEFT JOIN cbcs_courses g ON g.id=a.course_id
LEFT JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem'AND a.`session`='$sess' AND a.session_year='$sy' and b.course_component='$cc'
ORDER BY a.id";
		$result=$this->db->query($sql);
		//echo $this->db->last_query();exit;
		return $result->result();
	}

function get_offered_coursestructure_filter($dept,$course,$branch,$sem,$sess,$sy){
	if($branch == 'comm' and $course == 'comm'){
$sql="SELECT a.`status`,a.lecture AS c_lecture,a.tutorial AS c_tutorial, a.practical AS c_practical,a.course_component,a.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp 

FROM cbcs_comm_coursestructure_policy a

JOIN cbcs_curriculam_policy c ON c.id=a.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=a.course_component /*AND e.course_id=a.course_id*/

WHERE a.course_id='comm' AND a.sem='$sem'
and concat(a.course_component,a.sequence) not in (
SELECT if(a.unique_sub_pool_id = 'NA' OR a.unique_sub_pool_id = '',a.sub_category,a.unique_sub_pool_id)
FROM cbcs_subject_offered a
JOIN cbcs_comm_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component /*AND e.course_id=a.course_id*/
WHERE a.dept_id='comm' AND a.course_id='comm' AND a.branch_id='comm' AND a.semester='$sem' AND a.`session`='$sess' AND a.session_year='$sy'
ORDER BY a.id)";
	}else{
	$sql="SELECT a.`status`,a.lecture AS c_lecture,a.tutorial AS c_tutorial, a.practical AS c_practical,a.course_component,a.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp 

FROM cbcs_coursestructure_policy a

JOIN cbcs_curriculam_policy c ON c.id=a.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=a.course_component AND e.course_id=a.course_id

WHERE a.course_id='$course' AND a.sem='$sem'
and concat(a.course_component,a.sequence) not in (
SELECT if(a.unique_sub_pool_id = 'NA' OR a.unique_sub_pool_id = '',a.sub_category,a.unique_sub_pool_id)
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem' AND a.`session`='$sess' AND a.session_year='$sy'
ORDER BY a.id)";
}
$result=$this->db->query($sql);
//echo $this->db->last_query();exit;
return $result->result();

}

function get_offered_coursestructure_filter_new($dept,$course,$branch,$sem,$sess,$sy,$cc){
	$sql="SELECT a.`status`,a.lecture AS c_lecture,a.tutorial AS c_tutorial, a.practical AS c_practical,a.course_component,a.sequence,e.name,c.course_credit_min,c.course_credit_max,d.course_id,d.mincp,d.maxcp 

FROM cbcs_coursestructure_policy a

JOIN cbcs_curriculam_policy c ON c.id=a.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=a.course_component AND e.course_id=a.course_id

WHERE a.course_id='$course' AND a.sem='$sem' AND a.course_component='$cc'
and concat(a.course_component,a.sequence) not in (
SELECT if(a.unique_sub_pool_id = 'NA' OR a.unique_sub_pool_id = '',a.sub_category,a.unique_sub_pool_id)
FROM cbcs_subject_offered a
JOIN cbcs_coursestructure_policy b ON b.course_id=a.course_id AND b.sem=a.semester AND (CONCAT(b.course_component,b.sequence)=a.sub_category OR CONCAT(b.course_component,b.sequence)=a.unique_sub_pool_id)
JOIN cbcs_curriculam_policy c ON c.id=b.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy d ON d.id=c.cbcs_credit_points_policy_id
JOIN cbcs_course_component e ON e.id=b.course_component AND e.course_id=a.course_id
WHERE a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem' AND a.`session`='$sess' AND a.session_year='$sy'
ORDER BY a.id)";
$result=$this->db->query($sql);
return $result->result();
}
}

?>