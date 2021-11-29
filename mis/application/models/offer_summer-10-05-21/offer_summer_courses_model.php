<?php

class Offer_summer_courses_model extends CI_Model
{
	function offered_subject_list(){
		$sql="SELECT Y.* from(SELECT 'CBCS' AS stu_type,a.*,'' AS map_id,GROUP_CONCAT(CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name)) AS emp_name,d.id AS permission
		FROM cbcs_subject_offered a
		LEFT JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
		INNER JOIN user_details c ON b.emp_no=c.id
		LEFT JOIN pre_stu_course d ON d.sub_offered_id=CONCAT('o',a.id)
		WHERE a.`session`='summer'
		GROUP BY a.id
		UNION
		SELECT 'NON-CBCS' AS stu_type,a.*,GROUP_CONCAT(CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name)) AS emp_name,d.id AS permission
		FROM old_subject_offered a
		LEFT JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
		INNER JOIN user_details c ON b.emp_no=c.id
		LEFT JOIN pre_stu_course d ON d.sub_offered_id=CONCAT('o',a.id)
		WHERE  a.`session`='summer'
		GROUP BY a.id) Y ORDER BY Y.created_on desc";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_subject_list($sy,$sess,$stu_type,$dept,$course,$branch,$sem){
		if($stu_type == 'CBCS'){
			$tbl='cbcs_subject_offered';
			$tbl1='cbcs_subject_offered_desc';
		}else{
			$tbl='old_subject_offered';
			$tbl1='old_subject_offered_desc';
		}
		if($dept=='comm'){
			$ic="1=1";
		}else{
			$ic="a.sub_category not like 'IC%'";
		}
		$sql="SELECT X.*,c.id AS offered FROM (SELECT a.*,CONCAT_WS('|',a.id,a.session_year,a.session,a.dept_id,a.course_id,a.branch_id,a.semester,a.unique_sub_pool_id,a.unique_sub_id,a.sub_name,a.sub_code,a.lecture,a.tutorial,a.practical,a.credit_hours,a.contact_hours,a.sub_type,a.wef_year,a.wef_session,a.pre_requisite,a.pre_requisite_subcode,a.fullmarks,a.no_of_subjects,a.sub_category,a.sub_group,a.criteria,a.minstu,a.maxstu,a.remarks) AS t_value,GROUP_CONCAT(CONCAT_WS('|',b.part,b.emp_no,b.coordinator,b.sub_id,b.section)) AS description,GROUP_CONCAT(CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name,'[',c.id,']') SEPARATOR '<hr>') AS emp
		FROM $tbl a
		INNER JOIN $tbl1 b ON a.id=b.sub_offered_id
		inner JOIN user_details c ON b.emp_no=c.id 
		WHERE a.session_year='$sy' AND a.`session`='$sess' AND a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem' AND $ic group by a.id) X
