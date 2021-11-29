<?php

class Cbcs_makeup_exam_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

// marks enrty starts

  function marks_entry_history(){
    $sql = "select a.approval_letter,b.*,concat_ws(' ',f.first_name,f.middle_name,f.last_name) as name,group_concat(concat_ws(' - ',c.category_name,c.marks)) as marks,e.grade from cbcs_marks_enrty_log a
            inner join cbcs_marks_upload_dean_acad b
            on a.`session`=b.`session` and a.session_year=b.session_year and a.sub_code=b.subject_code and a.sub_offered_id=b.sub_offered_id
            inner join cbcs_marks_upload_description_dean_acad c
            on b.id=c.marks_id and b.admn_no=c.admn_id
            inner join cbcs_marks_master d on b.session_year=d.session_year and b.`session`=d.`session` and b.subject_code=d.subject_id and b.sub_offered_id=d.sub_map_id
            inner join cbcs_marks_subject_description e on d.id=e.marks_master_id and b.admn_no=e.admn_no
            inner join user_details f on b.admn_no=f.id
            where a.submit_to_exam_status='1' group by a.id order by a.id desc";
    $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
    if ($query->num_rows() > 0)
        return $query->result();
    else
        return 0;
  }

  function submit_marks_to_entry_marks_master($data,$correction_log_id,$admn_no){
    $updateClouse=array(
      "marks_master_id"=>$data['marks_master_id'],
      "admn_no"=>$data['admn_no'],
    );
    $this->db->select('*');
    $this->db->from('cbcs_marks_subject_description');
    $this->db->where($updateClouse);
    $cnt=$this->db->get()->result();
    foreach ($cnt as $cntdata) {
      // code...
    //  print_r($cntdata->total);
     $oldtotal=$cntdata->total;
      $oldgrade=$cntdata->grade;
    }

    $updateWhere=array(
      "correction_log_id"=>$correction_log_id,
      "admn_no"=>$admn_no
    );

    //echo "<pre>";print_r($updateWhere);
  //  exit;
    //$cnt=$this->db->last_query(); die();
    $count=count($cnt);
  //  echo "<pre>"; print_r($data); exit;
    if($count=="0"){
      $this->db->insert('cbcs_marks_subject_description', $data);
    //  $this->db->last_query(); die();
    if ($this->db->affected_rows() > 0) {
      $sql="update cbcs_marks_enrty_log set submit_to_exam_status='1' , update_status='1'
      where id='$correction_log_id'";
      $query = $this->db->query($sql);
		//echo $this->db->last_query(); die();
          return 1; /* "Marks Submitted Successfully."*/
    } else {
      return  0; /*"Unable to Submit Marks.Please try again."*/
  }
    }else{
      $this->db->where($updateClouse);
      $this->db->update('cbcs_marks_subject_description', $data);
      //echo $this->db->last_query(); die();
      if ($this->db->_error_message()) {
        return  0;/*"Someting Went Worng. Please try again.";*/
      } else {

        $sql="update cbcs_marks_enrty_log set submit_to_exam_status='1' , update_status='1'
        where id='$correction_log_id'";
      //  echo $sql;
        $query = $this->db->query($sql);

      return  1 ;/*"Marks Submitted Successfully.."*/
      }
    }
  }

public function countOrGetLastID_new_entry($data){
  $sql = "SELECT id from cbcs_marks_upload_dean_acad where admn_no='$data[admn_no]' and session_year='$data[session_year]' and session='$data[session]'
  and branch_id='$data[branch_id]' and course_id='$data[course_id]' and subject_code='$data[subject_code]'
  order by id";
  $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->last_row()->id;
  else
      return 0;
}
function marks_entry_log($logData){
  if($this->db->insert('cbcs_marks_enrty_log', $logData)){
  //  $this->db->last_query(); exit;
      return $this->db->insert_id();
  }else{
    return false;
  }
}

function save_cbcs_marks_entry_upload($dataMarksUpload){
    $this->db->insert('cbcs_marks_upload_dean_acad', $dataMarksUpload);
          if($this->db->affected_rows() != 1){
            // echo "<pre>";
            // echo"reg_form :".	$this->db->_error_message();

           }else{
             return $this->db->insert_id();
           }
      //     echo $this->db->last_query();

}

