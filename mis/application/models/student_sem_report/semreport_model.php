<?php

class Semreport_model extends CI_Model
{

	function get_summersem($sessionY,$c,$hs='1',$as='1')
        {
            
            $sql= "SELECT `reg_summer_form`.*, `user_details`.*, `stu_academic`.* FROM `reg_summer_form` INNER JOIN `user_details` ON `reg_summer_form`.`admn_no` = `user_details`.`id` INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no` where reg_summer_form.session_year='".$sessionY."' and `reg_summer_form`.`hod_status`='".$hs."' and `reg_summer_form`.`acad_status`='".$as."'"; 
			
			if(is_array($c)){
                          $i=0; 
                           foreach($c as $v){
                               if($i==0){ $sql.=' and ('; }else{ $sql.=' or '; }
                               $sql.="`reg_summer_form`.`course_id`='".$v."'";                               
                          $i++; }
                       }
			
			$sql.="order by stu_academic.course_id asc,stu_academic.branch_id asc,reg_summer_form.admn_no asc";
		
		
			$query = $this->db->query($sql);

			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
        }
		
        function get_studentsubjectbyID($id)
        {
            $sql= "Select * from reg_summer_subject as a join subjects as b on a.sub_id=b.id join course_structure as c on b.id=c.id where a.form_id='".$id."'";
		
		
			$query = $this->db->query($sql);

			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
        }
		
		
        function get_regular_sem_form_report($sessionY,$s,$c='',$hs='1',$as='1'){
            
			$sql= "SELECT `reg_regular_form`.*,`reg_regular_form`.`semester` as sem, `user_details`.*, `stu_academic`.* FROM `reg_regular_form` INNER JOIN `user_details` ON `reg_regular_form`.`admn_no` = `user_details`.`id` INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no` where session_year='".$sessionY."' and `reg_regular_form`.`hod_status`='".$hs."' and `reg_regular_form`.`acad_status`='".$as."' and `reg_regular_form`.`semester`='".$s."'"; 
                       
                        if(is_array($c)){
                          $i=0; 
                           foreach($c as $v){
                               if($i==0){ $sql.=' and ('; }else{ $sql.=' or '; }
                               $sql.="`reg_regular_form`.`course_id`='".$v."'";                               
                          $i++; }
                       }
                        
                        $sql.=" ) order by stu_academic.course_id asc,stu_academic.branch_id asc,reg_regular_form.admn_no asc";
			//echo $sql; die();
			$query = $this->db->query($sql);

			if($query->num_rows() > 0)
                         return $query->result();  // print_r($query->result()); die(); 
                  return false;
		}
                
         function get_idle_sem_form_report($sessionY,$s,$c='',$hs='1',$as='1'){
            
			$sql= "SELECT `reg_idle_form`.*,`reg_idle_form`.`semester` as sem, `user_details`.*, `stu_academic`.* FROM `reg_idle_form` INNER JOIN `user_details` ON `reg_idle_form`.`admn_no` = `user_details`.`id` INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no` where session_year='".$sessionY."' and `reg_idle_form`.`hod_status`='".$hs."' and `reg_idle_form`.`acad_status`='".$as."' and `reg_idle_form`.`semester`='".$s."'"; 
                       
                        if(is_array($c)){
                          $i=0; 
                           foreach($c as $v){
                               if($i==0){ $sql.=' and '; }else{ $sql.=' or '; }
                               $sql.="`reg_idle_form`.`course_id`='".$v."'";                               
                          $i++; }
                       }
                        
                        $sql.=" order by stu_academic.course_id asc,stu_academic.branch_id asc,reg_idle_form.admn_no asc";
			//echo $sql; die();
			$query = $this->db->query($sql);

			if($query->num_rows() > 0)
                         return $query->result();  // print_r($query->result()); die(); 
                  return false;
		}
                
         function get_other_sem_form_report($sessionY,$s,$c='',$hs='1',$as='1'){
            
			$sql= "SELECT `reg_exam_rc_form`.*,`reg_exam_rc_form`.`semester` as sem, `user_details`.*, `stu_academic`.* FROM `reg_exam_rc_form` INNER JOIN `user_details` ON `reg_exam_rc_form`.`admn_no` = `user_details`.`id` INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no` where session_year='".$sessionY."' and `reg_exam_rc_form`.`hod_status`='".$hs."' and `reg_exam_rc_form`.`acad_status`='".$as."' and `reg_exam_rc_form`.`semester`='".$s."'"; 
                       
                        if(is_array($c)){
                          $i=0; 
                           foreach($c as $v){
                               if($i==0){ $sql.=' and '; }else{ $sql.=' or '; }
                               $sql.="`reg_exam_rc_form`.`course_id`='".$v."'";                               
                          $i++; }
                       }
                        
                        $sql.=" order by stu_academic.course_id asc,stu_academic.branch_id asc,reg_exam_rc_form.admn_no asc";
		//echo $sql; die();
			$query = $this->db->query($sql);

			if($query->num_rows() > 0)
                         return $query->result();  // print_r($query->result()); die(); 
                  return false;
		}
                
     function get_other_studentsubjectbyID($id)
        {
            $sql= "Select * from reg_other_subject as a join subjects as b on a.sub_id=b.id where a.form_id='".$id."'";
		
		
			$query = $this->db->query($sql);

			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
        }
                
        function get_reg_studentsubjectbyID($id)
        {
            $sql= "Select * from reg_regular_elective_opted as a join subjects as b on a.sub_id=b.id where a.form_id='".$id."'";
		
		
			$query = $this->db->query($sql);

			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
        }
        
        function sturegsem(){
            $sql= "SELECT Distinct(semester) as semester from reg_regular_form order by semester asc";
            $query = $this->db->query($sql);

			if($query->num_rows() > 0)
			return $query->result();
                       
                        return FALSE;
            
        }
        function sturegcou(){
            $sql= "SELECT Distinct(course_id) as course from reg_regular_form order by course asc";
            $query = $this->db->query($sql);

			if($query->num_rows() > 0)
			return $query->result();
                       
                        return FALSE;
            
        }
        
        function sturegfee($fid){
            $sql= "SELECT fee_amt as fee, fee_date from reg_regular_fee where form_id='".$fid."'";
            $query = $this->db->query($sql);

			if($query->num_rows() > 0)
			return $query->row();
                       
                        return FALSE;
            
        }
        
        function getStuListCSE(){
                 $q = "select rf.*,ud.dept_id from reg_regular_form as rf 
join user_details as ud 
on rf.admn_no = ud.id 
where rf.semester='3' 
and ud.dept_id='cse' 
and (rf.course_id='b.tech' 
or rf.course_id='dualdegree') 
and (rf.branch_id = 'cse'
or rf.branch_id = 'cse+cse')
group by rf.admn_no order by course_id ASC";
                 $qu=$this->db->query($q);
                 if($qu->num_rows() > 0){
                    return $qu->result();
                 }
                 return false;
               }
}
