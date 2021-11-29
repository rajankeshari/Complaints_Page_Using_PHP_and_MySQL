<?php

class Other_basic_model extends CI_Model
{
     var $courses = 'courses';
     var $course_structure = 'course_structure';
     var $table_subject = 'subjects';
    private $form = 'reg_other_form';
	private $form_summer = 'reg_summer_form';
    private $formexam = 'reg_exam_rc_form';
    private $subject ='reg_other_subject';
    private $fee ='reg_other_fee';
	 private $fee_summer ='reg_summer_fee';
	
	
    private $feeexam ='reg_exam_rc_fee';
	private  $form_undo='reg_other_form_undo_remark';

    function __construct() {
        parent::__construct();
    }

    function getCourses(){
        $q=$this->db->get($this->courses);
        return $this->getDropdown($q->result(),'id','name');

    }

    //result form data base Set field name value, label show//
    private function getDropdown($result,$v,$show){
        $a = array();
            foreach($result as  $v){
                $a[$v->id]=$v->name;
            }
                return $a;
    }


                //subject Get
    /*function getSubject($course_id, $branch_id, $semester, $stuid, $ty = '', $dept_id = '',$group='') {
        $d = $this->getStudentAcdamicDetails($stuid);

        if (($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc') && ($semester == '1' || $semester == '2')) {
            $curaid = 'comm_comm_' . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
            $course_id = 'comm';
            $branch_id = 'comm';
        } else {
            $curaid = $course_id . "_" . $branch_id . "_" . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
        }
          // echo $curaid;
        $this->load->model('course_structure/basic_model');

        $agr = $this->basic_model->get_latest_aggr_id($course_id, $branch_id, $curaid);
       // print_r($agr[0]);
        if (($course_id == 'btech' || $course_id == 'b.tech' ) && ($semester == '1' || $semester == '2')) {
            if (!empty($agr)) {
                $tr = explode('_', $agr[0]->aggr_id);
                if (isset($tr[2])) {

                    $f = "comm_comm_" . $tr[2]."_".$tr[3];
                }
            } else {
                $f = "comm_comm_" . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
            }
        } else if (empty($agr)) {

            $f = $curaid = $course_id . "_" . $branch_id . "_" . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
        } else {

            $f = $agr[0]->aggr_id;
        }
       //   echo $agr[0]->aggr_id;
        if ($_id == "")
            $dept_id = $this->session->userdata('dept_id');

         //  echo $semester .",". $f .",". $dept_id;
        $data =array();
       if (($semester == '1' || $semester == '2') && ($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc')) {

           if($group)
           $data = $this->get_subjects_by_sem_filtered($semester."_".$group,$f);

       }else{
        $data = $this->get_subjects_by_sem_and_dept_filtered($semester, $f, $dept_id);
       }

        if ($ty == '1') {
            $o = false;
            foreach ($data as $da) {
                if (is_float($this->get_numeric($da->sequence))) {
                    $o = true;
                    break;
                }
            }
            return $o;
        } else {
            $i = 0;

            foreach ($data as $da) {
                    $d['subjects'][$i]['sequence'] = $da->sequence;
                    $d['subjects'][$i]['id'] = $da->id;
                    $d['subjects'][$i]['subject_id'] = $this->basic_model->get_subject_details($da->id)->subject_id;
                    $d['subjects'][$i]['name'] = $this->basic_model->get_subject_details($da->id)->name;
                $i++;
            }
            return $d;
        }
    }
 */
    function getStudentAcdamicDetails($id) {
        return $this->db->get_where('stu_academic', array('admn_no' => $id))->result();
    }


    function getSubjectById($id) {
        $q = $this->db->get_where($this->table_subject, array('id' => $id));
        if ($q->num_rows($q)) {

            return $q->row();
        }
        return false;
    }

    function getSubjectSemester($id){
      $q=$this->db->get_where($this->course_structure, array('id' => $id));
      if($q->num_rows($q)){
        return $q->result_array();
      }
      return false;
    }

    function getSemester($id) {
        $q = $this->db->get_where($this->table_subject, array('id' => $id));
        if ($q->num_rows($q)) {

            return $q->row();
        }
        return false;
    }

