<?php
	/**
      * @Author:Rohit Rana
     */
	class Report extends CI_Model{
          //  $ta = 'stu_fee_database_';
            function getDetails($t,$s,$sy){
                // echo $t;
                // echo $s;
                // echo $sy;
                // exit;
            if($t=='stu_fee_database_regular'){
                $sql = "select distinct(`course_id`) as `course` from `stu_fee_database_regular` where session_year='$sy' and session='$s'";
            }
            $sql = "select distinct(`course_id`) as `course` from `stu_fee_database_regular` where session_year='$sy' and session='$s'";
               $q=$this->db->query($sql);
              
                if($q->num_rows() > 0){
                    return $q->result();
                }
                return false;
            }
            
            function categorydetailsDept($t,$c,$s,$sy,$sem,$cate){
                if($t=='stu_fee_database_regular'){
                    $sql = "select distinct(`course_id`) as `course` from `stu_fee_database_regular` where session_year='$sy' and session='$s' and course_id='$c' and category='$cate' and semester='$sem'";
                }
                $q=$this->db->order_by('semester')->get_where($sql);
               
                if($q){
                if($q->num_rows() > 0){
                    return $q->row();
                }}
                return false;
            }
            
            function getFeeSemester($t,$s,$sy){
                if($t=='stu_fee_database_regular'){
                    // $sql = "select distinct(`course_id`) as `course` from `stu_fee_database_regular` where session_year='$sy' and session='$s' and course_id='$c' and category='$cate' and semester='$sem'";
                    $sql = "select distinct(semester) as semester from `stu_fee_database_regular` where session_year='$sy' and session='$s'";
                }
                  $q=$this->db->query($sql);
               // echo $this->db->last_query();
                if($q->num_rows() > 0){
                    return $q->result();
                }
                return false;
                
            }
        }
?>
