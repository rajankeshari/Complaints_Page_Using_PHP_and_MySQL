
<?php

class Defaulter_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	function insert_table_absent_defaulter($syear, $sess) {

       /* $sql = "INSERT INTO cbcs_absent_table_defaulter(session_year,`session`,admn_no,sub_offered_id,subject_code)
SELECT p.* FROM(
SELECT a.session_year,a.`session`,a.admn_no,CONCAT('c',a.sub_offered_id)AS sub_offered_id, a.subject_code FROM cbcs_stu_course a 
WHERE a.session_year=? AND a.`session`=?
UNION
SELECT a.session_year,a.`session`,a.admn_no,CONCAT('o',a.sub_offered_id)AS sub_offered_id, a.subject_code FROM old_stu_course a 
WHERE a.session_year=? AND a.`session`=?
)p
ORDER BY p.admn_no;";
        $query = $this->db->query($sql, array($syear, $sess,$syear, $sess));*/
		
	/*	$sql="INSERT INTO cbcs_absent_table_defaulter(session_year,`session`,admn_no,sub_offered_id)
SELECT p.* FROM(
SELECT '2019-2020' AS session_year,'Monsoon' AS SESSION,
a.admn_no,b.subject_offered_id
 from cbcs_absent_table a
INNER JOIN cbcs_class_engaged b ON b.id=a.class_engaged_id
GROUP BY a.admn_no,b.subject_offered_id
)p
ORDER BY p.admn_no;";*/

/*$sql="INSERT INTO cbcs_absent_table_defaulter(session_year,`session`,admn_no,sub_offered_id,sub_codegroup_no,section)
SELECT '2019-2020' as session_year,'Monsoon' as session,a.admn_no,b.subject_offered_id,b.group_no,b.section FROM cbcs_absent_table a 
INNER JOIN cbcs_class_engaged b ON b.id=a.class_engaged_id
#WHERE b.subject_offered_id='o146'
#WHERE b.section='A'
#WHERE a.admn_no='16je002484'
#WHERE b.group_no=3
GROUP BY a.admn_no,b.subject_offered_id
ORDER BY a.admn_no,b.subject_offered_id";*/

/*$sql="INSERT INTO cbcs_absent_table_defaulter(session_year,`session`,admn_no,sub_offered_id,sub_code,group_no,section)
SELECT q.session_year,q.`session`,q.admn_no,q.subject_offered_id,q.sub_code,q.group_no,q.section FROM(
SELECT p.* ,   o.sub_code AS old_sub_code,   c.sub_code AS cbcs_sub_code ,
(case when  p.rstatus='o' then o.sub_code  ELSE c.sub_code  end)AS sub_code  from
(
SELECT '2019-2020' as session_year,'Monsoon' as session,a.admn_no,b.subject_offered_id,b.group_no,b.section,
SUBSTRING(b.subject_offered_id,1,1)AS rstatus,
SUBSTRING(b.subject_offered_id,2)AS sub_id

FROM cbcs_absent_table a 
INNER JOIN cbcs_class_engaged b ON b.id=a.class_engaged_id
GROUP BY a.admn_no,b.subject_offered_id
ORDER BY a.admn_no,b.subject_offered_id)p
LEFT  JOIN 
old_subject_offered o ON CONCAT('o',o.id)=p.subject_offered_id
LEFT  JOIN 
cbcs_subject_offered c ON CONCAT('c',c.id)=p.subject_offered_id
)q order BY q.admn_no
";
*/
//$sql="select a.* from cbcs_absent_table_defaulter a where pclass is null";



 $query = $this->db->query($sql);

        if ($query) {
             return true;
        } else {
            return false;
        }
    }
	
	function get_table_data($syear, $sess){
		
		//$sql=" SELECT * FROM cbcs_absent_table_defaulter WHERE session_year=? AND SESSION=?  ";
		$sql="select a.* from cbcs_absent_table_defaulter a where pclass is null";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear, $sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
		
	}
