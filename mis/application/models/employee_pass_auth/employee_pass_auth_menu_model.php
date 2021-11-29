<?php

class Employee_pass_auth_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> emp
		//$menu['exam_da1']=array();
		//$menu['exam_da1']['Change Employee Password']= site_url('employee_pass_auth/Employee_password/change_password');
		
		//$menu['acad_da1']=array();
		//$menu['acad_da1']['Change Employee Password']= site_url('employee_pass_auth/Employee_password/change_password');
		//$menu['emp_pass_auth']=array();
		//$menu['emp_pass_auth']['Change Employee Password']= site_url('employee_pass_auth/Employee_password/change_password');
		
		
		return $menu;
	}
}
/* End of file super_admin_menu_models.php */
/* Location: mis/application/models/super_admin/super_admin_menu_model.php */
