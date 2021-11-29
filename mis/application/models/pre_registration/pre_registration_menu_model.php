<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Pre_registration_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
       $menu = array();
      // $menu['hod'] = array();
       //$menu['hod']['Pre Registration'] = site_url('pre_registration/student_registration');

     //  $menu['dpgc'] = array();
     //  $menu['dpgc']['Pre Registration'] = site_url('pre_registration/student_registration');

     //  $menu['dugc'] = array();
    //   $menu['dugc']['Pre Registration'] = site_url('pre_registration/student_registration');

       //$menu['dean_acad'] = array();
       //$menu['dean_acad']['Pre Registration'] = site_url('pre_registration/student_registration');

       //$menu['adpg'] = array();
       //$menu['adpg']['Pre Registration'] = site_url('pre_registration/student_registration');

       //$menu['adug'] = array();
      // $menu['adug']['Pre Registration'] = site_url('pre_registration/student_registration');

     //  $menu['exam_dr'] = array();
    //   $menu['exam_dr']['Pre Registration'] = site_url('pre_registration/student_registration');

    //   $menu['acad_dr'] = array();
     //  $menu['acad_dr']['Pre Registration'] = site_url('pre_registration/student_registration');

    //   $menu['acad_ar'] = array();
    //   $menu['acad_ar']['Pre Registration'] = site_url('pre_registration/student_registration');

       //$menu['stu_reg'] = array();
       //$menu['stu_reg']['Pre Registration'] = site_url('pre_registration/student_registration');

      return $menu;
    }

}
