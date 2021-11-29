<?php
class Unregister_candidates_model extends CI_model{

public function sessiondata()
  {
   $datas=array();
   $stmtd="select id,name from cbcs_courses where id not in ('minor','honour','m.phil','comm','online') order by name asc";
   $excdep=$this->db->query($stmtd);
   $valuesd=$excdep->result_array();
   $datas['course']=$valuesd;


   $stmty="select session_year from mis_session_year";
   $excutey=$this->db->query($stmty);
   //if($excutey->num_rows() > 0)
     
      $valuesy=$excutey->result_array();
      $datas['session_year']=$valuesy;
      



   $stmts="select * from mis_session ";
   $excutes=$this->db->query($stmts); 
   $values=$excutes->result_array();
   $datas['session']=$values;
   return $datas;

  }
  public function all_unregister_candicates($sess,$sessyear,$course,$id)
  {
       
    
	
      $smt="SELECT sa.admn_no,CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) as stu_name,sa.course_id,upper(sa.branch_id) 
          FROM users u
          JOIN stu_academic sa ON sa.admn_no=u.id
          JOIN user_details AS ud ON ud.id=u.id
          where u.`status`='A' AND u.id LIKE '%$id%' AND sa.course_id='$course' and sa.admn_no NOT IN(
          SELECT t.admn_no
         FROM reg_regular_form t
         WHERE t.course_id='$course' AND t.`session`='$sess' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') GROUP BY sa.admn_no";
	    $query=$this->db->query($smt);
		 if($query)
		 {
		 $result=$query->result_array();
		 return $result;
		 }
		 else
		 {
		 return false;
		 }
        

   }
   public function bothsess_all_unregister_candicate($sessyear,$course,$id)
   {

   $smt="SELECT sa.admn_no, CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,sa.course_id,upper(sa.branch_id)
              FROM users u
             JOIN stu_academic sa ON sa.admn_no=u.id
             JOIN user_details AS ud ON ud.id=u.id
             WHERE u.`status`='A' AND u.id LIKE '%$id%' AND sa.course_id='$course' AND sa.admn_no NOT IN(
           SELECT t.admn_no
           FROM reg_regular_form t
           WHERE t.course_id='$course' AND (t.`session`='Winter' OR t.`session`='Monsoon') AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') GROUP BY sa.admn_no";
         // union all
         //   (SELECT sa.admn_no, CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,sa.course_id,upper(sa.branch_id)
         //  FROM users u
         //  JOIN stu_academic sa ON sa.admn_no=u.id
         //  JOIN user_details AS ud ON ud.id=u.id
         //  WHERE u.`status`='A' AND u.id LIKE '%$id%' AND sa.course_id='$course' AND sa.admn_no NOT IN(
         //  SELECT t.admn_no
         //  FROM reg_regular_form t
         //  WHERE t.course_id='$course' AND t.`session`='monsoon' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1'))"
	$query=$this->db->query($smt);
		 if($query)
		 {
		 $result=$query->result_array();
		 // echo "<pre>";
		 // print_r($result);
		 // exit;
		 return $result;
		 }
		 else
		 {
		 return false;
		 }
        
   }
  
   
   public function fulltime_parttimephd($sess,$sessyear,$course){
   		
   		$id='dr';
   		$dp='dp';
   	   $smt="SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,
       CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
		FROM users u
		JOIN stu_academic sa ON sa.admn_no=u.id
		JOIN user_details AS ud ON ud.id=sa.admn_no
		LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
		LEFT JOIN user_details AS uds ON pg.guide=uds.id
		WHERE sa.admn_no NOT IN(
		SELECT t.admn_no
        FROM reg_regular_form t
        WHERE t.course_id='$course' AND t.`session`='$sess' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
        AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='$course' GROUP BY sa.admn_no";
        $query=$this->db->query($smt);
	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}

   }
   public function fulltimephd($sess,$sessyear,$course,$ft){
   	
   	   

	 $smt="select sa.admn_no,sa.semester,UPPER(sa.branch_id),CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`)
        AS stu_name,CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
		FROM users u
		JOIN stu_academic sa ON sa.admn_no=u.id
        JOIN user_details AS ud ON ud.id=sa.admn_no
		left JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
		left JOIN user_details AS uds ON pg.guide=uds.id
		WHERE sa.admn_no NOT IN(
		SELECT t.admn_no
		FROM reg_regular_form t
        WHERE t.course_id='$course' AND t.`session`='$sess' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
        AND u.`status`='A' AND (u.id LIKE '%dr%'or u.id LIKE '%dp%') AND sa.course_id='$course' and sa.other_rank='fulltime' GROUP BY sa.admn_no";

        $query=$this->db->query($smt);
        //echo $this->db->last_query();
	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}

   }
   public function parttimephd($sess,$sessyear,$course,$ft){

   	   $f='dp';

		 $smt="select sa.admn_no,sa.semester,UPPER(sa.branch_id),CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`)
        AS stu_name,CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
		FROM users u
		JOIN stu_academic sa ON sa.admn_no=u.id
		JOIN user_details AS ud ON ud.id=sa.admn_no
		left JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
		left JOIN user_details AS uds ON pg.guide=uds.id
		WHERE sa.admn_no NOT IN(
		SELECT t.admn_no
		FROM reg_regular_form t
        WHERE t.course_id='$course' AND t.`session`='$sess' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
        AND u.`status`='A' AND (u.id LIKE '%dr%'or u.id LIKE '%dp%')AND sa.course_id='$course' and sa.other_rank='parttime' GROUP BY sa.admn_no";
        $query=$this->db->query($smt);
	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}


   }
    public function  session_both_fulltime_parttimephd($sessyear,$course){
   		
   	 $smt="SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,
        CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
        FROM users u
        JOIN stu_academic sa ON sa.admn_no=u.id
        JOIN user_details AS ud ON ud.id=sa.admn_no
        LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
        LEFT JOIN user_details AS uds ON pg.guide=uds.id
        WHERE sa.admn_no NOT IN(
        SELECT t.admn_no
        FROM reg_regular_form t
        WHERE t.course_id='jrf' AND (t.`session`='monsoon' OR t.`session`='Winter') AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
       AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='jrf' GROUP BY sa.admn_no";

       // (SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,
       // CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
       //  FROM users u
       //  JOIN stu_academic sa ON sa.admn_no=u.id
       //  JOIN user_details AS ud ON ud.id=sa.admn_no
       //  LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
       //  LEFT JOIN user_details AS uds ON pg.guide=uds.id
       //  WHERE sa.admn_no NOT IN(
       //  SELECT t.admn_no
       //  FROM reg_regular_form t
       //  WHERE t.course_id='jrf' AND t.`session`='winter' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
       //   AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='jrf')
         
        $query=$this->db->query($smt);
	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}



   }
   public function  session_both_fulltimephd($sessyear,$course)
   {
   	
	 $smt="SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) 
AS stu_name, CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
FROM users u
JOIN stu_academic sa ON sa.admn_no=u.id
JOIN user_details AS ud ON ud.id=sa.admn_no
LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
LEFT JOIN user_details AS uds ON pg.guide=uds.id
WHERE sa.admn_no NOT IN(
SELECT t.admn_no
FROM reg_regular_form t
WHERE t.course_id='jrf' AND (t.`session`='monsoon' OR t.`session`='Winter') AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='jrf' AND sa.other_rank='fulltime' GROUP BY sa.admn_no"; 
// UNION ALL
// (SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`)
//  AS stu_name, CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
// FROM users u
// JOIN stu_academic sa ON sa.admn_no=u.id
// JOIN user_details AS ud ON ud.id=sa.admn_no
// LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
// LEFT JOIN user_details AS uds ON pg.guide=uds.id
// WHERE sa.admn_no NOT IN(
// SELECT t.admn_no
// FROM reg_regular_form t
// WHERE t.course_id='jrf' AND t.`session`='winter' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1')
//  AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='jrf' AND sa.other_rank='fulltime')";

        $query=$this->db->query($smt);
      //  echo $this->db->last_query();

	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}

   }
   public function session_both_parttimephd($sessyear,$course)
   {

   	   $f='dp';

		   $smt="SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,
            CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS profsname,sa.other_rank
			FROM users u
			JOIN stu_academic sa ON sa.admn_no=u.id
			JOIN user_details AS ud ON ud.id=sa.admn_no
			LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
			LEFT JOIN user_details AS uds ON pg.guide=uds.id
			WHERE sa.admn_no NOT IN(
			SELECT t.admn_no
			FROM reg_regular_form t
            WHERE t.course_id='$course' AND (t.`session`='monsoon' OR t.`session`='winter') AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
            AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='$course'and sa.other_rank='parttime' GROUP BY sa.admn_no";
        // union all
        //  (SELECT sa.admn_no,sa.semester, UPPER(sa.branch_id), CONCAT_WS(' ',ud.`first_name`, ud.`middle_name`, ud.`last_name`) AS stu_name,
        //  CONCAT_WS(' ',uds.`first_name`, uds.`middle_name`, uds.`last_name`) AS   profsname,sa.other_rank
        //    FROM users u
        //    JOIN stu_academic sa ON sa.admn_no=u.id
        //    JOIN user_details AS ud ON ud.id=sa.admn_no
        //    LEFT JOIN project_guide AS pg ON pg.admn_no=sa.admn_no
        //    LEFT JOIN user_details AS uds ON pg.guide=uds.id
        //    WHERE sa.admn_no NOT IN(
        //    SELECT t.admn_no
        //    FROM reg_regular_form t
        //    WHERE t.course_id='$course' AND t.`session`='winter' AND t.session_year='$sessyear' AND t.hod_status='1' AND t.acad_status='1') 
        //     AND u.`status`='A' AND (u.id LIKE '%dr%' OR u.id LIKE '%dp%') AND sa.course_id='$course' and sa.other_rank='parttime')";
        $query=$this->db->query($smt);
	    if($query)
		{
		 
		 $result=$query->result_array();
		 return $result;
		}
		else
		{

		 return false;

		}


   }


 
}

?>