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
                  $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join hm_form h on h.admn_no=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id='".$did."'
AND a.branch_id='".$bid."' AND a.semester='".$sem."' AND a.session_year='".$syear."' AND a.`session`='".$sess."'
and h.honours='1' and h.honour_hod_status='Y'  and h.dept_id='".$did."' GROUP BY a.admn_no,a.semester order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery);
              }
             else if($cid=='minor')
              {
                  $this->load->model('exam_attendance/exam_attd_model');
                  /*$myquery = "SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join hm_form h on h.admn_no=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id='".$did."'
AND a.branch_id='".$bid."' AND a.semester='".$sem."' AND a.session_year='".$syear."' AND a.`session`='".$sess."'
and h.minor='1' and h.minor_hod_status='Y'  and h.dept_id='".$did."' order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";*/
            $obj= $this->exam_attd_model->getStudentIncomingMinor($bid,$sem,null,$syear,$sess,$did);

          //    echo $this->db->last_query(); die();
              }
              else
              {
            $myquery = "select a.admn_no,concat_WS(' ',b.first_name,b.middle_name,b.last_name) as stu_name,b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_regular_form a
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess));
              }
          }
          if($et=='other')
          {
              $myquery = "select * from (select a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_exam_rc_form a
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? and a.`type`='R' GROUP BY a.admn_no,a.semester order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester)p

union

select * from (
select a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_other_form a
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? GROUP BY a.admn_no,a.semester order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester)q";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess,$did,$cid,$bid,$sem,$syear,$sess));

          }
          if($et=='special')
          {
              $myquery = "select a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) as stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session` from reg_exam_rc_form a
inner join user_details b on b.id=a.admn_no
where a.hod_status<>'2' and a.acad_status<>'2'
and b.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=?
and a.session_year=? and a.`session`=? and a.`type`='O' order by a.admn_no,b.dept_id,a.course_id,a.branch_id,a.semester";
             $query = $this->db->query($myquery,array($did,$cid,$bid,$sem,$syear,$sess));

          }

           //  echo $this->db->last_query(); die();
          if($obj==null)
             if ($query->num_rows() > 0) {
                 return $query->result();
             } else {
                 return FALSE;
             }
           else
                return ($obj==null?false:$obj);

        }

      public function get_student_list_deptwise($did1,$syear,$sess)
        {

            $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id=? and a.session_year=? AND a.`session`=? and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester";

             $query = $this->db->query($myquery,array($did1,$syear,$sess));

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }


        }
        public function get_student_list_deptwise_summer($syear,$sess,$did1)
        {

            $myquery = "select x.*,y.dept_id,concat_ws(' ',y.first_name,y.middle_name,y.last_name) as stu_name from
(select a.admn_no,GROUP_CONCAT(distinct c.semester)as semester,a.course_id,a.branch_id,a.session_year,a.`session` from reg_summer_form a
inner join reg_summer_subject b on b.form_id=a.form_id
inner join course_structure c on c.id=b.sub_id
where a.session_year=? and a.`session`=?  and c.aggr_id not like '%comm%'
and a.hod_status<>'2' and a.acad_status<>'2'
group by a.admn_no)x
inner join user_details y on y.id=x.admn_no
where y.dept_id=? ORDER BY x.admn_no, x.course_id,x.branch_id,x.semester";

             $query = $this->db->query($myquery,array($syear,$sess,$did1));

         //    echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }


        }

        public function get_student_list_deptwise_others($did1,$syear,$sess)
        {


            $myquery = "select * from (SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id=? and a.session_year=?
AND a.`session`=? and a.`type`='R' and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester)p
union

select * from (
SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id=? and a.session_year=?
AND a.`session`=?  and a.semester>=4 order by a.admn_no, a.course_id,a.branch_id,a.semester)q";

             $query = $this->db->query($myquery,array($did1,$syear,$sess,$did1,$syear,$sess));

             //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }


        }
        public function get_student_list_deptwise_others_spl($did1,$syear,$sess)
        {


            $myquery = "select * from (SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id=? and a.session_year=?
AND a.`session`=? and a.`type`='S' and a.semester>=3 order by a.admn_no, a.course_id,a.branch_id,a.semester)p
union

