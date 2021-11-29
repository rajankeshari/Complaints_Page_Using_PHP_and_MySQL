<?php

class Student_sem_form_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$this->load->model('student_sem_form/sbasic_model');
		if($this->sbasic_model->checkDate()){
		$menu=array();
		//auth ==> stu
		$menu['stu']=array();
		$menu['stu']['Semester Registration']=array();
		$menu['stu']["Semester Registration"]["Regular Registration / Status"] = site_url('student_sem_form/regular_form');
                
                $menu['stu']["Semester Registration"]["Idle Registration / Status"] = site_url('idle_reg_form/reg_form');
                $menu['stu']["Semester Registration"]["Other Registration / Status"] = site_url('other_reg_form/reg_form');
		}
                $menu['stu']["Semester Registration"]["Honours / Minor "] = site_url('honourminor/main');
                 $menu['stu']["Semester Registration"]["Summer Registration / Status"] = site_url('summer_reg_form/reg_form');
                //auth ==> hod
		$menu['hod']=array();
		$menu['hod']["Semester Approved"]["Regular Registration"] = site_url('student_sem_form/regular_check');
                $menu['hod']["Semester Approved"]["Idle Registration"] = site_url('idle_reg_form/department_check');
                $menu['hod']["Semester Approved"]["Summer Registration"] = site_url('summer_reg_form/department_check');
                $menu['hod']["Semester Approved"]["Other Registration"] = site_url('other_reg_form/department_check');
				$menu['hod']["Semester Approved"]["Honours / Minor "] = site_url('honourminor/department');
		
		//auth ==> acad_ar,
		$menu['acad_ar']=array();
		$menu['acad_ar']["Semester date"]["Set Semester Reg.Date"] = site_url('student_sem_form/date');
		$menu['acad_ar']["Semester Approved"]["Regular Registration"] = site_url('student_sem_form/regular_check_acdamic');
                $menu['acad_ar']["Semester Approved"]["Idle Registration"] = site_url('idle_reg_form/acdamic_check');
                $menu['acad_ar']["Semester date"]["Summer Reg. Date"] = site_url('summer_reg_form/date');
		$menu['acad_ar']["Semester Approved"]["Summer Registration"] = site_url('summer_reg_form/acdamic_check');
                $menu['acad_ar']["Semester Approved"]["Other Registration"] = site_url('other_reg_form/acdamic_check');
				 $menu['acad_ar']["Semester Report"]["Summer Reg. Report"] = site_url('student_sem_report/semreport');
				  $menu['acad_ar']["Semester Report"]["Regular Reg. Report"] = site_url('student_sem_report/semreport/regreport');
		
                //auth ==> fa
		$menu['fa']=array();
		$menu['fa']["Semester Approved"]["Semester Form"] = site_url('student_sem_form/regular_check');
                
                //auth ==> acad_da2
                $menu['acad_da2']=array();
		$menu['acad_da2']["Semester Approved"]["Semester Form"] = site_url('student_sem_form/regular_check_auth_acdamic');
		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */