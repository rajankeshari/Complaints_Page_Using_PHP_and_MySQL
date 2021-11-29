<?php

class Cbcs_check_feedback_subjects_model extends CI_Model {

    public function get_details_core($admn_no) {

        $curr_status = $this->get_student_latest_status($admn_no); //latest registration from reg_regular_form
        $sec=$this->get_student_section($curr_status->session_year,$admn_no);
// print_r($sec);
        if($sec){ $con=" AND c.section='".$sec."'";}else { $con="";}

        $sql = "SELECT x.* FROM(
(SELECT CONCAT('c',a.sub_offered_id) AS sub_offered_id,a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN cbcs_subject_offered_desc c ON c.sub_offered_id=b.id ".$con."
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? AND (b.sub_type='Theory' || b.sub_type='Sessional'|| b.sub_type='Modular' || b.sub_type='Audit')  AND b.lecture<>'0' GROUP BY a.subject_code,c.emp_no)
 UNION
 (SELECT CONCAT('o',a.sub_offered_id) AS sub_offered_id,a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM old_stu_course a
INNER JOIN old_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN old_subject_offered_desc c ON c.sub_offered_id=b.id /*".$con."*/
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? AND (b.sub_type='Theory' || b.sub_type='Sessional' || b.sub_type='Modular' || b.sub_type='Audit')  AND b.lecture<>'0'  GROUP BY a.subject_code,c.emp_no)
)x
GROUP BY x.subject_code,x.emp_no
ORDER BY x.semester,x.subject_name";

        $query = $this->db->query($sql, array($curr_status->session_year,$curr_status->form_id,$curr_status->admn_no,$curr_status->session_year,$curr_status->form_id,$curr_status->admn_no));
//echo $this->db->last_query();

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //=========================================================
      public function get_details_diff_sub_name($admn_no,$missing_papers) {

        $curr_status = $this->get_student_latest_status($admn_no); //latest registration from reg_regular_form
        $sec=$this->get_student_section($curr_status->session_year,$admn_no);
// print_r($sec);
        if($sec){ $con=" AND c.section='".$sec."'";}else { $con="";}

        $sql = "SELECT p.subject_name FROM( SELECT x.* FROM(
(SELECT CONCAT('c',a.sub_offered_id) AS sub_offered_id,a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN cbcs_subject_offered_desc c ON c.sub_offered_id=b.id ".$con."
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? AND (b.sub_type='Theory' || b.sub_type='Sessional'|| b.sub_type='Modular')  AND b.lecture<>'0' GROUP BY a.subject_code,c.emp_no)
 UNION
 (SELECT CONCAT('o',a.sub_offered_id) AS sub_offered_id,a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM old_stu_course a
INNER JOIN old_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN old_subject_offered_desc c ON c.sub_offered_id=b.id /*".$con."*/
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? AND (b.sub_type='Theory' || b.sub_type='Sessional' || b.sub_type='Modular')  AND b.lecture<>'0' GROUP BY a.subject_code,c.emp_no)
)x
GROUP BY x.subject_code,x.emp_no
ORDER BY x.semester,x.subject_name
)p WHERE p.subject_code IN (".$missing_papers.")
group BY p.subject_code
";

        $query = $this->db->query($sql, array($curr_status->session_year,$curr_status->form_id,$curr_status->admn_no,$curr_status->session_year,$curr_status->form_id,$curr_status->admn_no));


        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    //============================================================

        public function count_subject($subject_code,$admn_no) {

        $curr_status = $this->get_student_latest_status($admn_no); //latest registration from reg_regular_form
        $sec=$this->get_student_section($curr_status->session_year,$admn_no);
// print_r($sec);
        if($sec){ $con=" AND c.section='".$sec."'";}else { $con="";}

        $sql = "SELECT count(x.subject_code) AS ctr FROM(
(SELECT a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN cbcs_subject_offered_desc c ON c.sub_offered_id=b.id ".$con."
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? GROUP BY a.subject_code,c.emp_no)
 UNION
 (SELECT a.subject_code,a.subject_name,b.sub_type,c.emp_no,
CONCAT_WS(' ',d.first_name,d.middle_name,d.last_name)AS faculty_name,e.name as dname,d.photopath,b.semester FROM old_stu_course a
INNER JOIN old_subject_offered b ON b.sub_code=a.subject_code AND a.sub_offered_id=b.id
INNER JOIN old_subject_offered_desc c ON c.sub_offered_id=b.id /*".$con."*/
INNER JOIN user_details d ON d.id=c.emp_no
INNER JOIN cbcs_departments e ON e.id=d.dept_id
LEFT JOIN stu_section_data f ON f.admn_no=a.admn_no AND f.session_year=? AND c.section=f.section
WHERE a.form_id=? AND a.admn_no=? GROUP BY a.subject_code,c.emp_no)
)x
WHERE x.subject_code=?
GROUP BY x.subject_code";

        $query = $this->db->query($sql, array($curr_status->session_year,$curr_status->form_id,$curr_status->admn_no,$curr_status->session_year,$curr_status->form_id,$curr_status->admn_no,$subject_code));


        if ($this->db->affected_rows() >= 0) {
            return $query->row()->ctr;
        } else {
            return false;
        }
    }


