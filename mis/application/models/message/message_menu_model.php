<?php

class Message_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu($auth='')
	{
		$menu=[
			'msg' =>[
				'Send Message' => site_url('message/post_message/index/msg'),
				'View Message' => site_url('message/view_message/index'),
			],
			
			'msg_admin'=>[
				'Assign Authorization' => site_url('message/message_admin/index/msg_admin'),
				'Global Group' => [

					
					'Add'  => site_url('message/group_global/create_group_global'),
					'Edit' => site_url('message/group_global/edit_group_global'),
					'Delete' => site_url('message/group_global/delete_group_global'),
				],
			],
		];
		
		return $menu;
	}
}
/* End of file information_menu.php */
/* Location: mis/application/models/information/information_menu.php */