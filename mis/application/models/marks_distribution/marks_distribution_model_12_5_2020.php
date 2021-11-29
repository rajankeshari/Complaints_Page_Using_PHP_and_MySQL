<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Marks_distribution_model extends CI_Model {

    function __construct() {
// Call the Model constructor
        parent::__construct();
    }
function sendtoCCStatusnew($data,$sub_offer_id){
//	print_r($data);exit;
    $sub_type=$data['sub_type']; 
    $where=array(
      "sub_code"=>$data['sub_code'],
      "sub_offered_id"=>$sub_offer_id,
      "course"=>$data['course_id'],
      "branch"=>$data['branch_id'],
      "session_year"=>$data['session_year'],
      "session"=>$data['session'],
      "instructor_emp_id"=>$this->session->userdata('id')
    );
	
    if($sub_type=="Modular"){
     $where=array(
      "sub_code"=>$data['sub_code'],
      "sub_offered_id"=>$sub_offer_id,
      "course"=>$data['course_id'],
      "branch"=>$data['branch_id'],
      "session_year"=>$data['session_year'],
      "session"=>$data['session'],
	  "dept"=>$data['section'],
      "instructor_emp_id"=>$this->session->userdata('id')
    );
    }else{
	  $where=array(
      "sub_code"=>$data['sub_code'],
      "sub_offered_id"=>$sub_offer_id,
      "course"=>$data['course_id'],
      "branch"=>$data['branch_id'],
      "session_year"=>$data['session_year'],
      "session"=>$data['session'],
      "instructor_emp_id"=>$this->session->userdata('id')
    );
	}
	//print_r($where);exit;
    $this->db->select('*');
    $this->db->from('cbcs_marks_send_to_coordinator');
    $this->db->where($where);
	//echo $this->db->last_query();exit;
   return $this->db->get()->num_rows();
	//echo $this->db->last_query();exit;
}

// marks upload for OE/DE start
  function  sub_offered_to($data){
     $sub_code=  $data['primary']['sub_code'];
      $session=  $data['primary']['session'];
      $session_year=  $data['primary']['session_year'];
      $jrfstatus=  $data['primary']['jrfstatus'];
      $emp_no=  $this->session->userdata("id");
      if($jrfstatus=="0"){
        $extraClouse="AND a.course_id != 'jrf'";
      }else{
        $extraClouse="AND a.course_id = 'jrf'";
      }
      $sql="(SELECT *,null as lastc
            FROM cbcs_subject_offered a
            INNER JOIN cbcs_subject_offered_desc b
            INNER JOIN cbcs_stu_course c ON a.sub_code=c.subject_code AND a.id=c.sub_offered_id AND a.`session`=c.`session` AND a.session_year=c.session_year
            WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' AND b.emp_no='$emp_no' $extraClouse
            GROUP BY a.course_id,a.branch_id)
            union
            (SELECT *
            FROM old_subject_offered a
            INNER JOIN old_subject_offered_desc b
            INNER JOIN old_stu_course c ON a.sub_code=c.subject_code AND a.id=c.sub_offered_id AND a.`session`=c.`session` AND a.session_year=c.session_year
            WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' AND b.emp_no='$emp_no' $extraClouse
            GROUP BY a.course_id,a.branch_id)

            ";
            $query = $this->db->query($sql);
          //  echo $this->db->last_query(); die();
            if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
    }
// marks upload for OE/DE end
	
	//marks correction log start

function marks_correction_history_sub_wise($sub_code,$sub_offerd_id,$course_id,$branch_id,$session_year,$session){
  $sql="SELECT a.correction_log_id,a.admn_no,a.form_id,a.session_year,a.`session`,a.sub_code,a.sub_offered_id,a.course_id,a.branch_id,
a.marks_upload_id,a.marks_upload_dis_id,a.dist_name,a.dist_id,a.old_marks,a.corrected_marks,a.old_total,a.new_total,a.new_grade,a.old_grade,a.corrected_by,a.updated_at,b.id AS logid,b.submit_to_exam_status AS st
FROM cbcs_marks_correction_backup a
INNER JOIN cbcs_marks_correction_log b ON a.correction_log_id=b.id AND a.admn_no=b.admn_no AND a.session_year=b.session_year AND a.`session`=b.`session` AND a.sub_code=b.sub_code
where a.sub_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.course_id='$course_id' and a.branch_id='$branch_id' and a.session_year='$session_year' and a.`session`='$session'
ORDER BY b.submit_to_exam_status,b.update_status,b.id DESC";
$query = $this->db->query($sql);
//echo $this->db->last_query(); die();
if ($query->num_rows() > 0)
    return $query->result();
else
    return false;

}




//marks correction log end
	
	//course Modification start
function getDeptCourse($session,$session_year,$dept){
  $sql = "select concat('c',a.id) as id,a.sub_code,a.sub_name from cbcs_subject_offered a
where a.`session`='$session' and a.session_year='$session_year' and a.dept_id='$dept' group by a.sub_code
union
select concat('o',a.id) as id,a.sub_code,a.sub_name from old_subject_offered a
where a.`session`='$session' and a.session_year='$session_year' and a.dept_id='$dept' group by a.sub_code";
  $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}

function getCourseInfo($session,$session_year,$course){
  $sql = "
  SELECT p.*,concat_ws(' ',e.first_name,e.middle_name,e.last_name) as emp_name,c.id AS dist_id,d.id AS dist_ch_id,d.category,d.wtg,d.pk,d.marks_upload_status,GROUP_CONCAT(DISTINCT concat_ws('|',d.id,d.category,d.wtg,d.pk,d.marks_upload_status)) as marks_dist_info,
  (
  SELECT COUNT(*)
  FROM cbcs_marks_upload_description x
  WHERE x.category_id IN(d.pk)) AS uploadcnt
  FROM (
  SELECT a.id, CONCAT('c',a.id) AS sub_offered_id,a.session_year,a.`session`,a.sub_code,a.sub_name,a.semester,a.dept_id,a.course_id,a.branch_id,a.sub_type,a.sub_group,b.desc_id,b.sub_offered_id AS sub_offer_id
  ,b.emp_no,b.section
  FROM cbcs_subject_offered a
  INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id AND a.sub_code=b.sub_id
  WHERE a.`session`='$session' AND a.session_year='$session_year' AND a.sub_code='$course'
  UNION
  SELECT a.id, CONCAT('o',a.id) AS sub_offered_id,a.session_year,a.`session`,a.sub_code,a.sub_name,a.semester,a.dept_id,a.course_id,a.branch_id,a.sub_type,a.sub_group,b.desc_id,b.sub_offered_id AS sub_offer_id
  ,b.emp_no,b.section
  FROM old_subject_offered a
  INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id AND a.sub_code=b.sub_id
  WHERE a.`session`='$session' AND a.session_year='$session_year' AND a.sub_code='$course') p
  LEFT JOIN cbcs_marks_dist c ON p.sub_code=c.sub_code AND p.course_id=c.course_id AND p.branch_id=c.branch_id AND p.session_year=c.session_year AND p.session=c.`session`
  and p.emp_no=c.emp_no AND (CASE WHEN p.sub_type='Modular' THEN p.sub_group=c.`group` ELSE 1=1 END)
  LEFT JOIN cbcs_marks_dist_child d ON c.id=d.id
  inner join user_details e on p.emp_no=e.id
  group by p.emp_no,p.id";
  $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}

