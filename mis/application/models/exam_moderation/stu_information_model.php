<?php

class Stu_information_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function stu_details_regular($adm_no,$sess,$sy,$sem)
    {
       	         $sql = "
            SELECT
  `user_details`.`id`,
  Concat(`user_details`.`first_name`, ' ', `user_details`.`middle_name`, ' ',
  `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
   `reg_regular_form`.`semester`,
  `reg_regular_form`.`session_year`,
  `reg_regular_form`.`session`,
  `reg_regular_form`.`section`,
  ssd.section as st_section,
  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_regular_form` ON `user_details`.`id` = `reg_regular_form`.`admn_no`
  left join stu_section_data ssd on ssd.admn_no = `reg_regular_form`.admn_no and ssd.session_year= `reg_regular_form`.`session_year`
WHERE
  `user_details`.`id` = '".$adm_no."'
  and `reg_regular_form`.`session` ='".$sess."'
  and `reg_regular_form`.`session_year`='".$sy."'
  and `reg_regular_form`.`semester`='".$sem."'
     ";
        $query = $this->db->query($sql);
       // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    function stu_details_others_special($adm_no,$sess,$syear,$sem) {
        $sql = "(SELECT
  `user_details`.`id`,
  Concat(`user_details`.`first_name`, ' ', `user_details`.`middle_name`, ' ',
  `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
  `reg_exam_rc_form`.`semester`,
  `reg_exam_rc_form`.`session_year`,
  `reg_exam_rc_form`.`session`,

  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_exam_rc_form` ON `user_details`.`id` =
    `reg_exam_rc_form`.`admn_no`
    WHERE
 `user_details`.`id` = '".$adm_no."'
  and session='".$sess."'
  and session_year='".$syear."'
  and   `reg_exam_rc_form`.`semester` like '%".$sem."%' and `reg_exam_rc_form`.hod_status='1' and `reg_exam_rc_form`.acad_status='1')
      UNION
      (SELECT
  `user_details`.`id`,
  Concat(`user_details`.`first_name`, ' ', `user_details`.`middle_name`, ' ',
  `user_details`.`last_name`) AS `stu_name`,
  `departments`.`name` AS `dept_nm`,
  `cs_courses`.`name` AS `course_nm`,
  `cs_branches`.`name` AS `branch_nm`,
  `reg_other_form`.`semester`,
  `reg_other_form`.`session_year`,
  `reg_other_form`.`session`,

  `cs_courses`.`id` AS `cid`,
  `cs_branches`.`id` AS `bid`,
  `departments`.`id` AS `did`,
    `user_details`.`photopath`
FROM
  `user_details`
  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
  INNER JOIN `cs_courses` ON `stu_academic`.`course_id` = `cs_courses`.`id`
  INNER JOIN `cs_branches` ON `stu_academic`.`branch_id` = `cs_branches`.`id`
  INNER JOIN `reg_other_form` ON `user_details`.`id` =
    `reg_other_form`.`admn_no`
    WHERE
 `user_details`.`id` = '".$adm_no."'
  and session='".$sess."'
  and session_year='".$syear."'
  and   `reg_other_form`.`semester` like '%".$sem."%' and `reg_other_form`.hod_status='1' and `reg_other_form`.acad_status='1')
     ";


        $query = $this->db->query($sql);
        
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function stu_detail_jrf($admn_no, $sess, $syear){
        
        $myquery="select a.admn_no as id,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,a.course_id,a.branch_id,c.name as dept_nm,d.name as course_nm,e.name as branch_nm, 'N/A' as semester,a.session_year,a.`session`
from reg_exam_rc_form a 
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id where a.admn_no=? and a.`session`=? and a.session_year=? and a.hod_status='1' and a.acad_status='1'";
      
        $query = $this->db->query($myquery,array($admn_no, $sess, $syear));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    function stu_details_summer($admn_no, $sess, $syear, $stusem,$group)
    {        
        if($group=='n'){$param="";}else{$param=' group by a.admn_no ';}
        $myquery="select a.admn_no as id,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id as did,a.course_id as cid,
a.branch_id as bid,c.name as dept_nm,h.name as course_nm,i.name as branch_nm,substring(g.semester,1,1) as semester,a.session_year,a.`session`,
f.section as st_section,j.`group` as section ,d.sub_id,e.subject_id,e.name,b.photopath,g.aggr_id from reg_summer_form a 
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join reg_summer_subject d on a.form_id=d.form_id
inner join subjects e on e.id=d.sub_id
left join stu_section_data f on f.admn_no=a.admn_no
inner join course_structure g on g.id=d.sub_id
inner join cs_courses h on h.id=a.course_id
inner join cs_branches i on i.id=a.branch_id
left join section_group_rel j on j.section=f.section
where a.hod_status='1' and a.acad_status='1' and a.admn_no =?
and a.`session`=? and a.session_year=?  and g.semester like '%?%'".$param."
order by a.admn_no";
      
        $query = $this->db->query($myquery,array($admn_no, $sess, $syear, (int)$stusem));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    //-----------------------------New Moderation from final foil
    
    function stu_details_from_final_foil($admn_no,$syear,$sess,$type,$stusem)
    {        
        if($type=='regular'){ $type='R';}
        else if($type=='other'){ $type='O';}
        else if($type=='special'){ $type='S';}
        else if($type=='jrf'){ $type='J';}
        
        $myquery="SELECT a.id as foil_id,a.admn_no AS id, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,c.name AS dept_nm,
e.name AS course_nm,f.name AS branch_nm,a.semester,a.tot_cr_hr as tot_hrs,a.tot_cr_pts as tot_cpts,a.core_gpa as tot_gpa,a.`status` as stu_status
FROM final_semwise_marks_foil a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN departments c ON c.id=b.dept_id
INNER JOIN stu_academic d ON d.admn_no=a.admn_no
INNER JOIN cs_courses e ON e.id=d.course_id
INNER JOIN cs_branches f ON f.id=d.branch_id
WHERE a.admn_no=? AND a.session_yr=? AND a.`session`=? AND a.`type`=? AND a.semester=?
";
      
        $query = $this->db->query($myquery,array($admn_no,$syear,$sess,$type,$stusem));
    //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_sub_list_from_final_foil($fid){
        
        $myquery="select a.*,b.name from final_semwise_marks_foil_desc a 
inner join subjects b on a.mis_sub_id=b.id
where a.foil_id=?";
      
        $query = $this->db->query($myquery,array($fid));
   // echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_foil_details($id){
        
        $myquery="select * from final_semwise_marks_foil where id=?";
      
        $query = $this->db->query($myquery,array($id));
   // echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
    function get_foil_desc_details($fid,$admn_no,$sub_id){
        
        $myquery="select a.* from final_semwise_marks_foil_desc a where a.foil_id=?
and a.admn_no=? and a.mis_sub_id=?";
      
        $query = $this->db->query($myquery,array($fid,$admn_no,$sub_id));
   // echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
    
    
    

    
}
