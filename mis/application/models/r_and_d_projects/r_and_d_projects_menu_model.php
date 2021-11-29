<?php

class R_and_d_projects_menu_model extends CI_Model
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

		$menu['emp']=array();
		// $menu['emp']['Attendance Management']=array();
		// $menu['emp']["Attendance Management"]["Generate Attendance"] = site_url('attendance/student_attendance');
		// $menu['emp']["Attendance Management"]["Attendance"] = site_url('attendance/student_attendance');
		$menu['emp']['R & D Projects']=array();
		$menu['emp']["R & D Projects"]["View your Projects"] = site_url('r_and_d_projects/r_and_d_employee_controller/view_projects_employee/emp');
	    $menu['emp']["R & D Projects"]["Enter new Projects"] = site_url('r_and_d_projects/r_and_d_employee_controller/enter_projects_employee/emp');

		//auth ==> est_ar
		// $menu['est_ar']=array();
		// $menu['est_ar']['Employee Details']=array();
		// $menu['est_ar']["Employee Details"]["Validation Requests"]=site_url('employee/validation');
		// $menu['est_ar']["Employee Details"]["Database Queries"]=array();
		// $menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Category"]=site_url('employee/queries/queryByCategory');
		// $menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Department"]=site_url('employee/queries/queryByDepartment');
		// $menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Designation"]=site_url('employee/queries/queryByDesignation');

		// //auth ==> deo
		// $menu['est_da1']=array();
		// $menu['est_da1']['Manage Employees']=array();
		// $menu['est_da1']["Manage Employees"]["Add Employee"] = site_url('employee/add');
		// $menu['est_da1']["Manage Employees"]["View Employee Details"] = site_url('employee/view/menu/est_da1');
		// $menu['est_da1']["Manage Employees"]["Edit Employee Details"] = site_url('employee/edit/menu/est_da1');
		// $menu['est_da1']["Manage Employees"]["Validation Requests"] = site_url('employee/validation');

		$menu['ft']=array();
		// $menu['emp']['Attendance Management']=array();
		// $menu['emp']["Attendance Management"]["Generate Attendance"] = site_url('attendance/student_attendance');
		// $menu['emp']["Attendance Management"]["Attendance"] = site_url('attendance/student_attendance');
		$menu['ft']['R & D Projects']=array();
		$menu['ft']["R & D Projects"]["View your Projects"] = site_url('r_and_d_projects/r_and_d_employee_controller/view_projects_employee/emp');
		$menu['ft']["R & D Projects"]["Enter new Projects"] = site_url('r_and_d_projects/r_and_d_employee_controller/enter_projects_employee/emp');

		//auth ==> est_ar
		/*$menu['est_ar']=array();
		$menu['est_ar']['Employee Details']=array();
		$menu['est_ar']["Employee Details"]["Validation Requests"]=site_url('employee/validation');
		$menu['est_ar']["Employee Details"]["Database Queries"]=array();
		$menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Category"]=site_url('employee/queries/queryByCategory');
		$menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Department"]=site_url('employee/queries/queryByDepartment');
		$menu['est_ar']["Employee Details"]["Database Queries"]["Get Employee By Designation"]=site_url('employee/queries/queryByDesignation');

		//auth ==> deo
		$menu['est_da1']=array();
		$menu['est_da1']['Manage Employees']=array();
		$menu['est_da1']["Manage Employees"]["Add Employee"] = site_url('employee/add');
		$menu['est_da1']["Manage Employees"]["View Employee Details"] = site_url('employee/view/menu/est_da1');
		$menu['est_da1']["Manage Employees"]["Edit Employee Details"] = site_url('employee/edit/menu/est_da1');
		$menu['est_da1']["Manage Employees"]["Validation Requests"] = site_url('employee/validation');*/


		return $menu;
	}
}

/* End of file employee_menu.php */
/* Location: mis/application/models/employee/employee_menu.php */

