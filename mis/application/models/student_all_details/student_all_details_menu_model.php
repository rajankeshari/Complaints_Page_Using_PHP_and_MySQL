<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Student_all_details_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();



       // $menu['admin_exam'] = array();
       // $menu['admin_exam']['Student All Details'] = array();
      //  $menu['admin_exam']['Student All Details']['Registration Details'] = site_url('student_all_details/registration_details');
      //  $menu['admin_exam']['Student All Details']['Edit Registration'] = site_url('student_all_details/edit_registration_student');
        
      //  $menu['exam_dr'] = array();
      //  $menu['exam_dr']['Student All Details'] = array();
      //  $menu['exam_dr']['Student All Details']['Registration Details'] = site_url('student_all_details/registration_details');
       // $menu['exam_dr']['Student All Details']['Edit Registration'] = site_url('student_all_details/edit_registration_student');
        
        
        
        
       


        


        return $menu;
    }

}
