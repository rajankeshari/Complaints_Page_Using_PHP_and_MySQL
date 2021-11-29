<?php

class Misextra_model extends CI_Model {

    function __construct() {
        parent::__construct();
    } 

        
    function get_student_details($admn_no)
    {
          
     
        
        $sql="
SELECT 
    CONCAT('SELECT * FROM ', A.TABLE_SCHEMA, '.', A.TABLE_NAME, 
           ' WHERE ', A.COLUMN_NAME , ' LIKE \'%".$admn_no."%\';')  AS squery
FROM INFORMATION_SCHEMA.COLUMNS A
WHERE 
            A.TABLE_SCHEMA != 'mysql' 
AND     A.TABLE_SCHEMA != 'innodb' 
AND     A.TABLE_SCHEMA != 'performance_schema' 
AND     A.TABLE_SCHEMA != 'information_schema'
AND     
        (
            A.DATA_TYPE LIKE '%text%'
        OR  
            A.DATA_TYPE LIKE '%char%'
        )
";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function run_select_query($sql){

      

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $sql;
        } else {
            return false;
        }

    }
    

}

?>