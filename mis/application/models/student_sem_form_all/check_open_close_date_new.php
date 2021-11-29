<?php 
class Check_open_close_date_new extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        /*function get_open_close_date_id($open_for,$etype,$syear,$sess){
            $sql = "select a.id from sem_date_open_close_tbl a where a.open_for=? and a.exam_type=? and a.session_year=? and a.`session`=? order by a.id desc limit 1";
        $query = $this->db->query($sql,array($open_for,$etype,$syear,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }*/
		
		
		function get_open_close_date_id($open_for,$etype,$syear,$sess){
            $sql = "select a.id from sem_date_open_close_tbl a where a.open_for=? and a.exam_type=? and a.session_year=? and a.`session`=? order by a.id desc limit 1";
        $query = $this->db->query($sql,array($open_for,$etype,$syear,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }
		
		
		
      /*  function get_open_close_date_id_indi($open_for,$etype,$syear,$sess,$id){
            $sql = "select a.id from sem_date_open_close_tbl a where a.open_for=? and a.exam_type=? and a.session_year=? and a.`session`=? and admn_no=? ";
        $query = $this->db->query($sql,array($open_for,$etype,$syear,$sess,$id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }*/
        
        function get_open_close_date_id_indi($open_for,$etype,$id){
            $sql = "select a.id from sem_date_open_close_tbl a where a.open_for=? and a.exam_type=? and admn_no=? order by a.id desc limit 1";
        $query = $this->db->query($sql,array($open_for,$etype,$id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }
        
        
       function check_open_close_all_normal($id)
       {
   
        $sql = "select * from sem_date_open_close_tbl where id= ?   and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') 
 and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
            
            
        }
        
        function check_open_close_all_latefine($id){
        $sql = "select * from sem_date_open_close_late_tbl where master_id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        function check_open_close_late_fine($id){
        $sql = "select * from sem_date_open_close_late_tbl where id= ?   and CURDATE() between DATE_FORMAT(late_start_date, '%Y-%m-%d')  and DATE_FORMAT(late_close_date, '%Y-%m-%d') 
 ";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_row_details($id){
        $sql = "  select * from sem_date_open_close_tbl where id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_stu_dept_course_bracnh_sem($id){
        $sql = "select a.dept_id,b.course_id,b.branch_id,b.semester+1 as semester from user_details a
inner join stu_academic b on a.id=b.admn_no
where admn_no=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_specific_normal($rowid,$dept,$course,$branch,$sem){
        $sql = "select a.* from sem_date_open_close_tbl a
        where a.id=? and a.dept=? and a.course=? and a.branch=? and a.semester=?";

        $query = $this->db->query($sql,array($rowid,$dept,$course,$branch,$sem));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_open_close_individual_normal($rowid,$id){
        $sql = "select * from sem_date_open_close_tbl where id= ? and admn_no=? and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";

        $query = $this->db->query($sql,array($rowid,$id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_latest_session_session_year($id,$openfor)
    {
        $sql = "select a.session_year,a.`session` from sem_date_open_close_tbl a where a.`session`=? and a.open_for=? order by a.id  desc limit 1";
        $query = $this->db->query($sql,array($id,$openfor));
       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function check_student_already_registered($admn_no,$sess,$syear,$sem){
        $sql = "select a.* from reg_regular_form a
where a.admn_no=? and a.`session`=?
and a.session_year=? and a.semester=? and a.hod_status<>'2' and a.acad_status<>'2'";
        $query = $this->db->query($sql,array($admn_no,$sess,$syear,$sem));
       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        
    }
	function get_enrollment_year($id){
        $sql="select enrollment_year from stu_academic where admn_no=?";
        $query = $this->db->query($sql,array($id));
       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->enrollment_year;
        } else {
            return false;
        }
    }
	
	function get_temp_details($admn_no,$sy,$sess){
		
		$sql = "select a.admn_no as admn_no,a.form_id as form_id from reg_regular_form a where a.admn_no=? and a.session_year=? and a.`session`=? ";
        $query = $this->db->query($sql,array($admn_no,$sy,$sess));
       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
		
		
	}
	
	function cbcs_get_open_close_date_id_indi($open_for,$etype,$id,$syear,$sess){
            $sql = "select a.id from sem_date_open_close_tbl a where a.open_for=? and a.exam_type=? AND admn_no=? and a.session_year=? and a.`session`=? order by a.id desc limit 1";
        $query = $this->db->query($sql,array($open_for,$etype,$id,$syear,$sess));

       
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }
}

?>