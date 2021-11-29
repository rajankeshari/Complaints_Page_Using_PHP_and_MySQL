<?php
class Non_cbcs_model extends CI_Model{
	function __construct() {
        parent::__construct();
    }
	//get session year
	function get_session_year($dept){
		//$sql="SELECT a.session_year FROM mis_session_year a ORDER BY a.id DESC";
		$sql="select a.session_year from old_course_structure a where dept_id='$dept' group by a.session_year";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get session
	function get_session($dept){
		//$sql="SELECT a.`session` FROM mis_session a";
		$sql="select a.session from old_course_structure a where dept_id='$dept' group by a.`session`";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get Course list
	function get_course_details($dept){
		// $sql="SELECT a.*,b.course_id,b.branch_id
		// FROM dept_course a
		// JOIN course_branch b ON b.course_branch_id=a.course_branch_id
		// WHERE a.dept_id='$dept'
		// GROUP BY b.course_id,b.branch_id
		// ";
		$sql="select a.course_id,a.branch_id from old_course_structure a where dept_id='$dept' group by a.course_id,a.branch_id order by a.branch_id";
		$result=$this->db->query($sql);
		return $result->result();
	}
	//get duration details
	function get_duration_year($sy,$sess,$dept,$course,$branch){
		//$sql="SELECT a.duration FROM courses a where a.id='$course'";
		$sql="SELECT a.semester 
FROM old_course_structure a 
where a.dept_id='$dept' and a.course_id='$course' and a.branch_id='$branch' and a.`session`='$sess' and a.session_year='$sy'
group by a.semester";
		$result=$this->db->query($sql);
		return $result->result();
	}

	//get course structure
	function get_course_structure($sy,$sess,$dept,$course,$branch,$sem){
// 		$c=$course.'_';
// 		$sql="SELECT
// REPLACE(a.aggr_id,'$c','') as cc
// FROM course_structure a
// WHERE a.semester='$sem' AND a.aggr_id LIKE '%$course%'
// GROUP BY a.aggr_id
// order by a.id desc";
		$sql="SELECT a.aggr_id,a.batch  
FROM old_course_structure a 
where a.dept_id='$dept' and a.course_id='$course' and a.branch_id='$branch' and a.`session`='$sess' and a.session_year='$sy' and a.semester='$sem'
group by a.aggr_id";
		$result=$this->db->query($sql);
		return $result->result();	
	}
	
	function get_subject_list($sem,$agg_id,$dept,$sy,$sess,$course){
		/*$sql="SELECT a.*,b.elective,b.subject_id,b.name,b.lecture,b.tutorial,b.practical,b.credit_hours,b.contact_hours
		FROM course_structure a 
INNER JOIN subjects b ON b.id=a.id
WHERE a.aggr_id='$agg_id' AND a.semester='$sem'";*/
		/*$sql="SELECT x.*,c.sub_category
from (SELECT a.*, b.elective, b.subject_id, b.name,b.lecture,b.tutorial,b.practical,b.credit_hours,b.contact_hours,count(b.subject_id) as countof, b.`type`
FROM course_structure a
JOIN subjects b ON a.id = b.id
where a.aggr_id = '$agg_id' and a.semester = '$sem'
group by b.subject_id) x
left join old_subject_offered c on c.semester='$sem' and c.sub_code=x.subject_id  and c.unique_sub_id=x.id and c.dept_id='$dept' AND c.session_year='$sy' AND c.`session`='$sess' AND c.course_id='$course'
group by x.id order by x.sequence";*/
	$course=explode('_',$course);
		$sql="SELECT x.*,c.sub_category
from (SELECT a.*, b.elective, b.subject_id, b.name,b.lecture,b.tutorial,b.practical,b.credit_hours,b.contact_hours,count(b.subject_id) as countof, b.`type`
FROM course_structure a
JOIN subjects b ON a.id = b.id
where a.aggr_id = '$agg_id' and a.semester = '$sem'
group by b.subject_id) x
left join old_subject_offered c on c.semester='$sem' and c.sub_code=x.subject_id  and c.unique_sub_id=x.id and c.dept_id='$dept' AND c.session_year='$sy' AND c.`session`='$sess' AND c.course_id='$course[0]'
group by x.id order by x.sequence";
		$result=$this->db->query($sql);
		return $result->result();		
	}

	function insert_subject_offered($data){
		$this->db->insert('old_subject_offered',$data);
		//echo $this->db->last_query();
		// if($this->db->insert('old_subject_offered',$data))
  //           return true;
  //       else
  //           return false;
	}

	function get_offered_course($sy,$session,$dept,$course,$branch,$sem){
		/*$sql="select a.* 
		from old_subject_offered a
		where a.session_year='$sy' and a.`session`='$session' and a.dept_id='$dept' and a.course_id='$course' and a.branch_id='$branch' and a.semester='$sem' order by a.sub_category,a.created_on";*/
		if (strpos($branch, ' ') !== false) {
    		$branch=str_replace(" ","+",$branch);
		}
		$sql="SELECT a.*,b.sub_offered_id,GROUP_CONCAT(distinct CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name),' / ',CASE WHEN b.coordinator='1' THEN 'Yes' WHEN b.coordinator='0' THEN 'No' END SEPARATOR '<br>') AS fname
FROM old_subject_offered a
left join old_subject_offered_desc b on b.sub_offered_id=a.id
left join user_details c on c.id=b.emp_no
where a.session_year='$sy' and a.`session`='$session' and a.dept_id='$dept' and a.course_id='$course' and a.branch_id='$branch' and a.semester='$sem'
group by a.id
ORDER BY a.sub_category,a.created_on";
		$result=$this->db->query($sql);
		return $result->result();
	}

	function get_subject_details($id){
		$sql="select a.* from old_subject_offered a where a.id='$id'";
		$result=$this->db->query($sql);
		return $result->result();
	}

	 function insert_batch_subject_offered_child($data)
    {
        if($this->db->insert_batch('old_subject_offered_desc',$data))
            return TRUE;
        else
            return FALSE;
    }

    function delete_rowid_subject_offered_table($id) {
        $this->db->where('id', $id);
        $this->db->delete('old_subject_offered');
    }

    function update_subject_code($old,$new){
    	$sql="update subjects set subject_id='$new' where subject_id='$old'";
    	if($this->db->query($sql)){
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	}
    }

    function update_subject_offered($sub_offered_id,$full_marks){
    	$sql="UPDATE old_subject_offered SET fullmarks='$full_marks' WHERE id='$sub_offered_id'";
    	$this->db->query($sql);
    }
}