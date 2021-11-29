<?php

class Super_admin_menu_model extends CI_Model
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
		$menu['admin']=array();
		$menu['admin']['Change Password']= site_url('super_admin/admin/change_password');
		$menu['admin']['Assign Authorizations'] = site_url('super_admin/admin/assign_auths');
		$menu['admin']['Deny Authorizations'] = site_url('super_admin/admin/deny_auths');
        $menu['admin']['Login Details'] = site_url('super_admin/check_log_file');
        $menu['admin']['Supervisor Report'] = site_url('super_admin/super_report');
		$menu['admin']['Student Hostel Report'] = site_url('student/student_ctl/get_student_mail_info');
		$menu['admin']['New Admission'] = site_url('super_admin/new_admission');
		return $menu;
	}
}
/* End of file super_admin_menu_models.php */
/* Location: mis/application/models/super_admin/super_admin_menu_model.php */