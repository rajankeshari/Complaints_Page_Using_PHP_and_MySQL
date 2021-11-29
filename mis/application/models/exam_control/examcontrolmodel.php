<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of examControl
 *
 * @author Your Name <Ritu Raj @ ISM DHANBAD>
 */
class Examcontrolmodel extends CI_Model
{
     private $defaulter_type;
     private $defaulter;
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
                $this->defaulter_type='defaulter_type';
                $this->defaulter='exam_attd_exception';
                
	}	
        
    function getDefaulterType($param=null){        
        if($param<>null)
             $q= $this->db->select('id,category')->where(array('category'=>$param))->group_by('category')->get($this->defaulter_type);
        else
             $q= $this->db->select('id,category')->group_by('category')->get($this->defaulter_type);
          if ($q->num_rows() > 0)
              return $q->result();
           else {
             return 0;
          }            
        }
        
  function getsubjects($emp_id,$session,$session_yr,$exam_type,$dept){
                    if(!empty($emp_id)){
                        
                        if($dept=='prep'){ 
                         $exchange="   ";
                       $secure_array=array($emp_id,$session_yr,$session,'1');
                       }
                       else{ 
                           $exchange=" AND orig_dpt.id=? ";
                           $secure_array=array($emp_id,$session_yr,$session,'1',$dept);
                       }
                        
                       $rep=' and b.emp_no=? ';
                       //$rep_sel=" ";
                       //$rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel=" ,concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as st_name ";
                       $rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel1 =' ,orig_dpt.name as orig_dept_name ';
                       $rep_join2=' inner JOIN departments orig_dpt ON orig_dpt.id=u.dept_id '.$exchange.'  ';
                       
                       
                        
                       
                    }
                       else{                                                                      
                           $secure_array=array($session_yr,$session,'1',$dept);                       
                          $rep='';
                       $rep_sel=" ,concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as st_name ";
                       $rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel1 =' ,orig_dpt.name as orig_dept_name ';
                       $rep_join2=' inner JOIN departments orig_dpt ON orig_dpt.id=u.dept_id and orig_dpt.id<>? ';
                       
                       
                          
                       
                       }
                       
            $sql=" select  x.*, mm.highest_marks, mm.id as  marks_master_id,mm.`status`, concat(x.aggr_id,'[', (CASE
    WHEN mm.type='R' THEN 'Regular'
    WHEN mm.type='J' THEN 'JRF'
    WHEN mm.type='S' THEN 'Special'
    WHEN mm.type='O' THEN 'Other'    
    ELSE mm.type  END),']') as reg_type,s.name,s.subject_id,d.name as dept_name,br.name as branch_name,cs.name as crs_name  ".$rep_sel1."  ".$rep_sel."
                    from
                       (select  a.map_id, a.dept_id,a.course_id,a.branch_id,a.semester,a.`group`,a.section , b.sub_id,b.emp_no,a.session_year,a.`session`,a.aggr_id from  subject_mapping a
                       inner join subject_mapping_des b on  a.map_id=b.map_id ".$rep." and a.`session_year`=? and a.session=?  and b.coordinator=?)x 
                       inner join marks_master mm on  (mm.subject_id=x.sub_id and mm.session_year=x.session_year and mm.`session`=x.session and mm.sub_map_id=x.map_id and /*mm.highest_marks<>''*/mm.status='Y')
                       inner join subjects s on s.id=x.sub_id  inner JOIN departments d ON d.id=x.dept_id left join cs_branches br on br.id=x.branch_id
                       left join cs_courses cs on cs.id=x.course_id  
                       ".$rep_join." ".$rep_join2."
                       order by  orig_dept_name,st_name,s.name ";
            
              $query = $this->db->query($sql, $secure_array);
       // echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
        }
   
		
		/*  function getsubjects($emp_id,$session,$session_yr,$exam_type,$dept){
                    if(!empty($emp_id)){
                       $secure_array=array($emp_id,$session_yr,$session,'1',$dept);
                       $rep=' and b.emp_no=? ';
                       //$rep_sel=" ";
                       //$rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel=" ,concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as st_name ";
                       $rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel1 =' ,orig_dpt.name as orig_dept_name ';
                       $rep_join2=' inner JOIN departments orig_dpt ON orig_dpt.id=u.dept_id and orig_dpt.id=? ';
                       
                    }
                       else{
                       $secure_array=array($session_yr,$session,'1',$dept);
                       $rep='';
                       $rep_sel=" ,concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as st_name ";
                       $rep_join=' inner join user_details u on u.id=x.emp_no ';
                       $rep_sel1 =' ,orig_dpt.name as orig_dept_name ';
                       $rep_join2=' inner JOIN departments orig_dpt ON orig_dpt.id=u.dept_id and orig_dpt.id<>? ';
                       }
                       
            $sql=" select  x.*, mm.highest_marks, mm.id as  marks_master_id,mm.`status`,s.name,s.subject_id,d.name as dept_name,br.name as branch_name,cs.name as crs_name  ".$rep_sel1."  ".$rep_sel."
                    from
                       (select  a.map_id, a.dept_id,a.course_id,a.branch_id,a.semester,a.`group`,a.section , b.sub_id,b.emp_no,a.session_year,a.`session` from  subject_mapping a
                       inner join subject_mapping_des b on  a.map_id=b.map_id ".$rep." and a.`session_year`=? and a.session=?  and b.coordinator=?)x 
                       inner join marks_master mm on  (mm.subject_id=x.sub_id and mm.session_year=x.session_year and mm.`session`=x.session and mm.sub_map_id=x.map_id and mm.highest_marks<>'')
                       inner join subjects s on s.id=x.sub_id  inner JOIN departments d ON d.id=x.dept_id left join cs_branches br on br.id=x.branch_id
                       left join cs_courses cs on cs.id=x.course_id  
                       ".$rep_join." ".$rep_join2."
                       order by  orig_dept_name,st_name,s.name ";
            
              $query = $this->db->query($sql, $secure_array);
        //echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
        }*/
        
        function getDefaulterList($sess,$session_yr,$exam_type,$dept,$cat_sel,$crs=null,$br=null,$sem=null){        
             //echo $sess.','.$session_yr.','.$exam_type.','.$dept.','.$cat_sel.','.$crs.','.$br.','.$sem; 
           $cat_sel1='HD'; $cat_sel2='MD';
          if(substr($cat_sel, 0, 2)=='HD'|| substr($cat_sel, 0, 2)=='MD') $cat_sel=substr($cat_sel, 0, 2);
             
          if($crs=='' ||$br=='' || $sem==''){
              $this->db->select('st.*,u.description,u.category');
              $this->db->from($this->defaulter.' st');              
              $this->db->join($this->defaulter_type.' u', 'u.id = st.defaulter_type');              
              
              if(substr($cat_sel, 0, 2)=='HM'){
               $secure=($dept=='all' ?array('session'=>$sess,'session_yr'=>$session_yr,'exam_type'=>$exam_type):array('session'=>$sess,'session_yr'=>$session_yr,'dept'=>$dept, 'exam_type'=>$exam_type));
               $this->db->where($secure);
               $this->db->where("(defaulter_type  like '".$cat_sel1."%'  || defaulter_type  like'".$cat_sel2."%' )");
              }              
              else{
               $secure=($dept=='all' ?array('session'=>$sess,'session_yr'=>$session_yr, 'exam_type'=>$exam_type,'defaulter_type  like' => ''.$cat_sel.'%'  )  :array('session'=>$sess,'session_yr'=>$session_yr,'dept'=>$dept, 'exam_type'=>$exam_type,'defaulter_type  like' => ''.$cat_sel.'%'  ) )     ;
              $this->db->where($secure);              
              }
              $q= $this->db->get();
          }
          else{
              $this->db->select('st.*,u.description,u.category');                                      
              $this->db->from($this->defaulter.' st');        
              $this->db->join($this->defaulter_type.' u', 'u.id = st.defaulter_type');
              $this->db->where(array('upper(course)'=>  strtoupper($crs),'upper(branch)'=>strtoupper($br),'semester'=>$sem,'session'=>$sess,'session_yr'=>$session_yr,'dept'=>$dept,
                                                                'exam_type'=>$exam_type,'defaulter_type  like' => ''.$cat_sel.'%'  ));
              $q= $this->db->get();
          }
        //   echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
              return $q->result();
           else {
             return 0;
          }            
        }
        function update_defaulter_ctrl($data,$where){            
            if($this->db->update($this->defaulter, $data, $where))
                    return 'success';
                else 
                      return $this->db->_error_message();
            }
        
        function save_ajax_marks_final_status_change_log($data){
        $this->db->trans_strict(true);          
        $this->db->trans_start(); 
        $this->db->select('status');
        $this->db->from('marks_master');
        $this->db->where(array('id' => $data['marks_master_id']));
        $select = $this->db->get();
           //echo $this->db->last_query(); die();
        $affected[]=$select->num_rows();
        if ($select->num_rows()) {
            //print_r($select->result_array() );die(); 
            $row = $select->result_array();
            $data['old_status'] = $row[0]['status'];            
        }      
        $this->db->update('marks_master', array('status'=>$data['curr_status']), array('id'=>$data['marks_master_id']));
        if($data['curr_status']=='N'){
        $this->db->delete('marks_subject_description', array('marks_master_id'=>$data['marks_master_id']));

$this->db->delete('marks_master', array('id'=>$data['marks_master_id']));}
       //echo $this->db->last_query(); die();
        //$affected[]=$this->db->affected_rows();
        $this->db->insert('marks_finalize_status_log',$data);
         //echo $this->db->last_query(); die();
        $affected[]=$this->db->affected_rows();
        $this->db->trans_complete();   
               //     print_r($affected); die();
        if(in_array(0,$affected)  || ($this->db->trans_status()===FALSE)) 
          return false;
          else
          return true;
        }            
        
        
        function get_PREP_COMM_Guide_List_by_param($session_yr,$session,$dept_id){
           if ( isset($dept_id)) {
               if(strtolower($dept_id)=='comm')
               $replace= " a.dept_id=? ";
               else if(strtolower($dept_id)=='prep')
               $replace= " a.course_id=? " ;
            $secure_array=array($session_yr,$session,$dept_id,'1');
            $sql="select x.emp_no as id ,u.salutation,u.first_name,u.middle_name,u.last_name from
                    (SELECT b.emp_no
                            FROM subject_mapping a
                                  INNER JOIN subject_mapping_des b ON a.map_id=b.map_id  AND a.session_year=? AND a.session=? AND ".$replace." AND b.coordinator=? 
                                         group by b.emp_no)x
                                                  inner join user_details u on u.id=x.emp_no order by  concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name)";
            
              $query = $this->db->query($sql, $secure_array);
         //echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else 
            return false;
        
        } else
            return FALSE;
        }
