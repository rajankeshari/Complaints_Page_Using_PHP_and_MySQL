<?php

class Quiz_details_model_new extends CI_Model {   

    function __construct() { 
        parent::__construct();
    }

        
    
	
	function add_quiz_data($data){
		if($this->db->insert('ft_online_quiz',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
	
	function get_quiz_list($syear,$sess,$sub_code,$group_no,$section,$emp_id,$quiz_no){
		  $sql="SELECT a.* FROM ft_online_quiz a WHERE a.session_year=? and a.session=? AND a.sub_code=?  and a.group_no=?  AND a.emp_no=? AND a.quiz_no=?";
              $query = $this->db->query($sql,array($syear,$sess,$sub_code,$group_no,$emp_id,$quiz_no));

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