public function save_cbcs_marks_distribution_for_entry($data,$dt,$lastID_DA){


$marks_log_data=array(
  "marks_id"=>$lastID_DA,
  "category_name"=>$data['category_name'],
  "category_id"=>$data['category_id'],
  "marks_dist_child_id"=>$data['marks_dist_child_id'],
  "marks"=>$data['marks'],
  "admn_id"=>$data['admn_id'],
  "uploaded_by"=>$data['uploaded_by']
);
//echo "<pre>";  print_r($marks_log_data); exit;


  $userid=$this->session->userdata("id");
  $sql = "SELECT * from cbcs_marks_upload_description where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
  and admn_id='$data[admn_id]'";
  $query = $this->db->query($sql);

  if($query->num_rows() == 0){
    //  echo "hello";
    //$data['marks'];
    if(!empty($data['marks']) || $data['marks']!=""){
  $this->db->insert('cbcs_marks_upload_description', $data);

        if($this->db->affected_rows() != 1){
        //    return "0";
         }else{
  //$this->db->insert('cbcs_marks_entry_dean_acad', $marks_log_data);



          //  return "1";
          // return $this->db->insert_id();
         }
       }else{

       }
      // return "5";
     }else{
       $sqlupdate="update cbcs_marks_upload_description set marks='$data[marks]' where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
       and admn_id='$data[admn_id]' ";
       $query = $this->db->query($sqlupdate);
         if($this->db->affected_rows() != 1){
        //   return "0";
         }else{
        //   return "1";
         }
     }

     // second entry for marks enrty
     $sqlbackups = "SELECT * from cbcs_marks_upload_description_dean_acad where marks_id='$marks_log_data[marks_id]' and category_name='$marks_log_data[category_name]' and category_id='$marks_log_data[category_id]' and marks_dist_child_id='$marks_log_data[marks_dist_child_id]'
     and admn_id='$data[admn_id]'";
     $querybackups = $this->db->query($sqlbackups);
  //   echo $querybackups;
     if($querybackups->num_rows() == 0){
       if(!empty($data['marks']) || $data['marks']!=""){
     $this->db->insert('cbcs_marks_upload_description_dean_acad', $marks_log_data);

           if($this->db->affected_rows() != 1){
               return "0";
            }else{
               return "1";

            }
          }else{

          }

     }else{

       $sqlupdatebackup="update cbcs_marks_upload_description_dean_acad set marks='$marks_log_data[marks]' where marks_id='$marks_log_data[marks_id]' and category_name='$marks_log_data[category_name]' and category_id='$marks_log_data[category_id]' and marks_dist_child_id='$marks_log_data[marks_dist_child_id]'
       and admn_id='$marks_log_data[admn_id]' ";
       $querybackups = $this->db->query($sqlupdatebackup);
         if($this->db->affected_rows() != 1){
           return "0";
         }else{
           return "1";
         }
     }


}

// marks entry ends


// acadmic Performance starts

  function acadmic_performance_details($dept,$course_id){
    $sql="select y.*,dp.name as dept_name from (select x.* from (select * from final_semwise_marks_foil_freezed a
where  a.course='$course_id' and a.course <> 'jrf' and a.course <> 'prep' and a.course <> 'minor'
order by a.admn_no,a.semester desc,a.actual_published_on desc limit 1000000000
) x group by x.admn_no) y
join reg_regular_form rg on rg.admn_no=y.admn_no and rg.session_year='2019-2020' and rg.`session`='Monsoon' and rg.hod_status='1' and rg.acad_status='1'
LEFT JOIN cbcs_departments dp on y.dept=dp.id";
    $query = $this->db->query($sql);
    //echo  $this->db->last_query(); die();

      if ($this->db->affected_rows() > 0) {
           return $query->result();
       } else {
           return false;
       }
  }

  function getCourse(){
    $sql="select * from cbcs_courses a where a.`status`='1'";
    $query = $this->db->query($sql);
    //echo  $this->db->last_query(); die();

      if ($this->db->affected_rows() > 0) {
           return $query->result();
       } else {
           return false;
       }
  }
  function getDept(){
    $sql="select * from cbcs_departments a where a.`type`='academic' and a.`status`='1'";
    $query = $this->db->query($sql);
    //echo  $this->db->last_query(); die();

      if ($this->db->affected_rows() > 0) {
           return $query->result();
       } else {
           return false;
       }
  }