function sendToExamStatus($session,$session_year,$course){
$sql="select * from cbcs_marks_send_to_coordinator a where a.sub_code='ECI101' and a.`session`='Monsoon' and a.session_year='2019-2020'
group by a.sub_code";
$query = $this->db->query($sql);
//echo $this->db->last_query(); die();
if ($query->num_rows() > 0)
    return $query->result();
else
    return 0;
}

function sendToCCStatus($session,$session_year,$course){
  $sql="select count(*) as s_status from cbcs_marks_send_to_coordinator a where a.sub_code='$course' and a.`session`='$session' and a.session_year='$session_year' and a.status='1'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return false;
}
function saveDeletionLog($logData){
    if($this->db->insert('cbcs_dist_deletion_log', $logData)){
        return true;
    }else{
      return false;
    }
}

function getCourseComponent($session,$session_year,$course){
  $sql="select * from cbcs_marks_dist a
inner join cbcs_marks_dist_child b on a.id=b.id
where a.`session`='$session' and a.session_year='$session_year' and a.sub_code='$course'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}
function getmarksupload($session,$session_year,$course,$course_id,$branch_id){
  $sql="select * from cbcs_marks_upload a
where a.branch_id='$branch_id'  and a.course_id='$course_id'
and a.`session`='$session' and a.session_year='$session_year' and a.subject_code='$course'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}

function deleteFromMarksUploadDisp($pk,$dist_id,$marks_id){
  $sql="delete from cbcs_marks_upload_description  where marks_id='$marks_id'";//and category_id='$pk' and marks_dist_child_id='$dist_id'
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0)
      return true;
  else
      return false;
}
function deleteFromMarksUpload($marks_id){
  $sql="delete from cbcs_marks_upload  where id='$marks_id'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0)
      return true;
  else
      return false;
}

function deleteFrommarksDistChild($dist_id){
  $sql="delete from cbcs_marks_dist_child  where id='$dist_id'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0)
      return true;
  else
      return false;
}
function deleteFrommarksDist($dist_id){
  $sql="delete from cbcs_marks_dist  where id='$dist_id'";
  $query = $this->db->query($sql);
  //echo $this->db->last_query(); die();
  if ($this->db->affected_rows() > 0)
      return true;
  else
      return false;
}

  function GetdeleteLog(){
    $id=$this->session->userdata("id");
    $sql="select * from cbcs_dist_deletion_log a where a.action_by='$id' order by id desc";
      $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
        return $query->result();
    else
        return false;
  }


