<?php
    
class Cbcs_offered_de_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_course_code()
    {

      $sql = "select * from cbcs_course_master order by sub_code";

  
      $query = $this->db->query($sql); 


      if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

function get_course_component()
{
    $sql = "SELECT a.id FROM cbcs_course_component a GROUP BY a.id ORDER BY a.id";


      $query = $this->db->query($sql);


      if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false; 
    }

}

function get_course_component_byCourse($cid,$sem)
{
    $sql = "SELECT CONCAT(a.course_component,a.sequence)AS course_component from cbcs_coursestructure_policy a WHERE a.course_id=? AND a.sem=?
GROUP BY a.id ORDER BY a.id;";


      $query = $this->db->query($sql,array($cid,$sem));


      if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }

}
function get_course_component_all($cid,$sem)
{
    $sql = "SELECT a.id FROM cbcs_course_component a GROUP BY a.id ORDER BY a.id;";


      $query = $this->db->query($sql);


      if ($this->db->affected_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }

}



function get_course_from_courseMaster($course_code){
 
$sql = "SELECT a.* FROM cbcs_course_master a WHERE a.sub_code=?";


      $query = $this->db->query($sql,array($course_code));


      if ($this->db->affected_rows() > 0) {
        return $query->row();
    } else {
        return false;
    }



}

function insert($data)
  {
    if($this->db->insert('cbcs_subject_offered',$data))
       return $this->db->insert_id();
    else
      return FALSE;
  }


   function insert_batch($data)
  {
    if($this->db->insert_batch('cbcs_subject_offered_desc',$data))
      return TRUE;
    else
      return FALSE;
  }
 
function insert_old($datatmp)
  {
	 $datatmp['map_id']='0';
    if($this->db->insert('old_subject_offered',$datatmp))
       return $this->db->insert_id();
    else
      return FALSE;
  }
  

   function insert_batch_old($data)
  {
    if($this->db->insert_batch('old_subject_offered_desc',$data))
      return TRUE;
    else
      return FALSE;
  }


//=======================
function insert_diff_f($data)
  {
    if($this->db->insert('cbcs_subject_offered_desc',$data))
      return TRUE;
    else
      return FALSE;
  }

function insert_old_diff_f($data)
  {
    if($this->db->insert('old_subject_offered_desc',$data))
      return TRUE;
    else
      return FALSE;
  }

  //==============================================================




  function get_ug_pg($cid)
  {

    switch($cid)
    {

    case "b.tech":
        return('ug');
        break;
    case "be":
        return('ug');
        break;
    case "comm":
        return('ug');
        break;
    case "dualdegree":
        return('ug');
        break;
    case "execmba":
        return('pg');
        break;
    case "exemtech":
         return('pg');
        break;
    case "int.m.sc":
         return('ug');
        break;

    
    
    case "int.m.tech":
         return('ug');
        break;
    case "int.msc.tech":
         return('ug');  
        break;
    case "m.phil":
         return('pg');
        break;
    case "m.sc":
         return('pg');
        break;
    case "m.sc.tech":
         return('pg');
        break;
    case "m.tech":
         return('pg');
        break;
    case "mba":
         return('pg');
        break;

    case "prep":
         return('ug');
        break;
    case "jrf":
         return('pg');
        break;
    default:
        echo "";
        break;
}
 



 
  }


  function check_availability($sy,$sess,$deptid,$courseid,$branchid,$sem,$sub_code){
      $sql = "SELECT * FROM cbcs_subject_offered WHERE session_year=?
AND SESSION=? AND dept_id=? AND course_id=? AND branch_id=?
AND semester=? AND sub_code=?";


      $query = $this->db->query($sql,array($sy,$sess,$deptid,$courseid,$branchid,$sem,$sub_code));


      if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }
  
  function check_availability_old($sy,$sess,$deptid,$courseid,$branchid,$sem,$sub_code){
      $sql = "SELECT * FROM old_subject_offered WHERE session_year=?
AND SESSION=? AND dept_id=? AND course_id=? AND branch_id=?
AND semester=? AND sub_code=? ";


      $query = $this->db->query($sql,array($sy,$sess,$deptid,$courseid,$branchid,$sem,$sub_code));


      if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }
  
  //======================MINOR==================
  
    function check_availability_minor($sy,$sess,$deptid,$sub_code){
      $sql = "SELECT * FROM cbcs_minor_course_offered WHERE session_year=?
AND SESSION=? AND dept_id=? AND sub_code=? ";


      $query = $this->db->query($sql,array($sy,$sess,$deptid,$sub_code));


      if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }
  
  function insert_minor($data)
  {
	 
    if($this->db->insert('cbcs_minor_course_offered',$data))
       return $this->db->insert_id();
    else
      return FALSE;
  }

  //===============================================Dual Degree============================
  
  function check_availability_dualdegree($sy,$sess,$deptid,$courseid,$branchid,$cat_type,$sub_code){
      $sql = "SELECT * FROM cbcs_dualdegree_course_offered WHERE session_year=?
AND SESSION=? AND dept_id=? and course_id=? and branch_id=? and category_type=? AND sub_code=? ";


      $query = $this->db->query($sql,array($sy,$sess,$deptid,$courseid,$branchid,$cat_type,$sub_code));


      if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }

  function insert_dualdegree($data)
  {
   
    if($this->db->insert('cbcs_dualdegree_course_offered',$data))
       return $this->db->insert_id();
    else
      return FALSE;
  }
  
  //===============================================Double Major============================
  
  function check_availability_doublemajor($sy,$sess,$deptid,$courseid,$branchid,$cat_type,$sub_code){
      $sql = "SELECT * FROM cbcs_doublemajor_course_offered WHERE session_year=?
AND SESSION=? AND dept_id=? and course_id=? and branch_id=? and category_type=? AND sub_code=? ";


      $query = $this->db->query($sql,array($sy,$sess,$deptid,$courseid,$branchid,$cat_type,$sub_code));


      if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }


  }

  function insert_doublemajor($data)
  {
   
    if($this->db->insert('cbcs_doublemajor_course_offered',$data))
       return $this->db->insert_id();
    else
      return FALSE;
  }
  
  function get_offered_minor_list(){
	   $sql = "SELECT * from cbcs_minor_course_offered ORDER BY dept_id";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() > 0) {
         return $query->result();
    } else {
        return false;
    }
  }
  
  function get_offered_dualdegree_list(){
	   $sql = "SELECT * from cbcs_dualdegree_course_offered ORDER BY dept_id";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() > 0) {
         return $query->result();
    } else {
        return false;
    }
  }
  
  function get_offered_double_major_list(){
	   $sql = "SELECT * from cbcs_doublemajor_course_offered ORDER BY dept_id";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() > 0) {
         return $query->result();
    } else {
        return false;
    }
  }




}

?>