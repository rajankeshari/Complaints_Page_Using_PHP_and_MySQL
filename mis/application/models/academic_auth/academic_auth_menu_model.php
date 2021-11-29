<?php

class Academic_auth_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
		//auth ==> hod,
		//$menu['acad_ar']=array();
		//$menu['acad_ar']["Assign Roles"]["Dealing Assistant"] = site_url('academic_auth/authcontrol');
		//$menu['acad_ar']["Semester Registration"]= site_url('semester_registration/semester_registration');
		//$menu['acad_ar']["Semester Fee Interface"]= site_url('semester_fee_interface/semester_fee_interface');
		
		//auth ==> hod,
		//$menu['acad_dr']=array();
		//$menu['acad_dr']["Assign Roles"]["Dealing Assistant"] = site_url('academic_auth/authcontrol');
	//	$menu['acad_dr']["Semester Registration"]= site_url('semester_registration/semester_registration');
		//$menu['acad_dr']["Semester Fee Interface"]= site_url('semester_fee_interface/semester_fee_interface');
		//auth ==> exam_dr,
		/*$menu['exam_dr']["Assign Roles"]["Dealing Assistant"] = site_url('academic_auth/authcontrol');
		$menu['exam_dr']["Semester Registration"]= site_url('semester_registration/semester_registration');
		$menu['exam_dr']["Semester Fee Interface"]= site_url('semester_fee_interface/semester_fee_interface');
		*/
		$menu['admin_exam']['Course Mapping 2020-2021'] = site_url('cbcs_offered_courses/offered_courses/course_mapping');

		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */