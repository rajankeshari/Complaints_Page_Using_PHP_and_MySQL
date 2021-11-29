<?php

Class Admission_special_model extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();
	}

	function get_course_structure($did,$cid,$bid)
        {
            $sql = "select a.aggr_id,right(a.aggr_id,9)as course_structure from dept_course a 
inner join course_branch b on a.course_branch_id=b.course_branch_id
where a.dept_id=? and b.course_id=? and b.branch_id=?";

        $query = $this->db->query($sql,array($did,$cid,$bid));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        function get_core_subject($cs,$sem){
            $sql = "select b.id as mis_id,concat(b.subject_id,' [',b.name,']') as sub_name from course_structure a
inner join subjects b on a.id=b.id
where a.aggr_id=? and a.semester=?
/*and a.sequence not like '%.%'*/";

        $query = $this->db->query($sql,array($cs,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        function insert_fee($data,$tbl)
	{
		if($this->db->insert($tbl,$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
        function insert_main($data,$tbl)
	{
		if($this->db->insert($tbl,$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
         function insert_batch($data,$tbl)
	{
		if($this->db->insert_batch($tbl,$data))
			return TRUE;
		else
			return FALSE;
	}
        function check_already($admn_no,$sess,$syear,$sem,$tbl){
         $sql = "select a.* from ".$tbl." a where a.admn_no=? and a.`session`=? and a.session_year=? and a.semester=? and a.hod_status<>'2' and a.acad_status<>'2'";
        $query = $this->db->query($sql,array($admn_no,$sess,$syear,$sem));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        }
        
        function get_personal_details_other($syear, $sess, $admn_no,$sem){
        
        $sql="SELECT a.form_id,a.admn_no, 
CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) stu_name,b.dept_id,a.course_id,a.branch_id, 
a.session_year,a.`session`,a.semester, c.name AS cname,d.name AS bname,a.timestamp
FROM reg_summer_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN cs_courses c ON c.id=a.course_id
INNER JOIN cs_branches d ON d.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? AND a.semester=?
limit 1";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->row();
            } else {
                return false;
            }
    }
    
    function get_subject_details_other($syear, $sess,$admn_no,$sem){
        
       /* $sql="select p.*,c.subject_id,c.name from (
select a.* from reg_other_subject a
where a.form_id=(select b.form_id from reg_other_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? limit 1))p
inner join subjects c on c.id=p.sub_id";*/
        
        $sql="SELECT p.*,c.subject_id,c.name
FROM (
SELECT a.*
FROM reg_summer_subject a
WHERE a.form_id=(
SELECT b.form_id
FROM reg_summer_form b
WHERE b.session_year='".$syear."' AND b.`session`='".$sess."' AND b.admn_no='".$admn_no."' AND b.semester like '%".$sem."%' and hod_status<>'2' and acad_status<>'2'
LIMIT 1))p
INNER JOIN subjects c ON c.id=p.sub_id";

            $query = $this->db->query($sql);

            //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    
    function get_fee_other($syear, $sess,$admn_no,$sem){
        
        $sql="
select a.* from reg_summer_fee a
where a.form_id=(select b.form_id from reg_summer_form b
where b.session_year=? and b.`session`=?
and b.admn_no=? and b.semester=? AND hod_status<>'2' AND acad_status<>'2' limit 1)";

            $query = $this->db->query($sql, array($syear, $sess,$admn_no,$sem));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }

	
}

?>