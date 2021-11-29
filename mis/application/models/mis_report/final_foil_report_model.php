<?php

class Final_foil_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_regular_student($syear, $sess, $etype, $did, $cid, $bid, $sem)
    {
              
        $sql="select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
CASE (b.sex) WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' END as 'sex',b.category,b.email,f.mobile_no,c.name as dname,d.name as cname,e.name as bname,
a.semester,a.session_yr,a.`session`,/*a.gpa,a.core_gpa,a.cgpa,a.core_cgpa,*/a.`status`,a.core_status,a.hstatus,
a.`type`,a.dept,a.course,a.branch
from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
left join departments c on c.id=a.dept
left join cs_courses d on d.id=a.course
left join cs_branches e on e.id=a.branch
inner join user_other_details f on f.id=b.id
where 1=1";

        if($syear){
            $sql.=" and a.session_yr='".$syear."'";
        }
        if($sess){
            $sql.=" and a.session='".$sess."'";
        }
        if($etype){
            if($etype=='Regular'){$et='R';}
            if($etype=='Other'){$et='O';}
            if($etype=='Special'){$et='S';}
            $sql.=" and a.type='".$et."'";
        }
        if($did!='all'){
            $sql.=" and a.dept='".$did."'";
        }
        if($cid!='all'){
            $sql.=" and a.course='".$cid."'";
        }
        if($bid!='all'){
            $sql.=" and a.branch='".$bid."'";
        }
        if($sem!=''){
            $sql.=" and a.semester='".$sem."'";
        }
        
        $sql.=" order by a.dept,a.course,a.branch,a.semester,a.admn_no ";
        
       $query = $this->db->query($sql);

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    
    //==================================JRF JRF Special==============================================
    function get_regular_jrf($syear, $sess,$etype,$did)
    {
              
        $sql="select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
CASE (b.sex) WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' END as 'sex',b.category,b.email,f.mobile_no,c.name as dname,d.name as cname,e.name as bname,
a.semester,a.session_yr,a.`session`,/*a.gpa,a.core_gpa,a.cgpa,a.core_cgpa,*/a.`status`,a.core_status,a.hstatus,
a.`type`,a.dept,a.course,a.branch
from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
left join departments c on c.id=a.dept
left join cs_courses d on d.id=a.course
left join cs_branches e on e.id=a.branch
inner join user_other_details f on f.id=b.id
where 1=1";

        if($syear){
            $sql.=" and a.session_yr='".$syear."'";
        }
        if($sess){
            $sql.=" and a.session='".$sess."'";
        }
        if($etype){
            if($etype=='JRF'){$et='J';}
            if($etype=='JRF_SPL'){$et='J';} //should be change
           $sql.=" and a.type='".$et."'";
        }
        if($did!='all'){
            $sql.=" and a.dept='".$did."'";
        }
       
        
        $sql.=" order by a.dept,a.course,a.branch,a.semester,a.admn_no ";
        
       $query = $this->db->query($sql);

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    //========================================PREP===============
    function get_regular_prep($syear, $sess,$etype)
    {
              
        $sql="select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
CASE (b.sex) WHEN 'm' THEN 'Male' WHEN 'f' THEN 'Female' END as 'sex',b.category,b.email,f.mobile_no,c.name as dname,d.name as cname,e.name as bname,
a.semester,a.session_yr,a.`session`,/*a.gpa,a.core_gpa,a.cgpa,a.core_cgpa,*/a.`status`,a.core_status,a.hstatus,
a.`type`,a.dept,a.course,a.branch
from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
left join departments c on c.id=a.dept
left join cs_courses d on d.id=a.course
left join cs_branches e on e.id=a.branch
inner join user_other_details f on f.id=b.id
where 1=1";

        if($syear){
            $sql.=" and a.session_yr='".$syear."'";
        }
        if($sess){
            $sql.=" and a.session='".$sess."'";
        }
        if($etype){
            if($etype=='PREP'){$et='R';}
            
           $sql.=" and a.type='".$et."'";
           $sql.=" and a.dept='PREP'";
        }
      
       
        
        $sql.=" order by a.dept,a.course,a.branch,a.semester,a.admn_no ";
        
       $query = $this->db->query($sql);

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }


}

?>