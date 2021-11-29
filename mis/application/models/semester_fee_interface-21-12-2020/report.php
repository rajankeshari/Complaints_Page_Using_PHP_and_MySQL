<?php
	/**
      * @Author:Rohit Rana
     */
	class Report extends CI_Model{
          //  $ta = 'stu_fee_database_';
            function getDetails($t,$s,$sy){
                $q=$this->db->query('select distinct(`course_id`) as `course` from stu_fee_database_'.$t.' where session_year=? and session=?',array($sy,$s));
              //  echo $this->db->last_query();
                if($q->num_rows() > 0){
                    return $q->result();
                }
                return false;
            }
            
            function categorydetailsDept($t,$c,$s,$sy,$sem,$cate){
                $q=$this->db->order_by('semester')->get_where('stu_fee_database_'.$t,array('session_year'=>$sy,'session'=>$s,'course_id'=>$c,'semester'=>$sem,'category'=>$cate));
               
                if($q){
                if($q->num_rows() > 0){
                    return $q->row();
                }}
                return false;
            }
            
            function getFeeSemester($t,$s,$sy){
                
                  $q=$this->db->query('select distinct(`semester`) as `semester` from stu_fee_database_'.$t.' where session_year=? and session=?',array($sy,$s));
               // echo $this->db->last_query();
                if($q->num_rows() > 0){
                    return $q->result();
                }
                return false;
                
            }
        }
?>
