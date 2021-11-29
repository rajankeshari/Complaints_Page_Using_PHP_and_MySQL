<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Stu_add_elective_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

      
        
    /* $menu['exam_da1'] = array();
	
	$menu['exam_da1']['Add Elective/Student Syllabus'] = site_url('stu_add_elective/student_elective');
	
	
	$menu['fa'] = array();
	$menu['fa']['Add Elective/Student Syllabus'] = site_url('stu_add_elective/student_elective');
	
	$menu['hod'] = array();
	$menu['hod']['Add Elective/Student Syllabus'] = site_url('stu_add_elective/student_elective');
	*/
        



        return $menu;
    }

}
