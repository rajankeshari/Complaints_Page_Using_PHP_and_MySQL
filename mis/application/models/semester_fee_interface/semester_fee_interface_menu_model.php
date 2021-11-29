<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Semester_fee_interface_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();      
	
    	// $menu['acad_ar'] = array();
     //    $menu['acad_ar']['Semester Fee Interface'] = array();
    	// $menu['acad_ar']['Semester Fee Interface'] = site_url('semester_fee_interface/semester_fee_interface');

        $menu['acad_dr'] = array();
        $menu['acad_dr']['Semester Fee Interface'] = array();
        $menu['acad_dr']['Semester Fee Interface']['All Student'] = site_url('semester_fee_interface/semester_fee_interface');
        $menu['acad_dr']['Semester Fee Interface']['Individual Student'] = site_url('semester_fee_interface/semester_fee_interface/student');
		
		
		$menu['sem_fee_admin'] = array();
        $menu['sem_fee_admin']['Semester Fee Interface'] = array();
        $menu['sem_fee_admin']['Semester Fee Interface']['All Student'] = site_url('semester_fee_interface/semester_fee_interface');
        $menu['sem_fee_admin']['Semester Fee Interface']['Individual Student'] = site_url('semester_fee_interface/semester_fee_interface/student');

        // $menu['exam_dr'] = array();
        // $menu['exam_dr']['Semester Fee Interface'] = array();
        // $menu['exam_dr']['Semester Fee Interface'] = site_url('semester_fee_interface/semester_fee_interface');

    return $menu;
    }

}
