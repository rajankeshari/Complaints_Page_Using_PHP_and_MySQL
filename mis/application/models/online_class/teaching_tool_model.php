<?php

class Teaching_tool_model extends CI_Model {

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
	function get_tool_list_all_coursewise($emp,$syear,$sess,$sub_code,$sub_off_id){
		$sql="SELECT a.* FROM ft_online_tool a WHERE a.emp_no=? AND a.session_year=? AND a.`session`=? and sub_code=? and a.sub_offered_id=?";
              $query = $this->db->query($sql,array($emp,$syear,$sess,$sub_code,$sub_off_id));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
		
		
	}
	
	function get_classlist($temp_id,$emp){
		if($emp=='064'){$emp='64';} 
		$sql="SELECT a.*,
SUBSTRING(a.subject_offered_id,1,1)AS rstatus,SUBSTRING(a.subject_offered_id,2)AS subid
 from cbcs_class_engaged a WHERE a.subject_offered_id=? AND a.engaged_by=?;";
              $query = $this->db->query($sql,array($temp_id,$emp));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
		
	}
	
	
    

 
    

}

?>