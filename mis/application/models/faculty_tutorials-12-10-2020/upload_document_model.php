<?php

class Upload_document_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_session_subjects($syear,$sess,$user_id)
    {
          
        $sql = "(select a.*,c.subject_id,c.name,b.dept_id,b.course_id,b.branch_id,b.semester,b.section,d.name as dname,e.name as cname, f.name as bname,b.session_year,b.`session`,b.aggr_id  from subject_mapping_des a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
inner join departments d on d.id=b.dept_id
left join cs_courses e on e.id=b.course_id
left join cs_branches f on f.id=b.branch_id
where b.session_year=? and b.`session`=?
and a.emp_no=?)union
(SELECT b.id AS map_id,
a.emp_no,a.coordinator,a.sub_id,'1' AS M,NULL AS id,
b.sub_code AS subject_id,b.sub_name AS NAME,f.dept_id AS dept_id,
b.course_id,b.branch_id,b.semester,a.section,d.name AS dname,d.name as cname,e.name as bname, b.session_year,b.session,'cbcs'  AS aggr_id
FROM cbcs_subject_offered_desc a
INNER JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
INNER JOIN course_branch c ON c.course_id=b.course_id AND c.branch_id=b.branch_id
INNER JOIN cbcs_courses d ON d.id= b.course_id
INNER JOIN cbcs_branches e ON e.id=b.branch_id
INNER JOIN cbcs_course_master f ON f.sub_code=b.sub_code
WHERE b.session_year=? AND b.`session`=? AND a.emp_no=?
AND d.`status`='1' AND e.`status`='1'/* group by b.id*/)  ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear,$sess,$user_id,$syear,$sess,$user_id));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	//================================ Added on 04-11-2019=========
	function get_session_subjects_cbcs($syear,$sess,$user_id)
    {
          
        $sql = "(SELECT b.id AS map_id,
a.emp_no,a.coordinator,a.sub_id,'1' AS M,NULL AS id,
b.sub_code AS subject_id,b.sub_name AS name,b.dept_id AS dept_id,
b.course_id,b.branch_id,b.semester,a.section,d.name AS dname,d.name as cname,e.name as bname, b.session_year,b.session,'old'  AS aggr_id
FROM old_subject_offered_desc a
INNER JOIN old_subject_offered b ON b.id=a.sub_offered_id
/*INNER JOIN course_branch c ON c.course_id=b.course_id AND c.branch_id=b.branch_id*/
INNER JOIN cbcs_courses d ON d.id= b.course_id
INNER JOIN cbcs_branches e ON e.id=b.branch_id
WHERE b.session_year=? AND b.`session`=? AND a.emp_no=?
AND d.`status`='1' AND e.`status`='1')union
(SELECT b.id AS map_id,
a.emp_no,a.coordinator,a.sub_id,'1' AS M,NULL AS id,
b.sub_code AS subject_id,b.sub_name AS name,f.dept_id AS dept_id,
b.course_id,b.branch_id,b.semester,a.section,d.name AS dname,d.name as cname,e.name as bname, b.session_year,b.session,'cbcs'  AS aggr_id
FROM cbcs_subject_offered_desc a
INNER JOIN cbcs_subject_offered b ON b.id=a.sub_offered_id
/*INNER JOIN course_branch c ON c.course_id=b.course_id AND c.branch_id=b.branch_id*/
INNER JOIN cbcs_courses d ON d.id= b.course_id
INNER JOIN cbcs_branches e ON e.id=b.branch_id
INNER JOIN cbcs_course_master f ON f.sub_code=b.sub_code
WHERE b.session_year=? AND b.`session`=? AND a.emp_no=?
AND d.`status`='1' AND e.`status`='1')  ";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear,$sess,$user_id,$syear,$sess,$user_id));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	//==============================

    function get_topic_list(){
         $sql = "select * from faculty_tutorial_list order by id";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    function insert($data)
    {
        if($this->db->insert('faculty_tutorial_main',$data))
            return $this->db->insert_id();
        else
            return FALSE;
    }
    function get_uploaded_list($syear,$sess,$user_id){
        $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname from faculty_tutorial_main a 
inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id where a.session_year=? and a.`session`=? and a.emp_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear,$sess,$user_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    function get_pdf_path($id){

        $sql = "select * from faculty_tutorial_main where id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }
    function delete_file($id)
    {
         
        $sql = "delete  from faculty_tutorial_main where id = ?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return True;
        } else {
            return false;
        }
    }

    function get_student_list($syear,$sess,$dept,$course,$branch,$aggrid,$sem,$sec,$subid){

        if($dept=='comm'){
            $sql = "select a.*,b.section as section1 from reg_regular_form a 
inner join stu_section_data b on b.session_year=a.session_year and b.admn_no=a.admn_no 
where a.session_year=? and a.`session`=?  and a.course_aggr_id=? and b.section=?
 and  a.hod_status='1' and a.acad_status='1' AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? 
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($syear,$sess,$aggrid,$sec,$aggrid,$syear,$sess,$sem,$subid));


        }else{

            $sql = "select a.* from reg_regular_form a 
inner join user_details b on b.id=a.admn_no
where a.session_year=? and a.`session`=? and b.dept_id=? and a.course_id=? and a.branch_id=?
and a.semester=?  and a.course_aggr_id=?  and  a.hod_status='1' and a.acad_status='1' AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? 
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($syear,$sess,$dept,$course,$branch,$sem,$aggrid,$aggrid,$syear,$sess,$sem,$subid));

//echo $this->db->last_query();   die();
        }

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    //core elective starts===================

    function get_student_list_elective($syear,$sess,$dept,$course,$branch,$aggrid,$sem,$sec,$subid){

        if($dept=='comm'){
            $sql = "select a.*,b.section as section1 from reg_regular_form a 
inner join stu_section_data b on b.session_year=a.session_year and b.admn_no=a.admn_no 
inner join reg_regular_elective_opted c on c.form_id=a.form_id
where a.session_year=? and a.`session`=?  and a.course_aggr_id=? and b.section=?
 and  a.hod_status='1' and a.acad_status='1' and c.sub_id=? AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? 
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($syear,$sess,$aggrid,$sec,$subid,$aggrid,$syear,$sess,$sem,$subid));


        }else{

            $sql = "select a.* from reg_regular_form a 
inner join user_details b on b.id=a.admn_no
inner join reg_regular_elective_opted c on c.form_id=a.form_id
where a.session_year=? and a.`session`=? and b.dept_id=? and a.course_id=? and a.branch_id=?
and a.semester=?  and a.course_aggr_id=?  and  a.hod_status='1' and a.acad_status='1' and c.sub_id=? AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? 
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($syear,$sess,$dept,$course,$branch,$sem,$aggrid,$subid,$aggrid,$syear,$sess,$sem,$subid));


        }

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }



    //core elective ends======================
    //Honours starts=================
    function get_student_list_honours($aggrid,$syear,$sess,$sem,$subid){

        $sql = "SELECT hm_form.`admn_no`
FROM (`hm_form`)
JOIN reg_regular_form a ON a.admn_no=hm_form.admn_no
INNER JOIN stu_academic c ON a.admn_no=c.admn_no
INNER JOIN cs_courses d ON d.id=c.course_id
WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' 
AND a.session_year=? AND a.`session`=? AND a.semester=? AND (d.duration=4 || d.duration=5) AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? 
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($aggrid,$syear,$sess,$sem,$aggrid,$syear,$sess,$sem,$subid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
           
        }

        //Elective
        function get_student_list_honours_elective($aggrid,$syear,$sess,$sem,$subid){

        $sql = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
inner join stu_academic c on a.admn_no=c.admn_no
inner join cs_courses d on d.id=c.course_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' 
 and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? 
 
AND (d.duration=4 || d.duration=5) AND a.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=?
AND a.`session`=? AND a.semester=? AND a.sub_id=? AND a.`status`='B'))";
  $query = $this->db->query($sql,array($aggrid,$syear,$sess,$sem,$subid,$aggrid,$syear,$sess,$sem,$subid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
           
        }


    //Honours Ends====================
        //Minor Starts =========================================

            function get_student_list_minor($deptid,$branchid,$syear,$sess,$sem,$aggrid,$subid){

        $sql = "SELECT hm_form.admn_no
FROM hm_form
JOIN hm_minor_details ON hm_form.form_id=hm_minor_details.form_id
JOIN reg_regular_form ON reg_regular_form.admn_no=hm_form.admn_no
WHERE hm_form.minor_hod_status ='Y' AND hm_minor_details.dept_id=? AND hm_minor_details.branch_id=? 
AND hm_minor_details.offered='1' AND reg_regular_form.session_year=? AND reg_regular_form.`session`=?
AND reg_regular_form.semester=? AND reg_regular_form.hod_status<>'2' 
AND reg_regular_form.acad_status<>'2' AND hm_form.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? AND a.`session`=?
AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($deptid,$branchid,$syear,$sess,$sem,$aggrid,$syear,$sess,$sem,$subid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
           
        }

        //Elective
        function get_student_list_minor_elective($deptid,$branchid,$syear,$sess,$sem,$aggrid,$subid){

        $sql = "SELECT hm_form.admn_no
FROM hm_form
JOIN hm_minor_details ON hm_form.form_id=hm_minor_details.form_id
JOIN reg_regular_form ON reg_regular_form.admn_no=hm_form.admn_no
Join reg_regular_elective_opted on reg_regular_elective_opted.form_id=reg_regular_form.form_id
WHERE hm_form.minor_hod_status ='Y' AND hm_minor_details.dept_id=? AND hm_minor_details.branch_id=? 
AND hm_minor_details.offered='1' AND reg_regular_form.session_year=? AND reg_regular_form.`session`=?
AND reg_regular_form.semester=? AND reg_regular_form.hod_status<>'2' 
AND reg_regular_form.acad_status<>'2' and reg_regular_elective_opted.sub_id=? AND hm_form.admn_no NOT IN (
SELECT a.admn_no
FROM stu_exam_absent_mark a
WHERE a.course_aggr_id=? AND a.session_year=? AND a.`session`=?
AND a.semester=? AND a.sub_id=? AND a.`status`='B')";
  $query = $this->db->query($sql,array($deptid,$branchid,$syear,$sess,$sem,$subid,$aggrid,$syear,$sess,$sem,$subid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
           
        }



        //Minor Ends================================================
    function get_student($syear,$sess,$user_id){
         $sql = "select a.*,b.section as section1,c.dept_id from reg_regular_form a 
left join stu_section_data b on b.session_year=a.session_year and b.admn_no=a.admn_no 
inner join user_details c on c.id=a.admn_no where a.session_year=? and a.`session`=?
and a.admn_no=?  and  a.hod_status='1' and a.acad_status='1'";
  $query = $this->db->query($sql,array($syear,$sess,$user_id));

  if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_uploaded_list_comm($syear,$sess,$aggrid,$dept,$course,$branch,$sec){

         $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
         inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
where a.session_year=? and a.`session`=? and a.aggr_id=?
and a.dept_id=? and a.course_id=? and a.branch_id=? and a.section=? ";
          $query = $this->db->query($sql,array($syear,$sess,$aggrid,$dept,$course,$branch,$sec));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }
	function get_uploaded_list_prep($syear,$sess,$aggrid,$course,$branch){

         $sql = "select a.*,'Preparatory' as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
         inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
where a.session_year=? and a.`session`=? and a.aggr_id=?
 and a.course_id=? and a.branch_id=?  ";
          $query = $this->db->query($sql,array($syear,$sess,$aggrid,$course,$branch));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }

    function get_uploaded_list_all($syear,$sess,$dept,$course,$branch,$sem){

         $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
         inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
where a.session_year=? and a.`session`=? 
and a.dept_id=? and a.course_id=? and a.branch_id=? and a.semester=? order by a.id desc ";
          $query = $this->db->query($sql,array($syear,$sess,$dept,$course,$branch,$sem));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }

    function get_student_honour_status($id){
        $sql = "select a.* from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y'";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }


    }

    function get_student_minor_status($id){
        $sql = "select b.* from hm_form a inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y' and b.offered='1'";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }


    }
    function get_department($id){
        $sql = "select * from departments where id=?";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }


    }
    function get_course($id){
        $sql = "select * from cs_courses where id=?";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }


    }
    function get_branch($id){
        $sql = "select * from cs_branches where id=?";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }


    }
    function get_faculty_byDept($id){
        $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as faculty from user_details a
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
where a.dept_id=? and b.auth_id='ft'
and c.`status`='A' order  by a.first_name,a.middle_name,a.last_name";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }
    function get_dept($id){
        $sql = "select dept_id from user_details where id=?";
          $query = $this->db->query($sql,array($id));

          if ($this->db->affected_rows() > 0) {
                    return $query->row()->dept_id;
                } else {
                    return false;
                }

    }
function get_uploaded_list_all_ft($syear,$sess,$did,$fid,$topic){
		
        $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a 
inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id 
inner join user_details e on e.id=a.emp_no
where a.session_year='".$syear."' and a.`session`='".$sess."' ";

if($did!="none" && $did!=""){
	$sql.=" and a.dept_id='".$did."' ";
}
if($fid!="none" && $fid!=""){
    $sql.=" and a.emp_no='".$fid."'";
}
if($topic!="none" && $topic!=""){
    $sql.=" and a.topic_name='".$topic."'";
}
          $query = $this->db->query($sql);

//echo $this->db->last_query();die();

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }
    /*function get_uploaded_list_all_ft($syear,$sess,$fid,$topic){
        if($topic!="" && $topic!="none"){
            $wcon=" and a.topic_name=?";
            $p=array($syear,$sess,$fid,$topic);
        }else{
            $wcon="";
            $p=array($syear,$sess,$fid);

        }

        $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a 
inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id 
inner join user_details e on e.id=a.emp_no
where a.session_year=? and a.`session`=? and a.emp_no=?".$wcon;
          $query = $this->db->query($sql,$p);

          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }*/

//for jrf missing 02-02-2019

    function check_student__type($id){

        $sql = "select a.* from stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        
        //echo $this->db->last_query();   die();
        if ($query->num_rows() > 0){
            return $query->row()->auth_id;
        }else{
            return FALSE;    
        }
    }

    function get_student_jrf($syear,$sess,$user_id){
         $sql = "SELECT a.*,c.dept_id
FROM reg_regular_form a
INNER JOIN user_details c ON c.id=a.admn_no
WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? AND a.hod_status='1' AND a.acad_status='1';
";
  $query = $this->db->query($sql,array($syear,$sess,$user_id));
//echo $this->db->last_query();   die();
  if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_uploaded_list_jrf($syear,$sess,$admn_no){

         $sql = "select a.*,b.name as dname,c.name as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
 inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
inner join reg_exam_rc_subject f on f.sub_id=a.sub_id
inner join reg_exam_rc_form g on g.form_id=f.form_id and g.session_year=? and g.`session`=? and g.admn_no=?
where a.session_year=? and a.`session`=?  ";
          $query = $this->db->query($sql,array($syear,$sess,$admn_no,$syear,$sess));
//echo $this->db->last_query();   die();
          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }
    
	
	function get_uploaded_list_jrf_cbcs($syear,$sess,$admn_no){

         $sql = "(select a.*,b.name as dname,'Ph.D' as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
 inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
INNER JOIN cbcs_stu_course f ON f.subject_code = a.sub_id
INNER JOIN reg_regular_form g ON f.form_id=g.form_id and g.session_year=? and g.`session`=? and g.admn_no=?
where a.session_year=? and a.`session`=?)
union
(select a.*,b.name as dname,'Ph.D' as cname,d.name as bname,concat_ws(' ',e.salutation,e.first_name,e.middle_name,e.last_name)as fname from faculty_tutorial_main a
 inner join departments b on b.id=a.dept_id
inner join cs_courses c on c.id=a.course_id
inner join cs_branches d on d.id=a.branch_id
inner join user_details e on e.id=a.emp_no
INNER JOIN old_stu_course f ON f.subject_code = a.sub_id
INNER JOIN reg_regular_form g ON f.form_id=g.form_id and g.session_year=? and g.`session`=? and g.admn_no=?
where a.session_year=? and a.`session`=?)  ";
          $query = $this->db->query($sql,array($syear,$sess,$admn_no,$syear,$sess,$syear,$sess,$admn_no,$syear,$sess));
//echo $this->db->last_query();   die();
          if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

    }
	
	function check_student_type($id){
		
		$sql="select a.* from stu_academic a where a.admn_no='".$id."'";
		$query = $this->db->query($sql);

          if ($this->db->affected_rows() > 0) {
                    return $query->row()->auth_id;
                } else {
                    return false;
                }
		
	}
	
	function get_student_subject_list($syear,$sess,$user_id){
		$sql="SELECT a.* FROM pre_stu_course a WHERE a.session_year=? AND a.`session`=? AND a.admn_no=? AND (a.remark2='1' || a.remark2='3')";
		$query = $this->db->query($sql,array($syear,$sess,$user_id));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();;
                } else {
                    return false;
                }
		
	}
	
	function get_uploded_class_materials_main($syear,$sess,$admn_no){
		$sql="SELECT t.*,
b.name AS dname,c.name AS cname,d.name AS bname, CONCAT_WS(' ',e.salutation,e.first_name,e.middle_name,e.last_name) AS fname
 from
(SELECT b.* FROM cbcs_stu_course a
INNER JOIN faculty_tutorial_main b ON CONCAT('c',b.map_id )=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=?  AND b.aggr_id='cbcs'
 and (case when b.section!='' AND b.section!='0' then 	 b.section=a.sub_category_cbcs_offered ELSE 1=1   END)
AND a.admn_no=?
UNION
SELECT b.* FROM old_stu_course a
INNER JOIN faculty_tutorial_main b ON CONCAT('o',b.map_id )=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=?  AND b.aggr_id='old'
 and (case when b.section!='' AND b.section!='0' then 	 b.section=a.sub_category_cbcs_offered ELSE 1=1   END)
AND a.admn_no=? )t
INNER JOIN departments b ON b.id=t.dept_id
INNER JOIN cs_courses c ON c.id=t.course_id
INNER JOIN cs_branches d ON d.id=t.branch_id
INNER JOIN user_details e ON e.id=t.emp_no ";
		$query = $this->db->query($sql,array($syear,$sess,$admn_no,$syear,$sess,$admn_no));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();;
                } else {
                    return false;
                }
	}
	function get_uploded_class_materials_pre($syear,$sess,$admn_no){
		$sql="(SELECT t.*,
b.name AS dname,c.name AS cname,d.name AS bname, CONCAT_WS(' ',e.salutation,e.first_name,e.middle_name,e.last_name) AS fname
 from
(SELECT b.* FROM pre_stu_course a
INNER JOIN faculty_tutorial_main b ON CONCAT('c',b.map_id )=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=?  AND b.aggr_id='cbcs'
 and (case when b.section!='' AND b.section!='0' then 	 b.section=a.sub_category_cbcs_offered ELSE 1=1   END)
AND a.admn_no=? AND (a.remark2='1' || a.remark2='3')
UNION
SELECT b.* FROM pre_stu_course a
INNER JOIN faculty_tutorial_main b ON CONCAT('o',b.map_id )=a.sub_offered_id
WHERE a.session_year=? AND a.`session`=?  AND b.aggr_id='old'
 and (case when b.section!='' AND b.section!='0' then 	 b.section=a.sub_category_cbcs_offered ELSE 1=1   END)
AND a.admn_no=? AND (a.remark2='1' || a.remark2='3'))t
INNER JOIN departments b ON b.id=t.dept_id
INNER JOIN cs_courses c ON c.id=t.course_id
INNER JOIN cs_branches d ON d.id=t.branch_id
INNER JOIN user_details e ON e.id=t.emp_no)union
(SELECT t.*, b.name AS dname,c.name AS cname,d.name AS bname, CONCAT_WS(' ',e.salutation,e.first_name,e.middle_name,e.last_name) AS fname
FROM (
SELECT b.*
FROM cbcs_stu_course a
inner JOIN faculty_tutorial_main b ON b.map_id=a.sub_offered_id
WHERE a.session_year='".$syear."' AND a.`session`='".$sess."'   and b.course_id='online'
AND a.admn_no='".$admn_no."'
)t
INNER JOIN departments b ON b.id=t.dept_id
INNER JOIN cs_courses c ON c.id=t.course_id
INNER JOIN cs_branches d ON d.id=t.branch_id
INNER JOIN user_details e ON e.id=t.emp_no) ";
		$query = $this->db->query($sql,array($syear,$sess,$admn_no,$syear,$sess,$admn_no));

          if ($this->db->affected_rows() > 0) {
                    return $query->result();;
                } else {
                    return false;
                }
	}
	
	
     

}

?>