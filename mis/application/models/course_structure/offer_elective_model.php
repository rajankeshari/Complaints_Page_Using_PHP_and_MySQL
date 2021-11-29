<?php

class Offer_elective_model extends CI_Model
{
var $table_elective_group = 'elective_group';
var $table_summer_group = 'summer_group';										 
	var $table_optional_offered = 'optional_offered';
	var $table_summer_offered = 'summer_offered';										  
	
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}	
	
	function get_branch_by_dept_course_session($dept,$course,$session){
		$query = $this->db->query("SELECT * from dept_course where dept_id='".$dept."' AND aggr_id REGEXP '^".$course.".*".$session."$'");
		return $query->result();
	}
	
	function select_elective_offered($aggr_id,$id,$batch,$syear,$sess)
	{
    	$query = $this->db->get_where($this->table_optional_offered,array('aggr_id'=>$aggr_id,'id'=>$id,'batch'=>$batch,'session_year'=>$syear,'session'=>$sess));
    	if($query->num_rows() > 0)
			return true;	
	}
	function select_summer_offered($aggr_id,$id,$batch,$syear,$sess)
	{
    	$query = $this->db->get_where($this->table_summer_offered,array('aggr_id'=>$aggr_id,'id'=>$id,'batch'=>$batch,'session_year'=>$syear,'session'=>$sess));
    	if($query->num_rows() > 0)
			return true;	
	}															 
	function insert_elective_offered($data)
	{
    	$this->db->insert($this->table_optional_offered,$data);
    	return $this->db->_error_message(); 
	}
	function insert_summer_offered($data)
	{
    	$this->db->insert($this->table_summer_offered,$data);
    	return $this->db->_error_message(); 
	}								  
	
	function select_elective_offered_by_aggr_id($aggr_id,$semester,$batch,$syear,$sess)
	{
		$query = $this->db->query("SELECT * FROM optional_offered INNER JOIN course_structure ON course_structure.id = optional_offered.id WHERE optional_offered.aggr_id = '$aggr_id' AND course_structure.semester = '$semester' AND optional_offered.batch = '$batch' and optional_offered.session_year='$syear' and optional_offered.session='$sess' order by sequence");
			return $query->result();	
	}
	
	function delete_optional_subject_offered($aggr_id,$semester,$batch,$syear,$sess)
	{
		$query = $this->db->query("DELETE ele_off FROM optional_offered ele_off INNER JOIN course_structure ON course_structure.id = ele_off.id
		WHERE ele_off.aggr_id = '$aggr_id' AND course_structure.semester = '$semester' AND ele_off.batch = '$batch' and ele_off.session_year='$syear' and ele_off.session='$sess'");
	    
		if($this->db->affected_rows() >=0 || !$this->db->_error_message())
			return true;

	}
																			 function delete_summer_subject_offered($aggr_id,$semester,$batch,$syear,$sess)
	{

		$query = $this->db->query("DELETE summ_off FROM summer_offered summ_off INNER JOIN course_structure cs ON cs.id = summ_off.id
		WHERE summ_off.aggr_id = '$aggr_id' AND cs.semester = '$semester' AND summ_off.batch = '$batch' and summ_off.session_year='$syear' and summ_off.session='$sess'");
	    
		if($this->db->affected_rows() >=0 || !$this->db->_error_message())
			return true;

	}
	/*function  getminor  ($aggr_id,$semester,$syear,$sess){
		$query = $this->db->query("SELECT DISTINCT A.subject_id, A.name,A.aggr_id
FROM(
(
SELECT e.name,e.subject_id,a.aggr_id
FROM (
SELECT x.id,x.aggr_id
FROM course_structure x
WHERE x.semester=8 AND x.aggr_id LIKE 'minor_eleceng_%')a
inner join optional_offered oo on oo.aggr_id=a.aggr_id and oo.id=a.id and    oo.session_year='2016-2017' and oo.`session`='Winter'
INNER JOIN subjects e ON e.id=a.id )

)A

ORDER BY A.subject_id");
			return $query->result();	
	}*/
	function  get_minor_elective  ($aggr_id,$semester,$syear,$sess,$batch){
		$query = $this->db->query("SELECT DISTINCT A.subject_id, A.name,A.aggr_id,A.sequence,A.id
FROM(
(
SELECT e.name,e.subject_id,a.aggr_id,a.sequence,a.id
FROM (
SELECT x.id,x.aggr_id,x.sequence
FROM course_structure x
WHERE x.semester='".$semester."' AND x.aggr_id ='".$aggr_id."')a
inner join optional_offered oo on oo.aggr_id=a.aggr_id and oo.id=a.id and    oo.session_year='".$syear."' and oo.`session`='".$sess."' and oo.batch='".$batch."'
INNER JOIN subjects e ON e.id=a.id )

)A

ORDER BY A.subject_id");
                 //echo  $this->db->last_query(); die();
			
                return $query->result();	
	}

}
/* End of file menu_model.php */

/* Location: mis/application/models/course_structure/menu_model.php */