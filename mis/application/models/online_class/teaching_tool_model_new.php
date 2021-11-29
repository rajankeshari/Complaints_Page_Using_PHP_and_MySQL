<?php
 
class Teaching_tool_model_new extends CI_Model { 

    function __construct() {
        parent::__construct(); 
    }

        
    
	function add_teaching_tool_data($data){
		if($this->db->insert('ft_online_tool',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
	
	/*function get_quiz_list($sub_offered_id,$sub_code,$emp_no,$quiz_no){
		  $sql="SELECT a.* FROM ft_online_quiz a WHERE a.sub_offered_id=? AND a.sub_code=? AND a.emp_no=? AND a.quiz_no=?";
              $query = $this->db->query($sql,array($sub_offered_id,$sub_code,$emp_no,$quiz_no));

              if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }
		
		
	}*/
	function get_tool_list_all($emp,$syear,$sess){
		$sql="SELECT a.* FROM ft_online_tool a WHERE a.emp_no=? AND a.session_year=? AND a.`session`=?";
              $query = $this->db->query($sql,array($emp,$syear,$sess));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else { 
                    return false;
                }
		
		
	}
	function get_tool_list_all_coursewise($syear,$sess,$sub_code,$emp,$group_no,$section){
		$sql="SELECT a.* FROM ft_online_tool a
WHERE a.session_year=? AND a.`session`=? AND a.sub_code=? AND a.emp_no=? AND a.group_no=? ";
              $query = $this->db->query($sql,array($syear,$sess,$sub_code,$emp,$group_no,$section));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
		
		
	}
	function get_tool_list_all_coursewise_all($syear,$sess,$emp){
		$sql="SELECT a.* FROM ft_online_tool a WHERE a.session_year=? AND a.`session`=?  AND a.emp_no=?  ";
              $query = $this->db->query($sql,array($syear,$sess,$emp));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
		
		
	}
	
	function get_classlist($syear,$sess,$sub_code,$emp,$group_no,$section){
		if($emp=='064'){$emp='64';} 
		$sql="SELECT a.* FROM cbcs_class_engaged_course_wise a
WHERE a.session_year=? AND a.`session`=? AND a.course_code=? AND a.engaged_by=? AND a.group_no=?";
              $query = $this->db->query($sql,array($syear,$sess,$sub_code,$emp,$group_no));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
		
	}
	
	
    

 
    

}

?>