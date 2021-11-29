<?php

class Stu_registration_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_stu_registration($tbl,$selsyear,$selsess,$admn_no)
    {
        if($admn_no!=""){
            $sql = "select * from ".$tbl."  where session_year=? and session=? and admn_no=?";
         } else{
            $sql = "select * from ".$tbl."  where session_year=? and session=?";
        }
        //echo $sql;die();
        $query = $this->db->query($sql,array($selsyear,$selsess,$admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_subjects($form_id,$tbl_subj)
    {
        $sql = "select a.*,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from ".$tbl_subj." a
inner join subjects b on b.id=a.sub_id left join course_structure c on c.id=a.sub_id where a.form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    function get_details_by_formID($form_id){
        $sql = "select * from reg_regular_form where form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_honour_minor($admn_no){
        $sql = "select a.* from hm_form a where a.admn_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }


    function get_subjects_cs($cid,$sem,$sec=null){

        $caggrid=explode('_', $cid);
        $tsem=$sem.'_'.$sec;
        if($caggrid[0]=="comm"){
            $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and  c.sequence not like '%.%' order by c.sequence+0";
$query = $this->db->query($sql,array($cid,$tsem));

        }else{

        $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and c.sequence not like '%.%' order by c.sequence+0";

$query = $this->db->query($sql,array($cid,$sem));
}

       // echo $sql;die();
       

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

function get_minor($admn_no){
        $sql = "select b.minor_agg_id from hm_form a 
inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and b.offered='1'";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    

 
    

}

?>