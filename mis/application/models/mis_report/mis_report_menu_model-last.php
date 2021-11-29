<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Mis_report_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();



        $menu['admin_exam'] = array();
        $menu['admin_exam']['MIS Report'] = array();
        $menu['admin_exam']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['admin_exam']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['admin_exam']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['admin_exam']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['admin_exam']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');
        $menu['admin_exam']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');
        $menu['admin_exam']['MIS Report']['Marks Upload Status'] = site_url('mis_report/marks_upload_status');
        $menu['admin_exam']['MIS Report']['Pass Fail List'] = site_url('mis_report/pass_fail_list');
        $menu['admin_exam']['MIS Report']['Summer Subject & Student'] = site_url('mis_report/summer_subject_student');
        //  $menu['admin_exam']['MIS Report']['Performance List'] = site_url('mis_report/performace_list');



        $menu['exam_da1'] = array();
        $menu['exam_da1']['MIS Report'] = array();
        //     $menu['exam_da1']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['exam_da1']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['exam_da1']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['exam_da1']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        //     $menu['exam_da1']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');


/* 24-10-2019
        $menu['acad_ar'] = array();
        $menu['acad_ar']['MIS Report'] = array();
        //  $menu['acad_ar']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['acad_ar']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        //   $menu['acad_ar']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        //     $menu['acad_ar']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        //     $menu['acad_ar']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');



        $menu['exam_dr'] = array();
        $menu['exam_dr']['MIS Report'] = array();
        //     $menu['exam_dr']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['exam_dr']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        //     $menu['exam_dr']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        //      $menu['exam_dr']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        //     $menu['exam_dr']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');
        $menu['exam_dr']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');







        /*   $menu['exam_dr'] = array();
          $menu['exam_dr']['MIS Report'] = array();
          $menu['exam_dr']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');


          $menu['exam_da1'] = array();
          $menu['exam_da1']['MIS Report'] = array();
          $menu['exam_dr']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');

          $menu['acad_ar'] = array();
          $menu['acad_ar']['MIS Report'] = array();
          $menu['exam_dr']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
         */

*/


        return $menu;
    }

}
