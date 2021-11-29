<?php

class Grade_sheet_final_foil_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function check_student_auth_id($admn_no)
    {
        $sql="select a.* from stu_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    function alumni_check_student_auth_id($admn_no)
    {
        $sql="select a.* from alumni_academic a where a.admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    //to get dept from user_details table
    
    function get_student_dept($admn_no)
    {
        $sql="select * from user_details where id=?";
        $query = $this->db->query($sql,array($admn_no));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
	
	function get_student_foil_branch_comm_other($data){
		  $this->db->select('a.branch');
            $this->db->from('final_semwise_marks_foil a');								
            $this->db->where($data);
            $query = $this->db->get();
			// echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
    }
		
    function get_student_foil_data($data){
		  $this->db->select('				  
								a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status,
								',FALSE );
            $this->db->from('final_semwise_marks_foil a');								
            $this->db->where($data);
            $query = $this->db->get();
			// echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return FALSE;
            }
    }
    //foil_freeze
    function get_student_foil_data_freeze($data){
	    $this->db->select(					  
								'a.old_id,
								a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status,
								a.published_on,
								a.actual_published_on,
								a.result_dec_id',FALSE );

						  
						  
		 $this->db->from('final_semwise_marks_foil_freezed a');
        $this->db->where($data);
        $this->db->order_by('actual_published_on','desc');
        $this->db->limit('1');
        $query = $this->db->get();
		// echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        }
        else {
            return FALSE;
        }
    }
    
   
    function get_student_foil_description($id){
                //$this->db->select('a.*,b.name');
				$this->db->select('a.*,b.name,c.sequence as seq,c.aggr_id ');
                $this->db->from('final_semwise_marks_foil_desc a');
                $this->db->join('subjects b', 'a.mis_sub_id = b.id','inner');
				$this->db->join('course_structure c', 'a.mis_sub_id = c.id','left');
                $this->db->where('a.foil_id', $id);
				$this->db->order_by('(0+seq)','ASC');
                $query = $this->db->get();
                //echo $this->db->last_query();die();
                if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return FALSE;
                }
    }
    //foil freeze description
    function get_student_foil_description_freeze($id){
        $this->db->select('a.*,b.name,c.sequence as seq,c.aggr_id ');
        $this->db->from('final_semwise_marks_foil_desc_freezed a');
        $this->db->join('subjects b', 'a.mis_sub_id = b.id','inner');
		$this->db->join('course_structure c', 'a.mis_sub_id = c.id','left');
        $this->db->where('a.foil_id', $id);
		$this->db->order_by('(0+seq)','ASC');
		
        $query = $this->db->get();
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
                 return FALSE;
        }
    }
    //cbcs 2019
	function get_student_foil_description_2019($id,$admn_no){
        $sql = "(select a.*,b.subject_name,c.sub_type , b.course,  c.lecture ,c.tutorial ,c.practical,
(case when b.course='honour' then  concat(b.subject_name, '( Honour )')    else  b.subject_name end)    as name from final_semwise_marks_foil_desc_freezed a 
inner join old_stu_course  b on b.subject_code=a.sub_code 
inner join old_subject_offered c on c.id=b.sub_offered_id
where a.foil_id=? and b.admn_no=? 
order by  b.course , c.lecture desc,c.tutorial desc,c.practical desc)
;";

        $query = $this->db->query($sql,array($id,$admn_no));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
	
	//end cbcs 2019 case
	
	
	
    
    
    function get_student_details($id){
        $sql = "SELECT UPPER(a.id) AS admn_no, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name, 
CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END as course_id,b.branch_id,c.name, UPPER(CONCAT(CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,a.photopath
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN cs_branches c ON c.id=b.branch_id
WHERE a.id=?";

        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }
    
    function get_student_gpa($id){
        $sql = "(select b.sem as semester,round(a.gpa,2)as core_gpa,'NA' as gpa,round(a.ogpa,2)as core_cgpa,'NA' as cgpa,
a.passfail as `core_status`,'NA' as `status`,a.examtype as `type`,'N' as hstatus from tabulation1 a 
inner join dip_m_semcode b on a.sem_code=b.semcode
where a.adm_no=?
group by a.ysession,a.wsms,a.examtype,a.sem_code)union
(select a.semester,round(a.core_gpa,2) as core_gpa,round(a.gpa,2)as gpa,round(a.core_cgpa,2)as core_cgpa,
round(a.cgpa,2)as cgpa,a.core_status,a.`status`,a.`type`,a.hstatus from final_semwise_marks_foil a
where a.admn_no=? and a.course<>'MINOR') ";

        $query = $this->db->query($sql,array($id,$id));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    function grade_sheet_details_jrf_foil($admn_no, $sy, $sess,$et){
        $sql = "select c.name,b.*,a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status from final_semwise_marks_foil_desc b 
inner join final_semwise_marks_foil a on a.id=b.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.id=b.mis_sub_id
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.`type`=?";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    //from freeze
    
    function grade_sheet_details_jrf_foil_freeze($admn_no, $sy, $sess,$et){
        $sql = "select c.name,b.*,a.old_id,
								a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status,
								a.published_on,
								a.actual_published_on,
								a.result_dec_id
		from final_semwise_marks_foil_desc_freezed b 
inner join final_semwise_marks_foil_freezed a on a.id=b.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.id=b.mis_sub_id
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.`type`=? and a.actual_published_on=(select max(a.actual_published_on) from final_semwise_marks_foil_freezed a
WHERE a.admn_no=? AND a.session_yr=? AND a.`session`=? AND a.`type`=?)";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et,$admn_no, $sy, $sess,$et));
         //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
        function grade_sheet_details_jrf_foil_subject_code($admn_no, $sy, $sess,$et){
        $sql = "select c.name,b.*,a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status from final_semwise_marks_foil_desc b 
inner join final_semwise_marks_foil a on a.id=b.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.subject_id=b.sub_code
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.`type`=?";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    //freeze
    function grade_sheet_details_jrf_foil_subject_code_freeze($admn_no, $sy, $sess,$et){
        $sql = " select c.name,b.*,a.old_id,
								a.id,
								a.session_yr,
								a.session,
								a.dept,
								a.course,
								a.branch,
								a.semester,
								a.admn_no,
								a.tot_cr_hr,
								a.tot_cr_pts,
								a.core_tot_cr_hr,
								a.core_tot_cr_pts,
								a.ctotcrpts,
								a.core_ctotcrpts,
								a.ctotcrhr,
								a.core_ctotcrhr,
								format(a.gpa,5) AS gpa,
								format(a.core_gpa,5) as core_gpa,
								format(a.cgpa,5) as cgpa,
								format(a.core_cgpa,5) as core_cgpa,
								a.status,
								a.core_status,
								a.hstatus,
								a.repeater,
								a.type,
								a.exam_type,
								a.final_status,
								a.published_on,
								a.actual_published_on,
								a.result_dec_id from final_semwise_marks_foil_desc_freezed b 
inner join final_semwise_marks_foil_freezed a on a.id=b.foil_id and b.admn_no=a.admn_no
inner join subjects c on c.subject_id=b.sub_code
where a.admn_no=? and a.session_yr=? and a.`session`=?
and a.`type`=? ";

        $query = $this->db->query($sql,array($admn_no, $sy, $sess,$et));
        // echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    
    
    function alumni_get_course_duration_sem($adm_no)
    {
        $sql="select a.course_id,b.duration,(b.duration*2)as tsem,a.auth_id  from alumni_academic a
inner join cs_courses b on a.course_id=b.id and a.admn_no='".$adm_no."'
group by a.course_id";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
	function check_branch_change($id){
		$sql="select a.*,Upper(concat(a.new_course_id,' (',b.name,')')) as discipline from cbcs_transcript a 
inner join cbcs_branches b on b.id=a.new_branch_id
where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        // echo $this->db->last_query();    
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
		
	}
    
  
}

?>