/*		
function get_unique_record_from_foil($dept,$crs,$br,$start,$eachset,$option=2,$admn_no=null){
	
	
// echo  'dept:'.$dept.'crs:'.$crs.'br:'.$br.'strt'.$start.'end'.$eachset .'option'.$option.',admn'.$admn_no; die();
        $admn_no = preg_replace('/\s+/', '', $admn_no);
     // $start='1';
//    / $eachset=25;
      if ($admn_no == null) {
          if($option==2)
             $rep=" limit ".$start." ,".$eachset."";
          else
           $rep=''; 
      }
        else
        $rep=''; 
        
   
        if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and admn_no in(" . $admn_no . ") ";       
                $secure_array = array($dept, $crs, $br);
            } else {
                $replacer1 = " and admn_no=? ";               
                 $secure_array = array($dept, $crs, $br,$admn_no);
            }
        } else {
            $replacer1 = "";     
               $secure_array = array($dept, $crs, $br);
        }
        
                  $sql = " select * from final_semwise_marks_foil where  lower(dept)=? and lower(course)=? and lower(branch)=?  ".$replacer1."   group by admn_no order by admn_no  ".$rep." ";
  //echo $sql;
            $query = $this->db->query($sql, $secure_array);
         //   echo $this->db->last_query(); die();

            if ($query->num_rows() > 0)
                return $option==2?$query->result():$query->num_rows();
            else
                return 0;
        
}*/
	
function get_unique_record_from_regular_registrarion($session,$session_yr,$crs,$br,$sem, $start,$eachset,$option=2,$admn_no=null){
	// echo  'dept:'.$dept.'crs:'.$crs.'br:'.$br.'strt'.$start.'end:'.$eachset .'option'.$option.',admn'.$admn_no; die();
	       $pred='20';
	    $yr = explode('-', $session_yr);
        //$new_prevsessyr_1 = substr($yr[0], 2, 2) - 1;	// original by Rituraj
		$new_prevsessyr_1 = substr($yr[0], 2, 2) - 0;     //modified by D.Ray on emergency basis to get the couuernt registered student list 27/12/2018
        $prevsessyr_2 = substr($yr[0], 2, 2);
        $prev_session_yr=$pred . $new_prevsessyr_1 . '-' . $pred . $prevsessyr_2;
		
		$prev_session=($session=='Monsoon'?'Winter':'Monsoon');
	
	
        $admn_no = preg_replace('/\s+/', '', $admn_no);
     // $start='1';
//    / $eachset=25;
      if ($admn_no == null) {
          if($option==2)
             $rep=" limit ".$start." ,".$eachset."";
          else
           $rep=''; 
      }
        else
        $rep=''; 
        
  /* if($sem==null){
        if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and admn_no in(" . $admn_no . ") ";       
                $secure_array = array($session,$session_yr, $crs, $br);
            } else {
                $replacer1 = " and admn_no=? ";               
                 $secure_array = array($session,$session_yr, $crs, $br,$admn_no);
            }
        } else {
            $replacer1 = "";     
               $secure_array = array($session,$session_yr, $crs, $br);
        }
		
   }else{*/
	   
	     if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and admn_no in(" . $admn_no . ") ";       
                $secure_array = array($session,$session_yr, $crs, $br,$sem,($sem-1),$prev_session_yr,$crs, $br,$prev_session_yr,$prev_session,($sem-1),$crs, $br);
            } else {
                $replacer1 = " and admn_no=? ";               
                 $secure_array = array($session,$session_yr, $crs, $br,$sem,$admn_no,($sem-1),$prev_session_yr,$crs, $br,$admn_no,$prev_session_yr,$prev_session, ($sem-1),$crs, $br,$admn_no);
            }
        } else {
            $replacer1 = "";     
               $secure_array = array($session,$session_yr, $crs, $br,$sem,($sem-1),$prev_session_yr,$crs, $br,$prev_session_yr,$prev_session, ($sem-1),$crs, $br);
        }
		
		//$add_qry=" and a.semester=? ";
   //}	
        
              
                  
           /*$sql= "select x.*, concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as  stu_name,ud.category  from
(
SELECT a.admn_no,a.course_id,a.branch_id,a.semester,a.section,a.course_aggr_id ,b.fee_amt
FROM reg_regular_form a  join reg_regular_fee b on a.form_id=b.form_id
WHERE SESSION=? AND session_year=? AND LOWER(course_id)=? AND LOWER(branch_id)=? $add_qry  AND hod_status<>'2' AND acad_status<>'2' ".$replacer1."
GROUP BY  a.admn_no
)x
join user_details ud on ud.id=x.admn_no
ORDER BY x.semester,x.admn_no ".$rep." 
		   ";
             */