//========================================================Total Class Starts============================================================
	function get_total_class($sub_offered_id,$group_no,$section) {
        //$sql = "SELECT COUNT(subject_offered_id)AS tot_classes FROM cbcs_class_engaged  WHERE  subject_offered_id='".$sub_offered_id."'";
		
		if ( (strlen($section) == 0) || ($section == '') || ($section == 'null') )
		{
			$sql = "SELECT COUNT(subject_offered_id)AS tot_classes FROM cbcs_class_engaged  WHERE  subject_offered_id='".$sub_offered_id."'  AND group_no='".$group_no."'";
		
		}else{
			 $sql = "SELECT COUNT(subject_offered_id)AS tot_classes FROM cbcs_class_engaged  WHERE  subject_offered_id='".$sub_offered_id."'  AND group_no='".$group_no."' AND section='".$section."'"; 
		}
		
		
        $query = $this->db->query($sql);
       
		if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
		
    }
	
	function update_total_class($sub_offered_id,$group_no,$section,$tot_classes) {
		if ( (strlen($section) == 0) || ($section == '') || ($section == 'null') )
		{
        $sql = "update cbcs_absent_table_defaulter set tot_classes=".$tot_classes." WHERE  sub_offered_id='".$sub_offered_id."'  AND group_no='".$group_no."' ";	
		
		}else{
			$sql = "update cbcs_absent_table_defaulter set tot_classes=".$tot_classes." WHERE  sub_offered_id='".$sub_offered_id."'  AND group_no='".$group_no."' AND section='".$section."'"; 
		}
			$query = $this->db->query($sql);

			if ($this->db->affected_rows() > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		
    }
	
//========================================================Total Class End============================================================
//========================================================Total Absent of Student Starts============================================================	
	
	function get_total_absnet_of_student($sub_offered_id,$admn_no) {
        $sql = "SELECT COUNT(a.admn_no)AS absent_classes FROM cbcs_absent_table a  INNER JOIN cbcs_class_engaged b ON b.id=a.class_engaged_id
				WHERE a.admn_no='".$admn_no."' AND b.subject_offered_id='".$sub_offered_id."'";
        $query = $this->db->query($sql);
       
		if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
		
    }
	
	
	
	function update_count_student_absent($sub_offered_id,$admn_no,$total_absent) {
		
        $sql = "update cbcs_absent_table_defaulter set absent_classes=".$total_absent." WHERE admn_no='".$admn_no."' AND sub_offered_id='".$sub_offered_id."'";
			$query = $this->db->query($sql);

			if ($this->db->affected_rows() > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
    }
	
//========================================================Total Absent of Student End============================================================		
	function get_institute_defaulter_percentage($sy, $sess) {
        $sql = "select a.* from defaulter_new_institute_tbl a where a.session_year=? and a.`session`=?";
        $query = $this->db->query($sql, array($sy, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	function update_institute_percentage_atd($per) {
        $sql = "update cbcs_absent_table_defaulter set ins_per=?";
        $query = $this->db->query($sql, array($per));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	/*function update_pclass(){
		
		$sql = "update cbcs_absent_table_defaulter set pclass=  (CAST(tot_classes AS UNSIGNED) - CAST(absent_classes AS UNSIGNED))";
        $query = $this->db->query($sql);
		 return TRUE;
	
	}*/
	
	function update_pclass($sub_offered_id,$admn_no){
		
		//$sql = "update cbcs_absent_table_defaulter set pclass=  (CAST(tot_classes AS UNSIGNED) - CAST(absent_classes AS UNSIGNED)) WHERE admn_no='".$admn_no."' AND sub_offered_id='".$sub_offered_id."' ";
		
		$sql="update cbcs_absent_table_defaulter set pclass=  
(case  when (CAST(tot_classes AS SIGNED) - CAST(absent_classes AS SIGNED))>=0 then (CAST(tot_classes AS SIGNED) - CAST(absent_classes AS SIGNED)) 
ELSE 
(CAST(tot_classes AS SIGNED))  END)
WHERE admn_no='".$admn_no."' AND sub_offered_id='".$sub_offered_id."'";
		
		
        $query = $this->db->query($sql);
		 return TRUE;
	
	}
	
	
	/*function update_ctbatt(){
		
		$sql = "update cbcs_absent_table_defaulter set ctbatt=(CAST(tot_classes AS UNSIGNED)*ins_per)/100";
        $query = $this->db->query($sql);
		return TRUE;
     
		
	}*/
	
	function update_ctbatt($sub_offered_id,$admn_no){
		
		$sql = "update cbcs_absent_table_defaulter set ctbatt=(CAST(tot_classes AS UNSIGNED)*ins_per)/100 WHERE admn_no='".$admn_no."' AND sub_offered_id='".$sub_offered_id."'";
        $query = $this->db->query($sql);
		return TRUE;
     
		
	}
	
	/*function update_def_status(){
		
		$sql = "update cbcs_absent_table_defaulter set def_status='y' WHERE pclass<ctbatt";
        $query = $this->db->query($sql);
		return TRUE;
		
	}*/
	
	function update_def_status($sub_offered_id,$admn_no){
		
		$sql = "update cbcs_absent_table_defaulter set def_status='y' WHERE pclass < ctbatt and admn_no='".$admn_no."' AND sub_offered_id='".$sub_offered_id."'";
		//echo $sql;
        $query = $this->db->query($sql);
		return TRUE;
		
	}
	
	function get_course_id($id,$syear,$sess){
		
		$sql = "SELECT course_id FROM cbcs_subject_offered WHERE id=? AND session_year=?  AND SESSION=?";
        $query = $this->db->query($sql, array($id,$sy, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
		
	}
	//=============================From old table============
	
	

    function insert($data) {
        if ($this->db->insert('defaulter_new_institute_tbl', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_student($data) {
        if ($this->db->insert('defaulter_new_individual_student', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_allpaper_student($data) {
        if ($this->db->insert('defaulter_new_allpaper_student', $data))
            return TRUE;
        else
            return FALSE;
    }

    function get_list() {


        $q = "select a.*,concat(concat_ws(' ',b.first_name,b.middle_name,b.last_name),' [',ucase(dept_id),']')as usernm from defaulter_new_institute_tbl a
inner join user_details b on b.id=a.user_id";

        $qu = $this->db->query($q);

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return $qu->result();
        } else {
            return false;
        }
    }

    function check_already($syear, $sess) {
        $q = "select * from defaulter_new_institute_tbl where session_year= ? and session= ?";

        $qu = $this->db->query($q, array($syear, $sess));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_already_ind_student($syear, $sess, $admn_no, $sem, $sub_id) {
        $q = "select * from defaulter_new_individual_student where session_year= ? and session= ? and admn_no=? and sem=? and subid=?";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no, $sem, $sub_id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function check_already_student_allpaper($syear, $sess, $admn_no, $sem) {
        $q = "select a.* from defaulter_new_allpaper_student a where a.session_year=? and a.`session`=?
              and a.admn_no=? and a.sem=?";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no, $sem));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function check_already_ind_student_other($syear, $sess, $admn_no, $sub_id) {
        $q = "select * from defaulter_new_individual_student where session_year= ? and session= ? and admn_no=?  and subid=?";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no, $sub_id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function check_already_allpaper_student($syear, $sess, $admn_no) {
        $q = "select * from defaulter_new_allpaper_student where session_year= ? and session= ? and admn_no=?";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_all_courses() {
        $q = "select * from cs_courses";

        $qu = $this->db->query($q);

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return $qu->result();
        } else {
            return false;
        }
    }

    function get_all_branches() {
        $q = "select * from cs_branches";

        $qu = $this->db->query($q);

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return $qu->result();
        } else {
            return false;
        }
    }

    function get_branch_bycourse($id) {
        $query = $this->db->query("select b.id,b.name from course_branch a
inner join cs_branches b on b.id=a.branch_id where course_id='" . $id . "'");
        //   echo  $this->db->last_query();    die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_semester_branch($bid, $cid) {
        $query = $this->db->query("
select b.duration*2 as sem from course_branch a
inner join cs_courses b on b.id=a.course_id
inner join cs_branches c on c.id=a.branch_id
where a.course_id='" . $cid . "' and a.branch_id='" . $bid . "'");
        //   echo  $this->db->last_query();    die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_all_hostel() {
        $query = $this->db->query("select distinct hostel_name from stu_hostel_info where hostel_name<>'N/A' and hostel_name<>'' order by hostel_name");
        //   echo  $this->db->last_query();    die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_student_details($tbl, $data) {
        $sql = "select a.* from " . $tbl . " a where a.session_year=? and a.`session`=? and a.admn_no=?
and a.hod_status='1' and a.acad_status='1'";

        $query = $this->db->query($sql, array($data['session_year'], $data['session'], $data['admn_no']));

        // echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    //===================================== Teaching Assistant End=========================================
    //========================================Regular core subjects======================================
    function get_regular_core_subject($aggr_id, $sem, $sec) {
        $x = explode('_', $aggr_id);

        if ($x[0] == "comm") {
            $tsem = $sem . '_' . $sec;
            $sql = "select a.*,b.subject_id,b.name from course_structure a
inner join subjects b on b.id=a.id where a.aggr_id='" . $aggr_id . "' and a.semester='" . $tsem . "' and a.sequence not like '%.%' order by b.name;";

            $query = $this->db->query($sql);
        } else {
            $sql = "select a.*,b.subject_id,b.name from course_structure a
inner join subjects b on b.id=a.id where a.aggr_id=? and a.semester=? and a.sequence not like '%.%'
order by b.name;";

            $query = $this->db->query($sql, array($aggr_id, $sem));
        }

        // echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_elective_details($formid) {

        $sql = "select c.*,b.subject_id,b.name from reg_regular_elective_opted a
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=b.id
where a.form_id=?
order by b.name";
        $query = $this->db->query($sql, array($formid));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_other_details($formid) {

        $sql = "select c.*,b.subject_id,b.name from reg_other_subject a
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=b.id
where a.form_id=?
order by b.name";
        $query = $this->db->query($sql, array($formid));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_exam_details($formid) {
        $sql = "select c.*,b.subject_id,b.name from reg_exam_rc_subject a
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=b.id
where a.form_id=?
order by b.name";
        $query = $this->db->query($sql, array($formid));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_summer_details($formid) {
        $sql = "select c.*,b.subject_id,b.name from reg_summer_subject a
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=b.id
where a.form_id=?
order by b.name";
        $query = $this->db->query($sql, array($formid));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_honour_details($admn_no) {

        $sql = "select a.* from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y'";
        $query = $this->db->query($sql, array($admn_no));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function get_student_minor_details($admn_no) {

        $sql = "select b.* from hm_form a
inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y' and b.offered='1'";
        $query = $this->db->query($sql, array($admn_no));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function get_minor_paper($aggr_id, $sem) {

        $sql = "SELECT a.*,b.subject_id,b.name FROM course_structure a
INNER JOIN subjects b ON b.id=a.id WHERE a.aggr_id= ? AND a.semester= ? AND a.sequence NOT LIKE '%.%'
ORDER BY b.name";
        $query = $this->db->query($sql, array($aggr_id, $sem));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_honours_paper($aggr_id, $sem) {
        $sql = "SELECT a.*,b.subject_id,b.name
FROM course_structure a
INNER JOIN subjects b ON b.id=a.id
WHERE a.aggr_id=? AND a.semester=? AND a.sequence NOT LIKE '%.%'
ORDER BY b.name";
        $query = $this->db->query($sql, array($aggr_id, $sem));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_personal_details($id, $sy, $sess) {
        $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as sname,
c.name as cname,d.name as bname,e.name as dname,f.semester,f.session_year,f.`session`,a.dept_id,b.course_id,b.branch_id
 from user_details a
inner join stu_academic b on b.admn_no=a.id
left join cs_courses c on c.id=b.course_id
left join cs_branches d on d.id=b.branch_id
inner join departments e on e.id=a.dept_id
inner join reg_regular_form f on f.admn_no=a.id
where a.id=? and f.session_year=? and f.`session`=?
and f.hod_status='1' and f.acad_status='1'";
        $query = $this->db->query($sql, array($id, $sy, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function get_student_personal_details_jrf($id, $sy, $sess) {
        $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as sname,
c.name as cname,d.name as bname,e.name as dname,f.semester,f.session_year,f.`session`,a.dept_id,b.course_id,b.branch_id
 from user_details a
inner join stu_academic b on b.admn_no=a.id
left join cs_courses c on c.id=b.course_id
left join cs_branches d on d.id=b.branch_id
inner join departments e on e.id=a.dept_id
inner join reg_exam_rc_form f on f.admn_no=a.id
where a.id=? and f.session_year=? and f.`session`=?
and f.hod_status='1' and f.acad_status='1'";
        $query = $this->db->query($sql, array($id, $sy, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    /*function get_institute_defaulter_percentage($sy, $sess) {
        $sql = "select a.* from defaulter_new_institute_tbl a where a.session_year=? and a.`session`=?";
        $query = $this->db->query($sql, array($sy, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }*/

    function get_hostel_student($hostel_name) {
        //        $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as sname from stu_hostel_info a
        //        inner join user_details b on b.id=a.admn_no
        // where a.hostel_name=? and a.`status`='SignIn' and DATE_FORMAT(a.entry_date, '%Y')=?";
        $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as sname from defaulter_new_hostel_all a
        inner join user_details b on b.id=a.admn_no
 where a.hostel_name=?";
        $query = $this->db->query($sql, array($hostel_name));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_student_coursewise($syear, $sess, $cid, $bid, $sem) {
        $sql = " select a.* from reg_regular_form a
 where a.session_year=? and a.`session`=?
 and a.course_id=?
 and a.hod_status='1' and a.acad_status='1'";
        if ($cid) {
            $query = $this->db->query($sql, array($syear, $sess, $cid));
        }
        if ($bid) {
            if (substr_count($bid, ',') > 0) {
                $bid = "'" . implode("','", explode(',', $bid)) . "'";
            }
            $sql.="and a.branch_id in (" . $bid . ") ";
            $query = $this->db->query($sql, array($syear, $sess, $cid, $bid));
        }
        if ($sem) {
            if (substr_count($sem, ',') > 0) {
                $sem = "'" . implode("','", explode(',', $sem)) . "'";
            }
            $sql.="and a.semester in (" . $sem . ") ";
            $query = $this->db->query($sql, array($syear, $sess, $cid, $sem));
        }


        echo $this->db->last_query();
        die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function get_sub_aggr_id($sid) {
        $sql = "select * from course_structure where id=?";
        $query = $this->db->query($sql, array($sid));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row()->aggr_id;
        } else {
            return FALSE;
        }
    }

    function get_map_id($syear, $sess, $aggrid, $sem) {
        $sql = "select a.* from subject_mapping a
where a.session_year=? and a.`session`=? and a.aggr_id=? and a.semester=?";
        $query = $this->db->query($sql, array($syear, $sess, $aggrid, $sem));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row()->map_id;
        } else {
            return FALSE;
        }
    }

    function get_section($syear, $admn_no) {

        $sql = "select * from stu_section_data where session_year=? and admn_no=?";
        $query = $this->db->query($sql, array($syear, $admn_no));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row()->section;
        } else {
            return FALSE;
        }
    }

    function get_map_id_comm($syear, $sess, $aggrid, $sem, $sec) {
        $sql = "select a.* from subject_mapping a
where a.session_year=? and a.`session`=? and a.aggr_id=? and a.semester=? and a.section=?";
        $query = $this->db->query($sql, array($syear, $sess, $aggrid, $sem, $sec));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row()->map_id;
        } else {
            return FALSE;
        }
    }

    //=============Course Wise ============================================
    //---------------------------------------------------------------------------------

    public function get_course($data) { # refactored
        $this->db->select('*');
        $query = $this->db->get_where('defaulter_new_coursewise', array('session_year' => $data['session_year'], 'session' => $data['session'], 'course' => $data['course'], 'branch' => $data['branch'], 'sem' => $data['sem']));
        $result = $query->result_array();

        $count = count($result);

        if (empty($count)) {
            return false;
        } else {
            return $result;
        }
    }

    //------------------------------------------------------------------------------------

    function insert_course($data) {
        if ($this->db->insert('defaulter_new_coursewise', $data))
            return TRUE;
        else
            return FALSE;
    }

    function check_already_hostel_all($syear, $sess, $hname, $cid, $bid, $sem) {
        $q = "select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=? and a.hostelname=? and a.course_id=? and a.branch_id=? and a.semester=?";

        $qu = $this->db->query($q, array($syear, $sess, $hname, $cid, $bid, $sem));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function insert_hostel($data) {
        if ($this->db->insert('defaulter_new_hostal_student', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_course_branch($data) {
        if ($this->db->insert_batch('defaulter_new_coursewise', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_course_branch_semester($data) {
        if ($this->db->insert_batch('defaulter_new_coursewise', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_course_branch_hostel($data) {
        if ($this->db->insert_batch('defaulter_new_hostal_student', $data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_course_branch_semester_hostel($data) {
        if ($this->db->insert_batch('defaulter_new_hostal_student', $data))
            return TRUE;
        else
            return FALSE;
    }

    function edit_institute_percentage($id) {
        $sql = "select * from defaulter_new_institute_tbl where id=?";
        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function move_to_temp_institute_per($id) {
        $sql = "insert into defaulter_new_institute_tbl_temp  select * from defaulter_new_institute_tbl where id=?";
        $query = $this->db->query($sql, array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_institute_percentage($id, $per, $uid) {
        $sql = "update defaulter_new_institute_tbl set percentage=" . $per . ",user_id=" . $uid . " where id=" . $id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_percentage_allpaper($syear, $sess, $admn_no) {
        $sql = "select a.* from defaulter_new_allpaper_student a
where a.session_year=? and a.`session`=? and a.admn_no=?";
        $query = $this->db->query($sql, array($syear, $sess, $admn_no));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function move_allpaper_student($id) {

        $sql = "insert into defaulter_new_allpaper_student_temp  select * from defaulter_new_allpaper_student where id=?";
        $query = $this->db->query($sql, array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_allpaper_student($per, $uid, $id) {
        $sql = "update defaulter_new_allpaper_student set per=" . $per . ",user_id=" . $uid . " where id=" . $id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //===============Individual student Modification===============================================

    function move_individual_student($id) {

        $sql = "insert into defaulter_new_individual_student_temp  select * from defaulter_new_individual_student where id=?";
        $query = $this->db->query($sql, array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_individual_student($per, $uid, $id) {
        $sql = "update defaulter_new_individual_student set per=" . $per . ",user_id=" . $uid . " where id=" . $id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_all_hostel_syear_sess_wise($syear, $sess) {
        $sql = "select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=?";
        $query = $this->db->query($sql, array($syear, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    //==================================Hostel Data========================
    function move_hosteldata($id) {

        $sql = "insert into defaulter_new_hostal_student_temp  select * from defaulter_new_hostal_student where id=?";
        $query = $this->db->query($sql, array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_hosteldata($per, $uid, $id) {
        $sql = "update defaulter_new_hostal_student set percentage=" . $per . ",user_id=" . $uid . " where id=" . $id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //=====================================================Course wise===============
    function get_all_course_syear_sess_wise($syear, $sess) {
        $sql = "select a.* from defaulter_new_coursewise a
where a.session_year=? and a.`session`=?";
        $query = $this->db->query($sql, array($syear, $sess));

        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function move_coursedata($id) {

        $sql = "insert into defaulter_new_coursewise_temp  select * from defaulter_new_coursewise where id=?";
        $query = $this->db->query($sql, array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_coursedata($per, $uid, $id) {
        $sql = "update defaulter_new_coursewise set per=" . $per . ",user_id=" . $uid . " where id=" . $id;
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_ind_student_syear_sess_wise($syear, $sess) {
        $q = "select * from defaulter_new_individual_student where session_year= ? and session= ?";

        $qu = $this->db->query($q, array($syear, $sess));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->result();
        } else {
            return FALSE;
        }
    }

    function get_all_paper_student_syear_sess_wise($syear, $sess) {
        $q = "select * from defaulter_new_allpaper_student where session_year= ? and session= ?";

        $qu = $this->db->query($q, array($syear, $sess));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->result();
        } else {
            return FALSE;
        }
    }

    function get_faculty_name($id) {
        $q = "select concat(concat_ws(' ',first_name,middle_name,last_name),' [',ucase(dept_id),']') as fname from user_details where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row()->fname;
        } else {
            return FALSE;
        }
    }

    function get_hostel_syear_sess_wise($syear, $sess) {
        $q = "select * from defaulter_new_hostal_student where session_year= ? and session= ?";

        $qu = $this->db->query($q, array($syear, $sess));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->result();
        } else {
            return FALSE;
        }
    }

    function get_subject_name($id) {

        $q = "select concat(name,' [',subject_id,']')as sname from subjects where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row()->sname;
        } else {
            return FALSE;
        }
    }

    function defaulter_new_individual_student_byID($id) {

        $q = "select * from defaulter_new_individual_student where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function defaulter_allpaper_student_byID($id) {
        $q = "select * from defaulter_new_allpaper_student where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function defaulter_Coursewise_byID($id) {
        $q = "select * from defaulter_new_coursewise where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function defaulter_Hostel_byID($id) {
        $q = "select * from defaulter_new_hostal_student where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function get_student_name($id) {
        $q = "select concat(concat_ws(' ',first_name,middle_name,last_name),' [',ucase(id),' ]') as sname from user_details where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row()->sname;
        } else {
            return FALSE;
        }
    }

    function get_stu_dcbs($admn_no) {
        $q = "SELECT a.dept_id,
    CASE
    WHEN b.auth_id='jrf' THEN 'jrf'
    WHEN b.auth_id='pd' THEN 'jrf'
    ELSE b.course_id
END as course_id,
CASE
    WHEN b.auth_id='jrf' THEN 'jrf'
    WHEN b.auth_id='pd' THEN 'jrf'
    ELSE b.branch_id
END as branch_id,

b.semester
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
WHERE a.id=?";

        $qu = $this->db->query($q, array($admn_no));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function get_stu_auth($admn_no) {
        $q = "select auth_id from stu_academic where admn_no=?";

        $qu = $this->db->query($q, array($admn_no));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row()->auth_id;
        } else {
            return FALSE;
        }
    }

    function check_registration_jrf($syear, $sess, $admn_no) {

        $q = "select a.* from reg_exam_rc_form a where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status='1' and a.acad_status='1'";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function check_registration_non_jrf($syear, $sess, $admn_no) {

        $q = "select a.* from reg_regular_form a where a.session_year=? and a.`session`=? and a.admn_no=? and a.hod_status='1' and a.acad_status='1'";

        $qu = $this->db->query($q, array($syear, $sess, $admn_no));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

    function get_aggr_sem($id) {

        $q = "select * from course_structure where id=?";

        $qu = $this->db->query($q, array($id));

        // echo $this->db->last_query();   die();
        if ($qu->num_rows() > 0) {
            //return TRUE;
            return $qu->row();
        } else {
            return FALSE;
        }
    }

//     function get_subjects($sy,$sess,$dept,$cid)
//     {
//         $q = "select a.*,b.dept_id,b.course_id,b.branch_id,b.semester,b.aggr_id,c.subject_id,c.name from total_class_table a
// inner join subject_mapping b on b.map_id=a.map_id
// inner join subjects c on c.id=a.sub_id
// inner join course_structure d on d.id=a.sub_id
// where b.session_year=? and b.`session`=? and b.dept_id=? and b.course_id=? and (d.aggr_id not like '%comm%' && d.aggr_id not like '%prep%')
// order by b.course_id,b.branch_id,b.semester,b.aggr_id,b.section";
//         $qu = $this->db->query($q,array($sy,$sess,$dept,$cid));
//         if ($qu->num_rows() > 0){
//             return $qu->result();
//         }else{
//             return FALSE;
//         }
//     }

    function get_subjects($sy, $sess, $dept, $cid) {
        $q = "select x.*,count(x.admn_no)as absent from(
SELECT a.*,b.dept_id,b.course_id,b.branch_id,b.semester,b.aggr_id,c.subject_id,c.name,e.admn_no,
concat_ws(' ',f.first_name,f.middle_name,f.last_name)as sname,b.session_year,b.`session`
FROM total_class_table a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
inner join absent_table e on e.map_id=a.map_id and e.session_id=a.session_id and e.sub_id=a.sub_id
inner join user_details f on f.id=e.admn_no
WHERE b.session_year=? AND b.`session`=? AND b.dept_id=? AND b.course_id=? AND (d.aggr_id NOT LIKE '%comm%' && d.aggr_id NOT LIKE '%prep%')
ORDER BY b.course_id,b.branch_id,b.semester,b.aggr_id,b.section,a.sub_id)x
group by x.sub_id,x.admn_no";

        $qu = $this->db->query($q, array($sy, $sess, $dept, $cid));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //COMMON

    function get_subjects_comm($sy, $sess, $dept, $cid) {
        $q = "select x.*,count(x.admn_no)as absent from(
SELECT a.*,b.dept_id,b.course_id,b.branch_id,b.semester,b.aggr_id,c.subject_id,c.name,e.admn_no,
concat_ws(' ',f.first_name,f.middle_name,f.last_name)as sname,b.session_year,b.`session`
FROM total_class_table a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
inner join absent_table e on e.map_id=a.map_id and e.session_id=a.session_id and e.sub_id=a.sub_id
inner join user_details f on f.id=e.admn_no
WHERE b.session_year=? AND b.`session`=? AND b.dept_id=? AND b.course_id=? AND (d.aggr_id LIKE '%comm%' && d.aggr_id NOT LIKE '%prep%')
ORDER BY b.course_id,b.branch_id,b.semester,b.aggr_id,b.section,a.sub_id)x
group by x.sub_id,x.admn_no";

        $qu = $this->db->query($q, array($sy, $sess, $dept, $cid));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //PREP

    function get_subjects_prep($sy, $sess, $cid) {
        $q = "select x.*,count(x.admn_no)as absent from(
SELECT a.*,b.dept_id,b.course_id,b.branch_id,b.semester,b.aggr_id,c.subject_id,c.name,e.admn_no,
concat_ws(' ',f.first_name,f.middle_name,f.last_name)as sname,b.session_year,b.`session`
FROM total_class_table a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
inner join absent_table e on e.map_id=a.map_id and e.session_id=a.session_id and e.sub_id=a.sub_id
inner join user_details f on f.id=e.admn_no
WHERE b.session_year=? AND b.`session`=?  AND b.course_id=? AND (d.aggr_id NOT LIKE '%comm%' && d.aggr_id  LIKE '%prep%')
ORDER BY b.course_id,b.branch_id,b.semester,b.aggr_id,b.section,a.sub_id)x
group by x.sub_id,x.admn_no";

        $qu = $this->db->query($q, array($sy, $sess, $cid));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //JRF

    function get_subjects_jrf($sy, $sess, $did) {
        $q = "SELECT x.*, COUNT(x.admn_no) AS absent
FROM(
SELECT a.*,b.dept_id,b.course_id,b.branch_id,b.semester,b.aggr_id,c.subject_id,c.name,e.admn_no, CONCAT_WS(' ',f.first_name,f.middle_name,f.last_name) AS sname,b.session_year,b.`session`
FROM total_class_table_jrf a
INNER JOIN subject_mapping b ON b.map_id=a.map_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
INNER JOIN absent_table e ON e.map_id=a.map_id AND e.session_id=a.session_id AND e.sub_id=a.sub_id
inner join reg_exam_rc_form ref  on ref.admn_no=e.admn_no and ref.session_year=b.session_year and ref.`session`=b.`session`
INNER JOIN user_details f ON f.id=e.admn_no
WHERE b.session_year=? AND b.`session`=? AND b.dept_id=?
ORDER BY b.course_id,b.branch_id,b.semester,b.aggr_id,b.section,a.sub_id
)x
GROUP BY x.sub_id,x.admn_no";

        $qu = $this->db->query($q, array($sy, $sess, $did));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    function get_courses_for_tab($sy, $sess, $dept) {
        $q = "select distinct b.course_id from total_class_table a
inner join subject_mapping b on b.map_id=a.map_id
inner join course_structure d on d.id=a.sub_id
where b.session_year=? and b.`session`=? and b.dept_id=? and (d.aggr_id not like '%comm%' && d.aggr_id not like '%prep%')
order by b.course_id,b.branch_id,b.semester,b.aggr_id,b.section";

        $qu = $this->db->query($q, array($sy, $sess, $dept));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //======================================COMMON==========================================
    function get_courses_for_tab_comm($sy, $sess, $dept) {
        $q = "select distinct b.course_id from total_class_table a
inner join subject_mapping b on b.map_id=a.map_id
inner join course_structure d on d.id=a.sub_id
where b.session_year=? and b.`session`=? and b.dept_id=? and (d.aggr_id  like '%comm%' && d.aggr_id not like '%prep%')
order by b.course_id,b.branch_id,b.semester,b.aggr_id,b.section";

        $qu = $this->db->query($q, array($sy, $sess, $dept));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //===============================================================================================
//======================================PREP==========================================
    function get_courses_for_tab_prep($sy, $sess) {
        $q = "select distinct b.course_id from total_class_table a
inner join subject_mapping b on b.map_id=a.map_id
inner join course_structure d on d.id=a.sub_id
where b.session_year=? and b.`session`=? and (d.aggr_id not like '%comm%' && d.aggr_id  like '%prep%')
order by b.course_id,b.branch_id,b.semester,b.aggr_id,b.section";

        $qu = $this->db->query($q, array($sy, $sess));

        if ($qu->num_rows() > 0) {

            return $qu->result();
        } else {
            return FALSE;
        }
    }

    //===============================================================================================


    function select_attendance_status_absent_table_ta($sub_id, $mapid, $admn_no) {
        $q = "select * from absent_table_ta  where sub_id=? and map_id=? and admn_no=?";

        $qu = $this->db->query($q, array($sub_id, $mapid, $admn_no));

        if ($qu->num_rows() > 0) {

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_attendance_status_absent_table($sub_id, $mapid, $admn_no) {
        $sql = "update absent_table set status='1' where sub_id='" . $sub_id . "' and map_id=" . $mapid . " and admn_no='" . $admn_no . "'";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_attendance_status_absent_table_ta($sub_id, $mapid, $admn_no) {
        $sql = "update absent_table_ta set status='1' where sub_id='" . $sub_id . "' and map_id=" . $mapid . " and admn_no='" . $admn_no . "'";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function create_table_absent_defaulter($syear, $sess) {

        $sql = "create table IF NOT EXISTS absent_table_defaulter
select a.* from absent_table a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
where b.session_year=? and b.`session`=?
and a.admn_no <>'' and c.type='Theory'
group by a.admn_no,a.sub_id,a.session_id";
        $query = $this->db->query($sql, array($syear, $sess));

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

  /*  function insert_table_absent_defaulter($syear, $sess) {

        $sql = "insert into absent_table_defaulter(admn_no,map_id,sub_id,session_id,date,timestamp,Remark,status,group_no,section,class_no)
select a.* from absent_table a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
where b.session_year=? and b.`session`=?
and a.admn_no <>'' and c.type='Theory'
group by a.admn_no,a.sub_id,a.session_id";
        $query = $this->db->query($sql, array($syear, $sess));

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }
*/
    function add_field_total_class() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN total_class INT DEFAULT 0";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    function add_field_percentage() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN percentage INT DEFAULT 0";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    function add_field_tot_absent() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN tot_absent INT DEFAULT 0";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    function add_field_pclass() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN pclass decimal(10,2) DEFAULT 0";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    function add_field_ctbatt() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN ctbatt decimal(10,2) DEFAULT 0";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    function add_field_def_status() {

        $sql = "ALTER TABLE absent_table_defaulter ADD COLUMN def_status varchar(1) DEFAULT 'n'";
        $query = $this->db->query($sql);

        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

  /*  function update_institute_percentage_atd($per) {
        $sql = "update absent_table_defaulter set percentage=?";
        $query = $this->db->query($sql, array($per));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }*/

    function update_hostel_percentage() {
        $sql = "update absent_table_defaulter T1
   join (
    select admn_no from defaulter_new_hostel_all a where a.hostel_name='Amber'
  ) T2 on T1.admn_no=T2.admn_no
set T1.percentage=85";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_indv_percentage($param = null) {

        if ($param == null) {
            $rep = " and (T1.admn_no    not like  '%dp%'  and  T1.admn_no    not like  '%DP%'  and  T1.admn_no    not like  '%dr%'  and T1.admn_no    not like  '%DR%' )    ";
        } else {

            $rep = " and (T1.admn_no     like  '%dp%'  or  T1.admn_no     like  '%DP%'  or T1.admn_no     like  '%dr%'  or T1.admn_no     like  '%DR%' )    ";
        }
        $sql = "update absent_table_defaulter T1
   join (
    select admn_no,per from defaulter_new_allpaper_student a
  ) T2 on T1.admn_no=T2.admn_no   $rep
set T1.percentage= T2.per ";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_total_class_atd($sess_yr, $sess, $param = null) {
        if ($param == null) {
            $rep = " and (absent_table_defaulter.admn_no   not like  '%dp%'  and  absent_table_defaulter.admn_no   not like  '%DP%'  and  absent_table_defaulter.admn_no    not like  '%dr%'  and absent_table_defaulter.admn_no   not like  '%DR%' )    ";
            $table = 'total_class_table';
        } else {

            $rep = " and (absent_table_defaulter.admn_no    like  '%dp%'  or  absent_table_defaulter.admn_no     like  '%DP%'  or absent_table_defaulter.admn_no    like  '%dr%'  or absent_table_defaulter.admn_no     like  '%DR%' )    ";
            $table = 'total_class_table_jrf';
        }

        $sql = "UPDATE absent_table_defaulter
       JOIN $table
       ON absent_table_defaulter.map_id=$table.map_id and absent_table_defaulter.sub_id=$table.sub_id and absent_table_defaulter.session_id=$table.session_id
       JOIN subject_mapping  sm on sm.map_id=absent_table_defaulter.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
	   $rep
         SET absent_table_defaulter.total_class = $table.total_class";
        $query = $this->db->query($sql);
        //  echo $this->db->last_query(); die();

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_total_class_absent($sess_yr, $sess, $param = null) {

        if ($param == null) {
            $rep = " and (T1.admn_no    not like  '%dp%'  and  T1.admn_no    not like  '%DP%'  and  T1.admn_no    not like  '%dr%'  and T1.admn_no    not like  '%DR%' )    ";
        } else {

            $rep = " and (T1.admn_no     like  '%dp%'  or  T1.admn_no     like  '%DP%'  or T1.admn_no     like  '%dr%'  or T1.admn_no     like  '%DR%' )    ";
        }

        $sql = "update absent_table_defaulter T1
  join (
    select admn_no,map_id,sub_id,session_id,count(date) as cab from absent_table a
group by a.admn_no,a.map_id,a.sub_id,a.session_id
  ) T2 on T1.admn_no=T2.admn_no and T1.map_id=T2.map_id and T1.sub_id=T2.sub_id and T1.session_id=T2.session_id
  JOIN subject_mapping  sm on sm.map_id=T1.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
  $rep
set T1.tot_absent=T2.cab";
        $query = $this->db->query($sql);



        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_present_class($sess_yr, $sess, $param = null) {
        if ($param == null) {
            $rep = " and (absent_table_defaulter.admn_no    not like  '%dp%'  and  absent_table_defaulter.admn_no    not like  '%DP%'  and  absent_table_defaulter.admn_no    not like  '%dr%'  and absent_table_defaulter.admn_no    not like  '%DR%' )    ";
        } else {
            $rep = " and (absent_table_defaulter.admn_no     like  '%dp%'  or  absent_table_defaulter.admn_no     like  '%DP%'  or absent_table_defaulter.admn_no     like  '%dr%'  or absent_table_defaulter.admn_no     like  '%DR%' )    ";
        }
        $sql = " update absent_table_defaulter
                JOIN subject_mapping  sm on sm.map_id=absent_table_defaulter.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
		   $rep   set pclass=total_class-tot_absent ";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_class_to_be_attend($sess_yr, $sess, $param = null) {
        if ($param == null) {
            $rep = " and (absent_table_defaulter.admn_no    not like  '%dp%'  and  absent_table_defaulter.admn_no    not like  '%DP%'  and  absent_table_defaulter.admn_no    not like  '%dr%'  and absent_table_defaulter.admn_no    not like  '%DR%' )    ";
        } else {

            $rep = " and (absent_table_defaulter.admn_no     like  '%dp%'  or  absent_table_defaulter.admn_no     like  '%DP%'  or absent_table_defaulter.admn_no     like  '%dr%'  or absent_table_defaulter.admn_no     like  '%DR%' )    ";
        }
        $sql = "update absent_table_defaulter  JOIN subject_mapping  sm on sm.map_id=absent_table_defaulter.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
		$rep   set ctbatt=ceil(total_class*percentage)/100";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

   /* function update_def_status($sess_yr, $sess, $param = null) {
        if ($param == null) {
            $rep = " and (absent_table_defaulter.admn_no    not like  '%dp%'  and  absent_table_defaulter.admn_no    not like  '%DP%'  and  absent_table_defaulter.admn_no    not like  '%dr%'  and absent_table_defaulter.admn_no    not like  '%DR%' )    ";
        } else {

            $rep = " and (absent_table_defaulter.admn_no     like  '%dp%'  or  absent_table_defaulter.admn_no     like  '%DP%'  or absent_table_defaulter.admn_no     like  '%dr%'  or absent_table_defaulter.admn_no     like  '%DR%' )    ";
        }
        $sql = "update absent_table_defaulter
		  JOIN subject_mapping  sm on sm.map_id=absent_table_defaulter.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
		   and  ctbatt>pclass  $rep  set def_status='y'";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }*/

    function update_status($sess_yr, $sess, $param = null) {
        if ($param == null) {
            $rep = " and (absent_table_defaulter.admn_no    not like  '%dp%'  and  absent_table_defaulter.admn_no    not like  '%DP%'  and  absent_table_defaulter.admn_no    not like  '%dr%'  and absent_table_defaulter.admn_no    not like  '%DR%' )    ";
        } else {

            $rep = " and (absent_table_defaulter.admn_no     like  '%dp%'  or  absent_table_defaulter.admn_no     like  '%DP%'  or absent_table_defaulter.admn_no     like  '%dr%'  or absent_table_defaulter.admn_no     like  '%DR%' )    ";
        }
        $sql = "update absent_table_defaulter
		JOIN subject_mapping  sm on sm.map_id=absent_table_defaulter.map_id and sm.session='$sess'  and sm.session_year='$sess_yr'
		 and def_status='y'   $rep  set status=1";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*function drop_created_table() {

        $sql = "drop table  absent_table_defaulter ";
        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }*/



    

}

?>