  function formrResponse($stid,$sem){
	  $q="SELECT `semester`, form_id as form_id FROM (`reg_other_form`) WHERE `admn_no` = ? and form_id=( select max(form_id) from  reg_other_form WHERE `admn_no` = ? )";
		$query = $this->db->query($q,array($stid,$stid))->result();
		
			$q =$this->db->get_where($this->form,array('admn_no'=>$stid,'semester'=>$query[0]->semester,'form_id'=>$query[0]->form_id));
			if($q->num_rows() > 0)
				return $q->result();

				return false;
		}
   function getApprovedFormByStudent($id){
	  	$query = $this->db->select('form_id,semester')->get_where($this->form,array('hod_status'=>'1','acad_status'=>'1','admn_no'=>$id));

		if($query->num_rows() > 0){
			return $query->result_array();
			}

	  }

          function getSelectedSubject($fid){
              return $this->db->get_where($this->subject, array('form_id' => $fid))->result();
          }
		  function getSelectedSubject_summer($fid){
              return $this->db->get_where('reg_summer_subject', array('form_id' => $fid))->result();
          }

          function getFeedetails($fid){
              return $this->db->get_where($this->fee, array('form_id' => $fid))->row();
          }

          public function GetStudent($id, $fid) {
        $query = $this->db->query("SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->form . "` ON (`stu_academic`.`admn_no` = `" . $this->form . "`.`admn_no`) INNER JOIN `" . $this->fee . "` ON (`" . $this->form . "`.`form_id` = `" . $this->fee . "`.`form_id`) WHERE  `" . $this->form . "`.`admn_no` = '" . $id . "' and  `" . $this->form . "`.`form_id` ='" . $fid . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
			
			$query = $this->db->query("SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->form_summer . "` ON (`stu_academic`.`admn_no` = `" . $this->form_summer . "`.`admn_no`) INNER JOIN `" . $this->fee_summer . "` ON (`" . $this->form_summer . "`.`form_id` = `" . $this->fee_summer . "`.`form_id`) WHERE  `" . $this->form_summer . "`.`admn_no` = '" . $id . "' and  `" . $this->form_summer . "`.`form_id` ='" . $fid . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
			
            return false;
		}
        }
    }
        public function GetStudent_exam($id, $fid) {
        $query = $this->db->query("SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->formexam . "` ON (`stu_academic`.`admn_no` = `" . $this->formexam . "`.`admn_no`) INNER JOIN `" . $this->feeexam . "` ON (`" . $this->formexam . "`.`form_id` = `" . $this->feeexam . "`.`form_id`) WHERE  `" . $this->formexam . "`.`admn_no` = '" . $id . "' and  `" . $this->formexam . "`.`form_id` ='" . $fid . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function hod_vaise_student($dep,$cid='',$bid='',$sid='',$ses='',$sesY=''){

		$q="select * from ".$this->form." as sf, stu_details as sd, user_details as ud, ".$this->fee." as srf  where dept_id='".$dep."' and srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
		if($cid)
			$q.=" and sf.course_id='".$cid."'";
		if($bid)
			$q.=" and sf.branch_id='".$bid."'";
		if($sid)
			$q.=" and sf.semester='".$sid."'";
		if($ses)
			$q.=" and sf.session='".$ses."'";
		if($sesY)
			$q.=" and sf.session_year='".$sesY."'";
			$q.=" order by sf.semester";

			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
	}

        public function acdamic_vaise_student($did='',$cid='',$bid='',$sid='',$ses='',$sesY=''){
			$q = "select * from ".$this->form." as sf, stu_details as sd, user_details as ud, ".$this->fee." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no and course_id<>'jrf'";
			if($did)
				$q.=" and ud.dept_id='".$did."'";
			if($cid)
				$q.=" and sf.course_id='".$cid."'";
			if($bid)
				$q.=" and sf.branch_id='".$bid."'";
			if($sid)
				$q.=" and sf.semester='".$sid."'";
			if($ses)
				$q.=" and sf.session='".$ses."'";
			if($sesY)
				$q.=" and sf.session_year='".$sesY."'";
				$q.=" and sf.hod_status='1'";
				$q.=" order by sf.semester";

			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
    }
    public function acdamic_vaise_student_exam($did='',$cid='',$bid='',$sid='',$ses='',$sesY=''){
			$q = "select * from ".$this->formexam." as sf, stu_details as sd, user_details as ud, ".$this->feeexam." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no and course_id<>'jrf'";
			if($did)
				$q.=" and ud.dept_id='".$did."'";
			if($cid)
				$q.=" and sf.course_id='".$cid."'";
			if($bid)
				$q.=" and sf.branch_id='".$bid."'";
			if($sid)
				$q.=" and sf.semester='".$sid."'";
			if($ses)
				$q.=" and sf.session='".$ses."'";
			if($sesY)
				$q.=" and sf.session_year='".$sesY."'";
				$q.=" and sf.hod_status='1'";
				$q.=" order by sf.semester";

			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
    }

      function udateForm($form,$stu_id,$data){
               //echo  $form; die();
			$this->db->update($this->form, $data, array('form_id' => $form,'admn_no'=>$stu_id));
                        if($this->db->affected_rows() >0){
                            return true; 
                          }else{
                            return false;
                          }
			//return true;
		}
                     function udateFormexam($form,$stu_id,$data){
               //echo  $form; die();
			$this->db->update($this->formexam, $data, array('form_id' => $form,'admn_no'=>$stu_id));
                        if($this->db->affected_rows() >=0){
                            return true; 
                          }else{
                            return false;
                          }
			//return true;
		}

      function getCourseDurationById($id){

           $q=$this->db->get_where($this->courses, array('id'=>$id));
           $qa=$q->result_array();
           $sem=0;
           if($qa)
               {
                 //$val=$q->result()->row
                 $sem = ($qa[0]['duration'] * 2);

                }
          return $sem;
        }

	  function formUndo($data){
           $this->db->insert($this->form_undo,$data);
           echo $this->db->last_query();
           if($this->db->_error_number()){
               return false;
           }
           return true;
       }
/*
    function get_subjects_by_sem_filtered($sem,$aggr_id)
  {
    $id=$this->session->userdata('id');
    $query = $this->db->query("SELECT * FROM course_structure WHERE semester = '$sem' AND aggr_id = '$aggr_id'
      AND course_structure.id IN (SELECT marks_master.subject_id FROM marks_master,marks_subject_description WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='id' AND marks_subject_description.grade='F')
      ORDER BY
    cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc,
    cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
    //$this->db->order_by("sequence","ASC");
    //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
    return $query->result();
  }

  function get_subjects_by_sem_and_dept_filtered($sem,$aggr_id,$dept_id)
  {
    $id=$this->session->userdata('id');
    $query = $this->db->query("SELECT * FROM course_structure
    INNER JOIN dept_course ON dept_course.aggr_id = course_structure.aggr_id
    WHERE semester = '$sem' AND course_structure.aggr_id = '$aggr_id' AND dept_course.dept_id = '$dept_id'
    AND course_structure.id IN (SELECT marks_master.subject_id FROM marks_master,marks_subject_description WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade='F')
    ORDER BY
    cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc,
    cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");


    //$this->db->order_by("sequence","ASC");
    //$query = $this->db->get_where($this->table_course_structure,array('semester'=>$sem, 'aggr_id'=>$aggr_id));
    return $query->result();
  }*/

  function get_list_of_failed_semesters($sess)
  {
    $id=$this->session->userdata('id');

    //$query=$this->db->query("SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id FROM marks_master,marks_subject_description,course_structure WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_master.subject_id=course_structure.id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade='F' AND marks_master.subject_id NOT IN (SELECT marks_master.subject_id FROM marks_master,marks_subject_description WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade!='F')");
/*$query = $this->db->query("SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed,course_structure



WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id

and final_semwise_marks_foil_freezed.admn_no=final_semwise_marks_foil_desc_freezed.admn_no

 AND final_semwise_marks_foil_desc_freezed.mis_sub_id=course_structure.id AND final_semwise_marks_foil_desc_freezed.admn_no='$id'

 AND final_semwise_marks_foil_desc_freezed.grade='F' AND final_semwise_marks_foil_desc_freezed.mis_sub_id NOT IN (
SELECT final_semwise_marks_foil_desc_freezed.mis_sub_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id
 AND
 final_semwise_marks_foil_desc_freezed.admn_no='$id' AND final_semwise_marks_foil_desc_freezed.grade!='F')
");
*/

$str_add=" and mod(final_semwise_marks_foil_freezed.semester,2) ".($sess=='Monsoon'?" >0 " :" =0 ")." ";


$query=$this->db->query("select x.id,x.semester,x.sequence,x.aggr_id from

(SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id,final_semwise_marks_foil_desc_freezed.mis_sub_id 
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed,course_structure
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id AND final_semwise_marks_foil_freezed.admn_no=final_semwise_marks_foil_desc_freezed.admn_no AND final_semwise_marks_foil_desc_freezed.mis_sub_id=course_structure.id AND final_semwise_marks_foil_desc_freezed.admn_no='$id' AND final_semwise_marks_foil_desc_freezed.grade='F' 

$str_add

GROUP BY final_semwise_marks_foil_freezed.session_yr,final_semwise_marks_foil_freezed.session,final_semwise_marks_foil_freezed.semester,final_semwise_marks_foil_freezed.type
,final_semwise_marks_foil_desc_freezed.mis_sub_id

ORDER BY final_semwise_marks_foil_freezed.session_yr desc  ,  final_semwise_marks_foil_freezed.semester DESC,   final_semwise_marks_foil_freezed.tot_cr_pts desc /*limit 1*/)x
where x.mis_sub_id

 NOT IN (
 
 select y.* from

(
SELECT final_semwise_marks_foil_desc_freezed.mis_sub_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id AND final_semwise_marks_foil_desc_freezed.admn_no='$id' AND final_semwise_marks_foil_desc_freezed.grade!='F'
GROUP BY final_semwise_marks_foil_freezed.session_yr,final_semwise_marks_foil_freezed.session,final_semwise_marks_foil_freezed.semester,final_semwise_marks_foil_freezed.type

ORDER BY final_semwise_marks_foil_freezed.session_yr desc  ,  final_semwise_marks_foil_freezed.semester DESC,   final_semwise_marks_foil_freezed.tot_cr_pts desc /*limit 1*/)y )
");



    $semest=array();
    foreach ($query->result() as $q) {
       
      $tmp=explode('_', $q->semester);
       /*if(isset($tmp[0])){
          if($sess=='Winter' && $tmp[0]%2==0){
            $semest[]=$tmp[0];
          }
          if($sess=='Monsoon' && $tmp[0]%2!=0){
            $semest[]=$tmp[0];
          }
       }*/
        $semest[]=$tmp[0];
    }
	
/*	foreach ($query->result() as $q) {
      $tmp=explode('_', $q->semester);
      if(isset($tmp[0]))
      $semest[]=$tmp[0];
    }*/

	
	 
    $semest_unique=array_unique($semest);

    sort($semest_unique);

	//print_r( $semest_unique);
    return $semest_unique;
  }
  //======================================from tabulation fail starts==============================================
  
  function get_list_of_failed_semesters_old()
  {
    $id=$this->session->userdata('id');

    $query=$this->db->query("select cs.id,cs.semester,cs.sequence,cs.aggr_id from
(select p.* from (
SELECT A.*
FROM (
SELECT
LEFT(a.passfail,1) as passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.subje_code,a.subje_name
FROM tabulation1 a
WHERE a.adm_no='".$id."' and a.sem_code not like 'PREP%' 
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A 
GROUP BY A.sem_code)p
where p.passfail='F')t
inner join subjects q on t.subje_code=q.subject_id
inner join course_structure cs on cs.id=q.id
inner join reg_regular_form rf on rf.course_aggr_id=cs.aggr_id and rf.admn_no='".$id."'");


    $semest=array();
    foreach ($query->result() as $q) {
       
      $tmp=explode('_', $q->semester);
       /*if(isset($tmp[0])){
          if($sess=='Winter' && $tmp[0]%2==0){
            $semest[]=$tmp[0];
          }
          if($sess=='Monsoon' && $tmp[0]%2!=0){
            $semest[]=$tmp[0];
          }
       }*/
        $semest[]=$tmp[0];
    }
	
/*	foreach ($query->result() as $q) {
      $tmp=explode('_', $q->semester);
      if(isset($tmp[0]))
      $semest[]=$tmp[0];
    }*/

    $semest_unique=array_unique($semest);

    sort($semest_unique);

    return $semest_unique;
  }
  
  
  
  //===================================from tabulation fail ends=========================================

  function get_list_of_failed_subjects_in_given_semester($stuid,$semest)
  
 
  {
	    //echo  'sem'.$semest; die();
    $this->load->model('course_structure/basic_model');
    $id=$this->session->userdata('id');
    $d = $this->getStudentAcdamicDetails($stuid);
   /* $query=$this->db->query("select p.* from(
SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id
FROM marks_master,marks_subject_description,course_structure
WHERE marks_master.id=marks_subject_description.marks_master_id AND 
marks_master.subject_id=course_structure.id AND marks_subject_description.admn_no='".$id."' 
AND marks_subject_description.grade='F' AND marks_master.subject_id NOT IN (
SELECT marks_master.subject_id
FROM marks_master,marks_subject_description
WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='".$id."' 
AND marks_subject_description.grade!='F' )
ORDER BY CAST(SUBSTRING_INDEX(`sequence`, '.', 1) AS DECIMAL) ASC, CAST(SUBSTRING_INDEX(`sequence`, '.', -1) AS DECIMAL) ASC)p
group by p.id");*/

      /*  $query = $this->db->query("select p.* from(SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed,course_structure



WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id

and final_semwise_marks_foil_freezed.admn_no=final_semwise_marks_foil_desc_freezed.admn_no

 AND final_semwise_marks_foil_desc_freezed.mis_sub_id=course_structure.id AND final_semwise_marks_foil_desc_freezed.admn_no='$id'

 AND final_semwise_marks_foil_desc_freezed.grade='F' AND final_semwise_marks_foil_desc_freezed.mis_sub_id NOT IN (
SELECT final_semwise_marks_foil_desc_freezed.mis_sub_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id
 AND
 final_semwise_marks_foil_desc_freezed.admn_no='$id' AND final_semwise_marks_foil_desc_freezed.grade!='F')
  ORDER BY CAST(SUBSTRING_INDEX(`sequence`, '.', 1) AS DECIMAL) ASC, CAST(SUBSTRING_INDEX(`sequence`, '.', -1) AS DECIMAL) ASC)p
group by p.id");
*/


//$str_add=" and mod(final_semwise_marks_foil_freezed.semester,2) ".($semest%2<>0?" >0 " :" =0 ")." ";
$query= $this->db->query("

select p.* from



(

SELECT x.id,x.semester,x.sequence,x.aggr_id
FROM (
SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id,final_semwise_marks_foil_desc_freezed.mis_sub_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed,course_structure
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id AND final_semwise_marks_foil_freezed.admn_no=final_semwise_marks_foil_desc_freezed.admn_no 
AND final_semwise_marks_foil_desc_freezed.mis_sub_id=course_structure.id AND final_semwise_marks_foil_desc_freezed.admn_no='".$id."'  AND 
final_semwise_marks_foil_desc_freezed.grade='F'  
GROUP BY final_semwise_marks_foil_freezed.session_yr,final_semwise_marks_foil_freezed.session,final_semwise_marks_foil_freezed.semester,final_semwise_marks_foil_freezed.type
,final_semwise_marks_foil_desc_freezed.mis_sub_id
ORDER BY final_semwise_marks_foil_freezed.session_yr DESC, final_semwise_marks_foil_freezed.semester DESC, final_semwise_marks_foil_freezed.tot_cr_pts DESC )x

WHERE x.mis_sub_id NOT IN (
SELECT y.*
FROM (
SELECT final_semwise_marks_foil_desc_freezed.mis_sub_id
FROM final_semwise_marks_foil_freezed,final_semwise_marks_foil_desc_freezed
WHERE final_semwise_marks_foil_freezed.id=final_semwise_marks_foil_desc_freezed.foil_id AND final_semwise_marks_foil_desc_freezed.admn_no='".$id."' 
 AND final_semwise_marks_foil_desc_freezed.grade!='F'
GROUP BY final_semwise_marks_foil_freezed.session_yr,final_semwise_marks_foil_freezed.session,final_semwise_marks_foil_freezed.semester,final_semwise_marks_foil_freezed.type
,final_semwise_marks_foil_desc_freezed.mis_sub_id
ORDER BY final_semwise_marks_foil_freezed.session_yr DESC, final_semwise_marks_foil_freezed.semester DESC, final_semwise_marks_foil_freezed.tot_cr_pts DESC ) y)
  ORDER BY CAST(SUBSTRING_INDEX(x.sequence, '.', 1) AS DECIMAL) ASC, CAST(SUBSTRING_INDEX(x.sequence, '.', -1) AS DECIMAL) ASC)p
group by p.id");


//print_r($query->result()); die();
     $i = 0;
    $data=array();
    foreach ($query->result() as $q) {
      $tmp=explode('_', $q->semester);
	  //print_r($tmp[0]); die();
      if($tmp[0] == $semest )
      {
                    $d['subjects'][$i]['sequence'] = $q->sequence;
                    $d['subjects'][$i]['id'] = $q->id;
                    $d['subjects'][$i]['subject_id'] = $this->basic_model->get_subject_details($q->id)->subject_id;
                    $d['subjects'][$i]['name'] = $this->basic_model->get_subject_details($q->id)->name;
                $i++;
      }

      $data[]=$q;
    }
 //print_r($d); die();
    return $d;
  }
  function get_list_of_failed_subjects_in_given_semester_old($stuid,$semest)
  {
    $this->load->model('course_structure/basic_model');
    $id=$this->session->userdata('id');
    $d = $this->getStudentAcdamicDetails($stuid);
    $query=$this->db->query("select cs.id,cs.semester,cs.sequence,cs.aggr_id from
(select p.* from (
SELECT A.*
FROM (
SELECT
LEFT(a.passfail,1) as passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.subje_code,a.subje_name
FROM tabulation1 a
WHERE a.adm_no='".$id."' and a.sem_code not like 'PREP%' 
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A 
GROUP BY A.sem_code)p
where p.passfail='F')t
inner join subjects q on t.subje_code=q.subject_id
inner join course_structure cs on cs.id=q.id
inner join reg_regular_form rf on rf.course_aggr_id=cs.aggr_id and rf.admn_no='".$id."'");

     $i = 0;
    $data=array();
    foreach ($query->result() as $q) {
      $tmp=explode('_', $q->semester);
      
      if($tmp[0] == $semest )
      {
                    $d['subjects'][$i]['sequence'] = $q->sequence;
                    $d['subjects'][$i]['id'] = $q->id;
                    $d['subjects'][$i]['subject_id'] = $this->basic_model->get_subject_details($q->id)->subject_id;
                    $d['subjects'][$i]['name'] = $this->basic_model->get_subject_details($q->id)->name;
                $i++;
      }

      $data[]=$q;
    }
    

    return $d;
  }
  
  function checkStuStatus($sy,$sess,$et){
      $admn=$this->session->userdata('id');
      $sql = "select * from reg_other_form where admn_no=? and session_year=?
and session=? and hod_status<>'2' and acad_status<>'2' and type=?";

        $query = $this->db->query($sql,array($admn,$sy,$sess,($et=='Other')?'R':'S'));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
  }
  
  function get_latest_open_date_other(){
      
      $sql = "select * from sem_date_open_close_tbl where exam_type='Other' and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";

        $query = $this->db->query($sql,array($sy,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
  }
  function get_latest_open_date_special(){
      
      $sql = "select * from sem_date_open_close_tbl where exam_type='Special' and CURDATE() between DATE_FORMAT(normal_start_date, '%Y-%m-%d') and DATE_FORMAT(normal_close_date, '%Y-%m-%d')";

        $query = $this->db->query($sql,array($sy,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
  }

}

?>
