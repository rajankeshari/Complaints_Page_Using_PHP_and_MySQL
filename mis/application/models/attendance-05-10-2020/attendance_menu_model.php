F<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class attendance_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> ft
	///	$menu['ft']=array();
	//	$menu['ft']['Attendance Sheet']=array();

		//$menu['ft']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/attendance');
		//$menu['stu']['Attendance'] = site_url( 'attendance/student_attendance');
		/*$menu['dsw']['Defaulter List'] = site_url('attendance/dsw_attendance_controller');
		$menu['adsw']['Defaulter List'] = site_url('attendance/dsw_attendance_controller');*/

		//$menu['dsw']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		//$menu['adsw']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		//$menu['acad_ar']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
	//	$menu['exam_dr']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['admr']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		$menu['dt']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		//$menu['adug']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		//$menu['adpg']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		//$menu['dean_acad']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		
		//$menu['acad_ar']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
	//	$menu['acad_dr']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
	//	$menu['dpgc']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
	//	$menu['dugc']['Student Attendance'] = site_url( 'attendance/student_attendance/addCustomAttendance');
		

		//$menu['ft']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/cbcs_attendance/');
		// $menu['ft']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/attendance/'); //closed on 24-12-2019 @anuj
      //  $menu['ft']['Attendance Sheet']['Upload Attendance Sheet'] = site_url('upload/upload_attendance/');
	 //	$menu['ft']['Attendance Sheet']['Upload Attendance Sheet'] = site_url('upload/cbcs_upload_attendance/');

        //$menu['ft']['Teaching Assistant'] = site_url('attendance/faculty_ta_mapping_status/');

        //$menu['ta']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/attendance_ta/');
        //$menu['ta']['Attendance Sheet']['Upload Attendance Sheet'] = site_url('upload/upload_attendance_ta/');
                
              // $menu['hod']['Delete Attendance']= site_url('attendance/cbcs_delete_attendance/');
			  
			  //exam attendance menus
	//	$menu['ft']['Attendance Sheet']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/');	  
	    //$menu['hod']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_hod');
		//$menu['adug']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		//$menu['adpg']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
	//	$menu['mis_db_exam']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
	//	$menu['exam_dr']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
	//	$menu['exam_da1']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_index');
		//$menu['dpgc']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_hod');
		//$menu['dugc']['Exam Attendance Sheet'] = site_url('attendance/exam_attendance/exam_attendance_hod');
		
		
		
		
		//===============defaulter=========================================
		
                
                
              //$menu['dean_acad']=array();
		//$menu['dean_acad']['Defaulter']=array();
		//$menu['dean_acad']['Defaulter']['Institute Percentage'] = site_url('attendance/defaulter_institute_percentage');
		//$menu['dean_acad']['Defaulter']['Student Percentage']['Add'] = site_url('attendance/defaulter_student_percentage');
		//$menu['dean_acad']['Defaulter']['Student Percentage']['View'] = site_url('attendance/defaulter_student_percentage_newview');
		//$menu['dean_acad']['Defaulter']['Process List'] = site_url('attendance/defaulter_process_list_new');
        //$menu['dean_acad']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		
		//$menu['adpg']=array();
		//$menu['adpg']['Defaulter']=array();
		//$menu['adpg']['Defaulter']['Institute Percentage'] = site_url('attendance/defaulter_institute_percentage');
		//$menu['adpg']['Defaulter']['Student Percentage']['Add'] = site_url('attendance/defaulter_student_percentage');
		//$menu['adpg']['Defaulter']['Student Percentage']['View'] = site_url('attendance/defaulter_student_percentage_newview');
		//$menu['adpg']['Defaulter']['Process List'] = site_url('attendance/defaulter_process_list_new');
        //$menu['adpg']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		
		//$menu['adug']=array();
		//$menu['adug']['Defaulter']=array();
		//$menu['adug']['Defaulter']['Institute Percentage'] = site_url('attendance/defaulter_institute_percentage');
		//$menu['adug']['Defaulter']['Student Percentage']['Add'] = site_url('attendance/defaulter_student_percentage');
		//$menu['adug']['Defaulter']['Student Percentage']['View'] = site_url('attendance/defaulter_student_percentage_newview');
		//$menu['adug']['Defaulter']['Process List'] = site_url('attendance/defaulter_process_list_new');
        //$menu['adug']['Defaulter']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		
		
		
		//===================defaulter end======================================

		return $menu;
	}
}