$sql="SELECT x.*, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS stu_name,ud.category
FROM (

select  y.* from  (
(
SELECT a.admn_no,a.course_id,a.branch_id,a.semester,a.section,a.course_aggr_id,b.fee_amt, rbf.amount1 as amt_to_be_paid, 'AR' as caption
FROM reg_regular_form a
JOIN reg_regular_fee b ON a.form_id=b.form_id
join reg_bank_fee rbf on rbf.admn_no=b.admn_no and rbf.session_year=a.session_year and rbf.session=a.session and  rbf.semester=a.semester
and a.session=? AND a.session_year=? AND LOWER(a.course_id)=? AND LOWER(a.branch_id)=?  AND a.semester=?  AND a.hod_status<>'2' AND a.acad_status<>'2'
GROUP BY a.admn_no)
union
(
SELECT B.*
FROM (
SELECT  a.admn_no, a.course,a.branch, a.semester,null as section,null as course_aggr_id, 0 fee_amt,0 amt_to_be_paid,'NR' as caption
FROM final_semwise_marks_foil a WHERE  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1')  and  a.semester=? and a.session_yr=?
AND LOWER(a.course)=? AND LOWER(a.branch)=? 
GROUP BY a.admn_no,a.session_yr,a.session,a.semester,a.exam_type
ORDER BY a.admn_no,a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
GROUP BY B.admn_no)

union

(select b.admn_no,b.course_id,b.branch_id,b.semester,null as section,b.course_aggr_id , 0 fee_amt ,0 amt_to_be_paid,'NR' as caption from  reg_idle_form b where b.session_year=? and b.`session`=? and
b.semester=? and  LOWER(b.course_id)=? AND LOWER(b.branch_id)=? and b.hod_status<>'2' AND b.acad_status<>'2'
GROUP BY b.admn_no)


order by admn_no,fee_amt desc)y 
group by y.admn_no
) x
JOIN user_details ud ON ud.id=x.admn_no
ORDER BY x.admn_no
";

			 

            $query = $this->db->query($sql, $secure_array);
            //echo $this->db->last_query(); die();

            if ($query->num_rows() > 0)
                return $option==2?$query->result():$query->num_rows();
            else
                return 0;
        
    }
       
	

