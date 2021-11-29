<?php

class Exam_moderation_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_failList_byID($id) {
        $myquery = "select a.admn_no,a.sessional,a.theory,a.practical,a.total,a.grade,b.subject_id,
b.session, b.session_year,c.`session`,c.session_year,c.dept_id,
c.course_id,c.branch_id, c.semester,c.`group`,c.section from 
marks_subject_description a inner join marks_master b 
on a.marks_master_id=b.id inner join subject_mapping c 
on b.sub_map_id=c.map_id 
where b.`status`='Y' 
and (a.total>=18 and a.total<21)
and c.dept_id='".$id."' group by a.admn_no  ";
      
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_sub_list($admn_no,$sem,$syear,$sess,$et)
    {
        //or  a.sub_type='U' has been added in winter do not know why is is U inspite of T
        $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id,b.`type` as ex_type from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and (a.sub_type='T' or  a.sub_type='U' or  a.sub_type='P')
    and b.`session`='".$sess."' and b.session_year='".$syear."' and b.`type`='".$et."' and b.status='Y') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id where e.semester='".$sem."'  and e.course_id!='minor' group by C.sid order by e.semester,C.seq+0 asc  ";
      
        $query = $this->db->query($myquery);
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    //-------------------------Student Moderated or not-------------------
    