// acadmic Performance end

    // marks correction start

    function marks_correction_history(){
      $sql="select a.correction_log_id,a.admn_no,a.form_id,a.session_year,a.`session`,a.sub_code,a.sub_offered_id,a.course_id,a.branch_id,
a.marks_upload_id,a.marks_upload_dis_id,a.dist_name,a.dist_id,a.old_marks,a.corrected_marks,a.old_total,a.new_total,a.new_grade,a.old_grade,a.corrected_by,a.updated_at,b.id as logid ,b.submit_to_exam_status as st from cbcs_marks_correction_backup a
            inner join cbcs_marks_correction_log b on a.correction_log_id=b.id and a.admn_no=b.admn_no and a.session_year=b.session_year and a.`session`=b.`session`
            and a.sub_code=b.sub_code order by b.submit_to_exam_status,b.update_status,b.id desc";
      $query = $this->db->query($sql);
      //echo  $this->db->last_query(); die();

        if ($this->db->affected_rows() > 0) {
             return $query->result();
         } else {
             return false;
         }
    }



    function coorected_by_name($corrected_by){
      $sql="select concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name) as name from user_details a where a.id='$corrected_by'";
      $query = $this->db->query($sql);
      //echo  $this->db->last_query(); die();

        if ($this->db->affected_rows() > 0) {
             return $query->result();
         } else {
             return false;
         }
    }

    function getBackupdataforDownload($session,$session_year,$sub_code,$sub_offered_id,$admn_no,$form_id){
      $sql="(select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,c.sub_name from cbcs_marks_correction_backup a
inner join user_details b on a.admn_no=b.id
inner join cbcs_subject_offered c on a.sub_code=c.sub_code
where a.sub_code='$sub_code'
and a.sub_offered_id='$sub_offered_id' and a.admn_no='$admn_no' and a.form_id='$form_id' and a.session_year='$session_year' and a.`session`='$session'
order by id desc limit 1)
union
(select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name,c.sub_name from cbcs_marks_correction_backup a
inner join user_details b on a.admn_no=b.id
inner join old_subject_offered c on a.sub_code=c.sub_code
where a.sub_code='$sub_code'
and a.sub_offered_id='$sub_offered_id' and a.admn_no='$admn_no' and a.form_id='$form_id' and a.session_year='$session_year' and a.`session`='$session'
order by id desc limit 1)";
      $query = $this->db->query($sql);
      //echo  $this->db->last_query(); die();

        if ($this->db->affected_rows() > 0) {
             return $query->result();
         } else {
             return false;
         }
    }


    function final_submitted_marks_list_for_correction($session,$sessionyear,$branch_id,$course_id,$sub_code,$admn_no,$category_pk){
  $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  where  a.session_year='$sessionyear' and a.`session`='$session'
  and a.branch_id='$branch_id' and a.course_id='$course_id'
  and a.subject_code='$sub_code' and admn_no='$admn_no' and b.marks_dist_child_id = '$category_pk' group by a.admn_no";
  $query = $this->db->query($sql);
  //echo  $this->db->last_query(); die();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}

    public function save_cbcs_marks_distribution_for_correction($data,$dt){
      $userid=$this->session->userdata("id");
      $sql = "SELECT * from cbcs_marks_upload_description where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
      and admn_id='$data[admn_id]'";
      $query = $this->db->query($sql);
      $res=$query->result();
      $backupData=array(
        "correction_log_id"=>$dt['log_id'],
        "admn_no"=>$dt['admn_no'],
        "form_id"=>$dt['form_id'],
        "session_year"=>$dt['session_year'],
        "session"=>$dt['session'],
        "sub_code"=>$dt['subject_code'],
        "sub_offered_id"=>$dt['sub_offered_id'],
        "course_id"=>$dt['course_id'],
        "branch_id"=>$dt['branch_id'],
        "marks_upload_id"=>$res[0]->marks_id,
        "marks_upload_dis_id"=>$res[0]->id,
        "dist_name"=>$res[0]->category_name,
        "dist_id"=>$res[0]->category_id,
        "old_marks"=>$res[0]->marks,
        "corrected_marks"=>$data['marks'],
        "corrected_by"=>$userid,
      );

  //  echo"<pre>";  print_r($backupData); exit;
      if($query->num_rows() == 0){
        //  echo "hello";
    /*    $data['marks'];
        if(!empty($data['marks']) || $data['marks']!=""){
    //  $this->db->insert('cbcs_marks_upload_description', $data);

            if($this->db->affected_rows() != 1){
              // echo "<pre>";
              // echo"reg_form :".	$this->db->_error_message();

                return "0";

             }else{
                return "1";
              // return $this->db->insert_id();
             }
           }else{
               return "0";
           } */
           return "5";
         }else{
           $sqlbackuplog = "SELECT * from cbcs_marks_correction_backup where sub_offered_id='$backupData[sub_offered_id]' and dist_id='$backupData[dist_id]' and marks_upload_dis_id='$backupData[marks_upload_dis_id]' and marks_upload_id='$backupData[marks_upload_id]'
           and admn_no='$backupData[admn_no]' and form_id='$backupData[form_id]' and correction_log_id='$backupData[correction_log_id]'";
           $querylog = $this->db->query($sqlbackuplog);
          // echo $sqlbackuplog; exit;
             if($querylog->num_rows() == 0){
            //   echo "<br>hello <br>";
               $this->db->insert('cbcs_marks_correction_backup', $backupData);
             }else{
               $sqlbackupupdate="update cbcs_marks_correction_backup set corrected_marks='$data[marks]' , corrected_by='$userid' where sub_offered_id='$backupData[sub_offered_id]' and dist_id='$backupData[dist_id]' and marks_upload_dis_id='$backupData[marks_upload_dis_id]' and marks_upload_id='$backupData[marks_upload_id]'
               and admn_no='$backupData[admn_no]' and form_id='$backupData[form_id]' and correction_log_id='$backupData[correction_log_id]'";
                $querybackupupdate = $this->db->query($sqlbackupupdate);
             }

           $sqlupdate="update cbcs_marks_upload_description set marks='$data[marks]' where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
           and admn_id='$data[admn_id]' ";
           $query = $this->db->query($sqlupdate);
             if($this->db->_error_message()){
               return "0";
             }else{

               $update_log="update cbcs_marks_correction_log set update_status='1' where id='$backupData[correction_log_id]'";
                $query_log = $this->db->query($update_log);
               return "1";
             }
         }

    }

    public function getStudentListwithMarks($data){
      $session_year=  $data['primary']['session_year'];
      $session=  $data['primary']['session'];
      $branch_id=  $data['primary']['branch_id'];
      $course_id=  $data['primary']['course_id'];
      $dept_id=  $data['primary']['dept_id'];
      $sub_code=  $data['primary']['sub_code'];
      $semester=  $data['primary']['semester'];
      $emp_no=  $data['primary']['emp_no'];
      $section=  $data['primary']['section'];
      $group=  $data['primary']['group'];
      $dist_id=  $data['primary']['dist_id'];
      $sub_offerd_id=  $data['primary']['sub_offerd_id'];
      $admn_no=  $data['primary']['admn_no'];
      $form_id=  $data['primary']['form_id'];

      $modular_exam_type=  $data['primary']['modular_exam_type'];
      $sub_type=  $data['primary']['sub_type'];

      $comm="";
      if($branch_id=='comm' && $course_id=='comm'){
        $comm="and b.section='$section'";
        $section="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
      }
      if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
        $innerjoin="INNER JOIN cbcs_modular_paper_details d on a.admn_no=d.admn_no and a.form_id=d.form_id";
        $condition="and d.$modular_exam_type in('$sub_code')  group by a.form_id";
      }
$sql = "select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from cbcs_stu_course a
INNER JOIN user_details c on UPPER(a.admn_no)=UPPER(c.id)
LEFT JOIN cbcs_marks_upload mu on UPPER(a.admn_no)=(mu.admn_no) and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$section

$innerjoin
where a.admn_no='$admn_no' and a.form_id='$form_id' and a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm $condition
union
select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from old_stu_course a
INNER JOIN user_details c on UPPER(a.admn_no)=UPPER(c.id)
LEFT JOIN cbcs_marks_upload mu on UPPER(a.admn_no)=UPPER(mu.admn_no) and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$section

$innerjoin
where a.admn_no='$admn_no' and a.form_id='$form_id' and  a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm $condition";


        $query = $this->db->query($sql);
        //echo $this->db->last_query(); //die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
      }

    function getSubjectPlanList($data){
      $session_year=  $data['session_year'];
      $session=  $data['session'];
     $branch_id=  $data['branch_id'];
      $jrfstatus=  $data['jrfstatus'];
      if($jrfstatus=="1"){
        $course_id=  "jrf";
      }else{
        $course_id=  $data['course_id'];
      }
//echo"".$course_id;
      $dept_id=  $data['dept_id'];
      $sub_code=  $data['sub_code'];
      $semester=  $data['semester'];
      $emp_no=  $data['emp_no'];
      $sub_group=  $data['sub_group'];
      $sub_type=  $data['sub_type'];
      $section=  $data['section'];
        if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
                $addgroup="AND a.group='$sub_group'";
                $joinGroup="AND a.group=c.sub_group";
              }
              if($branch_id=='comm' && $course_id=='comm'){
                $exsection="and d.section= '$section'";

                $extraSec="and a.section=d.section";

              }else{
                $exsection="";
                $extraSec="";
              }
          //    echo "<pre>";print_r($data['primary']); exit;
            $sql="
            (SELECT a.*,b.*,concat('c',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN cbcs_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code $joinGroup
            inner join cbcs_subject_offered_desc d on c.id=d.sub_offered_id $extraSec
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id'  AND a.sub_code='$sub_code' AND a.semester='$semester'  $exsection $addgroup
            group by b.id,b.pk ORDER BY b.category ASC)

            union

            (SELECT a.*,b.*,concat('o',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN old_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code $joinGroup
            inner join old_subject_offered_desc d on c.id=d.sub_offered_id $extraSec
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.sub_code='$sub_code' AND a.semester='$semester'  $exsection $addgroup
            group by b.id,b.pk ORDER BY b.category ASC)
            ";
        $query = $this->db->query($sql);
      //  echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }


    function marks_correction_log($logData){
      if($this->db->insert('cbcs_marks_correction_log', $logData)){
          return $this->db->insert_id();
      }else{
        return false;
      }
    }
    function stu_subject_list_for_correction($session_year,$session,$admn_no){
      $sql = "select a.*,concat('c',a.sub_offered_id) as sub_id,c.total,d.course_id,d.branch_id,d.semester,d.sub_type,d.sub_group,d.id as offer_id,e.section from cbcs_stu_course a
      inner join cbcs_subject_offered d on a.sub_offered_id=d.id and a.subject_code=d.sub_code and a.course=d.course_id and a.branch=d.branch_id
      LEFT join cbcs_marks_master b on a.subject_code=b.subject_id and a.sub_offered_id=SUBSTRING(b.sub_map_id,2) and a.`session`=b.`session` and a.session_year=a.session_year
      left join cbcs_marks_subject_description c on b.id=c.marks_master_id and a.admn_no=c.admn_no
      LEFT JOIN stu_section_data e on a.admn_no=e.admn_no and a.session_year=e.session_year
      where a.session_year='$session_year' and a.`session`='$session' and a.admn_no='$admn_no'
      union
      select a.*,concat('o',a.sub_offered_id) as sub_id,c.total,d.course_id,d.branch_id,d.semester,d.sub_type,d.sub_group,d.id as offer_id,e.section from old_stu_course a
      inner join old_subject_offered d on a.sub_offered_id=d.id and a.subject_code=d.sub_code and a.course=d.course_id and a.branch=d.branch_id
      LEFT join cbcs_marks_master b on a.subject_code=b.subject_id and a.sub_offered_id=SUBSTRING(b.sub_map_id,2) and a.`session`=b.`session` and a.session_year=a.session_year
      left join cbcs_marks_subject_description c on b.id=c.marks_master_id and a.admn_no=c.admn_no
      LEFT JOIN stu_section_data e on a.admn_no=e.admn_no and a.session_year=e.session_year
      where a.session_year='$session_year' and a.`session`='$session' and a.admn_no='$admn_no'";

      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
  //     echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }

    // marks correction end

    public function countOrGetLastID($data){
      $sql = "SELECT id from cbcs_marks_upload where admn_no='$data[admn_no]' and session_year='$data[session_year]' and session='$data[session]'
      and branch_id='$data[branch_id]' and course_id='$data[course_id]' and subject_code='$data[subject_code]'
      order by id";
      $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
          return $query->last_row()->id;
      else
          return 0;
    }
    public function save_cbcs_marks_upload($data){
      $this->db->insert('cbcs_marks_upload', $data);
            if($this->db->affected_rows() != 1){
              // echo "<pre>";
              // echo"reg_form :".	$this->db->_error_message();

             }else{
               return $this->db->insert_id();
             }
        //     echo $this->db->last_query();
    }
    public function save_cbcs_marks_distribution($data,$dt,$markup_exam_data){

               $sqls = "SELECT id from cbcs_markup_marks_upload where admn_no='$markup_exam_data[admn_no]' and session_year='$markup_exam_data[session_year]' and session='$markup_exam_data[session]'
               and branch_id='$markup_exam_data[branch_id]' and course_id='$markup_exam_data[course_id]' and sub_code='$markup_exam_data[sub_code]' and dist_id='$markup_exam_data[dist_id]'
               order by id";

               $query = $this->db->query($sqls);
           //  echo $this->db->last_query(); die();
               if ($query->num_rows() > 0){

                 $sqlupdate="update cbcs_markup_marks_upload set marks='$markup_exam_data[marks]' where form_id='$markup_exam_data[form_id]' and dist_name='$markup_exam_data[dist_name]' and dist_id='$markup_exam_data[dist_id]' and dist_category_id='$markup_exam_data[dist_category_id]'
                 and admn_no='$markup_exam_data[admn_no]' and sub_offered_id='$markup_exam_data[sub_offered_id]'  and session_year='$markup_exam_data[session_year]' and session='$markup_exam_data[session]'";

                 $query = $this->db->query($sqlupdate);

                   if($this->db->affected_rows() != 1){

                   }else{

                   }
               }else{
                    $this->db->insert('cbcs_markup_marks_upload', $markup_exam_data);
                    if($this->db->affected_rows() != 1){

                     }else{

                     }


                  }


      $sql = "SELECT * from cbcs_marks_upload_description where marks_id='$data[marks_id]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
      and admn_id='$data[admn_id]'";
      $query = $this->db->query($sql);

      if($query->num_rows() == 0){
        $data['marks'];
        if(!empty($data['marks']) || $data['marks']!=""){
      $this->db->insert('cbcs_marks_upload_description', $data);

            if($this->db->affected_rows() != 1){
              // echo "<pre>";
              // echo"reg_form :".	$this->db->_error_message();

                return "0";

             }else{
                return "1";
              // return $this->db->insert_id();
             }
           }else{
               return "0";
           }
         }else{
        /*   $sqlupdate="update cbcs_marks_upload_description set marks='$data[marks]' where marks_id='$data[marks_id]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
           and admn_id='$data[admn_id]' ";
           $query = $this->db->query($sqlupdate);
             if($this->db->affected_rows() != 1){
               return "0";
             }else{
               return "1";
             }  */
         }

    }

    function get_session_year()
    {
       $sql = "select * from mis_session_year order by session_year desc";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    function get_session()
    {
        $sql = "select * from mis_session";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }

function get_stu_section($admn_no,$session_year){
  $sql = "select * from stu_section_data a where a.admn_no='$admn_no' and a.session_year='$session_year'";

  $query = $this->db->query($sql);

  if ($this->db->affected_rows() >= 0) {
     $this->db->last_query();
      return $query->result();
  } else {
      return false;
  }
}
    function stu_subject_list($session_year,$session,$admn_no){
      $sql = "select a.*,c.total from cbcs_stu_course a
      LEFT join cbcs_marks_master b on a.subject_code=b.subject_id and a.sub_offered_id=SUBSTRING(b.sub_map_id,2) and a.`session`=b.`session` and a.session_year=a.session_year
      left join cbcs_marks_subject_description c on b.id=c.marks_master_id and a.admn_no=c.admn_no
      where a.session_year='$session_year' and a.`session`='$session' and a.admn_no='$admn_no'
      union
      select a.*,c.total from old_stu_course a
      LEFT join cbcs_marks_master b on a.subject_code=b.subject_id and a.sub_offered_id=SUBSTRING(b.sub_map_id,2) and a.`session`=b.`session` and a.session_year=a.session_year
      left join cbcs_marks_subject_description c on b.id=c.marks_master_id and a.admn_no=c.admn_no
      where a.session_year='$session_year' and a.`session`='$session' and a.admn_no='$admn_no'";

      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
  //     echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }

    function stu_info($session_year,$session,$admn_no){
      $sql = " select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as stu_name from reg_regular_form a
  inner join user_details b on a.admn_no=b.id
  where a.session_year='$session_year' and a.`session`= '$session' and a.admn_no='$admn_no'
 ";

      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //   echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }
    }
    function get_subject_data($session_year,$session,$sub_code,$sub_id,$semester,$course,$branch,$section){

      if($course=='comm' && $branch='comm'){
        $section="and b.section='$section'";
      }else{
        $section="";
      }

      $sql="
      (select a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_name,a.sub_code,a.sub_type,a.sub_category,a.sub_group,concat('c',a.id) as sub_offered_id,b.section,b.emp_no,b.coordinator from cbcs_subject_offered a
      inner join cbcs_subject_offered_desc b on a.id=b.sub_offered_id and a.sub_code=b.sub_id
      where a.sub_code='$sub_code' and a.id='$sub_id' and a.session_year='$session_year' and a.`session`='$session'
      and b.coordinator='1' $section)
      union
      (select a.id,a.session_year,a.`session`,a.dept_id,a.course_id,a.branch_id,a.semester,a.sub_name,a.sub_code,a.sub_type,a.sub_category,a.sub_group,concat('o',a.id) as sub_offered_id ,b.section,b.emp_no,b.coordinator from old_subject_offered a
      inner join old_subject_offered_desc b on a.id=b.sub_offered_id and a.sub_code=b.sub_id
      where a.sub_code='$sub_code' and a.id='$sub_id' and a.session_year='$session_year' and a.`session`='$session'
      and b.coordinator='1' $section)
      ";
    //  echo $sql; exit;
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //   echo  $this->db->last_query(); die();
          return $query->result();
      } else {
          return false;
      }

    }
  function  makeupExamData($data){
    $whereClouse=array(
      "admn_no"=>$data['admn_no'],
      "session"=>$data['session'],
      "session_yr"=>$data['session_yr'],
      "sub_code"=>$data['sub_code'],
      "sub_offered_id"=>$data['sub_offered_id']
    );
    $cnt= $this->db->where($whereClouse)->from("cbcs_makeup_exam_stu_list")->count_all_results();
    if($cnt=="0"){
      $this->db->insert('cbcs_makeup_exam_stu_list', $data);
      if($this->db->affected_rows() == 0){
      //  $this->session->set_flashdata('flashError', 'Unable to Save Data.Please try again !!');
      //  redirect("cbcs_makeup_exam/cbcs_makeup_exam/", 'refresh');

                    //    echo"cbcs_assign_course_coordinator :".	$this->db->_error_message();

                      }else{
                        return true;
                      }
    }
  //echo "cnt ".$cnt;
    //    echo "<pre>";print_r($whereClouse);
    }
    function get_makeup_stu_List($session_year,$session){
      $sql="
      select a.*,concat_ws(' ',b.first_name,b.middle_name,b.last_name) as ft_name,c.`status` from cbcs_makeup_exam_stu_list a
  inner join user_details b on a.faculty_id=b.id
  LEFT join cbcs_markup_marks_upload c on a.form_id=c.form_id and a.admn_no=c.admn_no and a.sub_code=c.sub_code and a.sub_offered_id=c.sub_offered_id
  where a.`session`='$session' and a.session_yr='$session_year'
";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //  echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }

    }

    function get_makeup_sub_List($session_year,$session,$emp_id){
      $sql="
      SELECT a.*,d.subject_name,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,c.form_id
    FROM cbcs_makeup_exam_stu_list a
    INNER JOIN user_details b ON a.admn_no=b.id
    inner join cbcs_stu_course d on a.admn_no=d.admn_no and a.sub_code=d.subject_code
    INNER JOIN reg_regular_form c ON a.admn_no=c.admn_no AND a.`session`=c.`session` AND a.session_yr=c.session_year
    WHERE a.`session`='$session' AND a.session_yr='$session_year' AND a.faculty_id='$emp_id'
    union
    SELECT a.*,d.subject_name ,CONCAT_WS(' ',b.first_name,b.middle_name,b.last_name) AS stu_name,c.form_id
    FROM cbcs_makeup_exam_stu_list a
    INNER JOIN user_details b ON a.admn_no=b.id
    inner join old_stu_course d on a.admn_no=d.admn_no and a.sub_code=d.subject_code
    INNER JOIN reg_regular_form c ON a.admn_no=c.admn_no AND a.`session`=c.`session` AND a.session_yr=c.session_year
    WHERE a.`session`='$session' AND a.session_yr='$session_year' AND a.faculty_id='$emp_id'
";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //  echo  $this->db->last_query();
          return $query->result();
      } else {
          return false;
      }

    }


    function get_stu_marks($data){
      $admn_no=$data['primary']['admn_no'];
      $session_year=$data['primary']['session_year'];
      $session=$data['primary']['session'];
      $course_id=$data['primary']['course_id'];
      $branch_id=$data['primary']['branch_id'];
      $sub_code=$data['primary']['sub_code'];
      $sub_offerd_id=$data['primary']['sub_offerd_id'];
      $sql="
      select * from cbcs_marks_upload a
  inner join cbcs_marks_upload_description b on a.id=b.marks_id  and a.admn_no=b.admn_id
  where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.`session`='$session' and a.session_year='$session_year' and a.admn_no='$admn_no'
  and a.branch_id='$branch_id' and a.course_id='$course_id'
  group by b.category_id
      ";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //  echo  $this->db->last_query(); die();
          return $query->result();
      } else {
          return false;
      }
    }
    function getSubjectPlanforview($sessionyear,$session,$sub_code){
    $sql = "SELECT b.category,b.pk,b.wtg
    FROM cbcs_marks_dist a
    INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
    WHERE a.session_year='$sessionyear' AND a.`session`='$session' AND a.sub_code='$sub_code' group by b.category order by b.category asc";
      $query = $this->db->query($sql);
      //  echo $this->db->last_query(); //die();
      if ($query->num_rows() > 0)
          return $query->result();
      else
          return 0;
    }

    function get_submitted_marks_list($session,$session_year,$sub_code,$subject_offer_id,$admn_no){
      $sql = "SELECT a.*, CONCAT_WS(',',GROUP_CONCAT(CONCAT_WS(',',b.category_name)),f.dist_name) AS category_name, CONCAT_WS(',',GROUP_CONCAT(CONCAT_WS(',',b.marks)),f.marks) AS marks,e.total,e.grade, CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS name
FROM cbcs_marks_upload a
INNER JOIN cbcs_marks_upload_description b ON a.id=b.marks_id
INNER JOIN user_details c ON a.admn_no=c.id
INNER JOIN cbcs_marks_master d ON a.subject_code=d.subject_id AND a.session_year=d.session_year AND a.session=d.session
INNER JOIN cbcs_marks_subject_description e ON d.id=e.marks_master_id AND a.admn_no=e.admn_no
inner join cbcs_markup_marks_upload f on a.admn_no=f.admn_no and f.form_id=a.form_id and a.subject_code=f.sub_code and a.sub_offered_id=f.sub_offered_id and a.session_year=f.session_year
and a.session=f.session
WHERE a.session_year='$session_year' AND a.session='$session' AND a.subject_code='$sub_code' AND a.sub_offered_id='$subject_offer_id' AND d.sub_map_id='$subject_offer_id'
and a.admn_no='$admn_no' and e.stu_status='M' and b.category_name not like 'end_%' and f.dist_name like 'end_%'
GROUP BY a.admn_no";
  // USE WHEN UPPER CODE NOT WORKS
/*$sql=" SELECT v.*, GROUP_CONCAT(v.category_name) as category_name,  group_concat(v.marks) as marks from
( select a.* ,b.category_name ,(case when b.category_name   LIKE '%end_%' then f.marks else b.marks end) as marks
,e.total,e.grade, CONCAT_WS(' ',c.first_name,c.middle_name,c.last_name) AS name
 from
( select  * from  cbcs_markup_marks_upload f  where  f.dist_name LIKE '%end_%'  and f.session_year='$session_year' AND
f.session='$session' AND f.sub_code='$sub_code' AND f.sub_offered_id='$subject_offer_id'  AND f.admn_no='$admn_no' )f
  join  cbcs_marks_upload  a ON a.admn_no=f.admn_no AND f.form_id=a.form_id AND a.subject_code=f.sub_code AND a.sub_offered_id=f.sub_offered_id
  AND a.session_year=f.session_year AND a.session=f.session
  INNER JOIN cbcs_marks_master d ON a.subject_code=d.subject_id AND a.session_year=d.session_year AND a.session=d.session
  INNER JOIN cbcs_marks_subject_description e ON d.id=e.marks_master_id AND a.admn_no=e.admn_no  AND e.stu_status='M'
  INNER JOIN user_details c ON a.admn_no=c.id
  left JOIN cbcs_marks_upload_description b ON a.id=b.marks_id -- AND b.category_name NOT LIKE '%end_%'
  ) v   group by v.admn_no
"; */

        $query = $this->db->query($sql);
      //   echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }


    function getInstructor($id){
      $sql = "select concat_ws(' ',a.first_name,a.middle_name,a.last_name) as emp_name from user_details a where a.id='$id'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
    }


    function getCoName($session_year,$session,$sub_code){
      $sql = "select concat_ws(' ',b.first_name,b.middle_name,b.last_name) as emp_name from(select a.co_emp_id as emp_id  from cbcs_assign_course_coordinator a
where a.sub_code='$sub_code' and a.`session`='$session' and a.session_year='$session_year')x
inner join user_details b on x.emp_id=b.id";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            return $query->result();
        } else {
            return false;
        }
    }

    function get_markeup_marks($data,$dist_id,$dist_cat_id){
      $admn_no=$data['primary']['admn_no'];
      $session_year=$data['primary']['session_year'];
      $session=$data['primary']['session'];
      $course_id=$data['primary']['course_id'];
      $branch_id=$data['primary']['branch_id'];
      $sub_code=$data['primary']['sub_code'];
      $sub_offerd_id=$data['primary']['sub_offerd_id'];
      $sql="
      select * from cbcs_markup_marks_upload a where a.admn_no='$admn_no' and a.session_year='$session_year'
      and a.`session`='$session' and a.course_id='$course_id'
      and a.branch_id='$branch_id' and a.sub_code='$sub_code'
      and a.sub_offered_id='$sub_offerd_id' and a.dist_id='$dist_id' and a.dist_category_id='$dist_cat_id'
      ";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
    //  echo  $this->db->last_query(); die();
          return $query->result();
      } else {
          return false;
      }
    }

    function get_subjects_plan($data){
      $session_year=  $data['primary']['session_year'];
      $session=  $data['primary']['session'];
     $branch_id=  $data['primary']['branch_id'];
      $jrfstatus=  $data['primary']['jrfstatus'];
      if($jrfstatus=="1"){
        $course_id=  "jrf";
      }else{
        $course_id=  $data['primary']['course_id'];
      }
//echo"".$course_id;
      $dept_id=  $data['primary']['dept_id'];
      $sub_code=  $data['primary']['sub_code'];
      $semester=  $data['primary']['semester'];
      $emp_no=  $data['primary']['emp_no'];
      $sub_group=  $data['primary']['group'];
      $sub_type=  $data['primary']['sub_type'];
      $section=  $data['primary']['section'];
      //echo "<pre>";print_r($data['primary']); exit;
      /* 05-9-19 $sql = "select a.*,b.*,c.id as sub_offered_id from cbcs_marks_dist a  inner join cbcs_marks_dist_child b on a.id=b.id
        left join cbcs_subject_offered c on a.dept_id=c.dept_id and a.course_id=c.course_id and a.branch_id=c.branch_id and a.sub_code=c.sub_code
         where a.session_year='$session_year'
             and a.`session`='$session' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.dept_id='$dept_id'
            and a.sub_code='$sub_code' and a.semester='$semester' and a.emp_no='$emp_no' order by b.category asc";*/
              if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
                $addgroup="AND a.group='$sub_group'";
                $joinGroup="AND a.group=c.sub_group";
              }
              if($branch_id=='comm' && $course_id=='comm'){
                $exsection="and a.section= '$section'";
              }else{
                $exsection="";
              }
          //    echo "<pre>";print_r($data['primary']); exit;
            $sql="
            (SELECT a.*,b.*,concat('c',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN cbcs_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code $joinGroup
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' and b.category like 'end_%' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no' $exsection $addgroup
            group by b.id,b.pk ORDER BY b.category ASC)

            union

            (SELECT a.*,b.*,concat('o',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN old_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code $joinGroup
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' and b.category like 'end_%' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no' $exsection $addgroup
            group by b.id,b.pk ORDER BY b.category ASC)
            ";
        $query = $this->db->query($sql);
      //  echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }

    function final_submitted_marks_list($session,$sessionyear,$branch_id,$course_id,$sub_code,$emp_id,$admn_no,$category_pk){
/*	$sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id,b.marks)) as marks,GROUP_CONCAT(b.marks) as total,GROUP_CONCAT(b.category_name) as cat
from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year=? and a.`session`=?
and a.branch_id=? and a.course_id=?
and a.subject_code=? and b.uploaded_by=? group by a.admn_no";*/
  $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  where  a.session_year=? and a.`session`=?
  and a.branch_id=? and a.course_id=?
  and a.subject_code=? and b.uploaded_by=? and admn_no=? and b.category_id <> ? group by a.admn_no";
  $query = $this->db->query($sql,array($sessionyear,$session,$branch_id,$course_id,$sub_code,$emp_id,$admn_no,$category_pk));
//  echo  $this->db->last_query(); die();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}
function final_markeup_marks_list($session,$sessionyear,$branch_id,$course_id,$sub_code,$sub_offered_id,$emp_id,$admn_no,$category_pk){
/*	$sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id,b.marks)) as marks,GROUP_CONCAT(b.marks) as total,GROUP_CONCAT(b.category_name) as cat
from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year=? and a.`session`=?
and a.branch_id=? and a.course_id=?
and a.subject_code=? and b.uploaded_by=? group by a.admn_no";*/
$sql="select * from cbcs_markup_marks_upload a
where a.admn_no='$admn_no' and a.session_year='$sessionyear' and a.`session` ='$session' and a.course_id='$course_id' and a.branch_id='$branch_id'
and a.sub_code='$sub_code' and a.sub_offered_id='$sub_offered_id' and a.dist_name like '%end_%'";
$query = $this->db->query($sql);
//echo  $this->db->last_query(); die();

if ($this->db->affected_rows() > 0) {
     return $query->result();
 } else {
     return false;
 }


}

function CourseCoordinatorID($session,$sessionyear,$sub_code,$sub_offerd_id){
  $sql="select * from cbcs_marks_master a where a.session_year=? and a.`session`=? and a.subject_id=? and a.sub_map_id=?";
  $query = $this->db->query($sql,array($sessionyear,$session,$sub_code,$sub_offerd_id));
//  echo  $this->db->last_query(); die();
  if ($query->num_rows() == 1)
      //return $query->row()->id;
      return $query->result();
  else
      return false;
}


function submit_marks_to_coordinator($data,$correction_log_id=null,$admn_no=null){
  $updateClouse=array(
    "marks_master_id"=>$data['marks_master_id'],
    "admn_no"=>$data['admn_no'],
  );
  if(!$correction_log_id){
	  $updateClouse['stu_status']="M";
  }
  $this->db->select('*');
  $this->db->from('cbcs_marks_subject_description');
  $this->db->where($updateClouse);
  $cnt=$this->db->get()->result();
  foreach ($cnt as $cntdata) {
    // code...
  //  print_r($cntdata->total);
   $oldtotal=$cntdata->total;
    $oldgrade=$cntdata->grade;
  }
  $update_backupdata=array(
    "old_total"=>$cnt[0]->total,
    "new_total"=>$data['total'],
    "old_grade"=>$oldgrade,
    "new_grade"=>$data['grade'],
  );
  $updateWhere=array(
    "correction_log_id"=>$correction_log_id,
    "admn_no"=>$admn_no
  );

  //echo "<pre>";print_r($update_backupdata);
  //exit;
  //$cnt=$this->db->last_query(); die();
  $count=count($cnt);
  if($count=="0"){
	  if(!$correction_log_id){
    $this->db->insert('cbcs_marks_subject_description', $data);
  //  $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0) {
    return "1";
  } else {
  return "0";
  
}
	  }
  }else{
	  if($correction_log_id){
	  
    $this->db->where($updateClouse);
    $this->db->update('cbcs_marks_subject_description', $data);
    //echo $this->db->last_query(); die();
    if ($this->db->_error_message()) {
      return "Someting Went Worng. Please try again.";
    } else {

      $this->db->where($updateWhere);
      $this->db->update('cbcs_marks_correction_backup', $update_backupdata);

      $sql="update cbcs_marks_correction_log set submit_to_exam_status='1'
      where id='$correction_log_id'";
      $query = $this->db->query($sql);

    return "Marks Submitted Successfully.";
    }
	  }else{
		   $this->db->where($updateClouse);
    $this->db->update('cbcs_marks_subject_description', $data);
    //echo $this->db->last_query(); die();
    if ($this->db->_error_message()) {
      return "Someting Went Worng. Please try again.";
    } else {
		return "1";
	}
		  
	  }
  
  }
}
function getGrades($sub_code,$session,$session_year,$sub_offerd_id,$course_id,$branch_id){
  if($course_id=='comm' && $branch_id=='comm'){
    $extraClouse="and a.sub_offered_id='$sub_offerd_id'";
  }

  $sql = "select a.* from cbcs_grading_range a where a.`session`='$session' and a.session_year='$session_year' and a.sub_code ='$sub_code' $extraClouse ";
    $query = $this->db->query($sql);
  //  echo  $this->db->last_query(); die();
    if ($this->db->affected_rows() > 0) {

        return $query->result();
    } else {
        return false;
    }
}

  function update_marks_status($session,$sessionyear,$branch_id,$course_id,$sub_code,$sub_offered_id,$emp_id,$admn_no,$category_pk){
    $sql="update cbcs_markup_marks_upload set status='1'
    where admn_no='$admn_no' and session_year='$sessionyear' and session ='$session' and course_id='$course_id' and branch_id='$branch_id'
    and sub_code='$sub_code' and sub_offered_id='$sub_offered_id' and dist_name like '%end_%'";
    $query = $this->db->query($sql);
    //echo  $this->db->last_query(); die();

    if ($this->db->affected_rows() > 0) {
         return true;
     } else {
         return false;
     }
  }


}

?>
