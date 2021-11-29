<?php

class Summer_basic_model extends CI_Model
{
     var $courses = 'courses';
     var $date= 'reg_summer_openclose';
     var $date_des = 'reg_summer_openclose_desc';
     var $table_subject = 'subjects';
     var $summer_courses_offered = 'summer_offered';

    private $form = 'reg_summer_form';
    private $subject ='reg_summer_subject';
    private $fee ='reg_summer_fee';

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

    public function update_ocDate($data){

	$this->db->update($this->date, $data, array('id' => '1'));
	return true;
    }

    public function insertDateDes($data){

	$this->db->insert($this->date_des, $data);
	if($this->db->_error_message()){
            return $this->db->_error_message();
	}else{
            return true;
        }
    }
    public function getOcdatedes(){
			return $this->db->query("SELECT * FROM `reg_summer_openclose_desc` WHERE `des_id`=(SELECT max(`des_id`) FROM `reg_summer_openclose_desc`) and date_id=1")->row();
      //return $this->db->query("SELECT * FROM `reg_summer_openclose` WHERE `id`=(SELECT max(`id`) FROM `reg_summer_openclose`)")->row();
    }
     public function getOcdate(){
			$q= $this->db->get_where($this->date, array('id' => '1'));
                        return $q->row();
		}