select * from (
SELECT a.admn_no, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND b.dept_id=? and a.session_year=?
AND a.`session`=? and a.`type`='S' and a.semester>=3 order by a.admn_no, a.course_id,a.branch_id,a.semester)q";

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

            $myquery = "select distinct section from section_group_rel";

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
              $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
                b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,a.section,s.section as groupsec
                FROM reg_regular_form a
                INNER JOIN user_details b ON b.id=a.admn_no
                inner join section_group_rel g on g.`group`=a.section
                inner join stu_section_data s on s.admn_no=a.admn_no and  s.session_year= a.session_year
                WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
                AND a.`session`=?  group by a.admn_no order by a.admn_no, a.semester,a.section,s.section";
               $query = $this->db->query($myquery,array($sy,$session));
          }
          else{
            $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,a.section,s.section as groupsec
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join section_group_rel g on g.`group`=a.section
inner join stu_section_data s on s.admn_no=a.admn_no and  s.session_year= a.session_year
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=? and s.section=?  group by a.admn_no order by a.admn_no, a.semester,a.section,s.section";
             $query = $this->db->query($myquery,array($sy,$session,$sec));
          }


            //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }


        }
        //======================summer=================

        public function get_common_record_summer($sy,$session,$sec)
        {
          if($sec=="All")
          {
              $myquery = "SELECT x.*,y.dept_id, CONCAT_WS(' ',y.first_name,y.middle_name,y.last_name) AS stu_name
FROM (
SELECT a.admn_no, GROUP_CONCAT(DISTINCT c.semester) AS semester,a.course_id,a.branch_id,a.session_year,a.`session`,'na' as section,s.section as groupsec
FROM reg_summer_form a
INNER JOIN reg_summer_subject b ON b.form_id=a.form_id
INNER JOIN course_structure c ON c.id=b.sub_id
INNER JOIN stu_section_data s ON (s.admn_no=a.admn_no and s.session_year=a.session_year)
WHERE a.session_year=? AND a.`session`=? AND c.aggr_id LIKE '%comm%' AND a.hod_status<>'2' AND a.acad_status<>'2'
GROUP BY a.admn_no)x
INNER JOIN user_details Y ON y.id=x.admn_no
ORDER BY x.admn_no, x.course_id,x.branch_id,x.semester,x.groupsec";
               $query = $this->db->query($myquery,array($sy,$session));
          }
          else{
            $myquery = "SELECT x.*,y.dept_id, CONCAT_WS(' ',y.first_name,y.middle_name,y.last_name) AS stu_name
FROM (
SELECT a.admn_no, GROUP_CONCAT(DISTINCT c.semester) AS semester,a.course_id,a.branch_id,a.session_year,a.`session`,'na' as section,s.section as groupsec
FROM reg_summer_form a
INNER JOIN reg_summer_subject b ON b.form_id=a.form_id
INNER JOIN course_structure c ON c.id=b.sub_id
INNER JOIN stu_section_data s ON (s.admn_no=a.admn_no and s.session_year=a.session_year)
WHERE a.session_year=? AND a.`session`=?  and s.section=? AND c.aggr_id LIKE '%comm%' AND a.hod_status<>'2' AND a.acad_status<>'2'
GROUP BY a.admn_no)x
INNER JOIN user_details Y ON y.id=x.admn_no
ORDER BY x.admn_no, x.course_id,x.branch_id,x.semester,x.groupsec";
             $query = $this->db->query($myquery,array($sy,$session,$sec));
          }


            //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }


        }




        //=================================================
        public function get_common_record_others($sy,$session,$sec)
        {
          if($sec=="All")
          {
              $myquery = "select * from
(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no and  s.session_year= a.session_year
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
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
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=?   group by a.admn_no order by a.admn_no a.semester,g.section,s.section)q
";
               $query = $this->db->query($myquery,array($sy,$session,$sy,$session));
          }
          else{
            $myquery = "select * from
(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_other_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no and  s.session_year= a.session_year
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=? and s.section=?  group by a.admn_no order by a.admn_no, a.semester,g.section,s.section)p

union

select * from
(
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`,g.section,g.`group` as groupsec
FROM reg_exam_rc_form a
INNER JOIN user_details b ON b.id=a.admn_no
inner join stu_section_data s on s.admn_no=a.admn_no
inner join section_group_rel g on s.section=g.section
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
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
select a.admn_no,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name)as stu_name,b.dept_id,a.course_id,a.branch_id,a.session_year,a.`session` from reg_exam_rc_form a
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
        function get_prep_record($sy,$sess){
            if($sess=='Summer'){ $tbl="reg_summer_form";}
            else{$tbl="reg_regular_form"; }
             $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,a.semester,a.session_year,a.`session`
FROM ".$tbl." a INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=? and a.course_aggr_id like '%prep%'";

             $query = $this->db->query($myquery,array($sy,$sess));

             //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
        }

        function get_jrf_record($syear,$sess,$did1){
            $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,'NA' as semester,a.session_year,a.`session`
FROM reg_exam_rc_form a INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=? and  a.course_id='jrf' and b.dept_id=? and a.type='R'
group by a.admn_no
order by a.admn_no
";

             $query = $this->db->query($myquery,array($syear,$sess,$did1));

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }

        }

        function get_jrf_record_spl($syear,$sess,$did1){
            $myquery = "SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,
b.dept_id,a.course_id,a.branch_id,'NA' as semester,a.session_year,a.`session`
FROM reg_exam_rc_form a INNER JOIN user_details b ON b.id=a.admn_no
WHERE a.hod_status<>'2' AND a.acad_status<>'2' and a.session_year=?
AND a.`session`=? and  a.course_id='jrf' and b.dept_id=? and a.type='JS'
group by a.admn_no
order by a.admn_no
";

             $query = $this->db->query($myquery,array($syear,$sess,$did1));

           //  echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }

        }
		//===================================CBCS====================================================================================================================

		function get_studetn_cbcs($sem,$did,$cid,$bid,$syear,$sess){



            $myquery = " (SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN old_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN old_subject_offered d ON d.id=c.sub_offered_id AND d.semester=?
INNER JOIN old_subject_offered_desc e ON e.sub_offered_id=d.id
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id=? AND d.course_id=?
AND d.branch_id=? AND d.session_year=? AND d.`session`=?
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester)
union
(SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN cbcs_subject_offered d ON d.id=c.sub_offered_id AND d.semester=?
INNER JOIN cbcs_subject_offered_desc e ON e.sub_offered_id=d.id
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id=? AND d.course_id=?
AND d.branch_id=? AND d.session_year=? AND d.`session`=?
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester)";

//echo $myquery;
             $query = $this->db->query($myquery,array($sem,$did,$cid,$bid,$syear,$sess,$sem,$did,$cid,$bid,$syear,$sess));

            //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }

        }

        //==========================CBCS Common Start========================================================================

        function get_studetn_cbcs_comm($sem,$did,$cid,$bid,$syear,$sess){


          if($sess=='Monsoon'){
            $sec=$sem;
            $sem='1';
          }
          if($sess=='Winter'){
            $sec=$sem;
            $sem='2';
          }

                $myquery = " (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`,a.section,c.sub_category_cbcs_offered AS groupsec
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN old_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no and c.sub_category_cbcs_offered='".$sec."'
INNER JOIN old_subject_offered d ON d.id=c.sub_offered_id AND d.semester='".$sem."'
INNER JOIN old_subject_offered_desc e ON e.sub_offered_id=d.id AND e.section='".$sec."'
/*INNER JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year='".$syear."' AND f.section=e.section*/
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id='".$did."' AND d.course_id='".$cid."' AND d.branch_id='".$bid."'
AND d.session_year='".$syear."' AND d.`session`='".$sess."'
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester) UNION (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`,a.section,c.sub_category_cbcs_offered AS groupsec
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no and c.sub_category_cbcs_offered='".$sec."'
INNER JOIN cbcs_subject_offered d ON d.id=c.sub_offered_id AND d.semester='".$sem."'
INNER JOIN cbcs_subject_offered_desc e ON e.sub_offered_id=d.id AND e.section='".$sec."'
/*INNER JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year='".$syear."' AND f.section=e.section*/
WHERE a.hod_status<>'2' AND a.acad_status<>'2'AND d.dept_id='".$did."' AND d.course_id='".$cid."' AND d.branch_id='".$bid."'
AND d.session_year='".$syear."' AND d.`session`='".$sess."'
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester)";

                 $query = $this->db->query($myquery);

                //echo $this->db->last_query(); die();
                 if ($query->num_rows() > 0) {
                     return $query->result();

                 } else {
                     return FALSE;
                 }

            }


        //==============================CBCS Common End======================================================================

        //==========================CBCS PREP Start========================================================================

        function get_studetn_cbcs_prep($sem,$did,$cid,$bid,$syear,$sess){

          if($cid=='prep'){
            if($sem=='1'){ $sem='-1';}
            if($sem=='2'){ $sem='-2';}
          }


                $myquery = " (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN old_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN old_subject_offered d ON d.id=c.sub_offered_id AND d.semester='".$sem."'
INNER JOIN old_subject_offered_desc e ON e.sub_offered_id=d.id AND e.section='".$sec."'
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id='".$did."' AND d.course_id='".$cid."' AND d.branch_id='".$bid."'
AND d.session_year='".$syear."' AND d.`session`='".$sess."'
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester) UNION (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id,
d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN cbcs_subject_offered d ON d.id=c.sub_offered_id AND d.semester='".$sem."'
INNER JOIN cbcs_subject_offered_desc e ON e.sub_offered_id=d.id AND e.section='".$sec."'
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id='".$did."' AND d.course_id='".$cid."' AND d.branch_id='".$bid."'
AND d.session_year='".$syear."' AND d.`session`='".$sess."'
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester)";

                 $query = $this->db->query($myquery);

                //echo $this->db->last_query(); die();
                 if ($query->num_rows() > 0) {
                     return $query->result();

                 } else {
                     return FALSE;
                 }

            }


        //==============================CBCS PREP End======================================================================


		//JRF

		function get_studetn_cbcs_jrf($did,$syear,$sess){


            $myquery = " (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id, d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN old_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN old_subject_offered d ON d.id=c.sub_offered_id
INNER JOIN old_subject_offered_desc e ON e.sub_offered_id=d.id
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id=? AND d.course_id='jrf'
 AND d.session_year=? AND d.`session`=?
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester) UNION (
SELECT a.admn_no, CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,d.dept_id,d.course_id,d.branch_id, d.semester,d.session_year,d.`session`
FROM reg_regular_form a
INNER JOIN user_details b ON b.id=a.admn_no
INNER JOIN cbcs_stu_course c ON c.form_id=a.form_id AND c.admn_no=a.admn_no
INNER JOIN cbcs_subject_offered d ON d.id=c.sub_offered_id
INNER JOIN cbcs_subject_offered_desc e ON e.sub_offered_id=d.id
WHERE a.hod_status<>'2' AND a.acad_status<>'2' AND d.dept_id=? AND d.course_id='jrf'
 AND d.session_year=? AND d.`session`=?
GROUP BY a.admn_no
ORDER BY a.admn_no,d.dept_id,d.course_id,d.branch_id,d.semester)";

             $query = $this->db->query($myquery,array($did,$syear,$sess,$did,$syear,$sess));

            //echo $this->db->last_query(); die();
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }

        }





}