function get_student_latest_status($id) {


        $sql = "select a.* from reg_regular_form a where a.admn_no=? and hod_status='1' and acad_status='1'  and a.session_year='2019-2020' and a.session='Monsoon'/*order by a.timestamp desc limit 1*/;";
        $query = $this->db->query($sql, array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }





       function insert($data) {

        if ($this->db->insert('fb_student_subject_main', $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function insert_desc($data1) {
        if ($this->db->insert('fb_student_subject_desc', $data1))
            return TRUE;
        else
            return FALSE;
    }

    function get_main_data($data) {
      $sql = "select a.* from fb_student_subject_main a
where a.session_year=? and a.`session`=?
and a.admn_no=? and a.course_id=? and a.branch_id=? ";

      $query = $this->db->query($sql, array($data['session_year'], $data['session'], $data['admn_no'], $data['course_id'], $data['branch_id']));

      //echo $this->db->last_query(); die();
      if ($this->db->affected_rows() >= 0) {
          return $query->row();
      } else {
          return false;
      }
    }



    function get_desc_data($id,$sec) {



        if($sec){ $con=" AND d.section='".$sec."'";}else { $con="";}
        $sql = " (SELECT a.*,
b.sub_code,
b.sub_name,
CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) AS fname
FROM fb_student_subject_desc a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.sub_id AND CONCAT('c',b.id)=a.sub_offered_id
INNER JOIN cbcs_subject_offered_desc d ON d.sub_offered_id=b.id AND d.emp_no=a.faculty_id AND d.sub_id=a.sub_id ".$con."
INNER JOIN user_details c ON c.id=a.faculty_id
WHERE a.main_id=?)
UNION
(SELECT a.*,
b.sub_code,
b.sub_name,
CONCAT_WS(' ',c.salutation,c.first_name,c.middle_name,c.last_name) AS fname
FROM fb_student_subject_desc a
INNER JOIN old_subject_offered b ON b.sub_code=a.sub_id AND CONCAT('o',b.id)=a.sub_offered_id
INNER JOIN old_subject_offered_desc d ON d.sub_offered_id=b.id AND d.emp_no=a.faculty_id AND d.sub_id=a.sub_id
INNER JOIN user_details c ON c.id=a.faculty_id
WHERE a.main_id=?)";

        $query = $this->db->query($sql, array($id,$id));

      //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_student_section($syear,$admn_no){

        $sql = " SELECT section FROM stu_section_data WHERE admn_no=? AND session_year=?";

        $query = $this->db->query($sql, array($admn_no,$syear));

        //cho $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->section;
        } else {
            return false;
        }


    }

        function delete_main_id($id) {
        $this->db->where('id', $id);
        $this->db->delete('fb_student_subject_main');
    }

    function delete_desc_id($id) {
        $this->db->where('main_id', $id);
        $this->db->delete('fb_student_subject_desc');
    }

    function get_student_name($admn_no) {
      $sql = "SELECT CONCAT_WS(' ', first_name,middle_name,last_name) AS sname FROM user_details WHERE id=?";
     $query = $this->db->query($sql, array($admn_no));
      if ($this->db->affected_rows() >= 0) {
          return $query->row()->sname;
      } else {
          return false;
      }
    }
    function get_student_course($id) {
      $sql = "SELECT NAME AS cname from  cbcs_courses WHERE id=?";
     $query = $this->db->query($sql, array($id));
      if ($this->db->affected_rows() >= 0) {
          return $query->row()->cname;
      } else {
          return false;
      }
    }
    function get_student_branch($id) {
      $sql = "SELECT NAME AS bname from  cbcs_branches WHERE id=?";
     $query = $this->db->query($sql, array($id));
      if ($this->db->affected_rows() >= 0) {
          return $query->row()->bname;
      } else {
          return false;
      }
    }

    public function get_course_duration_by_course_id($id)
{
  $basic_query = " SELECT duration FROM cbcs_courses WHERE id = '$id' ";
  $result = $this->db->query($basic_query)->result();
  return $result[0]->duration;
}

public function get_section($id,$session_year)
{
  $basic_query = " SELECT section FROM stu_section_data WHERE admn_no = '$id' AND session_year = '$session_year' ";
  $result = $this->db->query($basic_query)->result();
  if(count($result)==0)
  {
    return NULL;
  }
  else
  return $result[0]->section;
}

public function get_group_details_fbs()
	{
		$basic_query = " SELECT * FROM fbs_group WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}
	public function get_parameter_details_fbs()
	{
		$basic_query = " SELECT * FROM fbs_parameter WHERE status = '1' ";
		return $this->db->query($basic_query)->result();
	}

	public function check_comment_status_fbs()
	{
		$basic_query = " SELECT value FROM fb_details WHERE description = 'fbs_comment' ";
		$status = $this->db->query($basic_query)->result();
		return $status[0]->value;
	}

  function get_faculty_details($id){
    $sql = "SELECT * FROM user_details WHERE id=?";
   $query = $this->db->query($sql, array($id));
    if ($this->db->affected_rows() >= 0) {
        return $query->row();
    } else {
        return false;
    }

  }

  function get_gpa_of_student($admn_no){

    $sql = "SELECT gpa FROM final_semwise_marks_foil WHERE admn_no=? ORDER BY semester DESC LIMIT 1";
   $query = $this->db->query($sql, array($admn_no));
    if ($this->db->affected_rows() >= 0) {
        return $query->row()->gpa;
    } else {
        return false;
    }
  }

  function check_date_openclose_all($etype){

    $sql = "SELECT a.* FROM sem_date_open_close_tbl a WHERE a.exam_type=? AND a.open_for='all'
AND (CURDATE() BETWEEN normal_start_date AND normal_close_date)";
   $query = $this->db->query($sql, array($etype));
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
  }

  function check_feedback_already_filled($data){

    $sql = "select a.* from fb_student_feedback_cbcs a
where a.session_year=? and a.`session`=? and a.admn_no=?
and a.course_id=? and a.branch_id=? AND feedback_type='sem_feedback'";
   $query = $this->db->query($sql, array($data['session_year'],$data['session'],$data['admn_no'], $data['course_id'],$data['branch_id'] ));
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }
  function get_details_facutly($id, $sy, $sess) {


    $sql = "(SELECT CONCAT('c',a.id) AS map_id,b.emp_no,b.coordinator,
b.sub_id, NULL AS 'M',a.id,a.sub_code AS subject_id,a.sub_name,
a.semester,c.name AS deptnm,d.name AS cname,e.name AS bname
 FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
LEFT JOIN cbcs_departments c ON c.id=a.dept_id
LEFT JOIN cbcs_courses d ON d.id=a.course_id
LEFT JOIN cbcs_branches e ON e.id=a.branch_id
WHERE b.emp_no=? AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') AND a.lecture !='0')
UNION
(SELECT CONCAT('o',a.id) AS map_id,b.emp_no,b.coordinator,
b.sub_id, NULL AS 'M',a.id,a.sub_code AS subject_id,a.sub_name,
a.semester,c.name AS deptnm,d.name AS cname,e.name AS bname
 FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
LEFT JOIN cbcs_departments c ON c.id=a.dept_id
LEFT JOIN cbcs_courses d ON d.id=a.course_id
LEFT JOIN cbcs_branches e ON e.id=a.branch_id
WHERE b.emp_no=? AND a.session_year=? AND a.`session`=?
AND (a.sub_type='Theory' OR a.sub_type='Sessional' OR a.sub_type='Modular') AND a.lecture !='0')
";

    $query = $this->db->query($sql, array($id,$sy, $sess,$id,$sy, $sess));

    //echo $this->db->last_query(); die();
    if ($this->db->affected_rows() >= 0) {
        return $query->result();
    } else {
        return false;
    }
}



}