//course Modification end
	
    function get_excusive_master_sub_core_md() {
        $sql = "SELECT sub_core_category_id, sub_core_category  from   cbcs_marks_dist_sub_core_master where status='active' order by sub_core_category";
        $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
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

    public function update_submit_status($id){
      $data = [
            'marks_upload_status' => '1',
            'marks_status_updated_by' => $this->session->userdata("id"),
        ];
        $this->db->where('pk', $id);
        $this->db->update('cbcs_marks_dist_child', $data);
        if($this->db->affected_rows() == 1){
          // echo "<pre>";
          // echo"reg_form :".	$this->db->_error_message();
          $status="1";
          return $status;
         }else{
           $status="0";
        //   echo ""
           return $status;
         }

    }
    public function change_submit_status($id){
      $data = [
            'marks_upload_status' => '0',
            'marks_status_updated_by' => $this->session->userdata("id"),
        ];
        $this->db->where('pk', $id);
        $this->db->update('cbcs_marks_dist_child', $data);
        if($this->db->affected_rows() == 1){
          // echo "<pre>";
          // echo"reg_form :".	$this->db->_error_message();
          $status="Marks Upload Status Updated successfully.";
          return true;
         }else{
           $status="Unable to update Marks Upload Status.Please try again.";
        //   echo ""
           return false;
         }
    }

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
    public function countsubmittedstatus($data,$dt){
      $sql="select b.*
      from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year='$dt[session_year]' and a.`session`='$dt[session]'
      and a.branch_id='$dt[branch_id]' and a.course_id='$dt[course_id]'
      and a.subject_code='$dt[subject_code]' and b.uploaded_by='$data[uploaded_by]' and b.category_id='$data[category_id]' group by a.admn_no";
      $query = $this->db->query($sql);
      if ($query->num_rows() > 0){
          return $query->result();
        }
      else{
          // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
          return 0;
        }
    }
    public function save_cbcs_marks_distribution($data,$dt){
      $sql = "SELECT * from cbcs_marks_upload_description where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
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
           $sqlupdate="update cbcs_marks_upload_description set marks='$data[marks]' where marks_id='$data[marks_id]' and category_name='$data[category_name]' and category_id='$data[category_id]' and marks_dist_child_id='$data[marks_dist_child_id]'
           and admn_id='$data[admn_id]' ";
           $query = $this->db->query($sqlupdate);
             if($this->db->affected_rows() != 1){
               return "0";
             }else{
               return "1";
             }
         }
           $countStu=$this->getStudentList($dt);
         $cnt=count($countStu);
         $countStu=$this->countsubmittedstatus($data,$dt);
          $cntsubmit=count($countStu); //exit;
         if($cnt == $cntsubmit){
           $this->update_submit_status($data['category_id']);
         }else{
           $this->change_submit_status($data['category_id']);
         }
    }
    public function GetCoordinatorStatus($data){
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
                $joinGroup="AND a.group=d.sub_group";
              }
			   if($branch_id=='comm' && $course_id=='comm'){
                $addgroup="AND a.group='$sub_group'";
                $joinGroup="AND a.group=d.sub_group";
				$commextraclouse="AND a.section='$section'";
              }
			  
			   if($sub_category=="DE" || $sub_category=="OE"){
                $extraJoin_cbcs="INNER JOIN cbcs_stu_course cs on cs.course=a.course_id and cs.branch=a.branch_id and cs.`session`=a.`session` and cs.sub_offered_id=a.map_id";
              }else{
                $extraJoin_cbcs="";
              }
			  
      $sql = "select  count(b.category) as noofdist,sum(b.marks_upload_status) as marks_upload_status,c.status from cbcs_marks_dist a
       inner join cbcs_marks_dist_child b on a.id=b.id
	   $extraJoin_cbcs
       LEFT JOIN cbcs_subject_offered d ON  a.course_id=d.course_id AND a.branch_id=d.branch_id AND a.sub_code=d.sub_code $joinGroup

       LEFT JOIN cbcs_marks_send_to_coordinator c ON a.sub_code=c.sub_code AND a.emp_no=c.instructor_emp_id AND
       a.session_year=c.session_year AND a.`session`=c.`session`
       where a.session_year='$session_year'
           and a.`session`='$session' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.dept_id='$dept_id'
          and a.sub_code='$sub_code' and a.semester='$semester' and a.emp_no='$emp_no' $commextraclouse $addgroup order by b.category asc";
      $query = $this->db->query($sql);
    //  echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
          return $query->result();
      else
          return 0;
    }
	    public function getSubjectPlanList($data){
      $session_year=  $data['primary']['session_year'];
      $session=  $data['primary']['session'];
     $branch_id=  $data['primary']['branch_id'];
      $jrfstatus=  $data['primary']['jrfstatus'];
	   $sub_category=  $data['primary']['sub_category'];
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
               if($section!=""){
				    $commCouse="and d.section='$section'";
					$extraSec="and a.section=d.section";
			   }else{
				   $commCouse="";
				   $extraSec="";
			   }
              }
			  
			  if($semester !="" || $semester != null){
				  $semclouse="and c.semester='$semester'";
			  }
			  
          //    echo "<pre>";print_r($data['primary']); exit;
		  
		   if($sub_category=='OE' || $sub_category=='DE'){
            $extraJoin_cbcs="INNER JOIN cbcs_stu_course cs on  cs.session_year=a.session_year and cs.`session`=a.`session` and cs.course=a.course_id
            and cs.branch=a.branch_id #and (case when a.map_id is not null then cs.sub_offered_id=a.map_id else 1=1 end)";

            $extraJoin_old="INNER JOIN old_stu_course cs on  cs.session_year=a.session_year and cs.`session`=a.`session` and cs.course=a.course_id
            and cs.branch=a.branch_id #and (case when a.map_id is not null then cs.sub_offered_id=a.map_id else 1=1 end)";
          }
		  
            $sql="
            (SELECT a.*,b.*,concat('c',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
			$extraJoin_cbcs
            inner JOIN cbcs_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code AND a.session_year=c.session_year  and a.session=c.session $semclouse $joinGroup
			inner join cbcs_subject_offered_desc d on c.id=d.sub_offered_id  $extraSec          
		   WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no' $addgroup $commCouse
            group by b.id,b.pk ORDER BY b.category ASC)

            union

            (SELECT a.*,b.*,concat('o',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
			$extraJoin_old
            inner JOIN old_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code AND a.session_year=c.session_year and a.session=c.session $semclouse $joinGroup
			inner join old_subject_offered_desc d on c.id=d.sub_offered_id $extraSec
			WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no' $addgroup $commCouse
            group by b.id,b.pk ORDER BY b.category ASC)
            ";
        $query = $this->db->query($sql);
    //    echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }
  /* 8-11-19  public function getSubjectPlanList($data){
      $session_year=  $data['primary']['session_year'];
      $session=  $data['primary']['session'];
      $branch_id=  $data['primary']['branch_id'];
      $jrfstatus=  $data['primary']['jrfstatus'];
      if($jrfstatus=="1"){
        $course_id=  "jrf";
      }else{
        $course_id=  $data['primary']['course_id'];
      }

      $dept_id=  $data['primary']['dept_id'];
      $sub_code=  $data['primary']['sub_code'];
      $semester=  $data['primary']['semester'];
      $emp_no=  $data['primary']['emp_no'];

      /* 05-9-19 $sql = "select a.*,b.*,c.id as sub_offered_id from cbcs_marks_dist a  inner join cbcs_marks_dist_child b on a.id=b.id
        left join cbcs_subject_offered c on a.dept_id=c.dept_id and a.course_id=c.course_id and a.branch_id=c.branch_id and a.sub_code=c.sub_code
         where a.session_year='$session_year'
             and a.`session`='$session' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.dept_id='$dept_id'
            and a.sub_code='$sub_code' and a.semester='$semester' and a.emp_no='$emp_no' order by b.category asc";*/

       /*     $sql="
            (SELECT a.*,b.*,concat('c',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN cbcs_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no'
            ORDER BY b.category ASC)

            union

            (SELECT a.*,b.*,concat('o',c.id) AS sub_offered_id
            FROM cbcs_marks_dist a 
            INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
            inner JOIN old_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code
            WHERE a.session_year='$session_year' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.dept_id='$dept_id' AND a.sub_code='$sub_code' AND a.semester='$semester' AND a.emp_no='$emp_no'
            ORDER BY b.category ASC)
            ";
        $query = $this->db->query($sql);
        //  echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    } */
   function checkCourseCoordinator($session,$sessionyear,$sub_code,$sub_offerd_id,$sub_type){
      $sub_offerd_id=substr($sub_offerd_id,1);
      if($sub_type=='Modular'){
        $extraParam="AND a.sub_offered_id=?";
        $extraClouse=",$sub_offerd_id";
      }
      $sql="select * from cbcs_assign_course_coordinator a where a.session_year=? and a.`session`=? and a.sub_code=? $extraParam";
        if($sub_type=='Modular'){
          $query = $this->db->query($sql,array($sessionyear,$session,$sub_code,$sub_offerd_id));
        }else{
          $query = $this->db->query($sql,array($sessionyear,$session,$sub_code));
        }
    #  $query = $this->db->query($sql,array($sessionyear,$session,$sub_code));
    #  echo $this->db->last_query(); die();
      return $query->num_rows();
    }
    function saveMarksMaster($data){
      $selectValue=array(
        "sub_map_id"=>$data['sub_map_id'],
        "session"=>$data['session'],
        "session_year"=>$data['session_year'],
        "subject_id"=>$data['sub_code'],
      );
      $marks_master=array(
        "sub_map_id"=>$data['sub_map_id'],
        "subject_id"=>$data['sub_code'],
        "session"=>$data['session'],
        "session_year"=>$data['session_year'],
        "process_timestamp"=>date("Y-m-d H:i:s"),
        "emp_id"=>$data['emp_id'],
      );
      $marks_master_update=array(
      //  "sub_map_id"=>$data['sub_offered_id'],
        "sub_map_id"=>$data['sub_map_id'],
        "subject_id"=>$data['sub_code'],
        "session"=>$data['session'],
        "session_year"=>$data['session_year']

      );
    //  print_r($selectValue);
      $this->db->select('*');
      $this->db->from('cbcs_marks_master');
      $this->db->where($selectValue);
      //  echo  $this->db->last_query();
      $cnt = $this->db->get()->num_rows();
    //  echo  $this->db->last_query(); die();
      if($cnt==0){
          $this->db->insert('cbcs_marks_master', $marks_master);
		// echo $this->db->last_query(); die();
		/*if ($this->db->affected_rows() > 0) {
			return true;
		}else{
			
			return false;
		}*/
      }else{
        $this->db->where($marks_master_update);
        $this->db->update('cbcs_marks_master', $marks_master);
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

    function submit_marks_to_coordinator($data){
      $updateClouse=array(
        "marks_master_id"=>$data['marks_master_id'],
        "admn_no"=>$data['admn_no'],
      );
      $this->db->select('*');
      $this->db->from('cbcs_marks_subject_description');
      $this->db->where($updateClouse);
      $cnt=$this->db->get()->result_array();
      //$cnt=$this->db->last_query(); die();
      $count=count($cnt);
      if($count=="0"){
        $this->db->insert('cbcs_marks_subject_description', $data);
      //  $this->db->last_query(); die();
      }else{
        $this->db->where($updateClouse);
        $this->db->update('cbcs_marks_subject_description', $data);
        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
        //  echo  "marks updated";
        } else {
          //	echo"cbcs_assign_course_coordinator :".	$this->db->_error_message();exit;
        //    echo  "marks not updated";
        }
      }
    }
    function save_marks_submit_toC($data){
      $whereClouse=array(
        "sub_offered_id"=>$data['sub_offered_id'],
        "sub_code"=>$data['sub_code'],
        "marks_master_id"=>$data['marks_master_id'],
        "instructor_emp_id"=>$data['instructor_emp_id'],
		"dept"=>$data['dept'],
        "status"=>'1',
      );

      $this->db->select('*');
      $this->db->from('cbcs_marks_send_to_coordinator');
      $this->db->where($whereClouse);
      $cnt=$this->db->get()->result_array();
      //$cnt=$this->db->last_query(); die();
      $count=count($cnt);
      if($count==0){
      $this->db->insert('cbcs_marks_send_to_coordinator', $data);
      if($this->db->affected_rows() != 1){

                        echo"ERROR :".	$this->db->_error_message();

                      }else{

                      }
    }else{
      $this->db->where($whereClouse);
      $this->db->update('cbcs_marks_send_to_coordinator', $data);
    //  echo $this->db->last_query(); die();
    }
    }
   function GetCourseCoordinatorID($session,$sessionyear,$sub_code,$sub_offerd_id,$sub_type){
      $sub_offerd_id=substr($sub_offerd_id,1);
      if($sub_type=='Modular'){
        $extraParam="AND a.sub_offered_id=?";
        $extraClouse=",$sub_offerd_id";
      }
      $sql="select * from cbcs_assign_course_coordinator a where a.session_year=? and a.`session`=? and a.sub_code=? $extraParam";
        if($sub_type=='Modular'){
            $query = $this->db->query($sql,array($sessionyear,$session,$sub_code,$sub_offerd_id));
        }else{
            $query = $this->db->query($sql,array($sessionyear,$session,$sub_code));
        }
    #  $query = $this->db->query($sql,array($sessionyear,$session,$sub_code));
    #  echo  $this->db->last_query(); die();
      if ($query->num_rows() == 1)
          //return $query->row()->id;
          return $query->result();
      else
          return false;
    }

    function final_submitted_marks_list($session,$sessionyear,$branch_id,$course_id,$sub_code,$emp_id,$sub_offerd_id){
/*	$sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id,b.marks)) as marks,GROUP_CONCAT(b.marks) as total,GROUP_CONCAT(b.category_name) as cat
from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year=? and a.`session`=?
and a.branch_id=? and a.course_id=?
and a.subject_code=? and b.uploaded_by=? group by a.admn_no";*/
 /* 04-02-2020 $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  where a.session_year=? and a.`session`=?
  and a.branch_id=? and a.course_id=?
  and a.subject_code=? and b.uploaded_by=? group by a.admn_no"; */
  
   $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  INNER join cbcs_stu_course d on a.subject_code=d.subject_code and a.admn_no=d.admn_no and a.form_id=d.form_id
  where a.session_year='$sessionyear' and a.`session`='$session'
  and (case when d.sub_category like 'DE%' OR d.sub_category like 'OE%' OR a.branch_id='Online' then 1=1 else a.branch_id='$branch_id' end)
  and (case when d.sub_category like 'DE%' OR d.sub_category like 'OE%' OR a.branch_id='Online' then 1=1 else a.course_id='$course_id' end)
  and a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and b.uploaded_by='$emp_id' group by a.admn_no
  UNION
  select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  INNER join old_stu_course d on a.subject_code=d.subject_code and a.admn_no=d.admn_no and a.form_id=d.form_id
  where a.session_year='$sessionyear' and a.`session`='$session'
  and (case when d.sub_category like 'DE%' OR d.sub_category like 'OE%' OR a.branch_id='Online' then 1=1 else a.branch_id='$branch_id' end)
  and (case when d.sub_category like 'DE%' OR d.sub_category like 'OE%' OR a.branch_id='Online' then 1=1 else a.course_id='$course_id' end)
  and a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and b.uploaded_by='$emp_id' group by a.admn_no
  ";
  
  
  $query = $this->db->query($sql,array($sessionyear,$session,$branch_id,$course_id,$sub_code,$emp_id));
  //echo  $this->db->last_query(); die();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}

    function get_submitted_marks_list($session,$sessionyear,$branch_id,$course_id,$sub_code,$sub_offered_id,$emp_id){
/*	$sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id,b.marks)) as marks,GROUP_CONCAT(b.marks) as total,GROUP_CONCAT(b.category_name) as cat
from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year=? and a.`session`=?
and a.branch_id=? and a.course_id=?
and a.subject_code=? and b.uploaded_by=? group by a.admn_no";*/
 /* $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  where a.session_year=? and a.`session`=?
  and a.branch_id=? and a.course_id=?
  and a.subject_code=? and a.sub_offered_id=? and b.uploaded_by=? group by a.admn_no";*/
  $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  LEFT JOIN cbcs_subject_offered d on a.subject_code=d.sub_code and a.session_year=d.session_year and a.`session`=d.`session` AND SUBSTR(a.sub_offered_id,2)=d.id
  where a.session_year=? and a.`session`=?
  AND (case when d.sub_category like 'OE%' OR d.sub_category like 'DE%' then 1=1 else a.branch_id='$branch_id' end )
  AND (case when d.sub_category like 'OE%' OR d.sub_category like 'DE%' then 1=1 else a.course_id='$course_id' end )

  and a.subject_code=? and a.sub_offered_id=? and b.uploaded_by=? group by a.admn_no";
  
  
  $query = $this->db->query($sql,array($sessionyear,$session,$sub_code,$sub_offered_id,$emp_id));
	//echo  $this->db->last_query(); //die();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


}


/* 13-11-19    function get_submitted_marks_list($session,$sessionyear,$branch_id,$course_id,$sub_code,$sub_offered_id,$emp_id){
/*	$sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id,b.marks)) as marks,GROUP_CONCAT(b.marks) as total,GROUP_CONCAT(b.category_name) as cat
from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id where a.session_year=? and a.`session`=?
and a.branch_id=? and a.course_id=?
and a.subject_code=? and b.uploaded_by=? group by a.admn_no";
  $sql="select a.*, GROUP_CONCAT(concat_ws(',',b.category_id)) as marks,GROUP_CONCAT(b.marks) as total,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name
  from cbcs_marks_upload a inner join cbcs_marks_upload_description b on a.id=b.marks_id
  INNER JOIN user_details c on a.admn_no=c.id
  where a.session_year=? and a.`session`=?
  and a.branch_id=? and a.course_id=?
  and a.subject_code=? and a.sub_offered_id=? and b.uploaded_by=? group by a.admn_no";
  $query = $this->db->query($sql,array($sessionyear,$session,$branch_id,$course_id,$sub_code,$sub_offered_id,$emp_id));
//	echo  $this->db->last_query(); die();

    if ($this->db->affected_rows() > 0) {
         return $query->result();
     } else {
         return false;
     }


} */

function send_marks_submission_request($data){
  $GetCOwhereClouse=array(
    "sub_code"=>$data['sub_code'],
  //  "sub_offered_id"=>substr($data['sub_offerd_id'],1),
    "session"=>$data['session'],
    "session_year"=>$data['session_year'],
  );
  $this->db->select('*');
  $this->db->from('cbcs_assign_course_coordinator');
  $this->db->where($GetCOwhereClouse);
  $this->db->order_by("id", "desc");
  $this->db->limit(1);

  $cco=$this->db->get()->result_array();
  //  echo $this->db->last_query();
  $co_id=$cco[0]['co_emp_id'];
  if($co_id !="" || $co_id !=null){
  $data['co_emp_id']=$co_id;
  
    $where=array(
    "sub_code"=>$data['sub_code'],
    "sub_offerd_id"=>$data['sub_offerd_id'],
    "component_id"=>$data['component_id'],
    "req_emp_by"=>$data['req_emp_by'],
    "open_status"=>'0',
  );
  $this->db->select('*');
  $this->db->from('cbcs_marks_submission_reopen_req');
  $this->db->where($where);
  //  echo  $this->db->last_query();
  $cnt = $this->db->get()->num_rows();
  if($cnt==0){
  $this->db->insert('cbcs_marks_submission_reopen_req', $data);
  if($this->db->affected_rows() != 1){

                    echo "ERROR :".	$this->db->_error_message();

                  }else{
                      $response="1";
                  }
  }else{
	  $response="1";
  }
  }else{
    $response="Course Co-ordinator Not Found !";
  }
  return $response;
}

public function getSubjectPlanforview($sessionyear,$session,$branch_id,$course_id,$sub_code,$extraParam){
  $sub_type=$extraParam['sub_type'];
  $group=$extraParam['group'];
  $section=$extraParam['section'];
  $modular_exam_type=$extraParam['modular_exam_type'];
  $main_marks_id=$extraParam['main_marks_id'];
  if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
    $addgroup="AND a.group='$group' and a.id='$main_marks_id'";
    $joinGroup="INNER JOIN cbcs_subject_offered c ON  a.course_id=c.course_id AND a.branch_id=c.branch_id AND a.sub_code=c.sub_code AND a.group=c.sub_group";
  }

  $sql = "SELECT b.category,b.pk
FROM cbcs_marks_dist a
INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
 $joinGroup

WHERE a.session_year='$sessionyear' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.sub_code='$sub_code' and a.id='$main_marks_id' $addgroup order by b.category asc";
  $query = $this->db->query($sql);
//  echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}

/* 13-11-19 public function getSubjectPlanforview($sessionyear,$session,$branch_id,$course_id,$sub_code){
  $sql = "SELECT b.category,b.pk
FROM cbcs_marks_dist a
INNER JOIN cbcs_marks_dist_child b ON a.id=b.id
WHERE a.session_year='$sessionyear' AND a.`session`='$session' AND a.branch_id='$branch_id' AND a.course_id='$course_id' AND a.sub_code='$sub_code' order by b.category asc";
  $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
  if ($query->num_rows() > 0)
      return $query->result();
  else
      return 0;
}*/
    public function getSubjectPlan($data){

    $session_year=  $data['primary']['session_year'];
    $session=  $data['primary']['session'];
    $branch_id=  $data['primary']['branch_id'];
    $course_id=  $data['primary']['course_id'];
    $dept_id=  $data['primary']['dept_id'];
    $sub_code=  $data['primary']['sub_code'];
    $semester=  $data['primary']['semester'];
    $emp_no=  $data['primary']['emp_no'];

      $sql = "select a.*,b.* from cbcs_marks_dist a  inner join cbcs_marks_dist_child b on a.id=b.id where a.session_year='$session_year'
           and a.`session`='$session' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.dept_id='$dept_id'
          and a.sub_code='$sub_code' and a.semester='$semester' and a.emp_no='$emp_no' and b.marks_upload_status=0";
      $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
          return $query->result();
      else
          return 0;
    }
    public function getSubjectnonMarked($data){

    $session_year=  $data['primary']['session_year'];
    $session=  $data['primary']['session'];
    $branch_id=  $data['primary']['branch_id'];
    $course_id=  $data['primary']['course_id'];
    $dept_id=  $data['primary']['dept_id'];
    $sub_code=  $data['primary']['sub_code'];
    $semester=  $data['primary']['semester'];
    $emp_no=  $data['primary']['emp_no'];

      $sql = "select a.*,b.* from cbcs_marks_dist a  inner join cbcs_marks_dist_child b on a.id=b.id where a.session_year='$session_year'
           and a.`session`='$session' and a.branch_id='$branch_id' and a.course_id='$course_id' and a.dept_id='$dept_id'
          and a.sub_code='$sub_code' and a.semester='$semester' and a.emp_no='$emp_no'"; //and b.marks_upload_status=0";
      $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
      if ($query->num_rows() > 0)
          return $query->result();
      else
          return 0;
    }

    public function getstandardDaviation($data){
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
      $comm="";
	  $section="";
      if($branch_id=='comm' && $course_id=='comm'){
  $comm="and b.section='$section'";
  $section="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
}

$sql = "select COUNT(a.form_id) AS cnt, SUM(mud.marks) AS total, ROUND(AVG(mud.marks),3) AS AVG,GROUP_CONCAT(mud.marks) as marks,(sum(mud.marks))/COUNT(a.form_id) as mean from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='$sub_code' and 
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.course='$course_id' END) AND
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.branch='$branch_id' END)
#a.course='$course_id' and a.branch='$branch_id' 
and a.session_year='$session_year' and a.`session`='$session' $comm and mud.marks <= 100 and mud.marks != ''  having count(a.form_id) != 0
union all
select COUNT(a.form_id) AS cnt, SUM(mud.marks) AS total, ROUND(AVG(mud.marks),3) AS AVG,GROUP_CONCAT(mud.marks) as marks,(sum(mud.marks))/COUNT(a.form_id) as mean from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='$sub_code' AND
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.course='$course_id' END) AND
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.branch='$branch_id' END)
#and a.course='$course_id' and a.branch='$branch_id' 
and a.session_year='$session_year' and a.`session`='$session' $comm and mud.marks <= 100 and mud.marks != ''  having count(a.form_id) != 0";


        $query = $this->db->query($sql);
    //  echo  $this->db->last_query(); //die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
    }
    public function getStudentAvghMarks($data){
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
      $comm="";
	  $section="";
      if($branch_id=='comm' && $course_id=='comm'){
  $comm="and b.section='$section'";
  $section="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
}

$sql = "select count(a.form_id) as cnt, sum(mud.marks) as total,round(sum(mud.marks)/count(a.form_id),3) as avg from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='$sub_code' and 
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.course='$course_id' END) AND
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.branch='$branch_id' END)
#a.course='$course_id' and a.branch='$branch_id' 
and a.session_year='$session_year' and a.`session`='$session' $comm 
and mud.marks <= 100 and mud.marks != ''  having count(a.form_id) != 0
union all
select count(a.form_id) as cnt, sum(mud.marks) as total,round(sum(mud.marks)/count(a.form_id),3) as avg from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='$sub_code' and 
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.course='$course_id' END) AND
(CASE WHEN a.sub_category LIKE 'OE%' OR a.sub_category LIKE 'DE%' THEN 1=1 ELSE a.branch='$branch_id' END)
#a.course='$course_id' and a.branch='$branch_id' 
and a.session_year='$session_year' and a.`session`='$session' $comm and mud.marks <= 100 and mud.marks != ''  having count(a.form_id) != 0";


        $query = $this->db->query($sql);
    //  echo  $this->db->last_query(); //die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
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
      $modular_exam_type=  $data['primary']['modular_exam_type'];
      $sub_type=  $data['primary']['sub_type'];

		$comm="";
		$sections="";
		$news="";
      if($branch_id=='comm' && $course_id=='comm'){
       # $comm="and b.section='$section'";
       # $sections="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
	   if($session_year=='2019-2020' && $session=='Monsoon'){
		      $comm="and b.section='$section'";
              $sections="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
	   }else{
		   $news="and a.sub_category_cbcs_offered='$section'";
	   }

      }
      if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
        $innerjoin="INNER JOIN cbcs_modular_paper_details d on a.admn_no=d.admn_no and a.form_id=d.form_id";
        $condition="and d.$modular_exam_type in('$sub_code')  group by a.form_id";
      }

