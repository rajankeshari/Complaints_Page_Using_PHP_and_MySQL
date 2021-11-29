<?php

class Exam_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    function get_session_year($id){
        $sql = "select * from mis_session_year order by id desc";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

    function get_session($id){
        $sql = "select * from mis_session";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }
    }

    function GetdefaulterStatus($admn_no,$sub_code,$sub_offerd_id,$session_year,$session){
      $sql="select a.* from cbcs_absent_table_defaulter a where a.admn_no='$admn_no' and a.sub_code='$sub_code' and a.session_year='$session_year' and a.`session`='$session'";
      $query = $this->db->query($sql);
    //    echo $this->db->last_query(); die();
      if ($query->num_rows() > 0){
      return $query->row()->def_status;
    }  else{
      return 1;
    }
    }


    function get_faculty_list($dept_id){
      $sql="select a.id,concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name) as ft_name from user_details a
        inner join users b on a.id=b.id
        where
        a.dept_id='$dept_id' and b.auth_id='emp'
      ";

  $query = $this->db->query($sql);
  //  echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
    }

    function get_dept_list(){
      $sql="select * from departments where type='academic'";

  $query = $this->db->query($sql);
  //  echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return false;
    }

    function getsubjectList($emp_id,$session_year,$session){
      $sql="
      (
     SELECT (case when b.sub_type = 'Modular' then IF(x.before_mid!='na','before_mid','after_mid') else null end) as mod_type ,b.*,'' as map_id,a.*,c.name as dept_name,d.name as course_name,e.name as branch_name
     FROM cbcs_subject_offered_desc a
     INNER JOIN cbcs_subject_offered b ON a.sub_offered_id=b.id
     inner join cbcs_departments c on b.dept_id=c.id
     inner join cbcs_courses d on b.course_id=d.id
     inner join cbcs_branches e on b.branch_id=e.id
     LEFT join cbcs_modular_paper_details x on b.sub_code = (IF(x.before_mid='na',x.after_mid,x.before_mid)) and a.section=x.section and b.course_id=b.course_id
     WHERE a.emp_no='$emp_id' AND b.session_year='$session_year' AND b.`session`='$session') UNION (
     SELECT IF(x.before_mid=null,x.after_mid,x.before_mid) as mod_type,b.*,a.*,c.name as dept_name,d.name as course_name,e.name as branch_name
     FROM old_subject_offered_desc a
     INNER JOIN old_subject_offered b ON a.sub_offered_id=b.id
     inner join cbcs_departments c on b.dept_id=c.id
     inner join cbcs_courses d on b.course_id=d.id
     inner join cbcs_branches e on b.branch_id=e.id
     LEFT join cbcs_modular_paper_details x on b.sub_code =(IF(x.before_mid='na',x.after_mid,x.before_mid))
     WHERE a.emp_no='$emp_id' AND b.session_year='$session_year' AND b.`session`='$session')
      ";

  $query = $this->db->query($sql);
  #  echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
    }




function getStudentList($sub_code,$sub_offerd_id,$session,$session_year,$section,$mod_type){
	if($section!=""){
		$extraClouse="AND a.sub_category_cbcs_offered='$section'";
		$extraClousemod="AND  y.`section`='$section'";
	}else{
		$extraClouse="";
	}
  if($mod_type!=null || $mod_type!='' ){
  $sql="
select y.*,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as stu_name from cbcs_modular_paper_details y
inner join user_details c on y.admn_no=c.id
where y.$mod_type='$sub_code' $extraClousemod order by y.admn_no";
}else{
  $sql="
  select x.* from((select a.*,concat_ws(' ',x.first_name,x.middle_name,x.last_name) as stu_name from cbcs_stu_course a
inner join user_details x on a.admn_no=x.id
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.session_year='$session_year' and a.`session`='$session' 
$extraClouse
order by a.admn_no)
union
(select a.*,concat_ws(' ',x.first_name,x.middle_name,x.last_name) as stu_name from old_stu_course a
inner join user_details x on a.admn_no=x.id
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.session_year='$session_year' and a.`session`='$session' order by a.admn_no)) x order by x.admn_no
  ";
}

$query = $this->db->query($sql);
//  echo $this->db->last_query(); die();
if ($query->num_rows() > 0)
    return $query->result();
else
    return 0;

}






}

?>
