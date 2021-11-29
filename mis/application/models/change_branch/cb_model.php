<?php

class Cb_model extends CI_Model {

    private $cb_log = "change_branch_log";
    private $cb_option = "change_branch_option";
    private $result = "resultdata";
    private $stu_academic = "stu_academic";
    private $stu_dept = "user_details";
    private $reg_regular = "reg_regular_form";
    private $c_branch = 'course_branch';
    private $branch = 'branches';
    private $dateOpenCloseTbl = 'sem_date_open_close_tbl';
    private $feed_back = 'fb_student_feedback';
    private $flag = 2;

    function __construct() {
        parent::__construct();
    }

    function getnameById($id) {
        //echo $id;
        $sql = 'select concat_ws(" ",a.first_name,a.middle_name,a.last_name) as name from user_details a where a.id=?';

        $q = $this->db->query($sql, array($id));
        return $q->row();
    }

    function insertCBLog($data) {
        if ($this->db->insert($this->cb_log, $data)) {
            return $this->db->insert_id();
        }
    }

    function updateCBlog($id, $data) {
        $this->db->update($this->cb_log, $data, array('id' => $id));
        return true;
    }

    function delcb($id, $sid) {
        $this->db->trans_start();
        $this->db->delete($this->cb_log, array('id' => $id, 'admn_no' => $sid));
        $this->db->delete($this->cb_option, array('cb_log_id' => $id));
        $this->db->trans_complete();
        return true;
    }

    function insertCBOption($data) {
        if ($this->db->insert($this->cb_option, $data)) {
            return true;
        }
    }

    function updateCBOption($id, $data) {
        if (!is_array($id)) {
            $this->db->update($this->cb_option, $data, array('cb_log_id' => $id));
            return true;
        } else {
            //echo 'bye';
            $this->db->update($this->cb_option, $data, $id);
            return true;
        }
        return false;
    }

    function checkfillStudent($id) {
        $q = $this->db->get_where($this->cb_log, array('admn_no' => $id));
        // echo $this->db->last_query(); 
        if ($q->num_rows() > 0) {
            return $q->row()->id;
        }
        return false;
    }

