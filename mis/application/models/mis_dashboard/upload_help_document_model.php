<?php

class Upload_help_document_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_all_auth()
    {
          
        $sql = "select concat(a.`type`,' [',a.id,']')as auth,a.id as authid from auth_types a
left join user_auth_types b on b.auth_id=a.id group by a.id order by a.`type`;";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function insert_batch($data)
    {
        if($this->db->insert_batch('help_document_tbl',$data))
            return TRUE;
        else
            return FALSE;
    }
     

}

?>