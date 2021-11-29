<?php
if(!defined('BASEPATH')) exit('no direct access allowed');
class Main_modal extends CI_Model{
    

    
    function getRegStufromMIS($sem,$c,$b,$sess){
        $sql = "select admn_no from reg_regular_form as a where a.course_id='$c' and a.branch_id='$b' and a.semester='$sem' and a.session_year='$sess' and a.acad_status='1' and a.hod_status='1' group by a.admn_no";
       
        $q=$this->db->query($sql);
        // echo $this->db->last_query();  
        if($q->num_rows() > 0)
            return $q->result();
        return array();
    }
    
    function getRegStuDip($id,$sem){
        $sql ="select a.adm_no from tabulation1 as a where a.adm_no like '$id%' and a.sem_code like '$sem%' group by a.adm_no";
       
        $q=$this->db->query($sql);
        
       //  echo $this->db->last_query();  
        if($q->num_rows() > 0)
            return $q->result();
        return array();
    }
    
    function getSemCodefordip($s,$d,$c,$b){
        $sql="select * from dip_m_semcode as a where a.deptmis='$d' and a.course='$c' and a.branch='$b' limit 1";
          $q=$this->db->query($sql);
          //echo $this->db->last_query();
          $dd = $q->row();
         // echo  substr($dd->semcode , 0,-1); die();
        return $this->getRegStuDip($s ,substr($dd->semcode , 0,-1));
        
    } 
        
    function getOGPAformDipSir($id){
        $sql = "SELECT (a.adm_no),max(right(a.sem_code,1)) as sem,a.sem_code,a.gpa,a.crpts, a.totcrpts,a.totcrhr,a.ogpa,a.ctotcrhr,a.ctotcrpts,a.grade,a.passfail  FROM tabulation1 AS a 
WHERE a.adm_no='$id' and a.examtype='R' group by a.sem_code order by sem desc;";
       $q=$this->db->query($sql);
      // echo $this->db->last_query();
         if($q->num_rows() > 0){
              $f=$q->result(); 
           foreach($f as $u){
               if($u->passfail== 'F' || $u->grade == 'F')
              return false; 
           }
             return $f[0];
         }
            
        return array();
    }
    
    function getMisdata($id){
        $sql="  select grade_points.points , sum( (grp.credit_hours*grade_points.points))  as totcrdtpt ,sum(grp.credit_hours) as  tocrhr ,grp.*from
   (select C.*,e.dept_id,e.aggr_id,e.course_id,e.branch_id,e.semester,e.`group` from (select B.*,d.sequence
 as seq from
   (select A.*,c.id as sub_id,c.name,c.credit_hours,c.`type`,c.subject_id as sub_code , concat(c.lecture
,'-',c.tutorial,'-',c.practical) as LTP from
    (select a.stu_status,a.theory,a.sessional,a.practical,a.total,a.grade,b.subject_id,b.`session`,b
.session_year,b.sub_map_id from marks_subject_description as a
     inner join marks_master as b on a.marks_master_id=b.id and b.`type`='R'  and b.`status`='Y' where a.admn_no='$id'   ) A 
     inner join subjects as c on A.subject_id=c.id ) B inner join course_structure as d on B.subject_id
=d.id ) C 
     inner join subject_mapping as e on C.sub_map_id = e.map_id where  e.course_id <> 'honour' and e.course_id <> 'minor'
     group by C.sub_code order by e.semester,C.seq asc )grp
     left join grade_points on grade_points.grade=grp.grade  order by grp.semester,grp.seq asc;";
        
        $q=$this->db->query($sql);
        //echo $this->db->last_query()."<br><br>";
        
         if($q->num_rows() > 0)
            return $q->row();
        return array();
    }
    
    function getName($id){
        
        $sql= "select  concat_ws(' ',a.first_name,a.middle_name,a.last_name) as name from user_details as a where id='$id'";
        $q=$this->db->query($sql);
         if($q->num_rows() > 0)
            return $q->row();
        return array();
    }
    
}

?>