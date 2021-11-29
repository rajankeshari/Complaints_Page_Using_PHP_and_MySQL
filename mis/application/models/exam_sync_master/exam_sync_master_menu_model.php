<?php

/**
 * Exam data  for each student to  be populated to result table on tabulation database placed  to another server (to process result by dipanker sir))
 * Copyright (c) ISM dhanbad * 
 * @category   sync tables
 * @package    exam_attendance
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #19/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */


if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Exam_sync_master_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
        function getMenu()
	{
		$menu=array();		
                $menu['admin_exam']=array();
		$menu['admin_exam']["Sync"] = site_url('exam_sync_master/exam_sync_master');		
		return $menu;                                                
	}
}