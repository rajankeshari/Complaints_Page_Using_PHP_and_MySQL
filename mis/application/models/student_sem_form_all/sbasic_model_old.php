<?php 
class Sbasic_model extends CI_Model
{
	var $sem_form = 'reg_regular_form';
        var $sem_fee= 'reg_regular_fee';
	var $sem_start_date = 'reg_regular_openclose';
	var $sem_date_des = 'reg_regular_openclose_desc';
	var $branches = 'branches';
	var $cs_branches = 'cs_branches';
	var $Cbranch = 'stu_sem_change_branch';
        var $course = 'courses';
        var $summer_form = 'reg_summer_form';
        var $sem_subject = 'reg_regular_elective_opted';
	
	//In Case of change Branch/
	private $stu_acdamic = 'stu_academic';
	private $ud='user_details';
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function checkStudent($a_id,$session,$session_year){
            
                    if($session=='M'){$session = 'Monsoon';}else if($session='W'){$session ='Winter';}else{$session ='Summer';}
		$query = $this->db->get_where($this->sem_form,array('admn_no'=>$a_id,'session_year'=>$session_year,'session'=>$session));          //echo $query->num_rows(); die();
		//print_r($query->result()); die();
			if($query->num_rows() > 0){
                        return false;
                        }
			else{
                        return true;
                        }
	}
	/*public function checkStudentRepeat($a_id,$semeter){
		$query = $this->db->select('re_id')->get_where($this->sem_form,array('admn_no'=>$a_id,'semester'=>$semeter));
		$data=$query->result_array();
			if(empty($data))
			return false;
			else
			return $data;
	}*/
	//date set///
	public function udate_ocDate($data){
			$this->db->update($this->sem_start_date, $data, array('id' => '1'));
			return true;
		}
		
	public function getOcdate(){
			return $this->db->get_where($this->sem_start_date, array('id' => '1'))->result();
		}
    public function getOcdatedes(){
			return $this->db->query("SELECT * FROM `reg_regular_openclose_desc` WHERE `des_id`=(SELECT max(`des_id`) FROM `reg_regular_openclose_desc`) and date_id=1")->result();
		}
             
	public function checkDate(){
			$sdate = $this->getOcdate();
			$sd = $sdate[0]->opendate;
			$cd = $sdate[0]->closedate;
			$cdate = strtotime(date('Y-m-d'));
			$sdate = strtotime($sd);
			$closedate = strtotime($cd);
			if( $cdate >= $sdate && $cdate <= $closedate  ){
				
					return true;
			}
			return false;
		}
		
		public function insertDateDes($data){
				
				$this->db->insert($this->sem_date_des, $data);
					if($this->db->_error_message()){
					return $this->db->_error_message();
					}else{
						return true;	
						}
			}

	/// End Date Set////	
	
