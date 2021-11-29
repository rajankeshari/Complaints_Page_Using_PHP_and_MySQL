<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

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
		$menu['ft']=array();
		$menu['ft']['Attendance Sheet']=array();

		//$menu['ft']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/attendance');
		$menu['stu']['Attendance'] = site_url( 'attendance/student_attendance');
		$menu['dsw']['Defaulter List'] = site_url('attendance/dsw_attendance_controller');
		$menu['acad_ar']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');
		$menu['exam_dr']['Defaulter List'] = site_url('attendance/ar_acad_attendance_controller');

		$menu['ft']['Attendance Sheet']['Generate Sheet'] = site_url('attendance/attendance/');
		$menu['ft']['Attendance Sheet']['Upload Attendance Sheet'] = site_url('upload/upload_attendance/');
		
		


		return $menu;
	}
}