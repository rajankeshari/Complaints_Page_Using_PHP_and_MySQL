<?php

class hod_menu_model extends CI_Model
{
	function __construct()
	{
		
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		$menu['hod']=array();
		
		$menu['hod']['Academic']["Class Engaged"] = site_url('class_engaged/class_engaged_details');
		$menu['hod']['Academic']['Class Materials'] = site_url('faculty_tutorials/assignment_admin_side');
		$menu['hod']['Academic']["Course Mapping"]= site_url('subject_mapping/cbcs_departmentwise/');
		$menu['hod']['Academic']['Delete Attendance']= site_url('attendance/cbcs_delete_attendance_new/');
		$menu['hod']['Academic']["Disciplinary Action"]['View All Student Actions'] = site_url('dsw_disciplinary_action/hod_students_actions/all_actions_by_department');
		$menu['hod']['Academic']['Feedback']['2019 Onwards']['View Semester Feedback'] = site_url('feedback_cbcs/hod/fbs_select_teacher');
		$menu['hod']['Academic']['Feedback']['2015 to 2018']['View Semester Feedback'] = site_url('feedback/hod/fbs_select_teacher');
	//	$menu['hod']['Academic']['Feedback']['Old Feedback']['Running Semester Feedback'] = site_url('feedback/hod/fbr_select_teacher');
		$menu['hod']['Academic']["Non-CBCS Course Offer"]['Non CBCS']= site_url('non_cbcs/non_cbcs');
		$menu['hod']['Academic']["Non-CBCS Course Offer"]['Modular']= site_url('cbcs_coursestructure_policy/modular_course_division_noncbcs');
		$menu['hod']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['hod']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['hod']['Academic']['Time Table']['CBCS']= site_url('time_table/view_time_table');
		$menu['hod']['Academic']['Time Table']['Non-CBCS']= site_url('time_table/view_time_table_old');
		$menu['hod']['Academic']['Time Table']['Venue Information']= site_url('venue_edit/venue_edit');
		//$menu['hod']['Academic']["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
		$menu['hod']['Attendance']['Attendance Distribution'] = site_url('attendance_distribution/distribution');
		
		$menu['hod']["Assign Roles"]["DPGC Convener"] = site_url('department_auth/dpgc_main');
		$menu['hod']["Assign Roles"]["DUGC Convener"] = site_url('department_auth/dugc_main');
		$menu['hod']["Assign Roles"]["Time Table In-charge"] = site_url('department_auth/ttcauthcontrol');		
		
		$menu['hod']['Bookings']["SAH Booking"]["Employee"] = site_url('sah_booking/booking_request/app_list/hod');
		$menu['hod']['Bookings']["SAH Booking"]["Student"] = site_url('sah_booking/booking_request/app_list_stu/hod');
		$menu['hod']['Bookings']["Vehicle Booking"] = site_url('car_booking/booking/view_all_hod');   
		
		$menu['hod']["CBCS"]["Assign Course Coordinator"] = site_url('cbcs_offered_subjects/offer_subject/course_coordinator');
		//$menu['hod']["CBCS"]["Course Component Deletion"] = site_url('marks_distribution/marks_distribution/course_component');
		$menu['hod']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
		$menu['hod']["CBCS"]["Credit Point Master"] = site_url('cbcs_credit_points_master/credit_point_master');
		$menu['hod']["CBCS"]["Curriculum Master"] = site_url('cbcs_curriculam_master/curriculammaster');
		$menu['hod']["CBCS"]["Offer Course"] = site_url('cbcs_offered_subjects/offer_subject');
		$menu['hod']["CBCS"]["Online Course"]["Offer Online Course"] = site_url('cbcs_subject_master/subject_master_dept/offer_online_course');
		$menu['hod']["CBCS"]["Online Course"]["Approve Registration"] = site_url('cbcs_subject_master/subject_master_dept/approve_online_course');
		
		$menu['hod']['Examination']["Exam Attendance Sheet"] = site_url('attendance/exam_attendance/exam_attendance_hod');
		//$menu['hod']['Examination']["Grade Sheet"]["2019 Onwards"] = site_url('cbcs_grade_sheet/dr_grade_sheet');
		//$menu['hod']['Examination']["Grade Sheet"]["2015 to 2018"] = site_url('student_grade_sheet/dr_grade_sheet'); 
		$menu['hod']['Examination']["Grade Sheet"] = site_url('cbcs_grade_sheet/dr_grade_sheet/new_grade_sheet');
	//	$menu['hod']['Examination']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		$menu['hod']['Examination']['Result']['2019 Onwards'] = site_url('result_declaration/result_declaration_drside/index/cbcs');   
		$menu['hod']['Examination']['Result']['2017 to 2018'] = site_url('result_declaration/result_declaration_drside');   
		$menu['hod']['Examination']['Result']['2015 to 2016'] = site_url('result_declaration-old-ver/result_declaration_drside');   
		
		$menu['hod']["Leave Management"]["Leave Applications"] = site_url('leave/leave_all_kind_application/approving_authority');
		$menu['hod']["Leave Management"]["Leave History"] = site_url('leave/leave_all_kind_application/history_of_authority');
		
		$menu['hod']['Notices, Circulars or Minutes']['Post']['Notice'] = site_url('information/post_notice/index/hod');
		$menu['hod']['Notices, Circulars or Minutes']['Post']['Circular'] = site_url('information/post_circular/index/hod');
		//$menu['hod']['Notices, Circulars or Minutes']['Post']['Minutes'] = site_url('information/post_minute/index/hod');
		$menu['hod']['Notices, Circulars or Minutes']['Edit']['Notice'] = site_url('information/edit_notice/index/hod');
		$menu['hod']['Notices, Circulars or Minutes']['Edit']['Circular'] = site_url('information/edit_circular/index/hod');
		//$menu['hod']['Notices, Circulars or Minutes']['Edit']['Minutes'] = site_url('information/edit_minute/index/hod');
		$menu['hod']['Notices, Circulars or Minutes']['View']['Notice'] = site_url('information/view_notice');
		$menu['hod']['Notices, Circulars or Minutes']['View']['Circular'] = site_url('information/view_circular');
		//$menu['hod']['Notices, Circulars or Minutes']['View']['Minutes'] = site_url('information/view_minute');
		
		$menu['hod']["Project Guide"]["Assign Guide(For JRF)"] = site_url('fellowship/fellowshipProcess/get_fellow_list');
		$menu['hod']["Project Guide"]["Assign Guide(For PG/UG)"] = site_url('project_guide/project_guide/manage_list');
		
		$menu['hod']['Registration']['2019 Onwards']['Pre Registration'] = site_url('pre_registration/student_registration');
		$menu['hod']['Registration']['2019 Onwards']["Registration Report"]["Course Wise"] = site_url('student_sem_form_all/viewandprint_all_cwise');
		$menu['hod']['Registration']['2019 Onwards']["Registration Report"]["Program Wise"] = site_url('student_sem_form_all/viewandprint_all');
		$menu['hod']['Registration']['2019 Onwards']["Registration Report"]["Student Wise"] = site_url('mis_dashboard/stu_registration_history');
		$menu['hod']['Registration']['2019 Onwards']["Registration Report"]["Session Wise"] = site_url('student_sem_form_all/viewandprint_all/session_wise');
		//$menu['hod']['Registration']['2019 Onwards']['Student Registration'] = site_url('student_registration/student_registration');
		$menu['hod']['Registration']['2015 to 2018']['Semester Registration']=site_url('mis_dashboard/stu_registration');
		
		// Course Master By @bhijeet
		$menu['cm']['CBCS']['Set Department'] = site_url('cbcs_subject_master/subject_master_dept/setDepartment');
		$menu['cm']["CBCS"]["Assign Course Coordinator"] = site_url('cbcs_offered_subjects/offer_subject/course_coordinator');
		//$menu['hod']["CBCS"]["Course Component Deletion"] = site_url('marks_distribution/marks_distribution/course_component');
		$menu['cm']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
		$menu['cm']["CBCS"]["Credit Point Master"] = site_url('cbcs_credit_points_master/credit_point_master');
		$menu['cm']["CBCS"]["Curriculum Master"] = site_url('cbcs_curriculam_master/curriculammaster');
		$menu['cm']["CBCS"]["Offer Course"] = site_url('cbcs_offered_subjects/offer_subject');
		$menu['cm']["CBCS"]["Online Course"]["Offer Online Course"] = site_url('cbcs_subject_master/subject_master_dept/offer_online_course');
	//	$menu['cm']["CBCS"]["Online Course"]["Approve Registration"] = site_url('cbcs_subject_master/subject_master_dept/approve_online_course');

		
		
		return $menu;
	}
}
