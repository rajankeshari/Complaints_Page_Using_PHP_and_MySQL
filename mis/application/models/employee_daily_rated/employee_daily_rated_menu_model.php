<?php

class Employee_daily_rated_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		$menu['admin']=array();
		$menu['admin']['Temporary MIS Users']=array();
		$menu['admin']["Temporary MIS Users"]["Validate User Details"]=site_url('employee_daily_rated/validation');
		$menu['admin']['Temporary MIS Users']['Validate Retirement']=site_url('employee_daily_rated/remove/remove_daily_rated_emp_validation');
		$menu['admin']['Temporary MIS Users']['Recover User']=site_url('employee_daily_rated/remove/recover_d');
		//site_url('employee/remove/recover');

		//auth ==> deo
		$menu['mis_da']=array();
		$menu['mis_da']['Temporary MIS Users']=array();
		$menu['mis_da']["Temporary MIS Users"]["Add User"] = site_url('employee_daily_rated/add');
		$menu['mis_da']["Temporary MIS Users"]["Edit User Details"] = site_url('employee_daily_rated/edit/index/mis_da');
		$menu['mis_da']["Temporary MIS Users"]["Retire User"] = site_url('employee_daily_rated/remove/index');
	              
		return $menu;
	}
}
/* End of file employee_menu.php */
/* Location: mis/application/models/employee/employee_menu.php */