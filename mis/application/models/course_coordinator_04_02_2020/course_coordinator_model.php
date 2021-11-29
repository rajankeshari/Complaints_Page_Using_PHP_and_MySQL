<?php

class Course_coordinator_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	function marks_correction_history_sub_wise($sub_code,$course_id,$branch_id,$session_year,$session){
      $sql="SELECT a.correction_log_id,a.admn_no,a.form_id,a.session_year,a.`session`,a.sub_code,a.sub_offered_id,a.course_id,a.branch_id,
            a.marks_upload_id,a.marks_upload_dis_id,a.dist_name,a.dist_id,a.old_marks,a.corrected_marks,a.old_total,a.new_total,a.new_grade,a.old_grade,a.corrected_by,a.updated_at,b.id AS logid,b.submit_to_exam_status AS st
            FROM cbcs_marks_correction_backup a
            INNER JOIN cbcs_marks_correction_log b ON a.correction_log_id=b.id AND a.admn_no=b.admn_no AND a.session_year=b.session_year AND a.`session`=b.`session` AND a.sub_code=b.sub_code
            where a.sub_code='$sub_code' and a.course_id='$course_id' and a.branch_id='$branch_id' and a.session_year='$session_year' and a.`session`='$session'
            ORDER BY b.submit_to_exam_status,b.update_status,b.id DESC";
            $query = $this->db->query($sql);
              //echo $this->db->last_query(); die();
              if ($query->num_rows() > 0)
              return $query->result();
              else
              return false;
    }
	
	
    function saveOrUpdateCourseCordinator($data){
      $selectValue=array(
        "session"=>$data['session'],
        "session_year"=>$data['session_year'],
        "sub_code"=>$data['sub_code'],
      );

    $this->db->select('*');
    $this->db->from('cbcs_assign_course_coordinator');
    $this->db->where($selectValue);
    $cnt = $this->db->get()->num_rows();
      //echo  $this->db->last_query();die();
    if($cnt==0){
      $this->db->insert('cbcs_assign_course_coordinator', $data);
      //echo  $this->db->last_query();die();
      if($this->db->affected_rows() != 1){

    										echo"cbcs_assign_course_coordinator :".	$this->db->_error_message();

    									}else{
    										echo "Course Coordinator Assigned Successfully";
    									}
    }else{
      $this->db->where($selectValue);
      $this->db->update('cbcs_assign_course_coordinator', $data);
    //echo  $this->db->last_query();die();
      if($this->db->affected_rows() != 1){

                        echo"cbcs_assign_course_coordinator :".	$this->db->_error_message();

                      }else{
                        echo "Course Coordinator Updated Successfully";
                      }
    }
    }
    function get_teaching_faultyforprint($sub_code,$session,$session_year){
      $sql = "(select a.sub_code,a.sub_name,b.emp_no,GROUP_CONCAT(concat_ws(',',concat_ws('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as offered_to ,
    GROUP_CONCAT(concat_ws(' - ',concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no ))) as name,
    IF(acc.`status`,(concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned')  as course_coordinator
    from cbcs_subject_offered a
    inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id
    Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code  and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$sub_code' group by a.sub_code)
     union
    (select a.sub_code,a.sub_name,b.emp_no,GROUP_CONCAT(concat_ws(',',concat_ws('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as offered_to ,
    GROUP_CONCAT(concat_ws(' - ',concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name,'-', b.emp_no))) as name,
    IF(acc.`status`,(concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned')  as course_coordinator
    from old_subject_offered a
    inner join old_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id
    Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code  and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$sub_code' group by a.sub_code)";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();die();
            return $query->result();
        } else {
            return false;
        }
    }
    function getCoName($session_year,$session,$sub_code){
      $sql = "select concat_ws(' ',b.first_name,b.middle_name,b.last_name) as emp_name from(select a.co_emp_id as emp_id  from cbcs_assign_course_coordinator a
where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year')x
inner join user_details b on x.emp_id=b.id";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
    }
    function get_teaching_faulty_single($subject,$session,$session_year){
      $sql = "(select a.session_year,a.`session`,a.dept_id,a.semester,a.sub_code
    ,a.sub_name,b.emp_no,concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) as name from cbcs_subject_offered a
    inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id
    #Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$subject' group by b.emp_no)
    union
    (select a.session_year,a.`session`,a.dept_id,a.semester,a.sub_code
    ,a.sub_name,b.emp_no,concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) as name from old_subject_offered a
    inner join old_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id

    #Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code  and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$subject' group by b.emp_no)";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
    }
      function get_teaching_faulty($subject,$session,$session_year){
        $sql = "(select a.session_year,a.`session`,a.dept_id,d.name as dept_name,bb.name as branch_name,cc.name as course_name,a.semester,a.sub_code,a.sub_name,b.emp_no,concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) as name,acc.`status` from cbcs_subject_offered a
    inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id
    inner join cbcs_departments d on a.dept_id=d.id
    inner join cbcs_branches bb on a.branch_id=bb.id
    inner join cbcs_courses cc on a.course_id=cc.id
    Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$subject')
    union all
    (select a.session_year,a.`session`,a.dept_id,d.name as dept_name,bb.name as branch_name,cc.name as course_name,a.semester,a.sub_code,a.sub_name,b.emp_no,concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) as name,acc.`status` from old_subject_offered a
    inner join old_subject_offered_desc b on a.id=b.sub_offered_id
    inner join user_details ud on b.emp_no=ud.id
    inner join cbcs_departments d on a.dept_id=d.id
    inner join cbcs_branches bb on a.branch_id=bb.id
    inner join cbcs_courses cc on a.course_id=cc.id
    Left join cbcs_assign_course_coordinator acc on b.emp_no=acc.co_emp_id and a.sub_code=acc.sub_code and a.dept_id=acc.offered_to
    where a.session_year='$session_year' and a.`session`='$session'
    and a.sub_code='$subject')";
          $query = $this->db->query($sql);
          if ($this->db->affected_rows() > 0) {
          //  echo  $this->db->last_query();
              return $query->result();
          } else {
              return false;
          }
      }
      function reOpenMarksSubmission($co_assign_id,$marks_child_id,$sub_code,$session,$session_year){
        $sql="update cbcs_marks_dist_child set marks_upload_status='0' where id='$marks_child_id'";
        $query = $this->db->query($sql);
      //   echo  $this->db->last_query(); die();
      $sqlCoAssign="update cbcs_marks_send_to_coordinator set status='2' where marks_master_id='$co_assign_id'";
      $queryCoAssign = $this->db->query($sqlCoAssign);
        if ($this->db->affected_rows() > 0) {
          $sqlupdate="update cbcs_marks_send_to_coordinator set dean_ac_status='2' where sub_code='$sub_code' and session='$session' and session_year='$session_year' and status='2'";
          $sqlupdates = $this->db->query($sqlupdate);
          $msg="Marks submission Re-open Successfully.";
            return $msg;
        } else {
          $msg="Marks submission already Open.";
            return $msg;
        }
      }
      function reOpenMarksbifercationSubmission($co_assign_id,$marks_child_id,$sub_code,$session,$session_year){
        $sql="update cbcs_marks_dist_child set marks_upload_status='0' where pk='$marks_child_id'";
        $query = $this->db->query($sql);
      //   echo  $this->db->last_query(); die();
      $sqlCoAssign="update cbcs_marks_send_to_coordinator set status='2' where sub_code='$sub_code' and sub_offered_id='$co_assign_id' and status='1'";
      $queryCoAssign = $this->db->query($sqlCoAssign);
  //  echo  $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {

          $sqlupdate="update cbcs_marks_send_to_coordinator set dean_ac_status='2' where sub_code='$sub_code' and session='$session' and session_year='$session_year'";
          $sqlupdates = $this->db->query($sqlupdate);

      $msg="Marks submission Re-open Successfully.";
            return $msg;
        } else {
          $msg="Marks submission already Open.";
            return $msg;
        }
      }
      function get_marks_bifercation_for_CO($marks_dist_id){
        $sql="select * from cbcs_marks_dist_child where id='$marks_dist_id'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
      //   echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
      }
      function getallStudentMarks($sub_code,$session,$session_year,$exam_type,$offered_id,$sub_type){
 if($sub_type=='Modular'){
	    $extrajoin="inner join cbcs_modular_paper_details h on x.subject_code in (h.after_mid) and x.admn_no=h.admn_no";

        $sql="select x.* from ((select a.id as ids,a.form_id,a.admn_no,CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as stu_name,d.name as course,e.name as branch,
concat('c',a.sub_offered_id) as sub_offered_id,a.subject_code,b.semester,g.id,g.marks_master_id,g.total,g.grade from cbcs_stu_course a
inner join cbcs_subject_offered b on a.sub_offered_id=b.id
inner join user_details c on a.admn_no=c.id
inner join cbcs_courses d on a.course=d.id
inner join cbcs_branches e on a.branch=e.id

LEFT join cbcs_marks_master f on concat('c',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`
LEFT join cbcs_marks_subject_description g on f.id=g.marks_master_id and a.admn_no=g.admn_no
where a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' AND f.id=g.marks_master_id)
 union
(select a.id,a.form_id,a.admn_no,CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as stu_name,d.name as course,e.name as branch,
concat('o',a.sub_offered_id) as sub_offered_id,a.subject_code,b.semester,g.id,g.marks_master_id,g.total,g.grade from old_stu_course a
inner join old_subject_offered b on a.sub_offered_id=b.id
inner join user_details c on a.admn_no=c.id
inner join cbcs_courses d on a.course=d.id
inner join cbcs_branches e on a.branch=e.id

Left join cbcs_marks_master f on concat('o',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`
Left join cbcs_marks_subject_description g on f.id=g.marks_master_id and a.admn_no=g.admn_no
where a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' AND f.id=g.marks_master_id)) x

inner join cbcs_modular_paper_details h on x.subject_code in (h.$exam_type) and x.admn_no=h.admn_no
";
} else{
 if($sub_type=='Modular'){
          $extraParam="";
          $extraClouse="AND f.id=g.marks_master_id";
        }
        $sql="(select a.id,a.form_id,a.admn_no,CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as stu_name,d.name as course,e.name as branch,
concat('c',a.sub_offered_id) as sub_offered_id,a.subject_code,b.semester,g.id,g.marks_master_id,g.total,g.grade from cbcs_stu_course a
inner join cbcs_subject_offered b on a.sub_offered_id=b.id
inner join user_details c on a.admn_no=c.id
inner join cbcs_courses d on a.course=d.id
inner join cbcs_branches e on a.branch=e.id
LEFT join cbcs_marks_master f on concat('c',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`
LEFT join cbcs_marks_subject_description g on f.id=g.marks_master_id and a.admn_no=g.admn_no
where a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse)
 union
(select a.id,a.form_id,a.admn_no,CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as stu_name,d.name as course,e.name as branch,
concat('o',a.sub_offered_id) as sub_offered_id,a.subject_code,b.semester,g.id,g.marks_master_id,g.total,g.grade from old_stu_course a
inner join old_subject_offered b on a.sub_offered_id=b.id
inner join user_details c on a.admn_no=c.id
inner join cbcs_courses d on a.course=d.id
inner join cbcs_branches e on a.branch=e.id
Left join cbcs_marks_master f on concat('o',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`
Left join cbcs_marks_subject_description g on f.id=g.marks_master_id and a.admn_no=g.admn_no
where a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse) ";


	  }
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
      //   echo  $this->db->last_query(); die();
            return $query->result();
        } else {
            return false;
        }
      }

      function getsubjectinfo($sub_code,$session,$session_year,$sub_type,$sub_offer_id,$course_id,$branch_id){
        if($sub_type=="Modular" && $course_id == "comm" && $branch_id == "comm"){
          $extraParam="AND a.sub_type='$sub_type' AND a.id='$sub_offer_id'";
          $extraClouseCBCS="AND concat('c',a.id)=md.map_id";
          $extraClouseOLD="AND concat('o',a.id)=md.map_id";
         $sql = "(
 SELECT msc.id as cc_id,msc.`status` as sendToCStatus,IF(md.id,md.id,'0') AS marks_dist_id,msc.sub_offered_id,a.id, (
 SELECT COUNT(mdc.marks_upload_status)
 FROM cbcs_marks_dist_child mdc
 WHERE mdc.id=marks_dist_id AND mdc.marks_upload_status='1') AS submittedcnt, (
 SELECT COUNT(mdc.id)
 FROM cbcs_marks_dist_child mdc
 WHERE mdc.id=marks_dist_id
 GROUP BY mdc.id) AS totalcnt,acc.id AS cc_id, a.sub_code,a.sub_name,b.emp_no, '$session' AS SESSION,$session_year AS session_year, (CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester))) AS offered_to,  CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no) AS name,
 IF(acc.`status`,(CONCAT_WS('',acc.co_emp_id)), 'Not Assigned') AS course_coordinator
 FROM cbcs_subject_offered a
 INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id and b.coordinator='1'
 INNER JOIN user_details ud ON b.emp_no=ud.id
 LEFT JOIN cbcs_assign_course_coordinator acc ON  a.sub_code=acc.sub_code and acc.sub_offered_id=a.id
 LEFT JOIN cbcs_marks_dist md ON b.emp_no=md.emp_no AND a.sub_code=md.sub_code AND a.session_year=md.session_year AND a.`session`=md.`session` $extraClouseCBCS
 LEFT JOIN cbcs_marks_send_to_coordinator msc ON md.sub_code=msc.sub_code AND md.course_id=msc.course AND md.branch_id=msc.branch and b.emp_no=msc.instructor_emp_id  and (case when a.sub_type='Modular' then md.map_id=msc.sub_offered_id else 1=1 end)
 WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.sub_code='$sub_code' AND a.sub_type='Modular' AND a.id='$sub_offer_id'
  ) union
  (
 SELECT msc.id as cc_id,msc.`status` as sendToCStatus,IF(md.id,md.id,'0') AS marks_dist_id,msc.sub_offered_id,a.id, (
 SELECT COUNT(mdc.marks_upload_status)
 FROM cbcs_marks_dist_child mdc
 WHERE mdc.id=marks_dist_id AND mdc.marks_upload_status='1') AS submittedcnt, (
 SELECT COUNT(mdc.id)
 FROM cbcs_marks_dist_child mdc
 WHERE mdc.id=marks_dist_id
 GROUP BY mdc.id) AS totalcnt,acc.id AS cc_id, a.sub_code,a.sub_name,b.emp_no, '$session' AS SESSION, $session_year AS session_year, (CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester))) AS offered_to,  CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no) AS name,
 IF(acc.`status`,(CONCAT_WS('',acc.co_emp_id)), 'Not Assigned') AS course_coordinator
 FROM old_subject_offered a
 INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id and b.coordinator='1'
 INNER JOIN user_details ud ON b.emp_no=ud.id
 LEFT JOIN cbcs_assign_course_coordinator acc ON  a.sub_code=acc.sub_code and acc.sub_offered_id=a.id
 LEFT JOIN cbcs_marks_dist md ON b.emp_no=md.emp_no AND a.sub_code=md.sub_code AND a.session_year=md.session_year AND a.`session`=md.`session` $extraClouseOLD
 LEFT JOIN cbcs_marks_send_to_coordinator msc ON md.sub_code=msc.sub_code AND md.course_id=msc.course AND md.branch_id=msc.branch and b.emp_no=msc.instructor_emp_id and (case when a.sub_type='Modular' then md.map_id=msc.sub_offered_id else 1=1 end)
 WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.sub_code='$sub_code' AND a.sub_type='Modular' AND a.id='$sub_offer_id' )";
        }else{
        $sql = "(
SELECT msc.id as cc_id,msc.`status` as sendToCStatus,IFNULL(md.id,'0') as marks_dist_id,msc.sub_offered_id,
(select count(mdc.marks_upload_status) from cbcs_marks_dist_child mdc where mdc.id=marks_dist_id and mdc.marks_upload_status='1' ) as submittedcnt,
(select count(mdc.id) from cbcs_marks_dist_child mdc where mdc.id=marks_dist_id group by mdc.id) as totalcnt,acc.id as cc_id,
a.sub_code,a.sub_name,b.emp_no,'Monsoon' as session,'2019-2020' as session_year , (CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester,b.section))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id and b.coordinator=1
INNER JOIN user_details ud ON b.emp_no=ud.id
LEFT JOIN cbcs_assign_course_coordinator acc ON  a.sub_code=acc.sub_code and acc.sub_offered_id=a.id  AND b.emp_no=acc.co_emp_id
LEFT JOIN cbcs_marks_dist md ON b.emp_no=md.emp_no AND a.sub_code=md.sub_code  and a.session_year=md.session_year and a.`session`=md.`session` and a.branch_id=md.branch_id  and a.course_id=md.course_id  and (case when b.section !='' then  b.section=md.section else 1=1 end )
Left JOIN cbcs_marks_send_to_coordinator msc on md.sub_code=msc.sub_code and md.course_id=msc.course and md.branch_id=msc.branch and b.emp_no=msc.instructor_emp_id
WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.sub_code='$sub_code' and b.coordinator='1' $extraParam
GROUP BY a.sub_code,a.branch_id,a.course_id,a.dept_id,(case when b.section !='' then  b.section else 1=1 end )) UNION(
SELECT msc.id as cc_id,msc.`status` as sendToCStatus,IFNULL(md.id,'0') as marks_dist_id,msc.sub_offered_id,
(select count(mdc.marks_upload_status) from cbcs_marks_dist_child mdc where mdc.id=marks_dist_id and mdc.marks_upload_status='1' ) as submittedcnt,
(select count(mdc.id) from cbcs_marks_dist_child mdc where mdc.id=marks_dist_id group by mdc.id) as totalcnt,acc.id as cc_id,
a.sub_code,a.sub_name,b.emp_no,'Monsoon' as session,'2019-2020' as session_year , (CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester,b.section))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name,'-', b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id and b.coordinator=1
INNER JOIN user_details ud ON b.emp_no=ud.id
LEFT JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code
LEFT JOIN cbcs_marks_dist md ON b.emp_no=md.emp_no AND a.sub_code=md.sub_code  and a.session_year=md.session_year and a.`session`=md.`session` and a.branch_id=md.branch_id and a.course_id=md.course_id and (case when b.section !='' then  b.section=md.section else 1=1 end )
Left JOIN cbcs_marks_send_to_coordinator msc on md.sub_code=msc.sub_code and md.course_id=msc.course and md.branch_id=msc.branch and b.emp_no=msc.instructor_emp_id
WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.sub_code='$sub_code' and b.coordinator='1' $extraParam
GROUP BY a.sub_code,a.branch_id,a.course_id,a.dept_id,(case when b.section !='' then  b.section else 1=1 end ))";
}
//echo $sql;
          $query = $this->db->query($sql);

          if ($this->db->affected_rows() > 0) {
          //  echo  $this->db->last_query();
            // echo "<pre>";print_r($query->result()); exit;
              return $query->result();
          } else {
              return false;
          }
      }
      function UpdateGrades($data,$gradeval){
        $updateVal=array(
          "grade"=>$gradeval
        );
        $this->db->where($data);
        $this->db->update('cbcs_marks_subject_description', $updateVal);
      }
      function SaveGrades($GradeData){
        $whereClouse=array(
          "session"=>$GradeData['session'],
          "session_year"=>$GradeData['session_year'],
          "sub_code"=>$GradeData['sub_code'],
		  "sub_offered_id"=>$GradeData['sub_offered_id'],
          "grade"=>$GradeData['grade'],
          "created_by"=>$this->session->userdata("id")
        );
        $updateData=array(
          "min_marks"=>$GradeData['min_marks'],
          "max_marks"=>$GradeData['max_marks'],
        );
        $this->db->select('*');
        $this->db->from('cbcs_grading_range');
        $this->db->where($whereClouse);
        $cnt=$this->db->get()->result_array();
        //$cnt=$this->db->last_query(); die();
        $count=count($cnt);
          if($count==0){
              $this->db->insert('cbcs_grading_range', $GradeData);
          }else{
            $this->db->where($whereClouse);
            $this->db->update('cbcs_grading_range', $GradeData);
          }
      }
      function getGrades($sub_code,$session,$session_year,$sub_offerd_id){
        $sql = "select a.* from cbcs_grading_range a where a.`session`='$session' and a.session_year='$session_year' and a.sub_code ='$sub_code' and a.sub_offered_id='$sub_offerd_id' ";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
	  function getGrades_test($sub_code,$session,$session_year){
        $sql = "select a.* from cbcs_grading_range a where a.`session`='$session' and a.session_year='$session_year' and a.sub_code ='$sub_code'  ";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }

      function GetsendToExamStatus($sub_code,$session,$session_year,$offered_id,$exam_type,$course_id,$branch_id){
		  if($course_id=='comm' && $branch_id=='comm'){
			  $extraClouse="and a.sub_offered_id='$offered_id'";
		  }
        $sql = "select count(a.sub_code) as sub_cnt,sum(IF(a.dean_ac_status like '%1%',1,0)) as dean_ac_status,a.updated_at as senddate from cbcs_marks_send_to_coordinator a
        where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' and a.status='1' $extraClouse";
          $query = $this->db->query($sql);
        // echo  $this->db->last_query();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }

      function subject_grading_info($sub_code,$session,$session_year,$sub_type,$offered_id,$exam_type,$course_id,$branch_id){
if($sub_type=='Modular' && $course_id=='comm' &&  $branch_id=='comm'){
	 $sql="select y.*,sum(y.stu_cnt) as stu_cnt ,GROUP_CONCAT(y.branch_stu_info1) as branch_stu_info from (select count(x.admn_no) as stu_cnt,x.*,concat_ws(' / ',x.branch_stu,count(x.admn_no)) as branch_stu_info1 from (select a.subject_name as sub,a.subject_code,a.admn_no,b.section
,GROUP_CONCAT(DISTINCT
(concat(a.course,' / ',a.branch,
  ' / ',b.section, ' / ' ,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name)))) as branch_stu
,a.subject_name,a.`session`,a.session_year,(select max(updated_at) from cbcs_marks_send_to_coordinator where subject_code='$sub_code' and session_year='$session_year' and session='$session') as submitted_date
from cbcs_stu_course a

LEFT join cbcs_subject_offered_desc b on a.sub_offered_id=b.sub_offered_id
LEFT join user_details c on b.emp_no=c.id

where  a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' and b.coordinator='1'  group by a.admn_no,b.section) x
inner join cbcs_modular_paper_details d on x.subject_code in (d.$exam_type) and x.admn_no=d.admn_no and x.section=d.section group by x.subject_code,x.section ) y";
	
	
}else{	

 if($sub_type=='Modular'){

        $offered_id=  substr($offered_id, 1);
        $extraClouse="";
          //$extraClouse=" and a.sub_offered_id='$offered_id'";
          $join_old_m="(select count(l.admn_no) from old_subject_offered_desc k
            inner join old_subject_offered m on k.desc_id=m.id
            inner join cbcs_modular_paper_details l on k.sub_id=l.before_mid OR k.sub_id=l.after_mid and k.section=l.section
            where k.sub_id='$sub_code' and k.emp_no=b.emp_no and k.section=l.section and l.session_year='$session_year' and l.`session`='$session' and l.branch_id=a.branch and l.course_id=a.course)";

            $join_cbcs_m="(select count(l.admn_no) from cbcs_subject_offered_desc k
              inner join cbcs_subject_offered m on k.desc_id=m.id
              inner join cbcs_modular_paper_details l on k.sub_id=l.before_mid OR k.sub_id=l.after_mid and k.section=l.section
              where k.sub_id='$sub_code' and k.emp_no=b.emp_no and k.section=l.section and l.session_year='$session_year' and l.`session`='$session' and l.branch_id=a.branch and l.course_id=a.course)";


        }else{
            $oldjoin="(select count(id) from  old_stu_course d where d.branch=a.branch and d.course=a.course and d.subject_code='$sub_code' and d.`session`='Monsoon' and d.session_year='2019-2020')";
            $cbcsjoin="(select count(id) from  cbcs_stu_course d where d.branch=a.branch and d.course=a.course and d.subject_code='$sub_code' and d.`session`='Monsoon' and d.session_year='2019-2020')";
        }

        $sql = "select count(DISTINCT(a.admn_no)) as stu_cnt,a.subject_name
