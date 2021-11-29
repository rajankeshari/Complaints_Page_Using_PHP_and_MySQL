<?php

class Phd_course_details_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_course_list()
    {
       
        $sql="SELECT t.* FROM(
SELECT a.id,a.subject_id AS sub_code,a.name AS sub_name,
c.dept_id,d.course_id,d.branch_id,'old' AS cstatus
 FROM subjects a
INNER JOIN course_structure b ON b.id=a.id
INNER JOIN dept_course c ON c.aggr_id=b.aggr_id
INNER JOIN course_branch d ON d.course_branch_id=c.course_branch_id
union
SELECT a.id,a.sub_code,a.sub_name,a.dept_id,NULL AS course_id,
NULL AS branch_id,'cbcs' AS cstatus FROM   cbcs_course_master a
)t";

        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function insertForm($data){
        if($this->db->insert('jrf_dsc', $data)){
           return $this->db->insert_id();
        }
    }
	function fetch_record($admn_no){
		$sql="SELECT a.* FROM jrf_dsc a WHERE a.admn_no=?";
		$query = $this->db->query($sql,array($admn_no));
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
	}
	
	function update_course_status($id,$status){
	
        $myquery = "update jrf_dsc set status='".$status."' where id=" . $id;
		$query = $this->db->query($myquery);
		
		
		
	}
	
	
	
	
    

}

?>