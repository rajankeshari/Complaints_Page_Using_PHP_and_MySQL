<?php

class Post_message_model extends CI_Model
{

	var $table = 'message_details';
	var $qouta_table='message_qouta';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}

	public function insert($table='',$data=array())
	{
		if(!empty($table)&&is_array($data)&&!empty($data)){

			$query=$this->db->insert($table,$data);
			if($query)
				return true;
		}
		return false;
	}

	
	public function update($table="",$data=array(),$primary=array())
	{	
		// if table name is not empty and data array and primary array is not empty
		if(!empty($table)&&!empty($data)&&!empty($primary))
		{	
			// perform update operation on record identified with primary array
			$query=$this->db->update($table,$data,$primary);
	          
			// if query is executed successfully            
			if($query)
			{   
				// return true
				
				return true;
			}
			else
			{	
				// return false.
				
				return false;
			}
		}
		return false;
	}

	public function get_latest_message_id_by_userid($user_id='')
	{
		$result=$this->db->select_max('message_id')->where(array('issued_by'=>$user_id))->get($this->table);
		
		if($result  && count($result)===1)
		{
			return $result->result_array()[0]['message_id'];
		}
		else
			return false;
	}

	public function put_msg_individual($msg=array(),$receiver=array())
	{
		$this->db->trans_begin();
				
		$msg_id=$this->put_msg($msg);

		

		if($msg_id)
		{
			$groups=array();
			foreach ($receiver['individuals'] as $ind) {
				$groups[]=array('message_id'=>$msg_id,'user_id'=>$ind);
			}
			$this->db->insert_batch('message_individuals',$groups);
			
			$data=[
				'type'   => 'ind',
				'users'  => $receiver['individuals']
			];

			$mobile_no=$this->get_mobile_no($data);

			if(count($mobile_no)<=$receiver['avb'])
			{
				$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

				if($status)
				{
					if($this->db->trans_status()===FALSE)
					{
						$this->db->trans_rollback();
						return false;
						$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					}
					else
					{	$this->db->trans_commit();
						return true;
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					return false;
				}
			}
			else
			{	
				$this->session->set_flashdata('flashWarning','message has been not sent (Check your Quota Limit!'.count($mobile_no).' is required and your limit is '.$receiver['avb']);
				$this->db->trans_rollback();
				return false;
			}
		}
		else
		{

			$this->db->trans_rollback();
			$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
			return false;
		}
	}
	

	public function put_msg_group($msg=array(),$receiver=array())
	{
		$this->db->trans_begin();
		
		$msg_id=$this->put_msg($msg);
		$user_id=$this->CI->session->userdata('id');

		
		if($msg_id)
		{	
			$this->db->insert('message_group_id',array('message_id'=>$msg_id,'group_id'=>$receiver['grp_selection'],'created_by'=>$user_id));
			// $this->send_msg(count($receiver['members']),$receiver['members']);
			$data=[
				'type'   => 'ind',
				'users'  => $receiver['members']
			];
			//print_r($receiver['members']);
			$mobile_no=$this->get_mobile_no($data);
			$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

			if(count($mobile_no)<=$receiver['avb'])
			{
				$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

				if($status)
				{
					if($this->db->trans_status()===FALSE)
					{
						$this->db->trans_rollback();
						return false;
						$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					}
					else
					{	$this->db->trans_commit();
						return true;
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					return false;
				}
			}
			else
			{	
				$this->session->set_flashdata('flashWarning','message has been not sent (Check your Quota Limit!'.count($mobile_no).' is required and your limit is '.$receiver['avb']);
				$this->db->trans_rollback();
				return false;
			}
		}
		else
		{

			$this->db->trans_rollback();
			$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
			return false;
		}
	}

	public function put_msg_global_group($msg=array(),$receiver=array())
	{
		$this->db->trans_begin();
		
		$msg_id=$this->put_msg($msg);
		$user_id=$this->CI->session->userdata('id');

		
		if($msg_id)
		{	
			$this->db->insert('message_group_global_id',array('message_id'=>$msg_id,'group_id'=>$receiver['grp_global_selection'],'created_by'=>$user_id));
			// $this->send_msg(count($receiver['members']),$receiver['members']);
			$data=[
				'type'   => 'ind',
				'users'  => $receiver['members']
			];
			//print_r($receiver['members']);
			$mobile_no=$this->get_mobile_no($data);
			$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

			if(count($mobile_no)<=$receiver['avb'])
			{
				$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

				if($status)
				{
					if($this->db->trans_status()===FALSE)
					{
						$this->db->trans_rollback();
						return false;
						$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					}
					else
					{	$this->db->trans_commit();
						return true;
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					return false;
				}
			}
			else
			{	
				$this->session->set_flashdata('flashWarning','message has been not sent (Check your Quota Limit!'.count($mobile_no).' is required and your limit is '.$receiver['avb']);
				$this->db->trans_rollback();
				return false;
			}
		}
		else
		{

			$this->db->trans_rollback();
			$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
			return false;
		}
	}

	public function put_msg_general($msg=array(),$receiver=array())
	{
		$this->db->trans_begin();
				
		$msg_id=$this->put_msg($msg);

		if($msg_id)
		{	if($receiver['cat_selection']==='stu')
			{
				$gen_data=array(
					'message_id'	=>	$msg_id,
					'message_cat'	=>	'stu',
					'dept_id' 		=>	$receiver['dept_selection'],
					'course_id' 	=>	$receiver['course_selection1'],
					'semester' 		=>	$receiver['sem_selection']
				);

				$this->db->insert('message_gen_stu',$gen_data);

				$data=[
					'type'   		=> 	'gen',
					'subtype' 		=>	'stu',
					'dept_id' 		=>	$receiver['dept_selection'],
					'course_id' 	=>	$receiver['course_selection1'],
					'semester' 		=>	$receiver['sem_selection']
				];
				//print_r($receiver['members']);
				// $mobile_no=$this->get_mobile_no($data);
				//  $this->send_msg($mobile_no,$msg['message_sub']);
			}
			else if($receiver['cat_selection']==='emp')
			{

				$gen_data=array(
					'message_id'	=>$msg_id,
					'message_cat'	=>'emp',
					'dept_id' 		=>$receiver['dept_selection'],
					'emp_auth_id' 	=>$receiver['emp_selection1'],
					
				);

				$this->db->insert('message_gen_emp',$gen_data);

				$data=[
					'type'   		=> 	'gen',
					'subtype' 		=>	'emp',
					'dept_id' 		=>	$receiver['dept_selection'],
					'auth_id' 	=>	$receiver['emp_selection1'],
				];
			}
			else
			{
				$gen_data=array(
					'message_id'	=>$msg_id,
					'message_cat'	=>'all',
					'dept_id' 		=>$receiver['dept_selection'],			
				);
				$this->db->insert('message_gen_all',$gen_data);

				$data=[
					'type'   		=> 	'gen',
					'subtype' 		=>	'all',
					'dept_id' 		=>	$receiver['dept_selection'],
				];
			}

			//print_r($data);
			$mobile_no=$this->get_mobile_no($data);
			if(count($mobile_no)<=$receiver['avb'])
			{
				$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

				if($status)
				{
					if($this->db->trans_status()===FALSE)
					{
						$this->db->trans_rollback();
						return false;
						$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					}
					else
					{	$this->db->trans_commit();
						return true;
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					return false;
				}
			}
			else
			{	
				$this->session->set_flashdata('flashWarning','message has been not sent (Check your Quota Limit!'.count($mobile_no).' is required and your limit is '.$receiver['avb']);
				$this->db->trans_rollback();
				return false;
			}
		}
		else
		{

			$this->db->trans_rollback();
			$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
			return false;
		}
	}

	public function put_other_msg($msg=array(),$receiver=array())
	{
		$this->db->trans_begin();
				
		$msg_id=$this->put_msg($msg);
		if($msg_id)
		{
			$data['type']=$msg['message_cat'];
			$mobile_no=$this->get_mobile_no($data);
		  	if(count($mobile_no)<=$receiver['avb'])
			{
				$status=$this->send_msg($mobile_no,$msg['message_sub'],$receiver['assign_id']);

				if($status)
				{
					if($this->db->trans_status()===FALSE)
					{
						$this->db->trans_rollback();
						return false;
						$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					}
					else
					{	$this->db->trans_commit();
						return true;
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
					return false;
				}
			}
			else
			{	
				$this->session->set_flashdata('flashWarning','message has been not sent (Check your Quota Limit!'.count($mobile_no).' is required and your limit is '.$receiver['avb']);
				$this->db->trans_rollback();
				return false;
			}
		}
		else
		{

			$this->db->trans_rollback();
			$this->session->set_flashdata('flashWarning','message has been not sent (please Try again!');
			return false;
		}
	}

	public function put_msg($msg=array())
	{
		if(!empty($msg))
		{
			$query=$this->db->insert($this->table,$msg);

			if($query)
			{
				return $this->get_latest_message_id_by_userid($msg['issued_by']);
			}
			return false;
		}
		
	}

	function get_mobile_no($data=array())
	{
		// print_r($data);
		if($data['type'] === "ind" || $data['type'] === "grp"){
			$users = $data['users'];
			$mobileAssoc = $this->db->select('contact_no as mobile_no')->where(array('type'=>"present"))->where_in('id',$users)->get('user_address')->result_array();
			
		}
		else if($data['type'] === "gen"){

			if($data['subtype'] === "stu"){

				if(empty($data['semester']))
					$data['semester']='all';


				$cond = [
					'S.course_id'	=>	$data['course_id'],
					'S.semester'	=>  $data['semester'],
					'U.dept_id'		=>	$data['dept_id'],
					'O.type'  =>  'present'
				];
				$cond = array_filter($cond,'sanitizeArray');
				$mobileAssoc =  $this->db->select('O.contact_no as mobile_no')->from('user_details as U')
												->where($cond)
												->join('stu_academic as S','S.admn_no = U.id','inner')
												->join('user_address as O','O.id = U.id','inner')
												->get()->result_array();
			}
			else if($data['subtype'] === "emp")
			{
				$cond = [
					'E.auth_id'		=>	$data['auth_id'],
					'U.dept_id'		=>	$data['dept_id'],
					'O.type'  =>  'present'
				];
				$cond = array_filter($cond,'sanitizeArray');
				$mobileAssoc =  $this->db->select('O.contact_no as mobile_no')->from('user_details as U')
												->where($cond)
												->join('emp_basic_details E','E.emp_no = U.id','inner')
												->join('user_address as O','O.id = U.id','inner')
												->get()->result_array();
			}
			else
			{

				$cond = [
					'U.dept_id'		=>	$data['dept_id'],
					'O.type'  =>  'present'
				];
				$cond = array_filter($cond,'sanitizeArray');
				$mobileAssoc =  $this->db->select('O.contact_no as mobile_no')->from('user_details as U')
												->where($cond)
												->join('user_address as O','O.id = U.id','inner')
												->get()->result_array();
			}
		}
		else if($data['type']==='emp'){
			$mobileAssoc = $this->db->select('O.contact_no as mobile_no')
									->where(array('O.type'  =>  'present'))
									->from('emp_basic_details as E')
									->join('user_address as O','O.id = E.emp_no','inner')
									->get()->result_array();

		}

		else if($data['type']==='stu')
		{
			$mobileAssoc = $this->db->select('O.contact_no as mobile_no')
									->where(array('O.type'  =>  'present'))
									->from('user_address as O')
									->join('stu_academic as S','O.id = S.admn_no','inner')
									->get()->result_array();
		}
		else if($data['type'] === 'ft'){
			
			$mobileAssoc = $this->db->select('O.contact_no as mobile_no')
									->from('emp_basic_details as E')
									->where('E.auth_id','ft')
									->where(array('O.type'  =>  'present'))
									->join('user_address as O','O.id = E.emp_no','inner')
									->get()->result_array();

		}
		else if($data['type'] === 'all'){
			$mobileAssoc = $this->db->select('O.contact_no as mobile_no')->where(array('O.type'  =>  'present'))->get('user_address')->result_array();
		}

		

		$mobile = [];
		foreach ($mobileAssoc as $key) {
			$mobile[] = $key['mobile_no'];
		}

		$mobile=array_values(array_filter($mobile));
		print_r($mobile);
		print_r($this->db->last_query());
		return $mobile;
	}

	public function send_msg($mobile=array(),$msg='',$assign_id=4)
	{
		//print_r($mobile);
		$this->load->library('SMS');
		$sms=new SMS;

		$response=$sms->sendSMS($mobile,$msg,'IITISM');
		//print_r($response);
		$total=count($mobile);
		$assign_data=[
			'assign_id'=>$assign_id,
			'quota_type'=>2,
			'quota'=>$total,
			'assigned_date'=>date('Y-m-d')
		];

		//print_r($assign_data);
		$this->db->insert('message_assign_history',$assign_data);
		$this->db->set('used','used'.'+'.$total,FALSE)
					         ->where(array('assign_id'=>$assign_id))
					         ->update('message_assigned');
	    return true;
		// print_r($msg);
	}


	public function add_and_update_quota($data)
	{
		if(is_array($data)&&!empty($data))
		{
			$this->db->trans_begin();


			$this->db->insert('message_assign_history',$data);
			$this->db->set('total_quota','total_quota'.'+'.$data['quota'],FALSE)
			         ->where(array('assign_id'=>$data['assign_id']))
			         ->update('message_assigned');
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

	public function add_user($data=array())
	{
		if(is_array($data)&&!empty($data))
		{
			$this->db->trans_begin();
			$this->db->insert('message_assigned',$data);
			$this->db->insert('user_auth_types',array('id'=>$data['user_id'],'auth_id'=>'msg'));
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
	public function remove_user($assign_id='',$user)
	{	
		$user_id=$this->CI->session->userdata('id');
		if(!empty($assign_id)&&!empty($user)&&!empty($user_id))
		{
			$this->db->trans_begin();

			$this->db->update('message_assigned',array('status'=>3,'assigned_by'=>$user_id),array('assign_id'=>$assign_id,));
			$this->db->delete('user_auth_types',array('auth_id'=>'msg','id'=>$user));

			//print_r($this->db->last_query());

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

	public function update_quota($assign_id='',$o_quota='',$n_quota='',$id='')
	{				
		if(!empty($assign_id)&&!empty($o_quota)&&!empty($n_quota)&&!empty($id))
		{
			$this->db->trans_begin();
			$user_id=$this->CI->session->userdata('id');
			$this->db->update('message_assign_history',array('quota'=>$n_quota,'assigned_by'=>$user_id),array('id'=>$id,'assign_id'=>$assign_id));
			$quota=$n_quota-$o_quota;

			$this->db->set('total_quota','total_quota'.'+'.$quota,FALSE)
			         ->where(array('assign_id'=>$assign_id))
			         ->update('message_assigned');
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
}
function sanitizeArray($arr)
{
	return !($arr==='All' || $arr==='0' ||$arr==='all' || $arr===0 ||$arr===''||is_null($arr));
}
/* End of file post_message_model.php */
/* Location: mis/application/models/post_message_model.php */