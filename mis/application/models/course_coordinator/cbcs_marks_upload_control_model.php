<?php

class Cbcs_marks_upload_control_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function get_sub_details($session_year,$session,$sub_code){
      $sql="select p.*,q.dean_ac_status from (select x.* from (select a.id,a.sub_code,b.sub_name,b.sub_type,a.exam_type,a.co_emp_id,a.offered_to_name,concat('c',b.id) as sub_offere_id,concat('c',a.sub_offered_id) as sub_offered_id ,b.dept_id,b.course_id,b.branch_id from cbcs_assign_course_coordinator a
inner join cbcs_subject_offered b on a.sub_code=b.sub_code and a.`session`=b.`session` and a.session_year=b.session_year
where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year'
union
select a.id,a.sub_code,b.sub_name,b.sub_type,a.exam_type,a.co_emp_id,a.offered_to_name,concat('c',b.id) as sub_offered_id,concat('o',a.sub_offered_id) as sub_offered_id,b.dept_id,b.course_id,b.branch_id from cbcs_assign_course_coordinator a
inner join old_subject_offered b on a.sub_code=b.sub_code and a.`session`=b.`session` and a.session_year=b.session_year
where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' ) x group by x.sub_offered_id) p
left join cbcs_marks_send_to_coordinator q on p.sub_code=q.sub_code and (case when p.course_id='comm' and p.branch_id='comm' then q.sub_offered_id=p.sub_offered_id else 1=1 end ) and q.`status` <> 2 group by p.sub_offered_id

";
$query = $this->db->query($sql);
if ($this->db->affected_rows() > 0) {
  //echo  $this->db->last_query();
    return $query->result();
} else {
    return false;
}
    }


    function open_for_regrading($session_year,$session,$sub_code,$sub_offerd_id,$course_id,$branch_id,$sub_type,$co_emp_id,$approve_letter){
      $insertdata=array(
        "session_year"=>$session_year,
        "session"=>$session,
        "sub_code"=>$sub_code,
        "sub_offered_id"=>$sub_offerd_id,
        "co_emp_id"=>$co_emp_id,
        "course_id"=>$course_id,
        "branch_id"=>$branch_id,
        "approve_letter"=>$approve_letter,
        "open_by"=>$this->session->userdata("id")

      );


        if($course_id=='comm' && $branch_id=='comm'){
          $extraClouse="and sub_offered_id='$sub_offerd_id'";
        }
        $sql="update cbcs_marks_send_to_coordinator set dean_ac_status='0' where sub_code='$sub_code' and session='$session' and session_year='$session_year' $extraClouse ";
        $query = $this->db->query($sql);
//echo  $this->db->last_query();
        if($this->db->_error_message()){
          return "Unbable to open course $sub_code for re-grading";
        }else{
          $this->db->insert('cbcs_grade_reopen_log', $insertdata);
          return "Course $sub_code open successfully for re-grading.";
        }

    }
