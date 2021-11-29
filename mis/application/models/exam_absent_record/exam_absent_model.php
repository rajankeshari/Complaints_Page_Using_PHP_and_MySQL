<?php

class Exam_absent_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_course_agg_id($a_id, $session, $session_year, $et = '') {
        $sql = "select form_id,admn_no,course_aggr_id,semester,section from reg_regular_form 
where session_year='" . $session_year . "' and session='" . $session . "' and hod_status='1'
and acad_status='1' and admn_no='" . $a_id . "' ";

        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

	
	public function get_course_agg_id_jrf($a_id, $session, $session_year, $et = '') {
        $sql = "select form_id,admn_no,course_aggr_id,semester,'na' as section from reg_exam_rc_form
where session_year='" . $session_year . "' and session='" . $session . "' and hod_status='1'
and acad_status='1' and admn_no='" . $a_id . "' ";

        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

	
    public function get_subject_list_by_admnno($sem, $aid) {

        $sql = " select a.id,a.semester,a.aggr_id,a.sequence,b.subject_id,b.name,b.credit_hours from course_structure as a
inner join subjects as b on a.id=b.id where  semester='" . $sem . "' and elective='0' 
and a.aggr_id='" . $aid . "' ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_elec_subject_list_by_admnno($adm_no, $sy, $sess) {
        $sql = " select * from subjects where id in(select sub_id from reg_regular_elective_opted where form_id=(select form_id from reg_regular_form where admn_no=? and hod_status='1'and acad_status='1' and session_year=? and `session`=?)) ";


        $query = $this->db->query($sql, array($adm_no, $sy, $sess));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function insert_batch($data) {
        if ($this->db->insert_batch('stu_exam_absent_mark', $data))
            return TRUE;
        else
            return FALSE;
    }

    function save_record_absent($data) {
        if ($this->db->insert('stu_exam_absent_mark', $data))
        //return $this->db->insert_id();
            return TRUE;
        else
            return FALSE;
    }

    function insert_res_remarks($data) {
        if ($this->db->insert('stu_result_restriction', $data))
        //return $this->db->insert_id();
            return TRUE;
        else
            return FALSE;
    }

    function get_existing_result_restruction($adm_no, $session, $syear, $sem,$etype) {
        $sql = " select start_reason from stu_result_restriction where admn_no=? 
             and semester=? and session_year=? and session=? and exam_type=?";



        $query = $this->db->query($sql,array($adm_no, $session, $syear, $sem,$etype));
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->start_reason;
        } else {
            return false;
        }
    }

    function get_existing_list_by_admnno($id) {
        $sql = " select * from stu_exam_absent_mark where form_id=? ";


        $query = $this->db->query($sql,array($id));
        $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function del_absenties_byFormID($id) {
        $this->db->where('form_id', $id); // I change id with book_id
        $this->db->delete('stu_exam_absent_mark');
    }

    function get_existing_list_by_subjectID($id, $subid, $sy, $s, $sem) {
        $sql = " select status from stu_exam_absent_mark where admn_no='" . $id . "' and sub_id='" . $subid . "'
and session_year='" . $sy . "'
and session='" . $s . "'
and semester='" . $sem . "' ";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    //--------------------------------------For Others-----------------------------------------


    public function get_others_detail($session_year,$session,$a_id ,$et) {
     
//       $sql="select a.form_id,a.admn_no,a.course_aggr_id,a.semester,a.`type`,a.examtype,b.semester as sem from reg_exam_rc_form a
//left join stu_exam_absent_mark b on a.form_id=b.form_id
//where a.session_year=? and a.`session`=? and a.hod_status='1'
//and a.acad_status='1' and a.admn_no=? and a.`type`=?";
//        $query = $this->db->query($sql, array($session_year,$session,$a_id ,$et));
        if ($et == "Other" || $et == "JRF") { $et = 'R'; } if ($et == "Special") { $et = 'S';}
        
        $sql="select * from (select a.form_id,a.admn_no,a.course_aggr_id,a.semester,b.semester as sem from reg_other_form a
left join stu_exam_absent_mark b on a.form_id=b.form_id
where a.session_year=? and a.`session`=?  and a.admn_no=?
and hod_status<>'2' and acad_status<>'2')p 
union
select * from (select a.form_id,a.admn_no,a.course_aggr_id,a.semester,b.semester as sem from reg_exam_rc_form a
left join stu_exam_absent_mark b on a.form_id=b.form_id
where a.session_year=? and a.`session`=?  and a.admn_no=? and a.`type`=?
and hod_status<>'2' and acad_status<>'2')p";
        
        $query = $this->db->query($sql, array($session_year,$session,$a_id,$session_year,$session,$a_id ,$et));
        
        
   //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_subject_list_for_others_byFormId($id) {

       // $sql = " select a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name from reg_exam_rc_subject as a inner join subjects as b                       on a.sub_id=b.id where form_id='" . $id . "' ";
        
//        $sql="SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name,c.semester
//FROM reg_exam_rc_subject AS a
//INNER JOIN subjects AS b ON a.sub_id=b.id
//inner join course_structure c on b.id=c.id
//WHERE form_id=?";
        
        $sql="
select * from (
SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name,c.semester
FROM reg_other_subject AS a
INNER JOIN subjects AS b ON a.sub_id=b.id
inner join course_structure c on b.id=c.id
WHERE form_id=?)p

union

select * from(
SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name,c.semester
FROM reg_exam_rc_subject AS a
INNER JOIN subjects AS b ON a.sub_id=b.id
inner join course_structure c on b.id=c.id
WHERE form_id=?)q
";


        $query = $this->db->query($sql,array($id,$id));
       // echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    //---------------------------JRF-------------------------
    
    public function get_subject_list_for_others_byFormId_jrf($id) {

        
        $sql="
select * from (
SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name
FROM reg_other_subject AS a
INNER JOIN subjects AS b ON a.sub_id=b.id
WHERE form_id=?)p

union

select * from(
SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name
FROM reg_exam_rc_subject AS a
INNER JOIN subjects AS b ON a.sub_id=b.id

WHERE form_id=?)q
";


        $query = $this->db->query($sql,array($id,$id));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    
    //---------------------------------------------------------------------

  /*  public function get_honour($aid, $sea, $sy, $sem) {
		$sea='Monsoon';
        $sql = "select  subjects.id,subjects.subject_id,subjects.name from  (select hf1.admn_no from  hm_form hf1  where hf1.honours='1' and hf1.honour_hod_status='Y' and hf1.admn_no='" . $aid . "' and hf1.session='" . $sea . "' and hf1.session_year='" . $sy . "')k left join stu_academic on stu_academic.admn_no=k.admn_no inner join course_structure on course_structure.aggr_id=concat('honour','_',stu_academic.branch_id,'_2013_2014') and course_structure.semester='" . $sem . "' left join subjects on  subjects.id =  course_structure.id";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }*/

   /* public function get_minor($aid, $sea, $sy, $sem) {
		$sea='Monsoon';
        $sql = " select  subjects.id,subjects.subject_id,subjects.name from ( select hf2.admn_no,hm_minor_details.branch_id from hm_form hf2  inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id  and hm_minor_details.offered='1' and hf2.minor='1' and hf2.minor_hod_status='Y' and hf2.admn_no='" . $aid . "' and hf2.session='" . $sea . "' and hf2.session_year='" . $sy . "' )k  inner join course_structure cs2 on cs2.aggr_id=concat('minor','_',k.branch_id,'_2013_2014') and cs2.semester='" . $sem . "' left join subjects on  subjects.id =  cs2.id";


        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }*/
    //---------------------------------------------------Honour minor-----------------------------------------
    function get_student_hons_subject($admm_no,$sem){
        
        $sql = "select a.subject_id,a.name,b.sequence,a.id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(select a.honours_agg_id from hm_form a where a.admn_no=? and a.honours='1' and a.honour_hod_status='Y' group by a.honours_agg_id)
and b.semester=?";

        $query = $this->db->query($sql,array($admm_no,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_student_minor_subject($admm_no,$sem){
        
        $sql = "select a.subject_id,a.name,b.sequence,a.id from subjects a
inner join course_structure b on a.id=b.id
where b.aggr_id=(
select m.minor_agg_id from hm_minor_details m where 
m.form_id=(select a.form_id from hm_form a where a.admn_no=? and a.minor='1' and a.minor_hod_status='Y')
and m.offered='1')
and b.semester=?";

        $query = $this->db->query($sql,array($admm_no,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    
    
    
    //---------------------------------------------------------------------------------------------------------------

    function check_data($adm_no, $sem, $syear, $session,$et) {
      $sql = " select * from stu_result_restriction where admn_no=? and semester=? and session_year=? and session=? and exam_type=? ";


        $query = $this->db->query($sql,array($adm_no, $sem, $syear, $session,$et));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_stu_details($adm_no,$sy,$sess,$et) 
   {
       
        if($et=="Regular")
        {
        $sql="select a.admn_no,a.semester,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,c.name as dept_nm,d.name as course_nm,e.name as branch_nm from reg_regular_form a 
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.admn_no=? and a.session_year=? and a.`session`=? and
a.hod_status='1' and a.acad_status='1'";
        }
        if($et=="Other" || $et=="Special" || $et=="JRF")
        {
            if ($et == "Other" || $et=="JRF") {
                $et = 'R';
            }
            if ($et == "Special") {
                $et = 'S';
            }

            
            $sql="SELECT *
FROM (
SELECT a.admn_no,a.semester, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,c.name AS 
 dept_nm,d.name AS course_nm,e.name AS branch_nm
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN departments c ON c.id=b.dept_id
INNER JOIN cs_courses d ON d.id=a.course_id
INNER JOIN cs_branches e ON e.id=a.branch_id
WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND
 a.hod_status<>'2' AND a.acad_status<>'2' AND a.`type`=?)p 
 UNION
SELECT *
FROM (
SELECT a.admn_no,a.semester, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS stu_name,c.name AS 
 dept_nm,d.name AS course_nm,e.name AS branch_nm
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
INNER JOIN departments c ON c.id=b.dept_id
INNER JOIN cs_courses d ON d.id=a.course_id
INNER JOIN cs_branches e ON e.id=a.branch_id
WHERE a.admn_no=? AND a.session_year=? AND a.`session`=? AND
 a.hod_status<>'2' AND a.acad_status<>'2')q";
            
        }


        $query = $this->db->query($sql,array($adm_no,$sy,$sess,$et,$adm_no,$sy,$sess));
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

//    function get_stu_details_others($adm_no) {
//        $sql = "SELECT
//  `user_details`.`id`,
//  Concat(`user_details`.`first_name`, ' ', `user_details`.`middle_name`, ' ',
//  `user_details`.`last_name`) AS `stu_name`,
//  `stu_academic`.`course_id` as course_nm,
//  `stu_academic`.`branch_id` as branch_nm,
//  `stu_academic`.`semester`,
//  `departments`.`name` as dept_nm
//FROM
//  `user_details`
//  INNER JOIN `stu_academic` ON `user_details`.`id` = `stu_academic`.`admn_no`
//  INNER JOIN `departments` ON `user_details`.`dept_id` = `departments`.`id`
//WHERE
//      
//  `user_details`.`id` = '" . $adm_no . "';";
//
//
//        $query = $this->db->query($sql);
//        if ($this->db->affected_rows() > 0) {
//            return $query->result();
//        } else {
//            return FALSE;
//        }
//    }

    function insert_res_over_remarks($adm_no, $syear, $session, $etype,$sem,$oreason,$ordate) {

//                $this->db->update('stu_result_restriction', $data,array('admn_no'=> $adm_no,'semester' => $sem,'session_year' => $syear,'session' => $session));
//              
//           
//                if($this->db->affected_rows() >0)
//                { 
//                 return TRUE;
//                     
//                }
//                else
//                {
//                    return FALSE;
//                }

        $sql = "update stu_result_restriction set over_reason='" . $oreason . "',over_timestamp='" . $ordate . "' where admn_no='" . $adm_no . "' and session_year='" . $syear . "' and session='" . $session . "'  and exam_type='".$etype."' and semester='" . $sem . "'";

        //echo $sql;

        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_absenties($id) {
        $this->db->where('form_id', $id);
        $this->db->delete('stu_exam_absent_mark');
    }
    
    function get_semester_by_subjectID($id)
    {
        $sql = "select semester from course_structure where id=?";


        $query = $this->db->query($sql,array($id));
       
        if ($this->db->affected_rows() > 0) {
           return $query->row()->semester;
        } else {
            return FALSE;
        }
    }
    
    //------------------------------------Summer--------------------------------
    
    public function get_summer_detail($syear, $session, $adm_no)
    {	
        $sql = "select form_id,admn_no,course_aggr_id,semester,'na' as section from reg_summer_form 
where session_year=? and session=? and hod_status='1' and acad_status='1' and admn_no=? ";


        $query = $this->db->query($sql,array($syear, $session, $adm_no));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function get_stu_details_summer($adm_no, $syear, $session)
    {	
        $sql = "select a.admn_no,a.semester,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as stu_name,c.name as dept_nm,
d.name as course_nm,e.name as branch_nm from reg_summer_form a 
inner join user_details b on a.admn_no=b.id
inner join departments c on c.id=b.dept_id
inner join cs_courses d on d.id=a.course_id
inner join cs_branches e on e.id=a.branch_id
where a.admn_no=? and a.session_year=? and a.`session`=? and
a.hod_status='1' and a.acad_status='1' ";


        $query = $this->db->query($sql,array($adm_no, $syear, $session));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function get_subject_list_for_others_byFormId_summer($fid)
    {	
        $sql = "SELECT a.form_id,a.sub_seq,a.sub_id,b.subject_id,b.name as sub_name,c.semester,concat(b.name,'(',c.semester,')') as name
FROM reg_summer_subject AS a
INNER JOIN subjects AS b ON a.sub_id=b.id
inner join course_structure c on b.id=c.id
WHERE form_id=? ";


        $query = $this->db->query($sql,array($fid));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function get_course_agg_id_summer($a_id, $session, $session_year, $et = '') {
        $sql = "select form_id,admn_no,course_aggr_id,semester,'na' as section from reg_summer_form 
where session_year='" . $session_year . "' and session='" . $session . "' and hod_status='1'
and acad_status='1' and admn_no='" . $a_id . "' ";

        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	//==================================@16-08-2018 ==================================
    function get_aggr_id_by_subjectID($id)
    {
        $sql = "select aggr_id from course_structure where id=?";


        $query = $this->db->query($sql,array($id));
       
        if ($this->db->affected_rows() > 0) {
           return $query->row()->aggr_id;
        } else {
            return FALSE;
        }
    }
    
    //========================================================================================

}

?>