<?php

class Student_sem_form_all_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
				
		//$menu['stu']=array();
		//$menu['stu']['Semester Registration']=array();
		//$menu['stu']['Semester Registration'] ['Register'] = site_url('semreg_all/semester_registration_tabs');
		//$menu['stu']["Registration For Online Course"] = site_url('student_sem_form_all/online_reg_course');
		
		//$menu['hod']=array();
		//$menu['hod']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		//$menu['hod']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		//$menu['dean_acad']=array();
		//$menu['dean_acad']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		//$menu['dean_acad']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		$menu['exam_dr']=array();
		$menu['exam_dr']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['exam_dr']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		$menu['acad_ar']=array();
		$menu['acad_ar']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_ar']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		//$menu['adug']=array();
		//$menu['adug']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		//$menu['adug']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		//$menu['adpg']=array();
		//$menu['adpg']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		//$menu['adpg']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		$menu['dugc']=array();
		$menu['dugc']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['dugc']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		$menu['dpgc']=array();
		$menu['dpgc']["Registration Report"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['dpgc']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		
		//$menu['sw_ar']["Semester Registration"]["Programme Wise"] = site_url('student_sem_form_all/viewandprint_all');
		
		
		
		
		
	
		
		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */
