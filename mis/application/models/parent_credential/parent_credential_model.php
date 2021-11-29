<?php
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Parent_credential_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


// Reset Pre-Registation by @abhijeet start

function savePreData($savepre){
//  $this->db->trans_start();
  $this->db->insert('pre_stu_course',$savepre);
//  $this->db->trans_complete();
  return $this->db->insert_id();
}

function insert_reg_regula_fee($feedata){
  //$this->db->trans_start();
  $this->db->insert('reg_regular_fee',$feedata);
    //echo $this->db->last_query();exit;
//  $this->db->trans_complete();
  return $this->db->insert_id();
}

function insert_reg_regular($insert_reg_regular){
  //  $this->db->trans_start();
    $this->db->insert('reg_regular_form',$insert_reg_regular);
    //  echo $this->db->last_query();exit;
    if ($this->db->affected_rows() > 0) {
      //echo $this->db->error();
      //  exit;
        return true;

    }else{
      return false;
    //  print_r($insert_reg_regular);echo "<br>";
    //  echo "false";
    //    exit;
    }
    //$this->db->trans_complete();


}
    function getCurrentOfferedCourse($dept_id,$course_id,$branch_id){
      $sql="select concat('c',a.id) as sub_offered_id,a.*,null as a from cbcs_subject_offered a
where a.session_year='2020-2021' and a.`session`='Monsoon' and a.dept_id='$dept_id' and a.course_id='$course_id'
and a.branch_id='$branch_id' and a.semester='5'
union
select concat('o',a.id) as sub_offered_id,a.* from old_subject_offered a
where a.session_year='2020-2021' and a.`session`='Monsoon' and a.dept_id='$dept_id' and a.course_id='$course_id'
and a.branch_id='$branch_id' and a.semester='5';";
  $query = $this->db->query($sql);
  //echo $this->db->last_query();exit;
    if ($this->db->affected_rows() >= 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  function update_reg_regular($whereClouse){
  //  $this->db->where($whereClouse);
  //  $this->db->delete('pre_stu_course');
    $update=array(
      "hod_status"=>'2',
      "acad_status"=>'2',
      "acad_remark"=>"Canceled pre-registation Due to Covid-19",
    );
    $this->db->where($whereClouse);
    $this->db->update('reg_regular_form',$update);
  }
  function Getreg_stu($id){
    $sql="select * from reg_regular_form a
where a.session_year='2020-2021' and a.`session`='Monsoon' and a.semester='5'
#and (a.course_id='b.tech' or a.course_id='dualdegree')
and a.admn_no like '%$id%'";
$query = $this->db->query($sql);
  if ($this->db->affected_rows() >= 0) {
    return $query->result();
  } else {
    return false;
  }
  }
  
    function stuData($adm_no){
    $sql="SELECT UPPER(a.id) AS admn_no,b.semester,a.dept_id, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name, CASE b.course_id WHEN 'exemtech' THEN b.course_id /*'M.TECH 3 YR'*/  ELSE b.course_id END as course_id,b.branch_id,c.name,
    UPPER(CONCAT(CASE b.course_id WHEN 'exemtech' THEN b.course_id /*'M.TECH 3 YR'*/ ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,
    a.photopath FROM user_details a INNER JOIN stu_academic b ON b.admn_no=a.id
    INNER JOIN cs_branches c ON c.id=b.branch_id WHERE a.id=?";
$query = $this->db->query($sql,array($adm_no));

//echo $this->db->last_query(); die();
if ($this->db->affected_rows() >= 0) {
    return $query->result();
} else {
    return false;
}
  }

  // Reset Pre-Registation by @abhijeet end


    function get_user($admn_no)
    {
        $query = $this->db->select('*')->from('emaildata')->join('user_details', 'emaildata.admission_no=user_details.id')
        ->where('user_details.id',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            $parentdata = $this->parent_details($admn_no);
            $row['parent_mobile_no'] = $parentdata['parent_mobile_no'];
            $row['account_no'] = $parentdata['account_no'];
            return $row;
        }
        else
        {
            return false;
        }
    }

    function get_stu($admn_no)
    {
        $query = $this->db->select('*')->from('user_details')->join('stu_academic', 'user_details.id=stu_academic.admn_no')
        ->join('user_other_details', 'user_details.id=user_other_details.id')
        ->where('user_details.id',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            $branch = $this->get_branch($row['branch_id']);
            $course = $this->get_course($row['course_id']);
            $department = $this->get_department($row['dept_id']);
            $row['branch'] = $branch['name'];
            $row['course'] = $course['name'];
            $row['department'] = $department['name'];
            $parentdata = $this->parent_details($admn_no);
            $row['parent_mobile_no'] = $parentdata['parent_mobile_no'];
            $row['account_no'] = $parentdata['account_no'];
            return $row;
        }
        else
        {
            return false;
        }
    }

    function parent_details($admn_no)
    {
        $query = $this->db->select('*')->from('stu_details')->join('stu_other_details', 'stu_details.admn_no=stu_other_details.admn_no')
        ->where('stu_details.admn_no',$admn_no)->get();
       $row = $query->result_array();

       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function user_email_domain($admn_no)
    {
       $this->db->where('admission_no',$admn_no);
	   $query=$this->db->get('emaildata');
       $row = $query->result_array();
       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function user_email($admn_no)
    {
        $this->db->where('id',$admn_no);
        $query=$this->db->get('user_details');
       if($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row;
        }
        else
        {
            return false;
        }
    }

    function insert_parent_credential_logs($parent_credential_logs)
	{
        $this->db->insert('parent_credential_logs',$parent_credential_logs);
        return TRUE;
    }

    function get_branch($branch_id)
	{
		$this->db->where('id',$branch_id);
		$query=$this->db->get('branches');
        $row=$query->row_array();
		return $row;
    }
    
    function get_course($course_id)
	{
		$this->db->where('id',$course_id);
		$query=$this->db->get('courses');
        $row=$query->row_array();
		return $row;
    }
    
    function get_department($dept_id)
	{
		$this->db->where('id',$dept_id);
		$query=$this->db->get('departments');
        $row=$query->row_array();
		return $row;
    }
    
    function insert_new_adm_repoting($new_adm_reporting)
	{
        $this->db->insert('new_adm_reporting',$new_adm_reporting);
        return TRUE;
    }

    function check_counter_log($admn_no)
	{
        $this->db->where('admn_no',$admn_no);
        $this->db->order_by('id','DESC');
		$query=$this->db->get('parent_credential_logs');
        if($query->num_rows() > 0)
        {
            $row=$query->result_array();
            return $row;
        }
        
	}
    
    

}