	public function hod_vaise_student($dep,$cid='',$bid='',$sid='',$ses='',$sesY=''){
			
		$q="select * from ".$this->sem_form." sf, stu_details as sd, user_details as ud, ".$this->sem_fee." as srf  where dept_id='".$dep."' and srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
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
			$q = "select * from ".$this->sem_form." as sf, stu_details as sd, user_details as ud, ".$this->sem_fee." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
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
	
	public function hod_view_student($id,$fid){
                    $q="SELECT *
FROM
  `stu_details`
  INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)
  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)
  INNER JOIN `".$this->sem_form."` ON (`stu_academic`.`admn_no` = `".$this->sem_form."`.`admn_no`)
  INNER JOIN `".$this->sem_fee."` ON (`".$this->sem_form."`.`form_id` = `".$this->sem_fee."`.`form_id`)
WHERE
  `".$this->sem_form."`.`admn_no` = '".$id."' and  `".$this->sem_form."`.`form_id` ='".$fid."'";
                   // echo $q;
			$query = $this->db->query($q);
			if($query->num_rows() > 0){ 
					return $query->result();
			}else return false;
	}
	
	public function udate_hod($form,$stu_id,$data, $sem=''){
                       /// echo $sem; die();
			$this->db->update($this->sem_form, $data, array('form_id' => $form,'admn_no'=>$stu_id));
                        if($data['acad_status'] == '1'){
                            $t['semester'] = $sem;
                            $this->udateCourseBranch($stu_id, $t);
                        }
			return true;
                        
		}
		
		public function udateCBStatus($form,$data){
			$this->db->update($this->Cbranch, $data, array('form_id' => $form));
			return true;
		}
	
		public function udateCourseBranch($stu_id,$data){
                          if($data['semester'] != ''){
			$this->db->update($this->stu_acdamic, $data, array('admn_no'=>$stu_id));
			return true;
                        }
                        return false;
		}
		
		public function udateDept($stu_id,$data){
			$this->db->update($this->sem_form, $data, array('admn_no'=>$stu_id));
			return true;
		}
		
	public function formrResponse($stid,$sem){
		
	$query = $this->db->select_max('form_id')->get_where($this->sem_form,array('admn_no'=>strtoupper($stid),'semester'=>$sem));
                if($query->num_rows() > 0){
		$query=$query->result();
			$q =$this->db->get_where($this->sem_form,array('admn_no'=>$stid,'semester'=>$sem,'form_id'=>$query[0]->form_id));
			
			if($q->num_rows() > 0){
				return $q->result();
                        }
				return false;
                }else{
                    return false;
                }
		}
		/*public function lateFeeRe1($fid){
			
			$q =$this->db->get_where($this->sem_form,array('form_id'=>$fid));		
			if($q->num_rows() > 0){
			return false;
			}
			return true;
		}*/
		
  public function getCourseById($id){
	  	return $this->db->select('name')->get_where('courses',array('id'=>$id))->row();
	  }
  
  public function getBranchById($id){
	 // echo $id; die();
	 	return $this->db->get_where($this->branches,array('id'=>$id))->row();
			
	  }
	   public function getBranchById1($id){
	 // echo $id; die();
	 	return $this->db->get_where($this->cs_branches,array('id'=>$id))->row();
			
	  }
	
	public function getDepatmentById($id){
			return $this->db->select('name')->get_where('departments',array('id'=>$id))->row();
		}
		
  public function getApprovedFormByStudent($id){
	  	$query = $this->db->select('form_id,semester')->get_where($this->sem_form,array('hod_status'=>'1','acad_status'=>'1','admn_no'=>$id));
		 
		if($query->num_rows() > 0){
			return $query->result_array();
			}
	  
	  }
	  
  public function getCbByfromId($id){
      return false;
	  	$query = $this->db->get_where($this->Cbranch,array('form_id'=>$id));
	  		
	  	if($query->num_rows() > 0){
	  		return $query->result_array();
	  	}
	  	 
	  }
	  
	  public function getHodbydept($dept){
	  	
	  	$q=$this->db->query("SELECT ud.id FROM user_details AS ud JOIN user_auth_types AS `uat` ON ud.id=`uat`.`id` WHERE dept_id='".$dept."'");
	  	if($q->num_rows() > 0){
	  	return	$q->result();
	  	}
	  }
	  
	  public function getArbyAuth(){
	  
	  	$q=$this->db->query("SELECT id FROM user_auth_types WHERE auth_id='acad_ar'");
	  	if($q->num_rows() > 0){
	  		return	$q->result();
	  	}
	  }
       
          function getCourseDurationById($id){
            
           $q=$this->db->get_where($this->course, array('id'=>$id)); 
           if($q->row()->duration)
               $sem = ($q->row()->duration * 2);
           return $sem;
        
        }
        function getMaxRegFromByStuId($id){
            $q = $this->db->query('select * from reg_regular_form where form_id =(select max(form_id) from reg_regular_form where admn_no="'.$id.'")');
            if($q->num_rows() > 0){
                return $q->row();
            }
            return false;
        }
	
        function checkSummer($id){
            $q=$this->db->get_where($this->summer_form,array('hod_status'=>'1',"acad_status"=>'1','admn_no'=>$id));
            if($q->num_rows() > 0)

            return false;
                    return true;
        }
        
        public function lateFeeRe($fid){
			
			$q =$this->db->get_where($this->sem_form,array('form_id'=>$fid));		
			if($q->num_rows() > 0){
			return false;
			}
			return true;
		}

              
		 /// Change Branch ///
        
        public function hod_vaise_studentCB($dep,$cid='',$bid='',$sid='',$ses='',$sesY=''){
			
		$q="select * from ".$this->sem_form." sf, stu_details as sd, user_details as ud, ".$this->sem_fee." as srf  where dept_id='".$dep."' and srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
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
			$q.=" and sf.reg_type='cb' order by sf.semester";
			
			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
	}
	
	public function acdamic_vaise_studentCB($did='',$cid='',$bid='',$sid='',$ses='',$sesY=''){
			$q = "select * from ".$this->sem_form." as sf, stu_details as sd, user_details as ud, ".$this->sem_fee." as srf  where srf.form_id = sf.form_id and sd.admn_no = sf.admn_no and ud.id = sf.admn_no";
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
				$q.=" and sf.reg_type='cb' order by sf.semester";
				
			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
	}
        
        
       function getcurrentId($course_id,$branch_id,$semster,$stuid){
           $d=$this->getStudentAcdamicDetails($stuid);
			$this->load->model('course_structure/basic_model');
			if(($course_id == 'be' || $course_id == 'b.tech' || $course_id=='dualdegree' ||  $course_id=='int.msc.tech' || $course_id=='int.m.tech' ||  $course_id== 'int.m.sc' ) && ($semster =='1' || $semster=='2')){
				$curaid = 'comm_comm_'.$d->enrollment_year."_".($d->enrollment_year+1);
                                $agr =$this->basic_model->get_latest_aggr_id('comm','comm',$curaid);
			}else{
				$curaid = $course_id."_".$branch_id."_".$d->enrollment_year."_".($d->enrollment_year+1);
                                $agr =$this->basic_model->get_latest_aggr_id($course_id,$branch_id,$curaid);
			}
                $r='<option value="">Select Year</option>';
                foreach($agr as $a){
                    $r .= '<option value="'.$a->aggr_id.'">'.$a->aggr_id.'</option>';
                }	          
                echo $r;
        }
        
        function getStudentAcdamicDetails($id){
			return $this->db->get_where('stu_academic',array('admn_no'=>$id))->row();
			}
                        
        function getformId($stu){
            $qu=$this->db->select('form_id')->get_where($this->sem_form,array('admn_no'=>$stu));
            if($qu->num_rows() > 0){
                return $qu->row()->form_id;
            }
        }
        
        function checkSemSubject($id){
            $q=$this->db->get_where($this->sem_subject,array('form_id'=>$id));
            if($q->num_rows() > 0){
                return true;
            }else{
                return false;
            }
        }

}
?>