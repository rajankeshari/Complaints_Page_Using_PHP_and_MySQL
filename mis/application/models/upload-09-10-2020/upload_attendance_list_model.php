<?php

class Upload_attendance_list_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //   public function get_all_student($data)
    //   {
    //   	$this->load->database();
    //   	$sub_id=$data['sub_id'];
    //   	$session = $data['session'];
    //   	$session_year = $data['session_year'];
    // $branch_id=$data['branch_id'];
    // $semester=$data['class_res'][0]->semester;
    // $course_id=$data['course_id'];
    //   	$query="SELECT U.id as id,U.first_name as first_name,U.middle_name as middle_name,U.last_name as last_name
    //   			FROM reg_regular_elective_opted
    //   			INNER JOIN reg_regular_form ON reg_regular_form.form_id=reg_regular_elective_opted.form_id
    //   			INNER JOIN user_details AS U ON U.id=reg_regular_form.admn_no
    //   			WHERE session='$session' AND session_year='$session_year' AND branch_id='$branch_id'
    //   			AND semester=$semester AND course_id='$course_id'
    //   			";
    //   	$query=$this->db->query($query);
    //   	print_r($query->result());
    //   }
    public function get_student($data) {

        $sub = $data['sub_id'];
        $session = $data['session'];
        $this->load->database();
        if ($session === 'Summer')
            $sub_table = 'reg_summer_subject';
        else
            $sub_table = 'reg_regular_elective_opted';
        $subs = $this->get_subject_id($sub, $data);
        $this->db->select('form_id');
        $this->db->from($sub_table);
        $w = "";
        $i = 0;
        foreach ($subs as $c) {
            if ($i == 0) {
                $w = "(`sub_id`='$c->id')";
            } else {
                $w.=" OR (`sub_id`='$c->id')";
            }
            $i++;
        }
        $this->db->where($w);
        //$this->db->where('sub_id',$sub);
        $query = $this->db->get();


        //if($query->num_rows() >0){
        $data['form_no'] = $query->result();
        //}else{
        //	$data['form_no']=array();
        //}
        //if(count($data['form_no']) < 5) $data['form_no'] =array();
        //	print_r($this->get_admn($data));
        return $this->get_admn($data);
    }

    public function get_student_comm($data) {
        // print_r($data);die();
        //@anuj fro drop student 16-08-2018
        $drop_stu = "reg_regular_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $data['session_year'] . "'
and a.`session`='" . $data['session'] . "' and a.semester=" . $data['semester'] . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";


        $this->db->select('reg_regular_form.admn_no')
                ->from('reg_regular_form')
                ->join('stu_section_data', 'stu_section_data.admn_no=reg_regular_form.admn_no and stu_section_data.session_year=reg_regular_form.session_year')
                ->where('reg_regular_form.hod_status', '1')
                ->where('reg_regular_form.acad_status', '1')
                ->where('reg_regular_form.session_year', $data['session_year'])
                ->where('reg_regular_form.session', $data['session'])
                ->where('reg_regular_form.semester', $data['semester'])
                ->where('stu_section_data.section', $data['section'])
                ->where('course_aggr_id', $data['aggr_id'])
                ->where($drop_stu); //@anuj fro drop student 16-08-2018
        //->limit('5');

        $q = $this->db->get();

        $data['stu_admn'] = $q->result();
        //   echo $this->db->last_query();
        // die();
        return $this->get_name($data);
    }

    public function get_student_Summer_comm($data) {
        $this->db->select('reg_summer_form.admn_no')
                ->from('reg_summer_form')
                ->join('stu_section_data', 'stu_section_data.admn_no=reg_summer_form.admn_no and stu_section_data.session_year=reg_summer_form.session_year')
                ->join('reg_summer_subject', 'reg_summer_subject.form_id=reg_summer_form.form_id')
                ->where('reg_summer_form.hod_status', '1')
                ->where('reg_summer_form.acad_status', '1')
                ->where('reg_summer_form.session_year', $data['session_year'])
                ->where('reg_summer_form.session', $data['session'])
                /* ->where('reg_summer_form.semester',$data['semester']) */
                ->where('stu_section_data.section', $data['section'])
                ->where('reg_summer_subject.sub_id', $data['sub_id']);

        $q = $this->db->get();
        //echo $this->db->last_query();
        $data['stu_admn'] = $q->result();
        return $this->get_name($data);
    }

    // added by @ rituraj @dated:12-7-18
    public function get_student_summer_minor($data) {
        $q = "
select z.* from
(SELECT hm_form.admn_no
FROM hm_form
JOIN hm_minor_details ON hm_form.form_id=hm_minor_details.form_id WHERE hm_form.minor_hod_status =? AND hm_minor_details.dept_id=? AND hm_minor_details.branch_id=? AND hm_minor_details.offered=?)z
join
(select x.admn_no from  reg_summer_form x join  reg_summer_subject y on x.form_id=y.form_id
   and  x.session_year=? AND x.`session`=?
   AND x.hod_status<>?  AND x.acad_status<>?
	 join  course_structure cs on cs.id=y.sub_id and cs.aggr_id like 'minor%' AND (cs.semester=? )
	 group by x.admn_no
	) w
   ON w.admn_no=z.admn_no order by z.admn_no ";
        $query = $this->db->query($q, array('Y', $this->session->userdata('dept_id'), $data['branch_id'], '1', $data['session_year'], $data['session'], '2', '2', $data['semester']));

        //echo $this->db->last_query(); die();
        $data['stu_admn'] = $query->result();
        //print_r($data['form_no']);
        // if(count($data['form_no'])!=0)
        return $this->get_name($data);
    }

    public function get_student_minor($data) {

        //print_r($data);
        $sub_id = $data['sub_id'];
        //print_r($data); die();
        $session = $data['session'];
        /* $this->load->database();

          $sub_table = 'hm_form';
          $this->db->select('hm_form.admn_no')
          ->from($sub_table)
          ->join('hm_minor_details','hm_minor_details.form_id=hm_form.form_id')
          ->where('hm_form.minor','1')
          ->where('hm_form.session_year',$data['session_year'])
          ->where('hm_form.minor_hod_status','Y')
          ->where('hm_minor_details.dept_id',$this->session->userdata('dept_id'))
          ->where('hm_minor_details.branch_id',$data['branch_id'])
          ->where('hm_minor_details.offered','1')
          ->where('hm_minor_details.minor_agg_id',$data['aggr_id']);
          //$this->db->where('sub_id',$sub_id);
          $query=$this->db->get(); */

        $q = "select  hm_form.admn_no from hm_form join hm_minor_details on hm_form.form_id=hm_minor_details.form_id JOIN reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no where hm_form.minor_hod_status ='Y' and hm_minor_details.dept_id='" . $this->session->userdata('dept_id') . "' and hm_minor_details.branch_id='" . $data['branch_id'] . "' and hm_minor_details.offered='1' and reg_regular_form.session_year='" . $data['session_year'] . "' and reg_regular_form.`session`='" . $data['session'] . "' and reg_regular_form.semester='" . $data['semester'] . "'
		   and reg_regular_form.hod_status<>'2' and reg_regular_form.acad_status<>'2' and hm_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $data['session_year'] . "'
and a.`session`='" . $session . "' and a.semester=" . $data['semester'] . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";






        $query = $this->db->query($q);

        //echo $this->db->last_query(); die();
        $data['stu_admn'] = $query->result();
        //print_r($data['form_no']);
        // if(count($data['form_no'])!=0)
        return $this->get_name($data);
        // else
        // 	return $data['form_no'];
    }

    /*  public function get_student_honour($data)
      {

      $session = $data['session'];
      $branch=$data['branch_id'];
      $aggr_id=$data['aggr_id'];
      $session = $data['session'];
      $session_year=$data['session_year'];
      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
      join reg_regular_form a on a.admn_no=hm_form.admn_no
      WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=?";

      $query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));
      $data['stu_admn'] = $query->result();
      //echo $this->db->last_query(); die();
      //print_r($data['stu_admn']);
      return $this->get_name($data);

      } */

    private function isElective($id) {
        $qu = $this->db->get_where('subjects', array('id' => $id));
        $r = $qu->row();
        if ($r->elective == 0)
            return false;

        return true;
    }

    public function get_student_honour($data) {
        //print_r($data); die();
        $branch = $data['branch'];
        $aggr_id = $data['aggr_id'];
        $session = $data['session'];
        $session_year = $data['session_year'];
        if ($session <> 'Summer') {
            if ($this->isElective($data['sub_id'])) {
                $q = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=?
 and a.semester=? and b.sub_id=? and (d.duration=4 || d.duration=5) and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $session_year . "'
and a.`session`='" . $session . "' and a.semester=" . $data['semester'] . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";

                $query = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester'], $data['sub_id']));
            } else {
                $q = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=?
 and a.`session`=? and a.semester=? and (d.duration=4 || d.duration=5) and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $session_year . "'
and a.`session`='" . $session . "' and a.semester=" . $data['semester'] . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";

                $query = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester']));
            }
        } //summer
        else {
            $q = "SELECT hm_form.`admn_no`
FROM (`hm_form`)
JOIN reg_summer_form a ON a.admn_no=hm_form.admn_no
inner join reg_summer_subject b on b.form_id=a.form_id
inner join course_structure c on c.id=b.sub_id and c.aggr_id=? and c.semester=?
WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' AND a.session_year=? AND a.`session`=?
and a.admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $session_year . "'
and a.`session`='" . $session . "' and a.semester=" . $data['semester'] . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";

            $query = $this->db->query($q, array($aggr_id, $data['semester'], $aggr_id, $session_year, $session));
        }
        //echo    $q;
        //  $q=""
        //echo $this->db->last_query(); die();


        $data['stu_admn'] = $query->result();

        return $this->get_name($data);
    }

