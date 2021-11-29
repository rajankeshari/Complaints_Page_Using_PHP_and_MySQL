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

        $menu['admin_exam']['MIS Report']['Student']['Admission Report'] = site_url('mis_report/user_details_report');
        $menu['admin_exam']['MIS Report']['Student']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');

//28-10-2019

       /* $menu['admin_exam'] = array();
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
        $menu['admin_exam']['MIS Report']['Change Branch'] = site_url('mis_report/change_branch_report');
        $menu['admin_exam']['MIS Report']['Common Student'] = site_url('mis_report/common_student_report');
        $menu['admin_exam']['MIS Report']['Common Student Subject Reprot'] = site_url('mis_report/common_student_subject_report');
		*/
        //$menu['admin_exam']['MIS Report']['Summer Subject Difference Reprot'] = site_url('mis_report/summer_subject_diff_report');
        //28-10-2019
		/*$menu['admin_exam']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
        $menu['admin_exam']['MIS Report']['Student']['Exam Report'] = site_url('mis_report/final_foil_report');
        $menu['admin_exam']['MIS Report']['Student']['PREP LIST'] = site_url('mis_report/prep_report');
        $menu['admin_exam']['MIS Report']['Student']['Admission Report'] = site_url('mis_report/user_details_report');
		$menu['admin_exam']['MIS Report']['Student']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');*/
        //$menu['admin_exam']['MIS Report']['Student']['Regular Registration Form Report'] = site_url('mis_report/reg_registration_report');
        
        //28-10-2019 $menu['admin_exam']['MIS Report']['Phone Number'] = site_url('mis_report/mis_office_no');
        //28-10-2019 $menu['admin_exam']['MIS Report']['Student With Image'] = site_url('mis_report/student_image_report');
        //  $menu['admin_exam']['MIS Report']['Performance List'] = site_url('mis_report/performace_list');



        $menu['exam_da1'] = array();
        $menu['exam_da1']['MIS Report'] = array();
        //     $menu['exam_da1']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
//28-10-2019     
	 /*  $menu['exam_da1']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['exam_da1']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['exam_da1']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['exam_da1']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
		$menu['exam_da1']['MIS Report']['Phone Number'] = site_url('mis_report/mis_office_no');
		$menu['exam_da1']['MIS Report']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');*/
        //     $menu['exam_da1']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');


		$menu['exam_da2'] = array();
		
		//28-10-2019
		/*
        $menu['exam_da2']['MIS Report'] = array();
        $menu['exam_da2']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['exam_da2']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['exam_da2']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['exam_da2']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
		$menu['exam_da2']['MIS Report']['Phone Number'] = site_url('mis_report/mis_office_no');
		$menu['exam_da2']['MIS Report']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');
       
		*/
		
		
		

        $menu['acad_ar'] = array();
		/* //28-10-2019
        $menu['acad_ar']['MIS Report'] = array();
        //  $menu['acad_ar']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['acad_ar']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['acad_ar']['MIS Report']['Change Branch'] = site_url('mis_report/change_branch_report');
		$menu['acad_ar']['MIS Report']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');*/
		
        //   $menu['acad_ar']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        //     $menu['acad_ar']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        //     $menu['acad_ar']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');



        //$menu['exam_dr'] = array();
        //$menu['exam_dr']['MIS Report'] = array();
        //     $menu['exam_dr']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
       // $menu['exam_dr']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        //     $menu['exam_dr']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        //      $menu['exam_dr']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        //     $menu['exam_dr']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');
