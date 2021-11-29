<?php

class Delete_help_document_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function delete_file($file_to_delete)
    {
         $tmpf='./'. $file_to_delete;
        $sql = "delete  from help_document_tbl where doc_path = ?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($tmpf));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return True;
        } else {
            return false;
        }
    }

    
     

}

?>