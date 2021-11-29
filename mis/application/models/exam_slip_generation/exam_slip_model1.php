<?php

/**
 * Author: Anuj
*/
class Exam_slip_model extends CI_Model {
  
    function __construct() {
        parent::__construct();
    }
        // inserting data in table leave_notice_tbl
      public function get_student_list($did,$cid,$bid,$sem,$syear,$sess,$et) 
        {
          if($et=='regular')
          {
              if($cid=='honour')
              {
                  $myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join hm_form h on h.admn_no=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' AND b.dept_id='".$did."'  
AND a.branch_id='".$bid."' AND a.semester='".$sem."' AND a.session_year='".$syear."' AND a.`session`='".$sess."'
and h.honours='1' and h.honour_hod_status='Y'  and h.dept_id='".$did."' order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery);
              }
             else if($cid=='minor')
              {
                  $myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join hm_form h on h.admn_no=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' AND b.dept_id='".$did."'  
AND a.branch_id='".$bid."' AND a.semester='".$sem."' AND a.session_year='".$syear."' AND a.`session`='".$sess."'
and h.minor='1' and h.minor_hod_status='Y'  and h.dept_id='".$did."' order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery);
              }
              else
              {
            $myquery = "select a.admn_no,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_regular_form a 
inner join user_details b on b.id=a.admn_no
where a.hod_status='1' and a.acad_status='1'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess));
              }
          }
          if($et=='other')
          {
              $myquery = "select * from (select a.admn_no,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_exam_rc_form a 
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? and a.`type`='R' GROUP BY a.admn_no,a.semester order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester)p

union

select * from (
select a.admn_no,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_other_form a 
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? GROUP BY a.admn_no,a.semester order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester)q";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess,$did,$cid,$bid,$sem,$syear,$sess));
              
          }
          if($et=='special')
          {
              $myquery = "select a.admn_no,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_exam_rc_form a 
inner join user_details b on b.id=a.admn_no
where a.hod_status='1' and a.acad_status='1'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? and a.`type`='O' order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess));
              
          }

          //   echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        
      public function get_student_list_deptwise($did1,$syear,$sess) 
        {
          
            $myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' AND b.dept_id=? and a.session_year=? AND a.`session`=? and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester";
          
             $query = $this->db->query($myquery,array($did1,$syear,$sess));

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        
        public function get_student_list_deptwise_others($did1,$syear,$sess) 
        {
          
            $myquery = "select * from (SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' AND b.dept_id=? and a.session_year=?
AND a.`session`=? and a.`type`='R' and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester)p
union

select * from (
SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' AND b.dept_id=? and a.session_year=?
AND a.`session`=?  and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester)q";
          
             $query = $this->db->query($myquery,array($did1,$syear,$sess,$did1,$syear,$sess));

             //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        
        public function get_student_groups() 
        {
          
            $myquery = "select section from section_group_rel";
          
             $query = $this->db->query($myquery);

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        public function get_common_record($sy,$session,$sec) 
        {
          if($sec=="All")
          {
              $myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
                b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,a.section,s.section as groupsec
                FROM reg_regular_form a
                INNER JOIN user_details b ON b.id=a.admn_no
                inner join section_group_rel g on g.`group`=a.section
                inner join stu_section_data s on s.admn_no=a.admn_no
                WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
                AND a.`session`=?  group by a.admn_no order by a.admn_no, a.semester,a.section,s.section";
               $query = $this->db->query($myquery,array($sy,$session));
          }
          else{
            $myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,a.section,s.section as groupsec
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join section_group_rel g on g.`group`=a.section
inner join stu_section_data s on s.admn_no=a.admn_no
WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
AND a.`session`=? and s.section=?  group by a.admn_no order by a.admn_no, a.semester,a.section,s.section";
             $query = $this->db->query($myquery,array($sy,$session,$sec));
          }
            

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        public function get_common_record_others($sy,$session,$sec) 
        {
          if($sec=="All")
          {
              $myquery = "select * from 
(SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
AND a.`session`=?  group by a.admn_no order by a.admn_no,a.semester,g.section,s.section)p

union

select * from 
(
SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
AND a.`session`=?   group by a.admn_no order by a.admn_no a.semester,g.section,s.section)q
";
               $query = $this->db->query($myquery,array($sy,$session,$sy,$session));
          }
          else{
            $myquery = "select * from 
(SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
AND a.`session`=? and s.section=?  group by a.admn_no order by a.admn_no, a.semester,g.section,s.section)p

union

select * from 
(
SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status='1' AND a.acad_status='1' and a.session_year=?
AND a.`session`=? and s.section=? group by a.admn_no order by a.admn_no, a.semester,g.section,s.section)q";
             $query = $this->db->query($myquery,array($sy,$session,$sec,$sy,$session,$sec));
          }
            

            // echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        
        public function get_student_jrf($syear,$sess,$et,$did)
        {
          
            $myquery = "
select a.admn_no,concat(b.first_name,' ',b.middle_name,' ',b.last_name)as stu_name,b.dept_id,a.course_id,a.branch_id,a.session_year,a.`session` from reg_exam_rc_form a 
inner join user_details b on a.admn_no=b.id
where a.session_year=? and a.`session`=? and course_id=? and dept_id=?

group by a.admn_no
order by a.admn_no,b.dept_id,a.course_id,a.branch_id";
          
             $query = $this->db->query($myquery,array($syear,$sess,$et,$did));

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }

        
			
        
    
}