<?php

class Quiz_details_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    
	
	function add_quiz_data($data){
		if($this->db->insert('ft_online_quiz',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
	
	function get_quiz_list($sub_offered_id,$sub_code,$emp_no,$quiz_no){
		  $sql="SELECT a.* FROM ft_online_quiz a WHERE a.sub_offered_id=? AND a.sub_code=? AND a.emp_no=? AND a.quiz_no=?";
              $query = $this->db->query($sql,array($sub_offered_id,$sub_code,$emp_no,$quiz_no));

              if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }
		
		
	}
	
	function get_quiz_list_all($emp,$syear,$sess){
		$sql="SELECT a.* FROM ft_online_quiz a WHERE a.emp_no=? AND a.session_year=? AND a.`session`=?";
              $query = $this->db->query($sql,array($emp,$syear,$sess));

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}
	
	
	
    

 
    

}

?>