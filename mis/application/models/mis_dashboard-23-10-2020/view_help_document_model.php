<?php

class View_help_document_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_topic_auth_wise($id)
    {
        $id = "'" . implode("','", $id) . "'"; 
        //print($p);
        $sql = "select *,
  SUBSTRING_INDEX(SUBSTRING_INDEX(doc_path, '/', 4), '/', -1) as filename from help_document_tbl where auth_id in ($id)  group by topicname";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    
     

}

?>