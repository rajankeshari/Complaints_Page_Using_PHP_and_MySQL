<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of common_dept_app
 *
 * @author Ritu Raj <rituraj00@rediffmail.com>
 */
class Acad_exam_menu_model extends CI_Model {

    function getMenu() {


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//DEAN ACADEMIC Starts

		$menu['dean_acad']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['dean_acad']['Academic']['Alternate Course']['All'] = site_url('alternate_subject/alternate_subject_all');
		$menu['dean_acad']['Academic']['Alternate Course']['Specific'] = site_url('alternate_subject/alternate_subject');
		$menu['dean_acad']['Academic']["Class Engaged"] = site_url('class_engaged/class_engaged_details');
		$menu['dean_acad']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['dean_acad']['Academic']['Feedback']['Date Open/Close'] = site_url('sem_date_open_close/semester_date_open_close');
		$menu['dean_acad']['Academic']['Feedback']['2019 Onwards']['View Semester Feedback'] = site_url('feedback_cbcs/dean/fbs_select_teacher');
		$menu['dean_acad']['Academic']['Feedback']['2019 Onwards']['View Exit Feedback'] = site_url('feedback_cbcs/dean/fbe_select_session');
		$menu['dean_acad']['Academic']['Feedback']['2015 to 2018']['View Semester Feedback'] = site_url('feedback/dean/fbs_select_teacher');
		$menu['dean_acad']['Academic']['Feedback']['2015 to 2018']['View Exit Feedback'] = site_url('feedback/dean/fbe_select_session');
		$menu['dean_acad']['Academic']['Manage Students']=array();
		$menu['dean_acad']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['dean_acad']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['dean_acad']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['dean_acad']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['dean_acad']['Academic']['PHD'] = array();
        $menu['dean_acad']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['dean_acad']['Academic']['PHD']['Examination']['Individual'] = site_url('jrf_reg/jrf_registration');
        $menu['dean_acad']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
        $menu['dean_acad']['Academic']['PHD']['Ongoing'] = site_url('phdawarded/phdcurrent');
		$menu['dean_acad']['Academic']['PHD']['Status Update'] = site_url('phdawarded/phdawarded');

		$menu['dean_acad']['Academic']['Section Division']=array();
		$menu['dean_acad']['Academic']['Section Division']['Course Wise'] = site_url('student_sec_division/section_course_wise');
		$menu['dean_acad']['Academic']['Section Division']['Create Section'] = site_url('student_sec_division/section_div_form');
		$menu['dean_acad']['Academic']['Section Division']['Edit Section'] = array();
		$menu['dean_acad']['Academic']['Section Division']['Edit Section']['Insert Student'] = site_url('student_sec_division/insert_new');
		$menu['dean_acad']['Academic']['Section Division']['Edit Section']['Update Student Section'] = site_url('student_sec_division/update_section');
		$menu['dean_acad']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		$menu['dean_acad']['Academic']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');

		$menu['dean_acad']['CBCS']['Common (Institute Core) Policy'] = site_url('cbcs_policy/institute_core_policy');
        $menu['dean_acad']['CBCS']['Common (Institute Core) Master'] = site_url('cbcs_master/institute_core_master');
        $menu['dean_acad']['CBCS']['Common Course Structure Policy'] = site_url('cbcs_coursestructure_policy/comm_coursestructure');
		$menu['dean_acad']['CBCS']['Offer Common Course'] = site_url('cbcs_offered_subjects/offer_subject_common');
		$menu['dean_acad']['CBCS']['Credit Point Policy'] = site_url('cbcs_credit_points_policy/credit_point_policy');
	    $menu['dean_acad']['CBCS']['Curriculum Policy'] = site_url('cbcs_curriculam_policy/curriculam');
		$menu['dean_acad']['CBCS']['ESO']['Guided ESO'] = site_url('cbcs_guided_eso/eso_mapping');
		$menu['dean_acad']['CBCS']['ESO']['Offered ESO'] = site_url('cbcs_guided_eso/eso_mapping/offered_eso');
		$menu['dean_acad']['CBCS']['Modular Course'] = site_url('cbcs_coursestructure_policy/modular_course_division');

		//$menu['dean_acad']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		$menu['dean_acad']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		$menu['dean_acad']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		$menu['dean_acad']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
		$menu['dean_acad']['Examination']["Grade Sheet"]["New Version"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['dean_acad']['Examination']["Grade Statistics"]["Overall"] = site_url('course_coordinator/cbcs_monitor_marks_submission/ft_wise_all');
		$menu['dean_acad']['Examination']["Grade Statistics"]["Specific Year"] = site_url('course_coordinator/cbcs_monitor_marks_submission');
		$menu['dean_acad']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		$menu['dean_acad']['Examination']['Makeup Exam Details'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam');
		$menu['dean_acad']['Examination']['Marks Correction'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/marks_coorection');
		$menu['dean_acad']['Examination']['Marks Entry'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/marks_entry');
		$menu['dean_acad']['Examination']['Marks/Grade Reopen']['Grade'] = site_url('course_coordinator/cbcs_marks_upload_control');
        $menu['dean_acad']['Examination']['Marks/Grade Reopen']['Marks Upload'] = site_url('course_coordinator/cbcs_marks_upload_control/marks_upload');
		$menu['dean_acad']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		$menu['dean_acad']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		$menu['dean_acad']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['dean_acad']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['dean_acad']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');
		$menu['dean_acad']['Examination']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		$menu['dean_acad']['Examination']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		$$menu['dean_acad']['Examination']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');
		$menu['dean_acad']['Examination']['Transcript Old Format'] = site_url('transcript_cbcs/transcript_single_old_format');
		$menu['dean_acad']['Examination']['Transcript/Grade Sheet'] = site_url('transcript_cbcs/transcript_single');
		$menu['dean_acad']['Examination']['Transcript Old'] = site_url('transcript/transcript_single');

		$menu['dean_acad']['Leave Management']['Leave Applications'] = site_url('leave/leave_all_kind_application/approving_authority');
		$menu['dean_acad']['Leave Management']['My History'] = site_url('leave/leave_all_kind_application/history_of_authority');

		$menu['dean_acad']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/dean_acad');
		$menu['dean_acad']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/dean_acad');
		$menu['dean_acad']['Notices, Circulars or Minutes']['Edit']['Notice'] = site_url('information/edit_notice/index/dean_acad');
		$menu['dean_acad']['Notices, Circulars or Minutes']['Edit']['Circular'] = site_url('information/edit_circular/index/dean_acad');
		$menu['dean_acad']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['dean_acad']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['dean_acad']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
        $menu['dean_acad']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		$menu['dean_acad']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');

		$menu['dean_acad']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['dean_acad']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['dean_acad']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['dean_acad']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		$menu['dean_acad']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');

		$menu['dean_acad']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['dean_acad']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['dean_acad']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');


		//DEAN ACADEMIC End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ASSOCIATE DEAN - PG Starts

		 $menu['adpg']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		 $menu['adpg']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		 $menu['adpg']['Academic']['Manage Students']=array();
		 $menu['adpg']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		 $menu['adpg']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		 $menu['adpg']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		 $menu['adpg']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		 $menu['adpg']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		 $menu['adpg']['Academic']['PHD'] = array();
         $menu['adpg']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		 $menu['adpg']['Academic']['PHD']['Examination']['Individual'] = site_url('jrf_reg/jrf_registration');
         $menu['adpg']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
         $menu['adpg']['Academic']['PHD']['Ongoing'] = site_url('phdawarded/phdcurrent');
		 $menu['adpg']['Academic']['PHD']['Status Update'] = site_url('phdawarded/phdawarded');
		 $menu['adpg']['Academic']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		 //$menu['adpg']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		 $menu['adpg']['CBCS']['Course Structure Policy'] = site_url('cbcs_coursestructure_policy/coursestructure');

		 $menu['adpg']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		//$menu['adpg']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
			$menu['adpg']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		 $menu['adpg']['Examination']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		 //$menu['adpg']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		 //$menu['adpg']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
     $menu['adpg']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		 $menu['adpg']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		 $menu['adpg']['Examination']['Makeup Exam Details'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam');
		 $menu['adpg']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		 $menu['adpg']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		 $menu['adpg']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		 $menu['adpg']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		 $menu['adpg']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');
		 $menu['adpg']['Examination']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		 $menu['adpg']['Examination']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		 $menu['adpg']['Examination']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');


		 $menu['adpg']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/adpg');
		 $menu['adpg']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/adpg');
		 $menu['adpg']['Notices, Circulars or Minutes']['Edit']['Notice'] = site_url('information/edit_notice/index/adpg');
		 $menu['adpg']['Notices, Circulars or Minutes']['Edit']['Circular'] = site_url('information/edit_circular/index/adpg');
		 $menu['adpg']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		 $menu['adpg']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		 $menu['adpg']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
         $menu['adpg']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		 $menu['adpg']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');

		 $menu['adpg']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		 $menu['adpg']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		 $menu['adpg']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		 $menu['adpg']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		 $menu['adpg']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');
		 $menu['adpg']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		 $menu['adpg']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		 $menu['adpg']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		 // sent by sujit
		 //$menu['adpg']['JRF Registration']['JRF Student View'] = site_url('jrf_reg/jrf_registration');
		 //$menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');
		//ASSOCIATE DEAN - PG End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ASSOCIATE DEAN - UG Starts

		 $menu['adug']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		 $menu['adug']['Academic']["Course Mapping"]= site_url('subject_mapping/cbcs_departmentwise/');
		 $menu['adug']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		 $menu['adug']['Academic']['Manage Students']=array();
		 $menu['adug']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		 $menu['adug']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		 $menu['adug']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		 $menu['adug']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		 $menu['adug']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		 $menu['adug']['Academic']['Section Division']=array();
		 $menu['adug']['Academic']['Section Division']['Course Wise'] = site_url('student_sec_division/section_course_wise');
		 $menu['adug']['Academic']['Section Division']['Create Section'] = site_url('student_sec_division/section_div_form');
		 $menu['adug']['Academic']['Section Division']['Edit Section'] = array();
		 $menu['adug']['Academic']['Section Division']['Edit Section']['Insert Student'] = site_url('student_sec_division/insert_new');
		 $menu['adug']['Academic']['Section Division']['Edit Section']['Update Student Section'] = site_url('student_sec_division/update_section');
		 $menu['adug']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		 $menu['adug']['Academic']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');

		 //$menu['adug']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		 $menu['adug']['CBCS']['Course Structure Policy'] = site_url('cbcs_coursestructure_policy/coursestructure');
		 $menu['adug']['CBCS']['ESO']['Guided ESO'] = site_url('cbcs_guided_eso/eso_mapping');
		 $menu['adug']['CBCS']['ESO']['Offered ESO'] = site_url('cbcs_guided_eso/eso_mapping/offered_eso');
		 $menu['adug']['CBCS']['Modular Course'] = site_url('cbcs_coursestructure_policy/modular_course_division');
		 $menu['adug']['CBCS']['Offer Common Course'] = site_url('cbcs_offered_subjects/offer_subject_common');

		 $menu['adug']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		// $menu['adug']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		 $menu['adug']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		 $menu['adug']['Examination']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		 //$menu['adug']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		 //$menu['adug']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
     $menu['adug']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		 $menu['adug']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		 $menu['adug']['Examination']['Makeup Exam Details'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam');
		 $menu['adug']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		 $menu['adug']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		 $menu['adug']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		 $menu['adug']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		 $menu['adug']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');
		 $menu['adug']['Examination']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		 $menu['adug']['Examination']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		 $menu['adug']['Examination']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');



		 $menu['adug']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/adug');
		 $menu['adug']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/adug');
		 $menu['adug']['Notices, Circulars or Minutes']['Edit']['Notice'] = site_url('information/edit_notice/index/adug');
		 $menu['adug']['Notices, Circulars or Minutes']['Edit']['Circular'] = site_url('information/edit_circular/index/adug');
		 $menu['adug']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		 $menu['adug']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		 $menu['adug']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
         $menu['adug']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		 $menu['adug']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');
		 $menu['adug']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		 $menu['adug']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		  $menu['adug']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		  $menu['adug']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		  $menu['adug']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');
		 $menu['adug']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		 $menu['adug']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['adug']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		
		$menu['dugc']['Additional Program'] = site_url('minor_dm_dd/minor_dm_dd_department');
		//ASSOCIATE DEAN - UG End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ASSOCIATE DEAN - AC Starts

		$menu['adac']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['adac']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['adac']['Academic']["Drop Out Student"]['Mark Drop Out'] = site_url('drop_student/drop_student');
		$menu['adac']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		$menu['adac']['Academic']["Reinstate Student"]['Mark Reinstate'] = site_url('drop_student/reinstate_student');
		$menu['adac']['Academic']["Reinstate Student"]['Report'] = site_url('drop_student/reinstate_student_report');

		$menu['adac']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adac']['Academic']["Manage Students"]['Validation'] = site_url('student/validate');
		$menu['adac']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['adac']['Academic']['Migration Certificate']['Fill Migration'] = site_url('student_migration_certificate/migration_certificate_deo');
        $menu['adac']['Academic']['Migration Certificate']['Issue Migration'] = site_url('student_migration_certificate/migration_print');
        $menu['adac']['Academic']['Migration Certificate']['Migration Status'] = site_url('student_migration_certificate/migration_print_already');
		$menu['adac']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['adac']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['adac']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['adac']['Academic']["Pass Out Student"]['Mark Pass Out'] = site_url('alumni/passout_student');
		$menu['adac']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['adac']['Academic']['Registration/Exam Date Open Close'] = site_url('sem_date_open_close/semester_date_open_close');
		//$menu['adac']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		$menu['adac']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');

		//$menu['adac']['Examination']['CBCS Transcript/Grade Sheet'] = site_url('transcript_cbcs/transcript_single');
		$menu['adac']['Examination']['Course Coordinator Details'] = site_url('course_coordinator/course_coordinator/courseCoordinatorDetails');
	//	$menu['adac']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		$menu['adac']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		$menu['adac']['Examination']['Grade Verification'] = site_url('course_coordinator/course_coordinator/marks_verification_window');
		$menu['adac']['Examination']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		//$menu['adac']['Examination']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');
		//$menu['adac']['Examination']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
    $menu['adac']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['adac']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		$menu['adac']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		$menu['adac']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		$menu['adac']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['adac']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['adac']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');
		$menu['adac']['Examination']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		$menu['adac']['Examination']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		$menu['adac']['Examination']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');


		$menu['adac']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/adac');
		$menu['adac']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/adac');
		$menu['adac']['Notices, Circulars or Minutes']['Edit']['Notice'] = site_url('information/edit_notice/index/adac');
		$menu['adac']['Notices, Circulars or Minutes']['Edit']['Circular'] = site_url('information/edit_circular/index/adac');
		$menu['adac']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['adac']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');
		//$menu['adac']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		$menu['adac']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
        $menu['adac']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		$menu['adac']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');

		$menu['adac']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['adac']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['adac']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['adac']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		$menu['adac']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');

		$menu['adac']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['adac']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['adac']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');

		//ASSOCIATE DEAN - AC End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//DR (Acad) Starts

		$menu['acad_dr']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['acad_dr']['Academic']['Bonafide Student'] = site_url('transcript/bonafide_student');
		//$menu['acad_dr']['Academic']['Course Mapping']['View'] = site_url('');
		$menu['acad_dr']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['acad_dr']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		//$menu['acad_dr']['Academic']["Manage Students"]["Edit Student Details"] = site_url('student/student_edit');
		$menu['acad_dr']['Academic']["Manage Students"]['Validation'] = site_url('student/validate');
		$menu['acad_dr']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['acad_dr']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_dr']['Academic']['Migration Certificate']['Fill Migration'] = site_url('student_migration_certificate/migration_certificate_deo');
        $menu['acad_dr']['Academic']['Migration Certificate']['Issue Migration'] = site_url('student_migration_certificate/migration_print');
        $menu['acad_dr']['Academic']['Migration Certificate']['Migration Status'] = site_url('student_migration_certificate/migration_print_already');
		$menu['acad_dr']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['acad_dr']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['acad_dr']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['acad_dr']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['acad_dr']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['acad_dr']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
        $menu['acad_dr']['Academic']['PHD']['Ongoing'] = site_url('phdawarded/phdcurrent');
		$menu['acad_dr']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		$menu['acad_dr']['Academic']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		//$menu['acad_dr']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		$menu['acad_dr']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		$menu['acad_dr']['Examination']['Course Coordinator Details'] = site_url('course_coordinator/course_coordinator/courseCoordinatorDetails');
		//$menu['acad_dr']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		$menu['acad_dr']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		$menu['acad_dr']['Examination']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		$menu['acad_dr']['Examination']['Exam Hall Stickers'] = site_url('exam_slip_generation/exam_slip');
		$menu['acad_dr']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		$menu['acad_dr']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
		$menu['acad_dr']['Examination']["Grade Sheet"]["New Version"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');

		$menu['acad_dr']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		$menu['acad_dr']['Examination']['Hall Tickets'] = site_url('student_hall_ticket/hall_ticket');
		$menu['acad_dr']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		$menu['acad_dr']['Examination']['Provisional Certificate'] = site_url('transcript/pro_certificate');
		$menu['acad_dr']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		$menu['acad_dr']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['acad_dr']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['acad_dr']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');
		$menu['acad_dr']['Examination']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		$menu['acad_dr']['Examination']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		$menu['acad_dr']['Examination']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');

		$menu['acad_dr']['Examination']['Transcript/Grade Sheet'] = site_url('transcript_cbcs/transcript_single');
		$menu['acad_dr']['Examination']['Transcript Old Format'] = site_url('transcript_cbcs/transcript_single_old_format');

		$menu['acad_dr']['Leave Management']['Leave Applications'] = site_url('leave/leave_all_kind_application/approving_authority');
		$menu['acad_dr']['Leave Management']['My History'] = site_url('leave/leave_all_kind_application/history_of_authority');

		$menu['acad_dr']['Miscellaneous']['Feedback Fine'] = site_url('student_grade_sheet/dr_gs_remove_feedback');

		$menu['acad_dr']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/acad_dr');
		$menu['acad_dr']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/acad_dr');
		$menu['acad_dr']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['acad_dr']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['acad_dr']['Registration']['Open/Close Date'] = site_url('sem_date_open_close/semester_date_open_close');
		$menu['acad_dr']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');

		$menu['acad_dr']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['acad_dr']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_dr']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['acad_dr']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['acad_dr']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['acad_dr']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');

		$menu['acad_dr']['Offline Process']["Drop Out Student"]['Mark Drop Out'] = site_url('drop_student/drop_student');
		$menu['acad_dr']['Offline Process']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		$menu['acad_dr']['Offline Process']["Reinstate Student"]['Mark Reinstate'] = site_url('drop_student/reinstate_student');
		$menu['acad_dr']['Offline Process']["Reinstate Student"]['Report'] = site_url('drop_student/reinstate_student_report');
		$menu['acad_dr']['Offline Process']['New Registration'] = site_url('mis_dashboard/stu_registration_new');
		$menu['acad_dr']['Offline Process']['Edit Student Details'] = site_url('student_all_details/edit_student_details');
		
		//$menu['acad_dr']['Additional Program']['Set Criteria'] = site_url('minor_dm_dd/set_criteria_academic');
		//$menu['acad_dr']['Additional Program']['Update Criteria'] = site_url('minor_dm_dd/minor_dm_dd_academic');
		//DR (E&A) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//AR (Acad) - UG Starts

		$menu['acad_arug']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		//$menu['acad_arug']['Academic']['Course Mapping']['View'] = site_url('');
		$menu['acad_arug']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['acad_arug']['Academic']["Drop Out Student"]['Mark Drop Out'] = site_url('drop_student/drop_student');
		$menu['acad_arug']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		//$menu['acad_arug']['Academic']["Manage Students"]["Edit Student Details"] = site_url('student/student_edit');
		$menu['acad_arug']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['acad_arug']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_arug']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['acad_arug']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['acad_arug']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['acad_arug']['Academic']["Pass Out Student"]['Mark Pass Out'] = site_url('alumni/passout_student');
		$menu['acad_arug']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['acad_arug']['Academic']['Section Division']['Course Wise'] = site_url('student_sec_division/section_course_wise');
		$menu['acad_arug']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		//$menu['acad_arug']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
		$menu['acad_arug']['Academic']["Reinstate Student"]['Mark Reinstate'] = site_url('drop_student/reinstate_student');
		$menu['acad_arug']['Academic']["Reinstate Student"]['Report'] = site_url('drop_student/reinstate_student_report');
		$menu['acad_arug']['Academic']['Alternate Course']['All'] = site_url('alternate_subject/alternate_subject_all');
		$menu['acad_arug']['Academic']['Alternate Course']['Specific'] = site_url('alternate_subject/alternate_subject');


		$menu['acad_arug']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		//$menu['acad_arug']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		 $menu['acad_arug']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		//$menu['acad_arug']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		//$menu['acad_arug']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
      $menu['acad_arug']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['acad_arug']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['acad_arug']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['acad_arug']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');

		$menu['acad_arug']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/acad_arug');
		$menu['acad_arug']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/acad_arug');
		$menu['acad_arug']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['acad_arug']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['acad_arug']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
		$menu['acad_arug']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');

		$menu['acad_arug']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['acad_arug']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_arug']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['acad_arug']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['acad_arug']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['acad_arug']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		//AR (E&A) - UG End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//AR (Acad) - PG Starts

		$menu['acad_arpg']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['acad_arpg']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['acad_arpg']['Academic']["Drop Out Student"]['Mark Drop Out'] = site_url('drop_student/drop_student');
		$menu['acad_arpg']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		//$menu['acad_arpg']['Academic']["Manage Students"]["Edit Student Details"] = site_url('student/student_edit');
		$menu['acad_arpg']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['acad_arpg']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_arpg']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['acad_arpg']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['acad_arpg']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['acad_arpg']['Academic']["Pass Out Student"]['Mark Pass Out'] = site_url('alumni/passout_student');
		$menu['acad_arpg']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['acad_arpg']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['acad_arpg']['Academic']['PHD']['Examination']['Individual'] = site_url('jrf_reg/jrf_registration');
        $menu['acad_arpg']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
        $menu['acad_arpg']['Academic']['PHD']['Ongoing'] = site_url('phdawarded/phdcurrent');
		$menu['acad_arpg']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		//$menu['acad_arpg']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
		$menu['acad_arpg']['Academic']["Reinstate Student"]['Mark Reinstate'] = site_url('drop_student/reinstate_student');
		$menu['acad_arpg']['Academic']["Reinstate Student"]['Report'] = site_url('drop_student/reinstate_student_report');

		$menu['acad_arpg']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		//$menu['acad_arpg']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		 $menu['acad_arpg']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		//$menu['acad_arpg']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		//$menu['acad_arpg']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
    $menu['acad_arpg']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['acad_arpg']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['acad_arpg']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['acad_arpg']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');

		$menu['acad_arpg']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/acad_arpg');
		$menu['acad_arpg']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/acad_arpg');
		$menu['acad_arpg']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['acad_arpg']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['acad_arpg']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
		$menu['acad_arpg']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		$menu['acad_arpg']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['acad_arpg']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_arpg']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['acad_arpg']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['acad_arpg']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['acad_arpg']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		//AR (E&A) - PG End



//********************************************************************************************************************************************************
//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		// ACAD & EXAM Starts

		$menu['acad_so']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['acad_so']['Academic']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['acad_so']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		$menu['acad_so']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['acad_so']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_so']['Academic']['Migration Certificate']['Fill Migration'] = site_url('student_migration_certificate/migration_certificate_deo');
        $menu['acad_so']['Academic']['Migration Certificate']['Issue Migration'] = site_url('student_migration_certificate/migration_print');
        $menu['acad_so']['Academic']['Migration Certificate']['Migration Status'] = site_url('student_migration_certificate/migration_print_already');
		$menu['acad_so']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['acad_so']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['acad_so']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['acad_so']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['acad_so']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['acad_so']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
		$menu['acad_so']['Academic']['PHD']['Examination']['Individual'] = site_url('phd_thesis/phd_thesis');
		$menu['acad_so']['Academic']['PHD']['Ongoing'] = site_url('phdawarded/phdcurrent');
		$menu['acad_so']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		$menu['acad_so']['Academic']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		//$menu['acad_so']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		$menu['acad_so']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
	//	$menu['acad_so']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		$menu['acad_so']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
	//	$menu['acad_so']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
	//	$menu['acad_so']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
  $menu['acad_so']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['acad_so']['Examination']['Performance Sheet'] = site_url('rank_list/rank_main');
		$menu['acad_so']['Examination']['Provisional Certificate'] = site_url('transcript/pro_certificate');
		$menu['acad_so']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');
		$menu['acad_so']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['acad_so']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['acad_so']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');

		$menu['acad_so']['Miscellaneous']['Feedback Fine'] = site_url('student_grade_sheet/dr_gs_remove_feedback');

		$menu['acad_so']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['acad_so']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['acad_so']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		$menu['acad_so']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['acad_so']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_so']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['acad_so']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['acad_so']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');

		// ACAD & EXAM  End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ACAD & EXAM (Reports)   starts

		$menu['acad_exam']['Academic']['Academic Performance'] = site_url('cbcs_makeup_exam/cbcs_makeup_exam/acadmic_performance');
		$menu['acad_exam']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		$menu['acad_exam']['Academic']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['acad_exam']['Academic']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_exam']['Academic']['Migration Certificate']['Migration Status'] = site_url('student_migration_certificate/migration_print_already');
		$menu['acad_exam']['Academic']['OBC Subcategory'] = site_url('acad_report/academic_report');
		$menu['acad_exam']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['acad_exam']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['acad_exam']['Academic']["Pass Out Student"]['Report'] = site_url('alumni/passout_student_report');
		$menu['acad_exam']['Academic']['PHD']['Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['acad_exam']['Academic']['PHD']['Examination']['Individual'] = site_url('phd_thesis/phd_thesis');
		$menu['acad_exam']['Academic']['PHD']['Examination']['Report'] = site_url('jrf_reg/jrf_registration/showreport');
		$menu['acad_exam']['Academic']['Section Division']['View Section'] = site_url('student_sec_division/section_view_details');
		//$menu['acad_exam']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));

		$menu['acad_exam']['Examination']['Backlog Details']=site_url('registration_report/registration_report/index/backpaper');
		//$menu['acad_exam']['Examination']['Course Count(Non-CBCS)'] = site_url('misextra/student_registration_details');
		$menu['acad_exam']['Examination']['Course Count'] = site_url('misextra/student_registration_details_new');
		//$menu['acad_exam']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		//$menu['acad_exam']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet');
		$menu['acad_exam']['Examination']["Grade Sheet"]  = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		$menu['acad_exam']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['acad_exam']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');
		$menu['acad_exam']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');

		$menu['acad_exam']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['acad_exam']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');

		$menu['acad_exam']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
		$menu['acad_exam']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['acad_exam']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['acad_exam']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['acad_exam']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		$menu['acad_exam']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');

		$menu['acad_exam']['Registration']['2019 Onwards']['Unregistered Candidate']=site_url('unregister_candidates/unregister_candidates');
		$menu['acad_exam']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		$menu['acad_exam']['Registration']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');
		$menu['acad_exam']['Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');

		//ACAD & EXAM (Reports) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************

		//ACAD (Certificates) Starts

		$menu['acad_certi']['Bonafide Student'] = site_url('transcript/bonafide_student');

		//ACAD (Certificates) End

//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ACAD (ID Cards) Starts

		$menu['acad_id']['Download Data'] = site_url('change_photo/student_change_photo_admin_side');

		//ACAD (ID Cards) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ACAD (Ph.D) Starts

		$menu['acad_phd']['DSC Details']['Add'] = site_url('phdawarded/dsc_entry');
		$menu['acad_phd']['DSC Details']['Edit'] = site_url('phdawarded/edit_dsc_entry');
		$menu['acad_phd']['Ongoing'] = site_url('phdawarded/phdcurrent');
		//$menu['acad_phd']['Academic']["Drop Out Student"]['Mark Drop Out'] = site_url('drop_student/drop_student');
		$menu['acad_phd']['Academic']["Drop Out Student"]['Report'] = site_url('drop_student/drop_student_report');
		//$menu['acad_phd']['Academic']["Reinstate Student"]['Mark Reinstate'] = site_url('drop_student/reinstate_student');
		$menu['acad_phd']['Academic']["Reinstate Student"]['Report'] = site_url('drop_student/reinstate_student_report');
		$menu['acad_phd']['Course Work'] = site_url('phdawarded/phd_course_details');
		$menu['acad_phd']['Date of Admission Updation'] = site_url('phdawarded/student_admission_year');

		//ACAD (Ph.D) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//ACAD (Section) Starts

		$menu['acad_sec']['Section Division']['Course Wise'] = site_url('student_sec_division/section_course_wise');

		//ACAD (Section) End



//********************************************************************************************************************************************************
//********************************************************************************************************************************************************

		//ACAD (Students) Starts

		$menu['acad_stu']['Defaulters'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['acad_stu']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');

		//ACAD (Students) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//EXAM (Certificates) Starts


		$menu['exam_certi']['Migration Certificate']['Fill Migration'] = site_url('student_migration_certificate/migration_certificate_deo');
        $menu['exam_certi']['Migration Certificate']['Issue Migration'] = site_url('student_migration_certificate/migration_print');
        $menu['exam_certi']['Migration Certificate']['Migration Status'] = site_url('student_migration_certificate/migration_print_already');


		//EXAM (Certificates) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************


		//EXAM (Ph.D) Starts


		 $menu['exam_phd']['PHD Status Update'] = site_url('phdawarded/phdawarded');


		//EXAM (Ph.D) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************

		//EXAM (Results) Starts

		$menu['exam_res']['Grade Sheet']['Bunch Print'] = site_url( 'student_grade_sheet/bunch_print_gradesheet');
		//$menu['exam_res']['Examination']["Grade Sheet"]  = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
		//Permitted by dean academic on 22-06-2020
		$menu['exam_res']['Performance Sheet'] = site_url('rank_list/rank_main');
		$menu['exam_res']['Tabulation']['2019 onwards'] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		$menu['exam_res']['Tabulation']['2015 to 2018'] = site_url('exam_tabulation/exam_tabulation');
		$menu['exam_res']['Tabulation']['Student Wise'] = site_url('exam_tabulation/exam_tabulation/individual');
		$menu['exam_res']['Transcript/Grade Sheet'] = site_url('transcript_cbcs/transcript_single');
		$menu['exam_res']['Transcript Old Format'] = site_url('transcript_cbcs/transcript_single_old_format');
		$menu['exam_res']['Transcript Old'] = site_url('transcript/transcript_single');




		//EXAM (Results) End
		//Dipankar Sir
		$menu['admin_exam']['Transcript/Grade Sheet'] = site_url('transcript_cbcs/transcript_single');
		$menu['admin_exam']['Transcript Old Format'] = site_url('transcript_cbcs/transcript_single_old_format');


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************

		//EXAM (Specific) Starts

		$menu['exam_spec']['Examination']['Exam Hall Stickers'] = site_url('exam_slip_generation/exam_slip');
		$menu['exam_spec']['Examination']['Hall Tickets'] = site_url('student_hall_ticket/hall_ticket');

		//EXAM (Specific) End


//********************************************************************************************************************************************************
//********************************************************************************************************************************************************
//********************************************************************************************************************************************************

		// For Parent Credential
		//$menu['parent_incharge'] = array();
        //$menu['parent_incharge']['Parent Credentials'] = array();
       // $menu['parent_incharge']['Parent Credentials'] = site_url('parent_credential/parent_credential');
	   $menu['rank_so']['Examination']['Rank List'] = site_url('rank_list/rank_list_new');

	   //For Aashish Sarkar Sir

	   $menu['acad_head']['Registration']['2019 Onwards']['Final Registration']['Add/Remove Course']=site_url('student_registration/student_registration');
        $menu['acad_head']['Registration']['2019 Onwards']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');
	   $menu['acad_head']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');
	   $menu['acad_head']['New Registration'] = site_url('mis_dashboard/stu_registration_new');


	   //Faculty

		$menu['ft']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['ft']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['ft']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['ft']['Registration']['2019 Onwards']["Registration Report"]["Session Wise1"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		$menu['ft']['Registration']['2019 Onwards']["Registration Report"]["Session Wise2"] = site_url('mis_dashboard/stu_registration_newreport');

    $menu['cm']['Optional Offering']['Minor'] = site_url('cbcs_offered_courses/cbcs_minor_course_offered');
	  $menu['cm']['Optional Offering']['Double Major'] = site_url('cbcs_offered_courses/cbcs_double_major_course_offered');
	  $menu['cm']['Optional Offering']['Dual Degree'] = site_url('cbcs_offered_courses/cbcs_dualdegree_course_offered');


		
		
		


	return $menu;    }

}

?>