function get_unique_record_from_foil($dept,$crs,$br,$start,$eachset,$option=2,$admn_no=null){
	// echo  'dept:'.$dept.'crs:'.$crs.'br:'.$br.'strt'.$start.'end:'.$eachset .'option'.$option.',admn'.$admn_no; die();
        $admn_no = preg_replace('/\s+/', '', $admn_no);
     // $start='1';
//    / $eachset=25;
      if ($admn_no == null) {
          if($option==2)
             $rep=" limit ".$start." ,".$eachset."";
          else
           $rep=''; 
      }
        else
        $rep=''; 
        
   
        if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and admn_no in(" . $admn_no . ") ";       
                $secure_array = array($dept, $crs, $br,$dept, $crs, $br);
            } else {
                $replacer1 = " and admn_no=? ";               
                 $secure_array = array($dept, $crs, $br,$admn_no,$dept, $crs, $br,$admn_no);
            }
        } else {
            $replacer1 = "";     
               $secure_array = array($dept, $crs, $br,$dept, $crs, $br);
        }
		
        
                  $sql = "select x.* from ( ( select * from final_semwise_marks_foil where  lower(dept)=? and lower(course)=? and lower(branch)=?  ".$replacer1.")
				  union 
				  (  select * from alumni_final_semwise_marks_foil where  lower(dept)=? and lower(course)=? and lower(branch)=?  ".$replacer1.")
				  )x  
				  group by x.admn_no order by x.admn_no  ".$rep." ";
                  
           
                  

            $query = $this->db->query($sql, $secure_array);
           //echo $this->db->last_query(); die();

            if ($query->num_rows() > 0)
                return $option==2?$query->result():$query->num_rows();
            else
                return 0;
        
    }
       
	
        
    /*   function check_ogpa_update_log($admn_no, $sem,$foil_id ) {
      //  echo $admn_no.','. $sem . $table.'<br/>';
        $table='final_semwise_marks_foil_log_temp';        
         $s_replace = "  and a.semester ='" . $sem . "' ";
          
        
     
        $sql = " 
SELECT (CASE WHEN ((a.hstatus='Y')) THEN a.core_status ELSE a.status END) AS core_status, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session, (CASE WHEN (a.status = 'FAIL' AND a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' AND a.hstatus='Y') THEN 'INC' ELSE (CASE WHEN ((a.hstatus='Y')) THEN FORMAT(a.core_gpa,5) ELSE FORMAT(a.gpa,5) END) END) AS gpa, (CASE WHEN (a.hstatus='Y') THEN (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) ELSE 'N/A' END) AS GPA_with_H, (CASE WHEN (a.hstatus='Y') THEN a.status ELSE 'N/A' END) AS H_status, (CASE WHEN (a.status = 'FAIL' AND a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' AND a.hstatus='Y') THEN 'INC' ELSE (CASE WHEN (a.hstatus='Y') THEN a.core_cgpa ELSE a.cgpa END) END) AS ogpa, (CASE WHEN (a.hstatus='Y') THEN (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) ELSE 'N/A' END) AS H_ogpa,a.hstatus, (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) AS totcrhr_h,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) AS totcrpts_h, a.core_tot_cr_hr AS totcrhr,a.core_tot_cr_pts AS totcrpts, (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END) AS ctotcrpts_h, (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr ELSE 'N/A' END) AS ctotcrhr_h, a.core_ctotcrpts AS ctotcrpts,a.core_ctotcrhr AS ctotcrhr,a.id AS foil_id
FROM final_semwise_marks_foil_log_temp a
WHERE a.admn_no=? AND a.course<>'MINOR' AND a.semester =? and a.id=? limit 1  " ;
        $query = $this->db->query($sql, array($admn_no, $sem,$foil_id));
       //echo $this->db->last_query(); die();
        return ($query->num_rows() > 0 ? $query->row() : false);                   
    }
	*/
	function check_ogpa_update_log($admn_no, $sem,$foil_id ) {
      //  echo $admn_no.','. $sem . $table.'<br/>';
        $table='final_semwise_marks_foil_log_temp';        
         $s_replace = "  and a.semester ='" . $sem . "' ";
          
        
     
        $sql = " 
SELECT (CASE WHEN ((a.hstatus='Y')) THEN a.core_status ELSE a.status END) AS core_status, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr,a.session, (CASE WHEN (a.status = 'FAIL' AND a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' AND a.hstatus='Y') THEN 'INC' ELSE (CASE WHEN ((a.hstatus='Y')) THEN FORMAT(a.core_gpa,5) ELSE FORMAT(a.gpa,5) END) END) AS gpa, (CASE WHEN (a.hstatus='Y') THEN (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE FORMAT(a.gpa,5) END) ELSE 'N/A' END) AS GPA_with_H, (CASE WHEN (a.hstatus='Y') THEN a.status ELSE 'N/A' END) AS H_status, (CASE WHEN (a.status = 'FAIL' AND a.hstatus<>'Y') THEN 'INC' WHEN (a.core_status = 'FAIL' AND a.hstatus='Y') THEN 'INC' ELSE (CASE WHEN (a.hstatus='Y') THEN a.core_cgpa ELSE a.cgpa END) END) AS ogpa, (CASE WHEN (a.hstatus='Y') THEN (CASE WHEN (a.status = 'FAIL') THEN 'INC' ELSE a.cgpa END) ELSE 'N/A' END) AS H_ogpa,a.hstatus, (CASE WHEN (a.hstatus='Y') THEN a.tot_cr_hr ELSE 'N/A' END) AS totcrhr_h,(CASE WHEN (a.hstatus='Y') THEN a.tot_cr_pts ELSE 'N/A' END) AS totcrpts_h, a.core_tot_cr_hr AS totcrhr,a.core_tot_cr_pts AS totcrpts, (CASE WHEN (a.hstatus='Y') THEN a.ctotcrpts ELSE 'N/A' END) AS ctotcrpts_h, (CASE WHEN (a.hstatus='Y') THEN a.ctotcrhr ELSE 'N/A' END) AS ctotcrhr_h, a.core_ctotcrpts AS ctotcrpts,a.core_ctotcrhr AS ctotcrhr,a.id AS foil_id
FROM final_semwise_marks_foil_log_temp a
WHERE a.admn_no=? AND a.course<>'MINOR' AND a.semester =? and a.id=? limit 1  " ;
        $query = $this->db->query($sql, array($admn_no, $sem,$foil_id));
       //echo $this->db->last_query(); die();
        return ($query->num_rows() > 0 ? $query->row() : false);                   
    }
	
      /* function update_final_foil($data,$foil_id,$admn_no,$sem){
           
	//function update_final_status_spl($admn_no,$branch_id, $course_id, $sem,$f_status,$cgpa, $core_cgpa){
		 $returntmsg = "";
       
        $this->db->trans_start();
        $data['final_status'] =  $f_status;            
        
		  
		
		
		   $select = $this->db->select('*')->where(
                       array('session_yr' => $this->input->post('session_year'),
                            'session' => $this->input->post('session'),
                            'dept' => ($course_id=='PREP'? 'PREP': $this->input->post('dept')),
                            'course' => $course_id,
                            'branch' => $branch_id,
                            'semester' => $sem,
                            'admn_no' => $admn_no,
                            'type' => ($this->input->post('exm_type') == 'other' ? 'O' : ($this->input->post('exm_type') == 'spl' ? 'S' : ($this->input->post('exm_type') == 'jrf' ? 'J' : ($this->input->post('exm_type') == 'espl' ? 'E' : 'R')) ) )
                        )
                )->get('final_semwise_marks_foil');
				
				 if ($select->num_rows()) {
            $row = $select->row();
			if ($row->cgpa==0 && ($f_status==''|| $f_status==null) ) {
              $data['cgpa'] =  $cgpa ;
        }
		if ($row->core_cgpa==0 && ($f_status==''|| $f_status==null) ) {
              $data['core_cgpa'] = $core_cgpa;
        }
			
             //echo $this->db->last_query();
           //  echo '<pre>';  print_r($data);  echo '</pre>'; echo  'foil_id='.$row->id 	;	//die();                          
                  $this->db->update('final_semwise_marks_foil', $data, array('id' => $row->id));                                             
				//  echo $this->db->last_query(); die();
        }
		  $this->db->trans_complete();
        if ($this->db->trans_status() != FALSE)
            $returntmsg = "success";
        else
		   $returntmsg .= "Error while Inserting/updating: " . $this->db->_error_message() . ",";
          
		 //echo $returntmsg; die();
            return $returntmsg;
				
	//}
	
       }
        function restore_foil($where){
        $this->db->trans_strict(true);
        $this->db->trans_start();                            
        $query = $this->db->get_where('final_semwise_marks_foil_log_temp', $where );                                             
             $newObj=new stdClass();    
             // removing rep_by element  from query result
           foreach ($query->row()  as $key=>$val){
                                   if($key<>'rep_by'  &&  $key<>'created')
                                      $newObj->$key= $val;                               
                     } 
          
           //print_r($newObj); die();
           $this->db->update('final_semwise_marks_foil',$newObj,$where);
           $affected[] = $this->db->affected_rows();                          
      
        $this->db->trans_complete();
        //     print_r($affected); die();
        if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)){
        echo mysql_error(); }
        else
            return 'success';  
        }
        
        
       function update_foil($data, $type, $where) {
        $this->db->trans_strict(true);
        $this->db->trans_start();                    
        
        $query = $this->db->get_where('final_semwise_marks_foil', $where );
           
                
         
        
         $query1 = $this->db->get_where('final_semwise_marks_foil_log_temp', $where );
         
         if(Commonclass::functionallyEmpty_static ($query1->row())){
                $newObj=new stdClass();    
             // adding rep_by element  to query result
           foreach ($query->row() as $key=>$val){                                   
                          $newObj->$key= $val;                                                        
                     } 
                      $newObj->rep_by= $type;
           //  print_r($newObj); die();
          $this->db->insert('final_semwise_marks_foil_log_temp',$newObj);
          //echo $this->db->last_query(); die();
         }
               
        $this->db->update('final_semwise_marks_foil',$data, $where);
        $affected[] = $this->db->affected_rows();          
        $this->db->trans_complete();
             
        if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)){
        echo mysql_error(); }
        else
            return 'success';
    }
	
 */function update_final_foil($data,$foil_id,$admn_no,$sem){
           
	//function update_final_status_spl($admn_no,$branch_id, $course_id, $sem,$f_status,$cgpa, $core_cgpa){
		 $returntmsg = "";
       
        $this->db->trans_start();
        $data['final_status'] =  $f_status;            
        
		  
		
		
		   $select = $this->db->select('*')->where(
                       array('session_yr' => $this->input->post('session_year'),
                            'session' => $this->input->post('session'),
                            'dept' => ($course_id=='PREP'? 'PREP': $this->input->post('dept')),
                            'course' => $course_id,
                            'branch' => $branch_id,
                            'semester' => $sem,
                            'admn_no' => $admn_no,
                            'type' => ($this->input->post('exm_type') == 'other' ? 'O' : ($this->input->post('exm_type') == 'spl' ? 'S' : ($this->input->post('exm_type') == 'jrf' ? 'J' : ($this->input->post('exm_type') == 'espl' ? 'E' : 'R')) ) )
                        )
                )->get('final_semwise_marks_foil');
				
				 if ($select->num_rows()) {
            $row = $select->row();
			if ($row->cgpa==0 && ($f_status==''|| $f_status==null) ) {
              $data['cgpa'] =  $cgpa ;
        }
		if ($row->core_cgpa==0 && ($f_status==''|| $f_status==null) ) {
              $data['core_cgpa'] = $core_cgpa;
        }
			
             //echo $this->db->last_query();
            // echo '<pre>';  print_r($data);  echo '</pre>'; echo  'foil_id='.$row->id 	;	die();                          
                  $this->db->update('final_semwise_marks_foil', $data, array('id' => $row->id));                                             
				//  echo $this->db->last_query(); die();
        }
		  $this->db->trans_complete();
        if ($this->db->trans_status() != FALSE)
            $returntmsg = "success";
        else
		   $returntmsg .= "Error while Inserting/updating: " . $this->db->_error_message() . ",";
          
		 //echo $returntmsg; die();
            return $returntmsg;
				
	//}
	
       }
        function restore_foil($where){
        $this->db->trans_strict(true);
        $this->db->trans_start();                            
        $query = $this->db->get_where('final_semwise_marks_foil_log_temp', $where );                                             
             $newObj=new stdClass();    
             // removing rep_by element  from query result
           foreach ($query->row()  as $key=>$val){
                                   if($key<>'rep_by'  &&  $key<>'created')
                                      $newObj->$key= $val;                               
                     } 
          
           //print_r($newObj); die();
                         
         
           		  
          if($record_src=='newsyssec'){
			 $this->db->select('*');		 
         $this->db->where(array('session_yr' =>  $newObj->session_yr,
                            'session' => $newObj->session,
                            'dept' =>  $newObj->dept,
                            'course' => $newObj->course,
                            'branch' => $newObj->branch,
                            'semester' => $newObj->semester,
                            'admn_no' => $newObj->admn_no,
                            'type' => $newObj->type
                        ));
         $this->db->from('alumni_final_semwise_marks_foil');         
	     $select2  = $this->db->get();							
	   if ($select2->num_rows()) {
            $row = $select2->row();	
			
           $master_col_restore_array=array('ctotcrpts','core_ctotcrpts','ctotcrhr','core_ctotcrhr' ,'cgpa','core_cgpa'); //  only to restore these fields			
           $newsubObj=new stdClass();  
           foreach ($newObj  as $key1=>$val1){
                    if(in_array($key1,$master_col_restore_array))       			   
                        $newsubObj->$key1=$val1;                               
          } 
		  // echo $this->db->last_query();                              
		  $this->db->update('alumni_final_semwise_marks_foil', $newsubObj, array('id' => $row->id)); 			
		  //echo $this->db->last_query();                              
          $affected[] = $this->db->affected_rows();  
	      //echo '<pre>';  print_r($newsubObj);  echo '</pre>'; echo  'foil_id='.$row->id .'old_id'.$row->old_id	;	die();                          
	   }   		   
			  
		  }else{
		   $this->db->update('final_semwise_marks_foil',$newObj,$where);
           $affected[] = $this->db->affected_rows();   
			  
		  }

		  
		   
		 $this->db->select('*');		 
         $this->db->where(array('session_yr' =>  $newObj->session_yr,
                            'session' => $newObj->session,
                            'dept' =>  $newObj->dept,
                            'course' => $newObj->course,
                            'branch' => $newObj->branch,
                            'semester' => $newObj->semester,
                            'admn_no' => $newObj->admn_no,
                            'type' => $newObj->type
                        ));
         $this->db->from('final_semwise_marks_foil_freezed');
         $this->db->order_by("actual_published_on", "desc");
         $this->db->limit('1');
	      $select2  = $this->db->get();							
	   if ($select2->num_rows()) {
            $row = $select2->row();	
			
           $master_col_restore_array=array('ctotcrpts','core_ctotcrpts','ctotcrhr','core_ctotcrhr' ,'cgpa','core_cgpa'); //  only to restore these fields			
           $newsubObj=new stdClass();  
           foreach ($newObj  as $key1=>$val1){
                    if(in_array($key1,$master_col_restore_array))       			   
                        $newsubObj->$key1=$val1;                               
          } 
		  // echo $this->db->last_query();                              
		  $this->db->update('final_semwise_marks_foil_freezed', $newsubObj, array('id' => $row->id,'old_id' => $row->old_id)); 			
		  //echo $this->db->last_query();                              
          $affected[] = $this->db->affected_rows();  
	      //echo '<pre>';  print_r($newsubObj);  echo '</pre>'; echo  'foil_id='.$row->id .'old_id'.$row->old_id	;	die();                          
	   }   		 
      
        $this->db->trans_complete();
        //     print_r($affected); die();
        if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)){
        echo mysql_error(); }
        else
            return 'success';  
        }
        
        
       function update_foil($data, $type,$record_src, $where) {
        $this->db->trans_strict(true);
        $this->db->trans_start();                    
        
        $query = $this->db->get_where(($record_src=='newsyssec' ? 'alumni_final_semwise_marks_foil' :  'final_semwise_marks_foil'), $where );                           
	//	echo $this->db->last_query();
                 
        $query1 = $this->db->get_where('final_semwise_marks_foil_log_temp', $where );
         
         if(Commonclass::functionallyEmpty_static($query1->row()) ){
            $newObj=new stdClass(); 
             // adding rep_by element  to query result
           foreach ($query->row() as $key=>$val){                                   		            
                       $newObj->$key= $val;                          
                     } 
              $newObj->rep_by= $type;
           //  print_r($newObj); die();
          $this->db->insert('final_semwise_marks_foil_log_temp',$newObj);
          //echo $this->db->last_query(); die();
         }		 	 		                
			   //print_r($data);print_r($where); die();
			   
			   
			   
        $this->db->update(    ($record_src=='newsyssec' ? 'alumni_final_semwise_marks_foil' :  'final_semwise_marks_foil'),$data, $where);
        $affected[] = $this->db->affected_rows();  
		
		//print_r($where);
		  $whereObj=new stdClass(); 
           foreach ((object)$where as $key1=>$val1){                                   
		               if($key1<>'id')
                          $whereObj->$key1= $val1;                          
                     }
               					 
         $this->db->select('*');		 
         $this->db->where((array)$whereObj);
         $this->db->from('final_semwise_marks_foil_freezed');
         $this->db->order_by("actual_published_on", "desc");
         $this->db->limit('1');
	      $select2  = $this->db->get();							
		 //   echo $this->db->last_query(); 
	   if ($select2->num_rows()) {
            $row = $select2->row();			
			 // echo $this->db->last_query();
             //echo '<pre>';  print_r($data);  echo '</pre>'; echo  'foil_id='.$row->id .'old_id'.$row->old_id	;	die();                                           
		     $this->db->update('final_semwise_marks_foil_freezed', $data, array('id' => $row->id,'old_id' => $row->old_id)); 
			//  echo $this->db->last_query(); die();
             $affected[] = $this->db->affected_rows(); 	  
	   }
    	//echo '<pre>';  print_r($data);  echo '</pre>'; echo  'foil_id='.$row->id .'old_id'.$row->old_id	;	die();                              
        $this->db->trans_complete();
	   //echo '<pre>';  print_r($data);  echo '</pre>';
             
        if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)){
        echo mysql_error(); }
        else
            return 'success';
    }


         
            
}
