<?php

class Change_branch_print_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get_form_id($syear,$admn_no)
    {
          
        $sql = "select a.* from change_branch_log a where a.session_year=? and a.`session`='Monsoon' and a.admn_no=?";

        $query = $this->db->query($sql,array($syear,$admn_no));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function user_name($admn_no)
    {
          
        $sql = "select concat_ws(' ',a.first_name,a.middle_name,a.last_name)as stu_name,a.physically_challenged,a.category from user_details a where a.id=?";

        $query = $this->db->query($sql,array($admn_no));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function get_all_student($syear)
    {
          
        $sql = "select a.* from change_branch_log a where a.session_year=? and a.`session`='Monsoon' order by a.admn_no";

        $query = $this->db->query($sql,array($syear));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>