<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class offered_courses_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
       // $menu['hod'] = array();
     //  	$menu['hod']['Academic'] = array();
	 //   $menu['hod']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	//    $menu['dugc'] = array();
    //   	$menu['dugc']['Academic'] = array();
	 //   $menu['dugc']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	 //   $menu['dpgc'] = array();
     //  	$menu['dpgc']['Academic'] = array();
	  //  $menu['dpgc']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	 //   $menu['dean_acad'] = array();
      // 	$menu['dean_acad']['Academic'] = array();
	//    $menu['dean_acad']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	//   $menu['adpg'] = array();
    //   	$menu['adpg']['Academic'] = array();
	//    $menu['adpg']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	//    $menu['adug'] = array();
   //    	$menu['adug']['Academic'] = array();
	//    $menu['adug']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	//    $menu['adac'] = array();
    //   	$menu['adac']['Academic'] = array();
	//    $menu['adac']['Academic']['Courses Offered'] = site_url('cbcs_offered_courses/offered_courses');

	    $menu['dt']['Offered Courses'] = site_url('cbcs_offered_courses/offered_courses');



	    return $menu;

    }
}
?>