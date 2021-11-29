<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Dealing_assistant_acad_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
		//Dealing Assistant Academic 720
        $menu = array();
			
			$menu['da_acad']['Student Admission JEE']["Upload Student Database"] = site_url('new_student_admission/db_upload_jee');
		      $menu['da_acad']['Student Admission JEE']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_jee');
		      $menu['da_acad']['Student Admission JEE']["Course Details"] = site_url('new_student_admission/course_edit_jee');
		  
		  
		  //mtech
		      $menu['da_acad']['Student Admission M.Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_mtech');
		      $menu['da_acad']['Student Admission M.Tech']["Student MIS Registration M.Tech 2 Years"] = site_url('new_student_admission/student_register_deo_mtech');
              $menu['da_acad']['Student Admission M.Tech']["Student MIS Registration M.Tech 3 Years"] = site_url('new_student_admission/student_register_deo_mtech_3years');	
		   
		   
		   //mba/ex-mba
		     $menu['da_acad']['Student Admission MBA/EX-MBA']["Upload Student Database"] = site_url('new_student_admission/db_upload_mba');
		     $menu['da_acad']['Student Admission MBA/EX-MBA']["Student MIS Registration MBA"] = site_url('new_student_admission/student_register_deo_mba');
             $menu['da_acad']['Student Admission MBA/EX-MBA']["Student MIS Registration EX-MBA"] = site_url('new_student_admission/student_register_deo_execmba');
		     
		   
		   
		   //msc
		     $menu['da_acad']['Student Admission MSc/MSc-Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_msc');
		     $menu['da_acad']['Student Admission MSc/MSc-Tech']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_msc');
			 
			/*  $menu['da_acad']['Student Admission MSc/MSc-Tech']['Student Admission MSc-Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_msc_tech');
		     $menu['da_acad']['Student Admission MSc/MSc-Tech']['Student Admission MSc-Tech']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_msc_tech');*/
		   
		   
		   // others
		     
		     $menu['da_acad']['Student Admission Others']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_others');
		 
		
		//jrf
		//$menu['new_adm']['Student Admission JRF']["Upload Student Database"] = site_url('new_student_admission/db_upload_jrf');
		     $menu['da_acad']['Student Admission JRF']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_jrf');
		

        return $menu;
    }

}
