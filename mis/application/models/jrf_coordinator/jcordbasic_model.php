<?php 
class Jcordbasic_model extends CI_Model
{	
	var $courses = 'courses';
    var $table_subject = 'subjects';
    private $form = 'reg_exam_rc_form';
    private $subject ='reg_exam_rc_subject';
    private $fee ='reg_exam_rc_fee';
	private  $form_undo='reg_exam_rc_undo_remark';
	private $form_jrf = 'reg_exam_jrf_sem_reg';
		
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getdata($sesYear,$session,$dept)
		{
		
			$q = "SELECT * FROM reg_exam_rc_form as r,user_details as ud WHERE r.admn_no = ud.id and ud.dept_id = '".$dept."' and r.course_id = 'jrf' and r.session_year= '".$sesYear."' and r.session = '".$session."'";
			$query = $this->db->query($q);
				if($query->num_rows() > 0)
				{
				return $query->result();
				}
				else return false;               
        }

       
	
	public function getDepartmentById($id)
		{
			return $this->db->select('name')->get_where('departments',array('id'=>$id))->row();
		}


		  
    function getCourses(){
        $q=$this->db->get($this->courses);
        return $this->getDropdown($q->result(),'id','name');
        
    }
    
    private function getDropdown($result,$v,$show){
        $a = array();
            foreach($result as  $v){
                $a[$v->id]=$v->name; 
            }
                return $a;
    }
       
                
                //subject Get
    function getSubject($course_id, $branch_id, $semester, $stuid, $ty = '', $dept_id = '',$group='') {
        $d = $this->getStudentAcdamicDetails($stuid);

        if (($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc') && ($semester == '1' || $semester == '2')) {
            $curaid = 'comm_comm_' . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
            $course_id = 'comm';
            $branch_id = 'comm';
        } else {
            $curaid = $course_id . "_" . $branch_id . "_" . $d[0]->enrollment_year . "_" . ($d[0]->enrollment_year + 1);
        }
        //  echo $curaid; die();
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
      //  echo $agr[0]->aggr_id;
        if ($dept_id == "")
            $dept_id = $this->session->userdata('dept_id');

         //echo $semester .",". $f .",". $dept_id;
        $data =array();
       if (($semester == '1' || $semester == '2') && ($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc')) {
           
           if($group)
           $data = $this->basic_model->get_subjects_by_sem($semester."_".$group,$f);
           
       }else{
        $data = $this->basic_model->get_subjects_by_sem_and_dept($semester, $f, $dept_id);
       }
          // print_r($data);
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
    
    
     function getSubjectviaAggrid($semester,$aggr_id,$dept,$g='',$ele=''){
            
            if($g){
                $semester = $semester."_".$g;
				$dept = 'cse';
            }
           //  echo $semester.",".$aggr_id.",".$dept;
            $this->load->model('course_structure/basic_model');
           $data=$this->basic_model->get_subjects_by_sem_and_dept($semester,$aggr_id,$dept);
         
          $i=0;
           foreach($data as $da){
			if($ele ==1){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}else{
			if(!is_float($this->get_numeric($da->sequence))){
			$d['subjects'][$i]['sequence']=$da->sequence;
			$d['subjects'][$i]['id']=$da->id;
			$d['subjects'][$i]['subject_id']=$this->basic_model->get_subject_details($da->id)->subject_id;
			$d['subjects'][$i]['name']=$this->basic_model->get_subject_details($da->id)->name;
			}
			}
			$i++;
		}
		
		// print_r($d);
		 return $d;
        }
        
       function getaggrIdbyCB($c,$b){
           $v=$c.'_'.$b.'%';
           $sql="select *,(right(a.aggr_id,4)+0) as ac,(right(a.aggr_id,9)) as bc from course_structure as a where a.aggr_id like ? group by aggr_id order by ac desc";
           $q=$this->db->query($sql,array($v));
         //
         //    echo $this->db->last_query();
           if($q->num_rows() >0){
               return $q->result();
           }
           return array();
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
		$query = $this->db->select('semester, MAX(form_id) as form_id')->get_where($this->form,array('admn_no'=>$stid,'current_semester'=>$sem))->result();
		
			$q =$this->db->get_where($this->form,array('admn_no'=>$stid,'form_id'=>$query[0]->form_id));
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
         
          function getFeedetails($fid){
              return $this->db->get_where($this->fee, array('form_id' => $fid))->row();
          }
          
          public function GetStudent($id, $fid) {
        $query = $this->db->query("SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->form . "` ON (`stu_academic`.`admn_no` = `" . $this->form . "`.`admn_no`) INNER JOIN `" . $this->fee . "` ON (`" . $this->form . "`.`form_id` = `" . $this->fee . "`.`form_id`) WHERE  `" . $this->form . "`.`admn_no` = '" . $id . "' and  `" . $this->form . "`.`form_id` ='" . $fid . "'");
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
			$q = "select * from ".$this->form." as sf, stu_details as sd, user_details as ud, ".$this->fee." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
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
			return true;
		}
                
      function getCourseDurationById($id){
            
           $q=$this->db->get_where($this->courses, array('id'=>$id)); 
           if($q->row()->duration)
               $sem = ($q->row()->duration * 2);
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
    
      function getjrfSubject(){
          $q=$this->db->get_where('subjects',array('type'=>'jrf'));
          if($q->num_rows() > 0 ){
              return $q->result();
          }
          return false;
      }
	  //============================JRF Semester Registration Starts======================
      public function getdata_jrf_sem_reg_rorm($sesYear,$session,$dept)
      {
    
      $q = "SELECT * FROM reg_exam_jrf_sem_reg as r,user_details as ud WHERE r.admn_no = ud.id and ud.dept_id = '".$dept."'  and r.session_year= '".$sesYear."' and r.session = '".$session."'";
      $query = $this->db->query($q);
        if($query->num_rows() > 0)
        {
        return $query->result();
        }
        else return false;               
        }
        public function GetStudent_jrf_sem_reg($id, $fid) {
        $query = $this->db->query("SELECT * FROM  `stu_details` INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)  INNER JOIN `" . $this->form_jrf . "` ON (`stu_academic`.`admn_no` = `" . $this->form_jrf . "`.`admn_no`)  WHERE  `" . $this->form_jrf . "`.`admn_no` = '" . $id . "' and  `" . $this->form_jrf . "`.`form_id` ='" . $fid . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function udateForm_jrf_sem_reg_form($form,$stu_id,$data){
               //echo  $form; die();
      $this->db->update($this->form_jrf, $data, array('form_id' => $form,'admn_no'=>$stu_id));
      return true;
    }

      //============================JRF Semester Registration Ends======================

}

?>