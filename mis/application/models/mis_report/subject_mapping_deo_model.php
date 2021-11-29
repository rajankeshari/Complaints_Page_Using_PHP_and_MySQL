<?php

class Subject_mapping_deo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_subject_mapping_all($syear,$sess,$dept_id)
    {
        if($sess=='Monsoon') 
            $exp="a.semester%2!=0";
        else if($sess=='Winter')
            $exp="a.semester%2=0";
        else if($sess=='Summer')
            $exp="";
        
        $sql = "select x.*,y.name as cname,z.name as bname from (
(select distinct a.semester,a.course_id,a.branch_id,a.course_aggr_id as aggr_id,b.dept_id from reg_regular_form a 
inner join dept_course b on a.course_aggr_id=b.aggr_id
where a.session_year=? and a.`session`=? and a.hod_status='1'
and b.dept_id like '%".$dept_id."%')
union
(select distinct a.semester,'Honour' as course_id,'".$dept_id."' as branch_id,a.aggr_id,b.dept_id from course_structure a 
inner join dept_course b on a.aggr_id=b.aggr_id
where b.dept_id='".$dept_id."' and a.aggr_id like '%honour%' and ".$exp.")
union
(select distinct a.semester,'Minor' as course_id,'".$dept_id."' as branch_id,a.aggr_id,b.dept_id from course_structure a 
inner join dept_course b on a.aggr_id=b.aggr_id
where b.dept_id='".$dept_id."' and a.aggr_id like '%minor%'  and ".$exp."))x
    inner join cs_courses y on y.id=x.course_id
inner join cs_branches z on z.id=x.branch_id
order by x.course_id,x.branch_id,x.semester";

        $query = $this->db->query($sql,array($syear,$sess));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_mapping_status($syear,$sess,$dept,$course_id,$branch_id,$aggr_id,$semester){
        $sql = "select a.* from subject_mapping a where a.session_year=? and a.`session`=?
and  a.dept_id=? and a.course_id=? and a.branch_id=? and a.aggr_id=?
and a.semester=?";

        $query = $this->db->query($sql,array($syear,$sess,$dept,$course_id,$branch_id,$aggr_id,$semester));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
            return false;
        }
        
    }
    
    function find_number_of_students($syear,$sess,$course_id,$branch_id,$semester,$aggr_id){
        
        if($course_id=='Honour'){
            $sql="SELECT a.* FROM reg_regular_form a inner join hm_form b on a.admn_no=b.admn_no
WHERE a.session_year=? AND a.`session`=?  
AND  a.semester=? AND b.honours_agg_id=? AND a.hod_status='1' AND a.acad_status='1'
and b.honours='1' and b.honour_hod_status='Y'";
            
            $query = $this->db->query($sql,array($syear,$sess,$semester,$aggr_id));
            
        }
        else if($course_id=='Minor'){
            $sql="SELECT a.*
FROM reg_regular_form a
inner join hm_form b on a.admn_no=b.admn_no
inner join hm_minor_details c on c.form_id=b.form_id
WHERE a.session_year=? AND a.`session`=?
AND  a.semester=? AND c.minor_agg_id=? AND a.hod_status='1' AND a.acad_status='1'
and b.minor='1' and b.minor_hod_status='Y' and c.offered='1'";
            
            $query = $this->db->query($sql,array($syear,$sess,$semester,$aggr_id));
        }else{
        $sql="select a.* from reg_regular_form a where a.session_year=?  and a.`session`=?
and a.course_id=? and a.branch_id=? and a.semester=? and a.course_aggr_id=? and a.hod_status='1' and a.acad_status='1'";
        $query = $this->db->query($sql,array($syear,$sess,$course_id,$branch_id,$semester,$aggr_id));
        }

       //echo $this->db->last_query(); 
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_mapping_description($map_id){
        $sql = "select a.map_id,a.coordinator,a.sub_id,b.subject_id,b.name,concat_ws(' ',c.first_name,c.middle_name,c.last_name)as faculty,d.office_no,e.mobile_no from subject_mapping_des a 
left join subjects b on a.sub_id=b.id
left join user_details c on c.id=a.emp_no
left join emp_basic_details d on d.emp_no=a.emp_no
left join user_other_details e on e.id=a.emp_no
where a.map_id=?";

        $query = $this->db->query($sql,array($map_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
            return false;
        }
        
    }
    
    

}

?>