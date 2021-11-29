<?php

class Emp_details_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> exam_dr
		$menu['admin']=array();
		$menu['admin']["Employee_Details"] = site_url('emp_details/emp_details');

		return $menu;
	}
}
?>