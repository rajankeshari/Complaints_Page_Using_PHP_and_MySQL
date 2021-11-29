<?php

/**
 * Attendance sheet generation for exam (Menu Creation to access th sub-module)
 * Copyright (c) ISM dhanbad * 
 * @category   PHPExcel
 * @package    exam_attendance
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #6/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Exam_attendance_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
        function getMenu()
	{
		$menu=array();		
        $menu['exam_dr']=array();
		$menu['exam_dr']["Exam Attendance"] = site_url('exam_attendance/exam_attd');	
		$menu['exam_da1']["Exam Attendance"] = site_url('exam_attendance/exam_attd');	
		$menu['exam_da2']["Exam Attendance"] = site_url('exam_attendance/exam_attd');	
		$menu['admin_exam']["Exam Attendance"] = site_url('exam_attendance/exam_attd');	
		$menu['acad_exam']["Exam Attendance"] = site_url('exam_attendance/exam_attd');	
		
			
		return $menu;                                                
	}
}