<?php

class Result_publication_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_list_declared($syear,$sess,$did,$cid,$bid,$sem)
    {
          
        $sql = "select a.* from result_declaration_log a where a.dept_id<>'0'";
        
        if($syear!='none'){
            $sql.=" and a.s_year='".$syear."' ";
        }
        if($sess!='none'){
            $sql.=" and a.session='".$sess."' ";
        }
        if($did!='none'){
            $sql.=" and a.dept_id='".$did."' ";
        }
        if($cid!='none'){
            $sql.=" and a.course_id='".$cid."' ";
        }
        if($bid!='none'){
            $sql.=" and a.branch_id='".$bid."' ";
        }
        if(!empty($sem)){
            $sql.=" and a.semester='".$sem."' ";
        }
        
        
        
        
        $sql.=" order by a.s_year,a.`session`, a.dept_id,a.course_id,a.branch_id,a.semester";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_list_pending($syear,$sess,$did,$cid,$bid,$sem){
        $sql = "select b.s_year,b.`session`,b.dept_id,b.course_id,b.branch_id,b.semester,b.section,b.exam_type,a.admn_no from result_declaration_log_partial_details a
inner join result_declaration_log b on b.id=a.res_dec_id
where a.`status`='P'";
        
        if($syear!='none'){
            $sql.=" and b.s_year='".$syear."' ";
        }
        if($sess!='none'){
            $sql.=" and b.session='".$sess."' ";
        }
        if($did!='none'){
            $sql.=" and b.dept_id='".$did."' ";
        }
        if($cid!='none'){
            $sql.=" and b.course_id='".$cid."' ";
        }
        if($bid!='none'){
            $sql.=" and b.branch_id='".$bid."' ";
        }
        if(!empty($sem)){
            $sql.=" and b.semester='".$sem."' ";
        }
        
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>