<?php

class Student_registration_details_model extends CI_Model {

    function __construct() {
        parent::__construct();
    } 

        
    function get_stu_list($syear,$sess,$course_id,$bid,$sem)
    {
		//$admn_check=" and  a.admn_no='16JE002447'";
		
		$sql="SELECT a.*,b.dept_id
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.session_year=?  AND a.`session`=?
and a.course_id=? AND a.branch_id=? AND a.semester=?
and a.hod_status='1' AND a.acad_status='1'    $admn_check ";

        
        $query = $this->db->query($sql,array($syear,$sess,$course_id,$bid,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_semwise_stu_list($admn_no){
		
		if((strstr( strtolower($admn_no),'14je')!=false || strstr( strtolower($admn_no),'13je')!=false || strstr( strtolower($admn_no),'12je')!=false||
strstr( strtolower($admn_no),'11je')!=false)){
	 
$sql=" 


SELECT x.*
FROM (
SELECT t.*,'new' AS reg
FROM(
SELECT a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,a.session_year,a.session,a.course_aggr_id
FROM reg_regular_form a
WHERE a.admn_no=? AND a.hod_status='1' AND a.acad_status='1' AND a.`session`<>'Summer'
ORDER BY a.timestamp DESC
LIMIT 10000) t
GROUP BY t.semester,t.admn_no UNION
SELECT a.srn AS form_id,a.adm_no AS admn_no,d.course AS course_id,d.branch AS branch_id, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS semester,a.ysession AS session_year,a.wsms AS SESSION,  
null as course_aggr_id, 'old' AS reg
FROM tabulation1 a
JOIN dip_m_semcode d ON d.semcode=a.sem_code AND a.adm_no=? AND a.wsms<>'ZS'
GROUP BY a.sem_code)x
GROUP BY x.semester 
 order by  CAST(x.semester AS UNSIGNED)
 ";

 $query = $this->db->query($sql,array($admn_no,$admn_no));


}
else{
		
		$sql="SELECT t.*,'reg' as reg  FROM(
SELECT a.* FROM reg_regular_form a WHERE a.admn_no=? and a.hod_status='1' AND a.acad_status='1'
AND a.`session`<>'Summer' ORDER BY a.timestamp desc LIMIT 10000)
t GROUP BY t.semester,t.admn_no   order by  CAST(t.semester AS UNSIGNED)";

 

        
        $query = $this->db->query($sql,array($admn_no));

}  
      //  echo $this->db->last_query(); die();
		
		if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
	
	function get_registration($admn_no,$sem)
	{
		$sql="SELECT COUNT(b.sequence) AS cnt from
(SELECT b.*
FROM reg_regular_form a
INNER JOIN course_structure b ON b.aggr_id=a.course_aggr_id AND b.semester= CONCAT(a.semester,'_',a.section)
INNER JOIN subjects c ON c.id=b.id
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1'
group by b.id) b
";

        
        $query = $this->db->query($sql,array($admn_no,$sem));
 //echo $this->db->last_query(); die();
       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
	function get_registration_rest($admn_no,$sem,$session_year)
	{
		
		/*if(in_array(strtolower($admn_no),Exam_tabulation_config::$start_project_discard_exception)){
			 echo $admn_no; die();
		}*/
		
		
	$table= ( in_array(strtolower($admn_no),Exam_tabulation_config::$start_project_discard_exception)? 'subjects_old' :( $session_year>=Exam_tabulation_config::$start_project_discard_session_yr ?'subjects':  ( in_array($sem,array('3','4','5','6'))!==false? 'subjects_old' :'subjects') )  );
		
		/*$sql="SELECT count(t.cnt1)AS cnt FROM(
SELECT distinct SUBSTRING(b.sequence,1,1) as cnt1
FROM reg_regular_form a
INNER JOIN course_structure b ON b.aggr_id=a.course_aggr_id AND b.semester=a.semester
INNER JOIN subjects c ON c.id=b.id
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1' AND c.credit_hours!='0')t";*/
$sql="SELECT COUNT(t.cnt1) AS cnt
FROM(
SELECT b.sequence AS cnt1
FROM reg_regular_form a
INNER JOIN course_structure b ON b.aggr_id=a.course_aggr_id AND b.semester=a.semester
INNER JOIN $table c ON c.id=b.id
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' 
AND a.acad_status='1' AND c.credit_hours!='0'
GROUP BY SUBSTRING_INDEX(b.sequence, '.', 1)
)t";

        
        $query = $this->db->query($sql,array($admn_no,$sem));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
	
	function get_registration_tabu($admn_no,$srn)
	{
		$sql="SELECT  count(a.adm_no)as  cnt FROM tabulation1 a where  a.adm_no=?   AND a.wsms<>'ZS'   and  a.srn=? group by a.sem_code ";        
        $query = $this->db->query($sql,array($admn_no,$srn));       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
	
	
	function get_registration_cbcs($admn_no,$sem,$session_year)
	{
		/*$sql="SELECT count(t.cnt1)AS cnt FROM(
SELECT distinct SUBSTRING(b.sequence,1,1) as cnt1
FROM reg_regular_form a
INNER JOIN course_structure b ON  b.semester=a.semester 
AND a.course_id=SUBSTRING_INDEX(b.aggr_id,'_',1)
AND a.branch_id=  SUBSTRING_INDEX(SUBSTRING_INDEX(b.aggr_id,'_',2),'_',-1)
INNER JOIN old_course_structure d ON d.aggr_id=b.aggr_id AND d.semester=b.semester
INNER JOIN subjects e ON e.id=b.id
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1' AND e.credit_hours!='0'
)t
";*/

			$table= ( in_array(strtolower($admn_no),Exam_tabulation_config::$start_project_discard_exception)? 'subjects_old' :( $session_year>=Exam_tabulation_config::$start_project_discard_session_yr ?'subjects':  ( in_array($sem,array('3','4','5','6'))!==false? 'subjects_old' :'subjects') )  );
		
		
$sql="SELECT count(t.cnt1)AS cnt FROM(
SELECT b.sequence AS cnt1 
FROM reg_regular_form a
INNER JOIN course_structure b ON  b.semester=a.semester 
AND a.course_id=SUBSTRING_INDEX(b.aggr_id,'_',1)
AND   (case when  a.branch_id='phy' then 'ap' else   a.branch_id end)  =  SUBSTRING_INDEX(SUBSTRING_INDEX(b.aggr_id,'_',2),'_',-1)
INNER JOIN old_course_structure d ON d.aggr_id=b.aggr_id AND d.semester=b.semester
INNER JOIN $table e ON e.id=b.id
INNER JOIN stu_academic f ON f.enrollment_year=d.batch AND f.admn_no=a.admn_no
WHERE a.admn_no=? AND a.semester=? AND a.hod_status='1' AND a.acad_status='1' AND e.credit_hours!='0'
GROUP BY SUBSTRING_INDEX(b.sequence, '.', 1)
)t
";


        
        $query = $this->db->query($sql,array($admn_no,$sem));
 //echo $this->db->last_query(); die();
       
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	}
	
	
// non static function
// finding   total pass  students
        function check_overall_pass_status_cbcs($admn_no){		    
				    $stu_list=$this->get_semwise_stu_list($admn_no); 
					$reg_count=0;
				    foreach ($stu_list as $b)
					{
				     if($b->reg=='old'){
						   $reg=$this->get_registration_tabu($b->admn_no,$b->form_id);
						 //  echo '<br/>'. $this->db->last_query().'<br/>';
				           $reg_count+=$reg->cnt;					 
					 }	
                    else{					 
					 $p=explode('-',$b->session_year);
					 $comm=explode('_',$b->course_aggr_id);
					 if($p[0]<=2018){
						if(($b->semester==1 || $b->semester==2) && $comm[0]=='comm'){
				          $reg=$this->get_registration($b->admn_no,$b->semester);
						 // echo '<br/>' .$this->db->last_query().'<br/>';
				          $reg_count+=$reg->cnt;								
						 }else{									
				           $reg=$this->get_registration_rest($b->admn_no,$b->semester,$b->session_year);
						  // echo '<br/>'. $this->db->last_query().'<br/>';
				           $reg_count+=$reg->cnt;					
						 }
						 
						 
					  }else{							
						$reg=$this->get_registration_cbcs($b->admn_no,$b->semester,$b->session_year);
						// echo '<br/>' .$this->db->last_query().'<br/>';
					    $reg_count+=$reg->cnt;					
					  }
					   //honours start=====
					  $h=$this->check_honours($b->admn_no);
					  if(!empty($h)){
						$hcount=$this->get_honours_count($h->honours_agg_id);	
						}else{
						$hcount=0;	
						}
						//honours end=====
						//minor start========
					   $m=$this->check_minor($b->admn_no);
					   if(!empty($m)){
						 $mcount=$this->get_minor_count($b->minor_agg_id);	
						}else{
							$mcount=0;
						}
						//echo $admn_no.'--';
					
						
						//minor end========
					
				      }
					  //	echo '<br/>' . $b->semester.'  sem=>'.$b->session_year.' paperctr:' .$reg->cnt;
					} // end  of for loop
					
					//echo '<br/>sum:' .($reg_count+$hcount)
					//die();
					//echo ($reg_count.'+'.$hcount.'='.($reg_count+$hcount));
					$data['reg_count']=$reg_count;
					$data['hcount']=$hcount;
					$data['mcount']=$mcount;
					//print_r($data); die();
					 return  ($data);						
                   }
	
	function check_honours($admn_no){
		$sql="SELECT a.* from hm_form a WHERE a.honours='1' AND a.honour_hod_status='Y'
AND a.admn_no=?";

        
        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();;
        } else {
            return false;
        }
	}
		function check_minor($admn_no){
		$sql="SELECT  b.* from hm_form a 
INNER JOIN hm_minor_details b ON b.form_id=a.form_id AND b.offered='1'
WHERE a.minor='1' AND a.minor_hod_status='Y'
AND a.admn_no=?";

        
        $query = $this->db->query($sql,array($admn_no));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row();;
        } else {
            return false;
        }
	}
	function get_honours_count($aggr_id){
		$sql="SELECT COUNT(id)AS cnt FROM course_structure a WHERE a.aggr_id=?";

        
        $query = $this->db->query($sql,array($aggr_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row()->cnt;;
        } else {
            return false;
        }
	}
	function get_minor_count($aggr_id){
		$sql="SELECT COUNT(id)AS cnt FROM course_structure a WHERE a.aggr_id=?";

        
        $query = $this->db->query($sql,array($aggr_id));

       
        if ($this->db->affected_rows() > 0) {
            return $query->row()->cnt;;
        } else {
            return false;
        }
	}
	
	

    
}

?>