//============================Dual Degree Honours starts ============================
    public function get_student_honour_dd($data) {
        //print_r($data); die();
        $branch = $data['branch'];
        $aggr_id = $data['aggr_id'];
        $session = $data['session'];
        $session_year = $data['session_year'];
        if ($session <> 'Summer') {
            if ($this->isElective($data['sub_id'])) {
                $q = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and d.duration=5";

                $query = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester'], $data['sub_id']));
            } else {
                $q = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and d.duration=5";

                $query = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester']));
            }
        } //summer
        else {
            $q = "SELECT hm_form.`admn_no`
FROM (`hm_form`)
JOIN reg_summer_form a ON a.admn_no=hm_form.admn_no
inner join reg_summer_subject b on b.form_id=a.form_id
inner join course_structure c on c.id=b.sub_id and c.aggr_id=? and c.semester=?
WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' AND a.session_year=? AND a.`session`=?
";

            $query = $this->db->query($q, array($aggr_id, $data['semester'], $aggr_id, $session_year, $session));
        }
        //    $q
        //  $q=""
        //echo $this->db->last_query(); die();


        $data['stu_admn'] = $query->result();

        return $this->get_name($data);
    }

//=============================Dual Degree Honours Ends================================




    /*    public function get_student_honour($data)
      {
      //print_r($data); die();
      $branch=$data['branch'];
      $aggr_id=$data['aggr_id'];
      $session = $data['session'];
      $session_year=$data['session_year'];
      if($this->isElective($data['sub_id'])){
      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
      join reg_regular_form a on a.admn_no=hm_form.admn_no
      join reg_regular_elective_opted b on a.form_id=b.form_id
      WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=?";

      $query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester'],$data['sub_id']));
      }else{
      $q="SELECT hm_form.`admn_no` FROM (`hm_form`)
      join reg_regular_form a on a.admn_no=hm_form.admn_no
      WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=?";

      $query=$this->db->query($q,array($aggr_id,$session_year,$session,$data['semester']));
      }
      //    $q
      //  $q=""
      //echo $this->db->last_query(); die();


      $data['stu_admn'] = $query->result();

      return $this->get_name($data);
      } */

    /* public function get_student_prep($data)
      {

      $semester = $data['semester'];

      $aggr_id=$data['aggr_id'];
      $session = $data['session'];
      $session_year=$data['session_year'];
      $q="SELECT `admn_no` FROM (`reg_regular_form`) WHERE `semester` = ? AND `hod_status` = '1' AND `acad_status` = '1' AND `session` =? AND `session_year` = ? and course_aggr_id=?";

      $query=$this->db->query($q,array($semester,$session,$session_year,$aggr_id));
      $data['stu_admn'] = $query->result();
      //echo $this->db->last_query(); die();
      //print_r($data['stu_admn']);
      return $this->get_name($data);

      } */

    public function get_student_prep($data) {
        $session_year = $data['session_year'];
        $semester = $data['semester'];
        $session = $data['session'];
        $this->load->database();
        $this->db->select('admn_no');
        $this->db->from('reg_regular_form');
        $this->db->where('semester', $semester);
        $this->db->where('hod_status', '1');
        //$this->db->where('acad_status','1');
        $this->db->where('session', $session);
        $this->db->where('session_year', $session_year);
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $data['stu_admn'] = $query->result();
        return $this->get_name($data);
    }

    /*
      function get_subject_id($id,$data){
      // echo $data['aggr_id'];

      $q=  $this->db->select('subject_id')->where('id',$id)->get('subjects')->row();
      $r="select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
      $get=$this->db->query($r,array($q->subject_id,$data['aggr_id']))->result();
      //echo $this->db->last_query();
      //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
      return $get;
      } */

    function get_subject_id($id, $data) {
        //echo $id; echo $data['aggr_id'];
        //+++++++++++++++++changed by anuj starts if subject is honours+++++++++++++++++
        //+++++++++++++++++so we need honurs aggr id+++++++++++++++++

        $sql = "select * from course_structure where id=?";
        $get_tmp = $this->db->query($sql, array($id))->result();
        //+++++++++++++++++++++++++++++anuj_end+++++++++++++++++++++++++++++++++++++++++++
        $q = $this->db->select('subject_id')->where('id', $id)->get('subjects')->row();

        $r = "select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
        $get = $this->db->query($r, array($q->subject_id, $data['aggr_id']))->result();
        //+++++++++++++++anuj starts-------------------
        if (empty($get) && ( (strpos($get_tmp[0]->aggr_id, 'honour') !== FALSE ) || (strpos($get_tmp[0]->aggr_id, 'minor') !== FALSE ))) {
            $r = "select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
            $get = $this->db->query($r, array($q->subject_id, $get_tmp[0]->aggr_id))->result();
        }

        //+++++++++++++++anuj ends-------------------
        //echo $this->db->last_query();
        //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
        return $get;
    }

    function getOtherStu($data) {
        $g = "select b.admn_no from reg_exam_rc_subject as a
inner join reg_exam_rc_form as b on a.form_id=b.form_id
where a.sub_id=?
and b.session_year=?
and b.`session`=?
and b.type='R'
and b.reason like '%rep%'
union
select b.admn_no from reg_other_subject as a
inner join reg_other_form as b on a.form_id=b.form_id
where a.sub_id=?
and b.session_year=?
and b.`session`=?
and b.type='R'
and b.reason  like '%rep%'

"
        ;
        $q = $this->db->query($g, array($data['sub_id'], $data['session_year'], $data['session'], $data['sub_id'], $data['session_year'], $data['session']));

        //echo $this->db->last_query();
        //die();
        if ($q->num_rows() > 0) {
            $data['stu_admn'] = $q->result();
            return $this->get_name($data);
        }
    }

    function getOtherSpecialStu($data) {
        $g = "select b.admn_no from reg_exam_rc_subject as a
inner join reg_exam_rc_form as b on a.form_id=b.form_id
where a.sub_id=?
and b.session_year=?
and b.`session`=?
and b.type='S'
union
select b.admn_no from reg_other_subject as a
inner join reg_other_form as b on a.form_id=b.form_id
where a.sub_id=?
and b.session_year=?
and b.`session`=?
and b.type='S'";
        $q = $this->db->query($g, array($data['sub_id'], $data['session_year'], $data['session'], $data['sub_id'], $data['session_year'], $data['session']));

        //echo $this->db->last_query(); //die();
        if ($q->num_rows() > 0) {
            $data['stu_admn'] = $q->result();
            return $this->get_name($data);
        }
    }

    function getjrfStu($data, $type = 'R') {
        $g = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
                                    AND acad_status = '1' and reg_exam_rc_form.type=?) as jrf_reg
                                ON user_details.id = jrf_reg.admn_no ";
        $q = $this->db->query($g, array($data['sub_id'], $data['session'], $data['session_year'], $type));
        //and user_details.dept_id=?
        //,$data['dept_id']
        //echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            $data['stu_admn'] = $q->result();
            return $this->get_name($data);
        }
    }

    function getjrfStuCore($data, $type = 'R') {

        /*   $g="SELECT user_details.id as admn_no
          FROM user_details
          INNER JOIN (SELECT admn_no, jrf_sub.form_id
          FROM reg_exam_rc_form
          INNER JOIN (SELECT *
          FROM reg_exam_rc_subject
          WHERE sub_id = ?) as jrf_sub
          ON jrf_sub.form_id = reg_exam_rc_form.form_id
          WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
          AND reg_exam_rc_form.session = ?
          AND reg_exam_rc_form.session_year=?
          AND reg_exam_rc_form.type=?
          AND acad_status = '1') as jrf_reg
          ON user_details.id = jrf_reg.admn_no where user_details.dept_id=?";
          $q = $this->db->query($g,array($data['sub_id'],$data['session'],$data['session_year'],$type,$data['dept_id']));
         */
        $g = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
									AND reg_exam_rc_form.type=?
                                    AND acad_status = '1') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no";
        $q = $this->db->query($g, array($data['sub_id'], $data['session'], $data['session_year'], $type));

        //and user_details.dept_id=? in parameter ,$data['dept_id']
        //echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            $data['stu_admn'] = $q->result();
            return $this->get_name($data);
        }
    }

    public function get_admn($data) {
        //echo '<pre>';print_r($data);echo '</pre>';die();
        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch = $data['branch_id'];
        $semester = $data['semester'];
        $course = $data['course_id'];

        // print_r($data);
        $this->load->database();
        $tmp = array();
        for ($i = 0; $i < count($data['form_no']); $i++) {
            $tmp[$i] = $data['form_no'][$i]->form_id;
        }
        $drop_stu = "admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $session_year . "'
and a.`session`='" . $session . "' and a.semester=" . $semester . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";

        if ($i > 0) {
            $form_table = 'reg_regular_form';
            if ($session === 'Summer')
                $form_table = 'reg_summer_form';
            $this->db->select('admn_no');
            $this->db->from($form_table);
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch);
            $this->db->where('course_id', $course);
            if ($session != 'Summer') {
                $this->db->where('semester', $semester);
                $this->db->where('course_aggr_id', $data['aggr_id']);
            }
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
			 if ($data['option'] <> 'marks_upload')  // drop students reuired  to display in  list in  case of marks upload but not  in case of  attendnace  upload @ 15-5-19 @rituraj
            $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
            $this->db->where_in('form_id', $tmp);
            //$this->db->limit('5');

            $query = $this->db->get();
            //echo $this->db->last_query(); die();
            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
        } else {
            $this->db->select('admn_no');
            $this->db->from('reg_regular_form');
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch);
            $this->db->where('course_id', $course);
            $this->db->where('semester', $semester);
            $this->db->where('course_aggr_id', $data['aggr_id']);
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
			if ($data['option'] <> 'marks_upload')  // drop students reuired  to display in  list in  case of marks upload but not  in case of  attendnace  upload @ 15-5-19 @rituraj
            $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
            //$this->db->limit('5');
            $query = $this->db->get();
            //echo $this->db->last_query();die();
            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
        }
    }

    public function get_name($data) {
        $tmp = array();
        for ($i = 0; $i < count($data['stu_admn']); $i++) {
            //	echo $data['form_no'][$i]->sem_form_id;
            $tmp[$i] = $data['stu_admn'][$i]->admn_no;
        }
        if ($i > 0) {
            $this->load->dbutil();
            $this->load->database();
            $this->db->select('id,first_name,middle_name,last_name');
            $this->db->from('user_details');
            //	after('id','2012');
            $this->db->where_in('id', $tmp);
            $this->db->order_by("id", "asc");

            $query = $this->db->get();

            //print_r($query->result());
            return $query->result();
        }
    }

    //for repeaters student

    public function get_rep_student($data) {
        $sub_id = $data['sub_id'];

        $this->load->database();
        $this->db->select('form_id');
        $this->db->from('reg_other_subject');
        $this->db->where('sub_id', $sub_id);

        $query = $this->db->get();
        $data['rep_form_no'] = $query->result();
        //if(count($data['rep_form_no'])!=0)
        return $this->get_rep_admn($data);
        //else
        //	return $data['rep_form_no'];
    }

    //----

    function get_rep_student_new($data, $sy, $s, $c, $b, $se, $agr_id, $mid, $sub_id) {
        //$data['session_year'], $data['session'], $data['course_id'], $data['branch_id'], $data['semester'], $data['aggr_id'], $this->input->post('id'),$data['sub_id']
        $q = "SELECT x.`form_id` FROM `reg_other_form` x join reg_other_subject sub on x.form_id=sub.form_id and sub.sub_id=?

	  and  x.`session_year` =? AND x.`session` = ? AND x.`branch_id` = ? AND x.`course_id` = ?   AND x.`hod_status` = '1' AND x.`acad_status` = '1'
      and x.admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=?)  group by x.`admn_no`";
        //      if($this->session->userdata('id')=='958') { echo $q;}
        $d = $this->db->query($q, array($sub_id, $sy, $s, $b, $c, $mid));
        // if($this->session->userdata('id')=='958'){ echo $this->db->last_query(); die();}
        if ($d->num_rows() > 0) {
            $data['rep_form_no'] = $d->result();
            //if(count($data['rep_form_no'])!=0)
            return $this->get_rep_admn($data);
        }
        return false;
    }

    function get_rep_student_comm_new($data, $sy, $s, $se, $sec, $agr_id, $mid) {
        $q = "SELECT `reg_other_form`.`form_id` FROM (`reg_other_form`) JOIN `stu_section_data` ON `stu_section_data`.`admn_no`=`reg_other_form`.`admn_no` WHERE `reg_other_form`.`hod_status` = '1' AND `reg_other_form`.`acad_status` = '1'
	  AND `reg_other_form`.`session_year` = ? AND `reg_other_form`.`session` = ? AND `reg_other_form`.`semester` = ? AND `stu_section_data`.`section` = ? and
	  reg_other_form.admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=?)";

        $d = $this->db->query($q, array($sy, $s, $se, $sec, $mid));
        if ($d->num_rows() > 0) {
            $data['rep_form_no'] = $d->result();
            //if(count($data['rep_form_no'])!=0)
            return $this->get_rep_admn_comm($data);
        }
        return false;
    }

