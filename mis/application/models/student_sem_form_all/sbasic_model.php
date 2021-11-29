<?php 
class Sbasic_model extends CI_Model
{
	private $sem_form = 'reg_regular_form';
    private $sem_fee= 'reg_regular_fee';	
	private $sem_start_date = 'reg_regular_openclose';
	private $sem_date_des = 'reg_regular_openclose_desc';
	private $branches = 'branches';
	private $cs_branches = 'cs_branches';
	private $Cbranch = 'stu_sem_change_branch';
    private $course = 'courses';
    private $summer_form = 'reg_summer_form';
    private $sem_subject = 'reg_regular_elective_opted';
    private $form_undo='reg_regular_undo_remark';
	
	//In Case of change Branch/
	private $stu_acdamic = 'stu_academic';
	private $ud='user_details';
	
	
	private $sem_summer_fee= 'reg_summer_fee'; 
	private $summer_form_undo='reg_summer_undo_remark';
	
	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function checkStudent($a_id,$session,$session_year){
                    if($session=='M'){$session = 'Monsoon';}else if($session='W'){$session ='Winter';}else{$session ='Summer';}
		$query = $this->db->get_where($this->sem_form,array('admn_no'=>$a_id,'session_year'=>$session_year,'session'=>$session));          //echo $query->num_rows(); die();
		//$data=$query->result_array();
			if($query->num_rows() > 0){
                        return false;
                        }
			else{
                        return true;
                        }
	}
	public function checkStudentWithDepartment($a_id,$session,$session_year,$department,$course,$semester){
                    if($session=='M'){$session = 'Monsoon';}else if($session='W'){$session ='Winter';}else{$session ='Summer';}
		$query = $this->db->get_where($this->sem_form,array('admn_no'=>$a_id,'session_year'=>$session_year,'session'=>$session));          //echo $query->num_rows(); die();
		//$data=$query->result_array();
				 echo $this->db->last_query(); die();
			if($query->num_rows() > 0){

                        return false;
                        }
			else
			{
				

				 if($department=='all') return true;
				 else if($department== $this->session->userdata('dept_id') )
				 {
				 	if($course == 'all') return true;
				 	else if($course== $this->session->userdata('course_id'))
				 	{

				 		if($semester == $this->session->userdata('semester')|| $semester == 0 )
				 			  return true;
				 	    else
				 	    	  return false;
				 	}	
				 	else
                        return false;
				 }
                 else
                 	 	return false;
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
	public function udate_ocDate($data,$id=1){
			$this->db->update($this->sem_start_date, $data, array('id' => $id));
                        //echo $this->db->last_query();die();
			return true;
		}
		
	public function getOcdate($id=1){
			return $this->db->get_where($this->sem_start_date, array('id' => $id))->result();
		}
    public function getOcdatedes($id=1){
			return $this->db->query("SELECT * FROM reg_regular_openclose_desc as b WHERE `des_id`=(SELECT max(a.des_id) FROM reg_regular_openclose_desc as a where date_id=?) and date_id=?",array($id,$id))->result();
		}
     public function getdateRecord($id=1){
        return $this->db->query("select * from reg_regular_openclose as a join reg_regular_openclose_desc as b on a.id=b.date_id where date_id='$id' order by des_id desc")->result();
    }  
    function getdateRecord_new(){
        return $this->db->query("select * from reg_regular_openclose_desc order by des_id desc")->result();
    }
	public function checkDate($id=1){
			$sdate = $this->getOcdate($id);
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
			//echo $this->db->last_query(); die();
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
			if($ses){
				$q.=" and sf.session='".$ses."'";
			}else{
				$q.= " and sf.session='Winter'";
			}
			if($sesY)
				$q.=" and sf.session_year='".$sesY."'";
				$q.=" and sf.hod_status='1'";
				$q.=" order by sf.semester";
				
			$query = $this->db->query($q);
			if($query->num_rows() > 0){
					return $query->result();
			}else return false;
	}
	
	 // @author: RituRaj  @dated:27-5-19    @desc : undo/reset option addition -->
	public function hod_view_student_summer($id,$fid){
                    $q="SELECT *
FROM
  `stu_details`
  INNER JOIN `stu_academic` ON (`stu_details`.`admn_no` = `stu_academic`.`admn_no`)
  INNER JOIN `user_details` ON (`stu_academic`.`admn_no` = `user_details`.`id`)
  INNER JOIN `".$this->summer_form."` ON (`stu_academic`.`admn_no` = `".$this->summer_form."`.`admn_no`)
  INNER JOIN `".$this->sem_summer_fee."` ON (`".$this->summer_form."`.`form_id` = `".$this->sem_summer_fee."`.`form_id`)
WHERE
  `".$this->summer_form."`.`admn_no` = '".$id."' and  `".$this->summer_form."`.`form_id` ='".$fid."'";
                   // echo $q;
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
	 // @author: RituRaj  @dated:27-5-19    @desc : undo/reset option addition -->
		public function udate_hod_summer($form,$stu_id,$data, $sem=''){
                       /// echo $sem; die();
			$this->db->update($this->summer_form, $data, array('form_id' => $form,'admn_no'=>$stu_id));
                      /*  if($data['acad_status'] == '1' || $data['acad_status'] == '3' ) {
                            $t['semester'] = $sem;
							$t['session'] = 'Summer';
                            $this->udateCourseBranch($stu_id, $t);
                        }*/
			return true;
                        
		}
	
	
	public function udate_hod($form,$stu_id,$data, $sem=''){
                       /// echo $sem; die();
			$this->db->update($this->sem_form, $data, array('form_id' => $form,'admn_no'=>$stu_id));
                        if($data['acad_status'] == '1' || $data['acad_status'] == '3' ) {
                            $t['semester'] = $sem;
                            $this->udateCourseBranch($stu_id, $t);
                        }
			return true;
                        
		}
		
		public function udateCBStatus($form,$data){
			$this->db->update($this->Cbranch, $data, array('form_id' => $form));
			return true;
		}
	
		public function udateCourseBranch($stu_id, $data) {
          $projectGuide_data =null;
        if ($data['semester'] != '') {
            $this->db->update($this->stu_acdamic, $data, array('admn_no' => $stu_id));
           		
            $projectGuide_data = $this->get_records_from_id("project_guide", "", "admn_no", $stu_id, "is_status", "Active");
		
            if (!empty($projectGuide_data)) {
                if ($projectGuide_data["semester"] != $data["semester"]) {
                    $updateArrayData = array();
                    $updateArrayData["id"] = $projectGuide_data["id"];
                    $updateArrayData["is_status"] = "Inactive";
                    $updateArrayData["modify_by"] = $this->session->userdata('id');
                    $this->add_update_record('project_guide', $updateArrayData, "id");
                    $is_even = $data["semester"] % 2;
                    if ($is_even == 0) {//means 2,4,6,8
                        $new_academic_year = $projectGuide_data["academic_year"];
                    } else {
                        $customData = explode("-", $projectGuide_data["academic_year"]);
                        $new_academic_year = $customData[1] . "-" . ($customData[1] + 1);
                    }

                    $insertArrayData = array();
                    $insertArrayData["academic_year"] = $new_academic_year;
                    $insertArrayData["guide"] = $projectGuide_data["guide"];
                    $insertArrayData["co_guide"] = $projectGuide_data["co_guide"];
                    $insertArrayData["admn_no"] = $projectGuide_data["admn_no"];
                    $insertArrayData["type"] = $projectGuide_data["type"];
                    $insertArrayData["sole_shared"] = $projectGuide_data["sole_shared"];
                    $insertArrayData["department"] = $projectGuide_data["department"];
                    $insertArrayData["course"] = $projectGuide_data["course"];
                    $insertArrayData["semester"] = $data["semester"];
                    $insertArrayData["branch"] = $projectGuide_data["branch"];
                    $insertArrayData["status"] = $projectGuide_data["status"];
                    $insertArrayData["is_status"] = $projectGuide_data["is_status"];
                    $insertArrayData["created_by"] = $this->session->userdata('id');
                    $insertArrayData["created_date"] = date("Y-m-d H:i:s");
                    $this->add_update_record('project_guide', $insertArrayData);
                }
            }

            return true;
        }
        return false;
    }

    public function add_update_record($table, $data = array(), $id_name = '') {
        $this->db->set($data);
        if ($id_name) {
            $this->db->where($id_name, $data[$id_name]);
            $query = $this->db->update($table);
        } else {//adding record in table
            $query = $this->db->insert($table);
        }
    }

    public function get_records_from_id($table_name, $request_fields = "", $id_name, $id_value, $id_name1, $id_value1) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        $this->db->select($str);
        $this->db->where($id_name, $id_value);
        $this->db->where($id_name1, $id_value1);
        $query = $this->db->get($table_name);
        return $query->row_array();
    }
		
		
		public function udateDept($stu_id,$data){
			$this->db->update($this->sem_form, $data, array('admn_no'=>$stu_id));
			return true;
		}
		
	public function formrResponse($stid,$sem){
		
		$query = $this->db->select_max('form_id')->get_where($this->sem_form,array('admn_no'=>strtoupper($stid),'semester'=>$sem))->result();
		
			$q =$this->db->get_where($this->sem_form,array('admn_no'=>$stid,'semester'=>$sem,'form_id'=>$query[0]->form_id));
			
			if($q->num_rows() > 0)
				return $q->result();
			
				return false;
		}
		public function lateFeeRe($fid){
			
			$q =$this->db->get_where($this->sem_fee,array('form_id'=>$fid));	
					
			if($q->num_rows() > 0){
				if($q->row()->late_receipt_path != ""){
					return true;
				}
			return false;
			
			}
			return true;
		}
		
  public function getCourseById($id){
	  	return $this->db->select('name')->get_where('courses',array('id'=>$id))->row();
	  }
	  
	  public function getCourseById1($id){
	  	return $this->db->select('name')->get_where('cs_courses',array('id'=>$id))->row();
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
	  	$query = $this->db->get_where($this->sem_form,array('hod_status'=>'1','acad_status'=>'1','admn_no'=>$id));
		 
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
            //$qu=$this->db->select('form_id')->get_where($this->sem_form,array('admn_no'=>$stu));
			$this->db->order_by("timestamp", "desc");
			$this->db->limit('1');
             $qu=$this->db->select('form_id')->get_where($this->sem_form,array('admn_no'=>$stu,'session_year'=>'2019-2020','session'=>'Monsoon'));
			 
            //echo $this->db->last_query();die();
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
		
		  function formUndo($data){
           $this->db->insert($this->form_undo,$data);
           if($this->db->_error_number()){
               return false; 
           }
           return true;
       }
	   
	    // @author: RituRaj  @dated:27-5-19   @desc : undo/reset option addition -->
	   function summerformUndo($data){
           $this->db->insert($this->summer_form_undo,$data);
           if($this->db->_error_number()){
               return false; 
           }
           return true;
       }
	   
       
   function getFee($sy,$s,$cid,$sem,$cate){
     
       $q=$this->db->get_where('stu_fee_database_regular',array('session_year'=>$sy,'session'=>$s,'semester'=>$sem,'course_id'=>$cid,'category'=>$cate));
       if($q->num_rows() > 0){
           return $q->row();
       }
       return false;
   }
   
   function getCommGrp($id){
       $q=$this->db->query('select * from stu_section_data a join section_group_rel b on a.section = b.section where a.admn_no=?',array($id));
       if($q->num_rows() > 0)
           return $q->row();
       return false;
   }
   
   function getStuName($id){
	    $q=$this->db->get_where('user_details',array('id'=>$id));
	    if($q->num_rows() > 0)
           return $q->row();
       return false;
   }

    function get_group_no($id)
   {
   	      $qq='';
   	      $q=$this->db->query("SELECT `group` from section_group_rel,stu_section_data where
   	      	section_group_rel.section=stu_section_data.section and section_group_rel.session_year=stu_section_data.session_year
   	      	and admn_no like'$id'")->result();
   	      if(!empty($q))
   	      return $q[0]->group;
   	      return $qq;
   }
    function isprep($id)
    {
    	$q=$this->db->query("SELECT * from stu_academic where auth_id='prep'
   	      	and admn_no like'$id'");
    	if($q->num_rows()>0)
    		return true;
    	return false;
    }
	function getCourseDurationByIdCS($id){
            
           $q=$this->db->get_where('cs_courses', array('id'=>$id));            
           if($q->row()->duration)
             $sem = ($q->row()->duration * 2);            
              return $sem;
        
        }
		
	function getSpecific($admn_no,$sy){
		$qu="select * from reg_open_specific a where a.session_year=? and a.admn_no=? order by a.id desc limit 1";
		 $q=$this->db->query($qu,array($sy,$admn_no));
		// echo $this->db->last_query();
		 if($q->num_rows() > 0)
			 return $q->row();
		 return false;
	}
	
	function updateSpecific($data,$con){
		$this->db->update('reg_open_specific',$data,$con);
			return true;
	}
        
        
        //@anuj get hod fro student starts
        function get_stu_hod($id){
            $sql = "select a.id from user_auth_types a inner join user_details b on a.id=b.id 
where a.auth_id='hod' and b.dept_id=(select a.dept_id from user_details a where a.id=?)";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        
        function getFacultyName($id){
            
            $sql = "select concat_ws(' ',a.first_name,a.middle_name,a.last_name)as faculty,a.category,a.physically_challenged from user_details a where a.id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }
        
        function get_elective_sub($id){
            
            $sql = "select a.hod_status from reg_regular_form a where a.admn_no=? and a.session_year='2018-2019' and a.`session`='Monsoon'";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->hod_status;
        } else {
            return false;
        }
            
        }
		
		function check_parttime_jrf($id){
            
            $sql = "select * from stu_academic where admn_no=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
            
        }
        
        
        //@anuj get hod fro student ends
}

?>