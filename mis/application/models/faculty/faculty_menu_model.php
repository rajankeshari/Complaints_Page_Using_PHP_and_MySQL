<?php

class Faculty_menu_model extends CI_Model
{
	function __construct()
	{
		
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		$menu['ft']=array();
		$menu['ft']['Academic']['Attendance Sheet']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/');
		$menu['ft']['Academic']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/cbcs_attendance_new/');
		$menu['ft']['Academic']['Attendance Sheet']['Upload Attendance Sheet'] = site_url('upload/cbcs_upload_attendance_new/');
		$menu['ft']['Academic']['Class Materials'] = site_url('faculty_tutorials/choose_syear_sess_new/');
		$menu['ft']['Academic']['Course Mapping Status']= site_url('subject_mapping/faculty_sm_status/');
		$menu['ft']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['ft']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['ft']['Academic']['Time Table']['CBCS']= site_url('time_table/view_time_table');
		$menu['ft']['Academic']['Time Table']['Non-CBCS']= site_url('time_table/view_time_table_old');
		$menu['ft']['Academic']['Online Classes']['Quiz Detail']= site_url('online_class/quiz_details_new');
		$menu['ft']['Academic']['Online Classes']['Teaching Tools']= site_url('online_class/teaching_tool_new');
		$menu['ft']['Academic']['Course-Faculty']= site_url('mis_dashboard/subject_faculty');
		$menu['ft']['Disciplinary Status'] = site_url('dsw_disciplinary_action/faculty_search/faculty_search_students_details');   
		
		$menu['ft']['Examination']['Course Coordinator']['Details'] = site_url('course_coordinator/course_coordinator/courseCoordinatorDetails');
		$menu['ft']['Examination']['Course Coordinator']['Perform Grading'] = site_url('course_coordinator/course_coordinator');   
		$menu['ft']['Examination']['Makeup Exam Marks Upload'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/makeup_exam_marks_upload');
		$menu['ft']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');   
		$menu['ft']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');   
		$menu['ft']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');   
		$menu['ft']['Examination']['Weightage & Marks Upload'] = site_url('common_dept_app/common_dept_app');   
		$menu['dpgc']['Examination']['Thesis Evaluation'] = site_url('common_dept_app/common_dept_app/thesis_evaluation'); 
		
		$menu['ft']['Feedback']['2019 Onwards']['Semester Feedback'] = site_url('feedback_cbcs/faculty');
		$menu['ft']['Feedback']['2019 Onwards']['Check Feedback Courses'] = site_url('feedback_cbcs/check_feedback_subjects_faculty');
		$menu['ft']['Feedback']['2015 to 2018']['Semester Feedback'] = site_url('feedback/faculty');
		$menu['ft']['Feedback']['2015 to 2018']['Check Feedback Courses'] = site_url('feedback/check_feedback_subjects_faculty');
		
		$menu['ft']['Research']['Project Management']['Project']['View Project']= site_url('accounts_project/view_project');
		$menu['ft']['Research']['Project Management']['Funding Agency']['View Agency']= site_url('accounts_project/funding_agency/view_funding_agency');
		$menu['ft']['Research']['Project Management']['Funds Management']['Add New Bill']= site_url('accounts_project/billing');
		$menu['ft']['Research']['Project Management']['Funds Management']['Bill History']= site_url('accounts_project/billing/view_history');
		
		$menu['ft']['Web User']['Edit Faculty'] = site_url('web_admin/web_admin/edit_web_profile');
		$menu['ft']['Web User']['Project Opening'] = site_url('web_admin/web_admin/project_opening');
		$menu['ft']['Web User']['Upload Notices'] = site_url('web_admin/web_admin/upload_notices');
		
		
		return $menu;
	}
}
