<?php

class Dsc_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_guide_co_guide($id)
    {
       
        $sql="SELECT * from project_guide WHERE admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	function guide_co_guide_details($id)
    {
       
        $sql="SELECT concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name)AS fname,
				a.id,c.name AS dname
				FROM  user_details a 
				INNER JOIN departments c ON c.id=a.dept_id
				WHERE a.id=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	function get_internal_list_student_wise($id){
		$sql="SELECT c.name AS dname,b.dept_id,b.emp_no,CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS fname,
b.role
FROM project_guide a
INNER JOIN project_guide_internal b ON b.project_id=a.id
INNER JOIN departments c ON c.id=b.dept_id
INNER JOIN user_details d ON d.id=b.emp_no
WHERE a.admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	function get_external_list_student_wise($id){
		$sql="SELECT a.* FROM project_guide_external a
INNER JOIN project_guide b ON b.id=a.project_id
WHERE b.admn_no=?";

        
        $query = $this->db->query($sql,array($id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	function add_external_guide($data){
        if($this->db->insert('project_guide_external',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	function add_internal_guide($data){
        if($this->db->insert('project_guide_internal',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        
    }
	
	
	
    

}

?>