//===============================common other starts===============================
    /* public function get_rep_student_comm($data)
      {


      $sql = "select z.form_id from
      (select b.*,c.aggr_id,c.semester,
      CASE WHEN c.semester='1_1' THEN 'A' WHEN c.semester='1_2' THEN 'E' END as section
      from reg_other_subject b
      inner join reg_other_form a on a.form_id=b.form_id
      inner join course_structure c on c.id=b.sub_id
      where a.session_year=? and a.`session`=?
      and c.aggr_id like '%comm%' and a.hod_status='1' and a.acad_status='1')z where z.section=? and z.sub_id=?
      ";

      $query = $this->db->query($sql,array($data['session_year'],$data['sesssion'],$data['section'],$data['sub_id']));


      $data['rep_form_no'] = $query->result();
      //if(count($data['rep_form_no'])!=0)
      return $this->get_rep_admn_comm($data);
      //else
      //	return $data['rep_form_no'];
      } */
    public function get_rep_student_comm($data) {
        //print_r($data);die();
        if ($data['session'] == "Monsoon") {
            $x1 = '1_1';
            $y1 = '1_2';
        }
        if ($data['session'] == "Winter") {
            $x1 = '2_1';
            $y1 = '2_2';
        }

        $sql = "select z.form_id from
(select b.*,c.aggr_id,c.semester,
CASE WHEN c.semester='" . $x1 . "' THEN 'A' WHEN c.semester='" . $y1 . "' THEN 'E' END as section
 from reg_other_subject b
inner join reg_other_form a on a.form_id=b.form_id
inner join course_structure c on c.id=b.sub_id
where a.session_year=? and a.`session`=?
and c.aggr_id like '%comm%' and a.hod_status='1' and a.acad_status='1')z where z.section=? and z.sub_id=?
";

        $query = $this->db->query($sql, array($data['session_year'], $data['session'], $data['section'], $data['sub_id']));

        //echo $this->db->last_query();die();
        $data['rep_form_no'] = $query->result();
        //if(count($data['rep_form_no'])!=0)
        return $this->get_rep_admn_comm($data);
        //else
        //	return $data['rep_form_no'];
    }

    //===============================common other ends===============================
    //===============================common other starts===============================

    public function get_rep_admn_comm($data) {
        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch_id = $data['branch_id'];
        $semester = $data['semester'];
        $course_id = $data['course_id'];
        $this->load->database();
        $tmp = array();
        for ($i = 0; $i < count($data['rep_form_no']); $i++) {
            $tmp[$i] = $data['rep_form_no'][$i]->form_id;
        }
        if ($i > 0) {
            $this->db->select('admn_no');
            $this->db->from('reg_other_form');
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->like('reason', 'rep');
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
            $this->db->where_in('form_id', $tmp);
            // $this->db->where('course_aggr_id',$data['aggr_id']);
            $query = $this->db->get();
            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
            //else
            //return $data['stu_rep_admn'];
        }
    }

