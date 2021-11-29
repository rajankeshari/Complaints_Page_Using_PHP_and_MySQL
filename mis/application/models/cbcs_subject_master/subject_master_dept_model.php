<?php

class Subject_master_dept_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // course master by @bhijeet start
    function get_cm_dept(){
      $sql="select * from cbcs_set_cm_dept a where a.`status`='1' order by a.id desc";
      $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
          return $query->last_row()->dept;
      else
          return FALSE;
    }
       function save_cm_dept($data){

       $dept=$data['dept'];
       $from_date=$data['from_date'];
       $to_date=$data['to_date'];
       $status="1";
       $sql="select * from cbcs_set_cm_dept a where a.dept=? and a.`status`='1'";
       $query = $this->db->query($sql,array($dept,$from_date));
       //echo $this->db->last_query();exit;
       if($query->num_rows() > 0){
         return false;
       }else{
         $update=array("status"=>0);
         $this->db->where('status', '1');
         $this->db->update('cbcs_set_cm_dept', $update);
         if($this->db->insert('cbcs_set_cm_dept', $data)){
             return true;
         }else{
           return false;
         }
       }

       }

       function getDepartmentSetList(){
         $sql="select * from cbcs_set_cm_dept a order by a.id desc";
         $query = $this->db->query($sql);
         if ($this->db->affected_rows() > 0) {
          return $query->result();
         } else {
          return false;
         }
       }


       function getDepartments(){
         $sql="select * from cbcs_departments a where a.`type`='academic' and a.`status`='1'";
         $query = $this->db->query($sql);
         if ($this->db->affected_rows() > 0) {
          return $query->result();
         } else {
          return false;
         }
       }

         // course master by @bhijeet end
	//online course start @abhijeet

  function updateReg($updateData,$id){
    $this->db->where('id', $id);
    $this->db->update('cbcs_online_course_reg', $updateData);
  }

function insert_Approved_OnlineCourse($approvedData){
  $this->db->insert('cbcs_stu_course', $approvedData);

  //echo $this->db->last_query(); exit;
  if($this->db->affected_rows() != 1){
    return false;
  }else{
    return true;
  }
}


function online_course_reg_stu($session,$session_year,$dept_id){
  $sql = "select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name from cbcs_online_course_reg a
          inner join user_details b on a.admn_no=b.id
          where a.`session`='$session' and a.session_year='$session_year' and a.offering_dept='$dept_id' order by a.`status` asc";
$query = $this->db->query($sql);
if ($this->db->affected_rows() > 0) {
 return $query->result();
} else {
 return false;
}
}

function delete_online_Course($id) {
   $this->db->where('sub_offered_id', $id);
   $this->db->delete('cbcs_subject_offered_desc');
   if ($this->db->affected_rows() > 0) {

     $this->db->where('id', $id);
     $this->db->delete('cbcs_subject_offered');

     return true;
   } else {
    return false;
   }

}


function getOnlineCourse($id){
  $sql = "select a.*,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as ft_name from cbcs_subject_offered a
inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
inner join user_details c on b.emp_no=c.id
where a.dept_id='$id' and a.sub_type='online' order by a.id desc";
$query = $this->db->query($sql);
if ($this->db->affected_rows() > 0) {
 return $query->result();
} else {
 return false;
}
}

function insertOnlineCourse($sub_data,$instructors){
  $this->db->insert('cbcs_subject_offered', $sub_data);
  $last_id=$this->db->insert_id();
  $sub_disp=array(
    "sub_offered_id"=>$last_id,
    "part"=>"P1",
    "emp_no"=>$instructors,
    "coordinator"=>"1",
    "sub_id"=>$sub_data['sub_code'],
    "section"=>""
  );
  $this->db->insert('cbcs_subject_offered_desc', $sub_disp);
    if($this->db->affected_rows() != 1){
      return false;
    }else{
      return true;
    }

}

function getFtList($id){
  $sql = "select b.id,concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name) as ft_name from user_details a
inner join users b on a.id=b.id
inner join emp_basic_details c on b.id=c.emp_no
where a.dept_id='$id' and b.auth_id='emp' and c.auth_id='ft' and b.remark='' order by a.first_name asc";
$query = $this->db->query($sql);
if ($this->db->affected_rows() > 0) {
 return $query->result();
} else {
 return false;
}
}

//online course end

        function get_sub_master_all(){
            $sql = "select * from cbcs_course_master order by sub_name";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }


	function get_sub_master_deptwise($id){
            $sql = "select * from cbcs_course_master where dept_id='$id' order by sub_name";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->result();
        } else {
           return false;
        }

    }


        function sub_master_insert($data) {
        if ($this->db->insert('cbcs_course_master', $data))
        return $this->db->insert_id();
            //return TRUE;
        else
            return FALSE;
    }
    function insert_backup($id,$action){

        $sql = "insert into cbcs_course_master_backup select * from cbcs_course_master where id=?";
        $query = $this->db->query($sql,array($id));

        $sql = "update cbcs_course_master_backup set action='".$action."' where id=".$id;
        $query = $this->db->query($sql);

        if($action=='modify'){
            $sql = "update cbcs_course_master set action='".$action."' where id=".$id;
            $query = $this->db->query($sql);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }


    }
     function delete_rowid($id) {
        $this->db->where('id', $id);
        $this->db->delete('cbcs_course_master');
    }

    function sub_master_update($data,$con)
    {
        $con1['id'] = $con;
         if($this->db->update('cbcs_course_master',$data,$con1))
         {
                    return true;
         }
            return false;

    }

    function get_sub_master_lastrow($id){

        $sql = "select * from cbcs_course_master where id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
           return $query->row();
        } else {
           return false;
        }

    }

    function delete_record_all($syear,$sess,$cid,$bid){

                    $sql="delete from cbcs_course_master where session_year=? and session=? and course_id=? and branch_id=?";

            $query = $this->db->query($sql,array($syear,$sess,$cid,$bid));

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

    function insert_subject_master($data)
    {


        $this->db->insert_batch('cbcs_course_master',$data['values']);
        if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }

    }

    function check_duplicate_subject($dept,$subcode){
        $sql="SELECT a. *
FROM cbcs_course_master a
WHERE a.sub_code='$subcode' AND a.dept_id='$dept'";
$result=$this->db->query($sql);
$rowcount = $result->num_rows();
return $rowcount;
    }

    function check_duplicate_subject_update($dept,$subcode,$con){
        $sql="SELECT a. *
FROM cbcs_course_master a
WHERE a.sub_code='$subcode' AND a.dept_id='$dept' AND a.id!='$con'";
$result=$this->db->query($sql);
$rowcount = $result->num_rows();
return $rowcount;
    }

}

?>
