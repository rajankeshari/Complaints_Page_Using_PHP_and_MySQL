<?
/**
* 
*/
class Swimming_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_category_by_auth_gender($auth='',$sex='',$check_rgistration_status=false)
	{   
		if($check_rgistration_status)
		{
			$category=$this->db->select('code,name')
			->where(array('auth'=>$auth,'gender'=>$sex,'reg_status'=>2))
			->where_not_in('category',['mixed'])
			->get('swimming_group');
			//print_r($this->db->last_query());
		}
		else
		{
			$category=$this->db->select('code,name')
			->where(array('auth'=>$auth,'gender'=>$sex))
			->get('swimming_group');
		}

		return($category->result_array());				   
	}

	public function check_user_id($user_id='',$group_code='')
	{
		if(!empty($user_id) && !empty($group_code))
		{    
			
			$res=$this->db->where(['id'=>$user_id])->get('user_details')->result_array();
			
			
				if(count($res)===1)
				{
						if($this->swim->check_already_applied($user_id))
						{
							return 2;
						}

						return 3;
				}
				else
				{
					return 1;
				}
		}
		return 0;
	}
	public function get_user_details($user_id='')
	{
		$result=$this->db->select('U.id as user_id,U.salutation, U.first_name,U.last_name,U.middle_name,U.dob,U.photopath,U.sex,D.name as dept_name')
		->from('user_details as U')
		->where(array('U.id'=>$user_id))
		->join('departments as D','U.dept_id=D.id','left')
		->get();
		if($result&&count($result)===1)
			return($result->result_array()[0]);
		else
			return array();
	}

	public function get_all_group($cond=array(),$not_in='')
	{	
		if(is_array($cond) && !empty($cond))
		{
			$this->db->where($cond);
			if(!empty($not_in) && is_array($not_in))
				$this->db->where_not_in('category',$not_in);
			$result=$this->db->get('swimming_group');
		}
		else
		{
			$result=$this->db->get('swimming_group');
		}

		if($result)
			return($result->result_array());
		else
			return array();
	}


	public function get_group_by_code($group_code='')
	{   
		if(!empty($group_code))
		{
			$result=$this->db->where(array('code'=>$group_code))->get('swimming_group');

			if($result)
				return($result->result_array()[0]);
			else
				return array();
		}
	}

	public function update_group($data=array(),$primary=array(),$slot_data=array(),$code='')
	{	
		if(is_array($data)&&is_array($primary)&&!empty($data)&&!empty($primary))
		{	
			$this->db->trans_begin();

			$this->db->update('swimming_group',$data,$primary);

			if(!empty($slot_data)&&!empty($code))
			{
				$this->db->update('swimming_slot',$slot_data,array('group_code'=>$code));
			}

			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();

			        return false;
			}
			else
			{
			        $this->db->trans_commit();
			        return true;
			}
		}
		return false;
	}

	public function insert_medical_data($data=array())
	{
		if(is_array($data)&&!empty($data))
		{		
			$query=$this->db->insert('swimming_medical_report',$data);
			if($query)
			{   $user_id=$this->CI->session->userdata('id');
				$result=$this->db
				             ->select('id')
				             ->where(array('user_id'=>$user_id))
				             ->order_by('id','DESC')
				             ->limit(1)
				             ->get('swimming_medical_report');
				if($result)
				{
					return array('status'=>true,'id'=>$result->result_array()[0]['id']);             
				}
			}
		}
		return array('status'=>false);
	}

	public function insert_student_data($data=array())
	{
		if(is_array($data) && !empty($data))
		{

			$primary=array("code"=>$data['group_code']);

			$this->db->trans_begin();

			$this->db->set('capacity_available', 'capacity_available-1', FALSE)
					 ->set('total_registered','total_registered+1',FALSE)
				   	 ->where($primary)
					 ->update('swimming_group');

			$this->db->insert('swimming_student_details',$data);

			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();

			        return false;
			}
			else
			{
			        $this->db->trans_commit();
			        return true;
			}
		}
		return false;
	}

	public function get_swimming_data_student($user_id='')
	{
		if(!empty($user_id))
		{
			$result=$this->db->select('U.date_applied,U.transaction_id,U.receipt_path,U.amount,U.status,U.remarks,U.id,G.name,S.group_code,S.slot_name')
							 ->from('swimming_student_details as U')
						 	 ->where(array('user_id'=>$user_id))
						 	 ->join('swimming_group as G','G.code=U.group_code','left')
						 	 ->join('swimming_slot S' ,'S.id=U.slot_id','left')
						 	 ->get();
						 	 //print_r($this->db->last_query());
			if($result)
			{
				return($result->result_array());
			}
			else
				return array();

		}
	}

	public function check_already_applied($user_id='')				
	{
		if(!empty($user_id))
		{
			$result=$this->db->select('id')->where(array('user_id'=>$user_id))
							 ->where_in('status',array(1,2))
							 ->get('swimming_student_details');
			//print_r($result->result_array());				 
		    if($result&& count($result->result_array())===0)
		     {
		     	return false;
		     }					 
		}

		return true;
	}

	public function insert_slot($data=array())
	{
		if(is_array($data)&&!empty($data))
		{
			$query=$this->db->insert('swimming_slot',$data);

			if($query)
				return true;
		}

		return false;
	}

	public function get_slot()	
	{
		$result=$this->db->from('swimming_slot as S')
						 ->join('swimming_group as G','S.group_code=G.code','left')
						 ->get();
		if($result)
			return($result->result_array());
		else
			return array();
	}

	public function get_slot_by_id($slot_id='')
	{	
		if(!empty($slot_id))
		{

			$result=$this->db->where(array('id'=>$slot_id))->get('swimming_slot');
			if($result)
				return($result->result_array()[0]);
			else
				return array();
		}
	}

	public function update_slot($data=array(),$id='')	
	{	
		//print_r($data);
		if(is_array($data)&&!empty($data)&&!empty($id))
		{
			$query=$this->db->update('swimming_slot',$data,array('id'=>$id));
			if($query)
				return true;
		}
		return false;
	}

	public function remove_slot($slot_id='')
	{
		if(!empty($slot_id))
		{  
			$query=$this->db->delete('swimming_slot',array('id'=>$slot_id));
			if($query)
				return true;
		}
		return false;
	}

	public function get_slot_by_groupcode($cond=array())
	{
		if(is_array($cond) && !empty($cond))
		{
			$result=$this->db->where($cond)->get('swimming_slot');
					 
			if($result)
				return($result->result_array());
		}
		return array();
	}
	public function get_student_applications($cond=array(1,2,3,4,5))
	{
		$result=$this->db->select('S.user_id,S.id, U.salutation, U.first_name,U.last_name,U.middle_name,G.name,S.date_applied,S.remarks,S.status,S.amount,S.transaction_id')
		 ->from('swimming_student_details as S')
	 	 ->join('swimming_group as G','G.code=S.group_code','left')
		 ->join('user_details as U','U.id=S.user_id','left')
		 ->where_in('S.status',$cond)
		 ->get();

		if($result)
			return($result->result_array());
		else
			return array();
	}

	public function get_application_student($id='')
	{
		if(!empty($id))
		{
				$result=$this->db->select()
							 ->from('swimming_student_details as S')
							 ->where(array('S.id'=>$id))
						 	 ->join('swimming_group as G','G.code=S.group_code','left')
							 ->join('user_details as U','U.id=S.user_id','left')
							 ->join('swimming_medical_report as M','M.id=S.report_id','left')
							 ->get();
			//	print_r($this->db->last_query())
				if($result)
					return($result->result_array()[0]);
		}
		return array();
	}

	public function update_application($table='',$data=array(),$primary=array(),$group_code="")
	{
		if(!empty($table) && is_array($data) && is_array($primary) && !empty($data) &&!empty($primary))
		{	
			$this->db->trans_begin();
			$query=$this->db->update($table,$data,$primary);

			if(!empty($group_code))
			{
				$this->db->set('capacity_available', 'capacity_available+1', FALSE)
						 ->set('total_registered','total_registered-1',FALSE)
					   	 ->where(array('code'=>$group_code))
						 ->update('swimming_group');
			}

			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				return false;
			}
			else
			{
				$this->db->trans_commit();
				return true;
			}
		}
		return false;
	}

	public function filter_student_application($cond=array())
	{
		if(is_array($cond))
		{
			$result=$this->db->select('S.user_id,S.id, U.salutation, U.first_name,U.last_name,U.middle_name,G.name,S.date_applied,S.slot_assigned,S.remarks,S.status,S.amount,S.transaction_id,A.semester,A.course_id,T.group_code,T.slot_name,T.id as slot_id,T.days,T.start_time,T.end_time')
			 ->from('swimming_student_details as S')
			 ->where($cond)
		 	 ->join('swimming_group as G','G.code=S.group_code','left')
			 ->join('user_details as U','U.id=S.user_id','left')
			 ->join('swimming_slot as T','T.id=S.slot_id','left')
			 ->join('stu_academic as A','A.admn_no=S.user_id','left')
			 ->get();

			if($result)
				return($result->result_array());
			else
				return array();
		}
	}

	public function get_alloted_capacity($table='',$slot_id='')
	{
		if(!empty($slot_id) && !empty($table))
		{
			$result=$this->db->select('count(*) as total')
			             ->where(array('slot_id'=>$slot_id))
			             ->get($table);
			if($result)
			  return array('status'=>true,'data'=>$result->result_array()[0]['total']);             
		}
		return array('status'=>false);
	}

	public function allocate_applicant($table='',$user_data=array(),$applicant=array(),$slot_data=array(),$slot_id='')
	{
		if(!empty($table)&&is_array($user_data)&&is_array($applicant)&&is_array($slot_data)&&!empty($user_data)&&!empty($applicant)&&!empty($slot_data)&&!empty($slot_id))
		{
			$primary=array("id"=>$slot_id);

			$this->db->trans_begin();

			$this->db->where_in('id',$applicant)->update($table,$user_data);

			$results = $this->db->where_in('id',$applicant)->get($table)->result_array();

			$this->db->update('swimming_slot',$slot_data,$primary);

			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();

			        return false;
			}
			else
			{
				// var_dump($results);
				// die();
					foreach ($results as $key) {
						$this->notification->notify($key['user_id'],'stu','Swimming Section','You have been allocated to swimming slot '.$slot_id,'swimming/student_registration/view_status','swimming/student_registration/view_status');
						$this->notification->notify($key['user_id'],'emp','Swimming Section','You have been allocated to swimming slot '.$slot_id,'swimming/emp_registration/view_status','swimming/emp_registration/view_status');
					}
			        $this->db->trans_commit();
			        return true;
			}
		}
		return false;
	}

	public function deallocate_applicant($table='',$id='',$slot_id)
	{	
		if(!empty($table)&&!empty($id)&&!empty($slot_id))
		{
			$this->db->trans_begin();
			$this->db->update($table,array('slot_id'=>NULL,'slot_assigned'=>1,"emp_id"=>$this->CI->session->userdata('id')),array('id'=>$id));
			$this->db->set('alloted','alloted-1',FALSE)
			         ->set('available',1,FALSE)
			         ->where(array('id'=>$slot_id))
			         ->update('swimming_slot');
			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();

			        return false;
			}
			else
			{

			        $this->db->trans_commit();
			        return true;
			}
		}
		return false;
	}


	public function get_stu_card($id='')
	{
		if(!empty($id))
		{
			$result=$this->db->select('U.salutation,C.id,U.first_name,U.last_name,U.middle_name,M.blood_group,U.photopath,
									U.sex,U.dob,C.slot_id,C.user_id,S.days,S.start_time,S.end_time,S.slot_name,G.name')
							 ->from('swimming_student_details as C')
							 ->where(array('C.id'=>$id))
							 ->join('swimming_medical_report as M','M.id=C.report_id','left')
							 ->join('swimming_slot as S','S.id=C.slot_id','left')
							 ->join('user_details as U','C.user_id=U.id','left')
							 ->join('swimming_group as G','G.code=S.group_code','left')
							 ->get();
		    if($result)		
		    return $result->result_array()[0];			 

		}
		return array();
	}
//--------------------------------------------------------
	public function get($table,$cond=[])
	{
		$res = $this->db->where($cond)->get($table);
		return $res->result_array();
	}

	public function update($table,$values,$cond=[]){
		$res = $this->db->where($cond)->update($table,$values);
		return $res;
	}

	public function insert($table,$values){
		$res = $this->db->insert($table,$values);
		return $res;
	}

	public function get_emp_details($id){
		$ret = $this->db->select('UD.id,UD.salutation,UD.first_name,UD.middle_name,UD.last_name,D.name as dept_name,DESIG.name as design_name,UA.contact_no as mobile,EBD.office_no as internal_tel,UA.line1,UA.line2,UA.city,UA.state,UA.pincode,UA.country')
					->from('user_details as UD')
					->where(['UD.id'	=>	$id,
							 'UA.type'	=>	'present'
							])
					->join('departments as D','UD.dept_id = D.id')
					->join('emp_basic_details as EBD','EBD.emp_no = UD.id')
					->join('designations as DESIG','DESIG.id = EBD.designation')
					->join('user_address as UA','UA.id = UD.id')
					->get()->result_array()[0];


		$ret['user_name'] = $ret['salutation']." ".$ret['first_name']." ".$ret['middle_name']." ".$ret['last_name'];
		$ret['res_address'] = $ret['line1'].",".$ret['line2'].",".$ret['city'].",".$ret['state'].",".$ret['pincode'].",".$ret['country'];
		// prettyDump($ret);


		return $ret;
	}

	public function check_emp_status($id='')
	{
		if(!empty($id) && !is_null($id))
		{
			$res=$this->db->where(array('user_id'=>$id,'s_no'=>'0'))->where_in('status',array(1,2))->get('swimming_emp_details')->result_array();
			//print_r($res);
			if(count($res)===0)
				return true;
		}
		return false;
	}
	public function get_emp_depend($id)
	{	
		$sql="

			SELECT `F`.`name`,`F`.`sno`
			FROM `emp_family_details` as F where `F`.`sno` NOT IN(SELECT `G`.`sno`
			FROM (`emp_family_details` as G)
			 JOIN `swimming_emp_details` as E  ON `E`.`user_id`=`G`.`emp_no` and `E`.`s_no`=`G`.`sno` and `E`.`status` in (1,2)
			WHERE `G`.`emp_no` =  ?
			) AND `F`.`emp_no` =  ?

			";

		$p=$this->db->query($sql,array($id,$id))->result_array();
		//print_r($p);	die();
		// $ret = $this->db->select('sno,name')
		// 				->from('emp_family_details')
		// 				->where(['emp_no'	=>	$id])
		// 				->get()->result_array();

		return $p;
	}
	public function get_dep_detail($id,$sno)
	{
		if($sno==='0')
		{
			$ret=$this->calc_emp_detail($id);
			echo json_encode($ret);
		}
		else
		{
			$ret = $this->calc_dep_detail($id,$sno);
			echo json_encode($ret);
		}
	}

	public function calc_emp_detail($id)
	{
		$res = $this->db->select('sex,dob')->from('user_details')->where(['id'	=>	$id])->get()->result_array()[0];
		if(strtolower($res['sex'])==='m'){
			$ret['sex'] = 'M';
		}
		else if(strtolower($res['sex'])==='f'){
			$ret['sex'] = 'F';
		}
		$ret['age'] =date('Y')-date('Y',strtotime($res['dob']));
		$ret['type'] = 'Spouse';
		$ret['relation'] = 'Self';
		$res = $this->db->select('code,no_of_slot,capacity,total_registered,reg_status')
						 ->from('swimming_group ')
						 ->where([
						 	'gender'	=>	$ret['sex'],
						 	'auth'		=>	'emp',
						 	'category'	=>	$ret['type'],
						 ])
						 ->get()
						 ->result_array()[0];
						 ;
		$ret['total'] = $res['no_of_slot']*$res['capacity'];
		$ret['code'] = $res['code'];
		$ret['reg_status']=$res['reg_status'];

		// Derive group of person end

		//$ret['filled'] = count($this->get('swimming_emp_details',['group_code'	=>	$res['code'],]));
		$ret['filled']=$res['total_registered'];
		return $ret;					 

	}
	public function calc_dep_detail($id,$sno){
		$res = $this->db->select('relationship,dob,photopath')
						->from('emp_family_details')
						->where(['emp_no'	=>	$id,
								 'sno'		=>	$sno,
								])
						->get()->result_array()[0];

		$ret['relation'] = $res['relationship'];
		$ret['age'] =date('Y')-date('Y',strtotime($res['dob']));
		$ret['photo'] = $res['photopath'];


		// Derive sex of the person

		$emp_sex = $this->db->select('sex')->from('user_details')->where(['id'	=>	$id])->get()->result_array()[0]['sex'];
		if(strtolower($res['relationship']) === 'son'|| strtolower($res['relationship']) === 'father'){
			$ret['sex'] = 'M';
		}
		else if(strtolower($res['relationship']) === 'daughter' || strtolower($res['relationship']) === 'mother' || strtolower($res['relationship']) === 'wife'){
			$ret['sex'] = 'F';
		}
		else if(strtolower($res['relationship']) === 'spouse'){
			if(strtolower($emp_sex)==='m'){
				$ret['sex'] = 'F';
			}
			else if(strtolower($emp_sex)==='f'){
				$ret['sex'] = 'M';
			}
		}

		// Derive sex of the person end

		// Derive type of person
		if((strtolower($ret['relation']) === 'son' || strtolower($ret['relation']) === 'daughter') && $ret['age'] <= 12){
			$ret['type'] = 'Children';
		}
		else{
			$ret['type'] = 'Spouse';
		}
		// Derive type of person end

		// Derive group of person
		$this->db->select('code,no_of_slot,capacity,total_registered,reg_status')
						 ->from('swimming_group')
						 ->where([
						 	'auth'		=>	'emp',
						 	'category'	=>	$ret['type'],
						 ]);
			if($ret['type'] !== 'Children'){
				$this->db->where([
						 	'gender'	=>	$ret['sex'],
						 ]);
			}

		$res = 	$this->db->get()
						 ->result_array()[0];
						 ;
		$ret['total'] = $res['no_of_slot']*$res['capacity'];
		$ret['code'] = $res['code'];
		$ret['reg_status']=$res['reg_status'];

		// Derive group of person end

		//$ret['filled'] = count($this->get('swimming_emp_details',['group_code'	=>	$res['code'],]));
		$ret['filled']=$res['total_registered'];
		return $ret;
	}

	public function insert_emp_form()
	{
		$file_type=$_FILES['receipt']['type'];
		if($file_type=="application/pdf"|| $file_type=="image/gif" || $file_type=="image/jpeg")
		{
			if($_FILES['size']<=204800){
				if (!empty($_FILES['receipt']) && $_FILES['receipt']['error'] == UPLOAD_ERR_OK){

				    // Be sure we're dealing with an upload
				    if (is_uploaded_file($_FILES['receipt']['tmp_name']) === false) {
				        throw new \Exception('Error on upload: Invalid file definition');
				    }	
				    $user_id=$this->CI->session->userdata('id');
				    // Rename the uploaded file
				    $path="assets/swimming/employee/";

				    if(!is_dir($path))
				    {  
				    	
				    	mkdir($path,0777,TRUE);
				    	
				    }
				    $uploadName = $_FILES['receipt']['name'];
				    $ext = strtolower(substr($uploadName, strripos($uploadName, '.')+1));
				    $filename = $user_id."_".date("Y")."_".round(microtime(true)).mt_rand().'.'.$ext;
				    move_uploaded_file($_FILES['receipt']['tmp_name'], $path.$filename);
				    $path.=$filename;
				    $swim_data['receipt_path']=$path;
				}
				else
				{
					$this->session->set_flashdata('flashWarning','Error while receipt uploading');
					redirect(site_url('swimming/emp_registration'));
				}
			}
			else
			{
				$this->session->set_flashdata('flashWarning','File Size Exceeds 200 KB');
				redirect(site_url('swimming/emp_registration'));
			}
		}
		else
		{
			$this->session->set_flashdata('flashInfo','please check file format');
			redirect(site_url('swimming/emp_registration'));
		}


		// check data

		if(is_null($_POST['mem_id']) || is_null($_POST['transaction_id'] ) ) {
			$this->session->set_flashdata('flashInfo','Transaction Id can not be empty!');
			redirect(site_url('swimming/emp_registration'));
		}


		$data_medi=
		[
			'user_id'						=>		$this->session->userdata('id'),
			'identification_mark'			=>		$this->input->post('mark'),
			'blood_group'			=>		$this->input->post('bg'),

			'epilepsy_detail'			=>		$this->input->post('epilepsy_detail'),
			'bronchical_asthma_detail'			=>		$this->input->post('asthma_detail'),
			'heart_disease_detail'			=>		$this->input->post('heart_detail'),
			'diabetes_detail'			=>		$this->input->post('diabetes_detail'),
			'hypertension_detail'			=>		$this->input->post('hypertension_detail'),
			'psychiatric_problem_detail'			=>		$this->input->post('psychiatric_detail'),
			'eye_infection_detail'			=>		$this->input->post('eye_infection_detail'),
			'ear_discharge_detail'			=>		$this->input->post('ear_detail'),
			'skin_disease_detail'			=>		$this->input->post('skin_detail'),
			'spectacles_type'			=>		$this->input->post('spec'),
			'allergy'			=>		$this->input->post('allergy'),
		];

		
	//	print_r($medi);
		if($this->input->post('mem_id')==='0')
			$group_code=$this->calc_emp_detail($this->session->userdata('id'))['code'];
		else
			$group_code=$this->calc_dep_detail($this->session->userdata('id'),$this->input->post('mem_id'))['code'];
		
		$group=$this->swim->get_group_by_code($group_code);
		
		if($group['capacity_available']<1)
		{
			$this->session->set_flashdata('flashInfo','No Vacent Seat Available. Contact Sport Section If you Think This is a Mistake or Wait for next Session');
			redirect(site_url('swimming/emp_registration'));
		}

		$this->db->trans_begin();

		$medi = $this->insert_medical_data($data_medi);

		$per_details = $this->personal_details($this->session->userdata('id'),$this->input->post('mem_id'));

			$data =
			[
				'user_id'			=>		$this->session->userdata('id'),
				's_no'				=>		$this->input->post('mem_id'),
				'can_swim'			=>		$this->input->post('k_swim'),
				'transaction_id'	=>		$this->input->post('transaction_id'),
				'amount'			=>		$this->input->post('amount'),
				'receipt_path'		=>		$path,
				'group_code'		=>		$group_code,
				'report_id'			=>		$medi['id'],
				'name'				=>		$per_details['name'],
				'dob'				=>		$per_details['dob'],
				'sex'				=>		$per_details['sex'],
				'photopath'			=>		$per_details['photopath'],
				'date_paid' => date('Y-m-d',strtotime($this->input->post('date_paid'))),
			];
			$this->insert('swimming_emp_details',$data);
			
			//print_r($this->db->last_query());
		
			$this->db->set('capacity_available', 'capacity_available-1', FALSE)
					 ->set('total_registered','total_registered+1',FALSE)
				   	 ->where(array('code'=>$data['group_code']))
					 ->update('swimming_group');
			if($this->db->trans_status()===FALSE){
			//	print_r($this->db->last_query());
			
				$this->db->trans_rollback();
				$this->session->set_flashdata('flashWarning','Your Form has not been submitted!! Try Again');
				//throw new Exception("ads",1);
				redirect(site_url('swimming/emp_registration'));	
			}
			else{
			//	throw new Exception("ads",1);
				$this->db->trans_commit();
				$this->session->set_flashdata('flashInfo','Your form has been submitted');
				redirect(site_url('swimming/emp_registration'));
				
			}

	}

	public function personal_details($id,$sno){
		$cond = [
			'UD.id'	=>	$id,
			'FD.sno'=>	$sno,
		];
		// print_r($cond);

		$cond = array_filter($cond,'sanitizeInputArray1');
		// print_r($cond);

		$dets = $this->db->select('UD.salutation,UD.first_name,UD.middle_name, UD.last_name,UD.sex,UD.dob,UD.photopath,FD.name,FD.photopath as dphoto,FD.dob as ddob')
						 ->from('user_details as UD')
						 ->where($cond)
						 ->join('emp_family_details as FD','FD.emp_no = UD.id','left')
						 ->get()->result_array()[0];
		// print_r($this->db->last_query());
		// print_r($dets);
		// throw new Exception("Error Processing Request", 1);
		
		if($sno==='0'){
			$ret['name'] 		= 	$dets['salutation']." ".$dets['first_name']." ".$dets['middle_name']." ".$dets['last_name'];
			$ret['dob']			=	$dets['dob'];
			$ret['photopath']	=	$dets['photopath'];
			$ret['sex']			=	$dets['sex'];
		}
		else{
			$ret['name'] 		= 	$dets['name'];
			$ret['dob']			=	$dets['ddob'];
			$ret['photopath']	=	$dets['dphoto'];
			$ret['sex']			=	$this->calc_dep_detail($id,$sno)['sex'];
		}
		// print_r($dets);
		// print_r(($ret['name']));
		// throw new Exception("Error Processing Request", 1);
		
		return $ret;
	}

	public function get_swimming_data_employee($id=''){
		// prettyDump($id);
		if(!empty($id)){
			$res = $this->db->select('FD.name,ED.name as emp_name,ED.status,ED.slot_id,ED.transaction_id,ED.amount,ED.receipt_path,ED.date_applied,ED.status,ED.remarks,ED.id,SG.name as group_name,SS.days,SS.start_time,SS.end_time,SS.slot_name')
							->from('swimming_emp_details as ED')
							->where(['ED.user_id'	=>	$id])
							->join('emp_family_details as FD','FD.sno = ED.s_no AND FD.emp_no = ED.user_id','left')
							->join('swimming_group as SG','SG.code = ED.group_code')
							->join('swimming_slot as SS','SS.id = ED.slot_id','left')
							->get()->result_array();
			 // print_r($this->db->last_query());
			// prettyDump($id);
			return $res;

		}
	}

	// ------------------------------------------------------------------------------------------------------------------
	public function filter_employee_application($cond=array())
	{
		// prettyDump($cond);
		if(is_array($cond))
		{
			$res = $this->db->select('FD.name,ED.status,ED.slot_id,ED.transaction_id,ED.amount,ED.date_applied,ED.group_code,ED.status,ED.remarks,SG.name as group_name,SS.days,SS.start_time,SS.end_time,SS.slot_name,ED.user_id,UD.salutation,UD.first_name,UD.middle_name,UD.last_name,ED.slot_assigned,ED.s_no as sno,ED.id,ED.receipt_path,FD.photopath,FD.dob,UA.contact_no as mobile,ED.can_swim,MR.identification_mark,MR.blood_group,MR.epilepsy_detail,MR.bronchical_asthma_detail,MR.heart_disease_detail,MR.diabetes_detail,MR.hypertension_detail,MR.psychiatric_problem_detail,MR.eye_infection_detail,MR.ear_discharge_detail,MR.skin_disease_detail,MR.spectacles_type,MR.allergy')
							->from('swimming_emp_details as ED')
							->where($cond)
							->join('emp_family_details as FD','FD.sno = ED.s_no && FD.emp_no = ED.user_id','left')
							->join('swimming_group as SG','SG.code = ED.group_code')
							->join('swimming_slot as SS','SS.id = ED.slot_id','left')
							->join('user_details as UD','UD.id = ED.user_id')
							->join('user_address as UA','UA.id = ED.user_id AND UA.type = "present"')
							->join('swimming_medical_report as MR' , 'MR.id = ED.report_id')
							->get()->result_array();
			// prettyDump($this->db->last_query());
			// prettyDump($res);
			return $res;
		}
	}


	public function get_emp_card($id='')
	{
		if(!empty($id))
		{
			$result=$this->db->select('C.name as user_name,M.blood_group,C.photopath,C.id,
									C.sex,C.dob,C.slot_id,C.user_id,S.days,S.start_time,S.end_time,G.name,S.slot_name')
							 ->from('swimming_emp_details as C')
							 ->where(array('C.id'=>$id))
							 ->join('swimming_medical_report as M','M.id=C.report_id','left')
							 ->join('swimming_slot as S','S.id=C.slot_id','left')
							 ->join('swimming_group as G','G.code=C.group_code','left')
							 ->get();
		    if($result)		
		    return $result->result_array()[0];			 

		}
		return array();
	}

	// public function flush_group($code){
	// 	print_r($code);
	// 	$data=[
	// 		'capacity'			=>	35,
	// 		'no_of_slot'		=>	1,
	// 		'capacity_available'=>	35,
	// 		'total_registered'	=>	0,
	// 	];

	// 	$cond = [
	// 		'code'	=>	$code,
	// 	];
	// 	$this->db->trans_begin();
	// 	$this->db->where($cond)->update('swimming_group',$data);
	// 	$this->db->where(['group_code'	=>	$code])->update('swimming_student_details',['status'	=>	3]);
	// 	$this->db->where(['group_code'	=>	$code])->update('swimming_emp_details',['status'	=>	3]);
	// //print_r($this->db->last_query());
	// 	if($this->db->trans_status === FALSE){
	// 		$this->session->set_flashdata('flashWarning','Error while flushing');
	// 		$this->db->trans_rollback();
			
	// 		//print_r($this->db->last_query());
	// 		redirect(site_url('swimming/swimming_section/view_swimming_group'));
	// 	}
	// 	else{
	// 		//print_r($this->db->last_query());
	// 		$this->session->set_flashdata('flashSuccess','Flushed');
	// 		$this->db->trans_commit();
	// 		redirect(site_url('swimming/swimming_section/view_swimming_group'));
	// 	}
	// }
    

    public function flush_group($code){
		print_r($code);
		$data=[
			'capacity'			=>	35,
			'no_of_slot'		=>	1,
			'capacity_available'=>	35,
			'total_registered'	=>	0,
		];

		$cond = [
			'code'	=>	$code,
		];
		$this->db->trans_begin();
		$this->db->where($cond)->update('swimming_group',$data);
		$this->db->where(['group_code'	=>	$code,'status'=>2])->update('swimming_student_details',['status'	=>	3]);
		$this->db->where(['group_code'	=>	$code,'status'=>2])->update('swimming_emp_details',['status'	=>	3,'remarks'=>"Application has been not approved"]);
		$this->db->where(['group_code'	=>	$code,'status'=>1])->update('swimming_student_details',['status'	=>	4,'remarks'=>"Application has been not approved"]);
	    $this->db->where(['group_code'	=>	$code,'status'=>1])->update('swimming_emp_details',['status'	=>	4]);
	    $this->db->where(['group_code'	=>	$code])->update('swimming_slot',['alloted'	=>	0,'available'=>1]);
	//print_r($this->db->last_query());
		if($this->db->trans_status === FALSE){
			$this->session->set_flashdata('flashWarning','Error while flushing');
			$this->db->trans_rollback();
			
			//print_r($this->db->last_query());
			redirect(site_url('swimming/swimming_section/view_swimming_group'));
		}
		else{
			//print_r($this->db->last_query());
			$this->session->set_flashdata('flashSuccess','Flushed');
			$this->db->trans_commit();
			redirect(site_url('swimming/swimming_section/view_swimming_group'));
		}
	}

	
	public function open_registration($id='')
	{
		if(!empty($id))
		{
			if($this->db->update('swimming_group',array('reg_status'=>2),array('code'=>$id)))
			{
				$this->session->set_flashdata('flashSuccess','Registration has been opened for the '.$id);
				redirect(site_url('swimming/swimming_section/view_swimming_group'));
			}
			else
			{
				$this->session->set_flashdata('flashWarning','Try Again');
				redirect(site_url('swimming/swimming_section/view_swimming_group'));
			}
		}
	}
	public function close_registration($id='')
	{
		if(!empty($id))
		{
			if($this->db->update('swimming_group',array('reg_status'=>1),array('code'=>$id)))
			{
				$this->session->set_flashdata('flashSuccess','Registration has been close for the '.$id);
				redirect(site_url('swimming/swimming_section/view_swimming_group'));
			}
			else
			{
				$this->session->set_flashdata('flashWarning','Try Again');
				redirect(site_url('swimming/swimming_section/view_swimming_group'));
			}
		}
	}




}


function sanitizeInputArray1($arr){
	return !($arr==='All' || $arr===0 ||$arr==='' || $arr === '0');
}

?>