//28-10-2019      
	  /*$menu['exam_dr']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');
        $menu['exam_dr']['MIS Report']['Change Branch'] = site_url('mis_report/change_branch_report');
        $menu['exam_dr']['MIS Report']['Common Student'] = site_url('mis_report/common_student_report');
	    $menu['exam_dr']['MIS Report']['Common Student Subject Reprot'] = site_url('mis_report/common_student_subject_report');
        $menu['exam_dr']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
        $menu['exam_dr']['MIS Report']['Student']['Exam Report'] = site_url('mis_report/final_foil_report');
        $menu['exam_dr']['MIS Report']['Student']['PREP LIST'] = site_url('mis_report/prep_report');
        $menu['exam_dr']['MIS Report']['Student']['Admission Report'] = site_url('mis_report/user_details_report');
	    $menu['admin_exam']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');
        $menu['exam_dr']['MIS Report']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');
        $menu['exam_dr']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['exam_dr']['MIS Report']['Pass Fail List'] = site_url('mis_report/pass_fail_list');*/

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
		//	$menu['tpo'] = array();
       //   $menu['tpo']['MIS Report'] = array();
        //  $menu['tpo']['MIS Report']['Pre Final/ Final List'] = site_url('mis_report/pass_fail_list/pre_final_final_criteria');

		
		
		//adug
		//28-10-2019
	/*	$menu['adug'] = array();
        $menu['adug']['MIS Report'] = array();
        $menu['adug']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['adug']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['adug']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['adug']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['adug']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');
        $menu['adug']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');
        $menu['adug']['MIS Report']['Marks Upload Status'] = site_url('mis_report/marks_upload_status');
        $menu['adug']['MIS Report']['Pass Fail List'] = site_url('mis_report/pass_fail_list');
        $menu['adug']['MIS Report']['Summer Subject & Student'] = site_url('mis_report/summer_subject_student');
        $menu['adug']['MIS Report']['Change Branch'] = site_url('mis_report/change_branch_report');
        $menu['adug']['MIS Report']['Common Student'] = site_url('mis_report/common_student_report');
        $menu['adug']['MIS Report']['Common Student Subject Reprot'] = site_url('mis_report/common_student_subject_report');
        //$menu['adug']['MIS Report']['Summer Subject Difference Reprot'] = site_url('mis_report/summer_subject_diff_report');
        $menu['adug']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
        $menu['adug']['MIS Report']['Student']['Exam Report'] = site_url('mis_report/final_foil_report');
        $menu['adug']['MIS Report']['Student']['PREP LIST'] = site_url('mis_report/prep_report');
        $menu['adug']['MIS Report']['Student']['Admission Report'] = site_url('mis_report/user_details_report');
		$menu['adug']['MIS Report']['Student']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');
        //$menu['adug']['MIS Report']['Student']['Regular Registration Form Report'] = site_url('mis_report/reg_registration_report');
        
        $menu['adug']['MIS Report']['Phone Number'] = site_url('mis_report/mis_office_no');
        
		
		
		
		
		//adpg
$menu['adpg'] = array();
        $menu['adpg']['MIS Report'] = array();
        $menu['adpg']['MIS Report']['Merit List'] = site_url('mis_report/cash_prize_list');
        $menu['adpg']['MIS Report']['Semester Registration'] = site_url('mis_report/semester_registration');
        $menu['adpg']['MIS Report']['Course Structure and Faculty'] = site_url('mis_report/course_structure_with_faculty');
        $menu['adpg']['MIS Report']['Final Year Student List'] = site_url('mis_report/final_year_passlist');
        $menu['adpg']['MIS Report']['Tequip Report'] = site_url('mis_report/tequip_report_list');
        $menu['adpg']['MIS Report']['Result Declaration Report'] = site_url('mis_report/result_publication_report');
        $menu['adpg']['MIS Report']['Marks Upload Status'] = site_url('mis_report/marks_upload_status');
        $menu['adpg']['MIS Report']['Pass Fail List'] = site_url('mis_report/pass_fail_list');
        $menu['adpg']['MIS Report']['Summer Subject & Student'] = site_url('mis_report/summer_subject_student');
        $menu['adpg']['MIS Report']['Change Branch'] = site_url('mis_report/change_branch_report');
        $menu['adpg']['MIS Report']['Common Student'] = site_url('mis_report/common_student_report');
        $menu['adpg']['MIS Report']['Common Student Subject Reprot'] = site_url('mis_report/common_student_subject_report');
        //$menu['adpg']['MIS Report']['Summer Subject Difference Reprot'] = site_url('mis_report/summer_subject_diff_report');
        $menu['adpg']['MIS Report']['Change Branch Form Print'] = site_url('mis_report/change_branch_print');
        $menu['adpg']['MIS Report']['Student']['Exam Report'] = site_url('mis_report/final_foil_report');
        $menu['adpg']['MIS Report']['Student']['PREP LIST'] = site_url('mis_report/prep_report');
        $menu['adpg']['MIS Report']['Student']['Admission Report'] = site_url('mis_report/user_details_report');
		$menu['adpg']['MIS Report']['Student']['Subject Mapping DEO'] = site_url('mis_report/subject_mapping_deo');
        //$menu['adpg']['MIS Report']['Student']['Regular Registration Form Report'] = site_url('mis_report/reg_registration_report');
        
        $menu['adpg']['MIS Report']['Phone Number'] = site_url('mis_report/mis_office_no');

		*/
		
		$menu['tequip'] = array();		
		$menu['tequip']['Tequip Report'] = site_url('mis_report/tequip_report_list');	
		
		//$menu['elec_fa'] = array();
        //$menu['elec_fa']['MIS Report'] = array();
		//$menu['elec_fa']['MIS Report']['Student With Image'] = site_url('mis_report/student_image_report');
		
		
        return $menu;
    }

}
