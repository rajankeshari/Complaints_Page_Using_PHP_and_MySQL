<?php

class Exam_form_new_model extends CI_Model
{
    
    function __construct() {
        parent::__construct();
    }
    
  
    function check_open_close_date_for_all(){
        
        $sql = "select * from sem_date_open_close_tbl where exam_type='exam' and open_for='all' order by id desc limit 1";
        $query = $this->db->query($sql);
        $t=$query->row();
        $sql = "select * from sem_date_open_close_tbl where id= ?   and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') 
 and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";
        $query = $this->db->query($sql,array($t->id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }
    
    function check_open_close_date_for_specific(){
        $sql = "select * from sem_date_open_close_tbl where exam_type='exam' and open_for='specific' order by id desc limit 1";
        $query = $this->db->query($sql);
        $t=$query->row();
        $sql = "select * from sem_date_open_close_tbl where id= ?   and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') 
 and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";
        $query = $this->db->query($sql,array($t->id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_open_close_date_for_individual($id){
        
$sql = "select * from sem_date_open_close_tbl where exam_type='exam' and open_for='indi_stu' and admn_no=? order by id desc limit 1";
        $query = $this->db->query($sql,array($id));
        $t=$query->row();
    
        $sql = "select * from sem_date_open_close_late_tbl where master_id=?";
        $query = $this->db->query($sql,array($t->id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function check_open_close_late_fine($id){
        $sql = "select * from sem_date_open_close_late_tbl where id= ?   and CURDATE() between DATE_FORMAT(late_start_date, '%Y-%m-%d')  and DATE_FORMAT(late_close_date, '%Y-%m-%d') 
 ";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
   
            
  //==============================================================  
    
    
    
    function get_date_open_close_status_without_fine()
    {
          
       // $sql = "select * from reg_regular_openclose_desc where department='exam' and course='exam'  and type='1' and end_date >=now() ";
	   $sql = "select * from reg_regular_openclose_desc where department='exam' and course='exam'  and type='1' and DATE_FORMAT(end_date, '%Y-%m-%d') >=CURDATE() ";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_date_open_close_status_with_fine()
    {
          
     //   $sql = "select * from reg_regular_openclose_desc where department='exam' and course='exam'  and type='2' and end_date >=now() ";
	 $sql = "select * from reg_regular_openclose_desc where department='exam' and course='exam'  and type='2' and DATE_FORMAT(end_date, '%Y-%m-%d') >=CURDATE() ";

        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_for_current_session($admn_no,$sy,$sess,$et){
        if($sess=='W'){$sess='Winter';}
        if($sess=='M'){$sess='Monsoon';}
        $sql = "select * from reg_exam_rc_form where admn_no=? and session_year=? and session=? and hod_status<>'2' and acad_status<>'2' and type=?  ";

        $query = $this->db->query($sql,array($admn_no,$sy,$sess,($et=='exam')?'R':'S'));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
        
    }
    
    function get_row_details($id){
        $sql = "  select * from sem_date_open_close_tbl where id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
}
?>