function get_sub_details_for_marks($session_year,$session,$sub_code){
  $sql="(select a.* ,GROUP_CONCAT(CONCAT_WS('|',b.category,b.wtg,b.pk,b.marks_upload_status)SEPARATOR', ') as marks_dist,CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) as ins_name from cbcs_marks_dist a
inner join cbcs_marks_dist_child b on a.id=b.id
inner join user_details q on a.emp_no=q.id
where a.session_year='$session_year' and a.`session`='$session' and a.sub_code='$sub_code'
group by a.id)";
  $query = $this->db->query($sql);
  if ($this->db->affected_rows() > 0) {
    //echo  $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}
function sendToExamStatus($session_year,$session,$sub_code){
  $sql="select count(*) as sendToExam from cbcs_marks_send_to_coordinator a
where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year' and a.dean_ac_status='0' and a.`status` !=2 group by a.sub_code";
  $query = $this->db->query($sql);
  if ($this->db->affected_rows() > 0) {
//    echo  $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}

function open_for_re_marksUpload($data,$fileData,$session,$session_year){
  $datas=explode("|",$data);
  $pk=$datas[0];
  $sub_code=$datas[1];
  $course_id=$datas[2];
  $branch_id=$datas[3];

  $uploadDir="./assets/cbcs/reopenRequest/";
  $data=date("m-d-Y H:i:s");
    if(!empty($_FILES["file"]["name"])){
      $fileName = basename($_FILES["file"]["name"]);
      $targetFilePath = $uploadDir."sub_code_".$sub_code."_emp_".$req_by."_".$data . $fileName;
      $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
      $allowTypes = array('pdf');
      if(in_array($fileType, $allowTypes)){
          if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            $approve_letter=substr($targetFilePath,1);
           $sqlselect="select x.*,z.co_emp_id from(select concat('c',a.id) as id,a.session_year,a.`session`,a.sub_code,a.dept_id,a.course_id,a.sub_type,b.sub_offered_id,b.emp_no as ins_id from cbcs_subject_offered a
           inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id
           where a.sub_code='$sub_code' and a.course_id='$course_id' and a.branch_id ='$branch_id' and a.session_year='$session_year' and a.`session`='$session' and b.coordinator=1
           union
           select concat('o',a.id) as id,a.session_year,a.`session`,a.sub_code,a.dept_id,a.course_id,a.sub_type,b.sub_offered_id,b.emp_no as ins_id from old_subject_offered a
           inner join old_subject_offered_desc b on a.id=b.sub_offered_id
           where a.sub_code='$sub_code' and a.course_id='$course_id' and a.branch_id ='$branch_id' and a.session_year='$session_year' and a.`session`='$session'
           and b.coordinator=1) x
           inner join cbcs_assign_course_coordinator z on  x.sub_code=z.sub_code and x.session=z.`session` and x.session_year=z.session_year
           ";
               $queryselect = $this->db->query($sqlselect);
             /*  if ($this->db->affected_rows() > 0) {
             //    echo  $this->db->last_query();
                   return $query->result();
               } else {
                   return false;
               }
           */

             foreach ($queryselect->result() as $value) {
               // code...
               $sub_offered_id=$value->id;
               $ins_id=$value->ins_id;
               $co_id=$value->co_emp_id;
             }

             $insertdata=array(
               "session"=>$session,
               "session_year"=>$session_year,
               "sub_offered_id"=>$sub_offered_id,
               "sub_code"=>$sub_code,
               "course_id"=>$course_id,
               "branch_id"=>$branch_id,
               "ins_emp_id"=>$ins_id,
               "co_emp_id"=>$co_id,
               "marks_dist_open_id"=>$pk,
               "open_by"=>$this->session->userdata("id"),
               "approval_letter"=>$approve_letter
             );
             $sql="update cbcs_marks_dist_child set marks_upload_status='0' where pk='$pk' ";
             $query = $this->db->query($sql);
           //echo  $this->db->last_query();
             if($this->db->_error_message()){
               return "Somthing Went Worng.Please try Again.";
             }else{
               $sqls="update cbcs_marks_send_to_coordinator set dean_ac_status='0',status='0' where sub_code='$sub_code' and course='$course_id' and branch='$branch_id' and session='$session' and session_year='$session_year'";
               $querys = $this->db->query($sqls);
               $examsql="update cbcs_marks_send_to_coordinator set dean_ac_status='0' where sub_code='$sub_code' and session='$session' and session_year='$session_year'";
               $examquery = $this->db->query($examsql);
               $this->db->insert('cbcs_marks_reopen_log', $insertdata);
               return "Course $sub_code open successfully for re-marks upload.";
             }
          }
      }
    }else{
      return "Unable to Upload Approval Letter.";
    }


}


function get_reopen_log(){
  $sql="select z.*,concat_ws(' ',q.first_name,q.middle_name,q.last_name) as co_emp_name from (select a.*,b.category,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as ins_name from cbcs_marks_reopen_log a
inner join cbcs_marks_dist_child b on a.marks_dist_open_id=b.pk
left join user_details c on c.id=a.ins_emp_id) z
left join user_details q on z.co_emp_id=q.id order by z.id desc
";
  $query = $this->db->query($sql);
 if ($this->db->affected_rows() > 0) {
//    echo  $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}
function reopen_grade_log(){
  $sql="select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as co_name from cbcs_grade_reopen_log a
left join user_details b on a.co_emp_id=b.id
order by a.id desc
";
  $query = $this->db->query($sql);
 if ($this->db->affected_rows() > 0) {
//    echo  $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}
  }
  ?>
