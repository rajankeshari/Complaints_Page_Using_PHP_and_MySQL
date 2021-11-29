<?php

class Delete_elective_regular_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_student_list($sy,$cat)
    {
          
        $sql = "";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>