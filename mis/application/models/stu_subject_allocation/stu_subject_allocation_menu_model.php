<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Stu_subject_allocation_menu_model extends CI_Model
{
	function __construct() {
        parent::__construct();
    }

	function getMenu()
	{
		// $menu = array();
		$menu['dean_acad'] = array();
		$menu['dean_acad']['Student Subject Allocation'] = array();
		$menu['dean_acad']['Student Subject Allocation']['Assign Subjects'] = site_url('stu_subject_allocation/select_session');
		$menu['dean_acad']['Student Subject Allocation']['Student List'] = site_url('stu_subject_allocation/student_list');
		return $menu;
	}
}