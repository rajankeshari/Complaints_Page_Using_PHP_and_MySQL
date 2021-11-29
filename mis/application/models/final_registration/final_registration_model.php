<?php 
class final_registration_model extends CI_Model{

   function getadmn($sy,$s,$adn){
       $admn=$adn;
       $session=$s;
       $session_year=$sy;
 		   if($admn==''){
   		   $stm="select users.*,reg_regular_form.*, reg_regular_form.status as status1  from reg_regular_form INNER JOIN users ON users.id=reg_regular_form.admn_no where session_year='".$session_year."' and `session`='".$session."' and hod_status ='1' and acad_status='1' AND users.`status`='A' AND (reg_regular_form.`status`='0' OR reg_regular_form.`status` IS NULL)";
         $exc=$this->db->query($stm);
         $val=$exc->result_array();
         $rowscheck=$exc->num_rows();
         return $val;
 		   }
 		    else{
	      $stm ="select  users.*,reg_regular_form.*, reg_regular_form.status as status1  from reg_regular_form INNER JOIN users ON users.id=reg_regular_form.admn_no where admn_no='".$admn."' and  session_year='".$session_year."' and  `session`='".$session."' and hod_status='1' and acad_status='1' AND users.`status`='A'";
        $exc=$this->db->query($stm);
	//	echo $this->db->last_query();   die();
        $val=$exc->result_array();
        $rowscheck=$exc->num_rows();
        foreach ($val as $rows){
          $status=$rows['status1'];
        }
        if($rowscheck>0){
          $stm2="SELECT cc.name, ud.salutation,ud.first_name,ud.middle_name, ud.last_name,ud.sex,ud.category,ud.dob,ud.email,ud.photopath,rrf.status, ud.marital_status,ud.physically_challenged,dp.name AS department,br.name AS branch, sa.admn_no,rrf.semester,rrf.session,rrf.session_year,rrf.acad_status,rrf.hod_status,rrf.acad_status,rrf.timestamp FROM user_details AS ud INNER JOIN departments AS dp ON ud.dept_id=dp.id INNER JOIN stu_academic AS sa ON ud.id=sa.admn_no INNER JOIN branches AS br ON sa.branch_id=br.id INNER JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no INNER JOIN cbcs_courses as cc ON rrf.course_id= cc.id 
		  INNER JOIN users u ON u.id=ud.id AND u.`status`='A'
		  WHERE rrf.admn_no='$admn ' and rrf.`session`='$session'and rrf.session_year='$session_year' ORDER BY rrf.timestamp;";
          $exc2=$this->db->query($stm2);
          $val2=$exc2->result_array();
          $val2['0']['student_status']=$status;
		  
          }
        else{
               
               $val2['0']['student_status']="dnt";
        }     
   			return $val2;
   		  }       

    }
    
  function sessiondata(){
         $datas=array();
         $stmty="select distinct session_year from reg_regular_form";
         $stmts="select distinct session from reg_regular_form";
         $excutey=$this->db->query($stmty);
         $excutes=$this->db->query($stmts);
         $valuesy=$excutey->result_array();
         $values=$excutes->result_array();
         $datas['session_year']=$valuesy;
         $datas['session']=$values;
         return $datas;
         }
}
?>