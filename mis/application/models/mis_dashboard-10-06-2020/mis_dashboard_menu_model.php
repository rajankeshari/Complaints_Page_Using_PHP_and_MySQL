<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class mis_dashboard_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        //mis_db
        $menu['mis_db'] = array();
      //  $menu['mis_db']['Delete Elective'] = site_url('mis_dashboard/delete_elective_regular');
      //  $menu['mis_db']['View Offered Elective'] = site_url('mis_dashboard/view_offered_elective');
        $menu['mis_db']['Faculty Subject'] = site_url('mis_dashboard/faculty_subject');
      //  $menu['mis_db']['Add Elective'] = site_url('mis_dashboard/add_elective_semwise');
        $menu['mis_db']['Registration'] = site_url('mis_dashboard/stu_registration');
        $menu['mis_db']['Registration History'] = site_url('mis_dashboard/stu_registration_history');
        $menu['mis_db']['JRF Paper'] = site_url('mis_dashboard/check_jrf_paper');
        $menu['mis_db']['Help Desk']= array();
        $menu['mis_db']['Help Desk']['Upload Document'] = site_url('mis_dashboard/upload_help_document');
        $menu['mis_db']['Help Desk']['View Document'] = site_url('mis_dashboard/view_help_document');
        $menu['mis_db']['Help Desk']['Delete Document'] = site_url('mis_dashboard/delete_help_document');
        $menu['mis_db']['Subject Faculty'] = site_url('mis_dashboard/subject_faculty');
		$menu['mis_db']['HOD'] = site_url('mis_dashboard/mis_hod');




        //mis_db_exam

        $menu['mis_db_exam'] = array();
       // $menu['mis_db_exam']['View Offered Elective'] = site_url('mis_dashboard/view_offered_elective');
        $menu['mis_db_exam']['Faculty Subject'] = site_url('mis_dashboard/faculty_subject');
       // $menu['mis_db_exam']['Add Elective'] = site_url('mis_dashboard/add_elective_semwise');
        $menu['mis_db_exam']['Registration'] = site_url('mis_dashboard/stu_registration');
        $menu['mis_db_exam']['Registration History'] = site_url('mis_dashboard/stu_registration_history');
        
        //$menu['mis_db_exam']['Help Desk']= array();
        //$menu['mis_db_exam']['Help Desk']['Upload Document'] = site_url('mis_dashboard/upload_help_document');
        //$menu['mis_db_exam']['Help Desk']['View Document'] = site_url('mis_dashboard/view_help_document');
        //$menu['mis_db_exam']['Help Desk']['Delete Document'] = site_url('mis_dashboard/delete_help_document');
        $menu['mis_db_exam']['JRF Paper'] = site_url('mis_dashboard/check_jrf_paper');
        $menu['mis_db_exam']['Subject Faculty'] = site_url('mis_dashboard/subject_faculty');
        $menu['mis_db_exam']['HOD'] = site_url('mis_dashboard/mis_hod');
		
		
        $menu['mis_db_da'] = array();
		$menu['mis_db_da']['Registration'] = site_url('mis_dashboard/stu_registration');
        $menu['mis_db_da']['Registration History'] = site_url('mis_dashboard/stu_registration_history');
		
		
		//$menu['ft'] = array();
		//$menu['ft']['Student Registration'] = site_url('mis_dashboard/stu_registration');
        //$menu['ft']['Student Registration History'] = site_url('mis_dashboard/stu_registration_history');
		
        



        return $menu;

            //          insert into auth_types(id,type)values('mis_db','MIS Dashboard');
            //          insert into user_auth_types(id,auth_id)values('mis-anuj','mis_db'); 
    }

}
