<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Course_coordinator_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

		$menu['dean_acad'] = array();
      
        $menu['dean_acad']['Marks/Grade Reopen Control']['Grade'] = site_url('course_coordinator/cbcs_marks_upload_control');
        $menu['dean_acad']['Marks/Grade Reopen Control']['Marks Upload'] = site_url('course_coordinator/cbcs_marks_upload_control/marks_upload');


        
        //$menu['dean_acad'] = array();
      //  $menu['dean_acad']['Grade Statistics'] = site_url('course_coordinator/cbcs_monitor_marks_submission');

      //  $menu['dt'] = array();
      //  $menu['dt']['Grade Statistics'] = site_url('course_coordinator/cbcs_monitor_marks_submission');
     

		$menu['dean_acad'] = array();
        $menu['dean_acad']['Grade Statistics'] = array();
        $menu['dean_acad']['Grade Statistics']['Specific Year'] = site_url('course_coordinator/cbcs_monitor_marks_submission/');
        $menu['dean_acad']['Grade Statistics']['Overall'] = site_url('course_coordinator/cbcs_monitor_marks_submission/ft_wise_all');
        //$menu['dean_acad']['Marks Upload Control']['Grade'] = site_url('course_coordinator/cbcs_marks_upload_control');
        //$menu['dean_acad']['Marks Upload Control']['Marks Upload'] = site_url('course_coordinator/cbcs_marks_upload_control/marks_upload');

        $menu['dt'] = array();
        $menu['dt']['Grade Statistics'] = array();
        $menu['dt']['Grade Statistics']['Specific Year'] = site_url('course_coordinator/cbcs_monitor_marks_submission');
        $menu['dt']['Grade Statistics']['Overall'] = site_url('course_coordinator/cbcs_monitor_marks_submission/ft_wise_all');

		//$menu['adac']['Course Coordinator Details'] = site_url('course_coordinator/course_coordinator/courseCoordinatorDetails');

        return $menu;
    }

}
