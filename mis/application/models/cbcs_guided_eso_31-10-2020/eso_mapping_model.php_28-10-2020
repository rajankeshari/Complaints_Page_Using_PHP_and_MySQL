<?php

class Eso_mapping_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*function get_eso_list(){
    	$sql="SELECT a."
    }*/

    function offered_eso_list(){
    	$sql="SELECT a.*,b.sub_code,b.sub_name,d.name AS d_name,c.name AS c_name,e.name AS b_name
FROM cbcs_guided_eso a
JOIN cbcs_subject_offered b ON a.sub_offered_id=b.id
JOIN cbcs_courses c ON a.course_id=c.id
JOIN cbcs_departments d ON a.dept_id=d.id
JOIN cbcs_branches e ON a.branch_id=e.id";
	$query=$this->db->query($sql);
	//echo $this->db->last_query();
    return $query->result();
    }

    function get_eso_offered_list($syear,$sess){
    	$sql="SELECT b.*,d.name AS d_name,c.name AS c_name,e.name AS b_name
FROM cbcs_subject_offered b 
JOIN cbcs_courses c ON b.course_id=c.id
JOIN cbcs_departments d ON b.dept_id=d.id
JOIN cbcs_branches e ON b.branch_id=e.id
WHERE b.session_year='$syear' AND b.`session`='$sess' AND b.sub_category LIKE 'ESO%'
";
	$query=$this->db->query($sql);
	//echo $this->db->last_query();
    return $query->result();
    }

    function get_eso_list($sy,$sess){
    	$sql="SELECT a.*
FROM cbcs_subject_offered a
WHERE a.session_year='$sy' AND a.`session`='$sess' 
AND a.sub_category like'ESO%'";
$query=$this->db->query($sql);
	//echo $this->db->last_query();
    return $query->result();
    }


    function match_eso_data($data){
    	$query=$this->db->get_where('cbcs_guided_eso',$data);
    	//echo $this->db->last_query();die();
    	if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }


    function eso_offered($data)
    {
        if($this->db->insert('cbcs_guided_eso',$data))
            return TRUE;
        else
            return FALSE;
    }

    function  delete_eso($id){
    	$this->db->where('id', $id);
        $this->db->delete('cbcs_guided_eso');
    }
}