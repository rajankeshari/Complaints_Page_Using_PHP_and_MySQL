<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Student_registration_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
       $menu = array();
       //$menu['hod'] = array();
  	  // $menu['hod']['Student Registration'] = site_url('student_registration/student_registration');

       $menu['dpgc'] = array();
       $menu['dpgc']['Student Registration'] = site_url('student_registration/student_registration');

       $menu['dugc'] = array();
       $menu['dugc']['Student Registration'] = site_url('student_registration/student_registration');

       //$menu['dean_acad'] = array();
       //$menu['dean_acad']['Student Registration'] = site_url('student_registration/student_registration');

       //$menu['adpg'] = array();
       //$menu['adpg']['Student Registration'] = site_url('student_registration/student_registration');

       //$menu['adug'] = array();
      // $menu['adug']['Student Registration'] = site_url('student_registration/student_registration');

       $menu['exam_dr'] = array();
       $menu['exam_dr']['Student Registration'] = site_url('student_registration/student_registration');

       $menu['acad_dr'] = array();
       $menu['acad_dr']['Student Registration'] = site_url('student_registration/student_registration');

       $menu['acad_ar'] = array();
       $menu['acad_ar']['Student Registration'] = site_url('student_registration/student_registration');

      // $menu['stu_reg'] = array();
      // $menu['stu_reg']['Student Registration'] = site_url('student_registration/student_registration');

      return $menu;
    }

}
