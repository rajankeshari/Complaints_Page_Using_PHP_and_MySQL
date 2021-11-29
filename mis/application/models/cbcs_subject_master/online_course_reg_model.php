<?php

class Online_course_reg_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getApply_status($id){
      $sql="select * from cbcs_online_course_reg a where a.admn_no='$id' order by a.id desc";
      $query = $this->db->query($sql);
      //  echo  $this->db->last_query();
      if ($this->db->affected_rows() > 0) {
      //  echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }

    function Getdept($session,$session_year){
      $sql="select a.dept_id as id,b.name from cbcs_subject_offered a
            inner join cbcs_departments b on a.dept_id=b.id
            where a.session_year='$session_year' and a.`session`='$session' and a.sub_type='online'
            group by a.dept_id";
      $query = $this->db->query($sql);
      //  echo  $this->db->last_query();
      if ($this->db->affected_rows() > 0) {
      //  echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }

    function getOnlineCourse($session,$session_year,$deptId){
      $sql="
            select a.session_year,a.`session`,a.sub_code,a.sub_name,a.sub_type,a.dept_id,a.course_id,a.branch_id,a.id,b.emp_no,b.coordinator,
            concat_ws(' ',c.first_name,c.middle_name,c.last_name) as ins_name from cbcs_subject_offered a
            inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
            inner join user_details c on b.emp_no=c.id
            where a.sub_type='online' and  a.session_year='$session_year' and a.`session`='$session' and a.dept_id='$deptId'  ";
      $query = $this->db->query($sql);
      //  echo  $this->db->last_query(); die();
      if ($this->db->affected_rows() > 0) {
      //  echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }
function getStuData($session,$session_year,$stu_id){
  $sql="select * from reg_regular_form a
        where a.session_year='$session_year' and a.`session`='$session' and a.admn_no='$stu_id'";
  $query = $this->db->query($sql);
//  echo  $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0) {
  //  echo  $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}

  function saveRegisterData($insertData){
    if($this->db->insert('cbcs_online_course_reg', $insertData)){
        return true;
    }else{
      return false;
    }
  }


}
?>