     function get_moderation_status($admn_no,$sem,$syear,$sess,$et)
    {
        
     /*   $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description_backup as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and a.sub_type='T'
    and b.`session`='".$sess."' and b.session_year='".$syear."') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id where e.semester='".$sem."'  and e.course_id!='minor' group by C.sid order by e.semester,C.seq+0 asc  ";*/
         $myquery="select a.* from final_semwise_marks_foil_desc_backup a
inner join final_semwise_marks_foil_backup b on a.foil_id=b.id
where a.admn_no=? and b.semester=? and b.session_yr=? and b.`session`=? and b.`type`=? and remark='Mod' group by mis_sub_id";
      
        $query = $this->db->query($myquery,array($admn_no,$sem,$syear,$sess,$et));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    //-------------------------------------GPA--------------------------------------------
    
    function get_gpa($admn_no,$et,$sess,$syear,$sem)
    {
       /* $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and b.`type`='".$et."'  and b.`session`='".$sess."' and b.session_year='".$syear."') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id where e.semester='".$sem."'  group by C.sid order by e.semester,C.seq+0 asc  ";*/
        $myquery="SELECT C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group`
FROM 
(
SELECT B.*,d.sequence AS seq
FROM
(
SELECT A.*,c.subject_id AS sid,c.name,c.credit_hours,c.`type` AS stype
FROM 
(
SELECT a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b
.`session`,b.session_year,b.sub_map_id,b.`type`
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE a.admn_no=? AND b.`type`=? AND b.`session`=? AND b.session_year=? and b.`status`='Y') A
INNER JOIN subjects AS c ON A.subject_id=c.id) B
INNER JOIN course_structure AS d ON B.subject_id=d.id) C
INNER JOIN subject_mapping AS e ON C.sub_map_id = e.map_id
WHERE e.semester=?
GROUP BY C.sid
ORDER BY e.semester,C.seq+0 ASC";
      
        $query = $this->db->query($myquery,array($admn_no,$et,$sess,$syear,$sem));
//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    
    
    //----------------------------------------------------------------------------------------------
    
    
    
    
    
    public function get_course_agg_id($a_id, $session, $session_year) {
        $sql = " select form_id,admn_no,course_aggr_id,semester,course_id from reg_regular_form 
where session_year='" . $session_year . "' and session='" . $session . "' and hod_status='1'
and acad_status='1' and admn_no='" . $a_id . "' ";

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function get_marks_description_byID($id)
    {
        $sql = " select * from marks_subject_description where id=".$id;

        $query = $this->db->query($sql);
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function insert_old_marks($data)
	{
		if($this->db->insert('marks_subject_description_backup',$data))
			return TRUE;
		else
			return FALSE;
	}
        function update_marks_subject_description($sessional,$theory,$total,$grade,$id)
        {
        
        $sql = "update marks_subject_description set sessional=".$sessional.",theory=".$theory.",total=".$total.",grade='".$grade."' where id=".$id;
        $query = $this->db->query($sql);
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
        
        
        
        
        function update_marks_subject_description_ufm($sessional1, $theory1, $tot1, $gd1, $status1, $id)
        {
         $sql = "update marks_subject_description set sessional=".$sessional1.",theory=".$theory1.",total=".$tot1.",grade='".$gd1."', stu_status='".$status1."' where id=".$id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }   
        }
        
        function get_highest_marks($id)
        {
                $sql = "select * from marks_master where id=".$id;
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
        }
        function get_grade_relative($h,$t)
        {
            if($h<$t){
            $sql = "select c.grade from  relative_grading_table  as c where  c.highest_marks='".$h."' and '".$h."'  between c.min and c.max";
            }
            else{
            $sql = "select c.grade from  relative_grading_table  as c where  c.highest_marks='".$h."' and '".$t."'  between c.min and c.max";
            }
            $query = $this->db->query($sql);
             //echo $this->db->last_query();die();
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
        }
        function get_grade_absolute($tot)
        {
            $sql = "select c.grade from grade_points as c where  '".$tot."' between c.min and c.max ";
            $query = $this->db->query($sql);
          //   echo $this->db->last_query();die();
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
        }
        
        function get_published_result($sy,$sess,$did,$cid,$bid,$sem,$etype,$sec)
        {
            if($did=="comm"){
			$where_add= " and semester=? and  section= ? ";
                       $arr=array($sy,$sess,$did,$cid,$bid,$sem,$sec,$etype);
		}
                
		else{
			if($cid=='jrf'){
                            $where_add="";
                            $arr=array($sy,$sess,$did,$cid,$bid,$etype);
                        }else{
                        $where_add=" and semester=? ";
                         $arr=array($sy,$sess,$did,$cid,$bid,$sem,$etype);
                        }
		}
                
                
            $sql = "select * from result_declaration_log 
                    where s_year=? and session=? and dept_id=?
                    and course_id=? and branch_id=?   and status=1  ".$where_add." and exam_type=?";
            
            
            $query = $this->db->query($sql,$arr);
            
           //echo $this->db->last_query();die();
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
        }
        
//        function ModOtherDeclearSheet($data,$con){
//                //print_r($data);die();
//            if($this->db->update('final_semwise_marks_foil',$data['t1'],$con['t1'])){
//               
//               if($this->db->update('final_semwise_marks_foil_desc',$data['t2'],$con['t2'])){
//                    return true;
//               } 
//            }
//            
//                   
//            return false;
//            
//        }
        
        function getoldOgPA($id,$sem){
           $q= $this->db->query("select * from tabulation1  a where a.adm_no=? and right(a.sem_code,1)=?  order by a.examtype desc limit 1 ",array($id,$sem));
          // echo $this->db->last_query();die();
           return $q->row(); 
        }
        
    
        function get_gpa_others($admn_no,$sem,$syear,$sess,$et,$cid)
    {
      $myquery = " select * from final_semwise_marks_foil where admn_no=? and semester=? and session_yr=? and `session`=? and type=? AND course=?";
      
        $query = $this->db->query($myquery,array($admn_no,$sem,$syear,$sess,$et,$cid));
       // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    //----------------------------------JRF-----------------------------------------------------------
    
    function get_gpa_others_jrf($admn_no, $syear, $sess, $et,$did,$cid,$bid)
    {
      $myquery = " select * from final_semwise_marks_foil where admn_no=?  and session_yr=? and `session`=? and type=? and dept=? AND course=? and branch=?";
      
        $query = $this->db->query($myquery,array($admn_no, $syear, $sess, $et,$did,$cid,$bid));
       // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    //----------------------------------------------------------------------------------------------------
    
       function get_sub_list_from_final($adm_no,$sem,$sy,$sess)
    {
        $sql="Select A.*,s.name from (SELECT *
FROM final_semwise_marks_foil_desc
WHERE foil_id=(
SELECT id
FROM final_semwise_marks_foil
WHERE admn_no='".$adm_no."' AND semester=".$sem." and session_yr='".$sy."' and session='".$sess."')
)A
inner join subjects s on s.subject_id=A.sub_code
group by A.sub_code

";
        $query = $this->db->query($sql);
          
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    //------------------------------------------------Modification--------------------------------------------
    
    function get_sub_list_for_marks_modification($admn_no,$sem,$syear,$sess,$et)
    {
        $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id,b.`type` as ex_type from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no=?  and b.`session`=? and b.session_year=? and b.`type`=? and b.`status`='Y') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id where e.semester=?  and e.course_id!='minor' group by C.sid order by e.semester,C.seq+0 asc  ";
      
        $query = $this->db->query($myquery,array($admn_no,$sess,$syear,$et,$sem));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function check_partial($admnno,$id)
    {
        $myquery = " SELECT *
FROM result_declaration_log_partial_details
WHERE admn_no=? AND res_dec_id=? AND STATUS='P' ";
      
        $query = $this->db->query($myquery,array($id,$admnno));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
    function update_partial($id)
    {
        $sql = "update result_declaration_log_partial_details set status='M' where id=".$id;
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    //--------------------------------------------------------------------------------------------------------------------------

    
    
    
    
    //---------------------------------GPA for special-------------------------------------------------------
    
    function get_gpa_special($admn_no,$et,$sess,$syear,$sem)
    {

                $myquery="select a.*,b.id,b.session_yr,b.`session`,b.semester,b.`type` from final_semwise_marks_foil_desc a
inner join final_semwise_marks_foil b on b.admn_no=a.admn_no
where b.admn_no=? and b.`type`=?
and b.`session`=?  and b.session_yr=? and b.semester=?";
      
        $query = $this->db->query($myquery,array($admn_no,$et,$sess,$syear,$sem));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_credit_hours($hadmn_no, $fid,$subid)
    {
        $myquery="select cr_hr from final_semwise_marks_foil_desc where admn_no=? and foil_id=? and mis_sub_id=?";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid,$subid));
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
    function update_foil_description($data,$con)
    {
         if($this->db->update('final_semwise_marks_foil_desc',$data['t2'],$con['t2']))
         {
                    return true;
         } 
            return false;
            
    }
    function update_foil_table($data,$con)
    {
            
            if($this->db->update('final_semwise_marks_foil',$data['t1'],$con['t1']))
            {
                    return true;
            }
            return false;
    }
    
    function get_gpa_after_update_foil_description($hadmn_no, $fid)
    {
        $myquery="select sum(cr_hr)as chours,sum(cr_pts)as cpoints,sum(cr_pts)/sum(cr_hr) as gpa from final_semwise_marks_foil_desc where admn_no=? and foil_id=?";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
   
   /* function get_gpa_after_update_foil_description_core($hadmn_no, $fid)
    {
        $myquery="SELECT SUM(cr_hr) AS chours, SUM(cr_pts) AS cpoints, SUM(cr_pts)/ SUM(cr_hr) AS gpa FROM final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
inner join course_structure c on c.id=b.id
WHERE admn_no=? AND foil_id=? and c.aggr_id not like '%honour_%'";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }*/
	
	function get_gpa_after_update_foil_description_core($hadmn_no, $fid) // need to be compare with subject code too
    {
        $myquery="SELECT SUM(cr_hr) AS chours, SUM(cr_pts) AS cpoints, SUM(cr_pts)/ SUM(cr_hr) AS gpa FROM final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
inner join course_structure c on c.id=b.id
WHERE admn_no=? AND foil_id=? and c.aggr_id not like '%honour_%'";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_gpa_after_update_foil_description_jrf($hadmn_no, $fid) // need to be compare with subject code too
    {
        $myquery="SELECT SUM(cr_hr) AS chours, SUM(cr_pts) AS cpoints, SUM(cr_pts)/ SUM(cr_hr) AS gpa FROM final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
WHERE admn_no=? AND foil_id=? ";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
	
    
    function get_passfail_foil($hadmn_no, $fid)
    {
        $myquery="SELECT COUNT(*) as pfail FROM final_semwise_marks_foil_desc  WHERE grade='F' and admn_no=? and foil_id=?";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    //----------------------------------------------------------------------------------------------------
    
    function get_passfail_foil_core($hadmn_no, $fid)
    {
        $myquery="SELECT COUNT(*) as pfail FROM final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
inner join course_structure c on c.id=b.id
WHERE admn_no=? AND foil_id=? and c.aggr_id not like '%honour_%' and grade='F'";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
       function get_passfail_foil_honours($hadmn_no, $fid)
    {
        $myquery="SELECT COUNT(*) as pfail FROM final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
inner join course_structure c on c.id=b.id
WHERE admn_no=? AND foil_id=? and c.aggr_id like '%honour_%' and grade='F'";
      
        $query = $this->db->query($myquery,array($hadmn_no, $fid));

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function get_honour_status($admno,$foilid){
            
            $sql = "select * from final_semwise_marks_foil where admn_no=? and id=?";
            
            $query = $this->db->query($sql,array($admno,$foilid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
            
        }
    
    
    //--------------------------------------------------------------------------------------------------------
    
    function check_jrf($id)
    {
        $myquery="select auth_id from stu_academic where admn_no=?";
      
        $query = $this->db->query($myquery,array($id));

        if ($query->num_rows() > 0) {
            return $query->row()->auth_id;
        } else {
            return FALSE;
        }
        
    }
    
    
    
    
    //-----------------------------------------------JRF-----------------------------------------------
    
    
        function get_sub_list_jrf($admn_no,$sess,$syear,$et)
    {
        
        $myquery = " SELECT C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group`
FROM (
SELECT B.*,d.sequence AS seq
FROM (
SELECT A.*,c.subject_id AS sid,c.name,c.credit_hours,c.`type`
FROM (
SELECT a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,
b.session_year,b.sub_map_id,b.`type` AS ex_type
FROM marks_subject_description AS a
INNER JOIN marks_master AS b ON a.marks_master_id=b.id
WHERE a.admn_no=? AND (a.sub_type='T' OR a.sub_type='U' OR a.sub_type='P') AND b.`session`=?
AND b.session_year=? AND b.`type`=?) A
INNER JOIN subjects AS c ON A.subject_id=c.id) B
INNER JOIN course_structure AS d ON B.subject_id=d.id) C
INNER JOIN subject_mapping AS e ON C.sub_map_id = e.map_id

GROUP BY C.sid
ORDER BY C.seq+0 ASC  ";
      
        $query = $this->db->query($myquery,array($admn_no,$sess,$syear,$et));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_sub_list_for_marks_modification_jrf($admn_no,$syear,$sess,$et)
    {
        $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id,b.`type` as ex_type from marks_subject_description as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no=?  and b.`session`=? and b.session_year=? and b.`type`=? and b.`status`='Y') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id   group by C.sid order by C.seq+0 asc  ";
      
        $query = $this->db->query($myquery,array($admn_no,$sess,$syear,$et,$sem));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_moderation_status_jrf($admn_no,$syear,$sess)
    {
        $myquery = " select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from 
(select B.*,d.sequence  as seq from
(select A.*,c.subject_id as sid,c.name,c.credit_hours,c.`type` from 
(select a.id, a.sessional,a.theory,a.practical,a.total,a.grade,a.stu_status,a.sub_type,b.subject_id,b.`session`,b.session_year,b.sub_map_id from marks_subject_description_backup as a
inner join marks_master as b on a.marks_master_id=b.id  where a.admn_no='".$admn_no."' and a.sub_type='T'
    and b.`session`='".$sess."' and b.session_year='".$syear."') A
inner join subjects as c on A.subject_id=c.id ) B
inner join course_structure as d on B.subject_id=d.id ) C
inner join subject_mapping as e on C.sub_map_id = e.map_id  group by C.sid order by C.seq+0 asc  ";
      
        $query = $this->db->query($myquery);
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_finaldata($sy,$sess,$admn_no,$sem,$etype){
        
            $myquery = " select a.* from final_semwise_marks_foil a where a.session_yr=? and a.`session`=? and a.admn_no=? and a.semester=? and a.type=? and a.course<>'MINOR'  ";
      
        $query = $this->db->query($myquery,array($sy,$sess,$admn_no,$sem,$etype));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
        
        
    }
    function get_finaldata_minor($sy,$sess,$admn_no,$sem,$etype){
        
            $myquery = " select a.* from final_semwise_marks_foil a where a.session_yr=? and a.`session`=? and a.admn_no=? and a.semester=? and a.type=? and a.course='MINOR'  ";
      
        $query = $this->db->query($myquery,array($sy,$sess,$admn_no,$sem,$etype));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
        
        
    }
    function get_course_aggr_id($hsyear,$hsess,$hadmn_no){
        $myquery = " select * from reg_regular_form where  session_year=? and session=?
and admn_no=? and hod_status='1' and acad_status='1'  ";
        $query = $this->db->query($myquery,array($hsyear,$hsess,$hadmn_no));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_subject_aggr_id($id){
        $myquery = " select * from course_structure where id=?  ";
        $query = $this->db->query($myquery,array($id));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
}
