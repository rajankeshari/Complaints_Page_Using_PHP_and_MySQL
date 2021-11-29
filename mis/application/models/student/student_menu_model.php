<?php

class Student_menu_model extends CI_Model
{
	function __construct()
	{
		
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		
		$menu['stu']['Academic']['Attendance'] = site_url('attendance/student_attendance');
		$menu['stu']['Academic']['Class Materials'] = site_url('faculty_tutorials/student_choose_syear_sess');
		$menu['stu']['Academic']['Offered Courses']['Session-wise']= site_url('cbcs_offered_courses/offered_courses');
		$menu['stu']['Academic']['Offered Courses']['Course Structure']= site_url('course_structure/view/index/cse');
		$menu['stu']['Academic']['Time Table']['CBCS']= site_url('time_table/view_time_table');
		$menu['stu']['Academic']['Time Table']['Non-CBCS']= site_url('time_table/view_time_table_old');

		$menu['stu']['Bookings']['Hostel Booking']['Received OTP']= site_url('hs_reg/hostel_booking/received_otp');
		$menu['stu']['Bookings']['Hostel Booking']['Choose Room Partner']= site_url('hs_reg/hostel_booking/choose_room_partner');
		$menu['stu']['Bookings']['Hostel Booking']['Enter OTP']= site_url('hs_reg/hostel_booking/enter_otp');
		$menu['stu']['Bookings']['Hostel Booking']['Room Booking']= site_url('hs_reg/hostel_booking/book_room');
		$menu['stu']['Bookings']['Hostel Booking']['View Booked Room']= site_url('hs_reg/hostel_booking/completed_room_booking');
		$menu['stu']['Bookings']['Hostel Booking']['Sent Request']= site_url('hs_reg/hostel_booking/success_otp');
		$menu['stu']['Bookings']['SAH Booking']['Booking Form']= site_url('sah_booking/booking/form');
		$menu['stu']['Bookings']['SAH Booking']['Track Booking']= site_url('sah_booking/booking/track_status');
		$menu['stu']['Bookings']['SAH Booking']['Booked History']= site_url('sah_booking/booking/history');
		$menu['stu']['Bookings']['Sports']['Group']['Create Group'] = site_url('spo_section/group');
		$menu['stu']['Bookings']['Sports']['Group']['Group Request'] = site_url('spo_section/group/group_request_list');
		$menu['stu']['Bookings']['Sports']['Group']['Rejected Group'] = site_url('spo_section/group/view_rejected_group');
		$menu['stu']['Bookings']['Sports']['Group']['View Group'] = site_url('spo_section/group/view_group');
		$menu['stu']['Bookings']['Sports']['Issue Item'] = site_url('spo_section/issue_item');
		$menu['stu']['Bookings']['Sports']['View History'] = site_url('spo_section/issue_history/view_current_history');
		$menu['stu']['Bookings']['Swimming Booking']['Registration'] = site_url('swimming/student_registration');
		$menu['stu']['Bookings']['Swimming Booking']['Status'] = site_url('swimming/student_registration/view_status');

		$menu['stu']['Disciplinary Action'] = site_url('dsw_disciplinary_action/get_student_details/student_actions');   
		
		//$menu['stu']['Examination']['Grade Sheets'][ '2019 Onwards'] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_final_foil ');   
		//$menu['stu']['Examination']['Grade Sheets'][ '2015 to 2018'] = site_url('student_grade_sheet/grade_sheet_final_foil ');  
		//$menu['stu']['Examination']['Grade Sheets'] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_final_foil/get_grade_report'); 		
		
		$menu['stu']['Examination']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');   
		$menu['stu']['Examination']['Weightage & Marks View'] = site_url('student/student_subject_weightage');   
		
		$menu['stu']['Feedback'] ['2019 Onwards']['Semester Feedback Receipt'] = site_url('feedback_cbcs/cbcs_student/semester_feedback_receipt_form');
		$menu['stu']['Feedback'] ['2019 Onwards']['Exit Feedback Receipt'] = site_url('feedback_cbcs/cbcs_student/exit_feedback_receipt');
		//$menu['stu']['Feedback'] ['2019 Onwards']['Semester Feedback'] = site_url('feedback_cbcs/cbcs_check_feedback_subjects');
		//$menu['stu']['Feedback']   ['2019 Onwards']['Exit Feedback'] = site_url('feedback_cbcs/cbcs_student/fill_exit_feedback');
		$menu['stu']['Feedback'] ['2015 to 2018']['Semester Feedback Receipt'] = site_url('feedback/student/semester_feedback_receipt_form');
		$menu['stu']['Feedback'] ['2015 to 2018']['Exit Feedback Receipt'] = site_url('feedback/student/exit_feedback_receipt');
		
		// $menu['stu']['Miscellaneous']['Complaint General']['Register Complaint']= site_url('complaint/register_complaint');
		// $menu['stu']['Miscellaneous']['Complaint General']['View Complaints']= site_url('complaint/view_own_complaint');
		// $menu['stu']['Miscellaneous']['Complaint General']['Add new Complaints']= site_url('complaint/add_new_complaint');
		$menu['stu']['Complaint General']['Register Complaint']= site_url('complaint/register_complaint');
		$menu['stu']['Complaint General']['View Complaints']= site_url('complaint/view_own_complaint');
		$menu['stu']['Complaint General']['Add new Complaints']= site_url('complaint/add_new_complaint');
		$menu['stu']['Miscellaneous']['Edit Hindi Name']= site_url('student/student_ctl');
		$menu['stu']['Miscellaneous']['Edit Photo']= site_url('change_photo/student_change_photo');
		$menu['stu']['Miscellaneous']['Register Email ID']= site_url('register_mail_id/register_mail_id_main');
		$menu['stu']['Miscellaneous']['Set/Reset Security Questions']= site_url('sec_inside/sec_inside_controller/change_security_questions');

		$menu['stu']['Notices & Circulars']['Notice'] = site_url('information/view_notice');
		$menu['stu']['Notices & Circulars']['Circular'] = site_url('information/view_circular');
		
		//$menu['stu']['Placement']['Fill CV'] = site_url('tnpcell/cv');
		$menu['stu']["Project Assignment"] = site_url('project_assignment/stu_project_assign');
		
		$menu['stu']['Registration']['Semester Registration'] = site_url('semreg_all/semester_registration_tabs');
		$menu['stu']['Registration']['Online Courses'] = site_url('student_sem_form_all/online_reg_course');
		
		$menu['stu']['Your Details'] = site_url('student_view_report/view');
		
		
		return $menu;
	}
}
