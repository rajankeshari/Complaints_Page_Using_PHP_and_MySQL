<?php


class Feedback_cbcs_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the model constructor
		parent:: __construct();
	}
	function getMenu()
	{

		//===============================STUDENT END=====Closed from here check in cbcs_makeup_exam_menu_model==========================================
		//$menu['stu'] = array();
		//$menu['stu']['Feedback'] = array();
		//$menu['stu']['CBCS Feedback']['Semester Feedback'] = site_url('feedback_cbcs/cbcs_check_feedback_subjects');
		//$menu['stu']['CBCS Feedback']['Semester Feedback Receipt'] = site_url('feedback_cbcs/cbcs_student/semester_feedback_receipt_form');
		//$menu['stu']['CBCS Feedback']['Exit Feedback'] = site_url('feedback_cbcs/cbcs_student/fill_exit_feedback');
		//$menu['stu']['CBCS Feedback']['Exit Feedback Receipt'] = site_url('feedback_cbcs/cbcs_student/exit_feedback_receipt');

		//===============================FACULTY END==========================================================================

		//$menu['ft'] = array();
		//$menu['ft']['Feedback'] = array();
	//	$menu['ft']['Feedback']['Semester Feedback'] = site_url('feedback_cbcs/faculty');
		//$menu['ft']['Feedback']['Check Feedback Subjects'] = site_url('feedback_cbcs/check_feedback_subjects_faculty');


//===============================HID END==========================================================================
		//$menu['hod'] = array();
		//$menu['hod']['Feedback'] = array();
		//$menu['hod']['Feedback']['View Semester Feedback'] = site_url('feedback_cbcs/hod/fbs_select_teacher');
		

//===============================DEAN ACADEMIC END==========================================================================
		//$menu['dean_acad'] = array();
		//$menu['dean_acad']['Feedback'] = array();
		//$menu['dean_acad']['Feedback']['View Semester Feedback'] = site_url('feedback_cbcs/dean/fbs_select_teacher');
		//$menu['dean_acad']['Feedback']['View Exit Feedback'] = site_url('feedback_cbcs/dean/fbe_select_session');
		
		$menu['dt'] = array();
		$menu['dt']['Feedback'] = array();
		$menu['dt']['Feedback']['View Semester Feedback'] = site_url('feedback_cbcs/dean/fbs_select_teacher');
		$menu['dt']['Feedback']['View Exit Feedback'] = site_url('feedback_cbcs/dean/fbe_select_session');
		$menu['dt']['Feedback']['Feedback Report'] = site_url('feedback_cbcs/feedback_report');


		return $menu;
	}
}
