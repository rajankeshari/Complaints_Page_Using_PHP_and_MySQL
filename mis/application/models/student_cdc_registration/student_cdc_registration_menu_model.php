<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Student_cdc_registration_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();


        $menu['stu'] = array();
        $menu['stu']['CDC Registration'] = array();
       // $menu['hos_stu_admin']['Hostel Booking']['Manage Hostel Name'] = site_url('hs_reg/hostel/manage_hostel_name');
        $menu['stu']['CDC Registration']['CDC Registration'] = site_url('student_cdc_registration/view');
        $menu['stu']['CDC Registration']['Change Password'] = site_url('student_cdc_registration/reports/change_pass_page');
        //$menu['admin']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');

      //$menu['admin']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');

      $menu['tpo'] = array();
      $menu['tpo']['CDC Registration'] = site_url('student_cdc_registration/view');

        

        return $menu;
    }

}