//===============================common other ends===============================
    public function get_rep_admn($data) {
        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch_id = $data['branch_id'];
        $semester = $data['semester'];
        $course_id = $data['course_id'];
        $this->load->database();
        $tmp = array();
        for ($i = 0; $i < count($data['rep_form_no']); $i++) {
            $tmp[$i] = $data['rep_form_no'][$i]->form_id;
        }
        if ($i > 0) {
            $this->db->select('admn_no');
            $this->db->from('reg_other_form');
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch_id);
            $this->db->where('course_id', $course_id);
            $this->db->where('semester', $semester);
            $this->db->like('reason', 'rep');
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
            $this->db->where_in('form_id', $tmp);
            // $this->db->where('course_aggr_id',$data['aggr_id']);
            $query = $this->db->get();
            //echo $this->db->last_query();
            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
            //else
            //return $data['stu_rep_admn'];
        }
    }

    public function get_rep_name($data) {
        $tmp = array();
        for ($i = 0; $i < count($data['stu_rep_admn']); $i++) {
            $tmp[$i] = $data['stu_rep_admn'][$i]->admn_no;
        }
        if ($i > 0) {
            $this->load->dbutil();
            $this->load->database();
            $this->db->select('id,first_name,middle_name,last_name');
            $this->db->from('user_details');
            //	$this->db->like('id','2012');
            $this->db->where_in('id', $tmp);
            $this->db->order_by("id", "asc");
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function get_subject_name($data = '') {
        $sub_id = $data['sub_id'];
        $this->load->database();
        $this->db->select('name');
        $this->db->from('subjects');
        $this->db->where('id', $sub_id);
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_class($data) {
        //print_r($data); die();

        /* $sub_id=$data['sub_id'];
          $this->load->database();
          $this->db->select('map_id');
          $this->db->from('subject_mapping_des');
          $this->db->where('sub_id',$sub_id);
          $query=$this->db->get();
          $tmp=$query->result();
          if(count($tmp)!=0)
          $map_id=$tmp[0]->map_id;
          else
          return $tmp; */
        if (isset($data['map_id'])) {
            $this->db->select('semester,course_id,branch_id');
            $this->db->from('subject_mapping');
            $this->db->where('map_id', $data['map_id']);

            $query = $this->db->get();
            // echo $this->db->last_query();
            return $query->result();
        }
    }

    public function get_course($data) {

        $branch = $data['branch'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $semester = $data['semester'];

        $form_table = 'reg_regular_form';
        if ($session === 'Summer')
            $form_table = 'reg_summer_form';
        $this->load->database();
        $this->db->select('course_id');
        $this->db->from($form_table);
        $this->db->where('branch_id', $branch);
        $this->db->where('semester', $semester);
        $this->db->where('session_year', $session_year);
        $this->db->where('session', $session);
        $this->db->distinct();
        $query = $this->db->get();
        $course_id = $query->result();
        if (count($course_id) != 0)
            return $this->get_course_name($course_id);
        else
            return $course_id;
    }

    public function get_course_name($course_id) {
        $course_id_arr = array();
        for ($i = 0; $i < count($course_id); $i++)
            $course_id_arr[$i] = $course_id[$i]->course_id;
        $this->load->database();
        $this->db->select('id,name');
        $this->db->from('courses');
        $this->db->where_in('id', $course_id_arr);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_course_name_again($course_id) {
        $this->load->database();
        $this->db->select('name');
        $this->db->from('courses');
        $this->db->where_in('id', $course_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_map_id($data) {
        $sub_id = $data['sub_id'];
        $emp_no = $data['emp_id'];
        $this->load->database();
        $this->db->select('map_id');
        $this->db->from('subject_mapping_des');
        $this->db->where('sub_id', $sub_id);
        $this->db->where('emp_no', $emp_no);
        $query = $this->db->get();
        //print_r($query->result());
        return $query->result();
    }

    public function get_session_id($session, $session_year, $semester, $branch_id, $course_id) {
        $this->load->database();
        $this->db->select('session_id');
        $this->db->from('session_track');
        $this->db->where('session', $session);
        $this->db->where('session_year', $session_year);
        $this->db->where('semester', $semester);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('course_id', $course_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_into_session_track($session, $session_year, $semester, $branch_id, $course_id) {
        $this->load->database();
        $query = "INSERT INTO session_track (session,session_year,semester,branch_id,course_id) VALUES('$session','$session_year',$semester,'$branch_id','$course_id')";
        $this->db->query($query);
        //   echo   $this->db->affected_rows();
        //    echo $this->db->last_query();
        //   echo $this->db->_error_message(); die();
    }

    public function insert_into_class_engaged($session_id, $map_id, $sub_id, $date, $timestamp, $group_no, $class_no, $section = '') {
        // echo $section; die();
        if ($section) {
            $query = "INSERT INTO class_engaged (session_id,map_id,sub_id,date,timestamp,group_no,class_no,section) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no,'$section')";
        } else {
            $query = "INSERT INTO class_engaged (session_id,map_id,sub_id,date,timestamp,group_no,class_no) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no)";
        }
        $this->db->query($query);
    }

    public function insert_into_class_engaged_dd_hons($session_id, $map_id, $sub_id, $date, $timestamp, $group_no, $class_no, $section = '') {
        // echo $section; die();
        if ($section) {
            $query = "INSERT INTO class_engaged_dd_hons (session_id,map_id,sub_id,date,timestamp,group_no,class_no,section) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no,'$section')";
        } else {
            $query = "INSERT INTO class_engaged_dd_hons (session_id,map_id,sub_id,date,timestamp,group_no,class_no) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no)";
        }
        $this->db->query($query);
    }

    public function insert_into_class_engaged_jrf($session_id, $map_id, $sub_id, $date, $timestamp, $group_no, $class_no, $section = '') {
        // echo $section; die();
        if ($section) {
            $query = "INSERT INTO class_engaged_jrf (session_id,map_id,sub_id,date,timestamp,group_no,class_no,section) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no,'$section')";
        } else {
            $query = "INSERT INTO class_engaged_jrf (session_id,map_id,sub_id,date,timestamp,group_no,class_no) VALUES($session_id,$map_id,'$sub_id','$date','$timestamp',$group_no,$class_no)";
        }
        $this->db->query($query);
    }

    public function get_total_class($map, $sub, $session_id, $group_no, $section = '') {

        $this->load->database();
        $this->db->select('total_class');
        $this->db->from('total_class_table');
        $this->db->where('map_id', $map);
        $this->db->where('sub_id', $sub);
        $this->db->where('session_id', $session_id);
        $this->db->where('group_no', $group_no);
        if ($section) {
            $this->db->where('section', $section);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();die();
        return $query->result();
    }

    public function get_total_class_dd_hons($map, $sub, $session_id, $group_no, $section = '') {

        $this->load->database();
        $this->db->select('total_class');
        $this->db->from('total_class_table_dd_hons');
        $this->db->where('map_id', $map);
        $this->db->where('sub_id', $sub);
        $this->db->where('session_id', $session_id);
        $this->db->where('group_no', $group_no);
        if ($section) {
            $this->db->where('section', $section);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();die();
        return $query->result();
    }

    public function get_total_class_jrf($map, $sub, $session_id, $group_no, $section = '') {

        $this->load->database();
        $this->db->select('total_class');
        $this->db->from('total_class_table_jrf');
        $this->db->where('map_id', $map);
        $this->db->where('sub_id', $sub);
        $this->db->where('session_id', $session_id);
        $this->db->where('group_no', $group_no);
        if ($section) {
            $this->db->where('section', $section);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();die();
        return $query->result();
    }

    public function get_total_group($session_id, $sub_id, $map_id, $section = '') {
        $this->load->database();
        $this->db->select('*');
        $this->db->from('prac_group_attendance');
        $this->db->where('session_id', $session_id);
        $this->db->where('sub_id', $sub_id);
        $this->db->where('map_id', $map_id);
        if ($section) {
            $this->db->where('section', $section);
        }
        $this->db->order_by("group_no", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    /* public function get_last_submisssion_date($map,$sub,$session_id,$group_no,$section='')
      {
      $this->load->database();
      $this->db->select('date');
      $this->db->from('class_engaged');
      $this->db->where('map_id',$map);
      $this->db->where('sub_id',$sub);
      $this->db->where('session_id',$session_id);
      $this->db->where('group_no',$group_no);
      if($section){
      $this->db->where('section',$section);
      }
      $this->db->order_by("date","desc");
      $query=$this->db->get();
      return $query->result();
      } */

    public function get_last_submisssion_date($map, $sub, $session_id, $group_no) {
        $this->load->database();
        $query = $this->db->query('select date_format(max(str_to_date(a.`date`,"%d-%m-%Y")),"%d-%m-%Y") as `date` from class_engaged a where a.map_id=? and a.sub_id=? and a.session_id=? and group_no=?', array($map, $sub, $session_id, $group_no));
        return $query->result();
    }

    public function get_last_submisssion_date_dd_hons($map, $sub, $session_id, $group_no) {
        $this->load->database();
        $query = $this->db->query('select date_format(max(str_to_date(a.`date`,"%d-%m-%Y")),"%d-%m-%Y") as `date` from class_engaged_dd_hons a where a.map_id=? and a.sub_id=? and a.session_id=? and group_no=?', array($map, $sub, $session_id, $group_no));
        return $query->result();
    }

    public function get_last_submisssion_date_jrf($map, $sub, $session_id, $group_no) {
        $this->load->database();
        $query = $this->db->query('select date_format(max(str_to_date(a.`date`,"%d-%m-%Y")),"%d-%m-%Y") as `date` from class_engaged_jrf a where a.map_id=? and a.sub_id=? and a.session_id=? and group_no=?', array($map, $sub, $session_id, $group_no));
        return $query->result();
    }

    public function insert_into_total_class_table($map, $sub_id, $session_id1, $num_class, $timestamp, $group_no, $section = '') {
        //echo $section; die();
        if ($section) {
            $query = "INSERT INTO total_class_table (map_id,sub_id,session_id,total_class,timestamp,group_no,section) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no,'$section')";
        } else {
            $query = "INSERT INTO total_class_table (map_id,sub_id,session_id,total_class,timestamp,group_no) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no)";
        }

        $this->db->query($query);
    }

    public function insert_into_total_class_table_dd_hons($map, $sub_id, $session_id1, $num_class, $timestamp, $group_no, $section = '') {
        //echo $section; die();
        if ($section) {
            $query = "INSERT INTO total_class_table_dd_hons (map_id,sub_id,session_id,total_class,timestamp,group_no,section) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no,'$section')";
        } else {
            $query = "INSERT INTO total_class_table_dd_hons (map_id,sub_id,session_id,total_class,timestamp,group_no) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no)";
        }

        $this->db->query($query);
    }

    public function insert_into_total_class_table_jrf($map, $sub_id, $session_id1, $num_class, $timestamp, $group_no, $section = '') {
        //echo $section; die();
        if ($section) {
            $query = "INSERT INTO total_class_table_jrf (map_id,sub_id,session_id,total_class,timestamp,group_no,section) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no,'$section')";
        } else {
            $query = "INSERT INTO total_class_table_jrf (map_id,sub_id,session_id,total_class,timestamp,group_no) VALUES($map,'$sub_id',$session_id1,$num_class,'$timestamp',$group_no)";
        }

        $this->db->query($query);
    }

    public function update_into_total_class_table($map, $sub_id, $session_id1, $num_class, $group_no, $section = '') {
        if ($section) {
            $query = "UPDATE total_class_table SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no='$group_no' AND section='$section'";
        } else {
            $query = "UPDATE total_class_table SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no=$group_no";
        }

        $this->db->query($query);
    }

    public function update_into_total_class_table_dd_hons($map, $sub_id, $session_id1, $num_class, $group_no, $section = '') {
        if ($section) {
            $query = "UPDATE total_class_table_dd_hons SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no='$group_no' AND section='$section'";
        } else {
            $query = "UPDATE total_class_table_dd_hons SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no=$group_no";
        }

        $this->db->query($query);
    }

    public function update_into_total_class_table_jrf($map, $sub_id, $session_id1, $num_class, $group_no, $section = '') {
        if ($section) {
            $query = "UPDATE total_class_table_jrf SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no='$group_no' AND section='$section'";
        } else {
            $query = "UPDATE total_class_table_jrf SET total_class=$num_class WHERE map_id=$map AND sub_id='$sub_id' AND session_id='$session_id1' AND group_no=$group_no";
        }

        $this->db->query($query);
    }

    public function insert_into_absent_table($admn, $map, $sub, $session_id, $date, $timestamp, $group_no, $class_no, $section = '') {
        if ($section) {
            $query = "INSERT INTO absent_table (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no,section) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no,'$section')";
        } else {
            $query = "INSERT INTO absent_table (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no)";
        }

        $this->db->query($query);
        // echo $this->db->last_query();die();
    }

    //===================================TA==========================================
    public function insert_into_absent_table_ta($admn, $map, $sub, $session_id, $date, $timestamp, $group_no, $class_no, $section = '', $uid) {
        if ($section) {
            $query = "INSERT INTO absent_table_ta (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no,section,user_id) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no,'$section','$uid')";
        } else {
            $query = "INSERT INTO absent_table_ta (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no,user_id) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no,'$uid')";
        }

        $this->db->query($query);
        // echo $this->db->last_query();die();
    }

    //=======================================================================================
    public function insert_into_absent_table_dd_hons($admn, $map, $sub, $session_id, $date, $timestamp, $group_no, $class_no, $section = '') {
        if ($section) {
            $query = "INSERT INTO absent_table_dd_hons (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no,section) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no,'$section')";
        } else {
            $query = "INSERT INTO absent_table_dd_hons (admn_no,map_id,sub_id,session_id,date,timestamp,Remark,group_no,class_no) VALUES('$admn',$map,'$sub',$session_id,'$date','$timestamp','none',$group_no,$class_no)";
        }

        $this->db->query($query);
        // echo $this->db->last_query();die();
    }

    public function get_absent($admn, $map, $sub, $session_id, $section = '') {
        if ($section) {
            $q = "select count(date) as date FROM absent_table WHERE map_id = $map AND sub_id = '$sub' AND admn_id = '$admn' AND session_id = $session_id AND (Remark='none' || Remark='late_reg') AND section='$section'";
        } else {
            $q = "select count(date) as date FROM absent_table WHERE map_id = $map AND sub_id = '$sub' AND admn_id = '$admn' AND session_id = $session_id AND (Remark='none' || Remark='late_reg')";
        }

        $query = $this->db->query($q);
        return $query->result();
    }

    public function send_dsw($admission_id, $map_id, $sub_id, $session_id, $status, $section = '') {
        if ($section) {
            $this->db->query("UPDATE absent_table SET status=$status WHERE admn_id='$admission_id' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id AND section='$section'");
        } else {
            $this->db->query("UPDATE absent_table SET status=$status WHERE admn_id='$admission_id' AND map_id=$map_id AND sub_id='$sub_id' AND session_id=$session_id");
        }
    }

    public function insert_into_defaulter_table($session_year, $session, $branch_id, $course_id, $semester, $sub_id, $admn, $name, $percentage, $remark, $section = '') {
        if ($section) {
            $query = "INSERT INTO defaulter_table (session_year,session,branch_id,course_id,semester,subject_id,admission_id,name,percentage,Remark,section) VALUES ('$session_year','$session','$branch_id','$course_id',$semester,'$sub_id','$admn','$name','$percentage','$remark','$section')";
        } else {
            $query = "INSERT INTO defaulter_table (session_year,session,branch_id,course_id,semester,subject_id,admission_id,name,percentage,Remark) VALUES ('$session_year','$session','$branch_id','$course_id',$semester,'$sub_id','$admn','$name','$percentage','$remark')";
        }

        $this->db->query($query);
    }

    public function delete_record_from_defaulter_table($session_year, $session, $branch_id, $course_id, $semester, $sub_id, $section = '') {
        if ($section) {
            $query = "DELETE FROM defaulter_table WHERE session_year='$session_year' AND session='$session' AND branch_id='$branch_id' AND course_id='$course_id' AND semester=$semester AND subject_id='$sub_id' AND section='$section'";
        } else {
            $query = "DELETE FROM defaulter_table WHERE session_year='$session_year' AND session='$session' AND branch_id='$branch_id' AND course_id='$course_id' AND semester=$semester AND subject_id='$sub_id'";
        }

        $this->db->query($query);
    }

    //for repeaters student

    /* public function get_rep_student($data)
      {
      $subject=$data['subject'];

      $this->load->database();
      $this->db->select('sem_form_id');
      $this->db->from('stu_other_sem_reg_subject');
      $this->db->where('sub_id',$subject);
      $query=$this->db->get();
      $data['rep_form_no'] = $query->result();
      return $this->get_rep_admn($data);
      }

      public function get_rep_admn($data)
      {
      $session_year = $data['session_year'];
      $session=$data['session'];
      $branch=$data['branch'];
      $semester=$data['class_res'][0]->semester;
      $course=$data['course'];
      $this->load->database();
      $tmp=array();
      for($i=0;$i<count($data['rep_form_no']);$i++)
      {
      $tmp[$i]=$data['rep_form_no'][$i]->sem_form_id;
      }
      if($i>0)
      {
      $this->db->select('admission_id');
      $this->db->from('stu_other_sem_reg_form');
      $this->db->where('session_year',$session_year);
      $this->db->where('session',$session);
      $this->db->where('branch_id',$branch);
      $this->db->where('course_id',$course);
      $this->db->where('semster',$semester);
      $this->db->like('reason','repeater');
      //			$this->db->where('hod_status','1');
      //			$this->db->where('acdmic_status','1');
      $this->db->where_in('sem_form_id',$tmp);
      $query=$this->db->get();
      $data['stu_rep_admn'] = $query->result();
      return $this->get_rep_name($data);
      }
      }

      public function get_rep_name($data)
      {
      $tmp=array();
      for($i=0;$i<count($data['stu_rep_admn']);$i++)
      {
      $tmp[$i]=$data['stu_rep_admn'][$i]->admission_id;
      }
      if($i>0)
      {
      $this->load->dbutil();
      $this->load->database();
      $this->db->select('id,first_name,middle_name,last_name');
      $this->db->from('user_details');
      //	$this->db->like('id','2012');
      $this->db->where_in('id',$tmp);
      $this->db->order_by("id","asc");
      $query=$this->db->get();
      return $query->result();
      }
      }
     */

    //==============JRF Special+++++++++++++++++++
    function getjrfStuCore_spl($data, $type = 'S') {
        $g = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
									AND reg_exam_rc_form.type=?
                                    AND acad_status = '1') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no /*where user_details.dept_id=?*/";
        $q = $this->db->query($g, array($data['sub_id'], $data['session'], $data['session_year'], $type, $data['dept_id']));
        //echo $this->db->last_query();die();
        if ($q->num_rows() > 0) {
            $data['stu_admn'] = $q->result();
            return $this->get_name($data);
        }
    }

    //++++++++++++++++++++++++++++++++++++++++++++++
    function getstartgroup($subid, $groupno, $sessionid = null, $map = null) {

        if ($sessionid != null) {
            $q = "select group_start from prac_group_attendance a where a.sub_id=? and a.group_no=? and a.session_id=? and a.map_id=?";
            $r = $this->db->query($q, array($subid, $groupno, $sessionid, $map));
        } else {
            $q = "select group_start from prac_group_attendance a where a.sub_id=? and a.group_no=? and a.map_id=?";
            $r = $this->db->query($q, array($subid, $groupno, $map));
        }
        if ($r->num_rows() > 0) {
            return $r->row()->group_start;
        }
    }

    /*   function getstartgroup($subid,$groupno,$sessionid=null){

      if($sessionid!=null){
      $q="select group_start from prac_group_attendance a where a.sub_id=? and a.group_no=? and a.session_id=?";
      $r= $this->db->query($q,array($subid,$groupno,$sessionid));
      }
      else{
      $q="select group_start from prac_group_attendance a where a.sub_id=? and a.group_no=?";
      $r= $this->db->query($q,array($subid,$groupno));
      }
      if($r->num_rows() > 0){
      return $r->row()->group_start;
      }
      }
     */

    //====================================JRF attendance upload============================

    public function get_student_jrf($data) {
        //echo '<pre>';print_r($data);echo '</pre>'; die();

        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch = $data['branch_id'];
        $semester = $data['semester'];
        $course = $data['course_id'];
        $this->load->database();


        //@anuj fro drop student 16-08-2018
        $drop_stu = "admn_no not in (
select a.admn_no from stu_exam_absent_mark a
where a.course_aggr_id='" . trim($data['aggr_id']) . "' and a.session_year='" . $session_year . "'
and a.`session`='" . $session . "' and a.semester=" . $semester . " and a.sub_id='" . $data['sub_id'] . "' and a.`status`='B')";


        $this->db->select('reg_exam_rc_form.admn_no');
        $this->db->from('reg_exam_rc_form');
        $this->db->join('reg_exam_rc_subject', 'reg_exam_rc_subject.form_id = reg_exam_rc_form.form_id');
        $this->db->where('reg_exam_rc_form.session_year', $session_year);
        $this->db->where('reg_exam_rc_form.session', $session);
        //$this->db->where('reg_exam_rc_form.branch_id',$branch);
        //$this->db->where('reg_exam_rc_form.course_id',$course);
        $this->db->where('reg_exam_rc_subject.sub_id', $data['sub_id']);
        $this->db->where('hod_status', '1');
        $this->db->where('acad_status', '1');
        $this->db->where($drop_stu); //@anuj fro drop student 16-08-2018
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $data['stu_admn'] = $query->result();
        return $this->get_name($data);
    }

}

?>