LEFT JOIN $tbl c ON c.session_year=X.session_year AND c.dept_id=X.dept_id AND c.course_id=X.course_id AND c.branch_id=X.branch_id AND c.sub_code=X.sub_code AND c.`session`='summer'";
		$query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}

	function save_selected_courses($data,$stu_type){
		if($stu_type == 'CBCS'){
			$tbl='cbcs_subject_offered';
		}else{
			$tbl='old_subject_offered';
		}
		$sql=$this->db->insert($tbl,$data);
		return $this->db->insert_id();
	}

	function save_selected_courses_desc($data1,$stu_type){
		if($stu_type == 'CBCS'){
			$tbl='cbcs_subject_offered_desc';
		}else{
			$tbl='old_subject_offered_desc';
		}
		$sql=$this->db->insert($tbl,$data1);
		return $this->db->insert_id();
	}

	function delete_selected_courses($type,$id){
		if($type == 'CBCS'){
			$tbl='cbcs_subject_offered';
			$tbl1='cbcs_subject_offered_desc';
		}else{
			$tbl='old_subject_offered';
			$tbl1='old_subject_offered_desc';
		}
		$this->db->query("DELETE FROM $tbl WHERE id='$id'");
		$this->db->query("DELETE FROM $tbl1 WHERE sub_offered_id='$id'");
	} 

	function get_subject_master_list($dept){

        if(!empty($dept)){

            $sql = "select a.* from cbcs_course_master a where a.dept_id=? GROUP BY a.sub_name, a.sub_code,a.lecture,a.tutorial,a.practical order by trim(a.sub_code)";
             $query = $this->db->query($sql,array($dept));
        }else{
            $sql = "select a.* from cbcs_course_master a GROUP BY a.sub_name, a.sub_code, a.lecture,a.tutorial,a.practical order by trim(a.sub_code)";
             $query = $this->db->query($sql);
        }


        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

    function get_non_cbcs_subject_list($dept,$course,$branch,$sem,$sy,$sess){
    	$aggr=$course.'_'.$branch.'_';
    	$sql="SELECT a.*, b.elective, b.subject_id AS sub_code, b.name AS sub_name,b.lecture,b.tutorial,b.practical,b.credit_hours,b.contact_hours, b.`type` AS sub_type,'$sy' as session_year,'$sess' as session
			FROM course_structure a
			JOIN subjects b ON a.id = b.id
			where a.aggr_id LIKE '$aggr%' /*and a.semester = '$sem'*/
			group by b.subject_id 
			order by a.sequence";
		$query = $this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

    function get_subject_by_id($did,$sid){

        if( !empty($did)){

            $sql = "select * from cbcs_course_master where  dept_id=? and sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($did,$sid));
        }

        else{
             $sql = "select * from cbcs_subject_master where sub_code=? order by trim(sub_name)";
             $query = $this->db->query($sql,array($sid));
        }


        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }

    function get_subject_details($dept_id,$scode,$wef){

         if( !empty($dept_id)){

        $sql = "select a.* from cbcs_course_master a where a.dept_id=?  and a.sub_code=? and a.wef_year=? ";
        $query = $this->db->query($sql,array($dept_id,$scode,$wef));
        }else{

            $sql = "select a.* from cbcs_subject_master a where  a.wef_year=? ";
            $query = $this->db->query($sql,array($wef));

        }

        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }


    }

    function get_non_cbcs_subject_details($dept_id,$course,$branch,$sem,$scode,$wef){
    	$aggr=$course.'_'.$branch.'_';
    	$sql="SELECT a.*, b.elective, b.subject_id AS sub_code, b.name AS sub_name,b.lecture,b.tutorial,b.practical,b.credit_hours,b.contact_hours, b.`type` AS sub_type,'$wef' as session_year,'summer' as wef_session
			FROM course_structure a
			JOIN subjects b ON a.id = b.id
			where a.aggr_id LIKE '$aggr%' /*and a.semester = '$sem'*/ and b.subject_id='$scode'
			group by b.subject_id 
			order by a.sequence";
		$query = $this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }
    }

    function get_sub_name($sub_code,$type){
    	if($type=='CBCS'){
        	$sql="SELECT sub_name FROM cbcs_course_master WHERE sub_code='$sub_code'";
        }else{
        	$sql="SELECT name as sub_name FROM subjects WHERE subject_id='$sub_code'";
        }
        $result=$this->db->query($sql);
        return $result->result();
    }

    function insert_subject_offered($data,$tbl1)
    {
        if($this->db->insert($tbl1,$data))
           return $this->db->insert_id();
        else
            return FALSE;
    }

    function insert_batch_subject_offered_child($data,$tbl2)
    {
        if($this->db->insert_batch($tbl2,$data))
            return TRUE;
        else
            return FALSE;
    }

    function check_duplicate_entry($sy,$sess,$dept,$course,$branch,$sem,$sub_code,$tbl1){
    	$sql="SELECT * FROM $tbl1 a where a.session_year='$sy' and a.session='$sess' and a.dept_id='$dept' AND a.course_id='$course' AND a.branch_id='$branch' AND a.semester='$sem' and a.sub_code='$sub_code'";
    	$result=$this->db->query($sql);
    	return $result->num_rows();
    }

    function get_subject_catrgory($sy,$sem,$scode){
    	$sql = "SELECT DISTINCT sub_code,sub_name,sub_category
FROM cbcs_subject_offered
WHERE session_year='$sy' AND sub_category LIKE 'IC%' AND semester='$sem' AND sub_code='$scode'
ORDER BY sub_name";
        $query = $this->db->query($sql);
        return $query->row();
    }
}

?>