,GROUP_CONCAT(DISTINCT
(concat(a.course,' / ',a.branch,' / ',
$oldjoin  $join_old_m  ,' / ',b.section, ' / ' ,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name)))) as branch_stu_info
,a.subject_name,a.`session`,a.session_year,(select max(updated_at) from cbcs_marks_send_to_coordinator where subject_code='$sub_code' and session_year='2019-2020' and session='Monsoon') as submitted_date
from old_stu_course a
LEFT join old_subject_offered_desc b on a.sub_offered_id=b.sub_offered_id
LEFT join user_details c on b.emp_no=c.id
where  a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' and b.coordinator='1' $extraClouse group by a.subject_code
UNION
select count(DISTINCT(a.admn_no)) as stu_cnt,a.subject_name
,GROUP_CONCAT(DISTINCT
(concat(a.course,' / ',a.branch,' / ',
$cbcsjoin  $join_cbcs_m,' / ',b.section, ' / ' ,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name)))) as branch_stu_info
,a.subject_name,a.`session`,a.session_year,(select max(updated_at) from cbcs_marks_send_to_coordinator where subject_code='$sub_code' and session_year='2019-2020' and session='Monsoon') as submitted_date
from cbcs_stu_course a

LEFT join cbcs_subject_offered_desc b on a.sub_offered_id=b.sub_offered_id
LEFT join user_details c on b.emp_no=c.id
where  a.subject_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' and b.coordinator='1' $extraClouse group by a.subject_code";
	  }
		  
		  $query = $this->db->query($sql);
      //    echo  $this->db->last_query(); die();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
	  
	     function get_sub_type_for_st($sub_code,$session,$session_year,$sub_type,$offered_id){
        $sql="
        select a.*,null as ex from cbcs_subject_offered a
        where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$sub_code' group by a.sub_code
        union
        select a.* from old_subject_offered a
        where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$sub_code'  group by a.sub_code
        ";
        $query = $this->db->query($sql);
    //    echo  $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
      }
	  
      function marksAvg($sub_code,$session,$session_year){
        $sql = "select avg(b.total) as avg_marks
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code' and b.total <= 100";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
      function Calculate_SD($sub_code,$session,$session_year){
        $sql = "select b.total as marks
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code' and b.total <= 100 ";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
      function gradestaticspercentagelesslike($sub_code,$session,$session_year,$exam_type,$sub_type,$sub_offerd_id){
		  if($sub_type =="Modular"){
          $extrajoin="inner join cbcs_modular_paper_details c on b.admn_no=c.admn_no and a.subject_id in (c.$exam_type)";
		  $extraClouse="and (case when c.course_id='comm' && c.branch_id='comm' then a.sub_map_id='$sub_offerd_id' else '1=1' end)";
        }
		  
        $sql = "select count(b.id) as total_stu,sum(IF(b.grade=null OR b.grade='',1,0)) as gradingStatus
               ,sum(IF(b.grade like 'A' OR b.grade like 'A+' OR b.grade like 'B+',1,0)) as 	agrade
               ,sum(IF(b.grade like 'B' OR b.grade like 'C' OR b.grade like 'C+',1,0)) as 	bgrade
               ,sum(IF(b.grade like 'D' OR b.grade like 'F',1,0)) as 	cgrade
			    ,sum(IF(b.grade like 'I',1,0)) as 	igrade
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
				$extrajoin
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code' $extraClouse";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query(); die();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
      function gradeStaticslike($sub_code,$session,$session_year,$exam_type,$sub_type,$sub_offerd_id){
		  if($sub_type =="Modular"){
          $extrajoin="inner join cbcs_modular_paper_details c on b.admn_no=c.admn_no and a.subject_id in (c.$exam_type)";
		  $extraClouse="and (case when c.course_id='comm' && c.branch_id='comm' then a.sub_map_id='$sub_offerd_id' else '1=1' end)";
        }
        $sql = "select count(b.id) as total_stu,sum(IF(b.grade=null OR b.grade='',1,0)) as gradingStatus
               ,sum(IF(b.grade like '%A%',1,0)) as 	agrade
               ,sum(IF(b.grade like '%B%',1,0)) as 	bgrade
               ,sum(IF(b.grade like '%C%',1,0)) as 	cgrade
               ,sum(IF(b.grade like '%D%',1,0)) as 	dgrade
               ,sum(IF(b.grade like '%F%',1,0)) as 	fgrade
			   ,sum(IF(b.grade like '%I%',1,0)) as 	igrade
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
				$extrajoin
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code' $extraClouse";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query(); die();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
	    function getIGrade($sub_code,$session,$session_year,$exam_type,$sub_type){
        if($sub_type=="Modular"){
          $extrajoin="inner join cbcs_modular_paper_details c on b.admn_no=c.admn_no and a.subject_id in (c.$exam_type)";
        }
        $sql = "select count(b.id) as total_stu,sum(IF(b.grade='I',1,0)) as igrade
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
                $extrajoin
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code'";
          $query = $this->db->query($sql);
      //    echo  $this->db->last_query(); die();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
      public function gradeStatics($sub_code,$session,$session_year ,$exam_type,$sub_type,$sub_offerd_id){
		  //echo $exam_type;
		  if($sub_type =="Modular"){
          $extrajoin="inner join cbcs_modular_paper_details c on b.admn_no=c.admn_no and a.subject_id in (c.$exam_type)";
		  $extraClouse="and (case when c.course_id='comm' && c.branch_id='comm' then a.sub_map_id='$sub_offerd_id' else '1=1' end)";
        }
        $sql = "select count(b.id) as total_stu,sum(IF(b.grade=null OR b.grade='',1,0)) as gradingStatus
                ,sum(IF(b.grade='A+',1,0)) as apgrade
                ,sum(IF(b.grade='A',1,0)) as  agrade
                ,sum(IF(b.grade='B+',1,0)) as bpgrade
                ,sum(IF(b.grade='B',1,0)) as 	bgrade
                ,sum(IF(b.grade='C+',1,0)) as cpgrade
                ,sum(IF(b.grade='C',1,0)) as 	cgrade
                ,sum(IF(b.grade='D',1,0)) as 	dgrade
                ,sum(IF(b.grade='F',1,0)) as 	fgrade
				,sum(IF(b.grade='I',1,0)) as 	igrade
                from cbcs_marks_master a inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
				$extrajoin
                where a.`session`='$session' and a.session_year='$session_year' and a.subject_id='$sub_code' $extraClouse";
          $query = $this->db->query($sql);
        //  echo  $this->db->last_query(); die();
          if ($this->db->affected_rows() > 0) {

              return $query->result();
          } else {
              return false;
          }
      }
      public function get_subject_list($emp_id,$dept_id,$session,$session_year){
    /* 18-11-19    $sql="
      (
  SELECT c.id AS cc_id,a.course_id,a.branch_id , a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
  acc.exam_type as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
  WHERE aa.session_year='$session_year' AND aa.`session`='$session' AND aa.sub_code=a.sub_code and aa.status=1 and aa.sub_offered_id !='0' ) as submit_cnt,
  count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
  ,(select sum(x.coordinator) from cbcs_subject_offered_desc x where x.sub_id=a.sub_code and x.sub_offered_id=a.id) as cnt_mrks_send_ToC,
  concat('c',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'$session' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
  FROM cbcs_subject_offered a
  INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
  INNER JOIN user_details ud ON b.emp_no=ud.id
  INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
  LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.instructor_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session` and concat('c',a.id)=c.sub_offered_id
  WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
  GROUP BY a.id) UNION (

  SELECT c.id AS cc_id,a.course_id,a.branch_id , a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
  acc.exam_type as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
  WHERE aa.session_year='$session_year' AND aa.`session`='$session' AND aa.sub_code=a.sub_code  and aa.status=1 and aa.sub_offered_id !='0' ) as submit_cnt,

  count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
  ,(select sum(x.coordinator) from old_subject_offered_desc x where x.sub_id=a.sub_code and x.sub_offered_id=a.id) as cnt_mrks_send_ToC,
  concat('o',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'Monsoon' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name,'-', b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
  FROM old_subject_offered a
  INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
  INNER JOIN user_details ud ON b.emp_no=ud.id
  INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
  LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.instructor_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session` and concat('o',a.id)=c.sub_offered_id
  WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
  GROUP BY a.id)
      ";
// change on 24-09-19 for moduler changes
/*      $sql="
    (
SELECT c.id AS cc_id, a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
(select if(x.before_mid=c.sub_code,'before_mid','after_mid') from cbcs_modular_paper_details x where x.before_mid in(c.sub_code) or x.after_mid in(c.sub_code)
and x.session_year=c.session_year and x.`session`=c.`session` limit 1) as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
WHERE aa.session_year='2019-2020' AND aa.`session`='Monsoon' AND aa.sub_code=a.sub_code and aa.status=1) as submit_cnt,
count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
,concat('c',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'$session' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details ud ON b.emp_no=ud.id
INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.instructor_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session`
WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
GROUP BY a.sub_code) UNION (

SELECT c.id AS cc_id, a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
(select if(x.before_mid=c.sub_code,'before_mid','after_mid') from cbcs_modular_paper_details x where x.before_mid in(c.sub_code) or x.after_mid in(c.sub_code)
and x.session_year=c.session_year and x.`session`=c.`session` limit 1) as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
WHERE aa.session_year='$session_year' AND aa.`session`='$session' AND aa.sub_code=a.sub_code and aa.status=1) as submit_cnt,

count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
,concat('o',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'Monsoon' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name,'-', b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
INNER JOIN user_details ud ON b.emp_no=ud.id
INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.instructor_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session`
WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
GROUP BY a.sub_code)
    ";*/
	
	
      $sql="
      (
  SELECT c.dean_ac_status,c.id AS cc_id,a.course_id,a.branch_id , a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
  acc.exam_type as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
  WHERE aa.session_year='$session_year' AND aa.`session`='$session' AND aa.sub_code=a.sub_code and aa.status=1 and (case when a.sub_type='Modular' then concat('c',a.id)=aa.sub_offered_id else 1=1 end) ) as submit_cnt,
  count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
  ,(
select ((SELECT IF(SUM(x.coordinator) is null,0,SUM(x.coordinator))
FROM cbcs_subject_offered_desc x
inner join cbcs_subject_offered xx on x.sub_offered_id=xx.id
WHERE x.sub_id=a.sub_code and xx.session_year='$session_year' and xx.`session`='$session' AND (CASE WHEN a.sub_type='Modular' THEN x.sub_offered_id=a.id ELSE 1=1 END)) + 
(SELECT IF(SUM(x.coordinator) is null,0,SUM(x.coordinator))
FROM old_subject_offered_desc x
inner join old_subject_offered xx on x.sub_offered_id=xx.id
WHERE x.sub_id=a.sub_code and xx.session_year='$session_year' and xx.`session`='$session' AND (CASE WHEN a.sub_type='Modular' THEN x.sub_offered_id=a.id ELSE 1=1 END))



)) as cnt_mrks_send_ToC,
  concat('c',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'$session' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
  FROM cbcs_subject_offered a
  INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
  INNER JOIN user_details ud ON b.emp_no=ud.id
  INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
  LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.coordinator_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session` and concat('c',a.id)=c.sub_offered_id and c.`status` !=2
  WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
  GROUP BY a.id) UNION (

  SELECT c.dean_ac_status,c.id AS cc_id,a.course_id,a.branch_id , a.sub_code,c.marks_master_id,a.id as sub_offered_ids,a.sub_type,
  acc.exam_type as ex_type,(select count(aa.id) from cbcs_marks_send_to_coordinator aa
  WHERE aa.session_year='$session_year' AND aa.`session`='$session' AND aa.sub_code=a.sub_code  and aa.status=1 and (case when a.sub_type='Modular' then concat('c',a.id)=aa.sub_offered_id else 1=1 end) ) as submit_cnt,

  count(DISTINCT(CONCAT_WS('/',a.dept_id,a.branch_id,a.course_id,a.semester))) as cnt_offer_in_dept
  ,(
select ((SELECT IF(SUM(x.coordinator) is null,0,SUM(x.coordinator))
FROM cbcs_subject_offered_desc x
inner join cbcs_subject_offered xx on x.sub_offered_id=xx.id
WHERE x.sub_id=a.sub_code and xx.session_year='$session_year' and xx.`session`='$session' AND (CASE WHEN a.sub_type='Modular' THEN x.sub_offered_id=a.id ELSE 1=1 END)) + 
(SELECT IF(SUM(x.coordinator) is null,0,SUM(x.coordinator))
FROM old_subject_offered_desc x
inner join old_subject_offered xx on x.sub_offered_id=xx.id
WHERE x.sub_id=a.sub_code and xx.session_year='$session_year' and xx.`session`='$session' AND (CASE WHEN a.sub_type='Modular' THEN x.sub_offered_id=a.id ELSE 1=1 END))



)) as cnt_mrks_send_ToC,
  concat('o',a.id) AS sub_offerd_id,a.sub_name,b.emp_no,'Monsoon' AS session,'$session_year' AS session_year, GROUP_CONCAT(DISTINCT(CONCAT_WS(',', CONCAT_WS(' / ',a.dept_id,a.branch_id,a.course_id,a.semester)))) AS offered_to, GROUP_CONCAT(CONCAT_WS(' - ', CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name,'-', b.emp_no))) AS name, IF(acc.`status`,(CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name, '-',b.emp_no)), 'Not Assigned') AS course_coordinator
  FROM old_subject_offered a
  INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
  INNER JOIN user_details ud ON b.emp_no=ud.id
  INNER JOIN cbcs_assign_course_coordinator acc ON b.emp_no=acc.co_emp_id AND a.sub_code=acc.sub_code AND a.dept_id=acc.offered_to and b.sub_offered_id=acc.sub_offered_id
  LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND b.emp_no=c.coordinator_emp_id AND a.session_year=c.session_year AND a.`session`=c.`session` and concat('o',a.id)=c.sub_offered_id and c.`status` !=2
  WHERE a.session_year='$session_year' AND a.`session`='$session' AND acc.co_emp_id='$emp_id'
  GROUP BY a.id)
      ";
	
          $query = $this->db->query($sql);
          if ($this->db->affected_rows() > 0) {
        //   echo  $this->db->last_query();
              return $query->result();
          } else {
              return false;
          }
      }

      function getDownloadDataDeptWise($sub_code,$session,$session_year,$exam_type,$offered_id,$sub_type,$course_id,$branch_id){
        $offered_id=substr($offered_id,1);
        if($sub_type=='Modular'  && $course_id=='comm' && $branch_id=='comm'){
          $extraJoincbcs="inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id";
          $extraJoinold="inner join old_subject_offered_desc b on a.id=b.sub_offered_id";
          $extraColumn=",b.section";
          $groupby="";
          $extraClouse="";
		  $modjoin="inner join cbcs_modular_paper_details c on a.sub_code in (c.$exam_type) and c.section=b.section";
        //  $extraClouse="and a.id='$offered_id'";
        }else{
          $groupby="group by sub_offered_id";
          $extraClouse="";
        }
//note use GROUP by sub_offered_id for getting report for all branches
        $sql="(select concat('c',a.id) as sub_offered_id $extraColumn,a.sub_code,a.course_id,a.branch_id,a.dept_id, a.sub_name,a.`session`,a.session_year
from cbcs_subject_offered a
$extraJoincbcs
$modjoin
where  a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse $groupby)
UNION 
(select concat('o',a.id) as sub_offered_id $extraColumn,a.sub_code,a.course_id,a.branch_id,a.dept_id,a.sub_name,a.`session`,a.session_year
from old_subject_offered a
$extraJoinold
$modjoin
where  a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse $groupby)";
              $query = $this->db->query($sql);
              //  echo  $this->db->last_query(); die();
              if ($this->db->affected_rows() > 0) {

                  return $query->result();
              } else {
                  return false;
              }
      }

      function getDownloadData($sub_code,$session,$session_year,$exam_type,$offered_id,$sub_type){
        $offered_id=substr($offered_id,1);
        if($sub_type=='Modular'){
          $extraJoincbcs="inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id";
          $extraJoinold="inner join old_subject_offered_desc b on a.id=b.sub_offered_id";
          $extraColumn=",b.section";
          $groupby="group by a.sub_code";
          $extraClouse="and a.id='$offered_id'";
        }else{
          $groupby="group by a.sub_code"; //"group by sub_offered_id"
          $extraClouse="";
        }
//note use GROUP by sub_offered_id for getting report for all branches
        $sql="(select concat('c',a.id) as sub_offered_id $extraColumn,a.sub_code,a.course_id,a.branch_id,a.dept_id, a.sub_name,a.`session`,a.session_year
from cbcs_subject_offered a
$extraJoincbcs
where  a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse $groupby)
UNION All
(select concat('o',a.id) as sub_offered_id $extraColumn,a.sub_code,a.course_id,a.branch_id,a.dept_id,a.sub_name,a.`session`,a.session_year
from old_subject_offered a
$extraJoinold
where  a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' $extraClouse $groupby)";
              $query = $this->db->query($sql);
              //  echo  $this->db->last_query(); die();
              if ($this->db->affected_rows() > 0) {

                  return $query->result();
              } else {
                  return false;
              }
      }

      function updateMarksStatus($sub_code,$session,$session_year){
        $sql="update cbcs_marks_master set status='Y' where session='$session' and session_year='$session_year' and subject_id='$sub_code'";
        $query = $this->db->query($sql);
        if($this->db->affected_rows() != 1){
        // /  echo  $this->db->last_query(); die();
            return true;

        } else {
          return false;
        }
      }
      function getSubjectInfoforDownloadDeptWise($sub_code,$subject_offer_id,$session,$session_year,$sub_type,$section,$examtype,$course_id,$branch_id){
       $empid=$this->session->userdata("id");
       // echo $sub_type; exit;
		
		$subject_offer_id= substr($subject_offer_id,1);
        if($sub_type=="Modular"){
        //  $innerJoin="inner join cbcs_modular_paper_details e on a.admn_no=e.admn_no and d.section=e.section and a.subject_code = e.$examtype";
          //and d.emp_no='$empid'
          $groupby="group by a.subject_code";
		 
		  
        }
		 if($sub_type=="Modular" && $course_id=='comm' && $branch_id=='comm'){
			 $extraClouse="and e.section='$section'";
              $innerJoin="inner join cbcs_modular_paper_details e on a.admn_no=e.admn_no and d.section=e.section and a.subject_code = e.$examtype";
			   $extraCo="and z.dept='$section'";

            }
        $sql="
        select p.* from (select a.subject_code as sub_code,a.subject_name as sub_name ,a.course AS course_name,a.branch AS branch_name,a.`session`,a.session_year,f.semester,d.section,count(a.admn_no) as noofstu,z.updated_at from cbcs_stu_course a
        inner join cbcs_subject_offered f on a.sub_offered_id=f.id
        inner join cbcs_subject_offered_desc d on a.sub_offered_id=d.sub_offered_id and d.coordinator='1'
         inner join cbcs_marks_send_to_coordinator z on a.subject_code=z.sub_code and a.sub_offered_id=SUBSTRING(z.sub_offered_id,2) and a.session_year=z.session_year and a.`session`=z.`session` and z.`status`=1 $extraCo
        $innerJoin
        WHERE a.subject_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.sub_offered_id='$subject_offer_id' $extraClouse $groupby

        union

        select a.subject_code as sub_code,a.subject_name as sub_name ,a.course AS course_name,a.branch AS branch_name,a.`session`,a.session_year,f.semester,d.section,count(a.admn_no) as noofstu,z.updated_at from old_stu_course a
        inner join old_subject_offered f on a.sub_offered_id=f.id
        inner join old_subject_offered_desc d on a.sub_offered_id=d.sub_offered_id and d.coordinator='1'
       inner join cbcs_marks_send_to_coordinator z on a.subject_code=z.sub_code and a.sub_offered_id=SUBSTRING(z.sub_offered_id,2) and a.session_year=z.session_year and a.`session`=z.`session` and z.`status`=1 $extraCo
        $innerJoin
        WHERE a.subject_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.sub_offered_id='$subject_offer_id' $extraClouse $groupby) p where p.noofstu !=0 order by p.noofstu

";
        // comment on 1-10-19
        /*$sql="select a.sub_name,a.sub_code,a.semester,b.name AS course_name,c.name AS branch_name,a.`session`,a.session_year,count(a.id) as noofstu from cbcs_subject_offered a
              inner join cbcs_courses b on a.course_id=b.id
              inner join cbcs_branches c on a.branch_id=c.id
              inner join cbcs_stu_course d on a.id=d.sub_offered_id
              WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.id='$subject_offer_id'";*/
              $query = $this->db->query($sql);
            //  echo  $this->db->last_query(); die();
        //    echo  $query->num_rows();
              if ($this->db->affected_rows() > 0) {
         //    echo  $this->db->last_query(); die();
                  return $query->result();
              } else {
                  return false;
              }
      }

      function getSubjectInfoforDownload($sub_code,$subject_offer_id,$session,$session_year,$sub_type,$section,$examtype){
       $empid=$this->session->userdata("id");
        $subject_offer_id= substr($subject_offer_id,1);
        if($sub_type=="Modular"){
          $innerJoin="inner join cbcs_modular_paper_details e on a.admn_no=e.admn_no and a.subject_code = e.$examtype";
          $extraClouse="and d.section='$section'";//and d.emp_no='$empid'
          $groupby="group by a.subject_code";
        }
      /*   $sql="
        select p.* from (select a.subject_code as sub_code,a.subject_name as sub_name ,b.name AS course_name,c.name AS branch_name,a.`session`,a.session_year,f.semester,d.section,count(a.admn_no) as noofstu from cbcs_stu_course a
         inner join cbcs_courses b on a.course=b.id
        inner join cbcs_branches c on a.branch=c.id
        inner join cbcs_subject_offered f on a.sub_offered_id=f.id
        inner join cbcs_subject_offered_desc d on a.sub_offered_id=d.sub_offered_id
        $innerJoin
        WHERE a.subject_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.sub_offered_id='$subject_offer_id' $extraClouse $groupby

        union

        select a.subject_code as sub_code,a.subject_name as sub_name ,b.name AS course_name,c.name AS branch_name,a.`session`,a.session_year,f.semester,d.section,count(a.admn_no) as noofstu from old_stu_course a
         inner join cbcs_courses b on a.course=b.id
        inner join cbcs_branches c on a.branch=c.id
        inner join old_subject_offered f on a.sub_offered_id=f.id
        inner join old_subject_offered_desc d on a.sub_offered_id=d.sub_offered_id
        $innerJoin
        WHERE a.subject_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.sub_offered_id='$subject_offer_id' $extraClouse $groupby) p where p.noofstu !=0 order by p.noofstu

";
        // comment on 1-10-19
        /*$sql="select a.sub_name,a.sub_code,a.semester,b.name AS course_name,c.name AS branch_name,a.`session`,a.session_year,count(a.id) as noofstu from cbcs_subject_offered a
              inner join cbcs_courses b on a.course_id=b.id
              inner join cbcs_branches c on a.branch_id=c.id
              inner join cbcs_stu_course d on a.id=d.sub_offered_id
              WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.id='$subject_offer_id'";*/

                $sql="
                select p.* from(select a.*,count(a.admn_no) as noofstu from cbcs_stu_course a
				$innerJoin
                where a.subject_code='$sub_code' and a.session_year='$session_year' and a.`session`='$session'
                union
                select a.*,count(a.admn_no) as noofstu from old_stu_course a 
				$innerJoin
				where a.subject_code='$sub_code' and a.session_year='$session_year' and a.`session`='$session') p where p.noofstu !=0 order by p.noofstu
                ";


              $query = $this->db->query($sql);
          //     echo  $this->db->last_query(); die();
        //    echo  $query->num_rows();
              if ($this->db->affected_rows() > 0) {
              //  echo  $this->db->last_query(); die();
                  return $query->result();
              } else {
                  return false;
              }
      }
      function sendToAC($sub_code,$session,$session_year,$sub_offerd_id,$course_id,$branch_id,$sub_type){
		  if($course_id=='comm' && $branch_id=='comm'){
          $extraClouse="AND sub_offered_id='$sub_offerd_id'";
        }
        $sql="update cbcs_marks_send_to_coordinator set dean_ac_status='1' where sub_code='$sub_code' and session='$session' and session_year ='$session_year' and status='1' $extraClouse ";
              $query = $this->db->query($sql);
              if($this->db->affected_rows() != 1){
              // /  echo  $this->db->last_query(); die();
                  return "Already Send to Exam Section.";

              } else {
                return "Marks Send to Exam Section Successfully.";
              }
      }
      function getallStudentMarksfordownload($sub_code,$subject_offer_id,$session,$session_year,$sub_type,$section){
            if($sub_type=="Modular"){
              $extrajoin="inner join cbcs_modular_paper_details d on b.admn_no =d.admn_no and a.`session`=d.session and a.session_year=d.session_year";
              $extraClouse="and d.section='$section' group by d.admn_no";
            }
              $sql="select a.subject_id,b.admn_no,b.total,b.grade,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as stu_name from cbcs_marks_master a
                    inner join cbcs_marks_subject_description b on a.id=b.marks_master_id
                    inner join user_details c on b.admn_no=c.id
                    $extrajoin
                    WHERE a.subject_id='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' and a.sub_map_id='$subject_offer_id' $extraClouse";
              $query = $this->db->query($sql);
              if ($this->db->affected_rows() > 0) {
              //  echo  $this->db->last_query(); die();
                  return $query->result();
              } else {
                  return false;
              }
      }
      function marksSubmitOpenRequest($emp_id,$session_year,$session){
              $sql = "select p.*,q.dean_ac_status from (select c.dept_id,c.course_id,c.branch_id,c.semester,b.pk,b.category,concat_ws(' ',d.first_name,d.middle_name,d.last_name) as req_by, a.* from cbcs_marks_submission_reopen_req a
                inner join cbcs_marks_dist_child b on a.component_id=b.pk
                inner join cbcs_marks_dist c on a.sub_code=c.sub_code and b.id=c.id
                inner join user_details d on a.req_emp_by=d.id
                where a.`session`='$session' and a.session_year='$session_year' and a.co_emp_id='$emp_id' order by a.id desc) p
                Left join cbcs_marks_send_to_coordinator q on p.sub_code=q.sub_code and p.sub_offerd_id=q.sub_offered_id
                and p.session=q.`session` and p.session_year=q.session_year";
          $query = $this->db->query($sql);
        //    echo $this->db->last_query(); die();
          if ($query->num_rows() > 0)
              return $query->result();
          else
              return 0;
      }
      function getSubjectPlanforview($sessionyear,$session,$sub_code){
      $sql = "SELECT b.category,b.pk,b.wtg
      FROM cbcs_marks_dist a
      INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
      WHERE a.session_year='$sessionyear' AND a.`session`='$session' AND a.sub_code='$sub_code' group by b.category order by b.category asc";
        $query = $this->db->query($sql);
        //  echo $this->db->last_query(); //die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
      }

      function get_submitted_marks_list($session,$session_year,$sub_code,$sub_type,$examtype,$course_id,$branch_id){
		    if($sub_type=='Modular' && $course_id=='comm' && $branch_id=='comm'){
          $extrajoin="INNER JOIN cbcs_modular_paper_details f on d.subject_id in (f.$examtype) and e.admn_no=f.admn_no and d.`session`=f.`session` and d.session_year=f.session_year";
        }
		  
        $sql = "SELECT a.*, GROUP_CONCAT(CONCAT_WS(',',b.category_name)) AS category_name, GROUP_CONCAT(b.marks) AS marks,e.total,e.grade, CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS name
FROM cbcs_marks_upload a
INNER JOIN cbcs_marks_upload_description b ON a.id=b.marks_id
INNER JOIN user_details c ON a.admn_no=c.id
INNER JOIN cbcs_marks_master d on a.subject_code=d.subject_id and a.session_year=d.session_year and a.session=d.session
INNER JOIN cbcs_marks_subject_description e on d.id=e.marks_master_id and a.admn_no=e.admn_no
$extrajoin
WHERE a.session_year='$session_year' AND a.session='$session' AND a.subject_code='$sub_code'
GROUP BY a.admn_no";
          $query = $this->db->query($sql);
        //    echo $this->db->last_query(); die();
          if ($query->num_rows() > 0)
              return $query->result();
          else
              return 0;
      }

      function get_submitted_marks_list_dept_wise($session,$session_year,$sub_code,$subject_offer_id,$exam_type,$sub_type,$section,$course_id,$branch_id){
		//	echo $sub_type;
		if($sub_type=='Modular' && $course_id=='comm' && $branch_id=='comm'){
          $extrajoin="INNER JOIN cbcs_modular_paper_details f on d.subject_id in (f.$exam_type) and e.admn_no=f.admn_no and d.`session`=f.`session` and d.session_year=f.session_year";
		  $extraClouse="and f.section='$section'";
        }

	  $sql = "SELECT a.*, GROUP_CONCAT(CONCAT_WS(',',b.category_name)) AS category_name, GROUP_CONCAT(b.marks) AS marks,e.total,e.grade, CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS name
FROM cbcs_marks_upload a
INNER JOIN cbcs_marks_upload_description b ON a.id=b.marks_id
INNER JOIN user_details c ON a.admn_no=c.id
INNER JOIN cbcs_marks_master d on a.subject_code=d.subject_id and a.session_year=d.session_year and a.session=d.session
INNER JOIN cbcs_marks_subject_description e on d.id=e.marks_master_id and a.admn_no=e.admn_no
$extrajoin
WHERE a.session_year='$session_year' AND a.session='$session' AND a.subject_code='$sub_code' and a.sub_offered_id='$subject_offer_id' and d.sub_map_id='$subject_offer_id' $extraClouse
GROUP BY a.admn_no";
          $query = $this->db->query($sql);
           // echo $this->db->last_query(); die();
          if ($query->num_rows() > 0)
              return $query->result();
          else
              return 0;
      }


      function getInstructor($session_year,$session,$sub_code){
        $sql = "select a.sub_code,b.coordinator,b.emp_no,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as emp_name from cbcs_subject_offered a
inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id and a.sub_code=b.sub_id
inner join user_details c on b.emp_no=c.id
where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$sub_code' and b.coordinator=1
union
select a.sub_code,b.coordinator,b.emp_no,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as emp_name from old_subject_offered a
inner join old_subject_offered_desc b on a.id=b.sub_offered_id and a.sub_code=b.sub_id
inner join user_details c on b.emp_no=c.id
where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$sub_code' and b.coordinator=1";
          $query = $this->db->query($sql);
          //  echo $this->db->last_query(); die();
          if ($query->num_rows() > 0)
              return $query->result();
          else
              return 0;
      }
	  
	  
	  	  
// @author:rituraj  @dsc: sending  data to foil & freeze

	   function send_to_foil($sub_code,$session,$session_year,$dept=null,$course=null,$branch=null,$sem=null,$admn_no=null,$param=null,$start=null,$eachset=null){		   		   
	     $this->load->model('attendance/exam_attendance_model');
		try{
			$this->db->trans_begin();
			$returntmsg='';
          if($sub_code<>null)$txt=" a.subject_code='$sub_code' and "; else $txt="" ;
		  if($dept<>null)$append1="  and  b.dept_id='$dept' "; else $append1="" ;
		  if($course<>null)$append2="  and  b.course_id='$course' "; else $append2="" ;
		    if($branch<>null )  $append3="  and  b.branch_id='$branch' "; else $append3="" ;  //hack code for mech+te to met
		  //if($branch<>null &&   $branch<>'mech+te')  $append3="  and  b.branch_id='$branch' "; else $append3="" ;  //hack code for mech+te to met
		  //if($branch<>null &&   $branch=='mech+te')  $append3="  and  (b.branch_id='mech+te'  or b.branch_id='met' )   "; else $append3="" ;//hack code for mech+te to met
		  if($sem<>null  )$append5="  and  rg.semester='".($dept=='comm'? ($session=='Monsoon'?1:2) : $sem )."' "; else $append5="" ;
		   if($sem<>null && $dept=='comm' && $sem<>'all'){
			  $section_append="   join stu_section_data ssd on  ssd.admn_no=v.admn_no and ssd.session_year=v.session_year and  ssd.section='$sem'"; 
			  $section_select =" ,ssd.section  "; 
		  }
		 else{ 
				$section_append="" ;$section_select="";
		    }
			
			
			if($dept<>'comm')				
				$append6="  and  rg.course_id='$course'   and  rg.branch_id= '$branch'  "; 
			    else 
			    $append6="" ;
			
			
		  if($admn_no<>null)$append4="  and  a.admn_no='$admn_no' "; else $append4="" ;
        if ($admn_no == null) {
          if($param==null)
             $rep=" limit ".$start." ,".$eachset."";
          else
           $rep=''; 
        }
        else
        $rep=''; 
    
 
        $sql="SELECT v.*,COALESCE((gp.points*v.credit_hours),0) AS cr_pts , rg.semester AS  curr_sem  $section_select    FROM(
SELECT f.*,g.id AS sub_des_id,g.marks_master_id,g.total,g.grade FROM 
(

(
SELECT a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,b.semester,(CASE WHEN b.course_id='honour' THEN 'b.tech' ELSE b.course_id END) AS course_id,b.branch_id,b.dept_id,b.credit_hours,f.id AS m_m_id,a.session_year
FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON a.sub_offered_id=b.id
LEFT JOIN cbcs_marks_master f ON CONCAT('c',a.sub_offered_id)=f.sub_map_id AND a.subject_code=f.subject_id AND a.session_year=f.session_year AND a.`session`=f.`session`
WHERE $txt  a.`session`='$session' and a.session_year='$session_year'  AND b.dept_id='comm' AND b.course_id='comm' AND b.branch_id='comm'  $append4)

union

 (
  SELECT a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,b.semester,(case when b.course_id='honour' then 'b.tech' else b.course_id end ) as  course_id,b.branch_id,b.dept_id,b.credit_hours,f.id AS m_m_id,a.session_year
		
from cbcs_stu_course a
inner join cbcs_subject_offered b on a.sub_offered_id=b.id
left join cbcs_marks_master f on concat('c',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`

where $txt a.`session`='$session' and a.session_year='$session_year'  $append1 $append2 $append3 $append4 )

 union

(SELECT a.id,a.form_id,a.admn_no, CONCAT('o',a.sub_offered_id) AS sub_offered_id, a.subject_code,b.semester,(case when b.course_id='honour' then 'b.tech' else b.course_id end ) as  course_id,b.branch_id,b.dept_id,b.credit_hours,f.id AS m_m_id,a.session_year
 from old_stu_course a
inner join old_subject_offered b on a.sub_offered_id=b.id

 left join cbcs_marks_master f on concat('o',a.sub_offered_id)=f.sub_map_id and a.subject_code=f.subject_id and a.session_year=f.session_year and a.`session`=f.`session`

where $txt a.`session`='$session' and a.session_year='$session_year' $append1 $append2 $append3 $append4 )
)f
 join cbcs_marks_subject_description g on f.m_m_id=g.marks_master_id and f.admn_no=g.admn_no and g.grade is not null /*and g.grade<>'I'*/ order by f.subject_code,g.grade limit 10000000

)v
JOIN   reg_regular_form rg ON rg.form_id=v.form_id $append5 $append6 
$section_append

 left  JOIN  grade_points gp ON gp.grade=v.grade 
 
 group by  v.admn_no, v.subject_code
  ORDER BY v.dept_id,v.course_id,v.branch_id,curr_sem ,v.admn_no $rep
 ";





// echo $sql; die();

        $query = $this->db->query($sql);
		
		//echo $query->num_rows();
		if($param==null){
		
        if ($query->num_rows() > 0) {
		$affected=null;	$arr22=null;$k=0;$arr21=null;$arr25=null;$arr23=null;$arr26=null;$admn_uniqure_array=null;
    //echo  $this->db->last_query();  	  echo '<pre>';print_r($query->result()); echo '</pre>';		die();




	      foreach($query->result_array() as $row1){
			    $defaulterStatus=null;
			    $defaulterStatus=$this->exam_attendance_model->GetdefaulterStatus($row1['admn_no'], $row1['subject_code'],$row1['sub_offerd_id'],$session_year,$session);  // defaulter
				$admn_uniqure_array[]= $row1['admn_no'];
                $arr22[$k]['session_yr'] = $session_year;
                $arr22[$k]['session'] = $session;
                $arr22[$k]['dept'] = $dept;
                $arr22[$k]['course'] = $course;
                $arr22[$k]['branch'] = $branch;
                $arr22[$k]['semester'] = $row1['curr_sem'];				
				if($dept=='comm')
				$arr22[$k]['branch'] = $row1['section'];				
                $arr22[$k]['admn_no'] = $row1['admn_no'];
                $arr22[$k]['type'] = 'R';
                $arr22[$k]['exam_type'] = 'R';
				$arr22[$k]['hstatus'] =  ($row1['course_id']=='honour'?'Y':'N');
				$arr22[$k]['tot_cr_hr'] =  null;
                $arr22[$k]['tot_cr_pts'] =  null;
                $arr22[$k]['core_tot_cr_hr'] =  null;
                $arr22[$k]['core_tot_cr_pts'] =  null;
                $arr22[$k]['ctotcrpts'] =  null;
                $arr22[$k]['core_ctotcrpts'] =  null;
                $arr22[$k]['ctotcrhr'] =  null;
                $arr22[$k]['core_ctotcrhr'] =  null;
                $arr22[$k]['gpa'] =  null;
                $arr22[$k]['core_gpa'] =  null;
                $arr22[$k]['cgpa'] =  null;
                $arr22[$k]['core_cgpa'] =  null;
                $arr22[$k]['status'] =  null;
                $arr22[$k]['core_status'] = null;



				//  checking whether data preseent from before
				$sqlfirst=" select admn_no,id from final_semwise_marks_foil a where a.admn_no='".$row1['admn_no']."' and  a.session_yr='".$session_year."' and  a.session='".$session."' and a.semester='".$row1['curr_sem']."'   and a.course='".$course."' and a.branch='".( $dept=='comm'?$row1['section']:$branch)."' and a.dept='".$dept."'  ";
				 //echo $sqlfirst; die();

				 $queryfirst = $this->db->query($sqlfirst);
			//   echo $this->db->last_query(). $this->db->affected_rows().'<br/>'; 
				if ($queryfirst->num_rows()>0){
					 //	  echo $this->db->last_query(); die();
				$queryfirstdata=$queryfirst->result_array();
				$arr21[$k]['cr_hr'] = $row1['credit_hours'];
				$arr21[$k]['total'] = $row1['total'];
				$arr21[$k]['grade'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']);
				$arr21[$k]['cr_pts'] = $row1['cr_pts'];
				$arr21[$k]['foil_id'] =$queryfirstdata[0]['id'];
				$arr21[$k]['admn_no'] =  $queryfirstdata[0]['admn_no']  ;
				$arr21[$k]['sub_code'] = $row1['subject_code'];
				$arr21[$k]['current_exam'] = null;
				$arr21[$k]['remark2'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'y':null);
                 $sqlfirst1=null;
				$sqlfirst1=" select a.* from final_semwise_marks_foil_desc a where  a.foil_id='".$queryfirstdata[0]['id']."'  and   a.admn_no='".$queryfirstdata[0]['admn_no']."'  and a.sub_code= '".$row1['subject_code']."'   and  a.grade<>'I' ";
                //echo  $sqlfirst1;
			   $queryfirst2 =null;
			   
				$queryfirst2 = $this->db->query($sqlfirst1);
				 //print_r($queryfirst1->result()); die();
				  //  echo $this->db->last_query();  die();
 //echo $queryfirst2->num_rows(); die();
				 if ($queryfirst2->num_rows() == 0  ) {
                  if(!$this->db->insert('final_semwise_marks_foil_desc',  $arr21[$k]))
				   $returntmsg .= $this->db->_error_message() . ",";
                   $affected[] = $this->db->affected_rows();
				 //  echo $this->db->last_query(). $this->db->affected_rows().'<br/>';
				  }
				  else
				  {
                      $data_to_update= array('cr_hr' => $row1['credit_hours'],'total' => $row1['total'],'grade'=> ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']),'cr_pts' => $row1['cr_pts'],	'remark2' => ($defaulterStatus=='y' && $defaulterStatus!='1'?'y':null));
					
                  //print_r($queryfirst2->result_array()); die();
					
					$queryfirst1=$queryfirst2->result_array();
					
				    if(($queryfirst1[0]['cr_hr'] !=  $row1['credit_hours'])|| ( $queryfirst1[0]['total'] !=  $row1['total']) || ( $queryfirst1[0]['grade'] !=  $row1['grade'])||($queryfirst1[0]['cr_pts'] !=  $row1['cr_pts'])) {
                         //echo 'ee'. $queryfirst1[0]['total'].'#'.$row1['total'];	die();                  				                       
                     //if(
					 if(!$this->db->update('final_semwise_marks_foil_desc' ,$data_to_update,array('foil_id' =>$queryfirst1[0]['foil_id'],'sub_code'=> $row1['subject_code'] ))
					 ){
					//	 echo $this->db->last_query(); echo $this->db->affected_rows().'<br/>';						die();																										
						             $returntmsg .= $this->db->_error_message() . ",";
									 $affected[] = $this->db->affected_rows();  
									// echo $this->db->last_query(); echo $this->db->affected_rows().'<br/>';
								   }
                                      
									   
								   }
				  }  
				  
				  
				 // freeze handling

				 	//  checking whether data preseent from before
				$sqlfirst_freeze="select * from final_semwise_marks_foil_freezed a where a.admn_no='".$row1['admn_no']."' and  a.session_yr='".$session_year."' and  a.session='".$session."' and a.semester='".$row1['curr_sem']."'   and a.course='".$course."' and a.branch='".( $dept=='comm'?$row1['section']:$branch)."' and a.dept='".$dept."'  ";
				
				 //echo $sqlfirst; die();

				 $queryfirst_freeze = $this->db->query($sqlfirst_freeze);
				 $queryfirstdata_freeze=$queryfirst_freeze->result_array();

				if ($queryfirst_freeze->num_rows()>0){
                    if($queryfirstdata_freeze[0]['published_on']=='') {

					 //	  echo $this->db->last_query(); die();
				
				$arr26[$k]['cr_hr'] = $row1['credit_hours'];
				$arr26[$k]['total'] = $row1['total'];
				$arr26[$k]['grade'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']);
				$arr26[$k]['cr_pts'] = $row1['cr_pts'];
				$arr26[$k]['foil_id'] =$queryfirstdata_freeze[0]['id'];
				$arr26[$k]['old_foil_id'] =$queryfirstdata_freeze[0]['old_id'];
				$arr26[$k]['admn_no'] =  $queryfirstdata_freeze[0]['admn_no']  ;
				$arr26[$k]['sub_code'] = $row1['subject_code'];
				$arr26[$k]['current_exam'] = null;
				$arr26[$k]['remark2'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'y':null);

				$sqlfirst1_freeze=" select * from final_semwise_marks_foil_desc_freezed a where  a.foil_id='".$queryfirstdata_freeze[0]['id']."'  and   a.admn_no='".$queryfirstdata_freeze[0]['admn_no']."'  and a.sub_code= '".$row1['subject_code']."'   and  a.grade<>'I' ";

				$queryfirst2_freeze = $this->db->query($sqlfirst1_freeze);

				 if ($queryfirst2_freeze->num_rows() == 0) {
                  if(!$this->db->insert('final_semwise_marks_foil_desc_freezed',  $arr26[$k]))
					        $returntmsg .= $this->db->_error_message() . ",";
                               $affected[] = $this->db->affected_rows();
							//   echo $this->db->last_query(). $this->db->affected_rows().'<br/>';
							   
							   // update time of publish on each subject arrival
							   
							   /*$this->db->select('id'); $this->db->from('final_semwise_marks_foil_freezed');$this->db->where(array('id' =>$queryfirstdata_freeze[0]['id'],'published_on !='=> '' ) ); 
							   $query_update = $this->db->get();
                               if ( $query_update->num_rows() > 0 ){
					               $this->db->where(array('id' =>$queryfirstdata_freeze[0]['id'],'published_on !='=> '' ));
                                   if(!$this->db->update('final_semwise_marks_foil_freezed', array('published_on'=>date("Y-m-d")  ,'actual_published_on'=>date("Y-m-d H:i:s")))){
							        // echo $this->db->last_query(). $this->db->affected_rows().'<br/>';
						             $returntmsg .= $this->db->_error_message() . ",";
								   }
                                      $affected[] = $this->db->affected_rows();
				                 }                */
					           // end

				  }
				  
				  else
				  { // update in  case  grade not released
					 $this->db->select('id'); $this->db->from('final_semwise_marks_foil_freezed');
					 $this->db->where(array('id' =>$queryfirstdata_freeze[0]['id'],'published_on'=> '' ) ); 
					  $query_update = $this->db->get();
                      if ( $query_update->num_rows() > 0 ){					  					  
                          $data_to_update_freeze= array('cr_hr' => $row1['credit_hours'],'total' => $row1['total'],'grade'=> ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']),'cr_pts' => $row1['cr_pts']);
					  $queryfirst1_freeze=$queryfirst2_freeze->result_array();
					  
				      if (($queryfirst1_freeze[0]['cr_hr'] !=  $row1['credit_hours'])|| ( $queryfirst1_freeze[0]['total'] !=  $row1['total'] )|| ( $queryfirst1_freeze[0]['grade'] !=  $row1['grade'])||($queryfirst1_freeze[0]['cr_pts'] !=  $row1['cr_pts'])) {
                     //    echo '<pre>';  print_r($data);  echo '</pre>'; 		die();                  				                       
                        if(!$this->db->update('final_semwise_marks_foil_desc' ,$data_to_update_freeze,array('foil_id' =>$queryfirst1_freeze[0]['foil_id'],'sub_code'=> $row1['subject_code'] ))){//	 echo$this->db->last_query().$this->db->affected_rows().'<br/>';																																
						             $returntmsg .= $this->db->_error_message() . ",";
									 $affected[] = $this->db->affected_rows(); 
                                    // echo $this->db->last_query(); echo $this->db->affected_rows().'<br/>';		
								   }
                                      							  
								   }
						}
												
				  }  
				  
				  
					} 
						 												
					}													
				 
				 // end of handlimng freeze
			  }
				else{
				//	echo $this->db->last_query(); die();
                if(!$this->db->insert("final_semwise_marks_foil", $arr22[$k]))
			   $returntmsg .= $this->db->_error_message() . ",";
                $affected[] = $this->db->affected_rows();
			//	echo $this->db->last_query(). $this->db->affected_rows().'<br/>';
					  //echo $this->db->last_query(); die();
				$arr25[$k]['session_yr'] = $session_year;
                $arr25[$k]['session'] = $session;
                $arr25[$k]['dept'] = $dept;
                $arr25[$k]['course'] = $course;
                $arr25[$k]['branch'] = $branch;
                $arr25[$k]['semester'] = $row1['curr_sem'];
					if($dept=='comm')
				$arr25[$k]['branch'] = $row1['section'];		
                $arr25[$k]['admn_no'] = $row1['admn_no'];
                $arr25[$k]['type'] = 'R';
                $arr25[$k]['exam_type'] = 'R';
				$arr25[$k]['hstatus'] =  ($row1['course_id']=='honour'?'Y':'N');
				$arr25[$k]['tot_cr_hr'] =  null;
                $arr25[$k]['tot_cr_pts'] =  null;
                $arr25[$k]['core_tot_cr_hr'] =  null;
                $arr25[$k]['core_tot_cr_pts'] =  null;
                $arr25[$k]['ctotcrpts'] =  null;
                $arr25[$k]['core_ctotcrpts'] =  null;
                $arr25[$k]['ctotcrhr'] =  null;
                $arr25[$k]['core_ctotcrhr'] =  null;
                $arr25[$k]['gpa'] =  null;
                $arr25[$k]['core_gpa'] =  null;
                $arr25[$k]['cgpa'] =  null;
                $arr25[$k]['core_cgpa'] =  null;
                $arr25[$k]['status'] =  null;
                $arr25[$k]['core_status'] = null;
				$arr25[$k]['published_on'] = null;
                $arr25[$k]['actual_published_on'] = null;
                $arr25[$k]['result_dec_id'] = null;


                $arr25[$k]['old_id']=$curr22 = $this->db->insert_id();
				if(!$this->db->insert("final_semwise_marks_foil_freezed", $arr25[$k]))
			      $returntmsg .= $this->db->_error_message() . ",";
                  $affected[] = $this->db->affected_rows();
				//  echo $this->db->last_query(). $this->db->affected_rows().'<br/>';
				$curr23_frz = $this->db->insert_id();

				$arr21[$k]['cr_hr'] = $row1['credit_hours'];
				$arr21[$k]['total'] = $row1['total'];
				$arr21[$k]['grade'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']);
				$arr21[$k]['cr_pts'] = $row1['cr_pts'];
				$arr21[$k]['foil_id'] =$curr22;
				$arr21[$k]['admn_no'] = $row1['admn_no']  ;
				$arr21[$k]['sub_code'] = $row1['subject_code'];
				$arr21[$k]['current_exam'] = null;
				$arr21[$k]['remark2'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'y':null);
               if(! $this->db->insert('final_semwise_marks_foil_desc',  $arr21[$k]))
				         $returntmsg .= $this->db->_error_message() . ",";
                $affected[] = $this->db->affected_rows();
				//echo $this->db->last_query(). $this->db->affected_rows().'<br/>';

				$arr23[$k]['cr_hr'] = $row1['credit_hours'];
				$arr23[$k]['total'] = $row1['total'];
				$arr23[$k]['grade'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'F':$row1['grade']);
				$arr23[$k]['cr_pts'] = $row1['cr_pts'];
				$arr23[$k]['foil_id'] =$curr23_frz;
				$arr23[$k]['old_foil_id'] =$curr22;
				$arr23[$k]['admn_no'] = $row1['admn_no']  ;
				$arr23[$k]['sub_code'] = $row1['subject_code'];
				$arr23[$k]['current_exam'] = null;
				$arr23[$k]['remark2'] = ($defaulterStatus=='y' && $defaulterStatus!='1'?'y':null);


				if(!$this->db->insert('final_semwise_marks_foil_desc_freezed',  $arr23[$k]))
					      $returntmsg .= $this->db->_error_message() . ",";
                        $affected[] = $this->db->affected_rows();
						//echo $this->db->last_query(). $this->db->affected_rows().'<br/>';

				}


			$k++;
		} 
		       if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {
                    //if($this->db->trans_status()!= FALSE ) {
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
                
//			 echo '<pre>';	print_r($affected);echo '</pre>';   echo $returntmsg ;  die();

			return  ($returntmsg== "failed"?0:($k).'_'.(count(array_unique($admn_uniqure_array))) );
        } else {
            return 0;
        }
     }// end of param
	 else
	     return $query->num_rows();
		 
	 
    		
	 } catch (Exception $e) { //echo 'tt'. $e->getMessage(); die();
        //   throw new Exception(0);
           // return $e->getMessage() == null ? 'Internal error ocuured' : $e->getMessage();
		 //return  0;
	    //echo  $e->getMessage() == null ? 'Internal error ocuured' : 'error:'.$e->getMessage();
		 
		 throw new Exception($e->getMessage() == null ? 'Internal error ocuured' : 'error:'.$e->getMessage());
        }


      }

	   
	   function update_freeze($session,$session_year,$dept,$course,$branch){
			  $this->load->model('attendance/exam_attendance_model');
			  // echo $session.','.$session_year.','.$dept.','.$course.','.$branch; die();
			  
			  
		try{
			$returntmsg='';                
	     		    
				//  checking whether data preseent from before
				$sqlfirst_freeze="select admn_no,id ,old_id from final_semwise_marks_foil_freezed a where   a.session_yr='".$session_year."' and  a.session='".$session."'  and    
				 a.course='".$course."' and a.branch='".$branch."' and a.dept='".$dept."' ";
				 //echo $sqlfirst; die();
				 $queryfirst_freeze = $this->db->query($sqlfirst_freeze);
				if ($queryfirst_freeze->num_rows()>0){                                             
					 //	  echo $this->db->last_query(); die();
				$queryfirstdata_freeze=$queryfirst_freeze->result_array();
				$k=0;
				foreach($queryfirstdata_freeze as $row1){
					
				         $this->db->where(array('id' =>$row1[$k]['id'],'published_on !='=> '' ));
                         if(!$this->db->update('final_semwise_marks_foil_freezed', array('published_on'=>date("Y-m-d")  ,'actual_published_on'=>date("Y-m-d H:i:s"))))
							   //echo $this->db->last_query(); die();
						    $returntmsg .= $this->db->_error_message() . ",";
                            $affected[] = $this->db->affected_rows();	
								 // end of handlimng freeze
                  $k++;
				}                  						
		       
		       if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {
                    //if($this->db->trans_status()!= FALSE ) {
                    $this->db->trans_rollback();
                    $returntmsg = "failed";
                } else {
                    $returntmsg = "success";
                    $this->db->trans_commit();
                }
		   
		     	return  ($returntmsg== "failed"?0:($k-1));			        
				
			}		
		    else 
                return 0;        
				} catch (Exception $e) {
            return $e->getMessage() == null ? 'Internal error ocuured' : $e->getMessage();        
				}
      }
	   


	   function marks_monitor($session,$session_year,$dept=null,$course=null,$branch=null,$admn_no=null){
		  if($dept<>null)$append1="  and  a.dept='$dept' "; else $append1="" ;
		  if($dept<>null)$append0="  and  a.dept_id='$dept' "; else $append0="" ;
		  if($course<>null)$append2="  and  a.course='$course' "; else $append2="" ;
		  if($branch<>null)$append3="  and  a.branch='$branch' "; else $append3="" ;
		   if($admn_no<>null)$append4="  and  a.admn_no='$admn_no' "; else $append4="" ;


		   $sql="
			SELECT c.std_dept   , cd.name,   u.dept, c.course,c.branch,cb.name AS br_name   ,c.subject_code,  c.sub_name,c.sub_type,u.published_on,c.resrc  FROM
				(SELECT v.course,v.branch,v.subject_code,v.dept_id AS std_dept, v.sub_name,v.sub_type,v.resrc
				FROM((
				SELECT  'new' AS  resrc  , a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,a.course,a.branch,b.dept_id,b.sub_name,b.sub_type
				FROM cbcs_stu_course a
				INNER JOIN cbcs_subject_offered b ON a.sub_offered_id=b.id
				WHERE a.`session`=? AND a.session_year=? $append0 $append2 $append3 $append4  )
				 UNION (
				SELECT 'old' AS  resrc   , a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,a.course,a.branch,b.dept_id,b.sub_name,b.sub_type
				FROM old_stu_course a
				INNER JOIN old_subject_offered b ON a.sub_offered_id=b.id
				WHERE a.`session`=? AND a.session_year=?   $append0 $append2 $append3 $append4 ))v GROUP BY v.course,v.branch ,v.subject_code)c

				left JOIN(
				SELECT a.*,f.sub_code FROM
               (SELECT a.*  FROM final_semwise_marks_foil_freezed a  WHERE a.`session`=?  and a.session_yr=? $append1 $append2 $append3 $append4) a
                 JOIN final_semwise_marks_foil_desc_freezed f ON  f.foil_id=a.id
                 GROUP BY a.dept,a.course,a.branch,f.sub_code )u

				 ON u.course=c.course  AND  u.branch=c.branch and  u.sub_code=c.subject_code
				 LEFT JOIN cbcs_departments cd ON cd.id=c.std_dept
                 LEFT JOIN cbcs_branches cb ON cb.id=c.branch

				 order by u.dept,u.course,u.branch,c.subject_code ";

			   $query = $this->db->query($sql,array($session,$session_year,$session,$session_year,$session,$session_year));

			  //  echo $sql;die();
		 //echo $query->num_rows();
               if ($query->num_rows() > 0) {
             //   echo  $this->db->last_query();  	  echo '<pre>';print_r($query->result()); echo '</pre>';		die();
				return $query->result();
	          }
       else  return 0;
			   }




	   function get_release_info($session,$session_year,$dept=null,$course=null,$branch=null,$sem=null,$sec=null,$admn_no=null){
		  if($dept<>null)$append1="  and  a.dept='$dept' "; else $append1="" ;
		  if($dept<>null)$append0="  and  b.dept_id='$dept' "; else $append0="" ;
		  if($course<>null)$append2="  and  a.course='$course' "; else $append2="" ;
		  if($branch<>null)$append3= "  and  a.branch='".$branch."' "; else $append3="" ;		  
		  if($branch<>null)$append3_1= "  and  a.branch='".($dept=='comm'? $sec:$branch)."' "; else $append3_1="" ;		  
		  
		     if($sem<>null  )$append5="  and  a.semester='".($dept=='comm'? ($session=='Monsoon'?1:2) : $sem )."' "; else $append5="" ;
		  if($sem<>null && $dept=='comm' && $sem<>'all'){			
			  $section_select =" ,ssd.section  "; 
		  }
		  else{ 
				$section_select="";
		    }
		  if($admn_no<>null)$append4="  and  a.admn_no='$admn_no' "; else $append4="" ;


		   $sql="
		     	SELECT c.std_dept   , cd.name,   u.dept, c.course,c.branch,cb.name AS br_name   ,c.subject_code,  c.sub_name,c.sub_type,u.published_on,c.resrc  FROM
				(SELECT v.course,v.branch,v.subject_code,v.dept_id AS std_dept, v.sub_name,v.sub_type,v.resrc
				FROM((
				SELECT  'new' AS  resrc  , a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,a.course,a.branch,b.dept_id,b.sub_name,b.sub_type
				FROM cbcs_stu_course a
				INNER JOIN cbcs_subject_offered b ON a.sub_offered_id=b.id
				WHERE a.`session`=? AND a.session_year=? $append0 $append2 $append3 $append4  )
				 UNION (
				SELECT 'old' AS  resrc   , a.id,a.form_id,a.admn_no, CONCAT('c',a.sub_offered_id) AS sub_offered_id, a.subject_code,a.course,a.branch,b.dept_id,b.sub_name,b.sub_type
				FROM old_stu_course a
				INNER JOIN old_subject_offered b ON a.sub_offered_id=b.id
				WHERE a.`session`=? AND a.session_year=?   $append0 $append2 $append3 $append4 ))v GROUP BY v.course,v.branch)c

				left JOIN
				(SELECT a.* FROM  final_semwise_marks_foil_freezed a WHERE  a.`session`=?  and a.session_yr=? $append1 $append2 $append3_1 $append4
				 GROUP BY a.dept,a.course,a.branch)u
				 ON u.course=c.course  AND  u.branch=c.branch
				 LEFT JOIN cbcs_departments cd ON cd.id=c.std_dept
                 LEFT JOIN cbcs_branches cb ON cb.id=c.branch

				 order by u.dept,u.course,u.branch ";

			   $query = $this->db->query($sql,array($session,$session_year,$session,$session_year,$session,$session_year));
/*
SELECT b.dept_id, a.course,a.branch,  c.semester,  (case when a.course='comm' then  ssd.section end ) FROM old_stu_course a 
INNER JOIN user_details b ON b.id=a.admn_no

left JOIN stu_section_data ssd ON ssd.admn_no=a.admn_no and ssd.session_year=a.session_year

left JOIN old_subject_offered c ON c.id=a.sub_offered_id
WHERE a.session_year='2019-2020' AND a.`session`='Monsoon'-- AND a.course<>'comm'

GROUP BY a.course,a.branch,c.semester, (case when a.course='comm' then  ssd.section end )
UNION
SELECT b.dept_id, a.course,a.branch,c.semester,ssd.section FROM cbcs_stu_course a 
INNER JOIN user_details b ON b.id=a.admn_no

left  JOIN stu_section_data ssd ON ssd.admn_no=a.admn_no and ssd.session_year=a.session_year

left JOIN cbcs_subject_offered c ON c.id=a.sub_offered_id
WHERE a.session_year='2019-2020' AND a.`session`='Monsoon' -- AND a.course<>'comm'
GROUP BY  a.course,a.branch,c.semester,ssd.section

*/


//			    echo $sql;die();
		 //echo $query->num_rows();
               if ($query->num_rows() > 0) {
               //echo  $this->db->last_query();  	  echo '<pre>';print_r($query->result()); echo '</pre>';		die();
				return $query->result();
	          }
       else  return 0;
			   }


	  

}

?>