    function getCbById($id, $stu) {
        $q = $this->db->get_where($this->cb_log, array('id' => $id, 'admn_no' => $stu));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    function getCbDesById($id) {
        $q = $this->db->get_where($this->cb_option, array('cb_log_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    function getEligibleStudent($id,$sy) {
        // echo $id." ".$cat." ".$pd;

        $pstatus = $this->get_stu_pass_status($id, $sy);
      
        if (empty($pstatus)) {
            return false;
        } else {
            return true;
        }
        
    }

    function get_stu_pass_status($id, $sy) {
        $sql = "select a.* from final_semwise_marks_foil_freezed a where a.admn_no=? and a.semester=1 and a.`status`='PASS'
and a.session_yr=? and a.`session`='Monsoon'";
        $query = $this->db->query($sql, array($id, $sy));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }

    function getEligiblCourse($id) {
        if ($id == 'b.tech' || $id == 'dualdegree' || $id == 'int.m.tech' || $id == 'int.msc.tech' || $id == 'int.m.sc') {
            return true;
        } else {
            return false;
        }
    }

    function stuAcademicChange($admn_no, $c, $b) {
        $this->db->update($this->stu_academic, array('course_id' => $c, 'branch_id' => $b, 'semester' => '2'), array('admn_no' => $admn_no));
        $this->db->update($this->feed_back, array('course_id' => $c, 'branch_id' => $b), array('admn_no' => $admn_no));
    }

    function studeptChange($admn_no, $dept) {
        $this->db->update($this->stu_dept, array('dept_id' => $dept), array('id' => $admn_no));
        $this->db->delete($this->reg_regular, array('admn_no' => $admn_no, 'semester' => '3'));
    }

    function getBranchByCourse($id) {
        $q = $this->db->query('select branch_id , name from ' . $this->c_branch . ' as c_b  join ' . $this->branch . ' as b on c_b.branch_id=b.id where c_b.course_id="' . $id . '"');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    /// ------ Academic End ------------///
    function getChangeBranchList($sy = '') {
        $sy = ($sy) ? $sy : date('Y') . "-" . (date('Y') + 1);
        $this->db->where('session_year', $sy);
        $q = $this->db->get($this->cb_log);

        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    function getStudentById($id) {
        $q = $this->db->query('SELECT *
FROM (stu_academic INNER JOIN stu_details ON stu_academic.admn_no = stu_details.admn_no) INNER JOIN user_details ON stu_academic.admn_no = user_details.id
WHERE (((user_details.id)="' . $id . '"));
');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    ///


    public function getOcdate() {
        date_default_timezone_set('Asia/Calcutta');
        $sy = (date('Y') . "-" . (date('Y') + 1));
        $dt = date("Y-m-d");
        $myquery = "SELECT * FROM (`sem_date_open_close_tbl`) 
WHERE `exam_type` = 'cb' AND `session_year` = ? AND (? BETWEEN normal_start_date AND normal_close_date)
ORDER BY `normal_close_date` DESC";
        $query = $this->db->query($myquery, array($sy, $dt));

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function getOcdatedes() {
        return $this->db->query("SELECT * FROM `" . $this->hm_date_des . "` WHERE `des_id`=(SELECT max(`des_id`) FROM `" . $this->hm_date_des . "`) and date_id=2")->result();
    }

    public function checkDate() {
        date_default_timezone_set("Asia/Kolkata");
        $sdate = $this->getOcdate();
        $sd = $sdate->normal_start_date;
        $cd = $sdate->normal_close_date;
        $cdate = strtotime(date('Y-m-d'));
        $sdate = strtotime($sd);
        $closedate = strtotime($cd);


        if ($cdate >= $sdate && $cdate <= $closedate) {

            return true;
        }
        return false;
    }

    public function insertDateDes($data) {

        $this->db->insert($this->hm_date_des, $data);
        if ($this->db->_error_message()) {
            return $this->db->_error_message();
        } else {
            return true;
        }
    }

    /// End Date Set////	
    private function functionallyEmpty($o) {
        if (empty($o))
            return true;
        else if (is_numeric($o))
            return false;
        else if (is_string($o))
            return !strlen(trim($o));
        else if (is_object($o))
            return $this->functionallyEmpty((array) $o);

        // It's an array!
        foreach ($o as $element)
            if ($this->functionallyEmpty($element))
                continue; // so far so good.
            else
                return false;

        // all good.
        return true;
    }

    function getchangeBranchstatus($id, $sy) {
        $q = "select * from change_branch_log a where a.admn_no=? and a.`session`='Monsoon' and a.session_year=? and a.acad_status='A' ";

        $query = $this->db->query($q, array($id, $sy));
        if ($query->num_rows > 0) {
            return true;
        }
        return false;
    }

    function getCbSessionYear() {
        $this->db->select('DISTINCT(session_year)');
        $query = $this->db->get($this->cb_log);

        if ($query->num_rows > 0) {
            return $this->singleDiamentionArray($query->result());
        }
        return $r[date('Y') . "-" . (date('Y') + 1)] = date('Y') . "-" . (date('Y') + 1);
    }

    function singleDiamentionArray($arr) {
        foreach ($arr as $a) {
            $r[$a->session_year] = $a->session_year;
        }
        // $r[date('Y')."-".(date('Y')+1)] = date('Y')."-".(date('Y')+1);
        return $r;
    }

    function get_dept_id($c, $b) {
        $sql = "select distinct b.dept_id from course_branch a inner join dept_course b on a.course_branch_id=b.course_branch_id
where a.course_id=? and a.branch_id=?  ORDER BY b.date DESC LIMIT 1";

        $query = $this->db->query($sql, array($c, $b));

         //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->dept_id;
        } else {
            return false;
        }
    }

    function get_student_details($id, $sy) {
        $sql = "select a.*,b.dept_id,c.section from reg_regular_form a 
inner join user_details b  on b.id=a.admn_no
inner join stu_section_data c on c.admn_no=a.admn_no and a.session_year=c.session_year
where a.admn_no=? and a.session_year=?
and a.hod_status='1' and a.acad_status='1' and a.semester=2";
        $query = $this->db->query($sql, array($id, $sy));
        // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function get_student_list($id,$rtype){
        if($rtype=='approved'){ $p=" and b.offered='1'"; }
        if($rtype=='fulllist'){ $p=""; }
        
        $sql = "select 
a.admn_no,
concat_ws(' ',c.first_name,c.middle_name,c.last_name)as stu_name,
a.current_dept_id,
a.current_course_id,
a.current_branch_id,
a.session_year,
a.acad_status,
a.acad_id,
concat_ws('-',b.dept_id,b.course_id,b.branch_id,b.offered)as choice
from change_branch_log a 
inner join change_branch_option b on b.cb_log_id=a.id
inner join user_details c on c.id=a.admn_no
where a.session_year=?".$p;
        $query = $this->db->query($sql, array($id));
         //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_result_gpa($id) {
        $sql = "select a.`status` as passfail,a.core_gpa as gpa from final_semwise_marks_foil a where a.admn_no=? ";
        $query = $this->db->query($sql, array($id));
        // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
