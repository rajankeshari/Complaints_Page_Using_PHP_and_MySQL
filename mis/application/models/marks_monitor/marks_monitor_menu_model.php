<?php

/**
 * Attendance sheet generation for exam (Menu Creation to access th sub-module)
 * Copyright (c) ISM dhanbad * 
 * @category   PHPExcel
 * @package    Marks_Monitor
 * @copyright  Copyright (c) 2015 - 2016 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #6/11/15#
 * @Author     Rohit Rana<rohitkkrana@gmail.com>
 */

if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Marks_monitor_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
        function getMenu()
	{
		$menu=array();		
        //$menu['admin_exam']=array();
		//$menu['admin_exam']["Marks Monitor"] = site_url('marks_monitor/monitor_main');	
		//$menu['exam_dr']["Marks Monitor"] = site_url('marks_monitor/monitor_main');	
		$menu['dean_acad']["Marks Monitor"] = site_url('marks_monitor/monitor_main');	
		//$menu['exam_da1']["Marks Monitor"] = site_url('marks_monitor/monitor_main');	
		
		
		//$menu['dean_acad'] = array();
        //$menu['dean_acad']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');

        $menu['dt'] = array();
        $menu['dt']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		
		//$menu['adug'] = array();
        //$menu['adug']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');

        //$menu['adpg'] = array();
        //$menu['adpg']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');
		
		/*$menu['adac'] = array();
        $menu['adac']['Grade Submission']['Status'] = site_url('marks_monitor/cbcs_monitor_marks_submission');*/
			
			
		return $menu;                                                
	}
}