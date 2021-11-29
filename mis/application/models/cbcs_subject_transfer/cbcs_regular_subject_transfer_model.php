<?php

class Cbcs_regular_subject_transfer_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }



    function get_list($sy,$sess)
    {

      $sql = "select a.course_id,a.branch_id,a.semester,a.section from reg_regular_form a
where a.session_year=? and a.`session`=? and a.section<>'0'
group by a.course_id,a.branch_id,a.semester,a.section ";


        $query = $this->db->query($sql,array($sy,$sess));


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }





}

?>
