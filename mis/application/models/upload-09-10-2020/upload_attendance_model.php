<?php

class Upload_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_session_year() {
        $emp_id = $data['emp_id']; //$this->session->userdata('id');
        $this->load->database();
        $query = $this->db->query("SELECT DISTINCT session_year
FROM subject_mapping_des AS A
INNER JOIN subject_mapping AS B ON A.map_id = B.map_id where B.session_year<>'' and B.session_year<>0
ORDER BY session_year desc;");
        return $query->result();
    }

    /* public function get_subjects($data)
      {
      $emp_id=$this->session->userdata('id');
      $session_year=$data['session_year'];
      $session=$data['session'];
      $this->load->database();


      $query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
      cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
      cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
      FROM subject_mapping
      INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
      INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
      INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
      INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
      WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and subject_mapping.course_id <> 'jrf' and type!='Practical' and `type`<>'Non-contact';");

      $result= $query->result();
      //print_r($result);
      return $result;

      } */

    public function get_subjects($data, $param = true) {
        if ($param) {
            $rep1 = " and type!='Practical' and `type`<>'Non-contact' ";
            $cbcs_rep1 = " and newt.sub_type<>'Practical'  and newt.contact_hours<>0  ";

            /* $dd_hon = " union (SELECT p.map_id AS map_id,p.semester AS semester,p.sub_name ,p.sub_id,
              p.branch_id,p.group,'0'as section, p. branch_name,
              p.course_id ,p.aggr_id, p.course_name,
              p.coordinator,p.type AS sub_type

              from
              (
              SELECT x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.map_id,
              z.coordinator,x.type
              FROM (
              SELECT DISTINCT e.subject_id AS s_id, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group',
              g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id,
              c.duration, b.session_year,b.`session`,e.`type`
              FROM hm_form a
              INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
              INNER JOIN cs_courses c ON c.id=b.course_id
              INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
              INNER JOIN subjects e ON e.id=d.id
              INNER JOIN stu_academic f ON f.admn_no=b.admn_no
              INNER JOIN cs_branches g ON g.id=f.branch_id
              WHERE a.dept_id='" . $this->session->userdata('dept_id') . "' AND c.duration=5 AND b.session_year='" . $data['session_year'] . "' AND b.`session`='" . $data['session'] . "' AND d.semester=b.semester)x
              INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
              INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id AND z.emp_no='" . $data['emp_id'] . "')p
              where p.course_id <> 'jrf' AND p.type!='Practical'  AND p.type<>'Non-contact') "; */
            //   $rep = $rep1 . $cbcs_sys_sql . $dd_hon;
        } else {
            $rep1 = "  ";
            $cbcs_rep1 = "  ";
        }
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();

        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_sys_sql = "  UNION
                        (
                        SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "'   and  newt.coordinator='1'  AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . " AND newt.course_id<>'jrf' $cbcs_rep1   ) ";
        // end


        $query = $this->db->query(" SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.subject_id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
          cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
          cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type,subjects.contact_hours
          FROM subject_mapping
          INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
          INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
          INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
          INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
          WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and  subject_mapping_des.coordinator='1'  and  subject_mapping.course_id <> 'jrf'   " . $rep1 . "  " . $cbcs_sys_sql . "  ");


		  $result = $query->result();
        //  echo $this->db->last_query();        die();
        //print_r($result);
        return $result;
    }

    //=======================JRf==============================================
    public function get_subjects_jrf($data, $param = true) {
        if ($param) {
            $rep1 = " and type!='Practical' and `type`<>'Non-contact' ";
            $cbcs_rep1 = " and newt.sub_type<>'Practical'  and newt.contact_hours<>0 ";
            /* $dd_hon = "union (select d.map_id,d.semester,e.name as sub_name,a.sub_id,
              d.branch_id,d.`group`,'0' AS section,f.name as branch_name,d.course_id,d.aggr_id,
              g.name as course_name,c.coordinator,e.`type` as sub_type
              from reg_exam_rc_subject a
              inner join reg_exam_rc_form b on b.form_id=a.form_id
              inner join subject_mapping_des c on c.sub_id=a.sub_id
              inner join subject_mapping d on d.map_id=c.map_id and d.session_year='" . $data['session_year'] . "' and d.`session`='" . $data['session'] . "'
              inner join subjects e on e.id=a.sub_id
              inner join cs_branches f on f.id=d.branch_id
              inner join cs_courses g on g.id=d.course_id
              where b.session_year='" . $data['session_year'] . "' and b.`session`='" . $data['session'] . "'
              and b.hod_status='1' and b.acad_status='1' and c.emp_no='" . $data['emp_id'] . "'
              AND e.type!='Practical' AND e.type<>'Non-contact')"; */
            //$rep = $rep1 . $dd_hon;
        } else {
            $rep = "";
            $cbcs_rep1 = "";
        }
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();



        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_sys_sql = "  UNION
                        (
                         SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . "AND newt.course_id='jrf' $cbcs_rep1   ) ";
        // end


        $query = $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type,subjects.contact_hours
			FROM subject_mapping
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no='$emp_id'  and  subject_mapping_des.coordinator='1'  and  subject_mapping.course_id = 'jrf'  " . $rep1 . "  " . $cbcs_sys_sql . "  ");

        $result = $query->result();
        //echo $this->db->last_query();die();
        //print_r($result);
        return $result;
    }

    //=========================================================================

    public function get_subjects_for_marks_upload($data) {
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();


        $query = $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id  and subject_mapping.course_id <> 'jrf';");

        $result = $query->result();
        //print_r($result);
        return $result;
    }

    public function get_prac_subjects($data) {
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();
        /* $dd_hon_prac = "union (SELECT p.map_id AS map_id,p.semester AS semester,p.sub_name ,p.sub_id,
          p.branch_id,p.group,'0'as section, p. branch_name,
          p.course_id ,p.aggr_id, p.course_name,
          p.coordinator,p.type AS sub_type

          from
          (
          SELECT x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.map_id,
          z.coordinator,x.type
          FROM (
          SELECT DISTINCT e.subject_id AS s_id, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group',
          g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id,
          c.duration, b.session_year,b.`session`,e.`type`
          FROM hm_form a
          INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
          INNER JOIN cs_courses c ON c.id=b.course_id
          INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
          INNER JOIN subjects e ON e.id=d.id
          INNER JOIN stu_academic f ON f.admn_no=b.admn_no
          INNER JOIN cs_branches g ON g.id=f.branch_id
          WHERE a.dept_id='" . $this->session->userdata('dept_id') . "' AND c.duration=5 AND b.session_year='" . $data['session_year'] . "' AND b.`session`='" . $data['session'] . "' AND d.semester=b.semester)x
          INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
          INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id AND z.emp_no='" . $data['emp_id'] . "')p
          where p.course_id <> 'jrf' AND p.type='Practical'
          AND p.type<>'Non-contact')"; */

        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_rep1 = " and newt.sub_type='Practical' ";
        $cbcs_sys_sql = "  UNION
                        (
                        SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "'  and  newt.coordinator='1'   AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . "AND newt.course_id<>'jrf' $cbcs_rep1   ) ";
        // end


        $query = $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id  and  subject_mapping_des.coordinator='1' and  subject_mapping.course_id <> 'jrf' and type='Practical' " . $cbcs_sys_sql . " ;");

        $result = $query->result();
        //print_r($result);
        return $result;
    }

    //==========================================JRF==============================================

    public function get_prac_subjects_jrf($data) {
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();
        /* $dd_hon_prac = "union (select d.map_id,d.semester,e.name as sub_name,a.sub_id,
          d.branch_id,d.`group`,'0' AS section,f.name as branch_name,d.course_id,d.aggr_id,
          g.name as course_name,c.coordinator,e.`type` as sub_type
          from reg_exam_rc_subject a
          inner join reg_exam_rc_form b on b.form_id=a.form_id
          inner join subject_mapping_des c on c.sub_id=a.sub_id
          inner join subject_mapping d on d.map_id=c.map_id and d.session_year='" . $data['session_year'] . "' and d.`session`='" . $data['session'] . "'
          inner join subjects e on e.id=a.sub_id
          inner join cs_branches f on f.id=d.branch_id
          inner join cs_courses g on g.id=d.course_id
          where b.session_year='" . $data['session_year'] . "' and b.`session`='" . $data['session'] . "'
          and b.hod_status='1' and b.acad_status='1' and c.emp_no='" . $data['emp_id'] . "'
          AND e.type='Practical' AND e.type<>'Non-contact')"; */


        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_rep1 = " and newt.sub_type='Practical' ";
        $cbcs_sys_sql = "  UNION
                        (
                        SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type

                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . "AND newt.course_id='jrf' $cbcs_rep1   ) ";
        // end

        $query = $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id,subject_mapping.aggr_id,
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id   and  subject_mapping_des.coordinator='1' and subject_mapping.course_id = 'jrf' and type='Practical' " . $cbcs_sys_sql . " ");

        $result = $query->result();
        //print_r($result);
        return $result;
    }

    //===============================================================================================
    public function get_branch($data, $subject) {
        $emp_id = $data['emp_id'];
        $this->load->database();
        $query = $this->db->query("SELECT DISTINCT branch_id
								FROM sub_mapping
								INNER JOIN (SELECT map_id
											FROM sub_mapping_des
											WHERE teacher_id = '$emp_id' AND subject_id='$subject') AS t
								ON t.map_id = sub_mapping.map_id ;");
        $branch_id = array();
        return $query->result();
    }

    public function get_branch_name($branch_id) {
        $branch_id_arr = array();
        for ($i = 0; $i < count($branch_id); $i++)
            $branch_id_arr[$i] = $branch_id[$i]->branch_id;
        $this->load->database();
        $this->db->select('id,name');
        $this->db->from('departments');
        $this->db->where_in('id', $branch_id_arr);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_branch_name_again($branch_id) {
        $this->load->database();
        $this->db->select('name');
        $this->db->from('departments');
        $this->db->where_in('id', $branch_id);
        $query = $this->db->get();
        return $query->result();
    }

    function check_ta_ft_mapping($empid, $taid, $subid, $tmapid) {
        $sql = "select a.* from faculty_ta_mapping_tbl a
        inner join subject_mapping_des b on b.map_id=a.map_id and b.emp_no=a.emp_no
        where  a.emp_no=? and a.admn_no=? and a.map_id=? and a.sub_id=? and a.status='1'";

        //echo $sql;die();
        $query = $this->db->query($sql, array($empid, $taid, $tmapid, $subid));

        // echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_faculty_byDept($id, $admn_no) {
        $sql = "select a.id,concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name)as faculty,d.map_id from user_details a
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
inner join faculty_ta_mapping_tbl d on d.emp_no=b.emp_no
where a.dept_id=? and b.auth_id='ft' and d.admn_no=?
and c.`status`='A' and d.status='1'
group by d.emp_no
 order  by a.first_name,a.middle_name,a.last_name";
        $query = $this->db->query($sql, array($id, $admn_no));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_faculty_byDept_gen($id, $admn_no) {
        $sql = "select a.id,concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name)as faculty from user_details a
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
inner join faculty_ta_mapping_tbl d on d.emp_no=b.emp_no
where a.dept_id=? and b.auth_id='ft' and d.admn_no=?
and c.`status`='A'
group by d.emp_no
 order  by a.first_name,a.middle_name,a.last_name";
        $query = $this->db->query($sql, array($id, $admn_no));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>
