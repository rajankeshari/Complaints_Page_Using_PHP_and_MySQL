<?php
class Get_list_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_sub_cat()
  {
  	$sql = "SELECT * FROM cbcs_course_component group by id";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }
  }

  function get_student_list($data){
    // echo "<pre>";
    // print_r($data);
    // exit;
  	$session = $data['session'];
  	$session_year = $data['session_year'];
    $sub_cat = substr($data['sub_cat'],0,1);
    $dept = $data['dept'];
  	$sub_id = $data['subjectID'];
    if(empty($sub_id)){
      // $sql = "SELECT f.admn_no,b.first_name,b.middle_name,b.last_name,
      //   c.name as cname,e.name as dname,d.name as bname,f.semester
      //   FROM cbcs_stu_course a
      //   INNER JOIN reg_regular_form f
      //   ON f.form_id = a.form_id
      //   INNER JOIN user_details b 
      //   ON f.admn_no = b.id
      //   INNER JOIN cs_courses c
      //   ON c.id = f.course_id
      //   INNER JOIN cs_branches d
      //   ON f.branch_id = d.id
      //   INNER JOIN user_details g
      //   ON f.admn_no = g.id
      //   INNER JOIN departments e
      //   ON e.id = g.dept_id
      //   WHERE f.session_year = '$session_year' AND 
      //   f.`session` = '$session' AND e.id='$dept' AND a.sub_category LIKE '$sub_cat%' GROUP BY f.admn_no;";
      $sql="SELECT a.remark2,f.admn_no,b.first_name,b.middle_name,b.last_name,
            c.name AS cname,e.name AS dname,d.name AS bname,f.semester
             FROM pre_stu_course a
            INNER JOIN reg_regular_form f ON f.form_id = a.form_id
            INNER JOIN user_details b ON f.admn_no = b.id
            INNER JOIN cbcs_courses c ON c.id = f.course_id
            INNER JOIN cbcs_branches d ON f.branch_id = d.id
            INNER JOIN user_details g ON f.admn_no = g.id
            INNER JOIN cbcs_departments e ON e.id = g.dept_id
            WHERE f.session_year = '$session_year' AND a.remark2=1 
            AND f.`session` = '$session' AND a.sub_category LIKE '$sub_cat%'
            AND e.id='$dept' GROUP BY f.admn_no";
    }
    else
    {
  	  // $sql = "SELECT f.admn_no,b.first_name,b.middle_name,b.last_name,
  			// c.name as cname,e.name as dname,d.name as bname,f.semester
  			// FROM cbcs_stu_course a
     //    INNER JOIN reg_regular_form f
     //    ON f.form_id = a.form_id
  			// INNER JOIN user_details b 
  			// ON f.admn_no = b.id
  			// INNER JOIN cs_courses c
  			// ON c.id = f.course_id
  			// INNER JOIN cs_branches d
  			// ON f.branch_id = d.id
     //    INNER JOIN user_details g
     //    ON f.admn_no = g.id
  			// INNER JOIN departments e
  			// ON e.id = g.dept_id
  			// WHERE f.session_year = '$session_year' AND 
  			// f.`session` = '$session' AND  e.id='$dept' AND a.sub_category LIKE '$subcat%' GROUP BY f.admn_no";
      $sql="SELECT a.remark2,f.admn_no,b.first_name,b.middle_name,b.last_name,
            c.name AS cname,e.name AS dname,d.name AS bname,f.semester,
            a.subject_code,a.subject_name FROM pre_stu_course a
            INNER JOIN reg_regular_form f ON f.form_id = a.form_id
            INNER JOIN user_details b ON f.admn_no = b.id
            INNER JOIN cbcs_courses c ON c.id = f.course_id
            INNER JOIN cbcs_branches d ON f.branch_id = d.id
            INNER JOIN user_details g ON f.admn_no = g.id
            INNER JOIN cbcs_departments e ON e.id = g.dept_id
            WHERE f.session_year = '$session_year' AND a.remark2=1 
            AND f.`session` = '$session' AND a.sub_category LIKE '$sub_cat%'
            AND a.subject_code='$sub_id'
            AND e.id='$dept' GROUP BY f.admn_no";
    }
      $query = $this->db->query($sql);
      // echo $this->db->last_query(); exit;
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }
  }

  function get_department_list(){
  	$sql = "SELECT t.* FROM cbcs_departments t WHERE t.`status`=1 AND t.`type`='academic'";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }
  }

  function get_subject_list($session_year,$session,$sub_cat,$dept){

  	// $sql = "SELECT sub_name,sub_code FROM cbcs_subject_offered WHERE session_year = '$session_year' AND 
  	// 		session = '$session' AND sub_category LIKE '$sub_cat%' AND dept_id = '$dept'  GROUP BY sub_code";
    $sql="SELECT a.subject_code,a.subject_name FROM pre_stu_course a INNER JOIN user_details b ON a.admn_no=b.id INNER JOIN cbcs_departments c ON b.dept_id=c.id WHERE a.session_year = '$session_year' AND a.session = '$session' AND a.sub_category LIKE '$sub_cat%' AND b.dept_id = '$dept' GROUP BY a.subject_code";
    $query = $this->db->query($sql);
     //echo $this->db->last_query(); exit;
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }
  }
}