                //subject Get
    function getSubject($course_id, $branch_id, $semster, $stuid, $ty = '', $dept_id = '',$group='') {
        $d = $this->getStudentAcdamicDetails($stuid);

if (($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc') && ($semster == '1' || $semster == '2')) {
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
        if (($course_id == 'btech' || $course_id == 'b.tech' ) && ($semster == '1' || $semster == '2')) {
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
        if ($dept_id == "")
            $dept_id = $this->session->userdata('dept_id');

         //  echo $semster .",". $f .",". $dept_id;
        $data =array();
       if (($semster == '1' || $semster == '2')) {

           if($group)
           $data = $this->basic_model->get_subjects_by_sem($semster."_".$group,$f);

       }else{
        $data = $this->basic_model->get_subjects_by_sem_and_dept($semster, $f, $dept_id);
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

    function getSemester($id) {
        $q = $this->db->get_where($this->table_subject, array('id' => $id));
        if ($q->num_rows($q)) {

            return $q->row();
        }
        return false;
    }

  function formrResponse($stid,$sem){
		$query = $this->db->select_max('form_id')->get_where($this->form,array('admn_no'=>$stid,'semester'=>$sem))->result();
                        
			$q =$this->db->get_where($this->form,array('admn_no'=>$stid,'semester'=>$sem,'form_id'=>$query[0]->form_id));
			if($q->num_rows() > 0)
				return $q->result();

				return false;
		}
   function getApprovedFormByStudent($id){
//	  	$query = $this->db->select('form_id,semester')->get_where($this->form,array('hod_status'=>'1','acad_status'=>'1','admn_no'=>$id));
      $query = $this->db->get_where($this->form,array('hod_status'=>'1','acad_status'=>'1','admn_no'=>$id));

		if($query->num_rows() > 0){
			return $query->result_array();
			}

	  }

          function getSelectedSubject($fid){
              return $this->db->get_where($this->subject, array('form_id' => $fid))->result();
          }

          function getFeedetails($fid){
              return $this->db->get_where($this->fee, array('form_id' => $fid))->row();
          }

          public function GetStudent($id, $fid) {
              $q="SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->form . "` ON (`stu_academic`.`admn_no` = `" . $this->form . "`.`admn_no`) INNER JOIN `" . $this->fee . "` ON (`" . $this->form . "`.`form_id` = `" . $this->fee . "`.`form_id`) WHERE  `" . $this->form . "`.`admn_no` = '" . $id . "' and  `" . $this->form . "`.`form_id` ='" . $fid . "'";
             // echo $q;
        $query = $this->db->query($q);

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
			$q.=" and sf.semster='".$sid."'";
		if($ses)
			$q.=" and sf.session='".$ses."'";
		if($sesY)
			$q.=" and sf.session_year='".$sesY."'";
			$q.=" order by sf.semester";
			//echo $q;
			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
	}

        public function acdamic_vaise_student($did='',$cid='',$bid='',$sid='',$ses='',$sesY=''){
			$q = "select * from ".$this->form." as sf, stu_details as sd, user_details as ud, ".$this->fee." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
			if($did)
				$q.=" and ud.dept_id='".$did."'";
			if($cid)
				$q.=" and sf.course_id='".$cid."'";
			if($bid)
				$q.=" and sf.branch_id='".$bid."'";
			if($sid)
				$q.=" and sf.semster='".$sid."'";
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
			return true;
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

    function get_list_of_failed_semesters()
    {
      $id=$this->session->userdata('id');
      $s='Summer';
       $sess_syear=$this->check_open_close_date_new->get_latest_session_session_year($s,'all');
       $syear=$sess_syear->session_year;
      $query=$this->db->query("SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id FROM marks_master,marks_subject_description,course_structure WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_master.subject_id=course_structure.id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade='F' and marks_master.`status`='Y' and marks_master.session_year='".$syear."' AND marks_master.subject_id NOT IN (SELECT marks_master.subject_id FROM marks_master,marks_subject_description WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade!='F' and marks_master.`status`='Y' and
marks_master.session_year='".$syear."')");
//      $query=$this->db->query("select c.id,c.semester,c.sequence,c.aggr_id from marks_subject_description a
//inner join marks_master b on a.marks_master_id=b.id
//inner join course_structure c on c.id=b.subject_id
//where a.admn_no='".$id."' and b.session_year='".$syear."' and a.grade='F'");

      $semest=array();
      foreach ($query->result() as $q) {
        $tmp=explode('_', $q->semester);
        if(isset($tmp[0]))
        $semest[]=$tmp[0];
      }

      $semest_unique=array_unique($semest);

      sort($semest_unique);

      return $semest_unique;
    }

    function get_list_of_failed_subjects_in_given_semester($stuid,$semest,$syear)
    {
      $this->load->model('course_structure/basic_model');
      $id=$this->session->userdata('id');
      $d = $this->getStudentAcdamicDetails($stuid);
      $query=$this->db->query("SELECT course_structure.id,course_structure.semester,course_structure.sequence,course_structure.aggr_id FROM marks_master,marks_subject_description,course_structure WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_master.subject_id=course_structure.id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade='F' and marks_master.`status`='Y' AND marks_master.subject_id NOT IN (SELECT marks_master.subject_id FROM marks_master,marks_subject_description WHERE marks_master.id=marks_subject_description.marks_master_id AND marks_subject_description.admn_no='$id' AND marks_subject_description.grade!='F' and marks_master.`status`='Y' and marks_master.session_year='".$syear."' )and marks_master.session_year='".$syear."'
        ORDER BY
        cast(SUBSTRING_INDEX(`sequence`, '.', 1) as decimal) asc,
        cast(SUBSTRING_INDEX(`sequence`, '.', -1) as decimal) asc");
      
//      $query=$this->db->query("select c.id,c.semester,c.sequence,c.aggr_id from marks_subject_description a
//inner join marks_master b on a.marks_master_id=b.id
//inner join course_structure c on c.id=b.subject_id
//where a.admn_no='".$id."' and b.session_year='".$syear."' and a.grade='F'");
      
      
     /*  $query=$this->db->query("select b.mis_sub_id as id,c.semester,c.sequence,c.aggr_id from final_semwise_marks_foil a
inner join final_semwise_marks_foil_desc b on b.foil_id=a.id
inner join course_structure c on c.id=b.mis_sub_id
where a.admn_no='15MS000154' and a.session_yr='2016-2017' and b.grade='F'"); */
            
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
    
    function check_for_current_session($admn_no,$sy,$sess){
        
        $sql = "select * from reg_summer_form where admn_no=? and session_year=? and session=? and hod_status<>'2' and acad_status<>'2' ";

        $query = $this->db->query($sql,array($admn_no,$sy,$sess));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
        
    }
	
	function check_whether_idle_reg_stu($admn_no,$sy,$sess,$sem){
        
        $sql = " select c.admn_no from   reg_idle_form c where  c.admn_no=?  and c.session_year=? and c.`session`=? and c.semester=? ";

        $query = $this->db->query($sql,array($admn_no,$sy,$sess,$sem));

      // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }                
    }

    function get_all_sub_offer_by_dept($dept,$couse,$branch,$sem){

      $sql = "select a.*, b.*,c.* from summer_offered a left join course_structure b on a.id=b.id inner join subjects c on c.id=b.id";
      $query = $this->db->query($sql);
      #$query = $this->db->query($sql,array($admn_no,$sy,$sess,$sem));

      // echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }                

    }


    //get data : shobhan
    function summer_subjects_offered($id){
//       $sql="select  a.session_yr, a.dept, a.course, a.semester, a.`status`, fd.sub_code, fd.mis_sub_id, s.name, fd.grade, cs.aggr_id,
// if((select count(*) from summer_offered where id=fd.mis_sub_id and aggr_id=cs.aggr_id and session_year=a.session_yr)>0,'y','n') as active
// from final_semwise_marks_foil_freezed a 
// join final_semwise_marks_foil_desc_freezed fd on a.id=fd.foil_id
// join course_structure cs on cs.id=fd.mis_sub_id
// join subjects s on s.id=fd.mis_sub_id
// where a.admn_no='$id' and a.status='FAIL' and fd.grade='F'
// group by fd.mis_sub_id
// order by a.session_yr,a.semester";
      $sql="select v.*,g.mis_sub_id,g.sub_code,g.grade,s.name,so.aggr_id   from 
(select z.* from(
(
SELECT B.*
FROM (
SELECT a.status AS passfail, a.type as exam_type , NULL AS sem_code, a.semester AS sem,a.id,a.session_yr,a.`session`,'newsys' as rec_from
FROM final_semwise_marks_foil_freezed a
WHERE a.admn_no='$id'  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1') 
GROUP BY a.session_yr,a.session,a.semester,a.type, a.actual_published_on 
ORDER BY a.session_yr DESC, a.semester DESC,a.actual_published_on desc, a.tot_cr_pts DESC)B
GROUP BY B.sem) 
UNION all (
SELECT A.*
FROM (
SELECT a.passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.id,  a.ysession as session_yr, a.wsms as session,'oldsys' as rec_from
FROM tabulation1 a
WHERE a.adm_no='$id' and a.sem_code not like 'PREP%' 
GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms
ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
GROUP BY A.sem_code)
order by sem,passfail desc
)z group by z.sem )v


 join final_semwise_marks_foil_desc_freezed g on v.id=g.foil_id and (g.grade='F' or g.grade is  null)
 
left join summer_offered so on so.id=g.mis_sub_id
left join subjects s on s.id=g.mis_sub_id";
    $query=$this->db->query($sql);
    return $query->result();
    }
    //get surrent student details.
    function get_latest_stu_details($id){
      $sql="select * from reg_summer_form where admn_no='$id' order by semester desc limit 1";
      $query=$this->db->query($sql);
      return $query->result();
    }
	


}


?>
