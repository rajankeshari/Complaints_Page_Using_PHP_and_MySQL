<?php

class User_notifications_model extends CI_Model
{

	var $table = 'user_notifications';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function update_notice_status($send_date,$user_to,$auth_id){
	//	echo base64_decode($send_date);exit;
	$this->db->where('user_to',base64_decode($user_to));
	$this->db->where('auth_id',base64_decode($auth_id));
	$this->db->where('send_date',$send_date);
	$this->db->update($this->table, array('status' => 1));

	}
	function getNotice_data($send_date,$user_to,$auth_id){
		$query = $this->db->select('user_notifications.*')
						->where('user_to',base64_decode($user_to))
						->where('auth_id',base64_decode($auth_id))
						->where('send_date',base64_decode($send_date))
						->from($this->table)
						->get();
		return $query->row();
	}
	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}

	function updateNotification($data, $where) {
		$this->db->update($this->table, $data, $where);
	}
	function updateUnreadUserNotifications($user_to, $auth){
		$checkdate= date('Y-m-d H:i:s', strtotime('-10 day'));
		$this->db->where('user_to',$user_to);
		$this->db->where('auth_id',$auth);
		$this->db->where('status',0);
		$this->db->where('send_date <=',$checkdate);
		$this->db->update($this->table, array('status' => 2));
	//echo $this->db->last_query(); exit;
	}
	function getUnreadUserNotifications($user_to, $auth)
	{
			$query = $this->db->select('user_notifications.*, user_details.photopath as photopath')
						->where('user_to',$user_to)
						->where('auth_id',$auth)
						->where('status',0)
						->from($this->table)
						->join('user_details', 'user_details.id = user_notifications.user_from')
						->order_by('send_date','desc')
						->get();
		return $query->result();
	}

	function getReadUserNotifications($user_to, $auth)
	{
		$query = $this->db->select('user_notifications.*, user_details.photopath as photopath')
						->where('user_to',$user_to)
						->where('auth_id',$auth)
						->where('status !=',0)
						->from($this->table)
						->join('user_details', 'user_details.id = user_notifications.user_from')
						->order_by('send_date','desc')
						->limit(100)
						->get();

		return $query->result();
	}

}

/* End of file user_notifications_model.php */
/* Location: mis/application/models/user_notifications_model.php */
