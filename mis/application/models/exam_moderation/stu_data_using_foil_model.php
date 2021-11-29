<?php

class Stu_data_using_foil_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

  function stu_details_core($admn_no,$syear,$sess,$stusem,$type){
    if($type=='regular'){$type='R';}if($type=='other'){$type='O';}if($type=='special'){$type='S';}if($type=='JRF'||$type=='jrf'){$type='J';}
        $sql = "select b.id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)stu_name,
c.name as dept_nm,d.name as course_nm,e.name as branch_nm,a.semester,a.session_yr,a.`session`,
ssd.section AS st_section,a.dept as did,a.course as cid,a.branch as bid
 from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=a.dept
inner join cs_courses d on d.id=a.course
inner join cs_branches e on e.id=a.branch
LEFT JOIN stu_section_data ssd ON ssd.admn_no=a.admn_no and ssd.session_year=a.session_yr
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.type=? and a.course<>'MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$type));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
 function stu_details_core_comm($admn_no,$syear,$sess,$stusem,$type){
    if($type=='regular'){$type='R';}if($type=='other'){$type='O';}if($type=='special'){$type='S';}if($type=='JRF'||$type=='jrf'){$type='J';}
        $sql = "SELECT b.id, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)stu_name,
a.dept AS dept_nm,a.course AS course_nm,a.branch AS branch_nm,a.semester,
a.session_yr,a.`session`,ssd.section AS st_section,a.dept AS did,a.course AS cid,a.branch AS bid
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON a.admn_no=b.id
LEFT JOIN stu_section_data ssd ON ssd.admn_no=a.admn_no AND ssd.session_year=a.session_yr
where a.admn_no=?  and a.session_yr=? and a.`session`=? and a.semester=? and a.`type`=? and a.course<>'MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$type));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    function stu_details_minor($admn_no,$syear,$sess,$stusem,$type){
         if($type=='regular'){$type='R';}if($type=='other'){$type='O';}if($type=='special'){$type='S';}if($type=='JRF'){$type='J';}
        $sql = "select b.id,concat_ws(' ',b.first_name,b.middle_name,b.last_name)stu_name,
c.name as dept_nm,d.name as course_nm,e.name as branch_nm,a.semester,a.session_yr,a.`session`,
ssd.section AS st_section,a.dept as did,a.course as cid,a.branch as bid
 from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=a.dept
inner join cs_courses d on d.id=a.course
inner join cs_branches e on e.id=a.branch
LEFT JOIN stu_section_data ssd ON ssd.admn_no=a.admn_no and ssd.session_year=a.session_yr
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.type=? and a.course='MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$type));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_gpa_core($admn_no,$syear,$sess,$stusem,$et){
        $sql = "select a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_gpa,a.core_status from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`=? and a.course<>'MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_gpa_honours($admn_no,$syear,$sess,$stusem,$et){
        $sql = "
select a.tot_cr_hr,a.tot_cr_pts,a.gpa,a.`status` from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`=? and a.course<>'MINOR'";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    //================================CGPA Starts=========================================
    function get_cgpa_core($admn_no,$syear,$sess,$stusem,$et){
        $sql = "select a.core_ctotcrhr,a.core_ctotcrpts,a.core_cgpa,a.core_status from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`=? and a.course<>'MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_cgpa_honours($admn_no,$syear,$sess,$stusem,$et){
        $sql = "
select a.ctotcrhr,a.ctotcrpts,a.cgpa,a.`status` from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`=? and a.course<>'MINOR'";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    
    
    
    //=============================CGPA Ends==================================================
    
        function check_honours_yes_no($admn_no,$syear,$sess,$stusem,$et){
        $sql = "select a.hstatus from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`=? and a.course<>'MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_gpa_minor($admn_no,$syear,$sess,$stusem,$et){
        $sql = "select a.core_tot_cr_hr,a.core_tot_cr_pts,a.core_gpa,a.core_status from final_semwise_marks_foil a
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.semester=? and a.`type`='R' and a.course='MINOR' ";
        $query = $this->db->query($sql, array($admn_no,$syear,$sess,$stusem,$et));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function get_sub_list_core($admn_no, $sem, $syear, $sess, $et){
     /*   $sql = "Select A.*,s.name,cs.aggr_id,
CASE WHEN cs.aggr_id like '%honour%' THEN 'Honour'
     WHEN cs.aggr_id not like '%honour%' THEN 'Core' 
     END as 'paper_type'
from (SELECT *
FROM final_semwise_marks_foil_desc
WHERE foil_id=(
SELECT id
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? and session_yr=? and session=? and course<>'Minor' and type=?)
)A
inner join subjects s on s.id=A.mis_sub_id
inner join course_structure cs on cs.id=s.id and semester=?
group by A.sub_code
order by paper_type";*/
        
       $sql="SELECT A.*,s.name,cs.aggr_id, CASE WHEN cs.aggr_id LIKE '%honour%' THEN 'Honour' WHEN cs.aggr_id NOT LIKE '%honour%' THEN 'Core' END AS 'paper_type'
FROM (select a.*,b.session_yr,b.`session`,b.semester from final_semwise_marks_foil_desc a
inner join final_semwise_marks_foil b on a.foil_id=b.id
where b.admn_no=? and b.semester=? AND b.session_yr=? AND b.SESSION=? AND b.course<>'Minor' AND b.TYPE=?)A
INNER JOIN subjects s ON s.id=A.mis_sub_id
LEFT JOIN stu_section_data ssd ON ssd.admn_no=A.admn_no AND ssd.session_year=A.session_yr
INNER JOIN course_structure cs ON cs.id=s.id AND A.semester=?
GROUP BY A.sub_code
ORDER BY paper_type";
        $query = $this->db->query($sql, array($admn_no, $sem, $syear, $sess, $et,$sem));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
        
    }
    
    function get_sub_list_minor($admn_no, $sem, $syear, $sess, $et){
        $sql = "Select A.*,s.name,cs.aggr_id,
CASE WHEN cs.aggr_id like '%honour%' THEN 'Honour'
     WHEN cs.aggr_id not like '%honour%' THEN 'Core' 
     END as 'paper_type'
from (SELECT *
FROM final_semwise_marks_foil_desc
WHERE foil_id=(
SELECT id
FROM final_semwise_marks_foil
WHERE admn_no=? AND semester=? and session_yr=? and session=? and course='Minor' and type=?)
)A
inner join subjects s on s.id=A.mis_sub_id
inner join course_structure cs on cs.id=s.id and semester=?
group by A.sub_code
order by paper_type";
        $query = $this->db->query($sql, array($admn_no, $sem, $syear, $sess, $et,$sem));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
        
    }
    
    function get_foil_description_by_mis_sub_id($id,$hadmn_no,$subid){
        
        $sql = "select a.* from final_semwise_marks_foil_desc a where a.foil_id=? and a.admn_no=? and a.mis_sub_id=?";
        $query = $this->db->query($sql, array($id,$hadmn_no,$subid));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function get_foil_description_by_sub_code($id,$hadmn_no,$subcode){
        
        $sql = "select a.* from final_semwise_marks_foil_desc a where a.foil_id=? and a.admn_no=? and a.sub_code=?";
        $query = $this->db->query($sql, array($id,$hadmn_no,$subcode));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
   /* function get_marks_subjects_description_by_sub_id($syear,$sess,$subid,$admn_no){
        
        $sql = "select b.* from marks_master a inner join marks_subject_description b on b.marks_master_id=a.id
                where a.session_year=? and a.`session`=? and subject_id=? and b.admn_no=? and a.status='Y'";
        $query = $this->db->query($sql, array($syear,$sess,$subid,$admn_no));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }*/
	function get_marks_subjects_description_by_sub_id($syear,$sess,$subid,$admn_no,$hetype){
       if ($hetype == 'regular') { $hetype = "R";}
        if ($hetype == 'other') { $hetype = "O";}
        if ($hetype == 'special') { $hetype = "S";}
        if ($hetype == 'jrf') { $hetype = "J";}
        
        $sql = "select b.* from marks_master a inner join marks_subject_description b on b.marks_master_id=a.id
                where a.session_year=? and a.`session`=? and subject_id=? and b.admn_no=? and a.status='Y' and a.`type`=?";
        $query = $this->db->query($sql, array($syear,$sess,$subid,$admn_no,$hetype));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function get_stu_foil_details($id){
        
        $sql = "select * from final_semwise_marks_foil where id=?";
        $query = $this->db->query($sql, array($id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    /*  function insert_final_foil_desc($data)
	{
		if($this->db->insert('final_semwise_marks_foil_desc_backup',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function insert_final_foil($data)
	{
		if($this->db->insert('final_semwise_marks_foil_backup',$data))
			return TRUE;
		else
			return FALSE;
	}
    */
    
    function insert_ff_bkp($id)
    {
        $sql = "insert into final_semwise_marks_foil_backup  select * from final_semwise_marks_foil where id=?";
        $query = $this->db->query($sql, array($id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function insert_ff_desc_bkp($id)
    {
        $sql = "insert into final_semwise_marks_foil_desc_backup  select * from final_semwise_marks_foil_desc where foil_id=?";
        $query = $this->db->query($sql, array($id));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function get_stu_type($syear,$sess,$type,$admn_no,$stusem){
    if($type=='regular'){$type='R';}if($type=='other'){$type='O';}if($type=='special'){$type='S';}if($type=='JRF'||$type=='jrf'){$type='J';} 
        $sql = "select a.course from final_semwise_marks_foil a where a.session_yr=? and a.`session`=?
                and a.`type`=? and a.course<>'MINOR' and a.admn_no=? and a.semester=?";
        $query = $this->db->query($sql, array($syear,$sess,$type,$admn_no,$stusem));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->course;
        } else {
            return FALSE;
        }
        
        
    }
    
    //JRF
    
    function get_sub_list_jrf($admn_no, $sem, $syear, $sess, $et){

        
       $sql="SELECT A.*,s.name,'Core' as 'paper_type'
FROM (
SELECT a.*,b.session_yr,b.`session`,b.semester
FROM final_semwise_marks_foil_desc a
INNER JOIN final_semwise_marks_foil b ON a.foil_id=b.id
WHERE b.admn_no=? AND b.semester=? AND b.session_yr=? AND b.SESSION=? AND b.course<>'Minor' AND b.TYPE=?)A
INNER JOIN subjects s ON s.id=A.mis_sub_id
GROUP BY A.sub_code
ORDER BY paper_type";
        $query = $this->db->query($sql, array($admn_no, $sem, $syear, $sess, $et));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
        
    }
    
    
    function check_final_foil($id){
       
       $sql="select * from final_semwise_marks_foil_backup where id=?";
        $query = $this->db->query($sql, array($id));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        
        
    }
    
    function check_final_foil_desc($id){
        $sql="select * from final_semwise_marks_foil_desc_backup where foil_id=?";
        $query = $this->db->query($sql, array($id));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
   
    
    
    

    

}
