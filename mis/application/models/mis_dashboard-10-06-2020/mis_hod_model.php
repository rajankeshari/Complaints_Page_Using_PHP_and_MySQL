<?php

class Mis_hod_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_hod_details()
    {
          
        $sql = "select a.*,c.name as dname,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as name from user_auth_types a 
inner join user_details b on a.id=b.id
inner join departments c on c.id=b.dept_id
where a.auth_id='hod'
order by c.name";

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