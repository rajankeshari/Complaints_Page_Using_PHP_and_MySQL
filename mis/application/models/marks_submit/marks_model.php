<?php 
class Marks_model extends CI_Model{
    
   function  getfailstu($id){
       $q="select A.admn_no,A.stu_name from
(select * from (SELECT *,0+right(`resultdata`.`sem_code`,1) as `b` FROM `resultdata` 
WHERE admn_no = '".$id."' and 0+right(`resultdata`.`sem_code`,1) in (1,2,3,4)
ORDER BY 0+right(`resultdata`.`sem_code`,1) ASC, `resultdata`.`session` desc ,`resultdata`.`examtype` desc limit 1000) `a` 
group by `a`.`sem_code` order by b asc ) A 
where passfail='F'";
       $qu=$this->db->query($q);
       if($qu->num_rows() > 0 ){
           return $qu->row();
       }else{
           return false;
       }
   }
   
   function getStu(){
       $q="select rf.admn_no,rf.semester from reg_regular_form as rf where (semester='9' or semester='7' ) and course_id<>'m.tech' and hod_status='1' and acad_status='1' ";
       $qu = $this->db->query($q);
       if($qu->num_rows() > 0 ){
           return $qu->result();
       }
       return false;
               
   }
   
   function getStu1(){
       $q="select * from reg_regular_form as rf where semester='3' and course_id<>'m.tech' and course_id<>'m.sc.tech' and course_id<>'m.sc' and hod_status='1' and acad_status='1'";
       $qu = $this->db->query($q);
       if($qu->num_rows() > 0 ){
           return $qu->result();
       }
       return false;
               
   }
   
    function getStu2(){
       $q="select * from reg_regular_form as rf where semester='5' and course_id<>'m.tech' and course_id<>'m.sc.tech' and course_id<>'m.sc' and hod_status='1' and acad_status='1'";
       $qu = $this->db->query($q);
       if($qu->num_rows() > 0 ){
           return $qu->result();
       }
       return false;
               
   }
   
   
}
?>