<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Summer_reg_report_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

       /* 24-10-2019

	   $menu['exam_dr'] = array();
        $menu['exam_dr']['Summer Registraiton Report'] = array();
        $menu['exam_dr']['Summer Registraiton Report']['Summer Report'] = site_url('summer_reg_report/summer_reg_report');
        $menu['exam_dr']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['exam_dr']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['exam_dr']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['exam_dr']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
        $menu['exam_dr']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');
//subject_mapping_report
        // Dipankar sir
        $menu['admin_exam'] = array();
        $menu['admin_exam']['Summer Registraiton Report'] = array();
        $menu['admin_exam']['Summer Registraiton Report']['Summer Report']  = site_url('summer_reg_report/summer_reg_report');
        $menu['admin_exam']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['admin_exam']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['admin_exam']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
	$menu['admin_exam']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
        $menu['admin_exam']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');
        $menu['admin_exam']['Summer Registraiton Report']['All Report'] = site_url('summer_reg_report/allreport');

        // Choudhary ji
        $menu['exam_da1'] = array();
        $menu['exam_da1']['Summer Registraiton Report'] = array();
        $menu['exam_da1']['Summer Registraiton Report']['Summer Report']  = site_url('summer_reg_report/summer_reg_report');
        $menu['exam_da1']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['exam_da1']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['exam_da1']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['exam_da1']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
         $menu['exam_da1']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');

        // Acad
        $menu['acad_ar'] = array();
        $menu['acad_ar']['Summer Registraiton Report'] = array();
        $menu['acad_ar']['Summer Registraiton Report']['Summer Report']  = site_url('summer_reg_report/summer_reg_report');
        $menu['acad_ar']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['acad_ar']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['acad_ar']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['acad_ar']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
         $menu['acad_ar']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');

		 
		  // Dean Acad Mohanti Sir //
        $menu['dean_acad'] = array();
        $menu['dean_acad']['Summer Registraiton Report'] = array();
        $menu['dean_acad']['Summer Registraiton Report']['Summer Report']  = site_url('summer_reg_report/summer_reg_report');
        $menu['dean_acad']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['dean_acad']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['dean_acad']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['dean_acad']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
         $menu['dean_acad']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');

		 //Associate Dean
		 
		 $menu['adug'] = array();
        $menu['adug']['Summer Registraiton Report'] = array();
        $menu['adug']['Summer Registraiton Report']['Summer Report'] = site_url('summer_reg_report/summer_reg_report');
        $menu['adug']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['adug']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['adug']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['adug']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
        $menu['adug']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');
		
		$menu['adpg'] = array();
        $menu['adpg']['Summer Registraiton Report'] = array();
        $menu['adpg']['Summer Registraiton Report']['Summer Report'] = site_url('summer_reg_report/summer_reg_report');
        $menu['adpg']['Summer Registraiton Report']['Fail List'] = site_url('summer_reg_report/fail_list');
        $menu['adpg']['Summer Registraiton Report']['Comparision Report'] = site_url('summer_reg_report/comparision_report');
        $menu['adpg']['Summer Registraiton Report']['Subject Wise Report'] = site_url('summer_reg_report/subject_wise_report');
		$menu['adpg']['Summer Registraiton Report']['Subject Mapping Status'] = site_url('summer_reg_report/subject_mapping_report');
        $menu['adpg']['Summer Registraiton Report']['Other Report'] = site_url('summer_reg_report/other_report');		
*/ 
        return $menu;
    }

}
