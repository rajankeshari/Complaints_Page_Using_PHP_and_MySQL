<?php

/**
 * Exam data  for each student to  be populated to result table on tabulation database placed  to another server (to process result by dipanker sir))
 * Copyright (c) ISM dhanbad * 
 * @category   sync tables
 * @package    exam_attendance
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #19/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */
class Exam_sync_master_model extends CI_Model {
  private $tabulation='tabulation';
        
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getStudentBasic() {
        $table = " reg_exam_rc_form ";
        $secure_array = array($this->input->post('session'), $this->input->post('session_year'), '1', '1', $course_id, $branch_id, $dept);

        $sql = "select B.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as st_name 
                  from                                   
                   (select admn_no from   " . $table . "  where  session=?  and session_year=? and hod_status=? and  acad_status=?
                   and upper(course_id)=? and branch_id=?  )B                   
                   inner join user_details ud on ud.id=B.admn_no   and dept_id=?                                    
                   order by B.admn_no
                   ";

        $query = $this->db->query($sql, $secure_array);
        //   echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }

    function getRegularStudentSubjects() {       
    if($this->input->post('sem')!='all' && $this->input->post('sem')!='')    
    {
     $where= " and course_structure.semester=?";    
     $where2=  " and cs2.semester=? ";
    $secure_array = array('1', '1',$this->input->post('session_year'),'1', 'Y',$this->input->post('sem'),'1', '1','Y',$this->input->post('sem') );
    }else{
      $where= "";  
      $where2="";
     $secure_array = array('1', '1',$this->input->post('session_year'),'1', 'Y','1', '1','Y' );   
    }
    
    $sql = "select ud.dept_id, sa.branch_id,sa.course_id,  coalesce(( select semcode  from dip_m_semcode  where branch=sa.branch_id and course=sa.course_id and sem=sa.semester),'NA') as semcode,sa.semester , sa.enrollment_year, b_grp.*,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name)as st_name 
             from
             (
               select grp1.*, GROUP_CONCAT((SELECT subject_id FROM subjects WHERE id =  cs2.id) SEPARATOR ',') as   minor_subject
              from 
               (select  concat(right(G.session_year,2)-1,right(G.session_year,2))as ysession ,G.admn_no,G.core_subject,G.elective_subject,
                GROUP_CONCAT((SELECT subject_id FROM subjects WHERE id = course_structure.id) SEPARATOR ',') as   honors_subject

                  from (
                         select Y.session_year,Y.admn_no,Y.form_id,Y.core_subject, GROUP_CONCAT((SELECT subject_id FROM subjects WHERE id =  el.sub_id)  SEPARATOR ',') as elective_subject from (
                         select X.*, GROUP_CONCAT((SELECT subject_id FROM subjects WHERE id = X.id) SEPARATOR ',') as  core_subject from 

                          (select a.form_id,cs.id, a.admn_no,a.session_year from reg_regular_form as a inner join course_structure as cs on cs.aggr_id=a.course_aggr_id and cs.semester=a.semester
                            and a.hod_status=? and a.acad_status=? and a.session_year=? and cs.sequence REGEXP '^[0-9]+$') X
                           group by X.admn_no
                        ) Y 
                        left join reg_regular_elective_opted el on el.form_id=Y.form_id group by Y.form_id)G
                        left join hm_form hf1 on hf1.admn_no=G.admn_no and hf1.honours=? and hf1.honour_hod_status=?
                        left join stu_academic on stu_academic.admn_no=hf1.admn_no
                        left join course_structure on course_structure.aggr_id=concat('honour','_',stu_academic.branch_id,'_2013_2014') ".$where."
                         group by G.admn_no,course_structure.aggr_id
                    )grp1

                   left join ( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  
                                inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                                        and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?
                            )k 
             on k.admn_no=grp1.admn_no 
             left join course_structure cs2 on cs2.aggr_id=concat('minor','_',k.branch_id,'_2013_2014') ".$where2."
             group by grp1.admn_no,cs2.aggr_id)b_grp


left join stu_academic sa on sa.admn_no=b_grp.admn_no
left join user_details ud on ud.id=b_grp.admn_no
";

        $query = $this->db->query($sql,$secure_array);
          // echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }
    
     function getOtherStudentSubjects() {       
          $where= "and  semester like '%?%' ";
          $table=" reg_exam_rc_form ";
    
          $secure_array = array('1', '1',$this->input->post('session_year'),'1', 'Y',$this->input->post('sem'),'1', '1','Y',$this->input->post('sem') );
    
            $sql="select B.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as st_name 
                  from
                 (select course_id,branch_id from dept_course a  join course_branch b  on a.course_branch_id=b.course_branch_id  and a.dept_id=? group by b.course_id,b.branch_id)A
                  inner join
                  (select admn_no,course_id,branch_id ,semester from   ".$table."  where  session=?  and session_year=? and hod_status=? and  acad_status=?
                   and upper(course_id)=? and branch_id=?  ".$where."  )B on A.course_id=B.course_id  and A.branch_id=B.branch_id  
                    left join user_details ud on ud.id=B.admn_no                    
                    order by B.admn_no
                   ";            

        $query = $this->db->query($sql,$secure_array);
          // echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }
    
    
    function getPrepStudentSubjects(){
         $secure_array = array('prep',$this->input->post('session_year'));
         $yr=explode('-',$this->input->post('session_year'));                              
         
            $sql="select A.ysession,ud.dept_id, A.branch_id,A.course_id, 'PREP01' as semcode,A.semester,A.enrollment_year,A.admn_no, null as core_subject, null as elective_subject, null as honors_subject,null as minor_subject, concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as st_name 
                  from                                   
                   ( select  concat(right(enrollment_year,2),right(enrollment_year,2)+1 ) as ysession, admn_no,branch_id,course_id,semester,enrollment_year  from stu_academic  where auth_id=? and  enrollment_year=?) A
                   inner join user_details ud on ud.id=A.admn_no
                   ";

        $query = $this->db->query($sql,$secure_array);
          // echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }
    
      function getCommonStudentSubjects() {        
    $secure_array = array($this->input->post('session_year'));
    
    $sql = "select X.ysession, X.dept_id,
X.branch_id,
X.course_id,
(case sa.semester
                  when '1' then
                                (case X.`group`
                                                when  '1' then 'BTCMA1'
		                                when '2' then 'BTCMB1'
		                                when '0' then 'NA'
                                               else 'NA'
		 
                               end)
                    when '2' then            
                                  (case X.`group`
                                                when  '1' then 'BTCMA2'
		                                when '2' then 'BTCMB2'
		                                when '0' then 'NA'
                                               else 'NA'
		 
                               end)
               else 'NA'             
 end) as semcode,
 sa.semester,
 sa.enrollment_year,
 X.admn_no,
 group_concat((SELECT subject_id FROM subjects WHERE id = cs2.id order by id ) SEPARATOR ',') as core_subject, null as elective_subject, null as honors_subject,null as minor_subject,X.st_name  from
(select ud.dept_id,'comm' as branch_id,'comm' as course_id,concat(' ',ud.first_name,ud.middle_name,ud.last_name) as st_name , A.admn_no,A.section,A.ysession,b.`group`
                  from 
                   (select admn_no,section, concat(right(session_year,2)-1,right(session_year,2))as ysession  from  stu_section_data where session_year=?  )A                    
                     inner join user_details ud on ud.id=A.admn_no 
                     left join section_group_rel b on b.section=A.section
                      )X
                      
              inner join stu_academic sa on sa.admn_no=X.admn_no
				  left join course_structure cs2 on cs2.aggr_id='comm_comm_2012_2013' and cs2.semester=concat(sa.semester,'_',X.`group`) 
				     
				  group by  X.admn_no,cs2.aggr_id
				  order by cs2.id,X.dept_id,X.`group`, X.admn_no
";

        $query = $this->db->query($sql,$secure_array);
          // echo $this->db->last_query(); die();

        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
    }

    
    
     function  getPrimaryCourseStructure(){
         /*$sql="
                   select  '1516' as ysession,coalesce(sm.semcode,'NA') as SEM_CODE ,subjects.subject_id  as SUBJE_CODE,FLOOR(course_structure.sequence) as SUBJE_ORDE,
                      (case subjects.type 
                                           when 'Non-Contact' then 'S'
                                           when 'Sessional' then 'S' 
                                           when 'Theory' then 'R'
                                           when 'Practical' then 'P'
                                      else 'S' 
	               end) as  S_R_P,
                       (case subjects.elective  
                                            when '0' then 
                                                          (case SUBSTRING_INDEX(course_structure.aggr_id, '_', 1) when 'honour' then 'H'
                                                                                                                  when 'minor' then 'M' 
                                                                                                               else 'G'
 			                                   end)
                                            else 'E' 
	                end) as  G_E, 
	                subjects.lecture as LECTURE,subjects.tutorial as THEORY, subjects.practical as PRACTICAL,subjects.credit_hours as WEIGHTAGE,'0' as OBSOLETE,
	                concat(right(course_structure.aggr_id,2)-1,right(course_structure.aggr_id,2)) as efctfrom,course_structure.sequence, course_structure.aggr_id,subjects.name
                     from  course_structure 
                         left join dip_m_semcode sm on sm.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(course_structure.aggr_id, '_', 2),'_',-1) and sm.course=SUBSTRING_INDEX(course_structure.aggr_id, '_', 1) and sm.sem=(course_structure.semester)
                         inner join subjects on subjects.id = course_structure.id
";*/
         
         /*$sql=" select  '1516' as ysession,coalesce(sm.semcode,'NA') as SEM_CODE ,subjects.subject_id  as SUBJE_CODE,FLOOR(G.sequence) as SUBJE_ORDE,
                      (case subjects.type 
                                           when 'Non-Contact' then 'S'
                                           when 'Sessional' then 'S' 
                                           when 'Theory' then 'R'
                                           when 'Practical' then 'P'
                                      else 'S' 
	               end) as  S_R_P,
                       (case subjects.elective  
                                            when '0' then 
                                                          (case SUBSTRING_INDEX(G.aggr_id, '_', 1) when 'honour' then 'H'
                                                                                                                  when 'minor' then 'M' 
                                                                                                               else 'G'
 			                                   end)
                                            else 'E' 
	                end) as  G_E, 
	                subjects.lecture as LECTURE,subjects.tutorial as THEORY, subjects.practical as PRACTICAL,subjects.credit_hours as WEIGHTAGE,'0' as OBSOLETE,
	                concat(right(G.aggr_id,2)-1,right(G.aggr_id,2)) as efctfrom,G.sequence, G.aggr_id,subjects.name,G.id
                     from  ( select * from course_structure  where (course_structure.semester in('1','3','5','7','9')  or course_structure.semester like '1_%')   ) G
                         left join dip_m_semcode sm on 
                                sm.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(G.aggr_id, '_', 2),'_',-1) and 
                                 sm.course=SUBSTRING_INDEX(G.aggr_id, '_', 1) 
                                 and
        (case  
               when (G.semester like '1_1' ) then sm.sem=SUBSTRING_INDEX(G.aggr_id, '_', 1)                               
               when (G.semester like '1_2' ) then sm.sem=SUBSTRING_INDEX(G.aggr_id, '_', 1)                               
                ELSE sm.sem=G.semester
        end)
                                 
        inner join subjects on subjects.id = G.id";*/
         
     /*    $sql="select  '1516' as ysession,(case SUBSTRING_INDEX(G.aggr_id, '_', 1) when 'honour' then 
COALESCE((select dip_m_semcode.semcode from dip_m_semcode where dip_m_semcode.course='b.tech' and dip_m_semcode.branch=SUBSTRING_INDEX(SUBSTRING_INDEX(G.aggr_id, '_', 2),'_',-1) and dip_m_semcode.sem='5' ),'NA') else COALESCE(sm.semcode,'NA') end) as SEM_CODE

,subjects.subject_id  as SUBJE_CODE,FLOOR(G.sequence) as SUBJE_ORDE,
                      (case subjects.type 
                                           when 'Non-Contact' then 'S'
                                           when 'Sessional' then 'S' 
                                           when 'Theory' then 'R'
                                           when 'Practical' then 'P'
                                      else 'S' 
	               end) as  S_R_P,
                       (case subjects.elective  
                                            when '0' then 
                                                          (case SUBSTRING_INDEX(G.aggr_id, '_', 1) when 'honour' then 'H'
                                                                                                                  when 'minor' then 'M' 
                                                                                                               else 'G'
 			                                   end)
                                            else 'E' 
	                end) as  G_E, 
	                subjects.lecture as LECTURE,subjects.tutorial as THEORY, subjects.practical as PRACTICAL,subjects.credit_hours as WEIGHTAGE,'0' as OBSOLETE,
	                concat(right(G.aggr_id,2)-1,right(G.aggr_id,2)) as efctfrom,G.sequence, G.aggr_id,subjects.name,G.id
                     from  ( select * from course_structure  where (course_structure.semester in('1','3','5','7','9')  or course_structure.semester like '1_%')   ) G
                         left join dip_m_semcode sm on 
                                sm.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(G.aggr_id, '_', 2),'_',-1) and 
                                 sm.course=SUBSTRING_INDEX(G.aggr_id, '_', 1) 
                                 and
        (case  
               when (G.semester like '1_1' ) then sm.sem=SUBSTRING_INDEX(G.aggr_id, '_', 1)                               
               when (G.semester like '1_2' ) then sm.sem=SUBSTRING_INDEX(G.aggr_id, '_', 1)                               
                ELSE sm.sem=G.semester
        end)
                                 
        inner join subjects on subjects.id = G.id";
       
       */
        //to have All exhautive subjects with semcode whose  exam has been accomplished dusring  given session yr   (in order to supply data to tabulation sheet)  
        $secure_array = array($this->input->post('session'),$this->input->post('session_year'));
       
     /*  $sql="select '1516' as ysession,
 (case SUBSTRING_INDEX(A.aggr_id, '_', 1) when 'honour' then 
                 COALESCE((select dip_m_semcode.semcode from dip_m_semcode where dip_m_semcode.course='b.tech' and dip_m_semcode.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(A.aggr_id, '_', 2),'_',-1)
                   and dip_m_semcode.sem=A.semester),'NA') 
  else 
      COALESCE(dps.semcode,'NA') 
  end) as SEM_CODE,
   A.subject_id as SUBJE_CODE, FLOOR(cs.sequence) as SUBJE_ORDE,
  (case A.type 
                                           when 'Non-Contact' then 'S'
                                           when 'Sessional' then 'S' 
                                           when 'Theory' then 'R'
                                           when 'Practical' then 'P'
                                      else 'S' 
	               end) as  S_R_P,
                       (case A.elective  
                                            when '0' then 
                                                          (case SUBSTRING_INDEX(A.aggr_id, '_', 1) when 'honour' then 'H'
                                                                                                    when 'minor' then 'M' 
                                                                                                    else 'G'
 			                                   end)
                                            else 'E' 
	                end) as  G_E, 

A.LECTURE,A.THEORY, A.PRACTICAL,A.WEIGHTAGE,'0' as OBSOLETE,

concat(right(A.aggr_id,2)-1,right(A.aggr_id,2)) as efctfrom,  A.name
 from

(select sm.aggr_id, a.id, sm.semester,a.subject_id,a.name,a.`type` ,a.elective, a.lecture as LECTURE,a.tutorial as THEORY, a.practical as PRACTICAL,a.credit_hours as WEIGHTAGE,m.sub_map_id,m.`session`,m.session_year  from subjects a 
inner join marks_master  m on a.id=m.subject_id 
inner join subject_mapping  sm on sm.map_id=m.sub_map_id  and m.`session`=? and m.session_year=? 
group by a.subject_id
)A

left join dip_m_semcode dps on 
                                 dps.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(A.aggr_id, '_', 2),'_',-1) and 
                                 dps.course=SUBSTRING_INDEX(A.aggr_id, '_', 1)  and
                                 dps.sem=A.semester


left  join  course_structure cs on cs.aggr_id=A.aggr_id and cs.semester=A.semester and cs.id=A.id";  */
        
        
        $sql="  select A.aggr_id, A.ysession,A.SEM_CODE, 
        A.subject_id as SUBJE_CODE, FLOOR(cs.sequence) as SUBJE_ORDE,
         (case A.type when 'Non-Contact' then 'S' when 'Sessional' then 'S' when 'Theory' then 'R' when 'Practical' then 'P' else 'S' end) as S_R_P,
          (case A.elective when '0' then (case SUBSTRING_INDEX(A.aggr_id, '_', 1) when 'honour' then 'H' when 'minor' then 'M' else 'G' end) else 'E' end) as G_E,
           A.LECTURE,A.THEORY, A.PRACTICAL,A.WEIGHTAGE,'0' as OBSOLETE, A.efctfrom, 
           A.name from 
           (select 
            COALESCE(dps.semcode,'NA')as SEM_CODE,    '1516' as ysession,       
           sm.aggr_id, a.id, sm.semester,a.subject_id,a.name,a.`type` ,a.elective, a.lecture as LECTURE,a.tutorial as THEORY, a.practical as PRACTICAL,
           a.credit_hours as WEIGHTAGE,m.sub_map_id,m.`session`,m.session_year ,concat(right(sm.aggr_id,2)-1,right(sm.aggr_id,2)) as efctfrom
           from subjects a 
           inner join marks_master m on a.id=m.subject_id 
           inner join subject_mapping sm on sm.map_id=m.sub_map_id and m.`session`=? and m.session_year=? 
           left join dip_m_semcode dps on dps.branch= SUBSTRING_INDEX(SUBSTRING_INDEX(sm.aggr_id, '_', 2),'_',-1)
            and                 
        (case  
                SUBSTRING_INDEX(sm.aggr_id, '_', 1) when 'honour' then  dps.course='b.tech'
                                                    else   dps.course=SUBSTRING_INDEX(sm.aggr_id, '_', 1)                                         
        end)
            
             and  dps.sem=sm.semester             
            
            
             group by ysession,SEM_CODE,efctfrom,m.subject_id order by dps.semcode,efctfrom,m.subject_id 
             )A 
             
           
          left join course_structure cs on cs.aggr_id=A.aggr_id and cs.semester=A.semester and cs.id=A.id      
         order by A.SEM_CODE,A.efctfrom,A.subject_id ";
         
        $query = $this->db->query($sql,$secure_array);         
           // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else {
            return 0;
        }
        
     }
     
     
      function getCheckIfCourseStructureExist($efctfrom,$semcode,$subject_code){
        
         $CI = &get_instance();       
         $this->db2 = $CI->load->database($this->tabulation, TRUE);         
         $select = $this->db2->select('id')->where(array('efctfrom'=>$efctfrom,'SEM_CODE'=>$semcode,'SUBJE_CODE'=>$subject_code))->get('ecrd_mst'); 
        // echo $this->db2->last_query(); die();
        if($select!=null){
         if($select->num_rows()) 
         return  $select->row()->id;        
        else
         return false; 
        }else
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        }
        
        
        
        
             
        function updateCourseStructure($data1,$data2,$id){ 
         $CI = &get_instance();         
        $this->db2 = $CI->load->database($this->tabulation, TRUE);         
        $this->db2->trans_start();           
        $this->db2->update('ecrd_mst', $data1,array('id'=>$id));                   
        $select = $this->db2->select('id')->where(array('efctfrom'=>$data1['efctfrom'],'SEM_CODE'=>$data1['SEM_CODE'],'SUBJE_CODE'=>$data1['SUBJE_CODE']))->get('esub_mst'); 
         if($select!=null){
        if($select->num_rows()){            
             $res=$select->row()->id;            
             $this->db2->update('esub_mst', $data2,array('id'=>$res));            
         }else{
             // insert case
              $this->db2->insert('esub_mst', $data2);             
         }
        $this->db2->trans_complete();
        if ($this->db2->trans_status() != FALSE) {
            return true;
        }  else {
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        }
       } else
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
            
       }
        
       
         function insertCourseStructure($data1,$data2){ 
         $CI = &get_instance();         
        $this->db2 = $CI->load->database($this->tabulation, TRUE);         
        $this->db2->trans_start();        
        $this->db2->insert('ecrd_mst', $data1);                                                     
          $select = $this->db2->select('id')->where(array('efctfrom'=>$data1['efctfrom'],'SEM_CODE'=>$data1['SEM_CODE'],'SUBJE_CODE'=>$data1['SUBJE_CODE']))->get('esub_mst'); 
         if($select!=null){
        if($select->num_rows()){            
             $res=$select->row()->id;            
             $this->db2->update('esub_mst', $data2,array('id'=>$res));            
         }else{
             // insert case
              $this->db2->insert('esub_mst', $data2);             
         }                 
        $this->db2->trans_complete();
        if ($this->db2->trans_status() != FALSE) {
            return true;
        }  else {
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        }
       }else  throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
      }   
       
       
     function getCheckIfStudExist($ysession,$semcode,$admn_no){
        
         $CI = &get_instance();
       
         $this->db2 = $CI->load->database($this->tabulation, TRUE);         
         $select = $this->db2->select('id')->where(array('YSESSION'=>$ysession,'SEM_CODE'=>$semcode,'ADM_NO'=>$admn_no))->get('ugms0304'); 
          if($select!=null){
         if($select->num_rows()) 
         return  $select->row()->id;        
        else
         return false; 
        }else
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        }
        
        
         function updateStu($data1,$id){ 
         $CI = &get_instance();         
        $this->db2 = $CI->load->database($this->tabulation, TRUE);         
        $this->db2->trans_start();           
        $this->db2->update('ugms0304', $data1,array('id'=>$id));                           
        $this->db2->trans_complete();
        if ($this->db2->trans_status() != FALSE) {
            return true;
        }  else {
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        } 
            
       }
        
        function insertStu($data1){ 
         $CI = &get_instance();         
        $this->db2 = $CI->load->database($this->tabulation, TRUE);         
        $this->db2->trans_start();           
        $this->db2->insert('ugms0304',$data1);                           
        $this->db2->trans_complete();
        if ($this->db2->trans_status() != FALSE) {
            return true;
        }  else {
          throw new Exception(($this->db2->_error_message()==null?"Internal Error Occured":$this->db2->_error_message()));
        } 
            
       }
        
     
}
?>
