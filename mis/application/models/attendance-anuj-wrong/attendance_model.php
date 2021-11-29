<?php

class Attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_session_year() {
        $emp_id = $this->session->userdata('id');
        $this->load->database();
        $query = $this->db->query("SELECT DISTINCT session_year
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id 
								where B.session_year <> 'false'
								ORDER BY session_year;");
        return $query->result();
    }

    public function get_session_year_exam() {

        $this->load->database();
        $query = $this->db->query("SELECT DISTINCT session_year
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id 								
								ORDER BY session_year;");
        return $query->result();
    }

    public function get_subjects($data = '') {
        $emp_id = $this->session->userdata('id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];
            $q = "SELECT S.subject_id as s_id, name, newt.sub_id as n_id, newt.semester
							FROM (SELECT session,session_year,sub_id, B.course_id, semester, emp_no, A.map_id
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id) AS newt
							INNER JOIN subjects AS S ON S.id = newt.sub_id
							WHERE newt.emp_no =  '$emp_id' AND session='$session'
							 AND session_year='$session_year'";
            if (isset($data['s'])) {
                $q.=" and newt.course_id='" . $data['s'] . "'";
            }

            $query = $this->db->query($q);

            $result = $query->result();
            return $result;
        }
    }

    public function get_branch($data) {
        $emp_id = $data['emp_id'];
        $subject = $data['subject'];
        $this->load->database();
        $query = $this->db->query("SELECT DISTINCT branch_id
								FROM subject_mapping 
								INNER JOIN (SELECT map_id 
											FROM subject_mapping_des
											WHERE emp_no = '$emp_id' AND sub_id='$subject') AS t
								ON t.map_id = subject_mapping.map_id ;");
        $branch_id = array();
        $branch_id = $query->result();
        return $this->get_branch_name($branch_id);
    }

    public function get_branch_name($branch_id) {
        $branch_id_arr = array();
        for ($i = 0; $i < count($branch_id); $i++)
            $branch_id_arr[$i] = $branch_id[$i]->branch_id;
        $this->load->database();
        $this->db->select('id,name');
        $this->db->from('branches');
        $this->db->where_in('id', $branch_id_arr);
        $query = $this->db->get();
        return $query->result();
    }

    function get_minor_stu($data) {
        //print_r($data); die();
        $q = "select  hm_form.admn_no from hm_form join hm_minor_details on hm_form.form_id=hm_minor_details.form_id JOIN reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no where hm_form.minor_hod_status ='Y' and hm_minor_details.dept_id='" . $data['dept_id'] . "' and hm_minor_details.branch_id='" . $data['branch_id'] . "' and hm_minor_details.offered='1' and reg_regular_form.session_year='" . $data['sy'] . "' and reg_regular_form.`session`='" . $data['session'] . "' and reg_regular_form.semester='" . $data['semester'] . "'";

        $qu = $this->db->query($q);
        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0)
            return $qu->result();
        return false;
    }

    // Added as per rq. 4/11/15 (@rituraj), desc: To load  All record at once from where   download link to be attached  against  each  row. 
    public function get_sub_brh_crs($data = '') {
        //echo "crs_type".$data['s'];
        $emp_id = $this->session->userdata('id');
        $dept_id=$this->session->userdata('dept_id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];
            $q = "(SELECT S.subject_id as s_id, S.name as sub_name, newt.sub_id , newt.semester,newt.group, newt.branch_name,newt.course_name,newt.branch_id,newt.course_id,newt.aggr_id
							FROM (SELECT session,session_year,sub_id, B.branch_id,B.course_id,B.group, semester, emp_no, A.map_id,branches.name as branch_name,courses.name as course_name,B.aggr_id
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id
                                                                LEFT JOIN branches ON B.branch_id=branches.id
                                                                LEFT JOIN courses ON B.course_id=courses.id    
                                                           ) AS newt
							INNER JOIN subjects AS S ON S.id = newt.sub_id
							WHERE newt.emp_no =  '$emp_id' AND session='$session'
							 AND session_year='$session_year' AND newt.group='0')union (select x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name,
x.course_name,x.branch_id,x.course_id,x.aggr_id
 from (
select 
distinct e.subject_id as s_id,
e.name as sub_name,
e.id as sub_id,
d.semester, 
'0' as 'group',
g.name as branch_name,
c.name as course_name,
b.branch_id,b.course_id,
a.honours_agg_id as aggr_id,
c.duration,
b.session_year,b.`session`
from hm_form a 
inner join reg_regular_form b on a.admn_no=b.admn_no
inner join cs_courses c on c.id=b.course_id
inner join course_structure d on d.aggr_id=a.honours_agg_id
inner join subjects e on e.id=d.id
inner join stu_academic f on f.admn_no=b.admn_no
inner join cs_branches g on g.id=f.branch_id
where a.dept_id='".$dept_id."' and c.duration=5 and b.session_year='" . $session_year . "' and b.`session`='" . $session . "'
and d.semester=b.semester)x
inner join subject_mapping y on (y.session_year=x.session_year and y.`session`=x.session and y.aggr_id=x.aggr_id and y.semester=x.semester)
inner join subject_mapping_des z on z.map_id=y.map_id and z.sub_id=x.sub_id
and z.emp_no='" . $emp_id . "') ";
            if (isset($data['s'])) {
                $q.=" and newt.course_id='" . $data['s'] . "'";
            }

            //echo $q;
            $query = $this->db->query($q);
            //echo $this->db->last_query(); die();
            $result = $query->result();
            return $result;
        }
    }

    // end
    public function get_subjectsss($data) {
        $emp_id = $this->session->userdata('id');
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();


        $query = $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester, subjects.subject_id as sub_code, subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,subject_mapping.group,subject_mapping.section,subject_mapping.aggr_id,
			 cs_branches.name as branch_name,subject_mapping.course_id as course_id, 
			 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
			FROM subject_mapping 
			INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
			INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
			INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
			INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
			WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and subject_mapping.course_id <> 'jrf' and type!='Practical';");

        $result = $query->result();
        //print_r($result);
        return $result;
    }

}

?>