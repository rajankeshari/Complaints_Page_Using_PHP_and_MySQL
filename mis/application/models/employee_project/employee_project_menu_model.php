<?php

class Employee_project_menu_model extends CI_Model
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
		$menu['admin']['Project Employee']=array();
		$menu['admin']["Project Employee"]["Add User"] = site_url('employee_project/add');
		$menu['admin']["Project Employee"]["Validate User Details"]=site_url('employee_project/validation');
		$menu['admin']["Project Employee"]["Edit User Details"]["Unverified User"] = site_url('employee_project/edit/index');
		$menu['admin']["Project Employee"]["Edit User Details"]["Verified User"] = site_url('employee_project/edit/verified_user');
		$menu['admin']["Project Employee"]["Retire User"] = site_url('employee_project/remove/index');
		//$menu['admin']['Project Employee']['Validate Retirement']=site_url('employee_daily_rated/remove/remove_daily_rated_emp_validation');
		//$menu['admin']['Project Employee']['Recover User']=site_url('employee_daily_rated/remove/recover_d');
		
		
		//auth ==> odean_rnd
		
		
		
		$menu['odean_rnd']['Project Employee']=array();
		$menu['odean_rnd']["Project Employee"]["Add User"] = site_url('employee_project/add');
		$menu['odean_rnd']["Project Employee"]["Edit User Details"]["Unverified User"] = site_url('employee_project/edit/index');
		$menu['odean_rnd']["Project Employee"]["Edit User Details"]["Verified User"] = site_url('employee_project/edit/verified_user');
		$menu['odean_rnd']["Project Employee"]["Retire User"] = site_url('employee_project/remove/index');
		//$menu['odean_rnd']["Project Employee"]["Validate User Details"]=site_url('employee_project/validation');
		
		//auth ==> dean_rnd
		
		$menu['dean_rnd']=array();
		$menu['dean_rnd']['Project Employee']=array();
		$menu['dean_rnd']["Project Employee"]["Add User"] = site_url('employee_project/add');
		$menu['dean_rnd']["Project Employee"]["Edit User Details"]["Unverified User"] = site_url('employee_project/edit/index');
		$menu['dean_rnd']["Project Employee"]["Edit User Details"]["Verified User"] = site_url('employee_project/edit/verified_user');
		//$menu['dean_rnd']["Project Employee"]["Retire User"] = site_url('employee_project/remove/index');
		$menu['dean_rnd']["Project Employee"]["Validate User Details"]=site_url('employee_project/validation');
		$menu['dean_rnd']['Institute PDF']=array();
		$menu['dean_rnd']["Institute PDF"]["Add Institute PDF"] = site_url('employee_project/add_pdf');
		$menu['dean_rnd']["Institute PDF"]["Edit Institute PDF"]["Unverified User"] = site_url('employee_project/edit_pdf');
		$menu['dean_rnd']["Institute PDF"]["Edit Institute PDF"]["Verified User"] = site_url('employee_project/edit_pdf/verified_user');
		$menu['dean_rnd']["Institute PDF"]["Validate Institute PDF"]=site_url('employee_project/validation_pdf');
	              
		return $menu;
	}
}
/* End of file employee_menu.php */
/* Location: mis/application/models/employee/employee_menu.php */