$sql = "select * from (select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$sections

$innerjoin
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' $news  and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' OR a.branch='Online' then 1=1 else a.course='$course_id' end) and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' OR a.branch='Online' then 1=1 else a.branch='$branch_id' end)

and a.session_year='$session_year' and a.`session`='$session' $comm $condition
union all
select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$sections

$innerjoin
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' $news  and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' OR a.branch='Online' then 1=1 else a.course='$course_id' end) and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' OR a.branch='Online' then 1=1 else a.branch='$branch_id' end)

and a.session_year='$session_year' and a.`session`='$session' $comm $condition) x
inner join reg_regular_form rrf on x.admn_no=rrf.admn_no and rrf.session_year='$session_year' and rrf.`session`='$session' and rrf.hod_status='1' and rrf.acad_status='1'
";

			//echo $sql; exit;
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
      }




  /*  public function getStudentListwithMarks($data){
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
      $modular_exam_type=  $data['primary']['modular_exam_type'];
      $sub_type=  $data['primary']['sub_type'];

		$comm="";
		$sections="";
		$news="";
      if($branch_id=='comm' && $course_id=='comm'){
       # $comm="and b.section='$section'";
       # $sections="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
	   if($session_year=='2019-2020' && $session=='Monsoon'){
		      $comm="and b.section='$section'";
              $sections="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
	   }else{
		   $news="and a.sub_category_cbcs_offered='$section'";
	   }
		
      }
      if($sub_type=='Modular' && $branch_id=='comm' && $course_id=='comm'){
        $innerjoin="INNER JOIN cbcs_modular_paper_details d on a.admn_no=d.admn_no and a.form_id=d.form_id";
        $condition="and d.$modular_exam_type in('$sub_code')  group by a.form_id";
      }

$sql = "select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$sections

$innerjoin
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' $news  and 
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' then 1=1 else a.course='$course_id' end) and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' then 1=1 else a.branch='$branch_id' end)

and a.session_year='$session_year' and a.`session`='$session' $comm $condition
union all
select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$sections

$innerjoin
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' $news  and 
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' then 1=1 else a.course='$course_id' end) and
(case when a.sub_category like 'OE%' OR a.sub_category like 'DE%' then 1=1 else a.branch='$branch_id' end)

and a.session_year='$session_year' and a.`session`='$session' $comm $condition";

			//echo $sql; exit;
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
      }*/


  /* 8-11-19  public function getStudentListwithMarks($data){
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
      $comm="";
      if($branch_id=='comm' && $course_id=='comm'){
        $comm="and b.section='$section'";
        $section="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
      }

/*       $sql = "select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='5511f60ba1fb2' and a.course='m.sc' and a.branch='m&c' and a.session_year='2019-2020' and a.`session`='Monsoon' $comm
union all
select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'
$section
where a.subject_code='5511f60ba1fb2' and a.course='m.sc' and a.branch='m&c' and a.session_year='2019-2020' and a.`session`='Monsoon'  $comm";
*/

