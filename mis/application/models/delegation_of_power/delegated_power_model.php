<?php
class Delegated_power_model extends CI_Model
{
	var $table = 'delegated_power';
	function __construct()
	{
		parent::__construct();
	}

	function insert($data)
	{
		$asign_by=$data['assign_by'];
		$auth=$data['authorization'];
	// To get the start_date and end_date from delegated_power table which have same authorization assigned ny the same user. 
			$query=$this->db->query("SELECT start_date,end_date FROM delegated_power AS D WHERE D.assign_by = '$asign_by' and D.authorization = '$auth' and (D.status =1 or D.status=0)  ");
		$dates=$query->result_array();
		if(is_array($dates)){
			foreach ($dates as $date) {

			if($date['start_date']<=$data['start_date']&&$data['start_date']<=$date['end_date'])
				{
					$this->session->set_flashdata('flashInfo','Authorization already delegated');
				redirect('delegation_of_power/delegate_power/assign_auths');
				}

			else if($date['start_date']>$data['start_date']&&$data['end_date']>=$date['start_date'])
				{
					$this->session->set_flashdata('flashInfo','Authorization already delegated');
				redirect('delegation_of_power/delegate_power/assign_auths');
				}

			}
				
		}
		$this->db->insert($this->table,$data);
	if($this->db->affected_rows() >0)
		{
			$this->session->set_flashdata('flashSuccess','Record inserted ');
			
		}
		else
		{
			$this->session->set_flashdata('flashError','Record not inserted ');
			
		}
	}


	function update($id)
	{
		$users=$this->db->where('S_no',$id)->get($this->table);
		$user=$users->result()[0];
		$today = date('Y-m-d');
		date_default_timezone_set('Asia/Kolkata');
		$time_current=date('Y-m-d H:i:s');
		$this->db->update($this->table,array('deny_timestamp'=>$time_current),array('S_no'=>$id));
		$this->db->update($this->table,array('status'=>'2'),array('S_no'=>$id));
		$this->db->update($this->table,array('end_date'=>$today),array('S_no'=>$id));
		$this->db->delete('user_auth_types',array('id'=>$user->assign_to ,'auth_id'=>$user->authorization));
	}

	function updateStatus($id,$auth)
	{
		$users=$this->db->where(array("assign_to"=>$id,"authorization"=>$auth,"status"=>'1'))->get($this->table)->result_array();
		
		if(count($users)===1)
		{
			$user=$users[0];
				date_default_timezone_set('Asia/Kolkata');
				$time_current=date('Y-m-d H:i:s');
			$today = date('Y-m-d');
			$this->db->update($this->table,array('status'=>'2','end_date'=>$today,'deny_timestamp'=>$time_current),array('S_no'=>$user['S_no']));
	    }
		$this->db->delete('user_auth_types',array('id'=>$id,'auth_id'=>$auth));
	}

function cancel($id)
	{
		$users=$this->db->where('S_no',$id)->get($this->table);
		$user=$users->result()[0];
		$today = date('Y-m-d');
		$this->db->update($this->table,array('status'=>'3'),array('S_no'=>$id));
		$this->db->update($this->table,array('end_date'=>$today),array('S_no'=>$id));	
	}
	function getUserIdById($id ='')
	{

		$query=$this->db->query("SELECT * FROM (SELECT * FROM delegated_power AS D INNER JOIN auth_types AS A ON D.authorization=A.id WHERE D.assign_by=$id ) AS T INNER JOIN user_details AS U ON T.assign_to=U.id");

		return $query->result();
	}
	
	function getUserIdBy_Id($id ='')
	{
		$query=$this->db->query("SELECT U.auth_id, type FROM user_auth_types AS U INNER JOIN auth_types AS A ON U.auth_id=A.id WHERE U.id = $id and U.auth_id!='admin' and($id,U.auth_id) NOT IN (select assign_to,authorization from delegated_power where status =1) ");
		return $query->result_array();
	
		
	
	}

	function getUserAuthByAuth($auth_id)
	{

		$query=$this->db->query("SELECT * FROM auth_menu_detail AS D INNER JOIN auth_types AS A ON D.auth_id=A.id WHERE D.auth_id='$auth_id'");
		return $query->result();
	}

}




?>