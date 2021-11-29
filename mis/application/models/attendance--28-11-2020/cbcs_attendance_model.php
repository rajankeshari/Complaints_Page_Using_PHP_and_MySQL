<?php

class Cbcs_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


    public function get_subjects($syear,$sess,$emp) 
    {
        
            $sql = "SELECT GROUP_CONCAT(CONCAT(t.rstatus,t.id))as temp_id,substr(t.sub_category,1,2)AS sub_cat,t.*,p.name AS cname,q.name AS bname
FROM ((
SELECT 'c' AS rstatus,a.*, NULL AS map_id,b.*
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=? AND a.sub_type Not IN ('Non-Contact')) UNION (
SELECT 'o' AS rstatus,a.*,b.*
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON b.sub_offered_id=a.id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=? AND a.sub_type Not IN ('Non-Contact')))t
INNER JOIN cbcs_courses p ON p.id=t.course_id
INNER JOIN cbcs_branches q ON q.id=t.branch_id
 GROUP BY t.course_id,t.branch_id,t.semester,t.sub_code,substr(t.sub_category,1,2),t.sub_code,t.section
ORDER BY t.course_id,t.branch_id,t.semester,t.sub_code,t.section";

        $query = $this->db->query($sql,array($syear,$sess,$emp,$syear,$sess,$emp));
        
        
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }else{
            return false;    
        }
        
    }

    function get_student_list($tsunb_id,$section,$syear,$sess,$course_id){
		
		$sub_off_id = "'" . implode("','", explode(',', $tsunb_id)) . "'";
	
	if($course_id=='comm'){
		$sql = "SELECT c.id ,c.first_name,c.middle_name,c.last_name FROM pre_stu_course a 
INNER JOIN reg_regular_form b  ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE a.sub_offered_id in (".$sub_off_id.") AND a.sub_category_cbcs_offered=?
AND a.session_year=? AND a.`session`=? and (a.remark2='1' or a.remark2='3') group by c.id order by c.id";

        $query = $this->db->query($sql,array($section,$syear,$sess));
		
		
	}
	else if($course_id=='online'){
		
		$x1=explode('c', $tsunb_id);
		$x1=$x1[1];
		
		$sql = "SELECT c.id ,c.first_name,c.middle_name,c.last_name FROM cbcs_stu_course a 
INNER JOIN reg_regular_form b  ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE a.sub_offered_id in (".$x1.") 
AND a.session_year=? AND a.`session`=?  group by c.id order by c.id" ;

        $query = $this->db->query($sql,array($syear,$sess));
		//echo $this->db->last_query();die();
		
	}
	
	else{
		$sql = "SELECT c.id ,c.first_name,c.middle_name,c.last_name FROM pre_stu_course a 
INNER JOIN reg_regular_form b  ON b.form_id=a.form_id AND b.admn_no=a.admn_no
INNER JOIN user_details c ON c.id=b.admn_no
WHERE a.sub_offered_id in (".$sub_off_id.") 
AND a.session_year=? AND a.`session`=?  and (a.remark2='1' or a.remark2='3') group by c.id order by c.id" ;

        $query = $this->db->query($sql,array($syear,$sess));
	}

                
        
        
        if ($query->num_rows() > 0)
        {
            return $query->result();
        }else{
            return false;    
        }

    }
	
	function count_student($tsunb_id,$section,$syear,$sess,$course_id){
		
		$sub_off_id = "'" . implode("','", explode(',', $tsunb_id)) . "'";
	
	if($course_id=='comm'){
		$sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM pre_stu_course a WHERE a.sub_offered_id IN (".$sub_off_id.")  AND a.session_year='".$syear."' AND a.`session`='".$sess."' and (a.remark2='1' or a.remark2='3') AND a.sub_category_cbcs_offered='".$section."' group by a.admn_no)t";
        $query = $this->db->query($sql);
		
		
	}
	else if($course_id=='online'){
		
		$x1=explode('c', $tsunb_id);
		$x1=$x1[1];
		$sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM cbcs_stu_course a WHERE a.sub_offered_id ='".$x1."'  AND a.session_year='".$syear."' AND a.`session`='".$sess."' group by a.admn_no)t";
        $query = $this->db->query($sql);
		
		//echo $this->db->last_query();die();
	}
	
	else{
		$sql = "select COUNT(t.admn_no)AS cnt from(SELECT a.* FROM pre_stu_course a WHERE a.sub_offered_id IN (".$sub_off_id.")  AND a.session_year='".$syear."' AND a.`session`='".$sess."' and (a.remark2='1' or a.remark2='3') group by a.admn_no)t";
        $query = $this->db->query($sql);
		
	}

        //echo $this->db->last_query();    
				

        
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }else{
            return false;    
        }
		
		
	}





















    //===========================================================================

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
 //@anuj for mark drop student added mark drop query her
    function get_minor_stu($data) {
        //print_r($data); die();
        $q = "select  hm_form.admn_no from hm_form join hm_minor_details on hm_form.form_id=hm_minor_details.form_id JOIN reg_regular_form on reg_regular_form.admn_no=hm_form.admn_no 
		where hm_form.minor_hod_status ='Y' and hm_minor_details.dept_id='" . $data['dept_id'] . "' and hm_minor_details.branch_id='" . $data['branch_id'] . "' and hm_minor_details.offered='1' 
		and reg_regular_form.session_year='" . $data['sy'] . "' and reg_regular_form.`session`='" . $data['session'] . "' and 
		reg_regular_form.semester='" . $data['semester'] . "' and reg_regular_form.admn_no not in (
select a.admn_no from stu_exam_absent_mark a 
where a.course_aggr_id='".trim($data['aggr_id'])."' and a.session_year='".$data['sy']."'
and a.`session`='".$data['session']."' and a.semester=".$data['semester']." and a.sub_id='".$data['sub_id']."' and a.`status`='B')";

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
            $q = "(select p.* from ((SELECT S.subject_id as s_id, S.name as sub_name, newt.sub_id , newt.semester,newt.group, newt.branch_name,newt.course_name,newt.branch_id,newt.course_id,newt.aggr_id
							FROM (SELECT session,session_year,sub_id, B.branch_id,B.course_id,B.group, semester, emp_no, A.map_id,branches.name as branch_name,courses.name as course_name,B.aggr_id
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id
                                                                LEFT JOIN branches ON B.branch_id=branches.id
                                                                LEFT JOIN courses ON B.course_id=courses.id    
                                                           ) AS newt
							INNER JOIN subjects AS S ON S.id = newt.sub_id
							WHERE newt.emp_no =  '$emp_id' AND session='$session'
							 AND session_year='$session_year' AND newt.group='0' and newt.course_id<>'jrf')union (select x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name,
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
and z.emp_no='" . $emp_id . "'))p
group by p.sub_id)union (
SELECT newt.sub_id AS s_id, newt.sub_name AS sub_name, newt.sub_id, newt.semester,newt.group, newt.branch_name,newt.course_name,newt.branch_id,newt.course_id,newt.aggr_id
FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`, 
B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
B.sub_name
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
WHERE newt.emp_no = '$emp_id' AND SESSION='$session' AND session_year='$session_year' AND newt.group='0' AND newt.course_id<>'jrf') ";
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
    
    public function get_sub_brh_crs_jrf($data = '') {
        //echo "crs_type".$data['s'];
        $emp_id = $this->session->userdata('id');
        $dept_id=$this->session->userdata('dept_id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];
            $q = "(SELECT S.subject_id AS s_id, S.name AS sub_name, newt.sub_id, newt.semester,newt.group, 'PHD' as branch_name, 'PHD' as course_name,newt.branch_id,newt.course_id,newt.aggr_id
FROM (
SELECT SESSION,session_year,sub_id, B.branch_id,B.course_id,B.group, semester, emp_no, A.map_id,branches.name AS branch_name,courses.name AS course_name,B.aggr_id
FROM subject_mapping_des AS A
INNER JOIN subject_mapping AS B ON A.map_id = B.map_id
LEFT JOIN branches ON B.branch_id=branches.id
LEFT JOIN courses ON B.course_id=courses.id) AS newt
INNER JOIN subjects AS S ON S.id = newt.sub_id
WHERE newt.emp_no = '".$emp_id."' AND SESSION='".$data['session']."' AND session_year='".$data['session_year']."' AND newt.group='0'
and newt.course_id='jrf')union
(
select z.subject_id as s_id,z.name as sub_name,x.sub_id,y.semester,y.`group`,
'PHD' AS branch_name, 'PHD' AS course_name,y.branch_id,y.course_id,y.aggr_id
 from subject_mapping_des x 
inner join subject_mapping y on y.map_id=x.map_id
inner join subjects z on z.id=x.sub_id
where x.sub_id in (
select a.sub_id from reg_exam_rc_subject a
inner join reg_exam_rc_form b on b.form_id=a.form_id
where b.session_year='".$data['session_year']."' and b.`session`='".$data['session']."'
and b.hod_status='0' and b.acad_status='0' group by a.sub_id )
and x.emp_no='".$emp_id."' and y.session_year='".$data['session_year']."' and y.`session`='".$data['session']."') ";
            
            $query = $this->db->query($q);
            //echo $this->db->last_query(); die();
            $result = $query->result();
            return $result;
        }
    }


//===================================== Teaching Assistant Start=========================================
    function get_ta_subjects($syear,$sess,$empid,$taid){
        $q = "select a.* from faculty_ta_mapping_tbl a
inner join subject_mapping b on b.map_id=a.map_id
where b.session_year=? and b.`session`=? and a.emp_no=? and a.admn_no=?";

        $qu = $this->db->query($q,array($syear,$sess,$empid,$taid));
        
        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->result();
        }else{
            return false;    
        }
    }


    public function get_sub_brh_crs_ta_with_ft($data = '') {
        //echo "crs_type".$data['s'];
         if (in_array("ta", $this->session->userdata('auth'))) {
            $emp_id = $data['emp_id'];    
            $ta_id=$this->session->userdata('id');

        }
        $dept_id=$this->session->userdata('dept_id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];
            $q = "SELECT p.*
FROM ((
SELECT S.subject_id AS s_id, S.name AS sub_name, newt.sub_id, newt.semester,newt.group, newt.branch_name,newt.course_name,newt.branch_id,newt.course_id,newt.aggr_id,newt.section,ftm.emp_no
FROM (
SELECT SESSION,session_year,sub_id, B.branch_id,B.course_id,B.group, semester, emp_no, A.map_id,B.section,CASE WHEN B.branch_id='comm' THEN 'Common' WHEN B.branch_id='jrf' THEN 'JRF' WHEN B.branch_id='minor' THEN 'Minor' ELSE branches.name END AS branch_name, CASE WHEN B.course_id='comm' THEN 'Common' WHEN B.course_id='jrf' THEN 'JRF' WHEN B.course_id='minor' THEN 'Minor' ELSE courses.name END AS course_name, B.aggr_id
FROM subject_mapping_des AS A
INNER JOIN subject_mapping AS B ON A.map_id = B.map_id
LEFT JOIN branches ON B.branch_id=branches.id
LEFT JOIN courses ON B.course_id=courses.id) AS newt
INNER JOIN subjects AS S ON S.id = newt.sub_id
INNER JOIN faculty_ta_mapping_tbl ftm ON ftm.map_id=newt.map_id AND ftm.emp_no=newt.emp_no AND ftm.admn_no='".$ta_id."' AND ftm.sub_id=newt.sub_id
WHERE newt.emp_no = '".$emp_id."' AND SESSION='".$session."' AND session_year='".$session_year."' AND newt.course_id<>'jrf') UNION (
SELECT x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.section,ftm.emp_no
FROM (
SELECT DISTINCT e.subject_id AS s_id, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group', g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id, c.duration, b.session_year,b.`session`
FROM hm_form a
INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
INNER JOIN cs_courses c ON c.id=b.course_id
INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN stu_academic f ON f.admn_no=b.admn_no
INNER JOIN cs_branches g ON g.id=f.branch_id
WHERE a.dept_id='".$dept_id."' AND c.duration=5 AND b.session_year='".$session_year."' AND b.`session`='".$session."' AND d.semester=b.semester)x
INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id AND z.emp_no='".$emp_id."'
INNER JOIN faculty_ta_mapping_tbl ftm ON ftm.map_id=y.map_id AND ftm.emp_no=z.emp_no AND ftm.admn_no='".$ta_id."'))p
GROUP BY p.sub_id ";
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

    function get_sub_brh_crs_ta_without_ft($data = ''){
        if (in_array("ta", $this->session->userdata('auth'))) {
            $emp_id = $data['emp_id'];    
            $ta_id=$this->session->userdata('id');

        }
        $dept_id=$this->session->userdata('dept_id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];
            $q = "SELECT p.*
FROM ((
SELECT S.subject_id AS s_id, S.name AS sub_name, newt.sub_id, newt.semester,newt.group, newt.branch_name,newt.course_name,newt.branch_id,newt.course_id,newt.aggr_id,newt.section,ftm.emp_no
FROM (
SELECT SESSION,session_year,sub_id, B.branch_id,B.course_id,B.group, semester, emp_no, A.map_id,B.section,CASE WHEN B.branch_id='comm' THEN 'Common' WHEN B.branch_id='jrf' THEN 'JRF' WHEN B.branch_id='minor' THEN 'Minor' ELSE branches.name END AS branch_name, CASE WHEN B.course_id='comm' THEN 'Common' WHEN B.course_id='jrf' THEN 'JRF' WHEN B.course_id='minor' THEN 'Minor' ELSE courses.name END AS course_name, B.aggr_id
FROM subject_mapping_des AS A
INNER JOIN subject_mapping AS B ON A.map_id = B.map_id
LEFT JOIN branches ON B.branch_id=branches.id
LEFT JOIN courses ON B.course_id=courses.id) AS newt
INNER JOIN subjects AS S ON S.id = newt.sub_id
INNER JOIN faculty_ta_mapping_tbl ftm ON ftm.map_id=newt.map_id AND ftm.emp_no=newt.emp_no AND ftm.admn_no='".$ta_id."' AND ftm.sub_id=newt.sub_id
WHERE SESSION='".$session."' AND session_year='".$session_year."' AND newt.course_id<>'jrf') UNION (
SELECT x.s_id,x.sub_name,x.sub_id,x.semester,x.group,x.branch_name, x.course_name,x.branch_id,x.course_id,x.aggr_id,y.section,ftm.emp_no
FROM (
SELECT DISTINCT e.subject_id AS s_id, e.name AS sub_name, e.id AS sub_id, d.semester, '0' AS 'group', g.name AS branch_name, c.name AS course_name, b.branch_id,b.course_id, a.honours_agg_id AS aggr_id, c.duration, b.session_year,b.`session`
FROM hm_form a
INNER JOIN reg_regular_form b ON a.admn_no=b.admn_no
INNER JOIN cs_courses c ON c.id=b.course_id
INNER JOIN course_structure d ON d.aggr_id=a.honours_agg_id
INNER JOIN subjects e ON e.id=d.id
INNER JOIN stu_academic f ON f.admn_no=b.admn_no
INNER JOIN cs_branches g ON g.id=f.branch_id
WHERE a.dept_id='".$dept_id."' AND c.duration=5 AND b.session_year='".$session_year."' AND b.`session`='".$session."' AND d.semester=b.semester)x
INNER JOIN subject_mapping y ON (y.session_year=x.session_year AND y.`session`=x.session AND y.aggr_id=x.aggr_id AND y.semester=x.semester)
INNER JOIN subject_mapping_des z ON z.map_id=y.map_id AND z.sub_id=x.sub_id 
INNER JOIN faculty_ta_mapping_tbl ftm ON ftm.map_id=y.map_id AND ftm.emp_no=z.emp_no AND ftm.admn_no='".$ta_id."'))p
GROUP BY p.sub_id ";
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

    function get_faculty_ta($data){
        $q = "select a.*,c.subject_id,c.name,d.semester,d.aggr_id,b.dept_id,b.course_id,b.branch_id,b.section,e.name as dname,
f.name as cname,g.name as bname,SUBSTRING_INDEX(d.aggr_id, '_', -2)as cs,
concat_ws(' ',h.first_name,h.middle_name,h.last_name)as stuname
 from faculty_ta_mapping_tbl a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
inner join departments e on e.id=b.dept_id
inner join cs_courses f on f.id=b.course_id
inner join cs_branches g on g.id=b.branch_id
inner join user_details h on h.id=a.admn_no
where b.session_year=? and b.`session`=? and a.emp_no=?";

        $qu = $this->db->query($q,array($data['syear'],$data['sess'],$data['emp_id']));
        
         //echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->result();
        }else{
            return false;    
        }
    }

    function update_status_ta($id,$status){
        $query = $this->db->query("update faculty_ta_mapping_tbl set status='".$status."' where id=".$id);
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }

    }

    function get_ta_status($id){
        $q = "select status from faculty_ta_mapping_tbl where id=?";

        $qu = $this->db->query($q,array($id));
        
        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->row();
        }else{
            return false;    
        }

    }


    //===================================== Teaching Assistant End=========================================
	
	function get_subjects_cbcs($data){
		
		//echo '<pre>';print_r($data);echo '</pre>';die();
		
        $q = "SELECT v.*,cgpa.*,cce.group_no AS ee_group,cce.subject_offered_id
FROM (
SELECT A.*,B.*, NULL AS map_id,D.name AS cname,C.name AS bname,'cbcs' AS rstatus
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1' AND A.emp_no = '".$data['emp_id']."' /*AND A.coordinator='1' */ AND B.`session`='".$data['session']."' AND B.session_year='".$data['session_year']."' AND B.course_id='jrf' AND B.sub_type<>'Practical' AND B.contact_hours<>0)v
LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('c', CAST(v.sub_offered_id AS CHAR))
LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no UNION
SELECT u.*,cgpa.*,cce.group_no AS ee_group,cce.subject_offered_id
FROM(
SELECT A.*,B.*,D.name AS cname,C.name AS bname,'old' AS rstatus
FROM old_subject_offered_desc AS A
INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1' AND A.emp_no = '".$data['emp_id']."' /*AND A.coordinator='1' */ AND B.`session`='".$data['session']."' AND B.session_year='".$data['session_year']."' AND B.course_id='jrf' AND B.sub_type<>'Practical' AND B.contact_hours<>0)u
LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('o', CAST(u.sub_offered_id AS CHAR))
LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no";

        $qu = $this->db->query($q,array($syear,$sess,$empid,$taid));
        
        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0){
            return $qu->result();
        }else{
            return false;    
        }
    }
	
	//==============================================30-08-2019=========================================
	
	function get_subjects_as_upload($data) {
      

      
 
                $sql = "SELECT cgpa.*,v.*,cce.group_no as ee_group ,cce.subject_offered_id from
(SELECT A.*,B.*,NULL AS map_id,D.name AS cname,C.name AS bname,'cbcs' as rstatus
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." /*AND B.sub_type<>'Practical'*/ AND B.contact_hours<>0 )v
 LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('c', CAST(v.sub_offered_id AS CHAR))
 AND    ( case  when  v.course_id='comm' then   cgpa.section=v.section else 1=1 end)   
 LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no 
 AND    ( case  when  v.course_id='comm' then   cce.section=v.section else 1=1 end)
 union 
 SELECT cgpa.*,u.*,cce.group_no as ee_group ,cce.subject_offered_id FROM(
 SELECT A.*,B.*,D.name AS cname,C.name AS bname,'old' as rstatus
FROM old_subject_offered_desc AS A
INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = ? /*AND A.coordinator='1' */
AND B.`session`=? AND B.session_year=? 
 AND " .($data['jrfstatus']=='1'? " B.course_id='jrf' " : " B.course_id<>'jrf' ")." /*AND B.sub_type<>'Practical'*/ AND B.contact_hours<>0)u
LEFT JOIN cbcs_prac_group_attendance cgpa ON cgpa.subject_id= CONCAT('o', CAST(u.sub_offered_id AS CHAR))
    AND    ( case  when  u.course_id='comm' then   cgpa.section=u.section else 1=1 end)   

LEFT JOIN cbcs_class_engaged cce ON cce.subject_offered_id=cgpa.subject_id AND cce.group_no=cgpa.group_no  
AND    ( case  when  u.course_id='comm' then   cce.section=u.section else 1=1 end)   

 ";
        $query = $this->db->query($sql, array($data['emp_id'],$data['session'],$data['session_year'],$data['emp_id'],$data['session'],$data['session_year']));
//echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	


}

?>