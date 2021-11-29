<?php
class Hm_form_rejection_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_hm_form_status($admn_no){
$query = $this->db->query("select * from hm_form where admn_no='".$admn_no."'");
        if ($query->num_rows() > 0) { // 
            return $query->result();
        } else {
            return false;
        }

    }

    function get_student_details($admn_no){
$query = $this->db->query("select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as sname,
a.dept_id,b.course_id,b.branch_id,c.name as dname,d.name as cname,e.name as bname,b.semester
 from user_details a 
inner join stu_academic b on b.admn_no=a.id
inner join departments c on c.id=a.dept_id
inner join cs_courses d on d.id=b.course_id
inner join cs_branches e on e.id=b.branch_id
where a.id='".$admn_no."'");
        if ($query->num_rows() > 0) { // 
            return $query->result();
        } else {
            return false;
        }

    }

    function update_honour_status($admn_no,$astatus){
    	date_default_timezone_set('Asia/Calcutta');

    	if($astatus=='R'){ $h='0';}
    	if($astatus=='Y'){ $h='1';}
    	$uid=$this->session->userdata('id');
    	$ts=date("Y-m-d H:i:s");

    	$sql = "update hm_form set honours='".$h."',honour_hod_status='".$astatus."',W_reason_honour='".$uid."',honour_hod_timestamp='".$ts."' where admn_no='".$admn_no."'";
        $query = $this->db->query($sql);
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    

	
	
}?>