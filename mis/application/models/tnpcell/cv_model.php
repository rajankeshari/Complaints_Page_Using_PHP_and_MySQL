<?php

class Cv_model extends CI_Model
{
  var $table_projects = 'tnp_cv_projects';
  var $table_achievements='tnp_cv_achievements';
  var $tabulation1 ='tabulation1';

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function insert_achievements($cv_details)
  {
    $query= $this->db->insert($this->table_achievements,$cv_details);
    if($this->db->affected_rows() > 0){
      return true;
    }
    return false;
  }

  function get_achievements($user_id)
  {
    $query=$this->db->get_where($this->table_achievements, array('user_id'=>$user_id));
    return $query->result();
  }

  function update_achievements($data, $id){
    $details = array('user_id' => $id);
    $this->db->where($details);
    $this->db->update($this->table_achievements, $data);
    if( isset($data['submit_status']) && $data['submit_status'] == 1)
    {
       $time   = date("Y-m-d H:i:s");
    $query = $this->db->query("INSERT INTO `cv_submit_timestamp`(`admn_no`, `timestamp`) VALUES ('".$id."','".$time."') ");
    }
    return $this->db->affected_rows();
  }

  function insert_project($project_details)
  {
    $query = $this->db->insert($this->table_projects,$project_details);
    if($this->db->affected_rows() > 0){
      return true;
    }
    return false;
  }

  function delete_project($project_details)
  {
    $query = $this->db->delete($this->table_projects,$project_details);
    if($this->db->affected_rows() > 0){
      return true;
    }
    return false;
  }

  function get_project($user_id)
  {
    $query=$this->db->get_where($this->table_projects, array('user_id'=>$user_id));
    return $query->result();
  }

  function update_project($data, $id, $i)
  {
    $details = array('user_id' => $id, 'row_no' => $i);
    $this->db->where('user_id',$id);
    $this->db->where('row_no',$i);
    $this->db->update($this->table_projects, $data);
    return $this->db->affected_rows();
  }



  function get_all_student_details($id){
    $query = $this->db->query('SELECT branches.name as branch_name, 
      courses.name as course_name,
      CONCAT_WS(" ",ud.first_name,ud.middle_name,ud.last_name) as stu_name,
      ud.id as admn_no,
      ud.dob as dob,
      ud.sex as sex,
      ud.email as email,
      uod.mobile_no as mobile_no,
      CONCAT_WS(" ",uadd.line1, uadd.line2, uadd.city, uadd.state, "-", uadd.pincode,",",uadd.country,"Contact-",uadd.contact_no) as address
    
    FROM `user_details`as ud 
    INNER JOIN stu_details as sd ON sd.admn_no = ud.id 
    INNER JOIN user_other_details as uod ON ud.id = uod.id
    INNER JOIN user_address as uadd ON uadd.id = ud.id AND uadd.type = "present"
    INNER JOIN stu_academic as sa ON sa.admn_no = sd.admn_no 
    INNER JOIN branches ON branches.id = sa.branch_id 
    INNER JOIN courses ON courses.id = sa.course_id 
    WHERE ud.id="'.$id.'"');
    return $query->result();
  }

  function get_gpa_and_ogpa($id){

    $query = $this->db->query('SELECT DISTINCT tb.gpa as gpa, tb.ogpa as ogpa 
    FROM `tabulation1` as tb 
    WHERE tb.adm_no="'.$id.'"
    ORDER BY tb.ctotcrpts');
    return $query->result();
  }

  function get_gpa_and_ogpa_with_hons($id){

    $query = $this->db->query("SELECT DISTINCT fs.core_gpa as gpa, fs.core_cgpa as ogpa, fs.cgpa as ogpa_hons, fs.hstatus as hstatus 
    FROM `final_semwise_marks_foil` as fs 
    WHERE fs.admn_no='$id' and fs.course != 'MINOR'
    ORDER BY fs.semester");
    return $query->result();
  }
  function check_honour($admn_no,$sem){
     $sql = "select * from hm_form  where admn_no='".$admn_no."' and semester=".$sem." and honours='1' and honour_hod_status='Y'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
            return FALSE;
        }
  }
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */