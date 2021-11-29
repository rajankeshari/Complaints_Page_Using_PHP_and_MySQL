<?php

class Modular_course_division_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_modular_subject($course,$sy,$sess)
    {
          

        
        $sql="SELECT a.sub_code,concat(a.sub_name,' [',a.sub_code,']')AS subject FROM cbcs_subject_offered a WHERE a.sub_type='Modular' 
AND a.course_id=? AND a.session_year=? AND a.`session`=? GROUP BY a.sub_code ORDER BY a.sub_name";

        
        $query = $this->db->query($sql,array($course,$sy,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_student_common_group($syear,$sess,$subcode,$group){
	
		if($group=='group1'){$sec="('A','B','C','D')";}
		if($group=='group2'){$sec="('E','F','G','H')";}
		
		   $sql="SELECT y.*,x.section FROM(
SELECT a.*
FROM cbcs_stu_course a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE b.session_year=? AND b.`session`=? AND a.subject_code=? AND b.admn_no IN (
SELECT a.admn_no
FROM stu_section_data a
WHERE a.session_year=? AND a.section IN ".$sec."
ORDER BY a.admn_no))y
INNER JOIN stu_section_data x ON x.admn_no=y.admn_no AND x.session_year=?";
        
        $query = $this->db->query($sql,array($syear,$sess,$subcode,$syear,$syear));
       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	//======================================Section Start========================================
	
			function get_student_common_section($syear,$sess,$subcode,$sec){
	
		//if($group=='group1'){$sec="('A','B','C','D')";}
		//if($group=='group2'){$sec="('E','F','G','H')";}
		
		   $sql="SELECT y.*,x.section FROM(
SELECT a.*
FROM cbcs_stu_course a
INNER JOIN reg_regular_form b ON b.form_id=a.form_id AND b.admn_no=a.admn_no
WHERE b.session_year=? AND b.`session`=? AND a.subject_code=? AND b.admn_no IN (
SELECT a.admn_no
FROM stu_section_data a
WHERE a.session_year=? AND a.section =?
ORDER BY a.admn_no))y
INNER JOIN stu_section_data x ON x.admn_no=y.admn_no AND x.session_year=?";
        
        $query = $this->db->query($sql,array($syear,$sess,$subcode,$syear,$sec,$syear));
       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	//======================================Section End========================================
	
	 function check_into_modular_main($data)
	{
		//echo '<pre>';print_r($data);echo '</pre>';
		
		$sql="SELECT * from cbcs_modular_paper_main
WHERE session_year=? AND SESSION=? AND course_id=? AND branch_id=? AND exam_type=? AND group_section=?
AND `GROUP`=? AND section=? AND sub_code=?";
        
		//echo $sql;
        $query = $this->db->query($sql,array($data['session_year'],$data['session'],$data['course_id'],$data['branch_id'],$data['exam_type'],$data['group_section'],$data['group'],$data['section'],$data['sub_code']));
       
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
		
	}
	
	function insert_into_modular_main($data)
	{
		if($this->db->insert('cbcs_modular_paper_main',$data))
			return $this->db->insert_id();
		else
			return FALSE;
		
	}
	
	function insert_records($data)
    {
        if($this->db->insert_batch('cbcs_modular_paper_details',$data))
            return TRUE;
        else
            return FALSE;
    }
	
	function get_modular_main_list( $course,$syear, $sess){
		
		$sql="SELECT * from cbcs_modular_paper_main WHERE course_id=? and session_year=? AND SESSION=?";

        
        $query = $this->db->query($sql,array($course,$syear, $sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
	}
	
	function get_course_name( $id){
		$sql="SELECT * FROM cbcs_courses WHERE id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->name;
        } else {
            return false;
        }
	}
	function get_branch_name( $id){
		$sql="SSELECT * FROM cbcs_branches WHERE id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->name;
        } else {
            return false;
        }
	}
	function get_subject_name( $id){
		$sql="SELECT sub_name FROM cbcs_subject_offered WHERE sub_code=? GROUP BY  sub_code";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->sub_name;
        } else {
            return false;
        }
	}
	
	function get_modular_paper_list($id){
		
		$sql="SELECT * from cbcs_modular_paper_details WHERE main_id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
		
	}
    

}

?>