/*
$sql = "select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$section
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm
union all
select a.form_id,a.admn_no,a.session_year,a.`session`,a.branch,a.course,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name,a.subject_code ,a.subject_name,mud.marks from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
LEFT JOIN cbcs_marks_upload mu on a.admn_no=mu.admn_no and a.session_year=mu.session_year and a.session=mu.session and a.subject_code=mu.subject_code
Left JOIN cbcs_marks_upload_description mud on mud.marks_id=mu.id and mud.category_id='$dist_id'

$section
where a.subject_code='$sub_code' and a.sub_offered_id='$sub_offerd_id' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm";


        $query = $this->db->query($sql);
      //   echo $this->db->last_query(); //die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
      } */
    public function getStudentList($data){
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
      $comm="";
      if($branch_id=='comm' && $course_id=='comm'){
  $comm="and b.section='$section'";
  $section="inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year";
}
        $sql = "select a.form_id,a.admn_no,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
$section
where a.subject_code='5511f60ba1fb2' and a.course='m.sc' and a.branch='m&c' and a.session_year='2019-2020' and a.`session`='Monsoon' $comm
union all
select a.form_id,a.admn_no,concat_ws(' ',c.first_name,c.middle_name,c.last_name) as name from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
$section
where a.subject_code='5511f60ba1fb2' and a.course='m.sc' and a.branch='m&c' and a.session_year='2019-2020' and a.`session`='Monsoon' $comm";

/*

$sql = "select a.form_id,a.admn_no,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name from cbcs_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year
where a.subject_code='$sub_code' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm
union all
select a.form_id,a.admn_no,concat_ws(' ',c.salutation,c.first_name,c.middle_name,c.last_name) as name from old_stu_course a
INNER JOIN user_details c on a.admn_no=c.id
inner join stu_section_data b on a.admn_no=b.admn_no and a.session_year=b.session_year
where a.subject_code='$sub_code' and a.course='$course_id' and a.branch='$branch_id' and a.session_year='$session_year' and a.`session`='$session' $comm";
*/

        $query = $this->db->query($sql);
//echo $this->db->last_query(); die();
        if ($query->num_rows() > 0){
            return $query->result();
          }
        else{
            // echo 'Database Error(' . $this->db->_error_number() . ') - ' . $this->db->_error_message();
            return 0;
          }
      }

  function get_subjects_offered($sub_code,$session,$session_year,$emp_no){
      $sql="(SELECT *,null as lastc
FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON a.id=b.sub_offered_id
WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' AND b.emp_no='$emp_no')
union
(SELECT *
FROM old_subject_offered a
INNER JOIN old_subject_offered_desc b ON a.id=b.sub_offered_id
WHERE a.sub_code='$sub_code' AND a.`session`='$session' AND a.session_year='$session_year' AND b.emp_no='$emp_no')
        ";
      $query = $this->db->query($sql);
  //  echo  $this->db->last_query(); die();

        if ($this->db->affected_rows() > 0) {
             return $query->result();
         } else {
             return false;
         }
    }
    public function save_primary($data) {

      /*   echo '<pre>';
          print_r($data);
          echo '</pre>';
          die(); */
		  
	    $this->db->select('*');
        $this->db->from('cbcs_marks_dist');
        $this->db->where($data['primary']);
        $cnt = $this->db->get()->num_rows();
		if($cnt == 0){
        $this->db->insert('cbcs_marks_dist', $data['primary']);		
		//echo $this->db->last_query(); exit;		
        return $this->db->insert_id();
		}else{
			return false;
		}
    }

    public function save_sec($data) {

        /*  echo '<pre>';
          print_r($data['sec']);
          echo '</pre>';
          die();
         */

        if ($this->db->insert_batch('cbcs_marks_dist_child', $data['sec']))
        //echo $this->db->last_query(); die();
            return $this->db->affected_rows();
    }
    public function save_sec_DE_OE($data) {
        /*  echo '<pre>';
          print_r($data['sec']);
          echo '</pre>'; */
          for($i=0;$i<count($data['sec']);$i++){
          $whereClouse=array(
            "id"=> $data['sec'][$i]['id'],
            "category"=> $data['sec'][$i]['category'],
            "wtg"=> $data['sec'][$i]['wtg'],
            "type"=> $data['sec'][$i]['type']
          );
        //echo"<pre>";  print_r($whereClouse);
        $this->db->select('*');
        $this->db->from('cbcs_marks_dist_child');
        $this->db->where($whereClouse);
        $cnt = $this->db->get()->num_rows();
        //  echo"<br>". $this->db->last_query();
            if($cnt==0){
              $insert=array(
                "id"=> $data['sec'][$i]['id'],
                "category"=> $data['sec'][$i]['category'],
                "wtg"=> $data['sec'][$i]['wtg'],
                "type"=> $data['sec'][$i]['type']
              );
            //  echo "<br>insert";
                $this->db->insert('cbcs_marks_dist_child', $insert);
            }else{
            //  echo "<br>not insert";
            }

          }
          return $this->db->affected_rows();
        /*  die();
          foreach ($data['sec'] as $value) {
            // code...
            print_r($value->id );
          }

        if ($this->db->insert_batch('cbcs_marks_dist_child', $data['sec']))
        //echo $this->db->last_query(); die();
            return $this->db->affected_rows();*/
    }
    function show_photo($id) {
        $sql = "select lecture_plan_path from cbcs_marks_dist WHERE id='$id'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function get_md_id($emp_id, $course_id, $branch_id, $sem, $sub_code, $section,$session_year,$session, $param = null) {
        if ($param <> null)
            $add2 = "  and  lecture_plan_path is not null ";
        $add = null;
        if ($course_id == 'comm')
            $add = " and  section = '$section' ";
        $sql = " select id from cbcs_marks_dist WHERE emp_no='$emp_id'   and   course_id='$course_id' and branch_id='$branch_id' and semester='$sem' and  sub_code='$sub_code'  and session='$session' and session_year='$session_year' $add    $add2 ";
        $result = $this->db->query($sql);
		//echo $this->db->last_query();exit;
        return $result->row()->id;
    }
	
	function get_md_primary_data($session_yr,$session,$sub_code ) {                    
        $sql = " select x.*,concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as name from (select *  from cbcs_marks_dist WHERE sub_code='$sub_code'  and   session_year='$session_yr' and session='$session' ) x  left join  user_details u on  u.id=x.emp_no ";
        $result = $this->db->query($sql);
        return $result->result();
    }
	
	function get_md_secondary_data($id) {                    
        $sql = " select x.* from cbcs_marks_dist_child x where x.id='$id' ";
        $result = $this->db->query($sql);
        return $result->result();
    }
	
	
	
	
	
    function update_lp($id, $path) {
        $sql = "UPDATE cbcs_marks_dist set lecture_plan_path='$path' WHERE id='$id'";
        if ($this->db->query($sql))
            return true;
        else
            return false;
    }
	//===============================================================DANGER
  function get_modular_before($session_yr,$session, $sub_code, $section){
		  $sql = " select count(id) as ctr from cbcs_modular_paper_details WHERE session=? and  session_year=? and  section=?   and   before_mid=?";
        $result = $this->db->query($sql,array($session,$session_yr, $section, $sub_code));
          return $result->row()->ctr;
	  }
	    function get_modular_after($session_yr,$session, $sub_code, $section){
		  $sql = " select count(id) as ctr from cbcs_modular_paper_details WHERE session=? and  session_year=? and  section=?   and   after_mid=?";
        $result = $this->db->query($sql,array($session,$session_yr, $section, $sub_code));
        return $result->row()->ctr;
	  }

	public function get_subjects($data, $param = true) {
          if ($param) 
            $cbcs_rep1 = " and newt.sub_type='Theory'  and newt.contact_hours<>0  ";
           else 
            $cbcs_rep1 = "  ";        
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();
        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_sys_sql = "  SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours,
								 newt.sub_category
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'old' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
                        FROM old_subject_offered_desc AS A
                        INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
						JOIN old_stu_course osc1 ON  osc1.sub_offered_id=B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "'   and  newt.coordinator='1'  AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.course_id<>'jrf' AND  (newt.sub_category NOT like 'OE%' AND newt.sub_category NOT like  'DE%' ) $cbcs_rep1 
		
		                     UNION                        
                           SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours,
								 newt.sub_category
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
						JOIN cbcs_stu_course osc1 ON  osc1.sub_offered_id=B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "'   and  newt.coordinator='1'  AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.course_id<>'jrf' AND  (newt.sub_category NOT like 'OE%' AND newt.sub_category NOT like  'DE%' ) $cbcs_rep1 ";
        // end
              $query = $this->db->query($cbcs_sys_sql );
		       $result = $query->result();        
                return $result;
     }
	 
	 
	      public function get_subjects_DE_OE($data,$param = true,$jf) {
		//  echo $jf;exit;
		
			if($jf=='1'){
				$withjrf="AND newt.course_id='jrf'";
			}else{
				$withjrf="AND newt.course_id<>'jrf'";
			}
		
             if ($param)
               $cbcs_rep1 = " and newt.sub_type='Theory'  and newt.contact_hours<>0  ";
              else
               $cbcs_rep1 = "  ";
           $emp_id = $data['emp_id'];
           $session_year = $data['session_year'];
           $session = $data['session'];
           $this->load->database();
           // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
           //  @author: ritu raj
           $cbcs_sys_sql = "  SELECT  concat('o',newt.sub_offered_id)  AS map_id,
                                   newt.semester,
                                   newt.sub_name AS sub_name,
                                   newt.sub_id,
                                   newt.branch_id,
                                   newt.group,
                                   newt.section,
                                   newt.branch_name,
                                   newt.course_id,
                                   newt.aggr_id,
                                   newt.course_name,
                                   newt.coordinator,
                                   newt.sub_type,
                                   newt.contact_hours,
                                   newt.sub_category
                           FROM (SELECT B.id as sub_offered_id,B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                           B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'old' as aggr_id,
                           B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
                           FROM old_subject_offered_desc AS A
                           INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
						   JOIN old_stu_course osc1 ON  osc1.sub_offered_id=B.id
                           LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                           LEFT JOIN cs_courses ON B.course_id=cs_courses.id
                           where (case when B.sub_category like 'OE%' OR B.sub_category  like  'DE%' then  A.emp_no='$emp_id' ELSE  ''  end)) AS newt
                           WHERE newt.emp_no = '" . $emp_id . "'   and  newt.coordinator='1'  AND SESSION='" . $session . "' AND session_year='" . $session_year . "'
                          $withjrf AND (newt.sub_category LIKE 'OE%' OR newt.sub_category LIKE 'DE%')
                           $cbcs_rep1

   		                     UNION
                              SELECT  concat('c',newt.sub_offered_id)  AS map_id,
                                   newt.semester,
                                   newt.sub_name AS sub_name,
                                   newt.sub_id,
                                   newt.branch_id,
                                   newt.group,
                                   newt.section,
                                   newt.branch_name,
                                   newt.course_id,
                                   newt.aggr_id,
                                   newt.course_name,
                                   newt.coordinator,
                                   newt.sub_type,
                                   newt.contact_hours,
                                   newt.sub_category
                           FROM (SELECT B.id as sub_offered_id, B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                           B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                           B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
                           FROM cbcs_subject_offered_desc AS A
                           INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id						   
                           JOIN cbcs_stu_course osc2 ON  osc2.sub_offered_id=B.id
                           LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                           LEFT JOIN cs_courses ON B.course_id=cs_courses.id
                           where (case when B.sub_category like 'OE%' OR B.sub_category  like  'DE%' then  A.emp_no='$emp_id' ELSE  ''  end)
                         ) AS newt
                           WHERE newt.emp_no = '" . $emp_id . "'   and  newt.coordinator='1'  AND SESSION='" . $session . "' AND session_year='" . $session_year . "' $withjrf AND  (newt.sub_category  like 'OE%' OR newt.sub_category  like  'DE%' )
                            $cbcs_rep1 group by newt.sub_id,SUBSTRING(newt.sub_category,1,2),newt.branch_id
                          ";
           // end
                 $query = $this->db->query($cbcs_sys_sql );
              //  echo  $this->db->last_query(); exit;
   		       $result = $query->result();
                   return $result;
        }

    //=======================JRf==============================================
	
	
   public function get_subjects_jrf($data, $param = true) {
        if ($param)
            $cbcs_rep1 = " and newt.sub_type='Theory'  and newt.contact_hours<>0 ";
         else
            $cbcs_rep1 = "";
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();

		 // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_sys_sql = " SELECT  concat('o',newt.id) AS map_id,
                newt.semester,
                newt.sub_name AS sub_name,
                newt.sub_id,
                newt.branch_id,
                newt.group,
                newt.section,
                newt.branch_name,
                newt.course_id,
                newt.aggr_id,
                newt.course_name,
                newt.coordinator,
                newt.sub_type,
                newt.contact_hours
        FROM (SELECT B.id,B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'old' as aggr_id,
        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
        FROM old_subject_offered_desc AS A
        INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND (newt.group='0' or  newt.group='' or newt.group=null)  "
. "AND newt.course_id='jrf' 
AND  (newt.sub_category NOT like 'OE%' AND newt.sub_category NOT like  'DE%' )
$cbcs_rep1
		                     UNION
                         SELECT  concat('c',newt.id) AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours
                        FROM (SELECT B.id,B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,B.sub_category,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . "AND newt.course_id='jrf' 
				AND  (newt.sub_category NOT like 'OE%' AND newt.sub_category NOT like  'DE%' )
				$cbcs_rep1 ";
        // end


        $query = $this->db->query($cbcs_sys_sql);

        $result = $query->result();
      //  echo $this->db->last_query();die();
        //print_r($result);
        return $result;
    }
  /*  public function get_subjects_jrf($data, $param = true) {
        if ($param)       
            $cbcs_rep1 = " and newt.sub_type='Theory'  and newt.contact_hours<>0 ";            
         else             
            $cbcs_rep1 = "";        
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();
		
		 // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
        $cbcs_sys_sql = "  
                         SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type,
                                newt.contact_hours
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,B.contact_hours,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.group='0'  "
                . "AND newt.course_id='jrf' $cbcs_rep1 ";
        // end


        $query = $this->db->query($cbcs_sys_sql);

        $result = $query->result();
        //echo $this->db->last_query();die();
        //print_r($result);
        return $result;
    }*/

    //=========================================================================



    public function get_prac_subjects($data,$param=null) {
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();    
        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj        
		   $cbcs_rep1 =" ".($param=='all_except_theory'?" and newt.sub_type<>'Theory' ":" and newt.sub_type='Practical' ")."  ";        		
		$cbcs_sys_sql = "		
		                 SELECT NULL AS map_id, newt.semester, newt.sub_name AS sub_name, newt.sub_id, newt.branch_id, newt.group, newt.section, newt.branch_name, newt.course_id, newt.aggr_id, newt.course_name, newt.coordinator, newt.sub_type
                        FROM (
                             SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`, B.semester, A.emp_no, A.sub_offered_id AS map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'old' AS aggr_id, B.sub_name,A.coordinator,B.sub_type,A.section
                              FROM old_subject_offered_desc AS A
                              INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
                              LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                              LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt 
                              WHERE newt.emp_no = '" . $emp_id . "'  and  newt.coordinator='1'   AND SESSION='" . $session . "' AND session_year='" . $session_year . "'   AND newt.course_id<>'jrf' $cbcs_rep1 
                     		   UNION                        
                               SELECT  NULL AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type
                        FROM (SELECT B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "'  and  newt.coordinator='1'   AND SESSION='" . $session . "' AND session_year='" . $session_year . "' AND newt.course_id<>'jrf' $cbcs_rep1   ";
        // end
        $query = $this->db->query($cbcs_sys_sql);
        $result = $query->result();       
        return $result;
    } 

    //==========================================JRF==============================================

     public function get_prac_subjects_jrf($data,$param=null) {
        $emp_id = $data['emp_id'];
        $session_year = $data['session_year'];
        $session = $data['session'];
        $this->load->database();
        // @desc:added  as per cbcs launch  from  2019-2020/ monsoon /@dated: 29/7/19 , query is being modified to aassertain that  old & new with cbcs students can accomodate as one unit
        //  @author: ritu raj
		$cbcs_rep1 = "".($param=='all_except_theory'?" and newt.sub_type<>'Theory' ":" and newt.sub_type='Practical' ")." ";
        $cbcs_sys_sql = " SELECT  concat('o',newt.id) AS map_id,
                newt.semester,
                newt.sub_name AS sub_name,
                newt.sub_id,
                newt.branch_id,
                newt.group,
                newt.section,
                newt.branch_name,
                newt.course_id,
                newt.aggr_id,
                newt.course_name,
                newt.coordinator,
                newt.sub_type
        FROM (SELECT B.id,B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'old' as aggr_id,
        B.sub_name,A.coordinator,B.sub_type,A.section
        FROM old_subject_offered_desc AS A
        INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "'  AND newt.course_id='jrf' $cbcs_rep1
        UNION

                        SELECT  concat('c',newt.id) AS map_id,
                                newt.semester,
                                newt.sub_name AS sub_name,
                                newt.sub_id,
                                newt.branch_id,
                                newt.group,
                                newt.section,
                                newt.branch_name,
                                newt.course_id,
                                newt.aggr_id,
                                newt.course_name,
                                newt.coordinator,
                                newt.sub_type
                        FROM (SELECT B.id,B.SESSION,B.session_year,B.sub_code AS sub_id, B.branch_id,B.course_id,B.sub_group AS `group`,
                        B.semester, A.emp_no, A.sub_offered_id as map_id,cs_branches.name AS branch_name,cs_courses.name AS course_name,'cbcs' as aggr_id,
                        B.sub_name,A.coordinator,B.sub_type,A.section
                        FROM cbcs_subject_offered_desc AS A
                        INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
                        LEFT JOIN cs_branches ON B.branch_id=cs_branches.id
                        LEFT JOIN cs_courses ON B.course_id=cs_courses.id) AS newt
                        WHERE newt.emp_no = '" . $emp_id . "' and  newt.coordinator='1' AND SESSION='" . $session . "' AND session_year='" . $session_year . "'  AND newt.course_id='jrf' $cbcs_rep1 ";
        // end
        $query = $this->db->query($cbcs_sys_sql);
        $result = $query->result();
        return $result;
    }

}
